@extends('layouts.default')

@section('title', 'Add Item Location')

@section('page-css')
  <link rel="stylesheet" href="{{ asset('css/utils/admin_sidebar.css') }}">
  <link rel="stylesheet" href="{{ asset('css/LostFoundReport/item_form.css') }}">

  <style>
  .content {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 20px;
  }

  .form-card {
      width: 90%;
      max-width: 700px;
      padding: 30px;
      border-radius: 1rem;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      background-color: white;
      box-sizing: border-box;
  }

  .button-stack {
      display: flex;
      flex-direction: column;
      gap: 10px;
      width: 100%;
  }

  #add-location-btn {
      background-color: white;
      color: #4CAF50;
      border: 2px solid #4CAF50;
  }

  #add-location-btn:hover {
      background-color: #45a049;
      color: white;
  }

  #cancel-location-btn {
      background-color: white;
      color: #ef4444;
      border: 2px solid #ef4444;
  }

  #cancel-location-btn:hover {
      background-color: #ef4444;
      color: white;
  }

  .form-card input,
  .button-stack button {
      width: 100%;
      box-sizing: border-box;
  }
  </style>
@endsection

@section('content')
<div class="layout">
    @include('layouts.partials.admin_sidebar')

    <div class="content">
        <div class="form-card">
            <h2>Add New Location</h2><br>

            <form action="{{ route('locations.store') }}" method="POST">
                @csrf
                <label for="locationName">Location Name:</label>
                <input type="text" name="locationName" id="locationName" value="{{ old('locationName') }}" placeholder="Enter location name..." required>
                @error('locationName') <small style="color:red">{{ $message }}</small> @enderror
                <br>

                <label for="latitude">Latitude:</label>
                <input type="number" step="0.000001" name="latitude" id="latitude" value="{{ old('latitude') }}" placeholder="Latitude (e.g., 6.123456)" required>
                  @error('latitude') <small style="color:red">{{ $message }}</small> @enderror
                <br>

                <label for="longitude">Longitude:</label>
                <input type="number" step="0.000001" name="longitude" id="longitude" value="{{ old('longitude') }}" placeholder="Latitude (e.g., 6.123456)" required>
                @error('longitude') <small style="color:red">{{ $message }}</small> @enderror
                <br>

                <div class="button-stack">
                    <button type="submit" class="btn btn-primary" id="add-location-btn">Add Location</button>
                    <button type="button" class="btn btn-primary" id="cancel-location-btn" onclick="window.location='{{ route('locations.index') }}'">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('page-js')
  <script src="{{ asset('js/admin_sidebar.js') }}"></script>
@endsection
