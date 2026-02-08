<?php

namespace App\Http\Controllers;

use App\Models\Carousel;
use App\Models\Products;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    // Show homepage products
    function show(Request $request)
    {
        $query = Products::query();

        // Gender filter
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Fabric filter
        if ($request->filled('fabric')) {
            $query->where('fabric', $request->fabric);
        }

        // Brand filter
        if ($request->filled('brand')) {
            $query->where('brand_name', $request->brand);
        }

        // Price filter
        if ($request->filled('price')) {
            match ($request->price) {
                '0-50' => $query->whereBetween('price', [0, 50]),
                '50-100' => $query->whereBetween('price', [50, 100]),
                '100+' => $query->where('price', '>=', 100),
            };
        }

        $product = $query->orderBy('id', 'desc')->paginate(6);

        // For filter lists
        $brands = Products::select('brand_name')->distinct()->pluck('brand_name');

        $c1 = Carousel::all();

        return view('Home', compact('product', 'c1', 'brands'));
    }

    // Show create product form
    function create()
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return view("Productsform");
        }
        return redirect()->route('home');
    }

    // Store new product
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'brand_name' => 'required|string|max:255',
            'article_name' => 'required|string|max:255',
            'type' => 'required|string',
            'size' => 'string',
            'size' => 'nullable|array',
            'fabric' => 'nullable|string',
            'gender' => 'nullable|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Handle images
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images/products'), $filename);
                $imagePaths[] = 'images/products/' . $filename;
            }
        }
        $product = new Products();
        $product->brand_name = $request->brand_name;
        $product->article_name = $request->article_name;
        $product->type = $request->type;
        $product->size = $request->size;
        $product->fabric = $request->fabric;
        $product->gender = $request->gender;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->images = !empty($imagePaths) ? json_encode($imagePaths) : null;
        $product->save();


        return redirect()->route('table.product')->with('success', 'Product added successfully');
    }
    public function productshow(Request $request)
    {
        // MEN PRODUCTS
        $query = Products::query();

        // Gender filter
        if ($request->filled('gender')) {
            $query->where('gender', operator: $request->gender);
        }

        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Fabric filter
        if ($request->filled('fabric')) {
            $query->where('fabric', $request->fabric);
        }

        // Brand filter
        if ($request->filled('brand')) {
            $query->where('brand_name', $request->brand);
        }

        // Price filter
        if ($request->filled('price')) {
            match ($request->price) {
                '0-50' => $query->whereBetween('price', [0, 50]),
                '50-100' => $query->whereBetween('price', [50, 100]),
                '100+' => $query->where('price', '>=', 100),
            };
        }

        $product = $query->orderBy('id', 'desc')->paginate(6);

        // For filter lists
        $brands = Products::select('brand_name')->distinct()->pluck('brand_name');

        $product = Products::where('gender', 'men')
            ->latest()
            ->paginate(8);

        // WOMEN PRODUCTS
        $p2 = Products::where('gender', 'women')
            ->latest()
            ->paginate(8);

        // KIDS PRODUCTS
        $p3 = Products::where('gender', 'kids')
            ->latest()
            ->paginate(8);

        // ACCESSORIES (bags, perfumes, belts etc.)
        $p4 = Products::where('type', 'accessories')
            ->latest()
            ->paginate(8);

        return view('products', compact('product', 'p2', 'p3', 'p4', 'brands'));
    }

    // Show product table (admin)
    public function table()
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            $product = Products::all();
            return view("Productable", compact("product"));
        }
        return redirect()->route('home');
    }

    // Edit product
    public function edit($id)
    {
        $product = Products::findOrFail($id);
        return view('Editproduct', compact('product'));
    }

    // Update product
    public function update(Request $request, $id)
    {
        $product = Products::findOrFail($id);
        $imagePaths = json_decode($product->images, true) ?? [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $timestamp = now()->format('YmdHis');
                    $randomString = Str::random(5);
                    $extension = $image->getClientOriginalExtension();
                    $filename = $timestamp . '_' . $randomString . '.' . $extension;

                    $destination = public_path('images/products');
                    if (!file_exists($destination)) {
                        mkdir($destination, 0755, true);
                    }

                    $image->move($destination, $filename);
                    $imagePaths[] = 'images/products/' . $filename;
                }
            }
        }

        $product->update([
            'brand_name' => $request->brand_name,
            'article_name' => $request->article_name,
            'type' => $request->type,
            'size' => $request->size,
            'fabric' => $request->fabric,
            'gender' => $request->gender,
            'description' => $request->description,
            'price' => $request->price,
            'images' => !empty($imagePaths) ? json_encode($imagePaths) : null,
        ]);

        return redirect()->route('table.product')->with('success', 'Product updated successfully');
    }

    // Delete product
    public function delete($id)
    {
        $product = Products::findOrFail($id);
        $product->delete();
        return redirect()->route("table.product")->with('success', 'Product deleted');
    }

    // Single product view
    public function product($id)
    {
        $product = Products::findOrFail($id);
        return view('Singleproduct', compact('product'));
    }

    // Search products
    public function search(Request $request)
    {
        $query = $request->input('query');

        // Search products based on query
        $products = Products::where('article_name', 'like', "%{$query}%")
            ->orWhere('brand_name', 'like', "%{$query}%")
            ->paginate(12); // Limit 12 products per page

        return view('Searchitem', compact('products', 'query'));
    }


    // About page
    public function about()
    {
        return view('About');
    }

    // Contact page
    public function contact()
    {
        return view('Contact');
    }

    public function index(Request $request)
    {
        $query = Products::query();

        // Filter by gender if selected
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender); // Using the gender field
        }

        // Paginate results, 9 per page, preserving query string
        $products = $query->paginate(9)->withQueryString();

        return view('productindex', compact('products'));
    }


    public function compare($id)
    {
        $product = Products::findOrFail($id);

        // If you're storing category as string field "categories"
        $otherProducts = Products::where('categories', $product->categories)
            ->where('id', '!=', $id)
            ->get();

        return view('Compare', compact('product', 'otherProducts'));
    }

    // ==================== API METHODS ====================

    /**
     * API: Get all products with filters
     */
    public function apiIndex(Request $request)
    {
        try {
            $query = Products::query();

            // Apply filters
            if ($request->filled('gender')) {
                $query->where('gender', $request->gender);
            }
            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }
            if ($request->filled('fabric')) {
                $query->where('fabric', $request->fabric);
            }
            if ($request->filled('brand')) {
                $query->where('brand_name', $request->brand);
            }
            if ($request->filled('price')) {
                match ($request->price) {
                    '0-50' => $query->whereBetween('price', [0, 50]),
                    '50-100' => $query->whereBetween('price', [50, 100]),
                    '100+' => $query->where('price', '>=', 100),
                };
            }

            $products = $query->orderBy('id', 'desc')->paginate(12);

            return response()->json([
                'success' => true,
                'message' => 'Products retrieved',
                'data' => $products,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve products: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Get single product by ID
     */
    public function apiShow($id)
    {
        try {
            $product = Products::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Product retrieved',
                'data' => $product,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve product: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Search products
     */
    public function apiSearch(Request $request)
    {
        try {
            $query = $request->input('query', '');

            $products = Products::where('article_name', 'like', "%{$query}%")
                ->orWhere('brand_name', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->paginate(12);

            return response()->json([
                'success' => true,
                'message' => 'Search results',
                'query' => $query,
                'data' => $products,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Get products by category
     */
    public function apiByCategory($category)
    {
        try {
            $products = Products::where('type', $category)
                ->orderBy('id', 'desc')
                ->paginate(12);

            return response()->json([
                'success' => true,
                'message' => 'Products by category',
                'category' => $category,
                'data' => $products,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve products: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Compare products
     */
    public function apiCompare($id)
    {
        try {
            $product = Products::findOrFail($id);

            $similarProducts = Products::where('type', $product->type)
                ->where('id', '!=', $id)
                ->limit(5)
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Comparison data retrieved',
                'main_product' => $product,
                'similar_products' => $similarProducts,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to compare products: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Store new product (admin only)
     */
    public function apiStore(Request $request)
    {
        try {
            $data = $request->validate([
                'brand_name' => 'required|string|max:255',
                'article_name' => 'required|string|max:255',
                'type' => 'required|string',
                'size' => 'nullable|array',
                'fabric' => 'nullable|string',
                'gender' => 'nullable|string',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            // Handle images
            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('images/products'), $filename);
                    $imagePaths[] = 'images/products/' . $filename;
                }
            }

            $product = Products::create([
                'brand_name' => $data['brand_name'],
                'article_name' => $data['article_name'],
                'type' => $data['type'],
                'size' => $data['size'],
                'fabric' => $data['fabric'],
                'gender' => $data['gender'],
                'description' => $data['description'],
                'price' => $data['price'],
                'images' => !empty($imagePaths) ? json_encode($imagePaths) : null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully',
                'data' => $product,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create product: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Update product (admin only)
     */
    public function apiUpdate(Request $request, $id)
    {
        try {
            $product = Products::findOrFail($id);

            $data = $request->validate([
                'brand_name' => 'sometimes|required|string|max:255',
                'article_name' => 'sometimes|required|string|max:255',
                'type' => 'sometimes|required|string',
                'size' => 'nullable|array',
                'fabric' => 'nullable|string',
                'gender' => 'nullable|string',
                'description' => 'sometimes|required|string',
                'price' => 'sometimes|required|numeric|min:0',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            $imagePaths = json_decode($product->images, true) ?? [];

            // Handle new images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('images/products'), $filename);
                    $imagePaths[] = 'images/products/' . $filename;
                }
            }

            $data['images'] = !empty($imagePaths) ? json_encode($imagePaths) : null;

            $product->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully',
                'data' => $product,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Delete product (admin only)
     */
    public function apiDelete($id)
    {
        try {
            $product = Products::findOrFail($id);
            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product: ' . $e->getMessage(),
            ], 500);
        }
    }
}
