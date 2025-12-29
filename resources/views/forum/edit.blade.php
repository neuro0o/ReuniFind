@extends('layouts.default')

@section('title', 'Edit Forum Post')

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
            <h1>Edit Forum Post</h1>
        </div>

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
            <form action="{{ route('forum.update', $post->forumID) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="forumCategory">Category <span class="required">*</span></label>
                    <select name="forumCategory" id="forumCategory" class="form-control" required>
                        <option value="">-- Select Category --</option>
                        <option value="Personal Story" {{ old('forumCategory', $post->forumCategory) == 'Personal Story' ? 'selected' : '' }}>📖 Personal Story</option>
                        <option value="Tips & Tricks" {{ old('forumCategory', $post->forumCategory) == 'Tips & Tricks' ? 'selected' : '' }}>💡 Tips & Tricks</option>
                        <option value="Others" {{ old('forumCategory', $post->forumCategory) == 'Others' ? 'selected' : '' }}>💬 Others</option>
                    </select>
                    @error('forumCategory')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="forumTitle">Title <span class="required">*</span></label>
                    <input type="text" 
                           name="forumTitle" 
                           id="forumTitle" 
                           class="form-control" 
                           placeholder="Enter an engaging title for your post..." 
                           value="{{ old('forumTitle', $post->forumTitle) }}"
                           required 
                           maxlength="255">
                    @error('forumTitle')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="forumContent">Content <span class="required">*</span></label>
                    <textarea name="forumContent" 
                              id="forumContent" 
                              class="form-control" 
                              placeholder="Share your story, tips, or thoughts with the community..." 
                              required 
                              rows="8" 
                              maxlength="1000">{{ old('forumContent', $post->forumContent) }}</textarea>
                    <div class="char-counter" id="charCounter">0 / 1000 characters</div>
                    @error('forumContent')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="forumImg">Image (Optional)</label>
                    <div class="upload-box" onclick="document.getElementById('forumImg').click()" style="cursor: pointer;">
                        <input type="file" 
                               name="forumImg" 
                               id="forumImg" 
                               accept="image/jpeg,image/png,image/jpg" 
                               hidden>
                        <label for="forumImg" class="upload-btn" style="{{ $post->forumImg ? 'display: none;' : '' }}">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Click to upload image</span>
                            <small>JPG, PNG (max 2MB)</small>
                        </label>
                        <img id="previewImg" 
                             src="{{ $post->forumImg ? asset('storage/' . $post->forumImg) : '' }}" 
                             alt="Preview" 
                             style="{{ $post->forumImg ? 'display: block;' : 'display: none;' }}">
                    </div>
                    @if($post->forumImg)
                        <p style="font-size: 1.3rem; color: rgba(58, 89, 135, 0.6); margin-top: 0.8rem; text-align: center;">
                            <i class="fas fa-info-circle"></i> Click image to change
                        </p>
                    @endif
                    @error('forumImg')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-check"></i> Update Post
                    </button>
                    <a href="{{ route('forum.show', $post->forumID) }}" class="btn-cancel">
                        <i class="fas fa-times"></i> Cancel
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
        updateCharCounter(); // Initialize on page load

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
                    if (uploadBtn) uploadBtn.style.display = 'none';
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    </script>
@endsection