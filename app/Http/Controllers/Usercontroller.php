<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;
class Usercontroller extends Controller
{
    public function page()
    {
        return view('view');
    }
    public function view()
    {
        return view('register');
    }
    public function table()
{
    if (Auth::check()) {
        if (Auth::user()->role === 'admin') {
            $users = User::where('role', 'user')->get(); // renamed
            return view('authtable', compact('users')); // pass plural
        } else {
            return redirect()->route('home');
        }
    } else {
        return redirect()->route('loginpage');
    }
}

public function admintable()
{
    if (Auth::check()) {
        if (Auth::user()->role === 'admin') {
            $admins = User::where('role', 'admin')->get(); // renamed
            return view('admintable', compact('admins')); // pass plural
        } else {
            return redirect()->route('home');
        }
    } else {
        return redirect()->route('loginpage');
    }
}

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            return redirect()->route('dashboard');
        }

        // If login fails
        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'role' => 'required',
            'password' => 'required|confirmed',
        ]);
        $user = User::create($data);
        return redirect()->route('loginpage');
    }
    public function dashboardpage()
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'admin') {
                return view('layout.dashboard');
            } else {
                return redirect()->route('home');
            }
        } else {
            return redirect()->route('loginpage');
        }

    }
    public function loginpage()
    {
        return view('login');
    }
    public function edituser($id)
    {
        $u1 = User::find($id);
        return view('useredit', compact('u1'));
    }
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$id}",
            'role' => 'required|in:user,admin',
            'password' => 'nullable|min:6', // password is optional
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->role = $data['role'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return redirect()->route('authtable');
    }
    public function deleteuser($id)
{
    $user = User::find($id);

    if ($user) {
        $user->delete();
    }

    return redirect()->route('authtable')->with('success', 'User deleted successfully.');
}
}