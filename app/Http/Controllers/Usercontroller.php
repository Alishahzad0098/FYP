<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;

class Usercontroller extends Controller
{
    // Show registration form
    public function view()
    {
        return view('register');
    }

    // Show login form
    public function loginpage()
    {
        return view('login');
    }

    // Handle registration
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:user,admin',
            'password' => 'required|confirmed|min:6',
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'password' => Hash::make($data['password']),
        ]);

        return redirect()->route('loginpage')->with('success', 'Registration successful.');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->route('dashboard');
            } else {
                return redirect()->route('home');
            }
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

    // Admin dashboard
    public function dashboardpage()
    {
        return view('layout.dashboard'); // middleware ensures only admin can access
    }

    // Show all regular users
    public function table()
    {
        $users = User::where('role', 'user')->get();
        return view('authtable', compact('users'));
    }

    // Show all admins
    public function admintable()
    {
        $admins = User::where('role', 'admin')->get();
        return view('admintable', compact('admins'));
    }

    // Show edit form for a user
    public function edituser($id)
    {
        $user = User::findOrFail($id);
        return view('useredit', compact('user'));
    }

    // Update a user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$id}",
            'role' => 'required|in:user,admin',
            'password' => 'nullable|min:6',
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->role = $data['role'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return redirect()->route('authtable')->with('success', 'User updated successfully.');
    }

    // Delete a user
    public function deleteuser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('authtable')->with('success', 'User deleted successfully.');
    }
}
