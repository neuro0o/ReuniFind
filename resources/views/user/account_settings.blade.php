@extends('layouts.default')

<!-- TITLE -->
@section('title', 'Account Settings')

<!-- PAGE SPECIFIC CSS -->
@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/utils/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user/account_settings.css') }}">
@endsection

<!-- HEADER SECTION -->
@section('header')
    
@endsection

<!-- CONTENT SECTION -->
@section('content')
  <div class="layout">
    @if (Auth::user()->userRole === 'Admin')
      @include('layouts.partials.admin_sidebar')
    @else
        @include('layouts.partials.sidebar')
    @endif

    <div class="main-content">
      <div class="dashboard-header">
        <h1>Account Settings</h1>
      </div>

      <div class="account-card">
        <h2>Profile Information</h2>
        <hr>

        <div class="profile-info">
          <img src="{{ $user->profileImg ? asset('storage/' . $user->profileImg) : asset('images/profiles/user_default.png') }}" 
            class="profile-img" alt="Profile">

          <div class="info-text">
            <p><strong>Username:</strong> {{ $user->userName }}</p>
            <p><strong>User Email:</strong> {{ $user->userEmail }}</p>
            <p><strong>Contact Info:</strong> {{ $user->contactInfo ?? '—' }}</p>
          </div>
        </div>

        <div class="actions">
          <button id="editBtn" class="btn btn-edit">Edit</button>
          <button id="deleteBtn" class="btn btn-delete">Delete</button>
        </div>
      </div>

      <!-- container where AJAX modal will load -->
      <div id="modalContainer"></div>
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

    <!-- Account Settings Popup Modal -->
    <script>
      document.getElementById('editBtn').addEventListener('click', async () => {
        const modalContainer = document.getElementById('modalContainer');

        // Prevent loading multiple modals
        if (document.getElementById('editModal')) return;

        try {
          // eslint-disable-next-line
          const response = await fetch('{{ route('account.settings.modal') }}');

          if (!response.ok) throw new Error('Failed to load modal');

          const html = await response.text();
          modalContainer.innerHTML = html;

          // Attach close event listeners AFTER modal is injected
          const modal = document.getElementById('editModal');

          // Use event delegation — works for dynamically loaded content
          modal.addEventListener('click', (e) => {
            if (
              e.target.id === 'closeModal' ||      // X button
              e.target.id === 'closeModalBtn' ||   // Cancel button
              e.target === modal                   // Click outside modal
            ) {
              modal.remove();
            }
          });
        } catch (error) {
          console.error(error);
          alert('Error loading modal');
        }
      });

        // <!-- Delete Account -->
        document.getElementById('deleteBtn').addEventListener('click', function () {
          if (confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('account.delete') }}';
            form.innerHTML = `
              @csrf
              @method('DELETE')
            `;
            document.body.appendChild(form);
            form.submit();
          }
        });

        // <!-- Image Preview -->
        document.addEventListener('change', function(e) {
          if (e.target && e.target.id === 'profileImg') {
            const file = e.target.files[0];
            if (file) {
              const reader = new FileReader();
              reader.onload = function(ev) {
                const img = document.querySelector('.profileImg');
                img.src = ev.target.result; // Show preview instantly
              };
              reader.readAsDataURL(file);
            }
          }
      });
    </script>
@endsection
