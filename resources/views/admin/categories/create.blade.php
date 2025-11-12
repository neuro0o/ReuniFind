@extends('layouts.default')

@section('title', 'Add Item Category')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/utils/admin_sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/LostFoundReport/item_form.css') }}">

    <style>
        .content {
            display: flex;
            justify-content: center; /* center horizontally */
            align-items: center;     /* center vertically */
            min-height: 100vh;       /* full viewport height */
            padding: 20px;           /* optional spacing */
        }

        .form-card {
            width: 90%;           /* use 90% of the parent/container width */
            max-width: 700px;     /* don't get too wide on large screens */
            padding: 30px;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            background-color: white;
            box-sizing: border-box; /* include padding in width calculation */
        }

        .button-stack {
            display: flex;
            flex-direction: column;
            gap: 10px; /* spacing between buttons */
            width: 100%; /* match input width */
        }

        #add-category-btn {
            background-color: white;
            color: #4CAF50;
            border: 2px solid #4CAF50;
        }

        #add-category-btn:hover {
            background-color: #45a049;
            color: white;
        }

        #cancel-category-btn {
            background-color: white;
            color: #ef4444;
            border: 2px solid #ef4444;
        }

        #cancel-category-btn:hover {
            background-color: #ef4444;
            color: white;
        }

        /* make inputs and buttons responsive inside the form */
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
            <h2>Add New Category</h2><br>

            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <label for="categoryName">Category Name:</label>
                <input type="text" name="categoryName" id="categoryName" value="{{ old('categoryName') }}" placeholder="Enter category name..." required>
                @error('categoryName') <small style="color:red">{{ $message }}</small> @enderror
                <br>

                <label for="description">Description:</label>
                <input type="text" name="description" id="description" value="{{ old('description') }}" placeholder="Enter category description..." required>
                <br>

                <div class="button-stack">
                    <button type="submit" class="btn btn-primary" id="add-category-btn">Add Category</button>
                    <button type="button" class="btn btn-primary" id="cancel-category-btn" 
                        onclick="window.location='{{ route('categories.index') }}'">
                    Cancel
</button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

@section('page-js')
    <script src="{{ asset('js/admin_sidebar.js') }}"></script>
@endsection
