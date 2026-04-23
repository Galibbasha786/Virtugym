@extends('layouts.guest')

@section('content')
<div>
    <div class="badge"><span class="bdot"></span> Welcome Back</div>
    <div class="form-title">Sign In</div>
    <div class="form-sub">Continue your fitness journey</div>

    <form method="POST" action="{{ route('login') }}" style="display:flex;flex-direction:column;gap:1rem;">
        @csrf

        <div>
            <label class="lbl">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="inp" placeholder="john@example.com">
            @error('email')<p class="err">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="lbl">Password</label>
            <input id="password" type="password" name="password" required
                   class="inp" placeholder="••••••••">
            @error('password')<p class="err">{{ $message }}</p>@enderror
        </div>

        <div style="display:flex;align-items:center;justify-content:space-between;">
            <label style="display:flex;align-items:center;gap:8px;font-size:.82rem;color:rgba(255,255,255,.4);cursor:pointer;">
                <input type="checkbox" name="remember"> Remember me
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="link-a" style="font-size:.82rem;">Forgot password?</a>
            @endif
        </div>

        <button type="submit" class="btn-go" style="margin-top:.5rem;">Sign In →</button>

        <div class="divider"><span></span><p>or</p><span></span></div>

        <p style="text-align:center;font-size:.83rem;color:rgba(255,255,255,.35);">
            Don't have an account? <a href="{{ route('register') }}" class="link-a">Create Account</a>
        </p>
    </form>
</div>
@endsection