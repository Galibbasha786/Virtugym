@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">📊 Admin Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl shadow-lg p-6 stat-card transition hover:-translate-y-1">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm">Total Users</p>
                <p class="text-3xl font-bold text-purple-600">{{ $stats['total_users'] }}</p>
            </div>
            <div class="text-4xl">👥</div>
        </div>
        <div class="mt-2 text-sm">
            <span class="text-green-400">{{ $stats['total_trainers'] }} Trainers</span> | 
            <span class="text-blue-400">{{ $stats['total_trainees'] }} Trainees</span>
        </div>
    </div>
    
    <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl shadow-lg p-6 stat-card transition hover:-translate-y-1">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm">Total Bookings</p>
                <p class="text-3xl font-bold text-green-600">{{ $stats['total_bookings'] }}</p>
            </div>
            <div class="text-4xl">📅</div>
        </div>
    </div>
    
    <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl shadow-lg p-6 stat-card transition hover:-translate-y-1">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm">Total Revenue</p>
                <p class="text-3xl font-bold text-yellow-600">₹{{ number_format($stats['total_revenue']) }}</p>
            </div>
            <div class="text-4xl">💰</div>
        </div>
    </div>
    
    <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl shadow-lg p-6 stat-card transition hover:-translate-y-1">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm">Pending Withdrawals</p>
                <p class="text-3xl font-bold text-orange-600">{{ $stats['pending_withdrawals'] }}</p>
            </div>
            <div class="text-4xl">💸</div>
        </div>
    </div>
</div>

@if($stats['pending_trainers'] > 0)
    <div class="bg-yellow-900/40 border-l-4 border-yellow-500 text-yellow-300 p-4 mb-6 rounded-lg shadow">
        <strong>{{ $stats['pending_trainers'] }} trainers pending verification!</strong>
        <a href="{{ route('admin.trainers') }}" class="ml-4 underline hover:text-yellow-100 transition">Review Now</a>
    </div>
@endif

<div class="grid lg:grid-cols-2 gap-6">
    <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl shadow-lg p-6">
        <h2 class="text-lg font-bold mb-4 text-white border-b border-gray-700 pb-2">Recent Users</h2>
        <div class="space-y-3">
            @foreach($recentUsers as $user)
                <div class="flex justify-between items-center border-b border-gray-700/50 pb-2">
                    <div>
                        <p class="font-semibold text-gray-200">{{ $user->name }}</p>
                        <p class="text-sm text-gray-400">{{ $user->email }} • {{ ucfirst($user->role ?? 'trainee') }}</p>
                    </div>
                    <span class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</span>
                </div>
            @endforeach
        </div>
    </div>
    
    <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl shadow-lg p-6">
        <h2 class="text-lg font-bold mb-4 text-white border-b border-gray-700 pb-2">Recent Bookings</h2>
        <div class="space-y-3">
            @foreach($recentBookings as $booking)
                <div class="flex justify-between items-center border-b border-gray-700/50 pb-2">
                    <div>
                        <p class="font-semibold text-gray-200">{{ $booking->trainee->name ?? 'N/A' }} → {{ $booking->trainer->name ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-400">₹{{ number_format($booking->amount ?? 0) }} • {{ $booking->status ?? 'pending' }}</p>
                    </div>
                    <span class="text-xs text-gray-500">{{ $booking->created_at->diffForHumans() }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>
</div>
@endsection