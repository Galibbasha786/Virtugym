<?php

namespace App\Services;

use App\Models\User;
use App\Models\Workout;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiAIService
{
    protected $apiKey;
    protected $model;
    protected $baseUrl;
    protected $enabled;
    
    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
        $this->model = env('GEMINI_MODEL', 'gemini-2.5-flash');
        $this->baseUrl = 'https://generativelanguage.googleapis.com/v1beta';
        $this->enabled = !empty($this->apiKey) && env('AI_ENABLED', true);
    }
    
    /**
     * Check if AI is available
     */
    public function isAvailable()
    {
        return $this->enabled && !empty($this->apiKey);
    }
    
    /**
 * Generate content using Gemini
 */
private function generate($prompt, $temperature = 0.7, $maxTokens = 800)
{
    // DEBUG: Log API key status
    \Log::info('=== GEMINI API DEBUG ===');
    \Log::info('API Key exists: ' . (!empty($this->apiKey) ? 'Yes' : 'No'));
    \Log::info('Model: ' . $this->model);
    
    if (!$this->isAvailable()) {
        \Log::warning('AI not available - using fallback response');
        return $this->getFallbackResponse($prompt);
    }
    
    try {
        // Try different model names
        $modelsToTry = [
            $this->model,
            'gemini-1.0-pro',
            'gemini-1.0-pro-latest',
            'gemini-pro'
        ];
        
        $lastError = null;
        
        foreach ($modelsToTry as $modelName) {
            \Log::info('Trying model: ' . $modelName);
            
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent?key={$this->apiKey}";
            
            $response = Http::timeout(30)->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => $temperature,
                    'maxOutputTokens' => $maxTokens,
                    'topP' => 0.95,
                    'topK' => 40
                ]
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
                \Log::info('API Response successful with model: ' . $modelName);
                return $text;
            } else {
                $lastError = $response->body();
                \Log::warning('Model ' . $modelName . ' failed: ' . $response->status());
            }
        }
        
        \Log::error('All Gemini models failed. Last error: ' . $lastError);
        return $this->getFallbackResponse($prompt);
        
    } catch (\Exception $e) {
        \Log::error('Gemini Exception: ' . $e->getMessage());
        return $this->getFallbackResponse($prompt);
    }
}
    /**
     * Get AI workout recommendation
     */
    public function recommendWorkout(User $user)
    {
        $goal = $this->getUserAttribute($user, 'goal', 'general fitness');
        $fitnessLevel = $this->getUserAttribute($user, 'fitness_level', 'intermediate');
        $equipment = $this->getUserAttribute($user, 'equipment', 'Bodyweight');
        $workoutDuration = $this->getUserAttribute($user, 'workout_duration', 45);
        $workoutDays = $this->getUserAttribute($user, 'workout_days', 3);
        
        $prompt = "Act as an expert fitness trainer. Create a personalized workout for:
        
User Profile:
- Goal: {$goal}
- Fitness Level: {$fitnessLevel}
- Available Equipment: " . $equipment . "
- Available Time: {$workoutDuration} minutes
- Workout Days per week: {$workoutDays}

Return a JSON object with this exact structure (no markdown, just pure JSON):
{
    \"workout_name\": \"string\",
    \"warmup\": [{\"exercise\": \"name\", \"duration\": \"seconds\"}],
    \"exercises\": [{\"name\": \"exercise\", \"sets\": number, \"reps\": \"number or text\", \"rest\": \"seconds\", \"notes\": \"optional tip\"}],
    \"cooldown\": [{\"exercise\": \"name\", \"duration\": \"seconds\"}],
    \"motivation\": \"one sentence motivation\"
}

Make exercises practical for the equipment available. Keep reps realistic. Don't add markdown or explanations, just the JSON.";

        $response = $this->generate($prompt, 0.8);
        return $this->parseJsonResponse($response);
    }
    
    /**
     * Analyze exercise form
     */
    public function analyzeForm($exerciseName, $formDescription)
    {
        $prompt = "You are a professional fitness trainer with 10+ years of experience. 
        
Analyze the exercise form for '{$exerciseName}' based on this description:
\"{$formDescription}\"

Provide analysis in this EXACT JSON format (no markdown, just JSON):
{
    \"form_quality\": \"Good/Needs Improvement/Poor\",
    \"correct_points\": [\"point1\", \"point2\"],
    \"mistakes\": [\"mistake1\", \"mistake2\"],
    \"corrections\": [\"correction1\", \"correction2\"],
    \"injury_risk\": \"Low/Medium/High\",
    \"tips\": [\"tip1\", \"tip2\"],
    \"encouragement\": \"one encouraging sentence\"
}

Be specific and actionable. Focus on safety first.";

        $response = $this->generate($prompt, 0.7);
        return $this->parseJsonResponse($response);
    }
    
    /**
     * Generate custom workout plan
     */
    public function generateWorkoutPlan($goal, $duration, $equipment, ?User $user = null)
    {
        $fitnessLevel = $user ? $this->getUserAttribute($user, 'fitness_level', 'intermediate') : 'intermediate';
        $equipmentStr = is_array($equipment) ? implode(', ', $equipment) : ($equipment ?: 'Bodyweight');
        
        $prompt = "Create a detailed workout plan for:
Goal: {$goal}
Duration: {$duration} minutes
Equipment: {$equipmentStr}
Fitness level: {$fitnessLevel}

Return ONLY valid JSON (no markdown):
{
    \"plan_name\": \"string\",
    \"difficulty\": \"Beginner/Intermediate/Advanced\",
    \"estimated_calories_burn\": number,
    \"warmup\": [{\"exercise\": \"name\", \"duration\": \"seconds\"}],
    \"circuits\": [
        {
            \"rounds\": number,
            \"exercises\": [{\"name\": \"exercise\", \"reps\": \"number or text\", \"rest\": \"seconds\"}],
            \"rest_between_rounds\": \"seconds\"
        }
    ],
    \"cooldown\": [{\"exercise\": \"name\", \"duration\": \"seconds\"}],
    \"tips\": [\"tip1\", \"tip2\"]
}

Make it challenging but achievable. Include circuit training for efficiency.";

        $response = $this->generate($prompt, 0.8);
        return $this->parseJsonResponse($response);
    }
    
    /**
     * AI Chat Assistant
     */
    public function chat($message, User $user)
    {
        $goal = $this->getUserAttribute($user, 'goal', 'fitness');
        $fitnessLevel = $this->getUserAttribute($user, 'fitness_level', 'intermediate');
        $userName = $this->getUserAttribute($user, 'name', 'friend');
        
        $prompt = "You are 'VirtuCoach', an enthusiastic, knowledgeable fitness AI trainer.
        
User: {$userName}
Goal: {$goal}
Fitness Level: {$fitnessLevel}

User message: \"{$message}\"

Respond as VirtuCoach. Be:
- Encouraging and supportive
- Practical and evidence-based
- Concise (2-4 sentences normally)
- Use emojis occasionally (💪, 🎯, 🔥, ✅)

If asked about medical issues, suggest consulting a doctor.
If unsure, be honest and recommend professional guidance.

Your response (no JSON, just natural conversation):";

        return $this->generate($prompt, 0.8, 300);
    }
    
    /**
     * Get nutrition advice
     */
    public function getNutritionAdvice(User $user)
    {
        $goal = $this->getUserAttribute($user, 'goal', 'general health');
        $age = $this->getUserAttribute($user, 'age', 'Adult');
        $weight = $this->getUserAttribute($user, 'weight', 'Not specified');
        
        $prompt = "Provide personalized nutrition advice for:

Goal: {$goal}
Age: {$age}
Weight: {$weight} kg

Return ONLY JSON (no markdown):
{
    \"daily_calories\": \"range\",
    \"protein\": \"grams\",
    \"carbs\": \"grams\",
    \"fats\": \"grams\",
    \"meal_ideas\": [\"breakfast\", \"lunch\", \"dinner\", \"snack\"],
    \"pre_workout_meal\": \"suggestion\",
    \"post_workout_meal\": \"suggestion\",
    \"hydration\": \"recommendation\",
    \"foods_to_eat\": [\"food1\", \"food2\", \"food3\"],
    \"foods_to_avoid\": [\"food1\", \"food2\"],
    \"motivation\": \"one sentence\"
}

Make advice practical and sustainable. No extreme diets.";

        $response = $this->generate($prompt, 0.7);
        return $this->parseJsonResponse($response);
    }
    
    /**
     * Predict progress
     */
    public function predictProgress(User $user)
    {
        $workoutCount = Workout::where('user_id', $user->id)->count();
        $completedCount = Workout::where('user_id', $user->id)->whereNotNull('completed_at')->count();
        $completionRate = $workoutCount > 0 ? round(($completedCount / $workoutCount) * 100) : 0;
        
        $workoutDays = $this->getUserAttribute($user, 'workout_days', 3);
        $fitnessLevel = $this->getUserAttribute($user, 'fitness_level', 'intermediate');
        $goal = $this->getUserAttribute($user, 'goal', 'fitness');
        $age = $this->getUserAttribute($user, 'age', 'Not specified');
        
        $prompt = "Based on these fitness metrics:

Weekly workout days: {$workoutDays}
Workout completion rate: {$completionRate}%
Current fitness level: {$fitnessLevel}
Goal: {$goal}
Age: {$age}

Predict progress and return ONLY JSON:
{
    \"weeks_to_goal\": number,
    \"confidence_percentage\": number,
    \"recommended_frequency\": number,
    \"predicted_obstacles\": [\"obstacle1\", \"obstacle2\"],
    \"suggestions\": [\"suggestion1\", \"suggestion2\", \"suggestion3\"],
    \"motivation_quote\": \"one line quote\"
}

Be realistic but encouraging. Base on standard fitness progression rates.";

        $response = $this->generate($prompt, 0.7);
        return $this->parseJsonResponse($response);
    }
    
    /**
     * Get motivation quote
     */
    public function getMotivation()
    {
        $prompts = [
            "Give me a short, powerful fitness motivation quote (1 sentence, include an emoji)",
            "Share an encouraging quote for someone who's tired but wants to workout (1 sentence, include 🔥)",
            "What's a short quote about consistency in fitness? (1 sentence, include 💪)",
            "Give me a quote about overcoming fitness plateaus (1 sentence, include 🎯)"
        ];
        
        $randomPrompt = $prompts[array_rand($prompts)];
        $response = $this->generate($randomPrompt, 0.9, 100);
        
        // If AI response is empty, return a default quote
        if (empty($response) || strlen($response) < 10) {
            return "✨ 'Your only limit is your mind. Keep pushing, keep growing, keep believing!' 💪🔥";
        }
        
        return $response;
    }
    
    /**
     * Generate workout summary after completion
     */
    public function generateWorkoutSummary(Workout $workout)
    {
        $exercises = '';
        if ($workout->exercises && is_array($workout->exercises)) {
            foreach ($workout->exercises as $ex) {
                $name = $ex['name'] ?? 'Exercise';
                $sets = $ex['sets'] ?? 3;
                $exercises .= "- {$name}: {$sets} sets\n";
            }
        }
        
        $title = $workout->title ?? 'Your Workout';
        $type = $workout->type ?? 'Fitness';
        $difficulty = $workout->difficulty ?? 'Intermediate';
        
        $prompt = "Create a short, motivational summary for this completed workout:

Workout: {$title}
Type: {$type}
Difficulty: {$difficulty}
Exercises: \n{$exercises}

Return ONLY JSON:
{
    \"celebration\": \"celebrate the achievement (1 sentence)\",
    \"progress_note\": \"what improved (1 sentence)\",
    \"tip\": \"one practical tip for next time\",
    \"energy_boost\": \"energizing closing statement\"
}

Use emojis. Make it personal and encouraging.";

        $response = $this->generate($prompt, 0.8);
        $parsed = $this->parseJsonResponse($response);
        
        // Ensure we have valid data
        if (!isset($parsed['celebration'])) {
            return [
                'celebration' => "🎉 Great job completing your workout!",
                'progress_note' => "You're getting stronger every day!",
                'tip' => "Stay hydrated and get good rest.",
                'energy_boost' => "Keep crushing your fitness goals! 💪"
            ];
        }
        
        return $parsed;
    }
    
    /**
     * Parse JSON response safely
     */
    private function parseJsonResponse($response)
    {
        if (!$response || empty($response)) {
            return $this->getFallbackData();
        }
        
        // Try to extract JSON from response
        $jsonStart = strpos($response, '{');
        $jsonEnd = strrpos($response, '}');
        
        if ($jsonStart !== false && $jsonEnd !== false && $jsonEnd > $jsonStart) {
            $jsonString = substr($response, $jsonStart, $jsonEnd - $jsonStart + 1);
            $data = json_decode($jsonString, true);
            if ($data && !isset($data['error'])) {
                return $data;
            }
        }
        
        return $this->getFallbackData();
    }
    
    /**
     * Fallback data when AI fails
     */
    private function getFallbackData()
    {
        return [
            'workout_name' => 'Power Session',
            'warmup' => [
                ['exercise' => 'Jumping Jacks', 'duration' => '60'], 
                ['exercise' => 'Arm Circles', 'duration' => '30']
            ],
            'exercises' => [
                ['name' => 'Push-ups', 'sets' => 3, 'reps' => '10-12', 'rest' => '60', 'notes' => 'Keep back straight'],
                ['name' => 'Squats', 'sets' => 3, 'reps' => '15', 'rest' => '60', 'notes' => 'Knees behind toes'],
                ['name' => 'Planks', 'sets' => 3, 'reps' => '30 sec', 'rest' => '45', 'notes' => 'Engage core'],
                ['name' => 'Lunges', 'sets' => 3, 'reps' => '10 each', 'rest' => '60', 'notes' => 'Step forward']
            ],
            'cooldown' => [
                ['exercise' => 'Hamstring Stretch', 'duration' => '30'], 
                ['exercise' => 'Quad Stretch', 'duration' => '30']
            ],
            'motivation' => 'You are stronger than yesterday! Keep pushing! 💪',
            'form_quality' => 'Good',
            'correct_points' => ['Good range of motion', 'Controlled movement'],
            'mistakes' => ['Rushing the reps', 'Breathing irregular'],
            'corrections' => ['Slow down', 'Breathe rhythmically'],
            'injury_risk' => 'Low',
            'tips' => ['Stay consistent', 'Listen to your body'],
            'encouragement' => "You're doing great! Every rep counts! 🎯"
        ];
    }
    
    /**
     * Fallback response for chat
     */
    private function getFallbackResponse($prompt)
    {
        if (str_contains($prompt, 'workout') && str_contains($prompt, 'JSON')) {
            return json_encode($this->getFallbackData());
        }
        
        if (str_contains($prompt, 'workout')) {
            return "💪 Try this effective full-body workout:\n\n🔥 Warm-up (5 min)\n• Jumping jacks: 60 sec\n• Arm circles: 30 sec\n• Light jog: 3 min\n\n💪 Main (3 rounds)\n• Push-ups: 10-12 reps\n• Squats: 15 reps\n• Planks: 30 sec\n• Lunges: 10 each\n\n🧘 Cool-down (5 min)\n• Full body stretching\n\nStay consistent and you'll see amazing results! 🎯";
        }
        
        if (str_contains($prompt, 'nutrition')) {
            return "🥗 Healthy Eating Tips:\n\n• Eat lean protein with every meal\n• Fill half your plate with veggies\n• Stay hydrated: 2-3L water daily\n• Complex carbs before workout\n• Listen to hunger cues\n\nSmall changes = big results! ✨";
        }
        
        if (str_contains($prompt, 'motivation')) {
            return "✨ 'Your only limit is your mind. Keep pushing, keep growing, keep believing!' 💪🔥";
        }
        
        if (str_contains($prompt, 'form_quality') || str_contains($prompt, '"form_quality"')) {
            return json_encode($this->getFallbackData());
        }
        
        return "I'm here to help! Ask me about workouts, nutrition, form, or motivation! 🌟💪";
    }
    
    /**
     * Get user attribute safely
     */
    private function getUserAttribute(?User $user, $attribute, $default)
    {
        if (!$user) {
            return $default;
        }
        
        return $user->$attribute ?? $default;
    }
}