@extends('layouts.default')

<!-- TITLE -->
@section('title', 'ReuniFind Landing Page')

<!-- PAGE SPECIFIC CSS -->
@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/auth/landing.css') }}">
@endsection

<!-- PAGE SPECIFIC JS -->
@section('page-js')

@endsection

<!-- HEADER SECTION -->
@section('header')
    
@endsection

<!-- CONTENT SECTION -->
@section('content')    
    <section class="hero">
        <div class="hero-content">
            <img src="{{ asset('images/ReuniFind_Logo.svg') }}" alt="ReuniFind Logo""> 
            <h1>Found something? <br>ReuniFind it!</h1> 
            <p style="font-style: italic;">Because every lost things deserve a way home</p>
            <div class="button-groups">
                <a href="{{ route('register') }}" class="btn btn-secondary">Register</a>
                <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
            </div>
        </div>
    </section>

    <section class="feature">
        
    </section>
@endsection


<!-- FOOTER SECTION -->
@section('footer')
    
@endsection

<!-- PAGE SPECIFIC JS -->
@section('page-js')
    
@endsection