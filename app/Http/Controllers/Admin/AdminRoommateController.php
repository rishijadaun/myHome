<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoommatePost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminRoommateController extends Controller
{
    /**
     * Display a paginated listing of all flatmate & roommate posts with comprehensive filtering.
     */
    public function index(Request $request)
    {
        // 1. Live Statistics
        $totalCount = RoommatePost::count();
        $activeCount = RoommatePost::where('status', 'active')->count();
        $filledCount = RoommatePost::where('status', 'filled')->count();
        $expiredCount = RoommatePost::where('status', 'expired')->count();
        $haveRoomCount = RoommatePost::where('post_type', 'have_room')->count();
        $needRoomCount = RoommatePost::where('post_type', 'need_room')->count();
        $totalViews = RoommatePost::sum('view_count');

        // 2. Query Builder with Eager Loading
        $query = RoommatePost::with(['user.profile']);

        // Search Filter
        if ($request->filled('search')) {
            $search = trim($request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('poster_name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('locality', 'like', "%{$search}%")
                  ->orWhere('profession', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('email', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        // Post Type Filter (Have Room vs Need Room)
        if ($request->filled('post_type') && in_array($request->query('post_type'), ['have_room', 'need_room'])) {
            $query->where('post_type', $request->query('post_type'));
        }

        // Status Filter
        if ($request->filled('status') && $request->query('status') !== 'all') {
            $query->where('status', $request->query('status'));
        }

        // City Filter
        if ($request->filled('city') && $request->query('city') !== 'all') {
            $query->where('city', $request->query('city'));
        }

        // Gender Preference Filter
        if ($request->filled('gender_pref') && $request->query('gender_pref') !== 'all') {
            $query->where('gender_preference', $request->query('gender_pref'));
        }

        // BHK Type Filter
        if ($request->filled('bhk_type') && $request->query('bhk_type') !== 'all') {
            $query->where('bhk_type', $request->query('bhk_type'));
        }

        // 3. Sorting & Pagination
        $sort = $request->query('sort', 'latest');
        match ($sort) {
            'oldest' => $query->oldest('created_at'),
            'rent_high' => $query->orderByDesc('budget_max'),
            'rent_low' => $query->orderBy('budget_max'),
            'views' => $query->orderByDesc('view_count'),
            default => $query->latest('created_at'),
        };

        $roommates = $query->paginate(15)->withQueryString();

        // 4. Auxiliary Lists for Filters
        $cities = RoommatePost::distinct()->whereNotNull('city')->where('city', '!=', '')->orderBy('city')->pluck('city');
        $bhkOptions = RoommatePost::bhkOptions();

        return view('admin.roommates', compact(
            'roommates',
            'totalCount',
            'activeCount',
            'filledCount',
            'expiredCount',
            'haveRoomCount',
            'needRoomCount',
            'totalViews',
            'cities',
            'bhkOptions'
        ));
    }

    /**
     * Delete a flatmate listing.
     */
    public function destroy(Request $request, $id)
    {
        $post = RoommatePost::findOrFail($id);
        $title = $post->title ?: $post->poster_name;
        $post->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Flatmate listing \"{$title}\" has been permanently deleted.",
            ]);
        }

        return redirect()->route('admin.roommates')->with('success', "Flatmate listing \"{$title}\" has been deleted.");
    }

    /**
     * Toggle or update flatmate listing status.
     */
    public function toggleStatus(Request $request, $id)
    {
        $post = RoommatePost::findOrFail($id);
        $newStatus = $request->input('status');

        if (in_array($newStatus, ['active', 'filled', 'expired', 'rejected'])) {
            $post->status = $newStatus;
            $post->is_active = ($newStatus === 'active');
            $post->save();
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Status updated to " . ucfirst($post->status) . ".",
                'status'  => $post->status,
            ]);
        }

        return redirect()->back()->with('success', "Status updated to " . ucfirst($post->status) . ".");
    }

    /**
     * Bulk Delete Flatmate Listings.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:roommate_posts,id',
        ]);

        $count = RoommatePost::whereIn('id', $request->input('ids'))->delete();

        return response()->json([
            'success' => true,
            'message' => "{$count} flatmate listing(s) deleted successfully.",
        ]);
    }
}
