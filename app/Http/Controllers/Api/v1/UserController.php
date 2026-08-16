<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ApiResponse;

    public function dashboard(Request $request)
    {
        return $this->success('User dashboard loaded', [
            'user' => $request->user(),
            'message' => 'Welcome to the user module API.',
        ]);
    }

    public function profile(Request $request)
    {
        return $this->success('User profile fetched', [
            'profile' => $request->user()->only(['id', 'name', 'email']),
        ]);
    }

    public function deleteAccount(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        $user->delete();

        return $this->success('Your account was deleted successfully');
    }
}
