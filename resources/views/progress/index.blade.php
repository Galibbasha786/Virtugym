@extends('layouts.app')

@section('title', 'Progress')

@section('content')
<div style="max-width:900px;margin:0 auto;padding-top:4rem;" class="fade-in-up">
    <div style="background:rgba(255,255,255,.02);border:1px solid rgba(139,92,246,.15);border-radius:24px;padding:4rem 2rem;text-align:center;box-shadow:0 20px 40px rgba(0,0,0,.2);">
        <div style="font-size:5rem;margin-bottom:1.5rem;animation:bounce 2s infinite;">📈</div>
        <h1 style="font-size:2.2rem;font-weight:900;background:linear-gradient(135deg,#fff 20%,#c4b5fd);-webkit-background-clip:text;background-clip:text;color:transparent;margin-bottom:1rem;">
            Progress Tracking Coming Soon!
        </h1>
        <p style="color:rgba(255,255,255,.4);font-size:1.05rem;max-width:500px;margin:0 auto;">
            Track your fitness journey with beautiful charts and deep analytics. We are working hard to bring you these features.
        </p>
    </div>
</div>
<style>
@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-15px); }
}
</style>
@endsection
