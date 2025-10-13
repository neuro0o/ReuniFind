@extends('layouts.default')

<!-- TITLE -->
@section('title', 'Register')

<!-- PAGE SPECIFIC CSS -->
@section('page-css')
  <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
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
          <h2 id="form-title">REGISTER</h2>
          <form method="post" action="">
            @csrf

            <label for="userName">Username</label>
            <input type="text" name="userName" id="userName" placeholder="Enter Username..." value="{{ old('userName') }}">
            <br>

            <label for="userEmail">Email</label>
            <input type="email" name="userEmail" id="userEmail" placeholder="Enter Email Address..." value="{{ old('userEmail') }}">
            <br>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="Enter Password...">
            <br>

            <label for="password_confirmation">Confirm Password</label>
            <input type="password" name="password" id="password" placeholder="Confirm Password...">
            <br>

            <button type="submit" class="btn btn-primary">Register</button>
          </form>
          <p class="auth-footer">Already have an account? <a href="{{ route('login') }}">Login</a></p>
        </div>        
      </div>
@endsection


<!-- FOOTER SECTION -->
@section('footer')
    
@endsection

<!-- PAGE SPECIFIC JS -->
@section('page-js')
  
@endsection