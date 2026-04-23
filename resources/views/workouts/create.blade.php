@extends('layouts.app')

@section('title', 'Create Workout')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('workouts.index') }}" class="text-purple-600 hover:text-purple-700">← Back to Workouts</a>
    </div>
    
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden fade-in-up">
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-6 text-white">
            <h1 class="text-2xl font-bold">Create New Workout</h1>
            <p class="mt-2">Design your personalized workout plan</p>
        </div>
        
        <form action="{{ route('workouts.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="space-y-6">
                <!-- Basic Info -->
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Workout Title *</label>
                        <input type="text" name="title" required
                               class="w-full px-4 py-2 rounded-xl border-2 border-gray-200 focus:border-purple-500 focus:outline-none"
                               placeholder="e.g., Chest Day, Full Body Workout">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Workout Type *</label>
                        <select name="type" required class="w-full px-4 py-2 rounded-xl border-2 border-gray-200 focus:border-purple-500 focus:outline-none">
                            <option value="">Select type</option>
                            <option value="Strength">💪 Strength</option>
                            <option value="Cardio">🏃 Cardio</option>
                            <option value="HIIT">⚡ HIIT</option>
                            <option value="Flexibility">🧘 Flexibility</option>
                            <option value="Full Body">🏋️ Full Body</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Difficulty *</label>
                        <select name="difficulty" required class="w-full px-4 py-2 rounded-xl border-2 border-gray-200 focus:border-purple-500 focus:outline-none">
                            <option value="">Select difficulty</option>
                            <option value="Beginner">🌱 Beginner</option>
                            <option value="Intermediate">💪 Intermediate</option>
                            <option value="Advanced">🏆 Advanced</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Duration (minutes)</label>
                        <input type="number" name="duration_minutes"
                               class="w-full px-4 py-2 rounded-xl border-2 border-gray-200 focus:border-purple-500 focus:outline-none"
                               placeholder="e.g., 45">
                    </div>
                </div>
                
                <!-- Exercises Section -->
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <label class="block text-sm font-semibold text-gray-700">Exercises</label>
                        <button type="button" id="addExerciseBtn" class="bg-green-600 text-white px-4 py-1 rounded-lg text-sm hover:bg-green-700">
                            + Add Exercise
                        </button>
                    </div>
                    
                    <div id="exercisesContainer" class="space-y-4">
                        <!-- Exercise rows will be added here dynamically -->
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 pt-4">
                    <a href="{{ route('workouts.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-xl font-semibold hover:bg-gray-300">
                        Cancel
                    </a>
                    <button type="submit" class="btn-gradient text-white px-6 py-2 rounded-xl font-semibold">
                        Create Workout
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    let exerciseCount = 0;
    const exercises = @json($exercises);
    
    document.getElementById('addExerciseBtn').addEventListener('click', function() {
        const container = document.getElementById('exercisesContainer');
        const exerciseId = exerciseCount;
        
        const exerciseHtml = `
            <div class="exercise-row bg-gray-50 rounded-xl p-4" id="exercise-${exerciseId}">
                <div class="flex justify-between items-start mb-3">
                    <h4 class="font-semibold text-gray-800">Exercise ${exerciseCount + 1}</h4>
                    <button type="button" onclick="removeExercise(${exerciseId})" class="text-red-500 hover:text-red-700">Remove</button>
                </div>
                <div class="grid md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Exercise *</label>
                        <select name="exercises[${exerciseId}][exercise_id]" required class="w-full px-3 py-1 rounded-lg border border-gray-300 focus:border-purple-500">
                            <option value="">Select exercise</option>
                            ${exercises.map(ex => `<option value="${ex._id}">${ex.name} (${ex.muscle_group})</option>`).join('')}
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Sets *</label>
                        <input type="number" name="exercises[${exerciseId}][sets]" required min="1" class="w-full px-3 py-1 rounded-lg border border-gray-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Reps *</label>
                        <input type="number" name="exercises[${exerciseId}][reps]" required min="1" class="w-full px-3 py-1 rounded-lg border border-gray-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Target Weight (kg)</label>
                        <input type="number" step="0.5" name="exercises[${exerciseId}][target_weight]" class="w-full px-3 py-1 rounded-lg border border-gray-300">
                    </div>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', exerciseHtml);
        exerciseCount++;
    });
    
    function removeExercise(id) {
        const element = document.getElementById(`exercise-${id}`);
        if (element) element.remove();
    }
</script>
@endsection