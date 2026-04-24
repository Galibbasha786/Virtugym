@extends('layouts.app')

@section('title', 'Trainer Dashboard')

@section('content')
<div class="p-6">
    <div class="bg-gradient-to-r from-purple-600 to-pink-600 rounded-2xl p-8 text-white mb-8">
        <h1 class="text-3xl font-bold">Welcome back, {{ auth()->user()->name }}! 🏋️</h1>
        <p class="mt-2">Manage your clients and training sessions</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <p class="text-gray-500 text-sm">Total Clients</p>
            <p class="text-3xl font-bold text-purple-600">{{ $stats['total_clients'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <p class="text-gray-500 text-sm">Total Sessions</p>
            <p class="text-3xl font-bold text-green-600">{{ $stats['total_sessions'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <p class="text-gray-500 text-sm">Total Earned</p>
            <p class="text-3xl font-bold text-yellow-600">₹{{ number_format($stats['total_earned'] ?? 0) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <p class="text-gray-500 text-sm">Rating</p>
            <p class="text-3xl font-bold text-orange-600">{{ $stats['rating'] ?? 0 }} ⭐</p>
        </div>
    </div>
    
    <div class="grid lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-lg font-bold mb-4">Upcoming Sessions</h2>
            @if(isset($upcomingBookings) && $upcomingBookings->count() > 0)
                @foreach($upcomingBookings as $booking)
                    <div class="border-b pb-3 mb-3">
                        <p class="font-semibold">{{ $booking->trainee->name ?? 'Trainee' }}</p>
                        <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($booking->session_date)->format('M d, Y h:i A') }}</p>
                    </div>
                @endforeach
            @else
                <p class="text-gray-500">No upcoming sessions</p>
            @endif
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-lg font-bold mb-4">My Workouts</h2>
            @if(isset($myWorkouts) && $myWorkouts->count() > 0)
                @foreach($myWorkouts as $workout)
                    <div class="border-b pb-3 mb-3">
                        <p class="font-semibold">{{ $workout->title }}</p>
                        <p class="text-sm text-gray-500">{{ $workout->type }} • {{ $workout->difficulty }}</p>
                    </div>
                @endforeach
            @else
                <p class="text-gray-500">No workouts created yet</p>
            @endif
            <a href="#" class="mt-3 inline-block text-purple-600">+ Create Workout (Coming Soon)</a>
        </div>
    </div>
</div>
@endsection
EOF