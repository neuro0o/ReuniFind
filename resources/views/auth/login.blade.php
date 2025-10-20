@extends('layouts.default')

<!-- TITLE -->
@section('title', 'Login')

<!-- PAGE SPECIFIC CSS -->
@section('page-css')
  <link rel="stylesheet" href="{{ asset('css/auth/auth.css') }}">
@endsection

<!-- HEADER SECTION -->
@section('header')

@endsection

<!-- CONTENT SECTION -->
@section('content')   

      <div class="content">
        <div class="logo">
          <img src="{{ asset('images/ReuniFind_Logo.svg') }}" alt="ReuniFind Logo">
        </div>

        <div class="form-card">
          <h2 id="form-title">LOGIN</h2>
          <form method="post" action="{{ route('login.process') }}">
            @csrf

            <label for="userEmail">Email</label>
            <input type="email" name="userEmail" id="userEmail" placeholder="Enter Email Address..." value="{{ old('userEmail') }}">
            <br>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="Enter Password...">
            <br>

            <button type="submit" class="btn btn-primary">Login</button>
          </form>
          <p class="auth-footer">Don't have an account? <a href="{{ route('register') }}">Register</a></p>
        </div>        
      </div>
@endsection


<!-- FOOTER SECTION -->
@section('footer')
    
@endsection

<!-- PAGE SPECIFIC JS -->
@section('page-js')
  
@endsection