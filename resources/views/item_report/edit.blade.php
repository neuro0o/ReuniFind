@extends('layouts.default')

@section('title', 'Edit Report')

@section('page-css')
  <link rel="stylesheet" href="{{ asset('css/utils/sidebar.css') }}">
  <link rel="stylesheet" href="{{ asset('css/LostFoundReport/item_form.css') }}">
@endsection

@section('content')
  <div class="layout">
    @include('layouts.partials.sidebar')

    <div class="content">
      <div class="form-card">
        <h2 id="form-title">EDIT ITEM REPORT</h2><br>

        <form method="POST" action="{{ route('item_report.update', $report->reportID) }}" enctype="multipart/form-data">
          @csrf
          @method('PUT')

          <div class="form-grid">
            <!-- LEFT: Upload Box -->
            <div class="upload-section">
              <div class="upload-box-edit">
                <input type="file" name="itemImg" id="itemImg" hidden>
                <label for="itemImg" class="upload-btn">Change Image</label>

                <!-- Check if there is an existing image file before -->
                @if ($report->itemImg)
                  <img id="previewImg"
                      src="{{ asset('storage/' . $report->itemImg) }}"
                      alt="Item Image">
                @else
                  <img id="previewImg"
                    src="{{ asset('storage/' . $report->itemImg) }}"
                      alt="No image file included before">
                @endif
              </div>
            </div>

            <!-- RIGHT: Form Fields -->
            <div class="form-fields">
              <label for="reportType">Report Type</label>
              <select name="reportType" id="reportType" required>
                  <option value="">-- Select Report Type --</option>
                  <option value="Lost" {{ $report->reportType == 'Lost' ? 'selected' : '' }}>Lost</option>
                  <option value="Found" {{ $report->reportType == 'Found' ? 'selected' : '' }}>Found</option>
              </select><br>
              <label for="itemName">Item Name</label>
              <input type="text" name="itemName" id="itemName" value="{{ old('itemName', $report->itemName) }}" required><br>

              <label for="itemCategory">Item Category</label>
              <select name="itemCategory" id="itemCategory" required>
                <option value="">-- Select Item Category --</option>
                @foreach ($categoryEnum as $category)
                  <option value="{{ $category }}" {{ $report->itemCategory == $category ? 'selected' : '' }}>
                      {{ $category }}
                  </option>
                @endforeach
              </select><br>

              <label for="itemDescription">Item Description</label> 
              <input type="text" name="itemDescription" id="itemDescription"
                    value="{{ old('itemDescription', $report->itemDescription) }}"><br>

              <label for="itemLocation">Location</label>
              <select name="itemLocation" id="itemLocation" required>
                <option value="">-- Select Location --</option>
                @foreach ($locationEnum as $location)
                  <option value="{{ $location }}" {{ $report->itemLocation == $location ? 'selected' : '' }}>
                      {{ $location }}
                  </option>
                @endforeach
              </select><br>

              <label for="reportDate">Date</label>
              <input type="date" name="reportDate" id="reportDate"
                    value="{{ old('reportDate', $report->reportDate) }}"><br>

              <button type="submit" class="btn btn-primary">Update Report</button>
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
          previewImg.src = e.target.result;
          previewImg.style.display = 'block';
        }
        reader.readAsDataURL(file);
      }
    });
  </script>
@endsection
