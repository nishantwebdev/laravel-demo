# Laravel API Demo - Job Proposal Showcase

This Laravel 11 API demo project showcases advanced Laravel development skills including REST API endpoints, authentication with Laravel Sanctum, Stripe webhook handling with idempotency, and Eloquent eager loading optimization.

## 🚀 Features Demonstrated

### 1. **REST API Endpoints with CRUD Operations**
- **UserController.php** - Complete CRUD operations for user management
- Clean, well-structured API responses with proper HTTP status codes
- Input validation and error handling
- Pagination support

**Endpoints:**
- `GET /api/users` - List users with pagination
- `POST /api/users` - Create new user
- `GET /api/users/{id}` - Get specific user
- `PUT /api/users/{id}` - Update user
- `DELETE /api/users/{id}` - Delete user

### 2. **Authentication with Laravel Sanctum**
- **AuthController.php** - Complete authentication system
- Token-based authentication for API access
- User registration and login endpoints
- Token refresh and logout functionality

**Endpoints:**
- `POST /api/register` - User registration
- `POST /api/login` - User login with token generation
- `POST /api/logout` - Token revocation
- `GET /api/profile` - Get authenticated user profile
- `POST /api/refresh` - Refresh authentication token

**Key Features:**
- Laravel 11 with Sanctum guard
- Secure password hashing
- Token management
- Proper error responses

### 3. **Stripe Webhook with Idempotency Check**
- **StripeWebhookController.php** - Robust webhook handling
- Prevents double-processing of events
- Database tracking of processed events
- Comprehensive error handling and logging

**Key Features:**
- Idempotency check using `stripe_events` table
- Webhook signature verification
- Support for multiple event types:
  - `invoice.payment_succeeded`
  - `invoice.payment_failed`
  - `customer.subscription.created`
  - `customer.subscription.updated`
  - `customer.subscription.deleted`
- Proper logging and error handling

### 4. **Eloquent Eager Loading & Sorting**
- **PostController.php** - Demonstrates N+1 query prevention
- Advanced relationship loading
- Multiple sorting strategies
- Performance optimization examples

**Examples:**
```php
// Basic eager loading with sorting
$posts = Post::with(['user', 'comments'])
    ->orderBy('created_at', 'desc')
    ->get();

// Advanced eager loading with counts
$posts = Post::with(['user'])
    ->withCount('comments')
    ->orderBy('comments_count', 'desc')
    ->get();

// Conditional eager loading
$posts = Post::with(['user', 'comments' => function ($query) {
    $query->latest()->limit(1);
}])->get();
```

## 📚 API Documentation

### Authentication Endpoints

#### Register User
```http
POST /api/register
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

#### Login User
```http
POST /api/login
Content-Type: application/json

{
    "email": "john@example.com",
    "password": "password123"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com"
        },
        "token": "1|abc123...",
        "token_type": "Bearer"
    }
}
```

### User Management Endpoints

#### List Users
```http
GET /api/users
Authorization: Bearer {token}
```

#### Create User
```http
POST /api/users
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Jane Doe",
    "email": "jane@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

### Eager Loading Examples

#### Posts with Relationships
```http
GET /api/posts
Authorization: Bearer {token}
```

#### Advanced Eager Loading
```http
GET /api/posts/advanced/example
Authorization: Bearer {token}
```

### Stripe Webhook

#### Webhook Endpoint
```http
POST /api/stripe/webhook
Content-Type: application/json
Stripe-Signature: {signature}

{
    "id": "evt_1234567890",
    "type": "invoice.payment_succeeded",
    "data": {
        "object": {
            "id": "in_1234567890",
            "customer": "cus_1234567890",
            "amount_paid": 2000
        }
    }
}
```

## 🔧 Technical Highlights

### Database Design
- Proper foreign key relationships
- Indexed columns for performance
- JSON storage for webhook payloads
- Unique constraints for idempotency

### Security Features
- CSRF protection
- Input validation and sanitization
- Secure password hashing
- Token-based authentication
- Webhook signature verification

### Performance Optimizations
- Eager loading to prevent N+1 queries
- Database indexing
- Pagination for large datasets
- Efficient relationship loading

## 🧪 Testing the API

### Using cURL

1. **Register a user:**
   ```bash
   curl -X POST http://localhost:8000/api/register \
     -H "Content-Type: application/json" \
     -d '{"name":"Test User","email":"test@example.com","password":"password123","password_confirmation":"password123"}'
   ```

2. **Login:**
   ```bash
   curl -X POST http://localhost:8000/api/login \
     -H "Content-Type: application/json" \
     -d '{"email":"test@example.com","password":"password123"}'
   ```

3. **Access protected route:**
   ```bash
   curl -X GET http://localhost:8000/api/users \
     -H "Authorization: Bearer YOUR_TOKEN_HERE"
   ```

### Using Postman
Import the provided Postman collection for easy API testing.
