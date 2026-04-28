@extends('layouts.app')

@section('title', 'My Workouts')

@section('content')
<div style="max-width:1280px;margin:0 auto;">
    
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;" class="fade-in-up">
        <div>
            <h1 style="font-size:1.8rem;font-weight:900;background:linear-gradient(135deg,#fff 20%,#c4b5fd);-webkit-background-clip:text;background-clip:text;color:transparent;margin-bottom:.35rem;">
                My Workouts 💪
            </h1>
            <p style="color:rgba(255,255,255,.4);font-size:.9rem;">Track and manage your fitness journey</p>
        </div>
        @if(Auth::user()->role === 'trainer')
            <a href="{{ route('workouts.create') }}" 
               style="background:linear-gradient(135deg,#8b5cf6,#ec4899);color:#fff;text-decoration:none;padding:12px 24px;border-radius:12px;font-size:.9rem;font-weight:700;box-shadow:0 8px 20px rgba(139,92,246,.35);transition:all .3s;"
               onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 14px 30px rgba(139,92,246,.5)'"
               onmouseout="this.style.transform='';this.style.boxShadow='0 8px 20px rgba(139,92,246,.35)'">
                + Create Workout
            </a>
        @endif
    </div>
    
    @if($workouts->count() > 0)
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(340px,1fr));gap:1.5rem;" class="fade-in-up delay-1">
            @foreach($workouts as $workout)
                <div style="background:rgba(255,255,255,.03);border:1px solid rgba(139,92,246,.18);border-radius:20px;padding:1.6rem;transition:all .3s;"
                     onmouseover="this.style.borderColor='rgba(139,92,246,.4)';this.style.transform='translateY(-4px)';this.style.boxShadow='0 10px 30px rgba(139,92,246,.15)'"
                     onmouseout="this.style.borderColor='rgba(139,92,246,.18)';this.style.transform='';this.style.boxShadow=''">
                    
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1rem;">
                        <div>
                            <h3 style="font-size:1.15rem;font-weight:800;color:#e2d9f3;margin-bottom:0;">{{ $workout->title }}</h3>
                            @if(Auth::user()->role === 'trainee' && isset($workout->trainer))
                                <p style="font-size:.75rem;color:rgba(255,255,255,.4);margin-top:4px;">
                                    👨‍🏫 Assigned by: {{ $workout->trainer->name ?? 'Trainer' }}
                                </p>
                            @endif
                            @if(Auth::user()->role === 'trainer' && isset($workout->trainee))
                                <p style="font-size:.75rem;color:rgba(255,255,255,.4);margin-top:4px;">
                                    👤 Assigned to: {{ $workout->trainee->name ?? 'Trainee' }}
                                </p>
                            @endif
                        </div>
                        @if($workout->completed_at)
                            <span style="background:rgba(16,185,129,.15);border:1px solid rgba(16,185,129,.3);color:#6ee7b7;padding:4px 10px;border-radius:50px;font-size:.7rem;font-weight:700;">
                                ✓ Completed
                            </span>
                        @else
                            <span style="background:rgba(245,158,11,.15);border:1px solid rgba(245,158,11,.3);color:#fcd34d;padding:4px 10px;border-radius:50px;font-size:.7rem;font-weight:700;">
                                In Progress
                            </span>
                        @endif
                    </div>
                    
                    <div style="display:flex;flex-wrap:wrap;gap:.8rem;margin-bottom:1.4rem;">
                        <span style="background:rgba(255,255,255,.05);padding:4px 10px;border-radius:8px;font-size:.75rem;color:rgba(255,255,255,.6);">🏷️ {{ $workout->type }}</span>
                        <span style="background:rgba(255,255,255,.05);padding:4px 10px;border-radius:8px;font-size:.75rem;color:rgba(255,255,255,.6);">📊 {{ $workout->difficulty }}</span>
                        @if($workout->duration_minutes)
                            <span style="background:rgba(255,255,255,.05);padding:4px 10px;border-radius:8px;font-size:.75rem;color:rgba(255,255,255,.6);">⏱️ {{ $workout->duration_minutes }} mins</span>
                        @endif
                    </div>
                    
                    <div style="display:flex;justify-content:space-between;align-items:center;border-top:1px solid rgba(139,92,246,.12);padding-top:1.2rem;">
                        <p style="font-size:.75rem;color:rgba(255,255,255,.4);">{{ count($workout->exercises ?? []) }} exercises</p>
                        
                        <div style="display:flex;gap:8px;">
                            <a href="{{ route('workouts.show', $workout->id) }}" 
                               style="background:rgba(139,92,246,.15);color:#c4b5fd;border:1px solid rgba(139,92,246,.3);padding:6px 14px;border-radius:8px;font-size:.8rem;font-weight:600;text-decoration:none;transition:all .2s;"
                               onmouseover="this.style.background='rgba(139,92,246,.25)'"
                               onmouseout="this.style.background='rgba(139,92,246,.15)'">
                                View
                            </a>
                            @if(Auth::user()->role === 'trainer')
                                <a href="{{ route('workouts.edit', $workout->id) }}" 
                                   style="background:rgba(255,255,255,.06);color:rgba(255,255,255,.7);border:1px solid rgba(255,255,255,.15);padding:6px 14px;border-radius:8px;font-size:.8rem;font-weight:600;text-decoration:none;transition:all .2s;"
                                   onmouseover="this.style.background='rgba(255,255,255,.1)'"
                                   onmouseout="this.style.background='rgba(255,255,255,.06)'">
                                    Edit
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div style="margin-top:2rem;">
            {{ $workouts->links() }}
        </div>
    @else
        <div style="background:rgba(255,255,255,.03);border:1px solid rgba(139,92,246,.18);border-radius:24px;text-align:center;padding:4rem 2rem;" class="fade-in-up">
            <div style="font-size:3.5rem;opacity:.3;margin-bottom:1rem;">🏋️</div>
            <h3 style="font-size:1.4rem;font-weight:800;color:#e2d9f3;margin-bottom:.5rem;">No workouts yet</h3>
            @if(Auth::user()->role === 'trainer')
                <p style="color:rgba(255,255,255,.4);font-size:.95rem;margin-bottom:1.5rem;">Create your first workout to start assigning it to clients</p>
                <a href="{{ route('workouts.create') }}" 
                   style="display:inline-block;background:linear-gradient(135deg,#8b5cf6,#ec4899);color:#fff;text-decoration:none;padding:12px 24px;border-radius:12px;font-size:.9rem;font-weight:700;box-shadow:0 8px 20px rgba(139,92,246,.35);transition:all .3s;"
                   onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 14px 30px rgba(139,92,246,.5)'"
                   onmouseout="this.style.transform='';this.style.boxShadow='0 8px 20px rgba(139,92,246,.35)'">
                    Create Your First Workout
                </a>
            @else
                <p style="color:rgba(255,255,255,.4);font-size:.95rem;">Your trainer hasn't assigned any workouts to you yet.</p>
            @endif
        </div>
    @endif
</div>
@endsection