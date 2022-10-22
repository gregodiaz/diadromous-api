<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserTokenController extends Controller
{

    public function createUser(AuthRequest $request)
    {
        $valid_data = $request->validated();

        /**
         * @var User $user
         * */
        $user = User::create([
            'name' => $valid_data['name'],
            'email' => $valid_data['email'],
            'password' => Hash::make($valid_data['password']),
        ]);

        $token = $user->createToken($valid_data['name'])->plainTextToken;

        return response()->json(compact('token'));
    }

    public function loginUser(AuthRequest $request)
    {
        $valid_data = $request->validated();

        /**
         * @var User $user
         * */
        $user = User::where('email', $valid_data['email'])->first();

        if (!$user || !Hash::check($valid_data['password'], $user->password)) {
            throw ValidationException::withMessages(['email' => 'The provided credentials are incorrect.']);
        }

        $token = $user->createToken($valid_data['name'])->plainTextToken;

        return response()->json(compact('token'));
    }
}
