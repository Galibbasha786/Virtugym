@extends('layouts.app')

@section('title', 'Book a Trainer')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-6 text-white">
            <h1 class="text-2xl font-bold">Book Training Session</h1>
            <p class="mt-2">with {{ $trainer->name }}</p>
        </div>
        
        <form method="POST" action="{{ route('initiate.payment', $trainer->id) }}" class="p-6">
            @csrf
            
            <!-- CO5 - Display Validation Errors -->
            @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <!-- CO5 - Old Input Repopulation -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Select Date *</label>
                <input type="date" name="session_date" value="{{ old('session_date') }}" 
                       class="w-full px-4 py-2 border rounded-lg focus:border-purple-500 focus:outline-none"
                       min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Select Time *</label>
                <select name="session_time" class="w-full px-4 py-2 border rounded-lg focus:border-purple-500 focus:outline-none" required>
                    <option value="">Select time slot</option>
                    <option value="09:00" {{ old('session_time') == '09:00' ? 'selected' : '' }}>9:00 AM</option>
                    <option value="10:00" {{ old('session_time') == '10:00' ? 'selected' : '' }}>10:00 AM</option>
                    <option value="11:00" {{ old('session_time') == '11:00' ? 'selected' : '' }}>11:00 AM</option>
                    <option value="14:00" {{ old('session_time') == '14:00' ? 'selected' : '' }}>2:00 PM</option>
                    <option value="15:00" {{ old('session_time') == '15:00' ? 'selected' : '' }}>3:00 PM</option>
                    <option value="16:00" {{ old('session_time') == '16:00' ? 'selected' : '' }}>4:00 PM</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Duration (minutes) *</label>
                <select name="duration" class="w-full px-4 py-2 border rounded-lg focus:border-purple-500 focus:outline-none" required>
                    <option value="30" {{ old('duration') == '30' ? 'selected' : '' }}>30 minutes - ₹250</option>
                    <option value="45" {{ old('duration') == '45' ? 'selected' : '' }}>45 minutes - ₹375</option>
                    <option value="60" {{ old('duration') == '60' ? 'selected' : '' }}>60 minutes - ₹500</option>
                    <option value="90" {{ old('duration') == '90' ? 'selected' : '' }}>90 minutes - ₹750</option>
                    <option value="120" {{ old('duration') == '120' ? 'selected' : '' }}>120 minutes - ₹1000</option>
                </select>
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Special Requests (Optional)</label>
                <textarea name="special_requests" rows="3" 
                          class="w-full px-4 py-2 border rounded-lg focus:border-purple-500 focus:outline-none"
                          placeholder="Any specific goals or injuries to note?">{{ old('special_requests') }}</textarea>
            </div>
            
            <div class="bg-purple-50 rounded-lg p-4 mb-6">
                <p class="text-center text-gray-700">
                    <span class="font-semibold">Total Amount:</span>
                    <span class="text-2xl font-bold text-purple-600" id="amountDisplay">₹500</span>
                </p>
                <p class="text-xs text-gray-500 text-center mt-2">Secure payment powered by Razorpay</p>
            </div>
            
            <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white py-3 rounded-xl font-semibold hover:shadow-lg transition">
                Proceed to Payment →
            </button>
        </form>
    </div>
</div>

<script>
    // Update amount based on duration selection
    const durationSelect = document.querySelector('select[name="duration"]');
    const amountDisplay = document.getElementById('amountDisplay');
    
    durationSelect.addEventListener('change', function() {
        const rates = {30: 250, 45: 375, 60: 500, 90: 750, 120: 1000};
        const amount = rates[this.value] || 500;
        amountDisplay.textContent = '₹' + amount;
    });
</script>
@endsection