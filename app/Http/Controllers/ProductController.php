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
        $banners = Carousel::all();

        return view('Home', compact('product', 'banners', 'brands'));
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
    $brands = Products::select('brand_name')->distinct()->pluck('brand_name');
    return view('About', compact('brands'));
}

public function contact()
{
    $brands = Products::select('brand_name')->distinct()->pluck('brand_name');
    return view('Contact', compact('brands'));
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

}