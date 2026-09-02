<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\RoommateMessage;
use App\Models\RoommatePost;
use App\Models\User;
use App\Services\ContentModerationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RoommateController extends Controller
{
    /**
     * Display a listing of active roommate/flatmate posts.
     */
    public function index(Request $request)
    {
        $query = RoommatePost::active()->with(['user.profile']);

        // Natural query search (keyword, location, roommate type)
        $searchQ = $request->query('q') ?? $request->query('search');
        if ($searchQ) {
            $terms = explode(' ', trim($searchQ));
            $query->where(function ($q) use ($terms, $searchQ) {
                $q->where('title', 'like', "%{$searchQ}%")
                  ->orWhere('description', 'like', "%{$searchQ}%")
                  ->orWhere('city', 'like', "%{$searchQ}%")
                  ->orWhere('locality', 'like', "%{$searchQ}%");

                foreach ($terms as $t) {
                    $t = trim($t);
                    if (strlen($t) >= 2) {
                        $q->orWhere('city', 'like', "%{$t}%")
                          ->orWhere('locality', 'like', "%{$t}%")
                          ->orWhere('title', 'like', "%{$t}%");
                    }
                }
            });
        }

        // City filter
        if ($request->filled('city')) {
            $city = trim($request->city);
            $query->where(function ($q) use ($city) {
                $q->where('city', 'like', "%{$city}%")
                  ->orWhere('locality', 'like', "%{$city}%");
            });
        }

        // Post Type filter
        if ($request->filled('type')) {
            $query->where('post_type', $request->type);
        }

        // Gender preference filter
        if ($request->filled('gender')) {
            $gender = strtolower($request->gender);
            if ($gender === 'boys' || $gender === 'male') {
                $query->where(function ($q) {
                    $q->where('gender_preference', 'male')->orWhere('gender_preference', 'any');
                });
            } elseif ($gender === 'girls' || $gender === 'female') {
                $query->where(function ($q) {
                    $q->where('gender_preference', 'female')->orWhere('gender_preference', 'any');
                });
            } elseif ($gender === 'co-ed' || $gender === 'any') {
                $query->where('gender_preference', 'any');
            } else {
                $query->where('gender_preference', $gender);
            }
        }

        // BHK Type filter
        if ($request->filled('bhk')) {
            $query->where('bhk_type', $request->bhk);
        }

        // Furnishing filter
        if ($request->filled('furnishing')) {
            $query->where('furnishing', $request->furnishing);
        }

        // Occupation filter
        if ($request->filled('occupation')) {
            $occupation = $request->occupation;
            $query->where(function ($q) use ($occupation) {
                $q->where('occupation_type', $occupation)
                  ->orWhere('occupation_type', 'any');
            });
        }

        // Budget presets or explicit max budget
        $budget = $request->query('budget');
        if ($budget) {
            if ($budget === '6000') {
                $query->where('budget_max', '<=', 6000);
            } elseif ($budget === '8000') {
                $query->where('budget_max', '<=', 8000);
            } elseif ($budget === '10000') {
                $query->whereBetween('budget_max', [6000, 10000]);
            } elseif ($budget === '12000') {
                $query->where('budget_max', '<=', 12000);
            } elseif ($budget === '15000') {
                $query->whereBetween('budget_max', [10000, 15000]);
            } elseif ($budget === '15000+' || $budget === '15000-plus') {
                $query->where('budget_max', '>=', 15000);
            }
        } elseif ($request->filled('budget_max') && is_numeric($request->budget_max)) {
            $query->where('budget_max', '<=', (int) $request->budget_max);
        }

        // Amenities flags
        if ($request->filled('ac') && $request->ac) {
            $query->whereJsonContains('amenities->ac', true);
        }
        if ($request->filled('fridge') && $request->fridge) {
            $query->whereJsonContains('amenities->fridge', true);
        }
        if ($request->filled('wifi') && $request->wifi) {
            $query->whereJsonContains('amenities->wifi', true);
        }
        if ($request->filled('food') && $request->food) {
            $query->whereJsonContains('amenities->food', true);
        }

        // Sorting
        $sort = $request->query('sort', 'newest');
        if ($sort === 'price-asc') {
            $query->orderBy('budget_min', 'asc');
        } elseif ($sort === 'price-desc') {
            $query->orderBy('budget_max', 'desc');
        } elseif ($sort === 'immediate') {
            $query->orderBy('move_in_date', 'asc');
        } else {
            $query->latest();
        }

        $posts = $query->paginate(12)->withQueryString();
        $totalActive = RoommatePost::active()->count();
        $popularCities = RoommatePost::popularCities();
        $bhkOptions = RoommatePost::bhkOptions();
        $lifestyleOptions = RoommatePost::lifestyleOptions();
        $amenitiesOptions = RoommatePost::amenitiesOptions();

        return view('user.roommate.index', compact(
            'posts',
            'totalActive',
            'popularCities',
            'bhkOptions',
            'lifestyleOptions',
            'amenitiesOptions'
        ));
    }

    /**
     * Show the form for creating a new room listing.
     */
    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('user.login')->with('flash_info', 'Please sign in or create an account to post your room listing.');
        }

        /** @var User $user */
        $user = Auth::user();
        $user->load('profile');

        // Check if user already has an active post (1 active post limit)
        $existingPost = RoommatePost::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('is_active', true)
            ->first();

        if ($existingPost) {
            return redirect()->route('user.roommate.edit', $existingPost->slug)->with(
                'flash_info',
                'You already have an active room listing. You can manage or update it below, or mark it as filled to create a new one.'
            );
        }

        $popularCities = RoommatePost::popularCities();
        $bhkOptions = RoommatePost::bhkOptions();
        $lifestyleOptions = RoommatePost::lifestyleOptions();
        $amenitiesOptions = RoommatePost::amenitiesOptions();
        $isEdit = false;
        $post = null;

        return view('user.roommate.create', compact(
            'user',
            'popularCities',
            'bhkOptions',
            'lifestyleOptions',
            'amenitiesOptions',
            'isEdit',
            'post'
        ));
    }

    /**
     * Store a newly created room listing in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('user.login')->with('flash_info', 'Please sign in to publish your room listing.');
        }

        /** @var User $user */
        $user = Auth::user();
        $user->load('profile');

        $validated = $request->validate([
            'city'                      => ['required', 'string', 'min:2', 'max:100'],
            'locality'                  => ['nullable', 'string', 'max:150'],
            'bhk_type'                  => ['required', 'string', 'in:single_room,1bhk,2bhk,3bhk,studio,any'],
            'furnishing'                => ['required', 'string', 'in:furnished,semi_furnished,unfurnished,any'],
            'move_in_date'              => ['nullable', 'date'],
            'budget_max'                => ['required', 'numeric', 'min:500', 'max:500000'],
            'preferred_duration_months' => ['nullable', 'integer', 'min:1', 'max:60'],
            'gender_preference'         => ['required', 'string', 'in:female,male,any'],
            'lifestyle'                 => ['nullable', 'array'],
            'amenities'                 => ['nullable', 'array'],
            'description'               => ['nullable', 'string', 'max:1500'],
        ], [
            'city.required'              => 'Please specify the city.',
            'bhk_type.required'          => 'Please select the room type.',
            'budget_max.required'        => 'Please provide expected monthly rent.',
            'budget_max.min'             => 'Monthly rent must be at least ₹500.',
            'gender_preference.required' => 'Please select flatmate gender preference.',
        ]);

        // Server-side content moderation
        $modCheck = ContentModerationService::validateContent([
            'name'        => $validated['city'] . ' ' . ($validated['locality'] ?? ''),
            'description' => $validated['description'] ?? '',
        ]);

        if (!$modCheck['passed']) {
            return back()->withInput()->withErrors([
                'description' => $modCheck['reason'] ?? 'Your listing contains prohibited or restricted terms. Please revise to proceed.'
            ]);
        }

        if (!empty($validated['locality'])) {
            $modLocality = ContentModerationService::validateContent(['name' => $validated['locality']]);
            if (!$modLocality['passed']) {
                return back()->withInput()->withErrors([
                    'locality' => $modLocality['reason'] ?? 'Locality contains prohibited terms.'
                ]);
            }
        }

        // Deactivate any previous active posts to enforce 1 active post rule
        RoommatePost::where('user_id', $user->id)
            ->where('status', 'active')
            ->update([
                'status'    => 'filled',
                'is_active' => false,
            ]);

        $profile = $user->profile;
        $posterName = $profile?->full_name ?: ($user->name ?? 'Tenant');
        $posterAge = $profile?->age;
        $posterGender = $profile?->gender ? strtolower($profile->gender) : null;
        $profession = $profile?->occupation ?: 'Working Professional';
        $occupationType = 'working_professional';

        $bhkLabels = RoommatePost::bhkOptions();
        $bhkLabel = $bhkLabels[$validated['bhk_type']] ?? 'Room';
        $locPart = !empty($validated['locality']) ? trim($validated['locality']) . ', ' : '';
        $title = "{$bhkLabel} in {$locPart}" . trim($validated['city']);

        $post = RoommatePost::create([
            'user_id'                   => $user->id,
            'title'                     => $title,
            'post_type'                 => 'have_room',
            'city'                      => trim($validated['city']),
            'locality'                  => !empty($validated['locality']) ? trim($validated['locality']) : null,
            'full_address'              => !empty($validated['locality']) ? (trim($validated['locality']) . ', ' . trim($validated['city'])) : trim($validated['city']),
            'poster_name'               => $posterName,
            'poster_age'                => $posterAge,
            'poster_gender'             => in_array($posterGender, ['male', 'female', 'other']) ? $posterGender : null,
            'profession'                => $profession,
            'occupation_type'           => $occupationType,
            'gender_preference'         => $validated['gender_preference'],
            'bhk_type'                  => $validated['bhk_type'],
            'furnishing'                => $validated['furnishing'],
            'budget_min'                => null,
            'budget_max'                => (int) $validated['budget_max'],
            'move_in_date'              => !empty($validated['move_in_date']) ? $validated['move_in_date'] : null,
            'preferred_duration_months' => !empty($validated['preferred_duration_months']) ? (int) $validated['preferred_duration_months'] : null,
            'lifestyle'                 => $validated['lifestyle'] ?? [],
            'amenities'                 => $validated['amenities'] ?? [],
            'description'               => $validated['description'] ?? null,
            'contact_phone'             => $user->phone ?? null,
            'contact_whatsapp'          => $user->phone ?? null,
            'contact_visible_to_all'    => false,
            'poster_avatar_url'         => $profile?->avatar_url,
            'status'                    => 'active',
            'is_active'                 => true,
            'expires_at'                => now()->addDays(30),
        ]);

        return redirect()->route('user.roommate.show', $post->slug)->with(
            'flash_success',
            '🎉 Your room listing is live! You can manage or update it anytime.'
        );
    }

    /**
     * Display the specified room listing.
     */
    public function show(string $slug)
    {
        $post = RoommatePost::where('slug', $slug)->firstOrFail();

        // Throttle view count increment per session to eliminate write spam
        $viewKey = 'viewed_roommate_post_' . $post->id;
        if (!session()->has($viewKey)) {
            $post->increment('view_count');
            session()->put($viewKey, true);
        }

        $post->load(['user.profile']);

        $related = RoommatePost::active()
            ->where('id', '!=', $post->id)
            ->where('city', $post->city)
            ->with(['user.profile'])
            ->take(4)
            ->get();

        $similarPosts = $related;

        return view('user.roommate.show', compact('post', 'related', 'similarPosts'));
    }

    /**
     * Show the form for editing the specified room listing.
     */
    public function edit(string $slug)
    {
        if (!Auth::check()) {
            return redirect()->route('user.login')->with('flash_info', 'Please sign in to edit your listing.');
        }

        /** @var User $user */
        $user = Auth::user();
        $post = RoommatePost::where('slug', $slug)->firstOrFail();

        // Check ownership
        if ($post->user_id !== $user->id) {
            abort(403, 'You do not have permission to edit this listing.');
        }

        $user->load('profile');
        $popularCities = RoommatePost::popularCities();
        $bhkOptions = RoommatePost::bhkOptions();
        $lifestyleOptions = RoommatePost::lifestyleOptions();
        $amenitiesOptions = RoommatePost::amenitiesOptions();
        $isEdit = true;

        return view('user.roommate.create', compact(
            'user',
            'popularCities',
            'bhkOptions',
            'lifestyleOptions',
            'amenitiesOptions',
            'isEdit',
            'post'
        ));
    }

    /**
     * Update the specified room listing in storage.
     */
    public function update(Request $request, string $slug)
    {
        if (!Auth::check()) {
            return redirect()->route('user.login')->with('flash_info', 'Please sign in to update your listing.');
        }

        /** @var User $user */
        $user = Auth::user();
        $post = RoommatePost::where('slug', $slug)->firstOrFail();

        if ($post->user_id !== $user->id) {
            abort(403, 'You do not have permission to edit this listing.');
        }

        $validated = $request->validate([
            'city'                      => ['required', 'string', 'min:2', 'max:100'],
            'locality'                  => ['nullable', 'string', 'max:150'],
            'bhk_type'                  => ['required', 'string', 'in:single_room,1bhk,2bhk,3bhk,studio,any'],
            'furnishing'                => ['required', 'string', 'in:furnished,semi_furnished,unfurnished,any'],
            'move_in_date'              => ['nullable', 'date'],
            'budget_max'                => ['required', 'numeric', 'min:500', 'max:500000'],
            'preferred_duration_months' => ['nullable', 'integer', 'min:1', 'max:60'],
            'gender_preference'         => ['required', 'string', 'in:female,male,any'],
            'lifestyle'                 => ['nullable', 'array'],
            'amenities'                 => ['nullable', 'array'],
            'description'               => ['nullable', 'string', 'max:1500'],
        ], [
            'city.required'              => 'Please specify the city.',
            'bhk_type.required'          => 'Please select the room type.',
            'budget_max.required'        => 'Please provide expected monthly rent.',
            'budget_max.min'             => 'Monthly rent must be at least ₹500.',
            'gender_preference.required' => 'Please select flatmate gender preference.',
        ]);

        // Server-side content moderation
        $modCheck = ContentModerationService::validateContent([
            'name'        => $validated['city'] . ' ' . ($validated['locality'] ?? ''),
            'description' => $validated['description'] ?? '',
        ]);

        if (!$modCheck['passed']) {
            return back()->withInput()->withErrors([
                'description' => $modCheck['reason'] ?? 'Your listing contains prohibited terms. Please revise.'
            ]);
        }

        if (!empty($validated['locality'])) {
            $modLocality = ContentModerationService::validateContent(['name' => $validated['locality']]);
            if (!$modLocality['passed']) {
                return back()->withInput()->withErrors([
                    'locality' => $modLocality['reason'] ?? 'Locality contains prohibited terms.'
                ]);
            }
        }

        $bhkLabels = RoommatePost::bhkOptions();
        $bhkLabel = $bhkLabels[$validated['bhk_type']] ?? 'Room';
        $locPart = !empty($validated['locality']) ? trim($validated['locality']) . ', ' : '';
        $post->title = "{$bhkLabel} in {$locPart}" . trim($validated['city']);

        $post->city = trim($validated['city']);
        $post->locality = !empty($validated['locality']) ? trim($validated['locality']) : null;
        $post->full_address = !empty($validated['locality']) ? (trim($validated['locality']) . ', ' . trim($validated['city'])) : trim($validated['city']);
        $post->gender_preference = $validated['gender_preference'];
        $post->bhk_type = $validated['bhk_type'];
        $post->furnishing = $validated['furnishing'];
        $post->budget_max = (int) $validated['budget_max'];
        $post->move_in_date = !empty($validated['move_in_date']) ? $validated['move_in_date'] : null;
        $post->preferred_duration_months = !empty($validated['preferred_duration_months']) ? (int) $validated['preferred_duration_months'] : null;
        $post->lifestyle = $validated['lifestyle'] ?? [];
        $post->amenities = $validated['amenities'] ?? [];
        $post->description = $validated['description'] ?? null;

        if ($user->profile) {
            $post->poster_name = $user->profile->full_name ?: ($user->name ?? $post->poster_name);
            $post->poster_age = $user->profile->age ?: $post->poster_age;
            if ($user->profile->gender) {
                $post->poster_gender = strtolower($user->profile->gender);
            }
            if ($user->profile->occupation) {
                $post->profession = $user->profile->occupation;
            }
            if ($user->profile->avatar_url) {
                $post->poster_avatar_url = $user->profile->avatar_url;
            }
        }

        $post->save();

        return redirect()->route('user.roommate.show', $post->slug)->with(
            'flash_success',
            'Listing updated successfully!'
        );
    }

    /**
     * Mark a room listing as filled.
     */
    public function markFilled(string $slug)
    {
        if (!Auth::check()) {
            return redirect()->route('user.login')->with('flash_info', 'Please sign in to manage your listing.');
        }

        /** @var User $user */
        $user = Auth::user();
        $post = RoommatePost::where('slug', $slug)->firstOrFail();

        if ($post->user_id !== $user->id) {
            abort(403, 'You do not have permission to modify this listing.');
        }

        $post->status = 'filled';
        $post->is_active = false;
        $post->save();

        return redirect()->route('user.roommate.create')->with(
            'flash_success',
            '🎉 Room marked as FILLED! Your listing has been archived and your active post count reset to 0. You can now post a new room listing.'
        );
    }

    /**
     * Remove the specified room listing from storage.
     */
    public function destroy(string $slug)
    {
        if (!Auth::check()) {
            return redirect()->route('user.login')->with('flash_info', 'Please sign in to delete your listing.');
        }

        /** @var User $user */
        $user = Auth::user();
        $post = RoommatePost::where('slug', $slug)->firstOrFail();

        if ($post->user_id !== $user->id) {
            abort(403, 'You do not have permission to delete this listing.');
        }

        $post->delete();

        return redirect()->route('user.roommate.index')->with(
            'flash_success',
            'Room listing deleted successfully.'
        );
    }

    /**
     * Fetch conversation messages between authenticated user and flatmate for this post.
     */
    public function getMessages(Request $request, string $slug)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please sign in to view conversation.',
            ], 401);
        }

        /** @var User $currentUser */
        $currentUser = Auth::user();
        $post = RoommatePost::where('slug', $slug)->firstOrFail();

        $isOwner = ($post->user_id === $currentUser->id);
        $peerId = $request->query('peer_id');

        $threads = [];
        $activePeer = null;

        if ($isOwner) {
            // Fetch all messages for this post in a single bulk query to avoid N+1 database queries
            $allPostMessages = RoommateMessage::where('roommate_post_id', $post->id)
                ->orderBy('created_at', 'desc')
                ->get();

            $peerGroups = $allPostMessages->groupBy(function ($m) use ($currentUser) {
                return $m->sender_id === $currentUser->id ? $m->receiver_id : $m->sender_id;
            })->filter(fn ($msgs, $key) => !empty($key) && $key !== $currentUser->id);

            $allPeerIds = $peerGroups->keys();

            if ($allPeerIds->isNotEmpty()) {
                if (!$peerId || !$allPeerIds->contains($peerId)) {
                    $peerId = $allPeerIds->first();
                }

                $peers = User::whereIn('id', $allPeerIds)->with('profile')->get()->keyBy('id');
                $threads = $allPeerIds->map(function ($pid) use ($peerGroups, $peers, $currentUser, $peerId) {
                    $msgs = $peerGroups->get($pid, collect());
                    $lastMsg = $msgs->first();
                    $unreadCount = $msgs->where('sender_id', $pid)->where('receiver_id', $currentUser->id)->where('is_read', false)->count();
                    $p = $peers->get($pid);

                    $name = $p?->profile?->full_name ?: ($p?->name ?? ($lastMsg?->sender_name ?? 'Interested User'));
                    return [
                        'user_id'      => $pid,
                        'name'         => $name,
                        'avatar_url'   => $p?->profile?->avatar_url,
                        'last_message' => $lastMsg ? Str::limit($lastMsg->message, 35) : '',
                        'last_time'    => $lastMsg && $lastMsg->created_at ? $lastMsg->created_at->format('h:i A') : '',
                        'unread_count' => $unreadCount,
                        'is_active'    => ($pid === $peerId),
                    ];
                })->values();

                $activePeerUser = $peers->get($peerId);
                if ($activePeerUser) {
                    $activePeer = [
                        'id'         => $activePeerUser->id,
                        'name'       => $activePeerUser->profile?->full_name ?: ($activePeerUser->name ?? 'Interested Flatmate'),
                        'avatar_url' => $activePeerUser->profile?->avatar_url,
                        'gender'     => $activePeerUser->profile?->gender ?? 'any',
                    ];
                }
            }
        }

        $query = RoommateMessage::where('roommate_post_id', $post->id);

        if ($isOwner) {
            if ($peerId) {
                $query->where(function ($q) use ($currentUser, $peerId) {
                    $q->where(function ($sq) use ($currentUser, $peerId) {
                        $sq->where('sender_id', $currentUser->id)->where('receiver_id', $peerId);
                    })->orWhere(function ($sq) use ($currentUser, $peerId) {
                        $sq->where('sender_id', $peerId)->where('receiver_id', $currentUser->id);
                    });
                });
            } else {
                // If owner has no messages yet from any peer
                $query->whereRaw('1 = 0');
            }
        } else {
            // Interested user viewing conversation with post owner
            $ownerId = $post->user_id;
            $query->where(function ($q) use ($currentUser, $ownerId) {
                $q->where(function ($sq) use ($currentUser, $ownerId) {
                    $sq->where('sender_id', $currentUser->id)->where('receiver_id', $ownerId);
                })->orWhere(function ($sq) use ($currentUser, $ownerId) {
                    $sq->where('sender_id', $ownerId)->where('receiver_id', $currentUser->id);
                });
            });
        }

        $messages = $query->orderBy('created_at', 'asc')->get();

        // Mark unread messages as read for current user
        if ($peerId) {
            RoommateMessage::where('roommate_post_id', $post->id)
                ->where('sender_id', $peerId)
                ->where('receiver_id', $currentUser->id)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        } else {
            RoommateMessage::where('roommate_post_id', $post->id)
                ->where('receiver_id', $currentUser->id)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        $formatted = $messages->map(function ($m) use ($currentUser) {
            return [
                'id'          => $m->id,
                'sender_id'   => $m->sender_id,
                'sender_name' => $m->sender_name,
                'message'     => $m->message,
                'is_me'       => ($m->sender_id === $currentUser->id),
                'is_read'     => (bool) $m->is_read,
                'time'        => $m->created_at ? $m->created_at->format('h:i A') : 'Just now',
                'date'        => $m->created_at ? $m->created_at->format('d M Y') : '',
            ];
        });

        // Summary of post for header
        $bhkLabels = RoommatePost::bhkOptions();
        $bhkLabel = $bhkLabels[$post->bhk_type] ?? $post->bhk_type;

        return response()->json([
            'success'        => true,
            'messages'       => $formatted,
            'is_owner'       => $isOwner,
            'threads'        => $threads,
            'active_peer_id' => $peerId,
            'active_peer'    => $activePeer,
            'post'           => [
                'id'                => $post->id,
                'slug'              => $post->slug,
                'title'             => $post->title,
                'poster_name'       => $post->poster_name,
                'poster_avatar_url' => $post->poster_avatar_url,
                'poster_gender'     => $post->poster_gender,
                'gender_icon'       => $post->gender_icon,
                'budget_range'      => $post->budget_range,
                'bhk_type'          => $bhkLabel,
                'locality'          => $post->locality ?: $post->city,
                'city'              => $post->city,
                'gender_preference' => $post->gender_preference,
            ]
        ]);
    }

    /**
     * Send a direct inquiry/message or reply in the WhatsApp-style room discussion.
     */
    public function sendMessage(Request $request, string $slug)
    {
        if (!Auth::check()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please sign in to send a message to this flatmate.',
                ], 401);
            }
            return redirect()->route('user.login')->with('flash_info', 'Please sign in to send a message.');
        }

        /** @var User $sender */
        $sender = Auth::user();
        $post = RoommatePost::where('slug', $slug)->firstOrFail();

        $isOwner = ($post->user_id === $sender->id);
        $receiverId = $isOwner ? $request->input('receiver_id') : $post->user_id;

        if (!$receiverId) {
            return response()->json([
                'success' => false,
                'message' => 'Recipient could not be identified.',
            ], 422);
        }

        // Prevent messaging self if not replying to a peer
        if (!$isOwner && $post->user_id === $sender->id) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot send a message to your own room listing.',
                ], 422);
            }
            return back()->withErrors(['message' => 'You cannot send a message to your own room listing.']);
        }

        $validated = $request->validate([
            'message'      => ['required', 'string', 'min:2', 'max:1000'],
            'sender_phone' => ['nullable', 'string', 'max:30'],
            'sender_email' => ['nullable', 'email', 'max:150'],
        ], [
            'message.required' => 'Please type your message.',
            'message.min'      => 'Message must be at least 2 characters long.',
            'message.max'      => 'Message cannot exceed 1000 characters.',
        ]);

        // Content Moderation & prohibited terms check
        $modCheck = ContentModerationService::validateContent([
            'description' => $validated['message'],
        ]);

        if (!$modCheck['passed']) {
            $reason = $modCheck['reason'] ?? 'Your message contains prohibited or inappropriate content. Please keep communication respectful.';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $reason,
                ], 422);
            }
            return back()->withInput()->withErrors(['message' => $reason]);
        }

        $senderProfile = $sender->profile;
        $senderName = $senderProfile?->full_name ?: ($sender->name ?? 'Interested Flatmate');
        $senderPhone = $sender->phone ?: ($validated['sender_phone'] ?? null);
        $senderEmail = $sender->email ?: ($validated['sender_email'] ?? null);

        $msgObj = RoommateMessage::create([
            'roommate_post_id'  => $post->id,
            'sender_id'         => $sender->id,
            'receiver_id'       => $receiverId,
            'sender_name'       => $senderName,
            'sender_email'      => $senderEmail,
            'sender_phone'      => $senderPhone,
            'message'           => trim($validated['message']),
            'is_read'           => false,
            'moderation_status' => 'passed',
        ]);

        // Notify recipient
        $recipientTitle = $isOwner
            ? '💬 ' . $senderName . ' replied to your roommate message'
            : '💬 New Message from ' . $senderName;

        Notification::create([
            'user_id'    => $receiverId,
            'user_type'  => 'user',
            'title'      => $recipientTitle,
            'message'    => Str::limit(trim($validated['message']), 140),
            'type'       => 'roommate_inquiry',
            'action_url' => route('user.roommate.show', $post->slug),
            'is_read'    => false,
        ]);

        $successMsg = "🎉 Your message has been sent to {$post->poster_name}! They will be notified.";

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'   => true,
                'message'   => $successMsg,
                'data'      => [
                    'id'          => $msgObj->id,
                    'sender_id'   => $msgObj->sender_id,
                    'sender_name' => $msgObj->sender_name,
                    'message'     => $msgObj->message,
                    'is_me'       => true,
                    'is_read'     => false,
                    'time'        => $msgObj->created_at ? $msgObj->created_at->format('h:i A') : 'Just now',
                ],
            ]);
        }

        return back()->with('flash_success', $successMsg);
    }

    /**
     * Standalone Bot discussion reply endpoint.
     */
    public function getBotReply(Request $request, string $slug)
    {
        $post = RoommatePost::where('slug', $slug)->firstOrFail();
        $message = $request->input('message', '');
        $senderName = Auth::check() ? (Auth::user()->name ?? 'there') : 'there';

        $botReply = $this->generateBotDiscussionReply($post, $message, $senderName);

        return response()->json([
            'success'   => true,
            'bot_reply' => $botReply,
        ]);
    }

    /**
     * Generate context-rich WhatsApp style response for roommate discussions.
     */
    protected function generateBotDiscussionReply(RoommatePost $post, string $userMsg, string $userName): array
    {
        $msgLower = strtolower($userMsg);
        $bhkLabels = RoommatePost::bhkOptions();
        $bhkLabel = $bhkLabels[$post->bhk_type] ?? $post->bhk_type;
        $amenityOpts = RoommatePost::amenitiesOptions();
        $activeAmenities = [];
        if ($post->amenities) {
            foreach ($post->amenities as $k => $v) {
                if ($v && isset($amenityOpts[$k])) {
                    $activeAmenities[] = ($amenityOpts[$k]['emoji'] ?? '✨') . ' ' . $amenityOpts[$k]['label'];
                }
            }
        }

        $amenitiesStr = !empty($activeAmenities) ? implode(', ', $activeAmenities) : 'Standard flat amenities available';
        $locationStr = ($post->locality ? $post->locality . ', ' : '') . $post->city;
        $genderPrefStr = $post->gender_preference === 'female' ? '👩 Girls Only' : ($post->gender_preference === 'male' ? '👨 Boys Only' : '🧑 Any Gender');

        $text = "";
        $suggestions = [];

        if (preg_match('/\b(available|status|vacant|still|free)\b/i', $msgLower)) {
            $moveIn = $post->move_in_date ? $post->move_in_date->format('d M Y') : 'Immediately available';
            $text = "Hi *{$userName}*! 👋 Yes, this *{$bhkLabel}* in *{$locationStr}* is currently *ACTIVE & AVAILABLE*.\n\n"
                  . "📅 *Move-in Date:* {$moveIn}\n"
                  . "💰 *Rent:* {$post->budget_range}\n"
                  . "👥 *Preferred:* {$genderPrefStr}\n\n"
                  . "{$post->poster_name} has been notified and will reply shortly. Would you like to schedule a visit or ask about house rules?";
            $suggestions = ['Schedule a Visit 🗓️', 'What are the house rules? 📋', 'Are bills included? 💡'];
        } elseif (preg_match('/\b(rent|price|deposit|budget|cost|maintenance|bill|electricity|wifi)\b/i', $msgLower)) {
            $duration = $post->preferred_duration_months ? "{$post->preferred_duration_months} months minimum" : "Flexible duration";
            $text = "💰 *Rent & Financial Breakdown*:\n\n"
                  . "• *Monthly Rent:* {$post->budget_range}\n"
                  . "• *Stay Duration:* {$duration}\n"
                  . "• *Brokerage:* *₹0 (100% Zero Brokerage)*\n"
                  . "• *Amenities Included:* {$amenitiesStr}\n\n"
                  . "You can discuss exact deposit & utility splitting directly with *{$post->poster_name}* here!";
            $suggestions = ['Is cooking/kitchen allowed? 🍳', 'When can I visit? 🗓️', 'Is this room available? 🔑'];
        } elseif (preg_match('/\b(fridge|ac|wifi|amenity|amenities|kitchen|washing|parking|geyser|maid|cook)\b/i', $msgLower)) {
            $furnishingStr = ucfirst(str_replace('_', ' ', $post->furnishing ?? 'Furnished'));
            $text = "✨ *Flat Amenities & Furnishing*:\n\n"
                  . "• *Furnishing:* {$furnishingStr}\n"
                  . "• *Amenities in Flat:* {$amenitiesStr}\n\n"
                  . "The flat is well-equipped. Fridge, Wifi, and other essentials are in place for comfortable living!";
            $suggestions = ['What is the rent? 💰', 'What are the food rules? 🥦', 'Schedule a visit 🗓️'];
        } elseif (preg_match('/\b(veg|non.?veg|cook|food|smoke|smoking|drink|pet|pets|party|rule|rules|girl|boy)\b/i', $msgLower)) {
            $lifestyleOpts = RoommatePost::lifestyleOptions();
            $activeLifestyle = [];
            if ($post->lifestyle) {
                foreach ($post->lifestyle as $k => $v) {
                    if ($v && isset($lifestyleOpts[$k])) {
                        $activeLifestyle[] = ($lifestyleOpts[$k]['icon'] ?? '') . ' ' . $lifestyleOpts[$k]['label'];
                    }
                }
            }
            $lifestyleStr = !empty($activeLifestyle) ? implode(' • ', $activeLifestyle) : 'Friendly and respectful house rules';

            $text = "📋 *House Preferences & Lifestyle*:\n\n"
                  . "• *Target Flatmate:* {$genderPrefStr}\n"
                  . "• *Occupation:* " . ucfirst(str_replace('_', ' ', $post->occupation_type)) . "\n"
                  . "• *Lifestyle / Habits:* {$lifestyleStr}\n\n"
                  . "We maintain a safe, welcoming, and respectful home environment.";
            $suggestions = ['When can I visit? 🗓️', 'What is the rent? 💰', 'Are bills included? 💡'];
        } elseif (preg_match('/\b(visit|see|location|map|address|where|meet|timing|call|phone)\b/i', $msgLower)) {
            $text = "📍 *Location & Visit Scheduling*:\n\n"
                  . "• *Locality:* {$locationStr}\n"
                  . "• *Property:* {$bhkLabel}\n\n"
                  . "💡 *Tip:* Propose your preferred day & time (e.g. *\"Can I visit tomorrow at 6 PM?\"*) in this chat. {$post->poster_name} will confirm the visit time with you!";
            $suggestions = ['Can I visit tomorrow evening? 🌆', 'Can I visit this weekend? 🗓️', 'Is the room available? 🔑'];
        } else {
            $text = "Hello *{$userName}*! 👋 I'm the StayNest Roommate Discussion Assistant.\n\n"
                  . "Your message has been delivered to *{$post->poster_name}*. While they get back to you, I can answer instant questions about:\n\n"
                  . "• 💰 *Rent:* {$post->budget_range}\n"
                  . "• 🏠 *Room:* {$bhkLabel} in {$locationStr}\n"
                  . "• 🧊 *Amenities:* {$amenitiesStr}\n"
                  . "• 👥 *Preference:* {$genderPrefStr}\n\n"
                  . "What would you like to know or discuss?";
            $suggestions = ['Is this flat available? 🔑', 'What is the rent & deposit? 💰', 'What amenities are included? 🧊', 'Schedule a visit 🗓️'];
        }

        return [
            'sender_name' => 'StayNest Assistant 🤖',
            'message'     => $text,
            'time'        => date('h:i A'),
            'suggestions' => $suggestions,
        ];
    }

    /**
     * Get unread roommate message stats and latest message snippet for authenticated user.
     */
    public function getUnreadStats(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success'      => true,
                'unread_count' => 0,
                'latest'       => null,
            ]);
        }

        $userId = Auth::id();

        $unreadCount = RoommateMessage::where('receiver_id', $userId)
            ->where('is_read', false)
            ->count();

        $latest = null;
        if ($unreadCount > 0) {
            $latestMsg = RoommateMessage::where('receiver_id', $userId)
                ->where('is_read', false)
                ->with(['post', 'sender.profile'])
                ->orderBy('created_at', 'desc')
                ->first();

            if ($latestMsg && $latestMsg->post) {
                $senderName = $latestMsg->sender?->profile?->full_name 
                    ?: ($latestMsg->sender?->name ?: ($latestMsg->sender_name ?: 'Flatmate'));
                $senderAvatar = $latestMsg->sender?->profile?->avatar_url;
                $bhkLabels = RoommatePost::bhkOptions();
                $bhkLabel = $bhkLabels[$latestMsg->post->bhk_type] ?? $latestMsg->post->bhk_type;
                $locationText = ($latestMsg->post->locality ? $latestMsg->post->locality . ', ' : '') . ($latestMsg->post->city ?: '');

                $latest = [
                    'id'            => $latestMsg->id,
                    'sender_id'     => $latestMsg->sender_id,
                    'sender_name'   => $senderName,
                    'sender_avatar' => $senderAvatar,
                    'sender_gender' => $latestMsg->sender?->profile?->gender ?? $latestMsg->post->poster_gender,
                    'message'       => Str::limit($latestMsg->message, 90),
                    'time'          => $latestMsg->created_at ? $latestMsg->created_at->diffForHumans(null, true, true) : 'Just now',
                    'post_slug'     => $latestMsg->post->slug,
                    'post_title'    => $bhkLabel . ' in ' . ($latestMsg->post->locality ?: $latestMsg->post->city),
                    'bhk_type'      => $bhkLabel,
                    'budget_range'  => $latestMsg->post->budget_range,
                    'locality'      => $locationText,
                ];
            }
        }

        return response()->json([
            'success'      => true,
            'unread_count' => $unreadCount,
            'latest'       => $latest,
        ]);
    }
}