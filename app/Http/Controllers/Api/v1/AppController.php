<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponse;

class AppController extends Controller
{
    use ApiResponse;

    public function checkUpdate()
    {
        return $this->success('App update status fetched', [
            'minimum_version' => '1.0.0',
            'latest_version' => '1.0.0',
            'update_required' => false,
            'download_url' => null,
        ]);
    }
}
