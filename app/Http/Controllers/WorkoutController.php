<?php

namespace App\Http\Controllers;

use App\Models\Workout;
use App\Models\Exercise;
use App\Models\ExerciseLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkoutController extends Controller
{
    public function index()
    {
        $workouts = Workout::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('workouts.index', compact('workouts'));
    }
    
    public function create()
    {
        $exercises = Exercise::orderBy('name')->get();
        return view('workouts.create', compact('exercises'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string',
            'difficulty' => 'required|string',
            'duration_minutes' => 'nullable|integer',
            'exercises' => 'required|array',
            'exercises.*.exercise_id' => 'required|exists:exercises,_id',
            'exercises.*.sets' => 'required|integer|min:1',
            'exercises.*.reps' => 'required|integer|min:1',
            'exercises.*.target_weight' => 'nullable|numeric',
        ]);
        
        $workout = Workout::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'type' => $request->type,
            'difficulty' => $request->difficulty,
            'duration_minutes' => $request->duration_minutes,
            'exercises' => $request->exercises,
            'scheduled_date' => $request->scheduled_date ?? now(),
        ]);
        
        return redirect()->route('workouts.show', $workout->id)
            ->with('success', 'Workout created successfully!');
    }
    
    public function show($id)
    {
        $workout = Workout::where('user_id', Auth::id())
            ->findOrFail($id);
        
        // Get exercise details
        $exercises = [];
        foreach ($workout->exercises as $exerciseData) {
            $exercise = Exercise::find($exerciseData['exercise_id']);
            if ($exercise) {
                $exercises[] = (object)[
                    'exercise' => $exercise,
                    'sets' => $exerciseData['sets'],
                    'reps' => $exerciseData['reps'],
                    'target_weight' => $exerciseData['target_weight'] ?? null,
                ];
            }
        }
        
        // Get existing logs for this workout
        $logs = ExerciseLog::where('workout_id', $workout->id)
            ->where('user_id', Auth::id())
            ->get()
            ->keyBy('exercise_id');
        
        return view('workouts.show', compact('workout', 'exercises', 'logs'));
    }
    
    public function edit($id)
    {
        $workout = Workout::where('user_id', Auth::id())->findOrFail($id);
        $exercises = Exercise::orderBy('name')->get();
        return view('workouts.edit', compact('workout', 'exercises'));
    }
    
    public function update(Request $request, $id)
    {
        $workout = Workout::where('user_id', Auth::id())->findOrFail($id);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string',
            'difficulty' => 'required|string',
            'duration_minutes' => 'nullable|integer',
        ]);
        
        $workout->update($request->only(['title', 'type', 'difficulty', 'duration_minutes']));
        
        return redirect()->route('workouts.show', $workout->id)
            ->with('success', 'Workout updated successfully!');
    }
    
    public function destroy($id)
    {
        $workout = Workout::where('user_id', Auth::id())->findOrFail($id);
        $workout->delete();
        
        return redirect()->route('workouts.index')
            ->with('success', 'Workout deleted successfully!');
    }
    
    public function complete(Request $request, $id)
    {
        $workout = Workout::where('user_id', Auth::id())->findOrFail($id);
        $workout->update([
            'completed_at' => now(),
            'notes' => $request->notes,
            'rating' => $request->rating,
        ]);
        
        return redirect()->route('workouts.show', $workout->id)
            ->with('success', 'Great job! Workout completed! 🎉');
    }
}