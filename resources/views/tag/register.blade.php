@extends('layouts.default')

@section('title', 'Register Item')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/utils/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/LostFoundReport/item_form.css') }}">
@endsection

@section('content')
<div class="layout">
    @include('layouts.partials.sidebar')

    <div class="content">
        <div class="form-card">
            <h2 id="form-title">REGISTER YOUR ITEM</h2><br>
            <p style="text-align: center; color: #666; margin-bottom: 2rem;">Register to get your personalized Item QR Tag!</p>

            <form action="{{ route('tag.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-grid">
                    <!-- LEFT: Upload Box -->
                    <div class="upload-section">
                        <div class="upload-box">
                            <input type="file" name="itemImg" id="itemImg" accept="image/*" hidden>
                            <label for="itemImg" class="upload-btn">Upload Image</label>
                            <img id="previewImg" src="" alt="Image Preview">
                        </div>
                    </div>

                    <!-- RIGHT: Form Fields -->
                    <div class="form-fields">
                        <label for="itemName">Item Name <span style="color: #ef4444;">*</span></label>
                        <input type="text" id="itemName" name="itemName" placeholder="Enter item name..." value="{{ old('itemName') }}" required>
                        @error('itemName')
                            <span class="error-msg">{{ $message }}</span>
                        @enderror
                        <br>

                        <label for="itemCategory">Item Category <span style="color: #ef4444;">*</span></label>
                        <select id="itemCategory" name="itemCategory" required>
                            <option value="" disabled selected>-- Select Item Category --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->categoryID }}" 
                                        data-description="{{ $category->description }}"
                                        {{ old('itemCategory') == $category->categoryID ? 'selected' : '' }}>
                                    {{ $category->categoryName }}
                                </option>
                            @endforeach
                        </select>
                        <p class="category-description" id="categoryDescription"></p>
                        @error('itemCategory')
                            <span class="error-msg">{{ $message }}</span>
                        @enderror
                        <br>

                        <label for="itemDescription">Item Description <span style="color: #ef4444;">*</span></label>
                        <input type="text" id="itemDescription" name="itemDescription" placeholder="Enter item description..." value="{{ old('itemDescription') }}" required>
                        @error('itemDescription')
                            <span class="error-msg">{{ $message }}</span>
                        @enderror
                        <br>

                        <button type="submit" class="btn btn-primary">Register Item</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('page-js')
    <script src="{{ asset('js/sidebar.js') }}"></script>
    
    <script>
        // Preview Main Item Image
        const itemImgInput = document.getElementById('itemImg');
        const previewImg = document.getElementById('previewImg');
        
        itemImgInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewImg.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });

        // Category Description Display
        const categorySelect = document.getElementById('itemCategory');
        const categoryDescription = document.getElementById('categoryDescription');

        categorySelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const description = selectedOption.getAttribute('data-description');
            categoryDescription.textContent = description || '';
        });

        // Trigger description on page load if value exists (for old() values)
        if (categorySelect.value) {
            const selectedOption = categorySelect.options[categorySelect.selectedIndex];
            const description = selectedOption.getAttribute('data-description');
            categoryDescription.textContent = description || '';
        }
    </script>
@endsection