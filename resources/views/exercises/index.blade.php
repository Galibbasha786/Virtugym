@extends('layouts.app')

@section('title', 'Exercise Library')

@section('content')
<div style="max-width:1280px;margin:0 auto;">
    <!-- Header -->
    <div style="margin-bottom:2rem;" class="fade-in-up">
        <h1 style="font-size:1.8rem;font-weight:900;background:linear-gradient(135deg,#fff 20%,#c4b5fd);-webkit-background-clip:text;background-clip:text;color:transparent;margin-bottom:.35rem;">
            Exercise Library 📚
        </h1>
        <p style="color:rgba(255,255,255,.4);font-size:.9rem;">Browse through our collection of exercises</p>
    </div>
    
    <!-- Search & Filters -->
    <div style="background:rgba(255,255,255,.03);border:1px solid rgba(139,92,246,.18);border-radius:20px;padding:1.5rem;margin-bottom:2rem;" class="fade-in-up delay-1">
        <form method="GET" action="{{ route('exercises.index') }}" style="display:flex;flex-direction:column;gap:1.5rem;">
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;">
                <div>
                    <label style="display:block;font-size:.73rem;font-weight:700;color:rgba(196,181,253,.65);letter-spacing:.04em;margin-bottom:6px;">SEARCH</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           style="width:100%;padding:10px 14px;background:rgba(8,8,26,.8);border:1px solid rgba(139,92,246,.25);border-radius:12px;color:#fff;font-size:.88rem;outline:none;"
                           placeholder="Exercise name..."
                           onfocus="this.style.borderColor='rgba(139,92,246,.6)'" onblur="this.style.borderColor='rgba(139,92,246,.25)'">
                </div>
                
                <div>
                    <label style="display:block;font-size:.73rem;font-weight:700;color:rgba(196,181,253,.65);letter-spacing:.04em;margin-bottom:6px;">MUSCLE GROUP</label>
                    <select name="muscle_group" 
                            style="width:100%;padding:10px 14px;background:rgba(8,8,26,.8);border:1px solid rgba(139,92,246,.25);border-radius:12px;color:#fff;font-size:.88rem;outline:none;"
                            onfocus="this.style.borderColor='rgba(139,92,246,.6)'" onblur="this.style.borderColor='rgba(139,92,246,.25)'">
                        <option value="">All</option>
                        @foreach($muscleGroups as $group)
                            <option value="{{ $group->muscle_group }}" {{ request('muscle_group') == $group->muscle_group ? 'selected' : '' }}>
                                {{ $group->muscle_group }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label style="display:block;font-size:.73rem;font-weight:700;color:rgba(196,181,253,.65);letter-spacing:.04em;margin-bottom:6px;">EQUIPMENT</label>
                    <select name="equipment" 
                            style="width:100%;padding:10px 14px;background:rgba(8,8,26,.8);border:1px solid rgba(139,92,246,.25);border-radius:12px;color:#fff;font-size:.88rem;outline:none;"
                            onfocus="this.style.borderColor='rgba(139,92,246,.6)'" onblur="this.style.borderColor='rgba(139,92,246,.25)'">
                        <option value="">All</option>
                        @foreach($equipmentList as $equip)
                            <option value="{{ $equip->equipment }}" {{ request('equipment') == $equip->equipment ? 'selected' : '' }}>
                                {{ $equip->equipment }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label style="display:block;font-size:.73rem;font-weight:700;color:rgba(196,181,253,.65);letter-spacing:.04em;margin-bottom:6px;">DIFFICULTY</label>
                    <select name="difficulty" 
                            style="width:100%;padding:10px 14px;background:rgba(8,8,26,.8);border:1px solid rgba(139,92,246,.25);border-radius:12px;color:#fff;font-size:.88rem;outline:none;"
                            onfocus="this.style.borderColor='rgba(139,92,246,.6)'" onblur="this.style.borderColor='rgba(139,92,246,.25)'">
                        <option value="">All</option>
                        @foreach($difficulties as $diff)
                            <option value="{{ $diff->difficulty }}" {{ request('difficulty') == $diff->difficulty ? 'selected' : '' }}>
                                {{ $diff->difficulty }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div style="display:flex;justify-content:flex-end;gap:1rem;">
                <a href="{{ route('exercises.index') }}" 
                   style="background:rgba(255,255,255,.05);color:rgba(255,255,255,.7);border:1px solid rgba(255,255,255,.15);padding:10px 20px;border-radius:10px;font-size:.85rem;font-weight:600;text-decoration:none;transition:all .2s;"
                   onmouseover="this.style.background='rgba(255,255,255,.1)'"
                   onmouseout="this.style.background='rgba(255,255,255,.05)'">
                    Clear
                </a>
                <button type="submit" 
                        style="background:linear-gradient(135deg,#8b5cf6,#ec4899);color:#fff;border:none;border-radius:10px;padding:10px 20px;font-size:.85rem;font-weight:700;cursor:pointer;box-shadow:0 6px 16px rgba(139,92,246,.3);transition:all .3s;"
                        onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 8px 24px rgba(139,92,246,.4)'"
                        onmouseout="this.style.transform='';this.style.boxShadow='0 6px 16px rgba(139,92,246,.3)'">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>
    
    <!-- Exercises Grid -->
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1.5rem;">
        @forelse($exercises as $exercise)
            <div style="background:rgba(255,255,255,.02);border:1px solid rgba(139,92,246,.15);border-radius:20px;padding:1.5rem;transition:all .3s;display:flex;flex-direction:column;"
                 onmouseover="this.style.borderColor='rgba(139,92,246,.4)';this.style.transform='translateY(-4px)';this.style.boxShadow='0 10px 30px rgba(139,92,246,.15)'"
                 onmouseout="this.style.borderColor='rgba(139,92,246,.15)';this.style.transform='';this.style.boxShadow=''">
                
                <div style="font-size:2.5rem;margin-bottom:1rem;opacity:.9;">
                    @switch($exercise->muscle_group)
                        @case('Chest') 💪 @break
                        @case('Back') 🏋️ @break
                        @case('Legs') 🦵 @break
                        @case('Shoulders') 🎯 @break
                        @case('Arms') 💪 @break
                        @default 🏃
                    @endswitch
                </div>
                
                <h3 style="font-size:1.1rem;font-weight:800;color:#e2d9f3;margin-bottom:.8rem;">{{ $exercise->name }}</h3>
                
                <div style="display:flex;flex-direction:column;gap:6px;margin-bottom:1.2rem;flex:1;">
                    <p style="font-size:.8rem;color:rgba(255,255,255,.5);"><strong style="color:rgba(255,255,255,.8);">Muscle:</strong> {{ $exercise->muscle_group }}</p>
                    <p style="font-size:.8rem;color:rgba(255,255,255,.5);"><strong style="color:rgba(255,255,255,.8);">Equipment:</strong> {{ $exercise->equipment }}</p>
                    <div style="display:flex;align-items:center;gap:6px;margin-top:4px;">
                        <strong style="font-size:.8rem;color:rgba(255,255,255,.8);">Difficulty:</strong>
                        <span style="padding:2px 8px;border-radius:50px;font-size:.7rem;font-weight:700;
                            @if($exercise->difficulty == 'Beginner') background:rgba(16,185,129,.15);color:#6ee7b7;border:1px solid rgba(16,185,129,.3);
                            @elseif($exercise->difficulty == 'Intermediate') background:rgba(245,158,11,.15);color:#fcd34d;border:1px solid rgba(245,158,11,.3);
                            @else background:rgba(239,68,68,.15);color:#fca5a5;border:1px solid rgba(239,68,68,.3);
                            @endif">
                            {{ $exercise->difficulty }}
                        </span>
                    </div>
                </div>
                
                <a href="{{ route('exercises.show', $exercise->id) }}" 
                   style="color:#c4b5fd;font-weight:700;font-size:.85rem;text-decoration:none;display:inline-block;transition:color .2s;"
                   onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#c4b5fd'">
                    View Details →
                </a>
            </div>
        @empty
            <div style="grid-column:1/-1;text-align:center;padding:4rem 2rem;background:rgba(255,255,255,.02);border:1px solid rgba(139,92,246,.15);border-radius:24px;">
                <div style="font-size:4rem;opacity:.3;margin-bottom:1rem;">😢</div>
                <h3 style="font-size:1.3rem;font-weight:800;color:#e2d9f3;margin-bottom:.5rem;">No exercises found</h3>
                <p style="color:rgba(255,255,255,.4);font-size:.9rem;">Try adjusting your filters</p>
            </div>
        @endforelse
    </div>
    
    <div style="margin-top:2.5rem;">
        {{ $exercises->links() }}
    </div>
</div>
@endsection