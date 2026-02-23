@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body text-center p-5">
                <h3 class="mb-2">Welcome, {{ Auth::user()->name }}!</h3>
                <p class="text-muted">Logged in via <strong>Foodpanda SSO</strong></p>
                <p class="text-muted">Email: <code>{{ Auth::user()->email }}</code></p>

                @if(session('foodpanda_token'))
                <div class="mt-3 p-2 bg-light rounded">
                    <small class="text-success"><i class="fas fa-check-circle me-1"></i>Foodpanda session active</small>
                </div>
                @endif

                <hr class="my-4">
                <p class="text-muted small">
                    This shows SSO working between Ecommerce and Foodpanda apps.<br>
                    Logging in via Foodpanda automatically logs you in here.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
