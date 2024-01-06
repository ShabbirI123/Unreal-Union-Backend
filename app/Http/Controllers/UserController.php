<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Register new user
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'unique:user,username'],
            'password' => ['required', 'string']
        ], $messages = [
            'username.unique' => 'This username is already taken!'
        ]);

        if ($validator->fails()) {
            return response()->json([$validator->errors()], 409);
        }

        $validated = $validator->safe()->all();

        try {
            $user = new User;

            $user->username = $validated['username'];
            $user->password = Hash::make($validated['password']);

            $user->save();

            return response()->json(status: 201);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string']
        ]);

        $dbUser = User::where('username', $validated['username'])->first();

        if ($dbUser) {
            if (Hash::check($validated['password'], $dbUser->password)) {
                return response()->json();
            } else {
                return response()->json(['error' => 'Invalid credentials'], 404);
            }
        } else {
            return response()->json(['error' => 'Invalid credentials'], 404);
        }
    }
}
