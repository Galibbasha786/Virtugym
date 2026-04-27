@extends('layouts.app')

@section('title', 'Video Session')

@section('content')
<div class="h-screen flex flex-col">
    <div class="bg-gradient-to-r from-purple-600 to-pink-600 text-white p-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold">Video Session with 
                    @if(Auth::id() == $booking->trainer_id)
                        {{ $booking->trainee->name ?? 'Trainee' }}
                    @else
                        {{ $booking->trainer->name ?? 'Trainer' }}
                    @endif
                </h1>
                <p class="text-sm opacity-90">{{ \Carbon\Carbon::parse($booking->session_date)->format('F d, Y h:i A') }}</p>
            </div>
            <div class="flex space-x-2">
                @if(Auth::id() == $booking->trainer_id && !$booking->meeting_ended)
                    <button onclick="endMeeting()" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                        End Session
                    </button>
                @endif
                <a href="{{ route('bookings.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                    Exit
                </a>
            </div>
        </div>
    </div>
    
    <div class="flex-1">
        <iframe 
            src="{{ $meetingLink }}"
            allow="camera; microphone; fullscreen; display-capture"
            class="w-full h-full border-0"
            allowfullscreen
        ></iframe>
    </div>
</div>

<script src="https://meet.jit.si/external_api.js"></script>
<script>
    function endMeeting() {
        if (confirm('Are you sure you want to end this session?')) {
            fetch('{{ route("video-call.end", $booking->id) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(() => {
                window.location.href = '{{ route("bookings.index") }}';
            });
        }
    }
</script>
@endsection