@extends('layouts.public')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header Section -->
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-gray-900 mb-2">Secure Checkout</h1>
            <p class="text-gray-500 font-medium">Review your order details and complete enrollment securely.</p>
        </div>
        <div>
            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold bg-green-50 text-green-600 border border-green-200">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                SSL Secured 256-bit
            </span>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 relative">
        <!-- Order Summary & Details -->
        <div class="lg:col-span-2 space-y-8">
            <div class="light-card p-6 sm:p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6 border-b border-gray-100 pb-4">Order Summary</h2>
                
                <div class="flex flex-col sm:flex-row gap-6 mb-8 group">
                    <div class="w-full sm:w-48 h-32 rounded-xl bg-gray-50 overflow-hidden flex-shrink-0 relative border border-gray-200">
                         <div class="absolute inset-0 bg-gradient-to-br from-primary-50 to-primary-50 flex items-center justify-center">
                            <svg class="w-10 h-10 text-primary/40 group-hover:scale-110 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"></path><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
                        </div>
                    </div>
                    <div class="flex-grow flex flex-col justify-center">
                        <span class="text-xs font-bold uppercase tracking-wider text-primary mb-1">Target: {{ $batch->course->target_exam ?? 'Various' }}</span>
                        <h3 class="text-2xl font-black text-gray-900 mb-2">{{ $batch->name }}</h3>
                        <p class="text-sm text-gray-500 mb-4 line-clamp-2 font-medium">{{ $batch->course->description }}</p>
                        
                        <div class="flex items-center gap-4 text-sm text-gray-600 font-medium">
                            <span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> Starts: {{ \Carbon\Carbon::parse($batch->start_date)->format('M d, Y') }}</span>
                            <span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg> Support Included</span>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 space-y-3">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600 font-medium">Base Price</span>
                        <span class="text-gray-500 font-bold line-through">₹{{ number_format($batch->original_price, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-green-600 font-bold">Special Discount @if($batch->original_price && $batch->original_price > $batch->price)(-{{ round((($batch->original_price - $batch->price) / $batch->original_price) * 100) }}%)@endif</span>
                        <span class="text-green-600 font-bold">- ₹{{ number_format($batch->original_price - $batch->price, 2) }}</span>
                    </div>
                    <div class="h-px w-full bg-gray-200 my-3"></div>
                    <div class="flex justify-between items-center text-lg">
                        <span class="text-gray-900 font-black">Total Amount</span>
                        <span class="text-primary font-black text-2xl">₹{{ number_format($batch->price, 2) }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Trust Indicators -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
               <div class="bg-white p-4 rounded-xl text-center border border-gray-200 shadow-sm">
                   <div class="w-10 h-10 mx-auto bg-primary-50 rounded-full flex items-center justify-center mb-2">
                       <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                   </div>
                   <span class="text-xs text-gray-600 font-bold">100% Risk Free</span>
               </div>
               <div class="bg-white p-4 rounded-xl text-center border border-gray-200 shadow-sm">
                   <div class="w-10 h-10 mx-auto bg-green-50 rounded-full flex items-center justify-center mb-2">
                       <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                   </div>
                   <span class="text-xs text-gray-600 font-bold">Secure Payment</span>
               </div>
               <div class="bg-white p-4 rounded-xl text-center border border-gray-200 shadow-sm">
                   <div class="w-10 h-10 mx-auto bg-primary-50 rounded-full flex items-center justify-center mb-2">
                       <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                   </div>
                   <span class="text-xs text-gray-600 font-bold">Instant Access</span>
               </div>
               <div class="bg-white p-4 rounded-xl text-center border border-gray-200 shadow-sm">
                   <div class="w-10 h-10 mx-auto bg-yellow-50 rounded-full flex items-center justify-center mb-2">
                       <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                   </div>
                   <span class="text-xs text-gray-600 font-bold">No Hidden Fees</span>
               </div>
            </div>
        </div>

        <!-- Checkout Actions Sidebar -->
        <div class="space-y-6">
            <div class="light-card p-6 lg:p-8 bg-primary-50/30 border-primary/20">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Account Details</h3>
                
                <div class="space-y-5 mb-8">
                    <div>
                        <span class="block text-xs text-gray-500 font-medium mb-1 uppercase tracking-wide">Enrolling As</span>
                        <span class="block text-sm text-gray-900 font-bold">{{ Auth::user()->name }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500 font-medium mb-1 uppercase tracking-wide">Registered Email</span>
                        <span class="block text-sm text-gray-700 font-medium">{{ Auth::user()->email }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500 font-medium mb-1 uppercase tracking-wide">Target Information</span>
                        <span class="block text-sm text-gray-700 font-medium">{{ Auth::user()->target_exam }} | {{ Auth::user()->grade_category }}</span>
                    </div>
                </div>

                <div class="bg-gray-50 border border-gray-200 text-sm text-gray-600 p-4 rounded-xl mb-6">
                    Enrollment gives you full access to all live classes, recordings, DPPs, and tests for this batch.
                </div>

                <button id="rzp-button1" class="w-full flex justify-center items-center py-4 px-4 rounded-xl text-base font-black text-white bg-primary hover:bg-primary-700 transition-all shadow-md hover:shadow-lg">
                    <span class="relative flex items-center gap-2" id="pay-btn-text">
                        Pay ₹{{ number_format($batch->price, 2) }} securely
                        <svg class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </span>
                </button>
                <p class="text-center text-xs text-gray-500 font-medium mt-4">By continuing, you agree to our Terms of Service & Privacy Policy.</p>
            </div>
            
            <div class="text-center">
                 <img src="https://razorpay.com/assets/razorpay-logo.svg" alt="Razorpay Secured" class="h-6 mx-auto opacity-50 grayscale hover:grayscale-0 transition-all duration-300">
            </div>
        </div>
    </div>
</div>

<form action="{{ route('checkout.process', $batch->uuid) }}" method="POST" id="process-form" class="hidden">
    @csrf
</form>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    document.getElementById('rzp-button1').onclick = function(e){
        e.preventDefault();
        const btnText = document.getElementById('pay-btn-text');
        btnText.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Initializing...';
        
        // 1. Ask our server to create an order
        fetch("{{ route('checkout.process', $batch->uuid) }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json"
            }
        })
        .then(response => response.json())
        .then(data => {
            if(!data.order_id) {
                alert(data.error || 'Server error creating order. Please try again.');
                btnText.innerHTML = 'Pay ₹{{ number_format($batch->price, 2) }} securely';
                return;
            }

            // 2. Open Razorpay Checkout modal
            var options = {
                "key": data.key, 
                "amount": data.amount, 
                "currency": data.currency,
                "name": "Topper's Hope",
                "description": "Enrollment for {{ addslashes($batch->name) }}",
                "order_id": data.order_id, 
                "handler": function (response){
                    btnText.innerHTML = 'Verifying Payment...';
                    
                    // 3. Send payment signature to server for verification
                    fetch("{{ route('checkout.verify') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            razorpay_payment_id: response.razorpay_payment_id,
                            razorpay_order_id: response.razorpay_order_id,
                            razorpay_signature: response.razorpay_signature,
                            batch_id: {{ $batch->id }}
                        })
                    })
                    .then(res => res.json())
                    .then(verifyData => {
                        if(verifyData.success) {
                            window.location.href = verifyData.redirect;
                        } else {
                            alert("Payment verification failed! If money was deducted, contact support.");
                            btnText.innerHTML = 'Pay Securely';
                        }
                    });
                },
                "prefill": {
                    "name": "{{ addslashes(Auth::user()->name) }}",
                    "email": "{{ addslashes(Auth::user()->email) }}",
                    "contact": "{{ addslashes(Auth::user()->phone) }}"
                },
                "theme": {
                    "color": "#7723D6"
                }
            };
            var rzp1 = new Razorpay(options);
            rzp1.on('payment.failed', function (response){
                alert("Payment Failed: " + response.error.description);
                btnText.innerHTML = 'Try Again';
            });
            rzp1.open();
        })
        .catch(error => {
            console.error(error);
            alert("Network error.");
            btnText.innerHTML = 'Pay Securely';
        });
    }
</script>
@endsection
