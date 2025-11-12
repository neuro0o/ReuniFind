@extends('layouts.default')

@section('title', 'Edit Item Category')

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
            margin-top: 20px;
        }

        #update-category-btn {
            background-color: white;
            color: #4CAF50;
            border: 2px solid #4CAF50;
        }

        #update-category-btn:hover {
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

        .form-card input,
        .form-card textarea,
        .button-stack button {
            width: 100%;
            box-sizing: border-box;
        }

        textarea {
            min-height: 80px;
            padding: 8px;
            border-radius: 0.5rem;
            border: 1px solid #ccc;
            resize: vertical;
        }
    </style>
@endsection

@section('content')
<div class="layout">
    @include('layouts.partials.admin_sidebar')

    <div class="content">
        <div class="form-card">
            <h2>Edit Category</h2><br>

            <form action="{{ route('categories.update', $category->categoryID) }}" method="POST">
                @csrf
                @method('PUT')

                <label for="categoryName">Category Name:</label>
                <input type="text" name="categoryName" id="categoryName" value="{{ old('categoryName', $category->categoryName) }}" required>
                @error('categoryName') <small style="color:red">{{ $message }}</small> @enderror
                <br>

                <label for="description">Description:</label>
                <input type="text" name="description" id="description" value="{{ old('description', $category->description) }}"></input>
                <br>

                <div class="button-stack">
                    <button type="submit" class="btn btn-primary" id="update-category-btn">Update Category</button>
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
