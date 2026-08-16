<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponse;
use Illuminate\Http\Request;

class BrokerController extends Controller
{
    use ApiResponse;

    public function dashboard(Request $request)
    {
        return $this->success('Broker dashboard loaded', [
            'broker' => $request->user(),
            'message' => 'Manage broker listings and leads.',
        ]);
    }

    public function listings(Request $request)
    {
        return $this->success('Broker listings fetched', [
            'listings' => [],
        ]);
    }
}
