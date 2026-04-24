@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>
    
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Users</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $stats['total_users'] }}</p>
                </div>
                <div class="text-4xl">👥</div>
            </div>
            <div class="mt-2 text-sm">
                <span class="text-green-600">{{ $stats['total_trainers'] }} Trainers</span> | 
                <span class="text-blue-600">{{ $stats['total_trainees'] }} Trainees</span>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Bookings</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['total_bookings'] }}</p>
                </div>
                <div class="text-4xl">📅</div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Revenue</p>
                    <p class="text-3xl font-bold text-yellow-600">₹{{ number_format($stats['total_revenue']) }}</p>
                </div>
                <div class="text-4xl">💰</div>
            </div>
        </div>
    </div>
    
    <!-- Pending Trainers Alert -->
    @if($stats['pending_trainers'] > 0)
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6 rounded">
            <strong>{{ $stats['pending_trainers'] }} trainers pending verification!</strong>
            <a href="{{ route('admin.trainers') }}" class="ml-4 underline">Review Now</a>
        </div>
    @endif
    
    <!-- Recent Users & Bookings -->
    <div class="grid lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-lg font-bold mb-4">Recent Users</h2>
            <div class="space-y-3">
                @foreach($recentUsers as $user)
                    <div class="flex justify-between items-center border-b pb-2">
                        <div>
                            <p class="font-semibold">{{ $user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $user->email }} • {{ ucfirst($user->role ?? 'trainee') }}</p>
                        </div>
                        <span class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-lg font-bold mb-4">Recent Bookings</h2>
            <div class="space-y-3">
                @foreach($recentBookings as $booking)
                    <div class="flex justify-between items-center border-b pb-2">
                        <div>
                            <p class="font-semibold">{{ $booking->trainee->name ?? 'N/A' }} → {{ $booking->trainer->name ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-500">₹{{ $booking->amount }} • {{ $booking->status }}</p>
                        </div>
                        <span class="text-xs text-gray-500">{{ $booking->created_at->diffForHumans() }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection