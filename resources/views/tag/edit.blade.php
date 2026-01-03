@extends('layouts.default')

@section('title', 'Edit Item')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/utils/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/LostFoundReport/item_form.css') }}">
@endsection

@section('content')
<div class="layout">
    @include('layouts.partials.sidebar')

    <div class="content">
        <div class="form-card">
            <h2 id="form-title">EDIT YOUR ITEM</h2><br>

            <form method="POST" action="{{ route('tag.update', $itemTag->tagID) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <!-- LEFT: Upload Box -->
                    <div class="upload-section">
                        <div class="upload-box-edit">
                            <input type="file" name="itemImg" id="itemImg" accept="image/*" hidden>
                            <label for="itemImg" class="upload-btn">Change Image</label>

                            @if($itemTag->itemImg)
                                <img id="previewImg" src="{{ asset('storage/' . $itemTag->itemImg) }}" alt="Item Image">
                            @else
                                <img id="previewImg" src="" alt="No image" style="display: none;">
                            @endif
                        </div>
                        @if($itemTag->itemImg)
                            <small class="text-muted" style="display: block; margin-top: 0.5rem; text-align: center;">
                                Don't change to keep current image
                            </small>
                        @endif
                    </div>

                    <!-- RIGHT: Form Fields -->
                    <div class="form-fields">
                        <label for="itemName">Item Name <span style="color: #ef4444;">*</span></label>
                        <input type="text" id="itemName" name="itemName" placeholder="Enter item name..." value="{{ old('itemName', $itemTag->itemName) }}" required>
                        @error('itemName')
                            <span class="error-msg">{{ $message }}</span>
                        @enderror
                        <br>

                        <label for="itemCategory">Item Category <span style="color: #ef4444;">*</span></label>
                        <select id="itemCategory" name="itemCategory" required>
                            <option value="">-- Select Item Category --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->categoryID }}" 
                                        data-description="{{ $category->description }}"
                                        {{ old('itemCategory', $itemTag->itemCategory) == $category->categoryID ? 'selected' : '' }}>
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
                        <input type="text" id="itemDescription" name="itemDescription" placeholder="Enter item description..." value="{{ old('itemDescription', $itemTag->itemDescription) }}" required>
                        @error('itemDescription')
                            <span class="error-msg">{{ $message }}</span>
                        @enderror
                        <br>

                        <div style="display: flex; gap: 1rem; width: 90%;">
                            <a href="{{ route('tag.my') }}" class="btn btn-secondary" style="flex: 1; text-align: center; text-decoration: none; display: flex; align-items: center; justify-content: center;">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary" style="flex: 1;">
                                Update Item
                            </button>
                        </div>
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

        // Trigger description on page load (for existing selected value)
        if (categorySelect.value) {
            const selectedOption = categorySelect.options[categorySelect.selectedIndex];
            const description = selectedOption.getAttribute('data-description');
            categoryDescription.textContent = description || '';
        }
    </script>
@endsection
