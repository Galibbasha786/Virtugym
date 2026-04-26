@extends('layouts.app')

@section('title', $exercise->name)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('exercises.index') }}" class="text-purple-600 hover:text-purple-700">← Back to Exercises</a>
    </div>
    
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden fade-in-up">
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-8 text-white">
            <div class="text-6xl mb-4">
                @switch($exercise->muscle_group)
                    @case('Chest') 💪 @break
                    @case('Back') 🏋️ @break
                    @case('Legs') 🦵 @break
                    @case('Shoulders') 🎯 @break
                    @default 🏃
                @endswitch
            </div>
            <h1 class="text-3xl font-bold">{{ $exercise->name }}</h1>
            <div class="flex gap-3 mt-3">
                <span class="bg-white/20 px-3 py-1 rounded-full text-sm">{{ $exercise->muscle_group }}</span>
                <span class="bg-white/20 px-3 py-1 rounded-full text-sm">{{ $exercise->equipment }}</span>
                <span class="bg-white/20 px-3 py-1 rounded-full text-sm">{{ $exercise->difficulty }}</span>
            </div>
        </div>
        
        <div class="p-8">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-3">📝 Instructions</h2>
                <p class="text-gray-700 leading-relaxed">{{ $exercise->instructions }}</p>
            </div>
            
            @if($exercise->tips)
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-3">💡 Pro Tips</h2>
                <p class="text-gray-700">{{ $exercise->tips }}</p>
            </div>
            @endif
            
            <div class="bg-purple-50 rounded-xl p-6 mt-6">
                <h3 class="font-bold text-purple-800 mb-2">Ready to add this exercise?</h3>
                <p class="text-purple-700 text-sm mb-4">Add this exercise to your next workout</p>
                <a href="#?exercise={{ $exercise->id }}" 
                   class="btn-gradient text-white px-6 py-2 rounded-xl inline-block">
                    Add to Workout →
                </a>
            </div>
        </div>
    </div>
</div>
@endsection