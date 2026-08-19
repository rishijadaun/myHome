@extends('user.layouts.app')

@section('title', 'Contact Us - StayNest')

@section('content')
<div class="pt-20 md:pt-10 pb-20 max-w-6xl mx-auto px-4 md:px-6">
    
    <!-- Hero Header Section -->
    <div class="text-center max-w-2xl mx-auto mb-10 md:mb-14">
        <div class="inline-flex items-center gap-2 bg-brand-light text-brand text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-wider mb-4">
            <i class="fas fa-headset"></i> 24/7 Dedicated Support
        </div>
        <h1 class="text-3xl md:text-5xl font-extrabold text-slate-900 mb-3 tracking-tight">
            Get in <span class="gradient-text">Touch With Us</span>
        </h1>
        <p class="text-slate-600 text-sm md:text-base leading-relaxed">
            Have questions about finding a PG, listing your property, or booking a verified stay? Our friendly team is here to assist you every step of the way.
        </p>
    </div>

    <!-- Quick Contact Channels Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-12">
        <!-- Phone Card -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 card-hover flex flex-col justify-between">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-brand-light rounded-xl flex items-center justify-center text-brand text-xl flex-shrink-0">
                    <i class="fas fa-phone-volume"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 text-base">Call Our Hotline</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Mon - Sat (9:00 AM - 8:00 PM)</p>
                    <a href="tel:+919876543210" class="inline-block mt-2 font-bold text-brand hover:text-brand-dark text-lg transition">
                        +91 98765 43210
                    </a>
                </div>
            </div>
            <a href="tel:+919876543210" class="mt-4 w-full bg-brand-50 hover:bg-brand-100 text-brand font-semibold py-2.5 rounded-xl text-center text-xs tap-effect transition flex items-center justify-center gap-2">
                <i class="fas fa-phone-alt text-[10px]"></i> Dial Now
            </a>
        </div>

        <!-- WhatsApp Card -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 card-hover flex flex-col justify-between">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 text-2xl flex-shrink-0">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 text-base">Instant WhatsApp Chat</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Fastest response under 5 mins</p>
                    <a href="https://wa.me/919876543210" target="_blank" class="inline-block mt-2 font-bold text-emerald-600 hover:text-emerald-700 text-lg transition">
                        +91 98765 43210
                    </a>
                </div>
            </div>
            <a href="https://wa.me/919876543210" target="_blank" class="mt-4 w-full bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-semibold py-2.5 rounded-xl text-center text-xs tap-effect transition flex items-center justify-center gap-2">
                <i class="fab fa-whatsapp text-sm"></i> Start WhatsApp Chat
            </a>
        </div>

        <!-- Email Card -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 card-hover flex flex-col justify-between sm:col-span-2 lg:col-span-1">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 text-xl flex-shrink-0">
                    <i class="fas fa-envelope-open-text"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 text-base">Email Support</h3>
                    <p class="text-xs text-slate-500 mt-0.5">We reply within 24 hours</p>
                    <a href="mailto:support@staynest.com" class="inline-block mt-2 font-bold text-blue-600 hover:text-blue-700 text-base transition truncate max-w-full">
                        support@staynest.com
                    </a>
                </div>
            </div>
            <a href="mailto:support@staynest.com" class="mt-4 w-full bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold py-2.5 rounded-xl text-center text-xs tap-effect transition flex items-center justify-center gap-2">
                <i class="fas fa-paper-plane text-[10px]"></i> Send Email
            </a>
        </div>
    </div>

    <!-- Contact Form & Office Info Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start mb-14">
        
        <!-- Contact Form (7 Cols) -->
        <div class="lg:col-span-7 bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-brand-light text-brand flex items-center justify-center font-bold">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Send us a Message</h2>
                    <p class="text-xs text-slate-500">Fill out this quick form and our representative will get back to you.</p>
                </div>
            </div>

            <!-- Success Alert (Hidden initially) -->
            <div id="formSuccessMessage" class="hidden mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-2xl flex items-start gap-3 transition-all duration-300">
                <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center flex-shrink-0 mt-0.5 shadow-sm">
                    <i class="fas fa-check"></i>
                </div>
                <div class="flex-1">
                    <p class="font-bold text-sm text-emerald-950" id="formSuccessTitle">Thank you for reaching out!</p>
                    <p class="text-xs text-emerald-800 mt-0.5" id="formSuccessDetail">Your inquiry has been received. Our team will contact you shortly.</p>
                </div>
            </div>

            <!-- Error Alert (Hidden initially) -->
            <div id="formErrorMessage" class="hidden mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-900 rounded-2xl flex items-start gap-3 transition-all duration-300">
                <div class="w-8 h-8 rounded-xl bg-rose-500 text-white flex items-center justify-center flex-shrink-0 mt-0.5 shadow-sm">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="flex-1">
                    <p class="font-bold text-sm text-rose-950">Submission Failed</p>
                    <p class="text-xs text-rose-800 mt-0.5" id="formErrorDetail">Please check the highlighted fields below and try again.</p>
                </div>
            </div>

            <form id="contactForm" onsubmit="handleContactSubmit(event)" class="space-y-4" novalidate>
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="contact_name" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Your Name <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-3.5 text-gray-400 text-sm"><i class="fas fa-user"></i></span>
                            <input type="text" id="contact_name" name="name" required placeholder="e.g. Rahul Sharma" class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand transition">
                        </div>
                        <p id="err_name" class="text-[11px] text-rose-500 mt-1 hidden font-medium flex items-center gap-1"><i class="fas fa-circle-exclamation text-[10px]"></i> <span></span></p>
                    </div>
                    <div>
                        <label for="contact_phone" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Phone Number <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-3.5 text-gray-400 text-sm"><i class="fas fa-phone"></i></span>
                            <input type="tel" id="contact_phone" name="phone" required placeholder="+91 98765 43210" class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand transition">
                        </div>
                        <p id="err_phone" class="text-[11px] text-rose-500 mt-1 hidden font-medium flex items-center gap-1"><i class="fas fa-circle-exclamation text-[10px]"></i> <span></span></p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="contact_email" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Email Address <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-3.5 text-gray-400 text-sm"><i class="fas fa-envelope"></i></span>
                            <input type="email" id="contact_email" name="email" required placeholder="name@example.com" class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand transition">
                        </div>
                        <p id="err_email" class="text-[11px] text-rose-500 mt-1 hidden font-medium flex items-center gap-1"><i class="fas fa-circle-exclamation text-[10px]"></i> <span></span></p>
                    </div>
                    <div>
                        <label for="contact_user_type" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">I am a</label>
                        <div class="relative">
                            <select id="contact_user_type" name="user_type" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand transition appearance-none">
                                <option value="tenant">Student / Working Professional (Looking for PG)</option>
                                <option value="owner">PG Owner / Host (Want to List Property)</option>
                                <option value="partner">Corporate / Broker Partner</option>
                                <option value="support">Existing Resident Needing Support</option>
                                <option value="other">General Query / Other</option>
                            </select>
                            <span class="absolute right-3.5 top-3.5 text-gray-400 pointer-events-none text-xs"><i class="fas fa-chevron-down"></i></span>
                        </div>
                        <p id="err_user_type" class="text-[11px] text-rose-500 mt-1 hidden font-medium flex items-center gap-1"><i class="fas fa-circle-exclamation text-[10px]"></i> <span></span></p>
                    </div>
                </div>

                <div>
                    <label for="contact_city" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">City / Location of Interest</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-3.5 text-gray-400 text-sm"><i class="fas fa-map-marker-alt"></i></span>
                        <input type="text" id="contact_city" name="city" placeholder="e.g. Koramangala (Bangalore), Sector 62 (Noida), Hinjewadi (Pune)" class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand transition">
                    </div>
                    <p id="err_city" class="text-[11px] text-rose-500 mt-1 hidden font-medium flex items-center gap-1"><i class="fas fa-circle-exclamation text-[10px]"></i> <span></span></p>
                </div>

                <div>
                    <label for="contact_message" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">How can we help you? <span class="text-red-500">*</span></label>
                    <textarea id="contact_message" name="message" required rows="4" placeholder="Tell us what you're looking for or how we can assist you..." class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand transition resize-none"></textarea>
                    <p id="err_message" class="text-[11px] text-rose-500 mt-1 hidden font-medium flex items-center gap-1"><i class="fas fa-circle-exclamation text-[10px]"></i> <span></span></p>
                </div>

                <button type="submit" id="submitBtn" class="w-full bg-gradient-to-r from-brand to-brand-dark text-white font-bold py-3.5 px-6 rounded-xl transition tap-effect shadow-lg shadow-brand/30 hover:opacity-95 text-sm flex items-center justify-center gap-2 cursor-pointer">
                    <i class="fas fa-paper-plane"></i> <span>Send Message</span>
                </button>
            </form>
        </div>

        <!-- Office & Presence Info (5 Cols) -->
        <div class="lg:col-span-5 space-y-6">
            <!-- Headquarters Card -->
            <div class="bg-gradient-to-br from-brand-50 to-white rounded-3xl p-6 border border-brand-100 shadow-sm space-y-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-brand rounded-xl flex items-center justify-center text-white flex-shrink-0 shadow-md shadow-brand/30">
                        <i class="fas fa-building"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-brand uppercase tracking-wider">Headquarters</span>
                        <h3 class="font-bold text-slate-900 text-base">StayNest Technologies Pvt. Ltd.</h3>
                    </div>
                </div>

                <div class="space-y-3 text-xs text-slate-600 border-t border-brand-100/60 pt-4">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-map-marker-alt text-brand text-sm mt-0.5"></i>
                        <div>
                            <p class="font-semibold text-slate-800">Bangalore Campus:</p>
                            <p>#123, 4th Cross, 80 Feet Road, 4th Block,</p>
                            <p>Koramangala, Bengaluru, Karnataka 560034</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="fas fa-map-marker-alt text-brand text-sm mt-0.5"></i>
                        <div>
                            <p class="font-semibold text-slate-800">NCR Hub Office:</p>
                            <p>Plot B-14, Ground Floor, Sector 62,</p>
                            <p>Noida, Uttar Pradesh 201309</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fas fa-clock text-brand text-sm"></i>
                        <div>
                            <p class="font-semibold text-slate-800">Operational Hours:</p>
                            <p>Monday – Saturday: 9:00 AM – 8:00 PM IST</p>
                        </div>
                    </div>
                </div>

                <div class="pt-2 flex flex-col sm:flex-row gap-3">
                    <a href="https://maps.google.com/?q=Koramangala+Bangalore" target="_blank" class="flex-1 bg-white border border-brand-200 text-brand font-bold py-2.5 rounded-xl text-center text-xs tap-effect hover:bg-brand-50 transition flex items-center justify-center gap-2">
                        <i class="fas fa-directions"></i> Get Directions
                    </a>
                    <a href="{{ route('user.search') }}" class="flex-1 bg-brand text-white font-bold py-2.5 rounded-xl text-center text-xs tap-effect hover:bg-brand-dark transition flex items-center justify-center gap-2">
                        <i class="fas fa-search"></i> Search PGs
                    </a>
                </div>
            </div>

            <!-- Zero Brokerage Assurance Box -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm space-y-3">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm font-bold">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4 class="font-bold text-slate-900 text-sm">Zero Brokerage Guarantee</h4>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed">
                    StayNest does not charge any brokerage or finding fee from tenants. Every interaction and booking on StayNest is completely transparent and direct.
                </p>
            </div>
        </div>
    </div>

    <!-- Frequently Asked Questions -->
    <div class="bg-white rounded-3xl p-6 md:p-10 border border-gray-100 shadow-sm mb-12">
        <div class="text-center max-w-xl mx-auto mb-8">
            <span class="text-brand bg-brand-light text-xs font-bold px-3 py-1 rounded-full uppercase">Got Questions?</span>
            <h2 class="text-2xl font-bold text-slate-900 mt-2">Frequently Asked Questions</h2>
            <p class="text-xs text-slate-500 mt-1">Quick answers to help you navigate StayNest seamlessly</p>
        </div>

        <div class="space-y-4 max-w-3xl mx-auto">
            <div class="border border-gray-100 rounded-2xl p-4 bg-gray-50/50">
                <h4 class="font-bold text-slate-900 text-sm flex items-center justify-between cursor-pointer" onclick="toggleFaq(this)">
                    <span>Is there any brokerage fee for booking a PG on StayNest?</span>
                    <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform"></i>
                </h4>
                <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                    No, StayNest is 100% brokerage-free for students and working professionals. You connect directly with verified property hosts and caretakers.
                </p>
            </div>

            <div class="border border-gray-100 rounded-2xl p-4 bg-gray-50/50">
                <h4 class="font-bold text-slate-900 text-sm flex items-center justify-between cursor-pointer" onclick="toggleFaq(this)">
                    <span>How do I schedule a visit to inspect a property?</span>
                    <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform"></i>
                </h4>
                <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                    On any PG detail page, simply tap the "Schedule a Visit" or "Contact Host" button to set up an in-person or virtual walkthrough directly.
                </p>
            </div>

            <div class="border border-gray-100 rounded-2xl p-4 bg-gray-50/50">
                <h4 class="font-bold text-slate-900 text-sm flex items-center justify-between cursor-pointer" onclick="toggleFaq(this)">
                    <span>How can I list my PG or hostel on StayNest?</span>
                    <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform"></i>
                </h4>
                <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                    You can click on the "List PG Free" button in the navigation header or go to the List Property page. It takes less than 3 minutes to submit your property details.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    async function handleContactSubmit(e) {
        e.preventDefault();
        const form = document.getElementById('contactForm');
        const btn = document.getElementById('submitBtn');
        const successMsg = document.getElementById('formSuccessMessage');
        const errorMsg = document.getElementById('formErrorMessage');
        const errorDetail = document.getElementById('formErrorDetail');

        // Clear previous error messages & styles
        document.querySelectorAll('[id^="err_"]').forEach(el => {
            el.classList.add('hidden');
            const span = el.querySelector('span');
            if (span) span.innerText = '';
        });
        document.querySelectorAll('#contactForm input, #contactForm select, #contactForm textarea').forEach(el => {
            el.classList.remove('border-rose-400', 'bg-rose-50/30', 'ring-2', 'ring-rose-200');
        });
        successMsg.classList.add('hidden');
        errorMsg.classList.add('hidden');

        // Prepare Payload
        const payload = {
            name: document.getElementById('contact_name').value.trim(),
            phone: document.getElementById('contact_phone').value.trim(),
            email: document.getElementById('contact_email').value.trim(),
            user_type: document.getElementById('contact_user_type').value,
            city: document.getElementById('contact_city').value.trim(),
            message: document.getElementById('contact_message').value.trim(),
        };

        // Client-side fast check
        let clientValid = true;
        if (!payload.name) {
            showFieldError('name', 'Please enter your full name.');
            clientValid = false;
        }
        if (!payload.phone) {
            showFieldError('phone', 'Please enter your phone number.');
            clientValid = false;
        }
        if (!payload.email) {
            showFieldError('email', 'Please enter your email address.');
            clientValid = false;
        }
        if (!payload.message) {
            showFieldError('message', 'Please write your message or query.');
            clientValid = false;
        }

        if (!clientValid) {
            errorDetail.innerText = 'Please fill in all required fields marked with (*).';
            errorMsg.classList.remove('hidden');
            return;
        }

        // Set Loading state
        const originalBtnHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin text-sm"></i> <span>Sending Message...</span>';
        btn.disabled = true;
        btn.classList.add('opacity-80', 'cursor-not-allowed');

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
            const response = await fetch('{{ url("/api/v1/contact") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(payload)
            });

            const data = await response.json();

            if (response.ok && data.success) {
                // Success feedback
                document.getElementById('formSuccessTitle').innerText = 'Thank you, ' + (payload.name.split(' ')[0] || 'Friend') + '!';
                document.getElementById('formSuccessDetail').innerText = data.message || 'Your inquiry has been received. Our team will contact you shortly.';
                successMsg.classList.remove('hidden');
                
                // Reset form
                form.reset();

                // Button success state
                btn.innerHTML = '<i class="fas fa-check-circle text-sm"></i> <span>Message Sent Successfully!</span>';
                btn.classList.remove('from-brand', 'to-brand-dark');
                btn.classList.add('bg-emerald-600');

                // Smooth scroll to success message
                successMsg.scrollIntoView({ behavior: 'smooth', block: 'center' });

                setTimeout(() => {
                    btn.innerHTML = originalBtnHtml;
                    btn.classList.add('from-brand', 'to-brand-dark');
                    btn.classList.remove('bg-emerald-600', 'opacity-80', 'cursor-not-allowed');
                    btn.disabled = false;
                }, 4000);

            } else if (response.status === 422) {
                // Validation error from server
                if (data.errors) {
                    for (const [field, messages] of Object.entries(data.errors)) {
                        showFieldError(field, messages[0]);
                    }
                }
                errorDetail.innerText = data.message || 'Please check the highlighted errors in the form.';
                errorMsg.classList.remove('hidden');
                errorMsg.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

                btn.innerHTML = originalBtnHtml;
                btn.classList.remove('opacity-80', 'cursor-not-allowed');
                btn.disabled = false;

            } else {
                // Server or other error
                errorDetail.innerText = data.message || 'Something went wrong while submitting. Please try again or reach us by phone/WhatsApp.';
                errorMsg.classList.remove('hidden');

                btn.innerHTML = originalBtnHtml;
                btn.classList.remove('opacity-80', 'cursor-not-allowed');
                btn.disabled = false;
            }

        } catch (err) {
            console.error('Contact submission error:', err);
            errorDetail.innerText = 'Unable to connect to the server. Please check your internet connection and try again.';
            errorMsg.classList.remove('hidden');

            btn.innerHTML = originalBtnHtml;
            btn.classList.remove('opacity-80', 'cursor-not-allowed');
            btn.disabled = false;
        }
    }

    function showFieldError(fieldName, message) {
        const inputEl = document.getElementById('contact_' + fieldName);
        if (inputEl) {
            inputEl.classList.add('border-rose-400', 'bg-rose-50/30', 'ring-2', 'ring-rose-200');
        }
        const errEl = document.getElementById('err_' + fieldName);
        if (errEl) {
            const span = errEl.querySelector('span');
            if (span) span.innerText = message;
            errEl.classList.remove('hidden');
        }
    }

    function toggleFaq(headerEl) {
        const p = headerEl.nextElementSibling;
        const icon = headerEl.querySelector('i');
        if (p.classList.contains('hidden')) {
            p.classList.remove('hidden');
            icon.classList.remove('rotate-180');
        } else {
            p.classList.add('hidden');
            icon.classList.add('rotate-180');
        }
    }
</script>
@endpush
