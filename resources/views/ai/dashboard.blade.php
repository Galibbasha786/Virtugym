@extends('layouts.app')

@section('title', 'AI Coach')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
            🤖 AI Fitness Coach
        </h1>
        <p class="text-gray-600 mt-2">Your personal AI-powered fitness assistant</p>
        @if(!$aiAvailable)
            <div class="bg-yellow-100 border-l-4 border-yellow-500 p-4 mt-4">
                <p class="text-yellow-700">⚠️ AI features are running in offline mode. Add Gemini API key to enable full AI capabilities.</p>
            </div>
        @endif
    </div>
    
    <!-- AI Chat Section -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-4 text-white">
            <h2 class="font-bold text-lg">💬 Chat with VirtuCoach</h2>
            <p class="text-sm opacity-90">Ask me anything about fitness, workouts, or nutrition!</p>
        </div>
        
        <div id="chatMessages" class="h-96 overflow-y-auto p-4 space-y-3 bg-gray-50">
            <div class="flex justify-start">
                <div class="bg-purple-100 rounded-2xl p-3 max-w-[80%]">
                    <p class="text-sm">👋 Hi! I'm VirtuCoach, your AI fitness trainer. Ask me about:</p>
                    <ul class="text-sm mt-1 ml-4">
                        <li>💪 Personalized workouts</li>
                        <li>🥗 Nutrition advice</li>
                        <li>📊 Progress tips</li>
                        <li>🎯 Motivation & accountability</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="p-4 border-t">
            <div class="flex space-x-2">
                <input type="text" id="chatInput" placeholder="Ask your fitness question..." 
                       class="flex-1 px-4 py-2 border rounded-xl focus:outline-none focus:border-purple-500">
                <button id="sendChat" class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-2 rounded-xl hover:shadow-lg transition">
                    Send
                </button>
            </div>
        </div>
    </div>
    
    <!-- Features Grid -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Workout Recommendation -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center text-xl">💪</div>
                <h3 class="font-bold text-lg">AI Workout</h3>
            </div>
            <div id="workoutRecommendation" class="text-sm text-gray-600">
                <button onclick="getWorkoutRecommendation()" class="text-purple-600 hover:text-purple-700">
                    🔄 Generate Personalized Workout
                </button>
            </div>
        </div>
        
        <!-- Nutrition Advice -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-xl">🥗</div>
                <h3 class="font-bold text-lg">Nutrition Guide</h3>
            </div>
            <div id="nutritionAdvice" class="text-sm text-gray-600">
                <button onclick="getNutritionAdvice()" class="text-green-600 hover:text-green-700">
                    🔄 Get Personalized Nutrition
                </button>
            </div>
        </div>
        
        <!-- Progress Prediction -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-xl">📊</div>
                <h3 class="font-bold text-lg">Progress Forecast</h3>
            </div>
            <div id="progressPrediction" class="text-sm text-gray-600">
                <button onclick="getProgressPrediction()" class="text-blue-600 hover:text-blue-700">
                    🔄 Predict My Progress
                </button>
            </div>
        </div>
        
        <!-- Form Analysis -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center text-xl">📹</div>
                <h3 class="font-bold text-lg">Form Check</h3>
            </div>
            <div class="space-y-3">
                <input type="text" id="formExercise" placeholder="Exercise name" class="w-full px-3 py-2 border rounded-lg text-sm">
                <textarea id="formDescription" rows="2" placeholder="Describe how you perform the exercise..." class="w-full px-3 py-2 border rounded-lg text-sm"></textarea>
                <button onclick="analyzeForm()" class="w-full bg-yellow-500 text-white py-2 rounded-lg text-sm hover:bg-yellow-600">
                    Analyze My Form
                </button>
            </div>
            <div id="formAnalysisResult" class="mt-3 text-sm"></div>
        </div>
        
        <!-- Custom Plan -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center text-xl">📋</div>
                <h3 class="font-bold text-lg">Custom Plan</h3>
            </div>
            <div class="space-y-3">
                <select id="planGoal" class="w-full px-3 py-2 border rounded-lg text-sm">
                    <option value="weight_loss">Weight Loss</option>
                    <option value="muscle_gain">Muscle Gain</option>
                    <option value="endurance">Endurance</option>
                    <option value="general_fitness">General Fitness</option>
                </select>
                <input type="number" id="planDuration" placeholder="Duration (minutes)" value="30" class="w-full px-3 py-2 border rounded-lg text-sm">
                <button onclick="generateCustomPlan()" class="w-full bg-orange-500 text-white py-2 rounded-lg text-sm hover:bg-orange-600">
                    Generate Plan
                </button>
            </div>
            <div id="customPlanResult" class="mt-3 text-sm"></div>
        </div>
        
        <!-- Motivation -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center text-xl">⚡</div>
                <h3 class="font-bold text-lg">Daily Motivation</h3>
            </div>
            <div id="motivation" class="text-sm text-gray-600 italic">
                <button onclick="getMotivation()" class="text-red-600 hover:text-red-700">
                    🔄 Get Motivation
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    
    // Chat functionality
    const chatInput = document.getElementById('chatInput');
    const sendButton = document.getElementById('sendChat');
    const chatMessages = document.getElementById('chatMessages');
    
    function addMessage(message, isUser = false) {
        const div = document.createElement('div');
        div.className = `flex ${isUser ? 'justify-end' : 'justify-start'}`;
        div.innerHTML = `
            <div class="${isUser ? 'bg-purple-600 text-white' : 'bg-gray-200 text-gray-800'} rounded-2xl p-3 max-w-[80%]">
                <p class="text-sm whitespace-pre-wrap">${escapeHtml(message)}</p>
            </div>
        `;
        chatMessages.appendChild(div);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    async function sendMessage() {
        const message = chatInput.value.trim();
        if (!message) return;
        
        addMessage(message, true);
        chatInput.value = '';
        
        try {
            const response = await fetch('/ai/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ message })
            });
            const data = await response.json();
            if (data.success) {
                addMessage(data.response);
            } else {
                addMessage("Sorry, I encountered an error. Please try again.");
            }
        } catch (error) {
            addMessage("Network error. Please check your connection.");
        }
    }
    
    sendButton.addEventListener('click', sendMessage);
    chatInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') sendMessage();
    });
    
    // Workout Recommendation
    async function getWorkoutRecommendation() {
        const container = document.getElementById('workoutRecommendation');
        container.innerHTML = '<div class="animate-pulse">🤔 Analyzing your profile...</div>';
        
        try {
            const response = await fetch('/ai/recommend-workout');
            const data = await response.json();
            if (data.success && data.data) {
                displayWorkoutRecommendation(data.data);
            } else {
                container.innerHTML = '<p class="text-red-500">Unable to generate recommendation</p><button onclick="getWorkoutRecommendation()" class="text-purple-600 mt-2">Try Again</button>';
            }
        } catch (error) {
            container.innerHTML = '<p class="text-red-500">Error loading recommendation</p>';
        }
    }
    
    function displayWorkoutRecommendation(workout) {
        let html = '<div class="space-y-3">';
        html += `<h4 class="font-bold text-purple-600">${workout.workout_name || 'Your Personalized Workout'}</h4>`;
        
        if (workout.warmup) {
            html += '<div><strong>🔥 Warm-up:</strong><ul class="ml-4 mt-1">';
            workout.warmup.forEach(w => {
                html += `<li>• ${w.exercise}: ${w.duration} sec</li>`;
            });
            html += '</ul></div>';
        }
        
        if (workout.exercises) {
            html += '<div><strong>💪 Main Workout:</strong><ul class="ml-4 mt-1">';
            workout.exercises.forEach(ex => {
                html += `<li>• <strong>${ex.name}</strong>: ${ex.sets} sets × ${ex.reps} reps (rest ${ex.rest} sec)</li>`;
            });
            html += '</ul></div>';
        }
        
        if (workout.cooldown) {
            html += '<div><strong>🧘 Cool-down:</strong><ul class="ml-4 mt-1">';
            workout.cooldown.forEach(c => {
                html += `<li>• ${c.exercise}: ${c.duration} sec</li>`;
            });
            html += '</ul></div>';
        }
        
        if (workout.motivation) {
            html += `<p class="text-purple-600 italic mt-2">✨ "${workout.motivation}"</p>`;
        }
        
        html += '<button onclick="getWorkoutRecommendation()" class="text-purple-600 text-sm mt-3">⟳ Generate New</button>';
        html += '</div>';
        
        document.getElementById('workoutRecommendation').innerHTML = html;
    }
    
    // Nutrition Advice
    async function getNutritionAdvice() {
        const container = document.getElementById('nutritionAdvice');
        container.innerHTML = '<div class="animate-pulse">🥗 Analyzing your needs...</div>';
        
        try {
            const response = await fetch('/ai/nutrition-advice');
            const data = await response.json();
            if (data.success && data.data) {
                displayNutritionAdvice(data.data);
            } else {
                container.innerHTML = '<p class="text-red-500">Unable to load advice</p>';
            }
        } catch (error) {
            container.innerHTML = '<p class="text-red-500">Error loading advice</p>';
        }
    }
    
    function displayNutritionAdvice(nutrition) {
        let html = '<div class="space-y-2">';
        html += `<p><strong>🔥 Calories:</strong> ${nutrition.daily_calories || '2000-2200'}</p>`;
        html += `<p><strong>🥩 Protein:</strong> ${nutrition.protein || '150-180g'} | <strong>🍚 Carbs:</strong> ${nutrition.carbs || '200-250g'} | <strong>🥑 Fats:</strong> ${nutrition.fats || '50-60g'}</p>`;
        if (nutrition.meal_ideas) {
            html += '<p><strong>🍽️ Meal Ideas:</strong></p><ul class="ml-4">';
            nutrition.meal_ideas.forEach(meal => {
                html += `<li>• ${meal}</li>`;
            });
            html += '</ul>';
        }
        html += `<p class="text-green-600 italic mt-2">💧 ${nutrition.hydration || 'Drink 2-3L water daily'}</p>`;
        html += '<button onclick="getNutritionAdvice()" class="text-green-600 text-sm mt-2">⟳ Refresh</button>';
        html += '</div>';
        
        document.getElementById('nutritionAdvice').innerHTML = html;
    }
    
    // Progress Prediction
    async function getProgressPrediction() {
        const container = document.getElementById('progressPrediction');
        container.innerHTML = '<div class="animate-pulse">📊 Analyzing your data...</div>';
        
        try {
            const response = await fetch('/ai/predict-progress');
            const data = await response.json();
            if (data.success && data.data) {
                displayProgressPrediction(data.data);
            } else {
                container.innerHTML = '<p class="text-red-500">Unable to predict</p>';
            }
        } catch (error) {
            container.innerHTML = '<p class="text-red-500">Error loading prediction</p>';
        }
    }
    
    function displayProgressPrediction(prediction) {
        let html = '<div class="space-y-2">';
        html += `<p><strong>⏰ Weeks to goal:</strong> ${prediction.weeks_to_goal || '8-12'} weeks</p>`;
        html += `<p><strong>📈 Confidence:</strong> ${prediction.confidence_percentage || '75'}%</p>`;
        html += `<p><strong>💪 Recommended frequency:</strong> ${prediction.recommended_frequency || '4'} days/week</p>`;
        if (prediction.suggestions) {
            html += '<p><strong>💡 Suggestions:</strong></p><ul class="ml-4">';
            prediction.suggestions.forEach(s => {
                html += `<li>• ${s}</li>`;
            });
            html += '</ul>';
        }
        html += `<p class="text-blue-600 italic mt-2">✨ "${prediction.motivation_quote || 'Stay consistent, you got this!'}"</p>`;
        html += '<button onclick="getProgressPrediction()" class="text-blue-600 text-sm mt-2">⟳ Refresh</button>';
        html += '</div>';
        
        document.getElementById('progressPrediction').innerHTML = html;
    }
    
    // Form Analysis
    async function analyzeForm() {
        const exercise = document.getElementById('formExercise').value;
        const description = document.getElementById('formDescription').value;
        
        if (!exercise || !description) {
            alert('Please enter both exercise name and description');
            return;
        }
        
        const resultDiv = document.getElementById('formAnalysisResult');
        resultDiv.innerHTML = '<div class="animate-pulse text-center">🔍 Analyzing your form...</div>';
        
        try {
            const response = await fetch('/ai/analyze-form', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ exercise, description })
            });
            const data = await response.json();
            if (data.success && data.data) {
                displayFormAnalysis(data.data);
            } else {
                resultDiv.innerHTML = '<p class="text-red-500">Analysis failed</p>';
            }
        } catch (error) {
            resultDiv.innerHTML = '<p class="text-red-500">Error analyzing form</p>';
        }
    }
    
    function displayFormAnalysis(analysis) {
        let html = '<div class="mt-3 space-y-2 border-t pt-3">';
        html += `<p><strong>✅ Form Quality:</strong> <span class="font-semibold">${analysis.form_quality || 'Good'}</span></p>`;
        
        if (analysis.correct_points) {
            html += '<p><strong>👍 What you\'re doing right:</strong></p><ul class="ml-4">';
            analysis.correct_points.forEach(p => {
                html += `<li class="text-green-600">✓ ${p}</li>`;
            });
            html += '</ul>';
        }
        
        if (analysis.corrections) {
            html += '<p><strong>📝 How to improve:</strong></p><ul class="ml-4">';
            analysis.corrections.forEach(c => {
                html += `<li class="text-blue-600">→ ${c}</li>`;
            });
            html += '</ul>';
        }
        
        if (analysis.tips) {
            html += '<p><strong>💡 Pro Tips:</strong></p><ul class="ml-4">';
            analysis.tips.forEach(t => {
                html += `<li>• ${t}</li>`;
            });
            html += '</ul>';
        }
        
        html += `<p class="text-purple-600 italic mt-2">✨ ${analysis.encouragement || 'Keep practicing, you\'re improving!'}</p>`;
        html += '</div>';
        
        document.getElementById('formAnalysisResult').innerHTML = html;
    }
    
    // Custom Plan
    async function generateCustomPlan() {
        const goal = document.getElementById('planGoal').value;
        const duration = document.getElementById('planDuration').value;
        
        const resultDiv = document.getElementById('customPlanResult');
        resultDiv.innerHTML = '<div class="animate-pulse text-center">📋 Generating your plan...</div>';
        
        try {
            const response = await fetch('/ai/generate-plan', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ goal, duration })
            });
            const data = await response.json();
            if (data.success && data.data) {
                displayCustomPlan(data.data);
            } else {
                resultDiv.innerHTML = '<p class="text-red-500">Plan generation failed</p>';
            }
        } catch (error) {
            resultDiv.innerHTML = '<p class="text-red-500">Error generating plan</p>';
        }
    }
    
    function displayCustomPlan(plan) {
        let html = '<div class="mt-3 space-y-2 border-t pt-3">';
        html += `<h4 class="font-bold text-orange-600">${plan.plan_name || 'Your Custom Plan'}</h4>`;
        html += `<p><strong>⚡ Difficulty:</strong> ${plan.difficulty || 'Intermediate'}</p>`;
        
        if (plan.circuits) {
            plan.circuits.forEach((circuit, idx) => {
                html += `<div class="mt-2"><strong>Circuit ${idx + 1}:</strong> ${circuit.rounds} rounds</div><ul class="ml-4">`;
                circuit.exercises.forEach(ex => {
                    html += `<li>• ${ex.name}: ${ex.reps} reps (rest ${ex.rest} sec)</li>`;
                });
                html += '</ul>';
            });
        }
        
        if (plan.tips) {
            html += '<p><strong>💡 Tips for success:</strong></p><ul class="ml-4">';
            plan.tips.forEach(tip => {
                html += `<li>• ${tip}</li>`;
            });
            html += '</ul>';
        }
        
        html += '<button onclick="generateCustomPlan()" class="text-orange-600 text-sm mt-2">⟳ Generate New Plan</button>';
        html += '</div>';
        
        document.getElementById('customPlanResult').innerHTML = html;
    }
    
    // Motivation
    async function getMotivation() {
        const container = document.getElementById('motivation');
        container.innerHTML = '<div class="animate-pulse">✨ Finding inspiration...</div>';
        
        try {
            const response = await fetch('/ai/motivation');
            const data = await response.json();
            if (data.success) {
                container.innerHTML = `<div class="text-center">
                    <p class="text-red-600 italic text-base font-medium">"${data.quote}"</p>
                    <button onclick="getMotivation()" class="text-red-600 text-sm mt-3">⟳ New Quote</button>
                </div>`;
            } else {
                container.innerHTML = '<p class="text-red-500">Unable to get quote</p>';
            }
        } catch (error) {
            container.innerHTML = '<p class="text-red-500">Error loading motivation</p>';
        }
    }
</script>
@endsection