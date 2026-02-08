<?php


namespace App\Http\Controllers;
use App\Models\Carousel;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class CarouselController extends Controller
{
    function create()
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'admin') {
                return view("carousel.carform");
            } else {
                return redirect()->route('home');
            }
        } else {
            return redirect()->route('loginpage');
        }
    }
    function table()
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'admin') {
                $car = Carousel::all();
                return view("carousel.cartable", compact('car'));
            } else {
                return redirect()->route('home');
            }
        } else {
            return redirect()->route('loginpage');
        }
    }
    public function store(Request $request)
    {
        $c1 = new Carousel();
        $image = $request->file("img");

        if ($image) {
            $imageName = time() . '_' . Str::random(5) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $c1->img = $imageName;
        }

        $c1->para = $request->para;
        $c1->save();

        return redirect()->back()->with('success', 'Carousel saved!');
    }

    // ==================== API METHODS ====================

    /**
     * API: Get all carousel items
     */
    public function apiIndex()
    {
        try {
            $carousels = Carousel::all();

            return response()->json([
                'success' => true,
                'message' => 'Carousel items retrieved',
                'data' => $carousels,
                'count' => $carousels->count(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve carousel items: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Store new carousel item (admin only)
     */
    public function apiStore(Request $request)
    {
        try {
            $data = $request->validate([
                'para' => 'required|string',
                'img' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            $carousel = new Carousel();

            if ($request->hasFile('img')) {
                $image = $request->file('img');
                $imageName = time() . '_' . Str::random(5) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageName);
                $carousel->img = $imageName;
            }

            $carousel->para = $data['para'];
            $carousel->save();

            return response()->json([
                'success' => true,
                'message' => 'Carousel item created successfully',
                'data' => $carousel,
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
                'message' => 'Failed to create carousel item: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Update carousel item (admin only)
     */
    public function apiUpdate(Request $request, $id)
    {
        try {
            $carousel = Carousel::findOrFail($id);

            $data = $request->validate([
                'para' => 'sometimes|required|string',
                'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            if ($request->hasFile('img')) {
                $image = $request->file('img');
                $imageName = time() . '_' . Str::random(5) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageName);
                $carousel->img = $imageName;
            }

            if (isset($data['para'])) {
                $carousel->para = $data['para'];
            }

            $carousel->save();

            return response()->json([
                'success' => true,
                'message' => 'Carousel item updated successfully',
                'data' => $carousel,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Carousel item not found',
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
                'message' => 'Failed to update carousel item: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Delete carousel item (admin only)
     */
    public function apiDelete($id)
    {
        try {
            $carousel = Carousel::findOrFail($id);
            $carousel->delete();

            return response()->json([
                'success' => true,
                'message' => 'Carousel item deleted successfully',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Carousel item not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete carousel item: ' . $e->getMessage(),
            ], 500);
        }
    }
}