@extends('layouts.default')

<!-- TITLE -->
@section('title', 'Lost & Found Item Report Matchmaking')

<!-- PAGE SPECIFIC CSS -->
@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/utils/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth/user_dashboard.css') }}">
@endsection

<!-- HEADER SECTION -->
@section('header')
    
@endsection

<!-- CONTENT SECTION -->
@section('content')
  <div class="layout">
    @include('layouts.partials.sidebar')
    <div class="main-content">
      <h1>Lost & Found Item Report Matchmaking</h1>
      <h3>Suggested Matches</h3>
    </div>
  </div>
@endsection


<!-- FOOTER SECTION -->
@section('footer')
    
@endsection

<!-- PAGE SPECIFIC JS -->
@section('page-js')
    <!-- FIXME: Fix Sidebar Collapse Behavior -->
    <script src="{{ asset('js/sidebar.js') }}"></script>
@endsection