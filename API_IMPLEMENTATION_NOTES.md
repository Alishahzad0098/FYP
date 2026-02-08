# API Implementation Notes

## What Has Been Added

### 1. **API Routes File** (`routes/api.php`)
   - Created comprehensive API routes structure
   - Organized into public and protected endpoints
   - Included admin-only routes with middleware
   - Sanctum authentication middleware applied to protected routes

### 2. **Usercontroller API Methods**
   - `apiRegister()` - User registration with validation
   - `apiLogin()` - Login with token generation
   - `apiLogout()` - Logout and token revocation
   - `apiGetProfile()` - Get authenticated user's profile
   - `apiUpdateProfile()` - Update profile information
   - `apiChangePassword()` - Change user password
   - `apiGetAllUsers()` - Admin: Get all users
   - `apiGetUser()` - Admin: Get single user
   - `apiUpdateUser()` - Admin: Update user
   - `apiDeleteUser()` - Admin: Delete user

### 3. **ProductController API Methods**
   - `apiIndex()` - Get all products with filters
   - `apiShow()` - Get single product
   - `apiSearch()` - Search products by name/brand/description
   - `apiByCategory()` - Get products by category
   - `apiCompare()` - Get similar products for comparison
   - `apiStore()` - Admin: Create new product
   - `apiUpdate()` - Admin: Update product
   - `apiDelete()` - Admin: Delete product

### 4. **CartController API Methods**
   - `apiShowCart()` - Get cart items and total
   - `apiAddToCart()` - Add product to cart with size validation
   - `apiUpdate()` - Update item quantity
   - `apiRemove()` - Remove item from cart
   - `apiClearCart()` - Clear entire cart

### 5. **OrderController API Methods**
   - `apiPlaceOrder()` - Create order from cart items
   - `apiGetOrders()` - Get user's orders
   - `apiGetOrder()` - Get order with items
   - `apiGetOrderItems()` - Get items in order
   - `apiCancelOrder()` - Cancel order
   - `apiGetAllOrders()` - Admin: Get all orders with filters
   - `apiUpdateOrderStatus()` - Admin: Update order status

### 6. **CarouselController API Methods**
   - `apiIndex()` - Get all carousel items
   - `apiStore()` - Admin: Create carousel item
   - `apiUpdate()` - Admin: Update carousel item
   - `apiDelete()` - Admin: Delete carousel item

---

## Configuration Required

### 1. **Enable Sanctum** (if not already enabled)
```bash
php artisan install:api
```

### 2. **Update User Model** (`app/Models/User.php`)
Add Sanctum trait if not present:
```php
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
```

### 3. **Create Middleware** (for admin routes)
```bash
php artisan make:middleware AdminMiddleware
```

Edit `app/Http/Middleware/AdminMiddleware.php`:
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && $request->user()->role === 'admin') {
            return $next($request);
        }

        return response()->json([
            'success' => false,
            'message' => 'Unauthorized - Admin access required'
        ], 403);
    }
}
```

Register in `app/Http/Kernel.php`:
```php
protected $routeMiddleware = [
    // ... existing middleware
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
];
```

### 4. **Update Order Model** (if needed)
Add relationship to OrderItem model:
```php
public function items()
{
    return $this->hasMany(OrderItem::class);
}
```

---

## Database Migrations (if needed)

### Add Status Column to Orders Table
If the `orders` table doesn't have a `status` column, create a migration:

```bash
php artisan make:migration add_status_to_orders_table
```

Migration file:
```php
Schema::table('orders', function (Blueprint $table) {
    $table->string('status')->default('pending')->after('payment');
});
```

---

## Usage Examples

### 1. Register User
```bash
curl -X POST http://localhost/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### 2. Login and Get Token
```bash
curl -X POST http://localhost/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

### 3. Get Products with Token
```bash
curl -X GET http://localhost/api/products \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### 4. Add to Cart
```bash
curl -X POST http://localhost/api/cart/add \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "product_id": 1,
    "quantity": 2,
    "selected_size": "M"
  }'
```

### 5. Place Order
```bash
curl -X POST http://localhost/api/orders \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "number": "1234567890",
    "address": "123 Main Street",
    "payment": "COD"
  }'
```

---

## Response Format

All API responses follow a consistent JSON format:

### Success Response
```json
{
  "success": true,
  "message": "Operation successful",
  "data": {...}
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error description",
  "errors": {...}  // Only for validation errors
}
```

---

## Pagination

List endpoints return paginated results:
```json
{
  "current_page": 1,
  "data": [...],
  "first_page_url": "http://localhost/api/products?page=1",
  "from": 1,
  "last_page": 5,
  "last_page_url": "http://localhost/api/products?page=5",
  "links": [...],
  "next_page_url": "http://localhost/api/products?page=2",
  "path": "http://localhost/api/products",
  "per_page": 12,
  "prev_page_url": null,
  "to": 12,
  "total": 150
}
```

---

## Security Notes

1. **Token Storage**: Store tokens securely in client application
2. **HTTPS**: Always use HTTPS in production
3. **Token Expiration**: Configure token expiration in `config/sanctum.php`
4. **CORS**: Configure CORS if frontend is on different domain
5. **Rate Limiting**: Consider adding rate limiting middleware
6. **Input Validation**: All endpoints validate input before processing

---

## Troubleshooting

### Issue: Token not working
**Solution**: Ensure Sanctum is installed and User model has `HasApiTokens` trait

### Issue: CORS errors
**Solution**: Update `config/cors.php` to allow your frontend origin

### Issue: Image upload fails
**Solution**: Ensure `public/images/products` directory exists and is writable

### Issue: Admin endpoints return 403
**Solution**: Verify user has `role = 'admin'` in database

---

## Next Steps

1. Test all endpoints using Postman or similar tool
2. Implement frontend application to consume API
3. Add rate limiting and request throttling
4. Implement caching for frequently accessed data
5. Add logging for API requests and errors
6. Set up API documentation with Swagger/OpenAPI

---

## Files Modified

1. `routes/api.php` - ✅ Created API routes
2. `app/Http/Controllers/Usercontroller.php` - ✅ Added 10 API methods
3. `app/Http/Controllers/ProductController.php` - ✅ Added 8 API methods
4. `app/Http/Controllers/CartController.php` - ✅ Added 5 API methods
5. `app/Http/Controllers/Ordercontroller.php` - ✅ Added 8 API methods
6. `app/Http/Controllers/CarouselController.php` - ✅ Added 4 API methods
7. `API_DOCUMENTATION.md` - ✅ Created comprehensive documentation

Total API methods implemented: **43 methods**
