# ElectronicMart API Documentation

## Overview
This API provides RESTful endpoints for the ElectronicMart e-commerce platform using Laravel Sanctum authentication.

## Base URL
```
http://localhost/api
```

## Authentication
All protected endpoints require Sanctum token authentication.

**Header Format:**
```
Authorization: Bearer {token}
```

---

## Authentication Endpoints

### 1. User Registration
**Endpoint:** `POST /api/auth/register`
**Access:** Public
**Description:** Register a new user account

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response (201):**
```json
{
  "success": true,
  "message": "User registered successfully",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "user",
    "created_at": "2025-02-07T10:00:00Z"
  }
}
```

---

### 2. User Login
**Endpoint:** `POST /api/auth/login`
**Access:** Public
**Description:** Login and get authentication token

**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Login successful",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "user"
  },
  "token": "1|aBcDeFgHiJkLmNoPqRsTuVwXyZ"
}
```

---

### 3. User Logout
**Endpoint:** `POST /api/auth/logout`
**Access:** Protected (Sanctum)
**Description:** Logout and revoke token

**Response (200):**
```json
{
  "success": true,
  "message": "Logout successful"
}
```

---

### 4. Get Current User Profile
**Endpoint:** `GET /api/auth/me`
**Access:** Protected (Sanctum)
**Description:** Get authenticated user's profile

**Response (200):**
```json
{
  "success": true,
  "message": "Profile retrieved",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "user"
  }
}
```

---

### 5. Update User Profile
**Endpoint:** `PUT /api/auth/profile`
**Access:** Protected (Sanctum)
**Description:** Update user profile information

**Request Body:**
```json
{
  "name": "Jane Doe",
  "email": "jane@example.com"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Profile updated successfully",
  "user": {
    "id": 1,
    "name": "Jane Doe",
    "email": "jane@example.com"
  }
}
```

---

### 6. Change Password
**Endpoint:** `POST /api/auth/change-password`
**Access:** Protected (Sanctum)
**Description:** Change user password

**Request Body:**
```json
{
  "current_password": "oldpassword123",
  "password": "newpassword123",
  "password_confirmation": "newpassword123"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Password changed successfully"
}
```

---

## Product Endpoints

### 1. Get All Products (with filters)
**Endpoint:** `GET /api/products`
**Access:** Public
**Description:** Get all products with optional filters

**Query Parameters:**
- `gender`: 'men', 'women', 'kids'
- `type`: Product type (e.g., 'shirt', 'pants')
- `fabric`: Fabric type
- `brand`: Brand name
- `price`: '0-50', '50-100', '100+'
- `page`: Pagination page number

**Example:**
```
GET /api/products?gender=men&price=100+&page=1
```

**Response (200):**
```json
{
  "success": true,
  "message": "Products retrieved",
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "brand_name": "Nike",
        "article_name": "T-Shirt",
        "type": "shirt",
        "price": 29.99,
        "description": "Comfortable cotton t-shirt",
        "gender": "men",
        "fabric": "cotton",
        "size": ["S", "M", "L", "XL"],
        "images": ["images/products/..."],
        "created_at": "2025-02-07T10:00:00Z"
      }
    ],
    "total": 150,
    "per_page": 12
  }
}
```

---

### 2. Get Single Product
**Endpoint:** `GET /api/products/{id}`
**Access:** Public
**Description:** Get detailed information of a specific product

**Response (200):**
```json
{
  "success": true,
  "message": "Product retrieved",
  "data": {
    "id": 1,
    "brand_name": "Nike",
    "article_name": "T-Shirt",
    "type": "shirt",
    "price": 29.99,
    "description": "Comfortable cotton t-shirt",
    "gender": "men",
    "fabric": "cotton",
    "size": ["S", "M", "L", "XL"],
    "images": ["images/products/..."]
  }
}
```

---

### 3. Search Products
**Endpoint:** `GET /api/products/search`
**Access:** Public
**Description:** Search products by name, brand, or description

**Query Parameters:**
- `query`: Search query string

**Example:**
```
GET /api/products/search?query=nike
```

**Response (200):**
```json
{
  "success": true,
  "message": "Search results",
  "query": "nike",
  "data": {
    "current_page": 1,
    "data": [...],
    "total": 25
  }
}
```

---

### 4. Get Products by Category
**Endpoint:** `GET /api/products/category/{category}`
**Access:** Public
**Description:** Get all products in a specific category

**Example:**
```
GET /api/products/category/shirt
```

**Response (200):**
```json
{
  "success": true,
  "message": "Products by category",
  "category": "shirt",
  "data": {...}
}
```

---

### 5. Compare Products
**Endpoint:** `GET /api/products/compare/{id}`
**Access:** Public
**Description:** Get similar products for comparison

**Response (200):**
```json
{
  "success": true,
  "message": "Comparison data retrieved",
  "main_product": {...},
  "similar_products": [...]
}
```

---

### 6. Create Product (Admin Only)
**Endpoint:** `POST /api/admin/products`
**Access:** Protected (Sanctum + Admin)
**Description:** Create new product

**Request (Form Data):**
```
brand_name: "Nike"
article_name: "Sports Shoe"
type: "shoes"
price: 99.99
description: "High-quality sports shoe"
gender: "men"
fabric: "leather"
size: ["7", "8", "9", "10", "11"]
images: [file1.jpg, file2.jpg]
```

**Response (201):**
```json
{
  "success": true,
  "message": "Product created successfully",
  "data": {...}
}
```

---

### 7. Update Product (Admin Only)
**Endpoint:** `PUT /api/admin/products/{id}`
**Access:** Protected (Sanctum + Admin)
**Description:** Update product information

**Response (200):**
```json
{
  "success": true,
  "message": "Product updated successfully",
  "data": {...}
}
```

---

### 8. Delete Product (Admin Only)
**Endpoint:** `DELETE /api/admin/products/{id}`
**Access:** Protected (Sanctum + Admin)
**Description:** Delete a product

**Response (200):**
```json
{
  "success": true,
  "message": "Product deleted successfully"
}
```

---

## Cart Endpoints

### 1. Get Cart
**Endpoint:** `GET /api/cart`
**Access:** Protected (Sanctum)
**Description:** Get all items in cart

**Response (200):**
```json
{
  "success": true,
  "message": "Cart retrieved",
  "data": {
    "item_1": {
      "product_id": 1,
      "brand_name": "Nike",
      "article_name": "T-Shirt",
      "price": 29.99,
      "quantity": 2,
      "size": "M",
      "images": [...]
    }
  },
  "item_count": 1,
  "total": 59.98
}
```

---

### 2. Add to Cart
**Endpoint:** `POST /api/cart/add`
**Access:** Protected (Sanctum)
**Description:** Add product to cart

**Request Body:**
```json
{
  "product_id": 1,
  "quantity": 2,
  "selected_size": "M"
}
```

**Response (201):**
```json
{
  "success": true,
  "message": "Product added to cart",
  "cart_item_count": 5
}
```

---

### 3. Update Cart Item
**Endpoint:** `PUT /api/cart/update/{cart_key}`
**Access:** Protected (Sanctum)
**Description:** Update quantity of cart item

**Request Body:**
```json
{
  "quantity": 3
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Cart updated",
  "item": {...},
  "total": 89.97
}
```

---

### 4. Remove from Cart
**Endpoint:** `DELETE /api/cart/remove/{cart_key}`
**Access:** Protected (Sanctum)
**Description:** Remove item from cart

**Response (200):**
```json
{
  "success": true,
  "message": "Item removed from cart",
  "item_count": 4
}
```

---

### 5. Clear Cart
**Endpoint:** `DELETE /api/cart/clear`
**Access:** Protected (Sanctum)
**Description:** Clear entire cart

**Response (200):**
```json
{
  "success": true,
  "message": "Cart cleared"
}
```

---

## Order Endpoints

### 1. Place Order
**Endpoint:** `POST /api/orders`
**Access:** Protected (Sanctum)
**Description:** Create order from cart items

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "number": "1234567890",
  "address": "123 Main Street, City",
  "payment": "COD"
}
```

**Response (201):**
```json
{
  "success": true,
  "message": "Order placed successfully",
  "order_id": 5,
  "total": 150.50
}
```

---

### 2. Get My Orders
**Endpoint:** `GET /api/orders`
**Access:** Protected (Sanctum)
**Description:** Get all orders for authenticated user

**Response (200):**
```json
{
  "success": true,
  "message": "Orders retrieved",
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 5,
        "customer_name": "John Doe",
        "customer_email": "john@example.com",
        "total_amount": 150.50,
        "status": "pending",
        "payment": "COD",
        "created_at": "2025-02-07T10:00:00Z"
      }
    ],
    "total": 10
  }
}
```

---

### 3. Get Single Order
**Endpoint:** `GET /api/orders/{id}`
**Access:** Protected (Sanctum)
**Description:** Get order details with items

**Response (200):**
```json
{
  "success": true,
  "message": "Order retrieved",
  "data": {
    "id": 5,
    "customer_name": "John Doe",
    "total_amount": 150.50,
    "items": [...]
  }
}
```

---

### 4. Get Order Items
**Endpoint:** `GET /api/orders/{id}/items`
**Access:** Protected (Sanctum)
**Description:** Get items in specific order

**Response (200):**
```json
{
  "success": true,
  "message": "Order items retrieved",
  "order_id": 5,
  "items": [
    {
      "id": 1,
      "brand_name": "Nike",
      "article_name": "T-Shirt",
      "price": 29.99,
      "quantity": 2
    }
  ],
  "item_count": 1
}
```

---

### 5. Cancel Order
**Endpoint:** `PUT /api/orders/{id}/cancel`
**Access:** Protected (Sanctum)
**Description:** Cancel an order

**Response (200):**
```json
{
  "success": true,
  "message": "Order cancelled successfully",
  "order_id": 5
}
```

---

### 6. Get All Orders (Admin Only)
**Endpoint:** `GET /api/admin/orders`
**Access:** Protected (Sanctum + Admin)
**Description:** Get all orders with filters

**Query Parameters:**
- `status`: 'pending', 'processing', 'shipped', 'delivered', 'cancelled'
- `payment`: 'COD', 'card', 'paypal'

**Response (200):**
```json
{
  "success": true,
  "message": "All orders retrieved",
  "data": {...}
}
```

---

### 7. Update Order Status (Admin Only)
**Endpoint:** `PUT /api/admin/orders/{id}/status`
**Access:** Protected (Sanctum + Admin)
**Description:** Update order status

**Request Body:**
```json
{
  "status": "shipped"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Order status updated",
  "order_id": 5,
  "status": "shipped"
}
```

---

## Carousel Endpoints

### 1. Get All Carousel Items
**Endpoint:** `GET /api/carousel`
**Access:** Public
**Description:** Get all carousel items

**Response (200):**
```json
{
  "success": true,
  "message": "Carousel items retrieved",
  "data": [
    {
      "id": 1,
      "img": "image_filename.jpg",
      "para": "Banner text",
      "created_at": "2025-02-07T10:00:00Z"
    }
  ],
  "count": 3
}
```

---

### 2. Create Carousel Item (Admin Only)
**Endpoint:** `POST /api/admin/carousel`
**Access:** Protected (Sanctum + Admin)
**Description:** Create new carousel item

**Request (Form Data):**
```
para: "Welcome to ElectronicMart"
img: image_file.jpg
```

**Response (201):**
```json
{
  "success": true,
  "message": "Carousel item created successfully",
  "data": {...}
}
```

---

### 3. Update Carousel Item (Admin Only)
**Endpoint:** `PUT /api/admin/carousel/{id}`
**Access:** Protected (Sanctum + Admin)
**Description:** Update carousel item

**Response (200):**
```json
{
  "success": true,
  "message": "Carousel item updated successfully",
  "data": {...}
}
```

---

### 4. Delete Carousel Item (Admin Only)
**Endpoint:** `DELETE /api/admin/carousel/{id}`
**Access:** Protected (Sanctum + Admin)
**Description:** Delete carousel item

**Response (200):**
```json
{
  "success": true,
  "message": "Carousel item deleted successfully"
}
```

---

## User Management Endpoints (Admin Only)

### 1. Get All Users
**Endpoint:** `GET /api/admin/users`
**Access:** Protected (Sanctum + Admin)
**Description:** Get all users

**Response (200):**
```json
{
  "success": true,
  "message": "Users retrieved",
  "data": [...],
  "count": 25
}
```

---

### 2. Get Single User
**Endpoint:** `GET /api/admin/users/{id}`
**Access:** Protected (Sanctum + Admin)
**Description:** Get user details

**Response (200):**
```json
{
  "success": true,
  "message": "User retrieved",
  "data": {...}
}
```

---

### 3. Update User
**Endpoint:** `PUT /api/admin/users/{id}`
**Access:** Protected (Sanctum + Admin)
**Description:** Update user information

**Request Body:**
```json
{
  "name": "Jane Doe",
  "email": "jane@example.com",
  "role": "admin",
  "password": "newpassword123"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "User updated successfully",
  "data": {...}
}
```

---

### 4. Delete User
**Endpoint:** `DELETE /api/admin/users/{id}`
**Access:** Protected (Sanctum + Admin)
**Description:** Delete user

**Response (200):**
```json
{
  "success": true,
  "message": "User deleted successfully"
}
```

---

## Error Handling

All endpoints return appropriate HTTP status codes:

- **200 OK** - Successful GET/PUT request
- **201 Created** - Successful POST request (resource created)
- **400 Bad Request** - Invalid request data
- **401 Unauthorized** - Missing or invalid authentication token
- **403 Forbidden** - Insufficient permissions
- **404 Not Found** - Resource not found
- **422 Unprocessable Entity** - Validation failed
- **500 Internal Server Error** - Server error

**Error Response Example:**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["Email is required"]
  }
}
```

---

## Testing with Postman

1. Import the API collection into Postman
2. Set base URL: `http://localhost/api`
3. Register a user or login to get token
4. Add Bearer token to Authorization header for protected endpoints
5. Test each endpoint

---

## Notes

- Cart data is stored in session (not persistent across logout)
- Products and Orders are stored in database
- Admin endpoints require 'admin' role
- All timestamps are in UTC timezone
- Images are stored in public/images directory
