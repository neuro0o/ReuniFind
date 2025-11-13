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
            <img src="{{ asset('images/ReuniFind_Logo.svg') }}" alt="ReuniFind Logo"> 
            <h1>Found something? <br>ReuniFind it!</h1> 
            <p style="font-style: italic;">Because every lost thing deserves a way home</p>
            <div class="button-groups">
                <a href="{{ route('register') }}" class="btn btn-secondary">Register</a>
                <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
            </div>
        </div>
    </section>

    <!-- We Boast Section -->
    <section class="we-boast">
        <h2>We Boast</h2>
        <div class="boast-cards">
            <div class="boast-card">
                <div class="boast-icon">👥</div>
                <h3>{{ $totalUsers ?? '0' }}</h3>
                <p>Active Users</p>
            </div>
            <div class="boast-card">
                <div class="boast-icon">📄</div>
                <h3>{{ $totalLost ?? '0' }}</h3>
                <p>Lost Reports</p>
            </div>
            <div class="boast-card">
                <div class="boast-icon">🔍</div>
                <h3>{{ $totalFound ?? '0' }}</h3>
                <p>Found Reports</p>
            </div>
            <div class="boast-card">
                <div class="boast-icon">✅</div>
                <h3>{{ $totalHandover ?? '0' }}</h3>
                <p>Successful Handovers</p>
            </div>
        </div>
    </section>

    <!-- ReuniFind Features Section -->
    <section class="feature">
        <h2>ReuniFind Features</h2>
        <div class="feature-groups">
            @php
            $features = [
                ['icon'=>'report.svg','title'=>'Lost/Found Item Reporting','desc'=>'Submit lost or found items easily through our platform.'],
                ['icon'=>'admin-verify.svg','title'=>'Verified by Admin','desc'=>'Ensure credibility with admin verification before publishing.'],
                ['icon'=>'map.svg','title'=>'Interactive Campus Map','desc'=>'Pin last-seen or found locations on our interactive map.'],
                ['icon'=>'match.svg','title'=>'Item Matchmaking','desc'=>'Automatic suggestions to match lost and found items.'],
                ['icon'=>'qr-tag.svg','title'=>'QR-Tag Registry','desc'=>'Register your items with QR tags for easier identification.'],
                ['icon'=>'analytics.svg','title'=>'Report Insights & Analytics','desc'=>'Get statistics and insights from reports to track trends.'],
                ['icon'=>'help.svg','title'=>'Help Center & Feedback','desc'=>'Reach out to our team for help or provide feedback.'],
                ['icon'=>'forum.svg','title'=>'Community Tips & Forum','desc'=>'Share tips, stories, and advice with the ReuniFind community.'],
            ];
            @endphp

            @foreach($features as $feature)
            <div class="feature-card">
                <img src="{{ asset('images/icons/'.$feature['icon']) }}" alt="{{ $feature['title'] }}">
                <div class="feature-text">
                    <h5>{{ $feature['title'] }}</h5>
                    <p>{{ $feature['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </section>
@endsection




<!-- FOOTER SECTION -->
@section('footer')
    
@endsection

<!-- PAGE SPECIFIC JS -->
@section('page-js')
    
@endsection