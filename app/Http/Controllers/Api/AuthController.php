<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        $token = $user->createToken('Personal Access Token', [$user->role])->accessToken;

        return response()->json([
            'message' => 'signin successfully',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * Login user
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['data sign success'],
            ]);
        }
        /** @var \App\Models\User $user */
        $user = Auth::user();
        switch ($user->role) {
            case 'super_admin':
                //  Super Admin:(admin + super_admin)
                $scope = ['admin', 'super_admin'];
                break;

            case 'admin':
                //  Admin: (admin)
                $scope = ['admin'];
                break;

            case 'user':
            default:
                //  User : (user)
                $scope = ['user'];
                break;
        }


        $token = $user->createToken('Personal Access Token', $scope)->accessToken;
        return response()->json([
            'message' => 'done signin successfully',
            'user' => $user,
            'token' => $token,
        ],201);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'done logout successfully',
        ]);
    }

    /**
     * Get authenticated user
     */
    public function user(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }
}
