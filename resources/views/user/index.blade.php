@extends('layouts.user_default')

<!-- HEADER SECTION -->
@section('header')
    
@endsection

<!-- CONTENT SECTION -->
@section('content')    
    <section class="hero">
        <div class="layout">
            @include('layouts.user_sidenav')
            <div class="main-content">
                <img src="{{ asset('images/ReuniFind_Logo.svg') }}" alt="ReuniFind Logo" style="width:100px; margin-bottom:20px;"> 
                <h1>Found something? ReuniFind it!</h1> 
                <p>Because every lost things deserve a way home</p>
                <a href="{{ route('register') }}" class="btn primary">Register</a>
                <a href="{{ route('register') }}" class="btn primary">Register</a>
            </div>
        </div>
    </section>
@endsection


<!-- FOOTER SECTION -->
@section('footer')
    
@endsection