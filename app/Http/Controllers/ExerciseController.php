<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    public function index(Request $request)
    {
        $query = Exercise::query();
        
        // Apply filters
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        if ($request->has('muscle_group') && $request->muscle_group) {
            $query->where('muscle_group', $request->muscle_group);
        }
        
        if ($request->has('equipment') && $request->equipment) {
            $query->where('equipment', $request->equipment);
        }
        
        if ($request->has('difficulty') && $request->difficulty) {
            $query->where('difficulty', $request->difficulty);
        }
        
        $exercises = $query->orderBy('name')->paginate(12);
        
        // Get filter options
        $muscleGroups = Exercise::distinct('muscle_group')->get();
        $equipmentList = Exercise::distinct('equipment')->get();
        $difficulties = Exercise::distinct('difficulty')->get();
        
        return view('exercises.index', compact('exercises', 'muscleGroups', 'equipmentList', 'difficulties'));
    }
    
    public function show($id)
    {
        $exercise = Exercise::findOrFail($id);
        return view('exercises.show', compact('exercise'));
    }
}