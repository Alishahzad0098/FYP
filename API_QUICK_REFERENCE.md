# API Quick Reference Guide

## Base URL
```
http://localhost/api
```

## Authentication Header
```
Authorization: Bearer {token}
```

---

## Quick Endpoint List

### Authentication (Public)
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/auth/register` | Register new user |
| POST | `/auth/login` | Login and get token |

### Authentication (Protected)
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/auth/logout` | Logout and revoke token |
| GET | `/auth/me` | Get user profile |
| PUT | `/auth/profile` | Update profile |
| POST | `/auth/change-password` | Change password |

### Products (Public)
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/products` | Get all products (with filters) |
| GET | `/products/{id}` | Get single product |
| GET | `/products/search` | Search products |
| GET | `/products/category/{category}` | Get by category |
| GET | `/products/compare/{id}` | Compare products |

### Products (Admin)
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/admin/products` | Create product |
| PUT | `/admin/products/{id}` | Update product |
| DELETE | `/admin/products/{id}` | Delete product |

### Cart (Protected)
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/cart` | Get cart items |
| POST | `/cart/add` | Add to cart |
| PUT | `/cart/update/{id}` | Update item quantity |
| DELETE | `/cart/remove/{id}` | Remove from cart |
| DELETE | `/cart/clear` | Clear cart |

### Orders (Protected)
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/orders` | Place order |
| GET | `/orders` | Get my orders |
| GET | `/orders/{id}` | Get order details |
| GET | `/orders/{id}/items` | Get order items |
| PUT | `/orders/{id}/cancel` | Cancel order |

### Orders (Admin)
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/admin/orders` | Get all orders |
| PUT | `/admin/orders/{id}/status` | Update order status |

### Carousel (Public)
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/carousel` | Get carousel items |

### Carousel (Admin)
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/admin/carousel` | Create carousel item |
| PUT | `/admin/carousel/{id}` | Update carousel item |
| DELETE | `/admin/carousel/{id}` | Delete carousel item |

### Users (Admin)
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/admin/users` | Get all users |
| GET | `/admin/users/{id}` | Get user |
| PUT | `/admin/users/{id}` | Update user |
| DELETE | `/admin/users/{id}` | Delete user |

---

## Common Request Examples

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

### 2. Login
```bash
curl -X POST http://localhost/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

### 3. Get Products (with filters)
```bash
curl -X GET "http://localhost/api/products?gender=men&price=100+&page=1" \
  -H "Accept: application/json"
```

### 4. Get Single Product
```bash
curl -X GET http://localhost/api/products/1 \
  -H "Accept: application/json"
```

### 5. Add to Cart (Protected)
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

### 6. Get Cart (Protected)
```bash
curl -X GET http://localhost/api/cart \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### 7. Place Order (Protected)
```bash
curl -X POST http://localhost/api/orders \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "number": "1234567890",
    "address": "123 Main St, City",
    "payment": "COD"
  }'
```

### 8. Create Product (Admin)
```bash
curl -X POST http://localhost/api/admin/products \
  -H "Authorization: Bearer ADMIN_TOKEN" \
  -F "brand_name=Nike" \
  -F "article_name=T-Shirt" \
  -F "type=shirt" \
  -F "price=29.99" \
  -F "description=Comfortable cotton t-shirt" \
  -F "gender=men" \
  -F "fabric=cotton" \
  -F "size[]=S" \
  -F "size[]=M" \
  -F "size[]=L" \
  -F "images=@image.jpg"
```

### 9. Update Order Status (Admin)
```bash
curl -X PUT http://localhost/api/admin/orders/1/status \
  -H "Authorization: Bearer ADMIN_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "status": "shipped"
  }'
```

---

## Response Status Codes

| Code | Meaning | Example |
|------|---------|---------|
| 200 | OK | GET successful |
| 201 | Created | POST successful |
| 400 | Bad Request | Invalid data |
| 401 | Unauthorized | Missing/invalid token |
| 403 | Forbidden | No permission (not admin) |
| 404 | Not Found | Resource doesn't exist |
| 422 | Validation Error | Validation failed |
| 500 | Server Error | Server issue |

---

## Query Parameters

### Products Endpoint
```
/api/products?gender=men&type=shirt&fabric=cotton&brand=Nike&price=50-100&page=1
```

Parameters:
- `gender`: 'men', 'women', 'kids'
- `type`: Product type
- `fabric`: Fabric type
- `brand`: Brand name
- `price`: '0-50', '50-100', '100+'
- `page`: Page number (default: 1)

### Products Search
```
/api/products/search?query=nike&page=1
```

### Admin Orders Filter
```
/api/admin/orders?status=shipped&payment=COD&page=1
```

Parameters:
- `status`: 'pending', 'processing', 'shipped', 'delivered', 'cancelled'
- `payment`: 'COD', 'card', 'paypal'
- `page`: Page number

---

## Important Notes

### Cart Storage
- Cart is stored in session (not persistent after logout)
- Each item key is: `{product_id}_{size}` or just `{product_id}`
- Use the full key when updating/removing items

### Authentication Flow
1. Register → Get user account
2. Login → Get API token
3. Use token in Authorization header for protected endpoints
4. Token format: `Bearer {token}`

### Admin Access
- User must have `role = 'admin'` in database
- Admin routes require both token AND admin role
- Returns 403 if not admin

### Error Handling
All errors follow format:
```json
{
  "success": false,
  "message": "Error description",
  "errors": {
    "field": ["Error message"]
  }
}
```

### Pagination
List responses include pagination data:
```json
{
  "current_page": 1,
  "data": [...],
  "total": 150,
  "per_page": 12,
  "last_page": 13
}
```

---

## Environment Setup

### 1. Install Sanctum (if needed)
```bash
php artisan install:api
```

### 2. Publish Sanctum Config
```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

### 3. Run Migrations
```bash
php artisan migrate
```

### 4. Add Admin Middleware
Create `app/Http/Middleware/AdminMiddleware.php` and register in `app/Http/Kernel.php`

---

## Testing Tools
- **Postman** - Full-featured API testing
- **Insomnia** - REST/GraphQL API client
- **Thunder Client** - VS Code extension
- **cURL** - Command-line tool
- **Postman Collections** - Pre-built test suites

---

## Troubleshooting

| Problem | Solution |
|---------|----------|
| Token not recognized | Install Sanctum, add `HasApiTokens` to User model |
| CORS errors | Update `config/cors.php` |
| Image upload fails | Ensure `public/images/products` directory exists |
| Admin endpoints return 403 | Check user has `role = 'admin'` |
| 404 on `/api/products` | Ensure `routes/api.php` is created and includes routes |
| 422 validation error | Check request body matches validation rules |

---

## File Locations

- API Routes: `routes/api.php`
- Controllers: `app/Http/Controllers/`
- Middleware: `app/Http/Middleware/`
- Documentation: `API_DOCUMENTATION.md`
- Implementation Notes: `API_IMPLEMENTATION_NOTES.md`

---

**Total API Methods Implemented: 43**
**Total Endpoints: 37+**
**Last Updated: February 7, 2026**
