<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    use ApiResponse;

    public function dashboard(Request $request)
    {
        return $this->success('Admin dashboard loaded', [
            'admin' => $request->user(),
            'message' => 'Use the admin API to manage users and content.',
        ]);
    }

    public function users(Request $request)
    {
        return $this->success('User list fetched', [
            'users' => [],
        ]);
    }
}
