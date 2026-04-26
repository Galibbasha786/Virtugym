
@extends('layouts.app')

@section('title', 'Find Trainers')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-2">Find Your Perfect Trainer 🏋️</h1>
    <p class="text-gray-600 mb-8">Browse through our fitness experts</p>
    
    @if(isset($trainers) && $trainers->count() > 0)
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($trainers as $trainer)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all">
                    <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-6 text-white text-center">
                        <div class="text-5xl mb-3">🏋️</div>
                        <h3 class="text-xl font-bold">{{ $trainer->name }}</h3>
                        <p class="text-purple-200 text-sm mt-1">{{ $trainer->specialization ?? 'Personal Trainer' }}</p>
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-gray-600">Experience:</span>
                            <span class="font-semibold">{{ $trainer->experience_years ?? 5 }} years</span>
                        </div>
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-gray-600">Hourly Rate:</span>
                            <span class="text-2xl font-bold text-purple-600">₹{{ $trainer->hourly_rate ?? 499 }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-gray-600">Rating:</span>
                            <span class="font-semibold text-yellow-500">⭐ {{ $trainer->rating ?? '4.8' }} / 5</span>
                        </div>
                        <a href="{{ route('book.trainer.create', $trainer->id) }}" 
                           class="block text-center bg-gradient-to-r from-purple-600 to-pink-600 text-white py-2 rounded-lg font-semibold hover:shadow-lg transition">
                            Book a Session →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-8">
            {{ $trainers->links() }}
        </div>
    @else
        <div class="bg-white rounded-xl shadow-lg p-12 text-center">
            <div class="text-6xl mb-4">😢</div>
            <h3 class="text-xl font-bold text-gray-800">No Trainers Available</h3>
            <p class="text-gray-600 mt-2">Please check back later for trainers</p>
        </div>
    @endif
</div>
@endsection
