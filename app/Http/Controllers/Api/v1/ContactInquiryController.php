<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use App\Models\Notification;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ContactInquiryController extends Controller
{
    /**
     * Submit a new contact inquiry via API.
     */
    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['required', 'string', 'min:7', 'max:25', 'regex:/^[\+]?[0-9\s\-\(\)]+$/'],
            'user_type' => ['nullable', 'string', 'in:tenant,owner,partner,support,other'],
            'city' => ['nullable', 'string', 'max:150'],
            'message' => ['required', 'string', 'min:5', 'max:3000'],
        ], [
            'name.required' => 'Please provide your full name.',
            'name.min' => 'Your name must be at least 2 characters.',
            'email.required' => 'Please provide a valid email address.',
            'email.email' => 'Please enter a valid email format (e.g. name@example.com).',
            'phone.required' => 'Please enter your contact phone number.',
            'phone.regex' => 'Please enter a valid phone number with digits only.',
            'phone.min' => 'Phone number must be at least 7 digits.',
            'message.required' => 'Please write your message or query.',
            'message.min' => 'Message must contain at least 5 characters.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please correct the highlighted errors in the form.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $data = $validator->validated();
            $data['user_type'] = $data['user_type'] ?? 'tenant';
            $data['status'] = 'new';
            $data['ip_address'] = $request->ip();
            $data['user_agent'] = substr((string) $request->header('User-Agent'), 0, 500);

            $inquiry = ContactInquiry::create($data);

            // Optional: Dispatch Admin Notification
            try {
                $adminRoles = Role::whereIn('slug', ['super_admin', 'admin'])->pluck('id');
                if ($adminRoles->isNotEmpty()) {
                    $adminUsers = User::whereHas('roles', fn($q) => $q->whereIn('roles.id', $adminRoles))->get();
                    foreach ($adminUsers as $admin) {
                        Notification::create([
                            'id' => (string) Str::uuid(),
                            'user_id' => $admin->id,
                            'user_type' => 'admin',
                            'title' => 'New Contact Inquiry Received',
                            'message' => "{$inquiry->name} ({$inquiry->email}) submitted an inquiry regarding: " . Str::limit($inquiry->message, 80),
                            'type' => 'contact_inquiry',
                            'is_read' => false,
                            'action_url' => route('admin.contacts'),
                            'created_at' => now(),
                        ]);
                    }
                }
            } catch (\Throwable $notifEx) {
                Log::warning('Failed to send admin notification for contact inquiry: ' . $notifEx->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Thank you! Your message has been submitted successfully. Our support team will get in touch with you shortly.',
                'inquiry' => [
                    'id' => $inquiry->id,
                    'name' => $inquiry->name,
                    'email' => $inquiry->email,
                    'created_at' => $inquiry->created_at->format('M d, Y H:i A'),
                ],
            ], 201);

        } catch (\Throwable $e) {
            Log::error('Error saving contact inquiry: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while processing your request. Please try again later.',
            ], 500);
        }
    }
}
