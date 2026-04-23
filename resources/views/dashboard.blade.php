@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-purple-600 to-pink-600 rounded-2xl p-8 text-white mb-8 fade-in-up">
        <div class="flex items-center justify-between flex-wrap">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold mb-2">Welcome back, {{ auth()->user()->name }}! 💪</h1>
                <p class="text-purple-100">Ready to crush your fitness goals today?</p>
            </div>
            <div class="mt-4 md:mt-0">
                <div class="bg-white/20 backdrop-blur rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold">{{ $streak ?? 0 }}</div>
                    <div class="text-sm">Day Streak 🔥</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6 stat-card fade-in-up delay-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Total Workouts</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $totalWorkouts ?? 0 }}</p>
                    <p class="text-xs text-green-600 mt-1">Completed: {{ $completedWorkouts ?? 0 }}</p>
                </div>
                <div class="text-4xl">🏋️</div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6 stat-card fade-in-up delay-2">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Exercises Logged</p>
                    <p class="text-3xl font-bold text-green-600">{{ $totalExercisesLogged ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">Total Volume: {{ number_format($totalVolume ?? 0) }} kg</p>
                </div>
                <div class="text-4xl">📊</div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6 stat-card fade-in-up delay-3">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Current Weight</p>
                    <p class="text-3xl font-bold text-orange-600">
                        {{ $latestProgress->weight ?? 'Not set' }} kg
                    </p>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ $latestProgress ? $latestProgress->date->format('M d, Y') : 'Not tracked' }}
                    </p>
                </div>
                <div class="text-4xl">⚖️</div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6 stat-card fade-in-up delay-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Fitness Level</p>
                    <p class="text-3xl font-bold text-pink-600 capitalize">
                        {{ auth()->user()->fitness_level ?? 'Beginner' }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1 capitalize">Goal: {{ auth()->user()->goal ?? 'Fitness' }}</p>
                </div>
                <div class="text-4xl">🎯</div>
            </div>
        </div>
    </div>
    
    <!-- Recent Workouts & PRs -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Recent Workouts -->
        <div class="bg-white rounded-xl shadow-lg p-6 fade-in-up delay-2">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">Recent Workouts</h2>
                <a href="{{ route('workouts.index') }}" class="text-purple-600 hover:text-purple-700 text-sm">View All →</a>
            </div>
            
            @if(isset($recentWorkouts) && $recentWorkouts->count() > 0)
                <div class="space-y-3">
                    @foreach($recentWorkouts as $workout)
                        <div class="border-b border-gray-100 pb-3 last:border-0">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="font-semibold text-gray-800">{{ $workout->title }}</h3>
                                    <p class="text-sm text-gray-500">
                                        {{ $workout->type ?? 'Workout' }} • {{ $workout->duration_minutes ?? 'N/A' }} mins
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-500">{{ $workout->created_at->diffForHumans() }}</p>
                                    @if($workout->completed_at)
                                        <span class="text-xs text-green-600">✓ Completed</span>
                                    @else
                                        <span class="text-xs text-yellow-600">Pending</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="text-4xl mb-2">🏋️</div>
                    <p class="text-gray-500">No workouts yet</p>
                    <a href="{{ route('workouts.create') }}" class="inline-block mt-3 text-purple-600 hover:text-purple-700 text-sm font-semibold">
                        Create your first workout →
                    </a>
                </div>
            @endif
        </div>
        
        <!-- Personal Records -->
        <div class="bg-white rounded-xl shadow-lg p-6 fade-in-up delay-3">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">🏆 Personal Records</h2>
                <a href="{{ route('progress.index') }}" class="text-purple-600 hover:text-purple-700 text-sm">View All →</a>
            </div>
            
            @if(isset($prs) && $prs->count() > 0)
                <div class="space-y-3">
                    @foreach($prs as $pr)
                        <div class="border-b border-gray-100 pb-3 last:border-0">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="font-semibold text-gray-800">{{ $pr->exercise_name }}</h3>
                                    <p class="text-sm text-gray-500">
                                        {{ $pr->weight }} kg × {{ is_array($pr->reps) ? implode(', ', $pr->reps) : $pr->reps }} reps
                                    </p>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">NEW PR!</span>
                                    <p class="text-xs text-gray-500 mt-1">{{ $pr->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="text-4xl mb-2">🏆</div>
                    <p class="text-gray-500">No personal records yet</p>
                    <p class="text-sm text-gray-400 mt-1">Complete workouts to set PRs!</p>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Quick Actions & Goals -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 fade-in-up delay-4">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white text-center">
            <div class="text-3xl mb-2">💪</div>
            <h3 class="font-bold mb-2">Start Workout</h3>
            <p class="text-sm text-blue-100 mb-3">Begin your training session</p>
            <a href="{{ route('workouts.create') }}" class="inline-block bg-white text-blue-600 px-4 py-1 rounded-lg text-sm font-semibold hover:bg-gray-100">
                Start Now
            </a>
        </div>
        
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white text-center">
            <div class="text-3xl mb-2">📚</div>
            <h3 class="font-bold mb-2">Browse Exercises</h3>
            <p class="text-sm text-green-100 mb-3">Learn new exercises</p>
            <a href="{{ route('exercises.index') }}" class="inline-block bg-white text-green-600 px-4 py-1 rounded-lg text-sm font-semibold hover:bg-gray-100">
                Explore
            </a>
        </div>
        
        <div class="bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl p-6 text-white text-center">
            <div class="text-3xl mb-2">📈</div>
            <h3 class="font-bold mb-2">Track Progress</h3>
            <p class="text-sm text-purple-100 mb-3">View your fitness journey</p>
            <a href="{{ route('progress.index') }}" class="inline-block bg-white text-purple-600 px-4 py-1 rounded-lg text-sm font-semibold hover:bg-gray-100">
                View Stats
            </a>
        </div>
    </div>
</div>

<style>
    .fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
        opacity: 0;
    }
    
    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
    .delay-3 { animation-delay: 0.3s; }
    .delay-4 { animation-delay: 0.4s; }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .stat-card {
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.15);
    }
</style>
@endsection