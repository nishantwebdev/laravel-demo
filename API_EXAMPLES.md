# API Usage Examples

This document provides practical examples of how to use the Laravel API demo endpoints.

## 🔐 Authentication Flow

### 1. Register a New User

```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

**Response:**
```json
{
  "success": true,
  "message": "User registered successfully",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    },
    "token": "1|abc123def456...",
    "token_type": "Bearer"
  }
}
```

### 2. Login User

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
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
    "token": "1|abc123def456...",
    "token_type": "Bearer"
  }
}
```

### 3. Get User Profile

```bash
curl -X GET http://localhost:8000/api/profile \
  -H "Authorization: Bearer 1|abc123def456..."
```

## 👥 User Management

### 1. List All Users (Paginated)

```bash
curl -X GET http://localhost:8000/api/users \
  -H "Authorization: Bearer 1|abc123def456..."
```

**Response:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "created_at": "2024-01-01T00:00:00.000000Z"
      }
    ],
    "first_page_url": "http://localhost:8000/api/users?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http://localhost:8000/api/users?page=1",
    "links": [...],
    "next_page_url": null,
    "path": "http://localhost:8000/api/users",
    "per_page": 10,
    "prev_page_url": null,
    "to": 1,
    "total": 1
  },
  "message": "Users retrieved successfully"
}
```

### 2. Create a New User

```bash
curl -X POST http://localhost:8000/api/users \
  -H "Authorization: Bearer 1|abc123def456..." \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Jane Smith",
    "email": "jane@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### 3. Get Specific User

```bash
curl -X GET http://localhost:8000/api/users/1 \
  -H "Authorization: Bearer 1|abc123def456..."
```

### 4. Update User

```bash
curl -X PUT http://localhost:8000/api/users/1 \
  -H "Authorization: Bearer 1|abc123def456..." \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Updated",
    "email": "john.updated@example.com"
  }'
```

### 5. Delete User

```bash
curl -X DELETE http://localhost:8000/api/users/1 \
  -H "Authorization: Bearer 1|abc123def456..."
```

## 📝 Posts with Eager Loading

### 1. List Posts with Relationships

```bash
curl -X GET http://localhost:8000/api/posts \
  -H "Authorization: Bearer 1|abc123def456..."
```

**Response:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "title": "My First Post",
        "content": "This is the content of my first post.",
        "user_id": 1,
        "published": true,
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z",
        "user": {
          "id": 1,
          "name": "John Doe",
          "email": "john@example.com"
        },
        "comments": [
          {
            "id": 1,
            "content": "Great post!",
            "user_id": 1,
            "post_id": 1,
            "created_at": "2024-01-01T00:00:00.000000Z"
          }
        ]
      }
    ]
  },
  "message": "Posts retrieved successfully with eager loading"
}
```

### 2. Advanced Eager Loading Examples

```bash
curl -X GET http://localhost:8000/api/posts/advanced/example \
  -H "Authorization: Bearer 1|abc123def456..."
```

**Response:**
```json
{
  "success": true,
  "data": {
    "posts_with_comments": [...],
    "posts_with_counts": [
      {
        "id": 1,
        "title": "My First Post",
        "content": "This is the content...",
        "user_id": 1,
        "published": true,
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z",
        "user": {
          "id": 1,
          "name": "John Doe",
          "email": "john@example.com"
        },
        "comments_count": 5
      }
    ],
    "posts_with_latest_comment": [...]
  },
  "message": "Advanced eager loading examples retrieved successfully",
  "explanation": {
    "posts_with_comments": "Posts with user and all comments, ordered by latest",
    "posts_with_counts": "Posts with user and comment count, ordered by comment count",
    "posts_with_latest_comment": "Posts with user and only the latest comment"
  }
}
```

## 💳 Stripe Webhook Testing

### 1. Payment Succeeded Event

```bash
curl -X POST http://localhost:8000/api/stripe/webhook \
  -H "Content-Type: application/json" \
  -H "Stripe-Signature: t=1234567890,v1=signature_here" \
  -d '{
    "id": "evt_1234567890",
    "object": "event",
    "type": "invoice.payment_succeeded",
    "data": {
      "object": {
        "id": "in_1234567890",
        "object": "invoice",
        "customer": "cus_1234567890",
        "amount_paid": 2000,
        "currency": "usd",
        "status": "paid"
      }
    },
    "created": 1234567890
  }'
```

### 2. Payment Failed Event

```bash
curl -X POST http://localhost:8000/api/stripe/webhook \
  -H "Content-Type: application/json" \
  -H "Stripe-Signature: t=1234567890,v1=signature_here" \
  -d '{
    "id": "evt_1234567891",
    "object": "event",
    "type": "invoice.payment_failed",
    "data": {
      "object": {
        "id": "in_1234567891",
        "object": "invoice",
        "customer": "cus_1234567890",
        "amount_due": 2000,
        "currency": "usd",
        "status": "open"
      }
    },
    "created": 1234567891
  }'
```

### 3. Subscription Created Event

```bash
curl -X POST http://localhost:8000/api/stripe/webhook \
  -H "Content-Type: application/json" \
  -H "Stripe-Signature: t=1234567892,v1=signature_here" \
  -d '{
    "id": "evt_1234567892",
    "object": "event",
    "type": "customer.subscription.created",
    "data": {
      "object": {
        "id": "sub_1234567890",
        "object": "subscription",
        "customer": "cus_1234567890",
        "status": "active",
        "current_period_start": 1234567890,
        "current_period_end": 1237159890
      }
    },
    "created": 1234567892
  }'
```

## 🔍 Error Handling Examples

### 1. Validation Error

```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John",
    "email": "invalid-email",
    "password": "123"
  }'
```

**Response:**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["The email field must be a valid email address."],
    "password": ["The password field must be at least 8 characters."]
  }
}
```

### 2. Authentication Error

```bash
curl -X GET http://localhost:8000/api/users
```

**Response:**
```json
{
  "message": "Unauthenticated."
}
```

### 3. Duplicate Event (Idempotency)

```bash
# First request
curl -X POST http://localhost:8000/api/stripe/webhook \
  -H "Content-Type: application/json" \
  -d '{"id": "evt_duplicate", "type": "invoice.payment_succeeded", "data": {"object": {"id": "in_123"}}}'

# Second identical request
curl -X POST http://localhost:8000/api/stripe/webhook \
  -H "Content-Type: application/json" \
  -d '{"id": "evt_duplicate", "type": "invoice.payment_succeeded", "data": {"object": {"id": "in_123"}}}'
```

**Response (second request):**
```json
{
  "message": "Event already processed"
}
```

## 🧪 Testing with Postman

1. **Import Environment Variables:**
   - `base_url`: `http://localhost:8000`
   - `token`: `{{login_token}}`

2. **Create Collection with these requests:**
   - Register User
   - Login User
   - Get Profile
   - List Users
   - Create User
   - List Posts
   - Stripe Webhook Test

3. **Set up Pre-request Scripts:**
   ```javascript
   // For login request
   pm.test("Login successful", function () {
       var jsonData = pm.response.json();
       pm.environment.set("token", jsonData.data.token);
   });
   ```

4. **Use token in Authorization:**
   - Type: Bearer Token
   - Token: `{{token}}`

## 📊 Database Queries for Verification

### Check Stripe Events Table
```sql
SELECT * FROM stripe_events ORDER BY created_at DESC;
```

### Check Users Table
```sql
SELECT id, name, email, created_at FROM users ORDER BY created_at DESC;
```

### Check Posts with Relationships
```sql
SELECT p.id, p.title, u.name as author, COUNT(c.id) as comment_count
FROM posts p
LEFT JOIN users u ON p.user_id = u.id
LEFT JOIN comments c ON p.id = c.post_id
GROUP BY p.id, p.title, u.name
ORDER BY p.created_at DESC;
```

## 🚀 Performance Testing

### Test Eager Loading vs N+1 Queries

1. **Enable Query Logging:**
   ```php
   // In tinker or a test route
   DB::enableQueryLog();
   ```

2. **Test with Eager Loading:**
   ```php
   $posts = Post::with(['user', 'comments'])->get();
   ```

3. **Test without Eager Loading:**
   ```php
   $posts = Post::all();
   foreach($posts as $post) {
       echo $post->user->name; // This will cause N+1 queries
   }
   ```

4. **Compare Query Count:**
   ```php
   $queries = DB::getQueryLog();
   echo "Total queries: " . count($queries);
   ```

This demonstrates the power of eager loading in preventing N+1 query problems!
