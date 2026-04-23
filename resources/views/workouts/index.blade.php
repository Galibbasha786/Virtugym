@extends('layouts.app')

@section('title', 'My Workouts')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8 fade-in-up">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">My Workouts 💪</h1>
            <p class="text-gray-600 mt-2">Track and manage your fitness journey</p>
        </div>
        <a href="{{ route('workouts.create') }}" class="btn-gradient text-white px-6 py-2 rounded-xl font-semibold">
            + Create Workout
        </a>
    </div>
    
    @if($workouts->count() > 0)
        <div class="space-y-4">
            @foreach($workouts as $workout)
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition p-6 fade-in-up">
                    <div class="flex flex-wrap justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-xl font-bold text-gray-800">{{ $workout->title }}</h3>
                                @if($workout->completed_at)
                                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-semibold">
                                        ✓ Completed
                                    </span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full text-xs font-semibold">
                                        In Progress
                                    </span>
                                @endif
                            </div>
                            <div class="flex flex-wrap gap-4 text-sm text-gray-600 mb-3">
                                <span>🏷️ {{ $workout->type }}</span>
                                <span>📊 {{ $workout->difficulty }}</span>
                                @if($workout->duration_minutes)
                                    <span>⏱️ {{ $workout->duration_minutes }} mins</span>
                                @endif
                                <span>📅 {{ $workout->created_at->format('M d, Y') }}</span>
                            </div>
                            <p class="text-gray-600 text-sm">
                                {{ count($workout->exercises ?? []) }} exercises
                            </p>
                        </div>
                        <div class="flex gap-2 mt-4 md:mt-0">
                            <a href="{{ route('workouts.show', $workout->id) }}" 
                               class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition text-sm">
                                View
                            </a>
                            <a href="{{ route('workouts.edit', $workout->id) }}" 
                               class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition text-sm">
                                Edit
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-8">
            {{ $workouts->links() }}
        </div>
    @else
        <div class="glass-card text-center py-12 fade-in-up">
            <div class="text-6xl mb-4">🏋️</div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">No workouts yet</h3>
            <p class="text-gray-600 mb-6">Create your first workout to start tracking</p>
            <a href="{{ route('workouts.create') }}" class="btn-gradient text-white px-6 py-2 rounded-xl inline-block">
                Create Your First Workout
            </a>
        </div>
    @endif
</div>
@endsection