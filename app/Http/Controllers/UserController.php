<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Register a new user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'role' => 'required',
            'email' => 'required|unique:users,email|email',
            'password' => 'required|min:8',
        ]);

        $user = new User;
        $user->id = \Ramsey\Uuid\Uuid::uuid4();
        $user->name = $request->name;
        $user->role = 'admin';
        $user->email = $request->email;
        $user->password = app('hash')->make($request->password);
        $user->save();

        // event(new Registered($user));

        return response()->json(['message' => 'User created successfully'], 201);
    }

    /**
     * Login a user and create a token.
     *
     * @param  Request  $request
     * @return Response
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json(['message' => 'Login failed'], 401);
        }

        $isValidPassword = app('hash')->check($password, $user->password);
        if (!$isValidPassword) {
            return response()->json(['message' => 'Login failed'], 401);
        }

        $generateToken = bin2hex(random_bytes(40));
        $user->update([
            'token' => $generateToken
        ]);

        return response()->json($user);
    }

    /**
     * reset a user password.
     *
     * @param  Request  $request
     * @return Response
     */
    public function resetPassword(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'newpassword' => 'required|min:8',
            'oldpassword' => 'required|min:8'
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json(['message' => 'Reset failed'], 401);
        }
        if (!app('hash')->check($request->oldpassword, $user->password)) {
            return response()->json(['message' => 'Reset failed'], 401);
        }

        $user->update([
            'password' => app('hash')->make($password)
        ]);

        return response()->json(['message' => 'Reset successfully'], 200);
    }

    /**
     * logout a user.
     * @param  Request  $request
     * @return Response
     */
    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = User::where('token', $request->token)->first();
        if (!$user) {
            return response()->json(['message' => 'Logout failed'], 401);
        }

        $user->update([
            'token' => null
        ]);

        return response()->json(['message' => 'Logout successfully'], 200);
    }

    /**
     * get a user.
     * @param  Request  $request
     * @return Response
     */
    public function getUser(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = User::where('token', $request->token)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 401);
        }

        return response()->json($user);
    }

    /**
     * get all users.
     * @param  Request  $request
     * @return Response
     */
    public function getAllUsers(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = User::where('token', $request->token)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 401);
        }

        $users = User::all();

        return response()->json($users);
    }
}
