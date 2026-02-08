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

    // ==================== API METHODS ====================

    /**
     * API: Register a new user
     */
    public function apiRegister(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6|confirmed',
            ]);

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => 'user', // Default role
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully',
                'user' => $user,
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
                'message' => 'Registration failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Login user and return token
     */
    public function apiLogin(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials',
                ], 401);
            }

            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'user' => $user,
                'token' => $token,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Logout user (revoke token)
     */
    public function apiLogout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logout successful',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Logout failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Get current user profile
     */
    public function apiGetProfile(Request $request)
    {
        try {
            $user = $request->user();

            return response()->json([
                'success' => true,
                'message' => 'Profile retrieved',
                'user' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve profile: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Update user profile
     */
    public function apiUpdateProfile(Request $request)
    {
        try {
            $user = $request->user();

            $data = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            ]);

            $user->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'user' => $user,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Update failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Change user password
     */
    public function apiChangePassword(Request $request)
    {
        try {
            $user = $request->user();

            $data = $request->validate([
                'current_password' => 'required',
                'password' => 'required|min:6|confirmed',
            ]);

            if (!Hash::check($data['current_password'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect',
                ], 401);
            }

            $user->update([
                'password' => Hash::make($data['password']),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password changed successfully',
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Change password failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Get all users (admin only)
     */
    public function apiGetAllUsers(Request $request)
    {
        try {
            $users = User::all();

            return response()->json([
                'success' => true,
                'message' => 'Users retrieved',
                'data' => $users,
                'count' => $users->count(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve users: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Get single user (admin only)
     */
    public function apiGetUser($id)
    {
        try {
            $user = User::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'User retrieved',
                'data' => $user,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve user: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Update user (admin only)
     */
    public function apiUpdateUser(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $data = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|unique:users,email,' . $id,
                'role' => 'sometimes|required|in:user,admin',
                'password' => 'sometimes|required|min:6',
            ]);

            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            $user->update($data);

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'data' => $user,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
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
                'message' => 'Update failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Delete user (admin only)
     */
    public function apiDeleteUser($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Delete failed: ' . $e->getMessage(),
            ], 500);
        }
    }

}

