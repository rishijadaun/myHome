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
        $xmlContent = \Illuminate\Support\Facades\Cache::remember('staynest_seo_sitemap_xml_v2', 3600, function () {
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
                    'priority' => '0.95'
                ],
                [
                    'url' => route('user.search', ['type' => 'pg-hostel']),
                    'lastmod' => now()->toAtomString(),
                    'changefreq' => 'daily',
                    'priority' => '0.90'
                ],
                [
                    'url' => route('user.search', ['type' => 'flat-apartment']),
                    'lastmod' => now()->toAtomString(),
                    'changefreq' => 'daily',
                    'priority' => '0.90'
                ],
                [
                    'url' => route('user.search', ['type' => 'commercial']),
                    'lastmod' => now()->toAtomString(),
                    'changefreq' => 'daily',
                    'priority' => '0.90'
                ],
                [
                    'url' => route('user.location'),
                    'lastmod' => now()->toAtomString(),
                    'changefreq' => 'daily',
                    'priority' => '0.85'
                ],
                [
                    'url' => route('user.list-property'),
                    'lastmod' => now()->subDays(1)->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.8'
                ],
                [
                    'url' => route('user.pricing'),
                    'lastmod' => now()->subDays(3)->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.7'
                ],
                [
                    'url' => route('user.about'),
                    'lastmod' => now()->subDays(5)->toAtomString(),
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

            // 2. City landing pages and City + Category landing pages
            $cities = City::where('is_active', 1)->get();
            $cityPages = [];
            $categories = ['pg-hostel', 'flat-apartment', 'commercial'];

            foreach ($cities as $city) {
                $citySlug = strtolower(trim(preg_replace('/\s*\(.*?\)\s*/', '', $city->name)));
                $cityPages[] = [
                    'url' => route('user.search', ['city' => $citySlug]),
                    'lastmod' => now()->toAtomString(),
                    'changefreq' => 'daily',
                    'priority' => '0.85'
                ];

                foreach ($categories as $cat) {
                    $cityPages[] = [
                        'url' => route('user.search', ['city' => $citySlug, 'type' => $cat]),
                        'lastmod' => now()->toAtomString(),
                        'changefreq' => 'weekly',
                        'priority' => '0.80'
                    ];
                }
            }

            // 3. Dynamic active, verified property detail pages
            $properties = Property::where('status', 'active')
                ->where('verification_status', 'verified')
                ->where('is_active', 1)
                ->with(['city', 'area', 'primaryImage', 'images'])
                ->latest('updated_at')
                ->get();

            $propertyPages = [];
            foreach ($properties as $property) {
                $propertyPages[] = [
                    'url' => route('user.detail', ['slug' => $property->slug ?: $property->id]),
                    'lastmod' => ($property->updated_at ?? now())->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.85',
                    'image' => $property->display_image_url,
                    'title' => $property->name
                ];
            }

            return view('sitemap', [
                'staticPages' => $staticPages,
                'cityPages' => $cityPages,
                'propertyPages' => $propertyPages,
            ])->render();
        });

        return response($xmlContent, 200)
            ->header('Content-Type', 'application/xml; charset=utf-8');
    }
}
