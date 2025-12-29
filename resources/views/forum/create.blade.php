@extends('layouts.default')

@section('title', 'Create Forum Post')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/utils/sidebar.css') }}">
    @if(Auth::user()->userRole === 'Admin')
        <link rel="stylesheet" href="{{ asset('css/utils/admin_sidebar.css') }}">
    @endif
    <link rel="stylesheet" href="{{ asset('css/forum/forum_form.css') }}">
@endsection

@section('content')
<div class="layout">
    @if(Auth::user()->userRole === 'Admin')
        @include('layouts.partials.admin_sidebar')
    @else
        @include('layouts.partials.sidebar')
    @endif

    <div class="main-content">
        <div class="page-header">
            <h1>Create Forum Post</h1>
        </div>

        @if(session('success'))
            <div class="status-info-card published">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="status-info-card rejected">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    <strong>Please fix the following errors:</strong>
                    <ul style="margin: 0.5rem 0 0 1.5rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="form-card">
            <form action="{{ route('forum.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="forumCategory">
                        Category <span class="required">*</span>
                    </label>
                    <select name="forumCategory" id="forumCategory" class="form-control" required>
                        <option value="">-- Select Category --</option>
                        <option value="Personal Story" {{ old('forumCategory') == 'Personal Story' ? 'selected' : '' }}>
                            📖 Personal Story
                        </option>
                        <option value="Tips & Tricks" {{ old('forumCategory') == 'Tips & Tricks' ? 'selected' : '' }}>
                            💡 Tips & Tricks
                        </option>
                        <option value="Others" {{ old('forumCategory') == 'Others' ? 'selected' : '' }}>
                            💬 Others
                        </option>
                    </select>
                    @error('forumCategory')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="forumTitle">
                        Title <span class="required">*</span>
                    </label>
                    <input type="text" 
                           name="forumTitle" 
                           id="forumTitle" 
                           class="form-control" 
                           placeholder="Enter an engaging title for your post..."
                           value="{{ old('forumTitle') }}"
                           required 
                           maxlength="255">
                    @error('forumTitle')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="forumContent">
                        Content <span class="required">*</span>
                    </label>
                    <textarea name="forumContent" 
                              id="forumContent" 
                              class="form-control" 
                              placeholder="Share your story, tips, or thoughts with the community..."
                              required 
                              rows="8" 
                              maxlength="1000">{{ old('forumContent') }}</textarea>
                    <div class="char-counter" id="charCounter">0 / 1000 characters</div>
                    @error('forumContent')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="forumImg">
                        Image (Optional)
                    </label>
                    <div class="upload-box" onclick="document.getElementById('forumImg').click()">
                        <input type="file" 
                               name="forumImg" 
                               id="forumImg" 
                               accept="image/jpeg,image/png,image/jpg" 
                               hidden>
                        <label for="forumImg" class="upload-btn">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Click to upload image</span>
                            <small>JPG, PNG (max 10MB)</small>
                        </label>
                        <img id="previewImg" src="" alt="Preview" style="display: none;">
                    </div>
                    @error('forumImg')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <!-- <i class="fas fa-paper-plane"></i> -->
                        Publish Post
                    </button>
                    <a href="{{ route('forum.index') }}" class="btn-cancel">
                        <!-- <i class="fas fa-times"></i> -->
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('page-js')
    @if(Auth::user()->userRole === 'Admin')
        <script src="{{ asset('js/admin_sidebar.js') }}"></script>
    @else
        <script src="{{ asset('js/sidebar.js') }}"></script>
    @endif
    
    <script>
        // Character counter
        const contentField = document.getElementById('forumContent');
        const charCounter = document.getElementById('charCounter');

        function updateCharCounter() {
            const length = contentField.value.length;
            charCounter.textContent = `${length} / 1000 characters`;
            
            if (length > 950) {
                charCounter.classList.add('error');
                charCounter.classList.remove('warning');
            } else if (length > 900) {
                charCounter.classList.add('warning');
                charCounter.classList.remove('error');
            } else {
                charCounter.classList.remove('warning', 'error');
            }
        }

        contentField.addEventListener('input', updateCharCounter);
        updateCharCounter();

        // Image preview
        const imgInput = document.getElementById('forumImg');
        const previewImg = document.getElementById('previewImg');
        const uploadBtn = document.querySelector('.upload-btn');

        imgInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewImg.style.display = 'block';
                    uploadBtn.style.display = 'none';
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    </script>
@endsection