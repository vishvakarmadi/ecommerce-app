@extends('layouts.app')
@section('title', 'Dashboard - Ecommerce SSO')
@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body text-center p-5">
                <div class="mb-4">
                    <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#059669,#10b981);display:inline-flex;align-items:center;justify-content:center;">
                        <i class="fas fa-check fa-2x text-white"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-2">Welcome, {{ Auth::user()->name }}!</h3>
                <p class="text-muted mb-1">You are logged in via <strong>Foodpanda SSO</strong></p>
                <p class="text-muted">Email: <code>{{ Auth::user()->email }}</code></p>

                @if(session('foodpanda_token'))
                <div class="mt-4 p-3 rounded-3" style="background: #f0fdf4;">
                    <p class="mb-0 text-success fw-semibold">
                        <i class="fas fa-link me-1"></i>Foodpanda session is active
                    </p>
                </div>
                @endif

                <hr class="my-4">
                <p class="text-muted small">
                    This demonstrates SSO (Single Sign-On) between the Ecommerce and Foodpanda systems.
                    <br>Logging in via Foodpanda automatically authenticates you here.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
