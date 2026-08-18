<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\PropertySearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AiSearchController extends Controller
{
    public function __construct(
        protected PropertySearchService $searchService
    ) {}

    /**
     * Handle AI conversational property search.
     * Endpoint: POST /api/v1/ai/search or POST /ai/search
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => 'nullable|string|max:500',
            'query' => 'nullable|string|max:500',
            'filters' => 'nullable|array',
        ]);

        $message = $validated['message'] ?? $validated['query'] ?? '';
        $overrideFilters = $validated['filters'] ?? [];

        $response = $this->searchService->search($message, $overrideFilters);

        return response()->json($response);
    }
}
