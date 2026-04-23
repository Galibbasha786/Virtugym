<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exercise;

class ExerciseSeeder extends Seeder
{
    public function run()
    {
        $exercises = [
            // Chest Exercises
            [
                'name' => 'Barbell Bench Press',
                'category' => 'Strength',
                'muscle_group' => 'Chest',
                'equipment' => 'Barbell',
                'difficulty' => 'Intermediate',
                'instructions' => 'Lie on bench, lower bar to chest, press up',
                'tips' => 'Keep elbows at 45 degrees, feet flat on floor'
            ],
            [
                'name' => 'Push Ups',
                'category' => 'Bodyweight',
                'muscle_group' => 'Chest',
                'equipment' => 'Bodyweight',
                'difficulty' => 'Beginner',
                'instructions' => 'Start in plank position, lower chest to ground, push up',
                'tips' => 'Keep core tight, back straight'
            ],
            [
                'name' => 'Incline Dumbbell Press',
                'category' => 'Strength',
                'muscle_group' => 'Chest',
                'equipment' => 'Dumbbell',
                'difficulty' => 'Intermediate',
                'instructions' => 'Lie on incline bench, press dumbbells up',
                'tips' => 'Focus on upper chest activation'
            ],
            
            // Back Exercises
            [
                'name' => 'Pull Ups',
                'category' => 'Bodyweight',
                'muscle_group' => 'Back',
                'equipment' => 'Pull-up Bar',
                'difficulty' => 'Advanced',
                'instructions' => 'Hang from bar, pull chest to bar, lower down',
                'tips' => 'Use full range of motion'
            ],
            [
                'name' => 'Lat Pulldown',
                'category' => 'Strength',
                'muscle_group' => 'Back',
                'equipment' => 'Cable Machine',
                'difficulty' => 'Beginner',
                'instructions' => 'Pull bar down to chest, squeeze shoulder blades',
                'tips' => 'Lean back slightly, pull to upper chest'
            ],
            [
                'name' => 'Bent Over Row',
                'category' => 'Strength',
                'muscle_group' => 'Back',
                'equipment' => 'Barbell',
                'difficulty' => 'Intermediate',
                'instructions' => 'Bend at hips, pull bar to lower chest',
                'tips' => 'Keep back flat, avoid jerking'
            ],
            
            // Legs Exercises
            [
                'name' => 'Squats',
                'category' => 'Strength',
                'muscle_group' => 'Legs',
                'equipment' => 'Barbell',
                'difficulty' => 'Intermediate',
                'instructions' => 'Lower body until thighs parallel to ground, stand up',
                'tips' => 'Keep chest up, knees track over toes'
            ],
            [
                'name' => 'Lunges',
                'category' => 'Strength',
                'muscle_group' => 'Legs',
                'equipment' => 'Bodyweight',
                'difficulty' => 'Beginner',
                'instructions' => 'Step forward, lower hips until both knees bent',
                'tips' => 'Keep front knee behind toes'
            ],
            [
                'name' => 'Leg Press',
                'category' => 'Strength',
                'muscle_group' => 'Legs',
                'equipment' => 'Machine',
                'difficulty' => 'Beginner',
                'instructions' => 'Push platform away with legs, control on way down',
                'tips' => 'Keep lower back pressed against pad'
            ],
            
            // Shoulders Exercises
            [
                'name' => 'Overhead Press',
                'category' => 'Strength',
                'muscle_group' => 'Shoulders',
                'equipment' => 'Barbell',
                'difficulty' => 'Intermediate',
                'instructions' => 'Press bar from shoulders to overhead',
                'tips' => 'Keep core tight, avoid leaning back'
            ],
            [
                'name' => 'Lateral Raises',
                'category' => 'Accessory',
                'muscle_group' => 'Shoulders',
                'equipment' => 'Dumbbell',
                'difficulty' => 'Beginner',
                'instructions' => 'Raise dumbbells to side until shoulder height',
                'tips' => 'Use light weight, focus on form'
            ],
            
            // Arms Exercises
            [
                'name' => 'Bicep Curls',
                'category' => 'Accessory',
                'muscle_group' => 'Arms',
                'equipment' => 'Dumbbell',
                'difficulty' => 'Beginner',
                'instructions' => 'Curl weight up to shoulders, lower slowly',
                'tips' => 'Keep elbows stationary'
            ],
            [
                'name' => 'Tricep Pushdowns',
                'category' => 'Accessory',
                'muscle_group' => 'Arms',
                'equipment' => 'Cable Machine',
                'difficulty' => 'Beginner',
                'instructions' => 'Push bar down until arms extended',
                'tips' => 'Keep elbows at sides'
            ],
            
            // Cardio Exercises
            [
                'name' => 'Running',
                'category' => 'Cardio',
                'muscle_group' => 'Full Body',
                'equipment' => 'None',
                'difficulty' => 'Beginner',
                'instructions' => 'Run at steady pace',
                'tips' => 'Maintain good posture, land mid-foot'
            ],
            [
                'name' => 'Cycling',
                'category' => 'Cardio',
                'muscle_group' => 'Legs',
                'equipment' => 'Bike',
                'difficulty' => 'Beginner',
                'instructions' => 'Pedal at consistent resistance',
                'tips' => 'Adjust seat height properly'
            ],
            [
                'name' => 'Jump Rope',
                'category' => 'Cardio',
                'muscle_group' => 'Full Body',
                'equipment' => 'Jump Rope',
                'difficulty' => 'Intermediate',
                'instructions' => 'Jump over rope as it passes under feet',
                'tips' => 'Land softly on balls of feet'
            ],
        ];
        
        foreach ($exercises as $exercise) {
            Exercise::create($exercise);
        }
        
        $this->command->info('✅ Seeded ' . count($exercises) . ' exercises');
    }
}