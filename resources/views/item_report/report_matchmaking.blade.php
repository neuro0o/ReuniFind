@extends('layouts.default')

@section('title', 'Suggested Matches')

@section('page-css')
  <link rel="stylesheet" href="{{ asset('css/utils/sidebar.css') }}">
@endsection

@section('content')
<div class="layout">
  @include('layouts.partials.sidebar')

  <div class="main-content">
    <h1>Suggested Matches</h1>
  </div>
</div>
@endsection

@section('page-js')
  <script src="{{ asset('js/sidebar.js') }}"></script>
@endsection
