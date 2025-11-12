<link rel="stylesheet" href="{{ asset('css/LostFoundReport/item_modal.css') }}">

<!-- Item Modal -->
<div id="itemDetailModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>

    <div class="modal-body">
      <!-- IMAGE -->
      <div class="item-image">
        <img id="itemImg" src="" alt="Item Image">
        <div class="modal-image-na">N/A</div>
      </div>

      <!-- DETAILS -->
      <div class="item-details">
        <h2 id="itemName"></h2>
        <p><strong>Category:</strong><br> <span id="itemCategory"></span></p>
        <p><strong>Report Type:</strong><br> <span id="reportType"></span></p>
        <p><strong>Location:</strong><br> <span id="itemLocation"></span></p>
        <p><strong>Report Date:</strong><br> <span id="reportDate"></span></p>
        <p><strong>Description:</strong></p>
        <p id="itemDescription"></p>
      </div>
    </div>

    <!-- ACTION BUTTON -->
    <div class="modal-footer">
        <button id="itemActionBtn" class="btn" 
            @if(Auth::user()->userRole === 'Admin') style="display:none;" @endif>
            Action
        </button>
    </div>
  </div>
</div>