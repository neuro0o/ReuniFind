@extends('layouts.default')

<!-- TITLE -->
@section('title', 'Register')

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
          <h2 id="form-title">REGISTER</h2>
          <form method="post" action="{{ route('register.process') }}">
            @csrf

            <label for="userName">Username</label>
            <input type="text" name="userName" id="userName" placeholder="Enter Username..." value="{{ old('userName') }}">

            @error('userName')
              <span class="error-msg" role="alert">{{ $message }}</span>
            @enderror
            <br>

            <label for="userEmail">Email</label>
            <input type="email" name="userEmail" id="userEmail" placeholder="Enter Email Address..." value="{{ old('userEmail') }}">
            
            @error('userEmail')
              <span class="error-msg" role="alert">{{ $message }}</span>
            @enderror
            <br>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="Enter Password...">
            
            @error('password')
              <span class="error-msg" role="alert">{{ $message }}</span>
            @enderror
            <br>

            <label for="password_confirmation">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password...">
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