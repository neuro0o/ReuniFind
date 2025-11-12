@extends('layouts.default')

@section('title', 'Report Found Item')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/utils/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/LostFoundReport/item_form.css') }}">
@endsection

@section('content')
<div class="layout">
  @include('layouts.partials.sidebar')

  <div class="content">
    <div class="form-card">
      <h2 id="form-title">FOUND ITEM REPORT FORM</h2><br>      

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
            <input type="hidden" name="reportType" value="Found">
            <label for="itemName">Item Name</label>
            <input type="text" name="itemName" id="itemName" placeholder="Enter Item Name..." value="{{ old('itemName') }}" required>
            <br>

            <label for="itemCategory">Item Category</label>
            <select name="itemCategory" id="itemCategory" required>
              <option value="">-- Select Item Category --</option>
              @foreach ($categories as $category)
                <option value="{{ $category->categoryID }}">{{ $category->categoryName }}</option>
              @endforeach
            </select>
            <br>

            <label for="itemDescription">Item Description</label>
            <input type="text" name="itemDescription" id="itemDescription" placeholder="Enter Item Description..." value="{{ old('itemDescription') }}" required>
            <br>

            <label for="itemLocation">Found Location</label>
            <select name="itemLocation" id="itemLocation" required>
              <option value="">-- Select Found Location --</option>
              @foreach ($locations as $location)
                <option value="{{ $location->locationID }}">{{ $location->locationName }}</option>
              @endforeach
            </select>
            <br>

            <label for="reportDate">Date Found</label>
            <input type="date" name="reportDate" id="reportDate" placeholder="DD/MM/YY." value="{{ old('reportDate') }}" max="{{ date('Y-m-d') }}"  required>
            <br>

            <!-- Item Report Verification Fields -->
            <label for="verificationNote">Verification Note</label>
            <input type="text" name="verificationNote" id="verificationNote" placeholder="Provide a note to verify authenticity of your report" value="{{ old('verificationNote') }}" required>
            <br>

            <div class="upload-box">
              <input type="file" name="verificationImg" id="verificationImg" hidden>
              <label for="verificationImg" class="upload-btn">Upload Image</label>
              <img id="previewVerificationImg" src="" alt="Image Preview">
            </div>
            <small class="text-muted">*Upload an image to support your report's authenticity.</small>
            <br>

            <button type="submit" class="btn btn-primary">Submit Found Item Report</button>
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

      // Preview Verification Image
      const verificationImgInput = document.getElementById('verificationImg');
      const previewVerificationImg = document.getElementById('previewVerificationImg');
      verificationImgInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = function(e) {
            previewVerificationImg.src = e.target.result;
            previewVerificationImg.style.display = 'block';
          }
          reader.readAsDataURL(file);
        }
      });
    </script>
@endsection
