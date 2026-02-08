# API Setup Checklist

## Pre-Flight Checklist ✓

- [x] API routes created (`routes/api.php`)
- [x] Usercontroller API methods added (10 methods)
- [x] ProductController API methods added (8 methods)
- [x] CartController API methods added (5 methods)
- [x] OrderController API methods added (8 methods)
- [x] CarouselController API methods added (4 methods)
- [x] Documentation created (4 files)

---

## Setup Required (Before Testing)

### Step 1: Install Laravel Sanctum
```bash
php artisan install:api
```

**Expected Output:**
```
Publishing Sanctum assets...
Sanctum scaffolding installed successfully.
```

If already installed, skip to Step 2.

### Step 2: Add HasApiTokens to User Model

**File:** `app/Models/User.php`

```php
<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;  // Add this line
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  // Add HasApiTokens here
    
    // ... rest of code
}
```

### Step 3: Create Admin Middleware

```bash
php artisan make:middleware AdminMiddleware
```

**File:** `app/Http/Middleware/AdminMiddleware.php`

Replace contents with:
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
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

### Step 4: Register Middleware

**File:** `app/Http/Kernel.php`

Find the `$routeMiddleware` array and add:

```php
protected $routeMiddleware = [
    // ... existing middleware
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
];
```

### Step 5: Add Status Column to Orders Table (Optional)

If your `orders` table doesn't have a `status` column:

```bash
php artisan make:migration add_status_to_orders_table
```

**File:** `database/migrations/XXXX_XX_XX_XXXXXX_add_status_to_orders_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('payment');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
```

Then run:
```bash
php artisan migrate
```

### Step 6: Run All Migrations

```bash
php artisan migrate
```

### Step 7: Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

---

## Testing Checklist

### Test 1: Register User ✓
```bash
curl -X POST http://localhost/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

**Expected:** User created with success message

### Test 2: Login ✓
```bash
curl -X POST http://localhost/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'
```

**Expected:** Token returned

### Test 3: Get Products (Public) ✓
```bash
curl -X GET http://localhost/api/products \
  -H "Accept: application/json"
```

**Expected:** Product list returned

### Test 4: Get Profile (Protected) ✓
```bash
curl -X GET http://localhost/api/auth/me \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

**Expected:** User profile returned

### Test 5: Add to Cart (Protected) ✓
```bash
curl -X POST http://localhost/api/cart/add \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "product_id": 1,
    "quantity": 1,
    "selected_size": "M"
  }'
```

**Expected:** Cart item added

### Test 6: Place Order (Protected) ✓
```bash
curl -X POST http://localhost/api/orders \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "number": "1234567890",
    "address": "123 Main St",
    "payment": "COD"
  }'
```

**Expected:** Order created

### Test 7: Admin Create Product (Admin Only) ✓
```bash
curl -X POST http://localhost/api/admin/products \
  -H "Authorization: Bearer ADMIN_TOKEN_HERE" \
  -F "brand_name=TestBrand" \
  -F "article_name=Test Product" \
  -F "type=shirt" \
  -F "price=29.99" \
  -F "description=Test product" \
  -F "gender=men"
```

**Expected:** Product created

---

## Troubleshooting

### Issue: "Call to undefined method createToken()"
**Solution:** Add `HasApiTokens` trait to User model (Step 2)

### Issue: "Route [api.auth.register] not defined"
**Solution:** Ensure `routes/api.php` exists with correct routes

### Issue: Admin endpoints return 403
**Solution:** Create AdminMiddleware (Step 3) and register it (Step 4)

### Issue: Image upload fails
**Solution:** Create `public/images/products` directory:
```bash
mkdir -p public/images/products
chmod 755 public/images/products
```

### Issue: CORS errors when calling from frontend
**Solution:** Update `config/cors.php`:
```php
'allowed_origins' => ['*'],  // Allow all origins (development only)
```

### Issue: Token expires too quickly
**Solution:** Update `config/sanctum.php`:
```php
'expiration' => null,  // No expiration
```

---

## Production Checklist

- [ ] Update `.env` with production database credentials
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Set `APP_ENV=production` in `.env`
- [ ] Update CORS origins in `config/cors.php`
- [ ] Configure Sanctum token expiration in `config/sanctum.php`
- [ ] Enable HTTPS for API endpoints
- [ ] Set up rate limiting middleware
- [ ] Configure email for order confirmations
- [ ] Set up monitoring and logging
- [ ] Create API key authentication if needed
- [ ] Document API version in responses
- [ ] Set up API versioning if needed

---

## Useful Commands

### Generate API Documentation
```bash
php artisan route:list | grep api
```

### Clear API Cache
```bash
php artisan config:clear
php artisan cache:clear
```

### Create Test User (Admin)
```bash
php artisan tinker
>>> $user = User::create(['name' => 'Admin', 'email' => 'admin@test.com', 'password' => Hash::make('password'), 'role' => 'admin']);
>>> exit
```

### Generate New Token for User
```bash
php artisan tinker
>>> $user = User::find(1);
>>> $token = $user->createToken('auth_token')->plainTextToken;
>>> echo $token;
>>> exit
```

### Revoke All User Tokens
```bash
php artisan tinker
>>> $user = User::find(1);
>>> $user->tokens()->delete();
>>> exit
```

---

## File Structure

```
ElectronicMart/
├── routes/
│   ├── api.php                    ✅ Created
│   └── web.php                    (existing)
├── app/Http/Controllers/
│   ├── Usercontroller.php         ✅ Modified (10 methods added)
│   ├── ProductController.php      ✅ Modified (8 methods added)
│   ├── CartController.php         ✅ Modified (5 methods added)
│   ├── Ordercontroller.php        ✅ Modified (8 methods added)
│   ├── CarouselController.php     ✅ Modified (4 methods added)
│   └── Controller.php             (existing)
├── app/Http/Middleware/
│   └── AdminMiddleware.php        ⏳ Need to create
├── app/Models/
│   ├── User.php                   ⏳ Need to add trait
│   ├── Products.php               (existing)
│   ├── Order.php                  (existing)
│   └── etc...                     (existing)
├── config/
│   ├── sanctum.php                ⏳ May need updates
│   ├── cors.php                   ⏳ May need updates
│   └── etc...                     (existing)
├── public/
│   ├── images/
│   │   └── products/              ⏳ Ensure exists
│   └── etc...
├── API_DOCUMENTATION.md           ✅ Created
├── API_IMPLEMENTATION_NOTES.md    ✅ Created
├── API_QUICK_REFERENCE.md         ✅ Created
├── API_SUMMARY.md                 ✅ Created
└── API_SETUP_CHECKLIST.md         ✅ This file
```

---

## Next Steps

1. **Complete Setup:**
   - [ ] Install Sanctum
   - [ ] Add trait to User model
   - [ ] Create Admin middleware
   - [ ] Register middleware
   - [ ] Run migrations
   - [ ] Clear cache

2. **Test API:**
   - [ ] Test public endpoints
   - [ ] Test protected endpoints
   - [ ] Test admin endpoints
   - [ ] Test error handling

3. **Deploy:**
   - [ ] Set up in production
   - [ ] Configure security
   - [ ] Set up monitoring

4. **Documentation:**
   - [ ] Share API docs with team
   - [ ] Create Postman collection
   - [ ] Document API changes

---

## Support Resources

- [Laravel Sanctum Documentation](https://laravel.com/docs/sanctum)
- [Laravel API Documentation](https://laravel.com/docs/api-authentication)
- [REST API Best Practices](https://restfulapi.net/)
- [JSON API Standard](https://jsonapi.org/)

---

## Quick Start Summary

1. Run: `php artisan install:api`
2. Add `HasApiTokens` to User model
3. Create AdminMiddleware
4. Run migrations
5. Test with curl/Postman
6. Deploy to production

---

**Status:** ✅ Code Implementation Complete  
**Remaining:** ⏳ Setup & Testing (5 steps)

All API methods are ready to use. Follow the setup steps above to enable the API.
