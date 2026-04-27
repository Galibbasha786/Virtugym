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
            
            @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            
            <!-- Date Selection -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Select Date</label>
                <input type="date" name="session_date" id="session_date" 
                       class="w-full px-4 py-2 border rounded-lg"
                       min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
            </div>
            
            <!-- Time Slot Selection -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Select Time</label>
                <select name="session_time" id="session_time" class="w-full px-4 py-2 border rounded-lg" required>
                    <option value="">Choose a date first</option>
                </select>
            </div>
            
            <!-- Duration -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Duration (minutes)</label>
                <select name="duration" id="duration" class="w-full px-4 py-2 border rounded-lg" required>
                    <option value="30">30 minutes - ₹{{ ($trainer->hourly_rate ?? 500) / 2 }}</option>
                    <option value="60" selected>60 minutes - ₹{{ $trainer->hourly_rate ?? 500 }}</option>
                    <option value="90">90 minutes - ₹{{ ($trainer->hourly_rate ?? 500) * 1.5 }}</option>
                    <option value="120">120 minutes - ₹{{ ($trainer->hourly_rate ?? 500) * 2 }}</option>
                </select>
            </div>
            
            <!-- Special Requests -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Special Requests</label>
                <textarea name="special_requests" rows="3" class="w-full px-4 py-2 border rounded-lg"></textarea>
            </div>
            
            <!-- Amount -->
            <div class="bg-purple-50 rounded-lg p-4 mb-6 text-center">
                <span class="font-semibold">Total Amount:</span>
                <span class="text-2xl font-bold text-purple-600" id="amountDisplay">₹{{ $trainer->hourly_rate ?? 500 }}</span>
            </div>
            
            <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white py-3 rounded-xl font-semibold">
                Proceed to Payment →
            </button>
        </form>
    </div>
</div>

<script>
    const trainerId = '{{ $trainer->id }}';
    const hourlyRate = {{ $trainer->hourly_rate ?? 500 }};
    const dateInput = document.getElementById('session_date');
    const timeSelect = document.getElementById('session_time');
    
    dateInput.addEventListener('change', function() {
        const date = this.value;
        if (!date) return;
        
        timeSelect.innerHTML = '<option value="">Loading...</option>';
        
        // Debug
        console.log('Fetching slots for trainer:', trainerId, 'date:', date);
        
        fetch(`/trainer/available-slots/${trainerId}/${date}`)
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(slots => {
                console.log('Slots received:', slots);
                timeSelect.innerHTML = '<option value="">Select time slot</option>';
                
                if (slots && slots.length > 0) {
                    slots.forEach(slot => {
                        const option = document.createElement('option');
                        option.value = slot.start_time;
                        option.textContent = `${slot.start_time} - ${slot.end_time}`;
                        timeSelect.appendChild(option);
                    });
                } else {
                    timeSelect.innerHTML = '<option value="">No slots available on this date</option>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                timeSelect.innerHTML = '<option value="">Error loading slots</option>';
            });
    });
</script>
@endsection