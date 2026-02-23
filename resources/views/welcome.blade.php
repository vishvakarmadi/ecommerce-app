@extends('layouts.app')
@section('title', 'Welcome - Ecommerce App')
@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-6 text-center">
        <div class="card p-5">
            <i class="fas fa-shopping-cart fa-5x text-primary mb-4"></i>
            <h1 class="display-5 fw-bold mb-3">Ecommerce App</h1>
            <p class="lead text-muted mb-4">Inventory Management System with SSO Login</p>
            <hr>
            <p class="mb-4">Login with your Foodpanda account to access the inventory system</p>
            <a href="{{ route('sso.foodpanda') }}" class="btn btn-lg px-5 py-3" style="background: linear-gradient(135deg, #d70f64, #ff2b85); color:white; border-radius:14px; font-weight:700; font-size:1.1rem;">
                <i class="fas fa-utensils me-2"></i>Login with Foodpanda
            </a>
            <div class="mt-4">
                <small class="text-muted">
                    <i class="fas fa-shield-alt me-1"></i>Secure OAuth2 Single Sign-On
                </small>
            </div>
            <div class="mt-3 p-3 bg-light rounded-3">
                <p class="mb-1 fw-semibold"><i class="fas fa-info-circle me-1 text-primary"></i>Demo Credentials (on Foodpanda):</p>
                <p class="mb-0 text-muted">Email: <code>test@example.com</code> | Password: <code>12345678</code></p>
            </div>
        </div>
    </div>
</div>
@endsection
