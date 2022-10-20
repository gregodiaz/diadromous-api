<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserTokenController extends Controller
{
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    public function __invoke(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        /**
         * @var User $user
         * */
        $user = User::where('email', $request->get('email'))->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages(['email' => 'The provided credentials are incorrect.']);
        }

        $token = $user->createToken($request->name)->plainTextToken;

        return response()->json(compact('token'));
    }
}
