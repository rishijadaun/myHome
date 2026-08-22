<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Property;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate dynamic XML Sitemap for Search Engines (Google, Bing, Yahoo).
     */
    public function index(): Response
    {
        // 1. Static high-priority pages
        $staticPages = [
            [
                'url' => route('user.home'),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '1.0'
            ],
            [
                'url' => route('user.search'),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '0.9'
            ],
            [
                'url' => route('user.location'),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '0.85'
            ],
            [
                'url' => route('user.list-property'),
                'lastmod' => now()->subDays(2)->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.8'
            ],
            [
                'url' => route('user.pricing'),
                'lastmod' => now()->subDays(5)->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.7'
            ],
            [
                'url' => route('user.about'),
                'lastmod' => now()->subDays(7)->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.7'
            ],
            [
                'url' => route('user.contact'),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.7'
            ],
            [
                'url' => route('user.terms'),
                'lastmod' => now()->subDays(30)->toAtomString(),
                'changefreq' => 'yearly',
                'priority' => '0.4'
            ],
            [
                'url' => route('user.privacy'),
                'lastmod' => now()->subDays(30)->toAtomString(),
                'changefreq' => 'yearly',
                'priority' => '0.4'
            ],
        ];

        // 2. City landing pages
        $cities = City::where('is_active', 1)->get();
        $cityPages = [];
        foreach ($cities as $city) {
            $citySlug = strtolower(trim(preg_replace('/\s*\(.*?\)\s*/', '', $city->name)));
            $cityPages[] = [
                'url' => route('user.search', ['city' => $citySlug]),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '0.85'
            ];
        }

        // 3. Dynamic active, verified property detail pages
        $properties = Property::where('status', 'active')
            ->where('verification_status', 'verified')
            ->where('is_active', 1)
            ->with(['city', 'area'])
            ->latest('updated_at')
            ->get();

        $propertyPages = [];
        foreach ($properties as $property) {
            $propertyPages[] = [
                'url' => route('user.detail', ['slug' => $property->slug ?: $property->id]),
                'lastmod' => ($property->updated_at ?? now())->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.8'
            ];
        }

        return response()->view('sitemap', [
            'staticPages' => $staticPages,
            'cityPages' => $cityPages,
            'propertyPages' => $propertyPages,
        ])->header('Content-Type', 'application/xml; charset=utf-8');
    }
}
