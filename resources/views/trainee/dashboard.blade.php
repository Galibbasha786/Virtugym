@extends('layouts.app')

@section('title', 'Trainee Dashboard')

@section('content')
<div class="p-6">
    <div class="bg-gradient-to-r from-purple-600 to-pink-600 rounded-2xl p-8 text-white mb-8">
        <h1 class="text-3xl font-bold">Welcome back, {{ auth()->user()->name }}! 💪</h1>
        <p class="mt-2">Track your fitness journey</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <p class="text-gray-500 text-sm">Total Workouts</p>
            <p class="text-3xl font-bold text-purple-600">{{ $stats['total_workouts'] ?? 0 }}</p>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6">
            <p class="text-gray-500 text-sm">Completed Workouts</p>
            <p class="text-3xl font-bold text-green-600">{{ $stats['completed_workouts'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <p class="text-gray-500 text-sm">Total Bookings</p>
            <p class="text-3xl font-bold text-blue-600">{{ $stats['total_bookings'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <p class="text-gray-500 text-sm">Upcoming Sessions</p>
            <p class="text-3xl font-bold text-orange-600">{{ $stats['upcoming_sessions'] ?? 0 }}</p>
        </div>
    </div>
    
    <div class="grid lg:grid-cols-2 gap-6">
        <!-- Recent Workouts -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-lg font-bold mb-4">Recent Workouts</h2>
            @if(isset($recentWorkouts) && $recentWorkouts->count() > 0)
                @foreach($recentWorkouts as $workout)
                    <div class="border-b pb-3 mb-3">
                        <p class="font-semibold">{{ $workout->title }}</p>
                        <p class="text-sm text-gray-500">{{ $workout->type }} • {{ $workout->difficulty }}</p>
                    </div>
                @endforeach
            @else
                <p class="text-gray-500">No workouts yet</p>
            @endif
            <a href="#" class="mt-3 inline-block text-purple-600">+ Create Workout (Coming Soon)</a>
        </div>
        
        <!-- Upcoming Sessions -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-lg font-bold mb-4">Upcoming Sessions with Trainers</h2>
            @if(isset($upcomingSessions) && $upcomingSessions->count() > 0)
                @foreach($upcomingSessions as $session)
                    <div class="border-b pb-3 mb-3">
                        <p class="font-semibold">{{ $session->trainer->name ?? 'Trainer' }}</p>
                        <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($session->session_date)->format('M d, Y h:i A') }}</p>
                        <p class="text-xs text-gray-400">Status: {{ ucfirst($session->status) }}</p>
                    </div>
                @endforeach
                <a href="{{ route('bookings.index') }}" class="mt-3 inline-block text-purple-600">View All Bookings →</a>
            @else
                <p class="text-gray-500">No upcoming sessions</p>
                <a href="{{ route('trainee.trainers') }}" class="mt-3 inline-block text-purple-600">+ Browse Trainers</a>
            @endif
        </div>
    </div>
    
    <!-- Available Trainers -->
    <div class="mt-6">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold">Available Trainers</h2>
                <a href="{{ route('trainee.trainers') }}" class="text-purple-600 text-sm">View All →</a>
            </div>
            <div class="grid md:grid-cols-3 gap-4">
                @if(isset($availableTrainers) && $availableTrainers->count() > 0)
                    @foreach($availableTrainers as $trainer)
                        <div class="border rounded-lg p-4 text-center hover:shadow-lg transition">
                            <div class="text-4xl mb-2">🏋️</div>
                            <h3 class="font-semibold">{{ $trainer->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $trainer->specialization ?? 'Personal Trainer' }}</p>
                            <p class="text-purple-600 font-bold mt-1">₹{{ $trainer->hourly_rate ?? 500 }}/hr</p>
                            <p class="text-xs text-gray-400">⭐ {{ $trainer->rating ?? '4.8' }} ({{ $trainer->total_clients ?? 0 }} clients)</p>
                            <a href="{{ route('book.trainer.create', $trainer->id) }}" class="mt-3 inline-block bg-gradient-to-r from-purple-600 to-pink-600 text-white px-4 py-1 rounded-lg text-sm hover:shadow-lg transition">
                                Book Now
                            </a>
                        </div>
                    @endforeach
                @else
                    <p class="text-gray-500 col-span-3 text-center py-4">No trainers available at the moment</p>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .transition {
        transition: all 0.3s ease;
    }
</style>
@endsection