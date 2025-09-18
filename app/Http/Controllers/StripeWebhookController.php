<?php

namespace App\Http\Controllers;

use App\Models\StripeEvent;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Handle Stripe webhook with idempotency check.
     */
    public function handleWebhook(Request $request): JsonResponse
    {
        $payload = $request->all();
        $eventId = $payload['id'] ?? null;
        $signature = $request->header('Stripe-Signature');

        // Verify webhook signature
        try {
            $event = Webhook::constructEvent(
                $request->getContent(),
                $signature,
                config('services.stripe.webhook_secret')
            );
        } catch (\Exception $e) {
            Log::error('Stripe webhook signature verification failed', [
                'error' => $e->getMessage(),
                'signature' => $signature
            ]);
            
            return response()->json(['message' => 'Invalid signature'], 400);
        }

        // Idempotency check - prevent double processing
        if (!$eventId || StripeEvent::where('event_id', $eventId)->exists()) {
            Log::info('Stripe event already processed', ['event_id' => $eventId]);
            return response()->json(['message' => 'Event already processed'], 200);
        }

        // Save event for idempotency
        StripeEvent::create([
            'event_id' => $eventId,
            'event_type' => $payload['type'],
            'payload' => $payload,
            'processed' => false
        ]);

        try {
            // Process the event
            $this->processEvent($payload);

            // Mark as processed
            StripeEvent::where('event_id', $eventId)->update(['processed' => true]);

            Log::info('Stripe webhook processed successfully', [
                'event_id' => $eventId,
                'event_type' => $payload['type']
            ]);

            return response()->json(['status' => 'success'], 200);

        } catch (\Exception $e) {
            Log::error('Stripe webhook processing failed', [
                'event_id' => $eventId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['status' => 'error', 'message' => 'Processing failed'], 500);
        }
    }

    /**
     * Process different types of Stripe events.
     */
    private function processEvent(array $payload): void
    {
        switch ($payload['type']) {
            case 'invoice.payment_succeeded':
                $this->handlePaymentSucceeded($payload);
                break;

            case 'invoice.payment_failed':
                $this->handlePaymentFailed($payload);
                break;

            case 'customer.subscription.created':
                $this->handleSubscriptionCreated($payload);
                break;

            case 'customer.subscription.updated':
                $this->handleSubscriptionUpdated($payload);
                break;

            case 'customer.subscription.deleted':
                $this->handleSubscriptionDeleted($payload);
                break;

            default:
                Log::info('Unhandled Stripe event type', ['type' => $payload['type']]);
        }
    }

    /**
     * Handle successful payment.
     */
    private function handlePaymentSucceeded(array $payload): void
    {
        $invoice = $payload['data']['object'];
        $customerId = $invoice['customer'];
        $amount = $invoice['amount_paid'];

        // Update order status
        Order::where('stripe_customer_id', $customerId)
            ->where('status', 'pending')
            ->update([
                'status' => 'paid',
                'stripe_invoice_id' => $invoice['id'],
                'paid_at' => now()
            ]);

        Log::info('Payment succeeded', [
            'customer_id' => $customerId,
            'amount' => $amount,
            'invoice_id' => $invoice['id']
        ]);
    }

    /**
     * Handle failed payment.
     */
    private function handlePaymentFailed(array $payload): void
    {
        $invoice = $payload['data']['object'];
        $customerId = $invoice['customer'];

        // Update order status
        Order::where('stripe_customer_id', $customerId)
            ->where('status', 'pending')
            ->update([
                'status' => 'failed',
                'stripe_invoice_id' => $invoice['id']
            ]);

        Log::warning('Payment failed', [
            'customer_id' => $customerId,
            'invoice_id' => $invoice['id']
        ]);
    }

    /**
     * Handle subscription created.
     */
    private function handleSubscriptionCreated(array $payload): void
    {
        $subscription = $payload['data']['object'];
        
        Log::info('Subscription created', [
            'subscription_id' => $subscription['id'],
            'customer_id' => $subscription['customer']
        ]);
    }

    /**
     * Handle subscription updated.
     */
    private function handleSubscriptionUpdated(array $payload): void
    {
        $subscription = $payload['data']['object'];
        
        Log::info('Subscription updated', [
            'subscription_id' => $subscription['id'],
            'status' => $subscription['status']
        ]);
    }

    /**
     * Handle subscription deleted.
     */
    private function handleSubscriptionDeleted(array $payload): void
    {
        $subscription = $payload['data']['object'];
        
        Log::info('Subscription deleted', [
            'subscription_id' => $subscription['id'],
            'customer_id' => $subscription['customer']
        ]);
    }
}
