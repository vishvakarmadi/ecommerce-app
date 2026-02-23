@extends('layouts.app')
@section('title', 'Welcome - Ecommerce App')
@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-5 text-center">
        <div class="card">
            <div class="card-body p-5">
                <h2 class="mb-3">Ecommerce App</h2>
                <p class="text-muted">Login with your Foodpanda account to access the system.</p>
                <hr>
                <a href="{{ route('sso.foodpanda') }}" class="btn btn-danger btn-lg px-4">
                    <i class="fas fa-utensils me-1"></i> Login with Foodpanda
                </a>
                <div class="mt-4 text-muted small">
                    <strong>Demo:</strong> test@example.com / 12345678
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
