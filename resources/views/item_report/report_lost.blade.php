@extends('layouts.default')

@section('title', 'Report Lost Item')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/utils/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/LostFoundReport/item_form.css') }}">
@endsection

@section('content')
<div class="layout">
  @include('layouts.partials.sidebar')

  <div class="content">
    <div class="form-card">
      <h2 id="form-title">LOST ITEM REPORT FORM</h2><br>      

      <form method="POST" action="{{ route('item_report.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-grid">
          <!-- LEFT: Upload Box -->
          <div class="upload-section">
            <div class="upload-box">
              <input type="file" name="itemImg" id="itemImg" hidden>
              <label for="itemImg" class="upload-btn">Upload Image</label>
              <img id="previewImg" src="" alt="Image Preview">
            </div>
          </div>

          <!-- RIGHT: Form Fields -->
          <div class="form-fields">
            <input type="hidden" name="reportType" value="Lost">
            <label for="itemName">Item Name</label>
            <input type="text" name="itemName" id="itemName" placeholder="Enter Item Name..." value="{{ old('itemName') }}" required>
            <br>

            <label for="itemCategory">Item Category</label>
            <select name="itemCategory" id="itemCategory" required>
              <option value="">-- Select Item Category --</option>
              @foreach ($categoryEnum as $category)
                <option value="{{ $category }}">{{ $category }}</option>
              @endforeach
            </select>
            <br>

            <label for="itemDescription">Item Description</label>
            <input type="text" name="itemDescription" id="itemDescription" placeholder="Enter Item Description..." value="{{ old('itemDescription') }}">
            <br>

            <label for="itemLocation">Last Seen Location</label>
            <select name="itemLocation" id="itemLocation" required>
              <option value="">-- Select Last Seen Location --</option>
              @foreach ($locationEnum as $location)
                <option value="{{ $location }}">{{ $location }}</option>
              @endforeach
            </select>
            <br>

            <label for="reportDate">Date Lost</label>
            <input type="date" name="reportDate" id="reportDate" placeholder="DD/MM/YY." value="{{ old('reportDate') }}">
            <br>

            <button type="submit" class="btn btn-primary">Submit Lost Item Report</button>
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
      const itemImgInput = document.getElementById('itemImg');
      const previewImg = document.getElementById('previewImg');

      itemImgInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = function(e) {
            previewImg.setAttribute('src', e.target.result);
            previewImg.style.display = 'block';
          }
          reader.readAsDataURL(file);
        } else {
          previewImg.setAttribute('src', '');
          previewImg.style.display = 'none';
        }
      });

    </script>
@endsection
