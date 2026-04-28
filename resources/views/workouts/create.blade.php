@extends('layouts.app')

@section('title', 'Create Workout')

@section('content')
<div style="max-width:960px;margin:0 auto;">
    
    <div style="margin-bottom:1.5rem;" class="fade-in-up">
        <a href="{{ route('workouts.index') }}" style="color:#c4b5fd;text-decoration:none;font-size:.85rem;font-weight:600;display:inline-flex;align-items:center;gap:6px;transition:color .2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#c4b5fd'">
            ← Back to Workouts
        </a>
    </div>
    
    <div style="background:rgba(255,255,255,.03);border:1px solid rgba(139,92,246,.18);border-radius:24px;overflow:hidden;" class="fade-in-up delay-1">
        
        {{-- Header --}}
        <div style="background:linear-gradient(135deg,rgba(139,92,246,.15),rgba(236,72,153,.1));border-bottom:1px solid rgba(139,92,246,.12);padding:1.8rem 2rem;">
            <h1 style="font-size:1.6rem;font-weight:900;color:#fff;margin-bottom:.3rem;">Create New Workout</h1>
            <p style="color:rgba(255,255,255,.4);font-size:.9rem;">Design and assign a personalized workout plan</p>
        </div>
        
        <form action="{{ route('workouts.store') }}" method="POST" style="padding:2rem;">
            @csrf
            @if ($errors->any())
                <div style="background:rgba(239,68,68,0.15); border:1px solid rgba(239,68,68,0.3); border-radius:12px; padding:1.5rem; margin-bottom:2rem;">
                    <h4 style="color:#fca5a5; font-size:1rem; margin-top:0; margin-bottom:0.8rem; font-weight:700;">Please fix the following errors:</h4>
                    <ul style="color:#fecaca; margin-bottom:0; font-size:0.85rem; padding-left:1.2rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            {{-- Basic Info --}}
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:1.5rem;margin-bottom:2rem;">
                <!-- Assign to Trainee -->
                <div>
                    <label style="display:block;font-size:.73rem;font-weight:700;color:rgba(196,181,253,.65);letter-spacing:.04em;margin-bottom:6px;">ASSIGN TO TRAINEE *</label>
                    <select name="trainee_id" required 
                            style="width:100%;padding:11px 14px;background:rgba(8,8,26,.8);border:1px solid rgba(139,92,246,.25);border-radius:12px;color:#fff;font-size:.88rem;outline:none;"
                            onfocus="this.style.borderColor='rgba(139,92,246,.6)'" onblur="this.style.borderColor='rgba(139,92,246,.25)'">
                        <option value="">Select a client</option>
                        @if(isset($clients) && count($clients) > 0)
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }} ({{ $client->email }})</option>
                            @endforeach
                        @else
                            <option disabled>No active clients found. You need a booked session to assign workouts.</option>
                        @endif
                    </select>
                </div>

                <div>
                    <label style="display:block;font-size:.73rem;font-weight:700;color:rgba(196,181,253,.65);letter-spacing:.04em;margin-bottom:6px;">WORKOUT TITLE *</label>
                    <input type="text" name="title" required placeholder="e.g., Chest Day, Full Body Workout"
                           style="width:100%;padding:11px 14px;background:rgba(255,255,255,.05);border:1px solid rgba(139,92,246,.25);border-radius:12px;color:#fff;font-size:.88rem;outline:none;"
                           onfocus="this.style.borderColor='rgba(139,92,246,.6)'" onblur="this.style.borderColor='rgba(139,92,246,.25)'">
                </div>
                
                <div>
                    <label style="display:block;font-size:.73rem;font-weight:700;color:rgba(196,181,253,.65);letter-spacing:.04em;margin-bottom:6px;">WORKOUT TYPE *</label>
                    <select name="type" required 
                            style="width:100%;padding:11px 14px;background:rgba(8,8,26,.8);border:1px solid rgba(139,92,246,.25);border-radius:12px;color:#fff;font-size:.88rem;outline:none;"
                            onfocus="this.style.borderColor='rgba(139,92,246,.6)'" onblur="this.style.borderColor='rgba(139,92,246,.25)'">
                        <option value="">Select type</option>
                        <option value="Strength">💪 Strength</option>
                        <option value="Cardio">🏃 Cardio</option>
                        <option value="HIIT">⚡ HIIT</option>
                        <option value="Flexibility">🧘 Flexibility</option>
                        <option value="Full Body">🏋️ Full Body</option>
                    </select>
                </div>
                
                <div>
                    <label style="display:block;font-size:.73rem;font-weight:700;color:rgba(196,181,253,.65);letter-spacing:.04em;margin-bottom:6px;">DIFFICULTY *</label>
                    <select name="difficulty" required 
                            style="width:100%;padding:11px 14px;background:rgba(8,8,26,.8);border:1px solid rgba(139,92,246,.25);border-radius:12px;color:#fff;font-size:.88rem;outline:none;"
                            onfocus="this.style.borderColor='rgba(139,92,246,.6)'" onblur="this.style.borderColor='rgba(139,92,246,.25)'">
                        <option value="">Select difficulty</option>
                        <option value="Beginner">🌱 Beginner</option>
                        <option value="Intermediate">💪 Intermediate</option>
                        <option value="Advanced">🏆 Advanced</option>
                    </select>
                </div>
                
                <div>
                    <label style="display:block;font-size:.73rem;font-weight:700;color:rgba(196,181,253,.65);letter-spacing:.04em;margin-bottom:6px;">DURATION (MINUTES)</label>
                    <input type="number" name="duration_minutes" placeholder="e.g., 45"
                           style="width:100%;padding:11px 14px;background:rgba(255,255,255,.05);border:1px solid rgba(139,92,246,.25);border-radius:12px;color:#fff;font-size:.88rem;outline:none;"
                           onfocus="this.style.borderColor='rgba(139,92,246,.6)'" onblur="this.style.borderColor='rgba(139,92,246,.25)'">
                </div>
            </div>
            
            {{-- Exercises Section --}}
            <div style="border-top:1px solid rgba(139,92,246,.12);padding-top:2rem;margin-bottom:2rem;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
                    <h2 style="font-size:1.1rem;font-weight:800;color:#e2d9f3;">Exercises</h2>
                    <button type="button" id="addExerciseBtn" 
                            style="background:rgba(16,185,129,.15);border:1px solid rgba(16,185,129,.3);color:#6ee7b7;padding:8px 16px;border-radius:10px;font-size:.8rem;font-weight:700;cursor:pointer;transition:all .2s;"
                            onmouseover="this.style.background='rgba(16,185,129,.25)'"
                            onmouseout="this.style.background='rgba(16,185,129,.15)'">
                        + Add Exercise
                    </button>
                </div>
                
                <div id="exercisesContainer" style="display:flex;flex-direction:column;gap:1.2rem;">
                    <!-- Exercise rows will be added here dynamically -->
                </div>
            </div>
            
            {{-- Submit --}}
            <div style="display:flex;justify-content:flex-end;gap:1rem;border-top:1px solid rgba(139,92,246,.12);padding-top:1.5rem;">
                <a href="{{ route('workouts.index') }}" 
                   style="background:rgba(255,255,255,.05);color:rgba(255,255,255,.7);border:1px solid rgba(255,255,255,.15);padding:12px 24px;border-radius:12px;font-size:.9rem;font-weight:600;text-decoration:none;transition:all .2s;"
                   onmouseover="this.style.background='rgba(255,255,255,.1)'"
                   onmouseout="this.style.background='rgba(255,255,255,.05)'">
                    Cancel
                </a>
                <button type="submit" 
                        style="background:linear-gradient(135deg,#8b5cf6,#ec4899);color:#fff;border:none;border-radius:12px;padding:12px 28px;font-size:.9rem;font-weight:700;cursor:pointer;box-shadow:0 8px 20px rgba(139,92,246,.35);transition:all .3s;"
                        onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 14px 30px rgba(139,92,246,.5)'"
                        onmouseout="this.style.transform='';this.style.boxShadow='0 8px 20px rgba(139,92,246,.35)'">
                    Create Workout
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let exerciseCount = 0;
    const exercises = @json($exercises);
    
    // Validate form before submission
    document.querySelector('form').addEventListener('submit', function(e) {
        const currentExercises = document.querySelectorAll('[id^="exercise-"]').length;
        if (currentExercises === 0) {
            e.preventDefault();
            alert('Please add at least one exercise to the workout plan.');
            return false;
        }
    });

    document.getElementById('addExerciseBtn').addEventListener('click', function() {
        const container = document.getElementById('exercisesContainer');
        const exerciseId = exerciseCount;
        
        const exerciseHtml = `
            <div id="exercise-${exerciseId}" style="background:rgba(0,0,0,.2);border:1px solid rgba(139,92,246,.12);border-radius:16px;padding:1.5rem;position:relative;">
                <button type="button" onclick="removeExercise(${exerciseId})" 
                        style="position:absolute;top:1rem;right:1rem;background:none;border:none;color:#f87171;font-size:.75rem;font-weight:700;cursor:pointer;opacity:.8;transition:opacity .2s;"
                        onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='.8'">
                    ✕ Remove
                </button>
                
                <h4 style="font-size:.85rem;font-weight:700;color:#c4b5fd;margin-bottom:1.2rem;">EXERCISE ${exerciseCount + 1}</h4>
                
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;">
                    <div style="grid-column:1/-1;">
                        <label style="display:block;font-size:.7rem;font-weight:700;color:rgba(196,181,253,.65);margin-bottom:6px;">EXERCISE *</label>
                        <select name="exercises[${exerciseId}][exercise_id]" required 
                                style="width:100%;padding:10px 14px;background:rgba(8,8,26,.8);border:1px solid rgba(139,92,246,.25);border-radius:10px;color:#fff;font-size:.85rem;outline:none;">
                            <option value="">Select exercise</option>
                            ${exercises.map(ex => `<option value="${ex.id}">${ex.name} (${ex.muscle_group})</option>`).join('')}
                        </select>
                    </div>
                    
                    <div>
                        <label style="display:block;font-size:.7rem;font-weight:700;color:rgba(196,181,253,.65);margin-bottom:6px;">SETS *</label>
                        <input type="number" name="exercises[${exerciseId}][sets]" required min="1" placeholder="e.g. 3"
                               style="width:100%;padding:10px 14px;background:rgba(255,255,255,.05);border:1px solid rgba(139,92,246,.25);border-radius:10px;color:#fff;font-size:.85rem;outline:none;">
                    </div>
                    
                    <div>
                        <label style="display:block;font-size:.7rem;font-weight:700;color:rgba(196,181,253,.65);margin-bottom:6px;">REPS *</label>
                        <input type="number" name="exercises[${exerciseId}][reps]" required min="1" placeholder="e.g. 10"
                               style="width:100%;padding:10px 14px;background:rgba(255,255,255,.05);border:1px solid rgba(139,92,246,.25);border-radius:10px;color:#fff;font-size:.85rem;outline:none;">
                    </div>
                    
                    <div>
                        <label style="display:block;font-size:.7rem;font-weight:700;color:rgba(196,181,253,.65);margin-bottom:6px;">TARGET WEIGHT (KG)</label>
                        <input type="number" step="0.5" name="exercises[${exerciseId}][target_weight]" placeholder="e.g. 20"
                               style="width:100%;padding:10px 14px;background:rgba(255,255,255,.05);border:1px solid rgba(139,92,246,.25);border-radius:10px;color:#fff;font-size:.85rem;outline:none;">
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