@extends('layouts.app')

@section('title', 'My Analytics')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Fitness Analytics 📊</h1>
    
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <p class="text-gray-500 text-sm">Total Workouts</p>
            <p class="text-3xl font-bold text-purple-600">{{ $totalWorkouts ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <p class="text-gray-500 text-sm">Completion Rate</p>
            <p class="text-3xl font-bold text-green-600">{{ $completionRate ?? 0 }}%</p>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <p class="text-gray-500 text-sm">Total Volume</p>
            <p class="text-3xl font-bold text-blue-600">{{ number_format($totalVolume ?? 0) }} kg</p>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <p class="text-gray-500 text-sm">Total Reps</p>
            <p class="text-3xl font-bold text-orange-600">{{ number_format($totalReps ?? 0) }}</p>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-lg font-bold mb-4">Recent Personal Records 🏆</h2>
        @if(isset($prs) && $prs->count() > 0)
            @foreach($prs as $pr)
                <div class="border-b pb-3 mb-3">
                    <p class="font-semibold">{{ $pr->exercise_name ?? 'Exercise' }}</p>
                    <p class="text-sm text-gray-500">
                        @if(isset($pr->weight)) {{ $pr->weight }} kg × @endif
                        @if(isset($pr->reps)) {{ is_array($pr->reps) ? implode(', ', $pr->reps) : $pr->reps }} reps @endif
                    </p>
                </div>
            @endforeach
        @else
            <p class="text-gray-500">No personal records yet. Keep training!</p>
        @endif
    </div>
    
    @if(isset($weeklyProgress) && count($weeklyProgress) > 0)
    <div class="bg-white rounded-xl shadow-lg p-6 mt-6">
        <h2 class="text-lg font-bold mb-4">Monthly Progress</h2>
        <div class="space-y-3">
            @foreach($weeklyProgress as $week => $count)
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span>{{ $week }}</span>
                        <span>{{ $count }} workouts</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-600 rounded-full h-2" style="width: {{ min(100, $count * 10) }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection