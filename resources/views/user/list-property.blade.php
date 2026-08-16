@extends('user.layouts.app')

@section('title', 'List Your Property - StayNest')

@push('styles')
<style>
    /* Custom checkbox styling */
    .amenity-checkbox:checked + div {
        border-color: #4bb59d;
        background-color: #f0fdf9;
    }
    .amenity-checkbox:checked + div .check-icon {
        opacity: 1;
        transform: scale(1);
    }
</style>
@endpush

@section('content')
        <!-- ================= MOBILE CONTENT ================= -->
        <div class="md:hidden pt-[70px] pb-24 px-4">
            
            <!-- Mobile Progress Bar -->
            <div class="flex items-center gap-2 mb-6" id="mobile-progress">
                <div class="flex-1 h-1.5 bg-brand rounded-full step-bar transition-colors duration-300"></div>
                <div class="flex-1 h-1.5 bg-gray-200 rounded-full step-bar transition-colors duration-300"></div>
                <div class="flex-1 h-1.5 bg-gray-200 rounded-full step-bar transition-colors duration-300"></div>
                <span class="text-xs font-semibold text-gray-500 ml-2" id="mobile-step-text">Step 1 of 3</span>
            </div>

            <!-- STEP 1: Basic Details -->
            <div id="mobile-step-1" class="step-content space-y-6">
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Property Details</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Property Name *</label>
                            <input type="text" placeholder="e.g. Sunrise Premium PG" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Property Type *</label>
                            <div class="grid grid-cols-3 gap-2">
                                <label class="cursor-pointer">
                                    <input type="radio" name="mob_ptype" class="hidden amenity-checkbox" checked>
                                    <div class="py-3 bg-brand text-white text-xs font-semibold rounded-xl tap-effect border-2 border-brand text-center transition-all">PG</div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="mob_ptype" class="hidden amenity-checkbox">
                                    <div class="py-3 bg-gray-50 text-gray-700 text-xs font-semibold rounded-xl tap-effect border-2 border-gray-200 text-center transition-all">Hostel</div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="mob_ptype" class="hidden amenity-checkbox">
                                    <div class="py-3 bg-gray-50 text-gray-700 text-xs font-semibold rounded-xl tap-effect border-2 border-gray-200 text-center transition-all">Co-living</div>
                                </label>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Full Address *</label>
                            <textarea rows="2" placeholder="Enter full address with landmark" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Gender Preference *</label>
                            <div class="grid grid-cols-3 gap-2">
                                <label class="cursor-pointer">
                                    <input type="radio" name="mob_gender" class="hidden amenity-checkbox">
                                    <div class="py-3 bg-gray-50 text-gray-700 text-xs font-semibold rounded-xl tap-effect border-2 border-gray-200 text-center transition-all">Boys</div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="mob_gender" class="hidden amenity-checkbox">
                                    <div class="py-3 bg-gray-50 text-gray-700 text-xs font-semibold rounded-xl tap-effect border-2 border-gray-200 text-center transition-all">Girls</div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="mob_gender" class="hidden amenity-checkbox" checked>
                                    <div class="py-3 bg-brand text-white text-xs font-semibold rounded-xl tap-effect border-2 border-brand text-center transition-all">Co-ed</div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <button onclick="nextStep()" class="w-full bg-gradient-to-r from-brand to-brand-dark text-white font-bold py-3.5 rounded-xl tap-effect shadow-lg shadow-brand/30">Continue to Step 2</button>
            </div>

            <!-- STEP 2: Amenities & Rooms -->
            <div id="mobile-step-2" class="step-content hidden space-y-6">
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Amenities</h2>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="checkbox" class="hidden amenity-checkbox" checked>
                            <div class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl tap-effect transition-all">
                                <div class="w-5 h-5 rounded border-2 border-gray-300 flex items-center justify-center check-icon opacity-0 transform scale-50 transition-all bg-brand border-brand">
                                    <i class="fas fa-check text-white text-[10px]"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">WiFi</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="checkbox" class="hidden amenity-checkbox" checked>
                            <div class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl tap-effect transition-all">
                                <div class="w-5 h-5 rounded border-2 border-gray-300 flex items-center justify-center check-icon opacity-0 transform scale-50 transition-all bg-brand border-brand">
                                    <i class="fas fa-check text-white text-[10px]"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">AC</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="checkbox" class="hidden amenity-checkbox" checked>
                            <div class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl tap-effect transition-all">
                                <div class="w-5 h-5 rounded border-2 border-gray-300 flex items-center justify-center check-icon opacity-0 transform scale-50 transition-all bg-brand border-brand">
                                    <i class="fas fa-check text-white text-[10px]"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Food</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="checkbox" class="hidden amenity-checkbox">
                            <div class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl tap-effect transition-all">
                                <div class="w-5 h-5 rounded border-2 border-gray-300 flex items-center justify-center check-icon opacity-0 transform scale-50 transition-all bg-brand border-brand">
                                    <i class="fas fa-check text-white text-[10px]"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Laundry</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="checkbox" class="hidden amenity-checkbox">
                            <div class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl tap-effect transition-all">
                                <div class="w-5 h-5 rounded border-2 border-gray-300 flex items-center justify-center check-icon opacity-0 transform scale-50 transition-all bg-brand border-brand">
                                    <i class="fas fa-check text-white text-[10px]"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Power Backup</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="checkbox" class="hidden amenity-checkbox" checked>
                            <div class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl tap-effect transition-all">
                                <div class="w-5 h-5 rounded border-2 border-gray-300 flex items-center justify-center check-icon opacity-0 transform scale-50 transition-all bg-brand border-brand">
                                    <i class="fas fa-check text-white text-[10px]"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">CCTV</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Room Types Available</h2>
                    <div class="space-y-3">
                        <label class="flex items-center justify-between p-3 border border-gray-200 rounded-xl tap-effect">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" class="w-5 h-5 text-brand rounded focus:ring-brand">
                                <span class="text-sm font-medium text-gray-700">Single Occupancy</span>
                            </div>
                            <input type="number" placeholder="₹ Rent" class="w-24 bg-gray-50 border border-gray-200 rounded-lg px-2 py-1.5 text-xs text-right focus:outline-none focus:ring-1 focus:ring-brand/50">
                        </label>
                        <label class="flex items-center justify-between p-3 border border-gray-200 rounded-xl tap-effect">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" class="w-5 h-5 text-brand rounded focus:ring-brand" checked>
                                <span class="text-sm font-medium text-gray-700">Double Sharing</span>
                            </div>
                            <input type="number" placeholder="₹ Rent" class="w-24 bg-gray-50 border border-gray-200 rounded-lg px-2 py-1.5 text-xs text-right focus:outline-none focus:ring-1 focus:ring-brand/50" value="8500">
                        </label>
                        <label class="flex items-center justify-between p-3 border border-gray-200 rounded-xl tap-effect">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" class="w-5 h-5 text-brand rounded focus:ring-brand">
                                <span class="text-sm font-medium text-gray-700">Triple Sharing</span>
                            </div>
                            <input type="number" placeholder="₹ Rent" class="w-24 bg-gray-50 border border-gray-200 rounded-lg px-2 py-1.5 text-xs text-right focus:outline-none focus:ring-1 focus:ring-brand/50">
                        </label>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button onclick="prevStep()" class="flex-1 bg-gray-100 text-gray-700 font-bold py-3.5 rounded-xl tap-effect hover:bg-gray-200 transition">Back</button>
                    <button onclick="nextStep()" class="flex-1 bg-gradient-to-r from-brand to-brand-dark text-white font-bold py-3.5 rounded-xl tap-effect shadow-lg shadow-brand/30">Next Step</button>
                </div>
            </div>

            <!-- STEP 3: Pricing, Photos & Review -->
            <div id="mobile-step-3" class="step-content hidden space-y-6">
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Pricing Details</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Monthly Rent (₹) *</label>
                            <input type="number" placeholder="e.g. 8500" value="8500" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Security Deposit (₹) *</label>
                            <input type="number" placeholder="e.g. 8500" value="8500" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Maintenance Charges (₹/mo)</label>
                            <input type="number" placeholder="e.g. 500" value="500" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Upload Photos</h2>
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center tap-effect hover:border-brand hover:bg-brand-50/30 transition cursor-pointer">
                        <div class="w-12 h-12 bg-brand-light rounded-full flex items-center justify-center text-brand mx-auto mb-3">
                            <i class="fas fa-cloud-upload-alt text-xl"></i>
                        </div>
                        <p class="text-sm font-semibold text-gray-900 mb-1">Tap to upload photos</p>
                        <p class="text-xs text-gray-500">JPG, PNG up to 5MB each</p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button onclick="prevStep()" class="flex-1 bg-gray-100 text-gray-700 font-bold py-3.5 rounded-xl tap-effect hover:bg-gray-200 transition">Back</button>
                    <button onclick="submitForm()" class="flex-1 bg-gradient-to-r from-brand to-brand-dark text-white font-bold py-3.5 rounded-xl tap-effect shadow-lg shadow-brand/30">Submit Listing</button>
                </div>
            </div>
        </div>

        <!-- ================= DESKTOP CONTENT ================= -->
        <div class="hidden md:block max-w-7xl mx-auto px-6 py-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">List Your Property</h1>
                <p class="text-gray-500">Reach thousands of potential tenants. Fill in the details below.</p>
            </div>

            <!-- Desktop Progress Steps -->
            <div class="flex items-center justify-center mb-12" id="desktop-progress">
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-3 step-item cursor-pointer" onclick="updateStep(1)">
                        <div class="w-10 h-10 bg-brand text-white rounded-full flex items-center justify-center font-bold step-circle transition-all duration-300">1</div>
                        <span class="font-semibold text-gray-900 step-text transition-all duration-300">Details</span>
                    </div>
                    <div class="w-16 h-0.5 bg-gray-200"></div>
                    <div class="flex items-center gap-3 step-item cursor-pointer" onclick="updateStep(2)">
                        <div class="w-10 h-10 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center font-bold step-circle transition-all duration-300">2</div>
                        <span class="font-medium text-gray-500 step-text transition-all duration-300">Amenities</span>
                    </div>
                    <div class="w-16 h-0.5 bg-gray-200"></div>
                    <div class="flex items-center gap-3 step-item cursor-pointer" onclick="updateStep(3)">
                        <div class="w-10 h-10 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center font-bold step-circle transition-all duration-300">3</div>
                        <span class="font-medium text-gray-500 step-text transition-all duration-300">Pricing</span>
                    </div>
                </div>
            </div>

            <!-- DESKTOP STEP 1 -->
            <div id="desktop-step-1" class="step-content grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Basic Information</h2>
                        <div class="grid grid-cols-2 gap-6">
                            <div class="col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Property Name *</label>
                                <input type="text" placeholder="e.g. Sunrise Premium PG" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Property Type *</label>
                                <select class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 appearance-none cursor-pointer">
                                    <option>PG / Hostel</option>
                                    <option>Co-living Space</option>
                                    <option>Apartment</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Gender Preference *</label>
                                <select class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 appearance-none cursor-pointer">
                                    <option>Co-ed (Both)</option>
                                    <option>Boys Only</option>
                                    <option>Girls Only</option>
                                </select>
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Full Address *</label>
                                <textarea rows="3" placeholder="Enter complete address with landmark" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">City *</label>
                                <input type="text" placeholder="City" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Pincode *</label>
                                <input type="text" placeholder="Pincode" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Property Photos</h2>
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-12 text-center tap-effect hover:border-brand hover:bg-brand-50/30 transition cursor-pointer">
                            <div class="w-16 h-16 bg-brand-light rounded-full flex items-center justify-center text-brand mx-auto mb-4">
                                <i class="fas fa-cloud-upload-alt text-2xl"></i>
                            </div>
                            <p class="text-base font-semibold text-gray-900 mb-2">Click to upload or drag and drop</p>
                            <p class="text-sm text-gray-500 mb-4">SVG, PNG, JPG or GIF (max. 5MB per file)</p>
                            <button type="button" class="bg-brand text-white px-6 py-2.5 rounded-xl text-sm font-semibold tap-effect">Select Files</button>
                        </div>
                        <div class="grid grid-cols-4 gap-4 mt-6">
                            <div class="relative group">
                                <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" class="w-full h-24 object-cover rounded-xl">
                                <button type="button" class="absolute top-1 right-1 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition"><i class="fas fa-times"></i></button>
                            </div>
                            <div class="relative group">
                                <img src="https://images.unsplash.com/photo-1598928506311-c55ded91a20c?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" class="w-full h-24 object-cover rounded-xl">
                                <button type="button" class="absolute top-1 right-1 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sticky top-24">
                        <h3 class="font-bold text-gray-900 mb-4">Listing Preview</h3>
                        <div class="bg-gray-50 rounded-xl p-4 mb-4">
                            <div class="w-full h-32 bg-gray-200 rounded-lg mb-3 flex items-center justify-center text-gray-400">
                                <i class="fas fa-image text-2xl"></i>
                            </div>
                            <h4 class="font-bold text-gray-900 text-sm">Your Property Name</h4>
                            <p class="text-xs text-gray-500 mt-1"><i class="fas fa-map-marker-alt text-brand"></i> Address will appear here</p>
                        </div>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between"><span class="text-gray-500">Status</span><span class="font-semibold text-yellow-600">Draft</span></div>
                            <div class="flex justify-between"><span class="text-gray-500">Fields filled</span><span class="font-semibold text-gray-900">2/12</span></div>
                        </div>
                        <div class="mt-6 space-y-3">
                            <button type="button" onclick="alert('Saved as draft!')" class="w-full bg-gray-100 text-gray-700 font-semibold py-3 rounded-xl tap-effect hover:bg-gray-200 transition">Save as Draft</button>
                            <button type="button" onclick="nextStep()" class="w-full bg-gradient-to-r from-brand to-brand-dark text-white font-bold py-3 rounded-xl tap-effect shadow-lg shadow-brand/30">Continue to Step 2</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DESKTOP STEP 2 -->
            <div id="desktop-step-2" class="step-content hidden grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Amenities & Facilities</h2>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            <label class="cursor-pointer">
                                <input type="checkbox" class="hidden amenity-checkbox" checked>
                                <div class="flex items-center gap-3 p-4 border border-gray-200 rounded-xl tap-effect transition-all hover:border-brand/50">
                                    <div class="w-6 h-6 rounded border-2 border-gray-300 flex items-center justify-center check-icon opacity-0 transform scale-50 transition-all bg-brand border-brand">
                                        <i class="fas fa-check text-white text-xs"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">High-Speed WiFi</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="checkbox" class="hidden amenity-checkbox" checked>
                                <div class="flex items-center gap-3 p-4 border border-gray-200 rounded-xl tap-effect transition-all hover:border-brand/50">
                                    <div class="w-6 h-6 rounded border-2 border-gray-300 flex items-center justify-center check-icon opacity-0 transform scale-50 transition-all bg-brand border-brand">
                                        <i class="fas fa-check text-white text-xs"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Air Conditioning</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="checkbox" class="hidden amenity-checkbox" checked>
                                <div class="flex items-center gap-3 p-4 border border-gray-200 rounded-xl tap-effect transition-all hover:border-brand/50">
                                    <div class="w-6 h-6 rounded border-2 border-gray-300 flex items-center justify-center check-icon opacity-0 transform scale-50 transition-all bg-brand border-brand">
                                        <i class="fas fa-check text-white text-xs"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Meals Included</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="checkbox" class="hidden amenity-checkbox">
                                <div class="flex items-center gap-3 p-4 border border-gray-200 rounded-xl tap-effect transition-all hover:border-brand/50">
                                    <div class="w-6 h-6 rounded border-2 border-gray-300 flex items-center justify-center check-icon opacity-0 transform scale-50 transition-all bg-brand border-brand">
                                        <i class="fas fa-check text-white text-xs"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Laundry Service</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="checkbox" class="hidden amenity-checkbox">
                                <div class="flex items-center gap-3 p-4 border border-gray-200 rounded-xl tap-effect transition-all hover:border-brand/50">
                                    <div class="w-6 h-6 rounded border-2 border-gray-300 flex items-center justify-center check-icon opacity-0 transform scale-50 transition-all bg-brand border-brand">
                                        <i class="fas fa-check text-white text-xs"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Power Backup</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="checkbox" class="hidden amenity-checkbox" checked>
                                <div class="flex items-center gap-3 p-4 border border-gray-200 rounded-xl tap-effect transition-all hover:border-brand/50">
                                    <div class="w-6 h-6 rounded border-2 border-gray-300 flex items-center justify-center check-icon opacity-0 transform scale-50 transition-all bg-brand border-brand">
                                        <i class="fas fa-check text-white text-xs"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">24/7 Security</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Room Configurations</h2>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl">
                                <div class="flex items-center gap-4">
                                    <input type="checkbox" class="w-5 h-5 text-brand rounded focus:ring-brand" checked>
                                    <div>
                                        <div class="font-semibold text-gray-900">Double Sharing</div>
                                        <div class="text-xs text-gray-500">2 beds per room</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-sm text-gray-500">₹</span>
                                    <input type="number" value="8500" class="w-24 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm text-right focus:outline-none focus:ring-1 focus:ring-brand/50">
                                    <span class="text-sm text-gray-500">/mo</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl">
                                <div class="flex items-center gap-4">
                                    <input type="checkbox" class="w-5 h-5 text-brand rounded focus:ring-brand">
                                    <div>
                                        <div class="font-semibold text-gray-900">Single Occupancy</div>
                                        <div class="text-xs text-gray-500">1 bed per room</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-sm text-gray-500">₹</span>
                                    <input type="number" placeholder="0" class="w-24 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm text-right focus:outline-none focus:ring-1 focus:ring-brand/50">
                                    <span class="text-sm text-gray-500">/mo</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sticky top-24">
                        <h3 class="font-bold text-gray-900 mb-4">Progress</h3>
                        <div class="space-y-3 text-sm mb-6">
                            <div class="flex items-center gap-2 text-green-600"><i class="fas fa-check-circle"></i><span>Basic Details Completed</span></div>
                            <div class="flex items-center gap-2 text-brand font-semibold"><i class="fas fa-circle-notch fa-spin"></i><span>Fill Amenities & Rooms</span></div>
                            <div class="flex items-center gap-2 text-gray-400"><i class="far fa-circle"></i><span>Pricing & Photos</span></div>
                        </div>
                        <div class="space-y-3">
                            <button type="button" onclick="prevStep()" class="w-full bg-gray-100 text-gray-700 font-semibold py-3 rounded-xl tap-effect hover:bg-gray-200 transition">Back to Step 1</button>
                            <button type="button" onclick="nextStep()" class="w-full bg-gradient-to-r from-brand to-brand-dark text-white font-bold py-3 rounded-xl tap-effect shadow-lg shadow-brand/30">Continue to Step 3</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DESKTOP STEP 3 -->
            <div id="desktop-step-3" class="step-content hidden grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Pricing & Policies</h2>
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Monthly Rent (₹) *</label>
                                <input type="number" placeholder="e.g. 8500" value="8500" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Security Deposit (₹) *</label>
                                <input type="number" placeholder="e.g. 8500" value="8500" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Maintenance Charges (₹/mo)</label>
                                <input type="number" placeholder="e.g. 500" value="500" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Notice Period (Days)</label>
                                <select class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 appearance-none cursor-pointer">
                                    <option>15 Days</option>
                                    <option selected>30 Days</option>
                                    <option>60 Days</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Property Photos</h2>
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-12 text-center tap-effect hover:border-brand hover:bg-brand-50/30 transition cursor-pointer mb-6">
                            <div class="w-16 h-16 bg-brand-light rounded-full flex items-center justify-center text-brand mx-auto mb-4">
                                <i class="fas fa-cloud-upload-alt text-2xl"></i>
                            </div>
                            <p class="text-base font-semibold text-gray-900 mb-2">Click to upload or drag and drop</p>
                            <p class="text-sm text-gray-500 mb-4">SVG, PNG, JPG or GIF (max. 5MB per file)</p>
                            <button type="button" class="bg-brand text-white px-6 py-2.5 rounded-xl text-sm font-semibold tap-effect">Select Files</button>
                        </div>
                        <div class="grid grid-cols-4 gap-4">
                            <div class="relative group">
                                <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" class="w-full h-24 object-cover rounded-xl">
                                <button type="button" class="absolute top-1 right-1 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition tap-effect"><i class="fas fa-times"></i></button>
                            </div>
                            <div class="relative group">
                                <img src="https://images.unsplash.com/photo-1598928506311-c55ded91a20c?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" class="w-full h-24 object-cover rounded-xl">
                                <button type="button" class="absolute top-1 right-1 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition tap-effect"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sticky top-24">
                        <h3 class="font-bold text-gray-900 mb-4">Final Review</h3>
                        <div class="bg-gray-50 rounded-xl p-4 mb-4">
                            <div class="flex gap-3 mb-3">
                                <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" class="w-16 h-16 object-cover rounded-lg">
                                <div>
                                    <h4 class="font-bold text-gray-900 text-sm">Sunrise Premium PG</h4>
                                    <p class="text-xs text-gray-500 mt-1"><i class="fas fa-map-marker-alt text-brand"></i> Sector 62, Noida</p>
                                    <div class="text-xs font-bold text-brand mt-1">₹8,500/mo</div>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-3 text-sm mb-6">
                            <div class="flex items-center gap-2 text-green-600"><i class="fas fa-check-circle"></i><span>Basic Details Completed</span></div>
                            <div class="flex items-center gap-2 text-green-600"><i class="fas fa-check-circle"></i><span>Amenities & Rooms Added</span></div>
                            <div class="flex items-center gap-2 text-brand font-semibold"><i class="fas fa-circle-notch fa-spin"></i><span>Review & Submit</span></div>
                        </div>
                        <div class="space-y-3">
                            <button type="button" onclick="prevStep()" class="w-full bg-gray-100 text-gray-700 font-semibold py-3 rounded-xl tap-effect hover:bg-gray-200 transition">Back to Step 2</button>
                            <button type="button" onclick="submitForm()" class="w-full bg-gradient-to-r from-brand to-brand-dark text-white font-bold py-3 rounded-xl tap-effect shadow-lg shadow-brand/30 flex items-center justify-center gap-2">
                                <i class="fas fa-check"></i> Submit Listing
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

@push('scripts')
    <!-- ================= JAVASCRIPT FOR MULTI-STEP FORM ================= -->
    <script>
        let currentStep = 1;
        const totalSteps = 3;

        function updateStep(step) {
            currentStep = step;
            
            // 1. Update Mobile UI
            document.querySelectorAll('.step-content').forEach(el => {
                if (el.id.startsWith('mobile-step-') || el.id.startsWith('desktop-step-')) {
                    el.classList.add('hidden');
                }
            });
            const mobEl = document.getElementById(`mobile-step-${currentStep}`);
            const deskEl = document.getElementById(`desktop-step-${currentStep}`);
            if (mobEl) mobEl.classList.remove('hidden');
            if (deskEl) deskEl.classList.remove('hidden');
            
            // 2. Update Mobile Progress Bar
            const mobileBars = document.querySelectorAll('#mobile-progress .step-bar');
            mobileBars.forEach((bar, index) => {
                if (index < currentStep) {
                    bar.classList.remove('bg-gray-200');
                    bar.classList.add('bg-brand');
                } else {
                    bar.classList.remove('bg-brand');
                    bar.classList.add('bg-gray-200');
                }
            });
            const mobText = document.getElementById('mobile-step-text');
            if (mobText) mobText.innerText = `Step ${currentStep} of ${totalSteps}`;

            // 3. Update Desktop Progress Bar
            const desktopItems = document.querySelectorAll('#desktop-progress .step-item');
            desktopItems.forEach((item, index) => {
                const circle = item.querySelector('.step-circle');
                const text = item.querySelector('.step-text');
                
                if (index + 1 === currentStep) {
                    // Active Step
                    circle.classList.remove('bg-gray-200', 'text-gray-500');
                    circle.classList.add('bg-brand', 'text-white');
                    circle.innerHTML = index + 1;
                    text.classList.remove('text-gray-500', 'font-medium');
                    text.classList.add('text-gray-900', 'font-semibold');
                } else if (index + 1 < currentStep) {
                    // Completed Step
                    circle.classList.remove('bg-gray-200', 'text-gray-500');
                    circle.classList.add('bg-brand', 'text-white');
                    circle.innerHTML = '<i class="fas fa-check"></i>';
                    text.classList.remove('text-gray-500', 'font-medium');
                    text.classList.add('text-gray-900');
                } else {
                    // Future Step
                    circle.classList.remove('bg-brand', 'text-white');
                    circle.classList.add('bg-gray-200', 'text-gray-500');
                    circle.innerHTML = index + 1;
                    text.classList.remove('text-gray-900', 'font-semibold');
                    text.classList.add('text-gray-500', 'font-medium');
                }
            });
            
            // Scroll to top smoothly
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function nextStep() {
            if (currentStep < totalSteps) {
                updateStep(currentStep + 1);
            }
        }

        function prevStep() {
            if (currentStep > 1) {
                updateStep(currentStep - 1);
            }
        }

        function submitForm() {
            const submitBtn = event ? event.target : null;
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Submitting...';
                submitBtn.disabled = true;
            }
            
            setTimeout(() => {
                alert('🎉 Property listed successfully! It will be reviewed and published within 24 hours.');
                window.location.href = "{{ route('user.home') }}";
            }, 1200);
        }

        // Initialize first step on load
        document.addEventListener('DOMContentLoaded', () => {
            updateStep(1);
        });
    </script>
@endpush
