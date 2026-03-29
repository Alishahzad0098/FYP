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
        $request->validate([
            'img' => 'required|image|mimes:jpg,jpeg,png,webp|max:5048'
        ]);

        $c1 = new Carousel();
        $image = $request->file('img');

        $imageName = time() . '_' . Str::random(5) . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images'), $imageName);
        $c1->img = $imageName;

        $c1->save();

        return redirect()->back()->with('success', 'Banner uploaded successfully!');
    }

    public function destroy($id)
    {
        $banner = Carousel::findOrFail($id);
        
        // Delete image file from public/images
        $imagePath = public_path('images/' . $banner->img);
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        $banner->delete();

        return redirect()->back()->with('success', 'Banner deleted successfully!');
    }
}