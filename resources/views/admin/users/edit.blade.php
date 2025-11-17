@extends('layouts.default')

@section('title', 'Edit User')

@section('page-css')
<link rel="stylesheet" href="{{ asset('css/utils/admin_sidebar.css') }}">
<link rel="stylesheet" href="{{ asset('css/LostFoundReport/item_myReport.css') }}">

<style>
    .content {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: flex-start;
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
    .form-group select:hover,
    .form-group input:focus,
    .form-group select:focus {
        border: 1px solid var(--color-primary);
        outline: none;
    }

    .error {
        color: red;
        font-size: 0.9rem;
    }

    small {
        display: block;
        color: #666;
        margin-top: 5px;
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

    .profile-section {
        display: flex;
        flex-direction: column;
        align-items: center; /* Center children horizontally */
        margin-bottom: 1.5rem;
    }

    .profile-preview {
        display: flex;
        justify-content: center; /* Center the image horizontally */
        margin-bottom: 10px;
    }

    .profile-preview img { 
        width: 180px; 
        height: 180px; 
        border-radius: 50%; 
        object-fit: cover; 
        border: 1px solid var(--color-primary); 
    }

    #reset-profile-form {
        display: flex;
        justify-content: center; /* Center the button */
        width: 100%;
    }

    #reset-profile-form .btn {
        width: auto; /* Button width fits content */
    }

    .btn-reset {
        padding: 10px 20px; /* auto width with padding */
        background-color: #f59e0b; 
        color: white;
        border: none;
        border-radius: 0.5rem;
        cursor: pointer;
        font-weight: var(--fw-bold);
        font-size: var(--fs-md);
    }

    .btn-reset:hover {
        background-color: #d97706;
    }
</style>
@endsection

@section('content')
<div class="layout">
    @include('layouts.partials.admin_sidebar')

    <div class="content">
        <div class="form-card">

            <h1>Edit User</h1>

            <div class="profile-section">
                <div class="profile-preview">
                    @php
                        $profileUrl = $user->profileImg 
                            ? asset('storage/' . $user->profileImg)
                            : ($user->userRole === 'Admin' 
                                ? asset('images/profiles/admin_default.png') 
                                : asset('images/profiles/user_default.png'));
                    @endphp
                    <img id="profilePreview" src="{{ $profileUrl }}" alt="{{ $user->userName }}">
                </div>

                <!-- Reset Profile Button below image -->
                <form id="reset-profile-form" action="{{ route('admin.users.resetProfile', $user->userID) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn-reset" onclick="return confirm('Reset profile to default?')">
                        Reset Profile Image to Default
                    </button>
                </form>
            </div>

            <!-- Main Update Form -->
            <form action="{{ route('admin.users.update', $user->userID) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')                              

                <!-- Upload New Profile Image -->
                <!-- <div class="form-group">
                    <label for="profileImg">Upload New Profile (optional)</label>
                    <input type="file" id="profileImg" name="profileImg" accept="image/*">
                    <small>Choose a new profile image to update.</small>
                </div> -->

                <!-- User Email -->
                <div class="form-group">
                    <label for="userEmail">Email</label>
                    <input id="userEmail" type="email" name="userEmail"
                        value="{{ old('userEmail', $user->userEmail) }}" required>
                    @error('userEmail') <span class="error">{{ $message }}</span> @enderror
                </div>

                <!-- User Name -->
                <div class="form-group">
                    <label for="userName">Name</label>
                    <input id="userName" type="text" name="userName"
                        value="{{ old('userName', $user->userName) }}" required>
                    @error('userName') <span class="error">{{ $message }}</span> @enderror
                </div>

                <!-- User Role -->
                <div class="form-group">
                    <label for="userRole">Role</label>
                    <select id="userRole" name="userRole" required>
                        <option value="User" {{ $user->userRole === 'User' ? 'selected' : '' }}>User</option>
                        <option value="Admin" {{ $user->userRole === 'Admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('userRole') <span class="error">{{ $message }}</span> @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password">New Password (optional)</label>
                    <input id="password" type="password" name="password">
                    <small>Leave blank to keep the current password</small>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm New Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation">
                </div>

                <!-- Buttons -->
                <div class="button-stack">
                    <button id="update-category-btn" type="submit">Update User</button>
                    <button id="cancel-category-btn" type="button" onclick="window.location='{{ route('admin.users.index') }}'">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('page-js')
<script src="{{ asset('js/admin_sidebar.js') }}"></script>

<script>
    // Preview uploaded profile image immediately
    const profileInput = document.getElementById('profileImg');
    const profilePreview = document.getElementById('profilePreview');

    profileInput?.addEventListener('change', e => {
        const file = e.target.files[0];
        if(file) {
            profilePreview.src = URL.createObjectURL(file);
        }
    });
</script>
@endsection
