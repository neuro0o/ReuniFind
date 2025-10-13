@extends('layouts.default')

<!-- TITLE -->
@section('title', 'Home')

<!-- PAGE SPECIFIC CSS -->
@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
@endsection

<!-- HEADER SECTION -->
@section('header')
    
@endsection

<!-- CONTENT SECTION -->
@section('content')    
    <section class="hero">
        <div class="layout">
            @include('layouts.sidebar')
            <div class="main-content">
                <h1>PAGE TITLE</h1>
                <h6>[-- DASHBOARD CONTENT --]</h6>
            </div>
        </div>
    </section>
@endsection


<!-- FOOTER SECTION -->
@section('footer')
    
@endsection

<!-- PAGE SPECIFIC JS -->
@section('page-js')
    <!-- FIXME: Fix Sidebar Collapse Behavior -->
    <script src="{{ asset('js/sidebar.js') }}"></script>
@endsection