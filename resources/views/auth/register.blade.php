@extends('layouts.guest')

@section('content')
<div>
    <div class="badge"><span class="bdot"></span> Join VirtuGym Free</div>
    <div class="form-title">Create Account</div>
    <div class="form-sub">Start your fitness journey today</div>

    <form method="POST" action="{{ route('register') }}" style="display:flex;flex-direction:column;gap:.9rem;">
        @csrf

        <div>
            <label class="lbl">Full Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required
                   class="inp" placeholder="John Doe">
            @error('name')<p class="err">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="lbl">Email Address</label>
            <input id="reg-email" type="email" name="email" value="{{ old('email') }}" required
                   class="inp" placeholder="john@example.com">
            @error('email')<p class="err">{{ $message }}</p>@enderror
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.8rem;">
            <div>
                <label class="lbl">Password</label>
                <input id="reg-password" type="password" name="password" required autocomplete="new-password"
                       class="inp" placeholder="••••••••">
                @error('password')<p class="err">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="lbl">Confirm</label>
                <input type="password" name="password_confirmation" required autocomplete="new-password"
                       class="inp" placeholder="••••••••">
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.8rem;">
            <div>
                <label class="lbl">Fitness Level</label>
                <select name="fitness_level" required class="inp">
                    <option value="">Select level</option>
                    <option value="beginner" {{ old('fitness_level')=='beginner'?'selected':'' }}>🌱 Beginner</option>
                    <option value="intermediate" {{ old('fitness_level')=='intermediate'?'selected':'' }}>💪 Intermediate</option>
                    <option value="advanced" {{ old('fitness_level')=='advanced'?'selected':'' }}>🏆 Advanced</option>
                    <option value="expert" {{ old('fitness_level')=='expert'?'selected':'' }}>⚡ Expert</option>
                </select>
                @error('fitness_level')<p class="err">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="lbl">Primary Goal</label>
                <select name="goal" required class="inp">
                    <option value="">Select goal</option>
                    <option value="weight_loss" {{ old('goal')=='weight_loss'?'selected':'' }}>🎯 Weight Loss</option>
                    <option value="muscle_gain" {{ old('goal')=='muscle_gain'?'selected':'' }}>💪 Muscle Gain</option>
                    <option value="endurance" {{ old('goal')=='endurance'?'selected':'' }}>🏃 Endurance</option>
                    <option value="flexibility" {{ old('goal')=='flexibility'?'selected':'' }}>🧘 Flexibility</option>
                    <option value="general_fitness" {{ old('goal')=='general_fitness'?'selected':'' }}>⭐ General Fitness</option>
                </select>
                @error('goal')<p class="err">{{ $message }}</p>@enderror
            </div>
        </div>

        <!-- Optional Details Accordion -->
        <details style="background:rgba(255,255,255,.03);border:1px solid rgba(139,92,246,.2);border-radius:14px;padding:.9rem 1rem;">
            <summary style="cursor:pointer;font-size:.82rem;font-weight:600;color:#a78bfa;list-style:none;display:flex;align-items:center;justify-content:space-between;">
                📋 Optional Details
                <span style="font-size:.7rem;color:rgba(255,255,255,.3);">Age · Weight · Height · Equipment</span>
            </summary>
            <div style="display:flex;flex-direction:column;gap:.8rem;margin-top:.9rem;padding-top:.9rem;border-top:1px solid rgba(139,92,246,.15);">
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:.7rem;">
                    <div>
                        <label class="lbl">Age</label>
                        <input type="number" name="age" value="{{ old('age') }}" class="inp" placeholder="25">
                    </div>
                    <div>
                        <label class="lbl">Weight (kg)</label>
                        <input type="number" step=".1" name="weight" value="{{ old('weight') }}" class="inp" placeholder="70">
                    </div>
                    <div>
                        <label class="lbl">Height (cm)</label>
                        <input type="number" name="height" value="{{ old('height') }}" class="inp" placeholder="175">
                    </div>
                </div>
                <div>
                    <label class="lbl">Gender</label>
                    <select name="gender" class="inp">
                        <option value="">Select</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="lbl">Equipment Available</label>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;margin-top:4px;">
                        @foreach(['dumbbells'=>'Dumbbells','barbell'=>'Barbell','resistance_bands'=>'Resistance Bands','kettlebells'=>'Kettlebells'] as $val=>$label)
                        <label style="display:flex;align-items:center;gap:8px;font-size:.8rem;color:rgba(255,255,255,.4);cursor:pointer;padding:6px 10px;background:rgba(255,255,255,.03);border:1px solid rgba(139,92,246,.15);border-radius:8px;">
                            <input type="checkbox" name="equipment[]" value="{{ $val }}"> {{ $label }}
                        </label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="lbl">Workout Days/Week</label>
                    <select name="workout_days" class="inp">
                        <option value="">Select</option>
                        @for($i=1;$i<=7;$i++)
                        <option value="{{ $i }}">{{ $i }} day{{ $i>1?'s':'' }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="lbl">Injuries / Limitations</label>
                    <textarea name="injuries" rows="2" class="inp" style="resize:vertical;" placeholder="List any injuries or limitations..."></textarea>
                </div>
            </div>
        </details>

        <button type="submit" class="btn-go" style="margin-top:.3rem;">Start Your Journey →</button>

        <p style="text-align:center;font-size:.83rem;color:rgba(255,255,255,.35);">
            Already have an account? <a href="{{ route('login') }}" class="link-a">Sign In</a>
        </p>
    </form>
</div>
@endsection