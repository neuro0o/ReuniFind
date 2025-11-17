@extends('layouts.default')

@section('title', 'Create User')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/utils/admin_sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/LostFoundReport/item_myReport.css') }}">

    <style>
        .content {
            flex: 1; /* take remaining space next to sidebar */
            display: flex;
            justify-content: center;
            align-items: flex-start; /* or center if sidebar doesn't push content down */
            min-height: 100vh;
            padding: 40px 20px;
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

        .form-card h1 {
            margin-bottom: 1.5rem;
            color: var(--color-primary);
            font-size: var(--fs-lg);
            text-align: center;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            font-weight: var(--fw-bold);
            font-size: var(--fs-md);
            display: block;
            margin-bottom: 0.3rem;
            color: var(--color-primary);
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border-radius: 0.5rem;
            box-sizing: border-box;
            font-size: var(--fs-md);
            font-weight: var(--fw-regular);
            background-color: var(--color-secondary);
            color: var(--color-primary);
            border: none;
        }

        .form-group input:hover,
        .form-group select:hover {
            border: 1px solid var(--color-primary);
        }

        .form-group input:focus,
        .form-group select:focus {
            border: 2px solid var(--color-primary);
            outline: none; /* optional, removes default focus outline */
        }

        .error {
            color: red;
            font-size: 0.9rem;
        }

        .button-stack {
            display: flex;
            flex-direction: column;
            gap: 10px;
            width: 100%;
            margin-top: 20px;
        }

        .button-stack button {
            padding: 10px;
            border-radius: 0.5rem;
            font-size: 1rem;
            cursor: pointer;
            border: none;
            width: 100%;
            font-size: var(--fs-md);
            font-weight: var(--fw-bold);
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
    </style>
@endsection

@section('content')
<div class="layout">
    @include('layouts.partials.admin_sidebar')

    <div class="content">
        <div class="form-card">

            <h1>Add New User</h1>

            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="userEmail">Email</label>
                    <input id="userEmail" type="email" name="userEmail" value="{{ old('userEmail') }}" required>
                    @error('userEmail') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="userName">Name</label>
                    <input id="userName" type="text" name="userName" value="{{ old('userName') }}" required>
                    @error('userName') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required>
                    @error('password') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required>
                </div>

                <div class="form-group">
                    <label for="userRole">Role</label>
                    <select id="userRole" name="userRole" required>
                        <option value="User" {{ old('userRole') === 'User' ? 'selected' : '' }}>User</option>
                        <option value="Admin" {{ old('userRole') === 'Admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('userRole') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="button-stack">
                    <button id="update-category-btn" type="submit">
                        Add User
                    </button>

                    <button id="cancel-category-btn" type="button"
                        onclick="window.location='{{ route('admin.users.index') }}'">
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