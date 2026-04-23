@extends('layouts.app')

@section('title', 'Exercise Library')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8 fade-in-up">
        <h1 class="text-3xl font-bold text-gray-800">Exercise Library 📚</h1>
        <p class="text-gray-600 mt-2">Browse through our collection of exercises</p>
    </div>
    
    <!-- Search & Filters -->
    <div class="glass-card p-6 mb-8 fade-in-up delay-1">
        <form method="GET" action="{{ route('exercises.index') }}" class="space-y-4">
            <div class="grid md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="w-full px-4 py-2 rounded-xl border-2 border-gray-200 focus:border-purple-500 focus:outline-none"
                           placeholder="Exercise name...">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Muscle Group</label>
                    <select name="muscle_group" class="w-full px-4 py-2 rounded-xl border-2 border-gray-200 focus:border-purple-500 focus:outline-none">
                        <option value="">All</option>
                        @foreach($muscleGroups as $group)
                            <option value="{{ $group->muscle_group }}" {{ request('muscle_group') == $group->muscle_group ? 'selected' : '' }}>
                                {{ $group->muscle_group }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Equipment</label>
                    <select name="equipment" class="w-full px-4 py-2 rounded-xl border-2 border-gray-200 focus:border-purple-500 focus:outline-none">
                        <option value="">All</option>
                        @foreach($equipmentList as $equip)
                            <option value="{{ $equip->equipment }}" {{ request('equipment') == $equip->equipment ? 'selected' : '' }}>
                                {{ $equip->equipment }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Difficulty</label>
                    <select name="difficulty" class="w-full px-4 py-2 rounded-xl border-2 border-gray-200 focus:border-purple-500 focus:outline-none">
                        <option value="">All</option>
                        @foreach($difficulties as $diff)
                            <option value="{{ $diff->difficulty }}" {{ request('difficulty') == $diff->difficulty ? 'selected' : '' }}>
                                {{ $diff->difficulty }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="submit" class="btn-gradient text-white px-6 py-2 rounded-xl font-semibold">
                    Apply Filters
                </button>
                <a href="{{ route('exercises.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-xl font-semibold hover:bg-gray-300 transition">
                    Clear
                </a>
            </div>
        </form>
    </div>
    
    <!-- Exercises Grid -->
    <div class="grid md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($exercises as $exercise)
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all hover:transform hover:scale-105 overflow-hidden fade-in-up">
                <div class="p-6">
                    <div class="text-4xl mb-3">
                        @switch($exercise->muscle_group)
                            @case('Chest') 💪 @break
                            @case('Back') 🏋️ @break
                            @case('Legs') 🦵 @break
                            @case('Shoulders') 🎯 @break
                            @case('Arms') 💪 @break
                            @default 🏃
                        @endswitch
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $exercise->name }}</h3>
                    <div class="space-y-1 text-sm">
                        <p class="text-gray-600"><span class="font-semibold">Muscle:</span> {{ $exercise->muscle_group }}</p>
                        <p class="text-gray-600"><span class="font-semibold">Equipment:</span> {{ $exercise->equipment }}</p>
                        <p class="text-gray-600">
                            <span class="font-semibold">Difficulty:</span>
                            <span class="px-2 py-1 rounded-full text-xs 
                                @if($exercise->difficulty == 'Beginner') bg-green-100 text-green-700
                                @elseif($exercise->difficulty == 'Intermediate') bg-yellow-100 text-yellow-700
                                @else bg-red-100 text-red-700
                                @endif">
                                {{ $exercise->difficulty }}
                            </span>
                        </p>
                    </div>
                    <a href="{{ route('exercises.show', $exercise->id) }}" 
                       class="inline-block mt-4 text-purple-600 hover:text-purple-700 font-semibold text-sm">
                        View Details →
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <div class="text-6xl mb-4">😢</div>
                <h3 class="text-xl font-bold text-gray-800">No exercises found</h3>
                <p class="text-gray-600 mt-2">Try adjusting your filters</p>
            </div>
        @endforelse
    </div>
    
    <div class="mt-8">
        {{ $exercises->links() }}
    </div>
</div>
@endsection