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
        <div class="feature-list">
            <h4>What ReuniFind can do for you?</h4> 
            <p id="feature-desc">
                ReuniFind is University Malaysia Sabah’s dedicated Lost & Found digital
                service built for students, by students. Whether you’ve misplaced something or found
                an item, ReuniFind makes it easy to report, search, and reunite them with their rightful owners.
            </p>  

            <!-- ReuniFind Feature List Card -->
            <div class="feature-groups">
                <div class="feature-card">
                    <img src="{{ asset('images/features/User_Profile_Management_Feature.svg') }}" alt="User Profile Management">
                    <div class="feature-text">
                        <h5>User Profile Management</h5>
                        <p>Create, Customize, Manage, and Delete your account with ease.</p>
                    </div>
                </div>

                <div class="feature-card">
                    <img src="{{ asset('images/features/Lost_Found_Reporting_Feature.svg') }}" alt="Item Reporting">
                    <div class="feature-text">
                        <h5>Lost/Found Reporting</h5>
                        <p>Submit detailed item reports with name, image, category, location, and date.</p>
                    </div>
                </div>

                <div class="feature-card">
                    <img src="{{ asset('images/features/Interactive_Campus_Map_Feature.svg') }}" alt="Item Reporting">
                    <div class="feature-text">
                        <h5>Interactive Campus Map</h5>
                        <p>Pinpoint last-seen/found item location for more accuracy.</p>
                    </div>
                </div>

                <div class="feature-card">
                    <img src="{{ asset('images/features/Item_Matchmaking_Feature.svg') }}" alt="User Profile Management">
                    <div class="feature-text">
                        <h5>Item Matchmaking</h5>
                        <p>Search and get notified of potential item matches in real-time.</p>
                    </div>
                </div>

                <div class="feature-card">
                    <img src="{{ asset('images/features/Secure_Private_Chat_Feature.svg') }}" alt="Item Reporting">
                    <div class="feature-text">
                        <h5>Secure Private Chat</h5>
                        <p>Connect, chat, and verify item claims before physical handover.</p>
                    </div>
                </div>

                <div class="feature-card">
                    <img src="{{ asset('images/features/QR_Tagged_Item_Registry_Feature.svg') }}" alt="Item Reporting">
                    <div class="feature-text">
                        <h5>QR-Tagged Item Registry</h5>
                        <p>Attach QR tag to items for easy owner contact if lost then found.</p>
                    </div>
                </div>

                <div class="feature-card">
                    <img src="{{ asset('images/features/Report_Insights_Analytics_Feature.svg') }}" alt="User Profile Management">
                    <div class="feature-text">
                        <h5>Report Insights & Analytics</h5>
                        <p>View hotspots, trends, and monthly item recovery statistics.</p>
                    </div>
                </div>

                <div class="feature-card">
                    <img src="{{ asset('images/features/Help_Center_Feedback_Feature.svg') }}" alt="Item Reporting">
                    <div class="feature-text">
                        <h5>Help Center & Feedback</h5>
                        <p>Find answers and suggest improvements to enhance experience.</p>
                    </div>
                </div>

                <div class="feature-card">
                    <img src="{{ asset('images/features/Community_Tips_Forum_Feature.svg') }}" alt="Item Reporting">
                    <div class="feature-text">
                        <h5>Community Tips & Forum</h5>
                        <p>Learn and share experiences on keeping items safe with other users.</p>
                    </div>
                </div>
                <br>
                <br>
            </div>
        </div>
    </section>
@endsection


<!-- FOOTER SECTION -->
@section('footer')
    
@endsection

<!-- PAGE SPECIFIC JS -->
@section('page-js')
    
@endsection