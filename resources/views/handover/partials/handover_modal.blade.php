<link rel="stylesheet" href="{{ asset('css/handover/handover_modal.css') }}">

<!-- Handover Modal Partial -->
<div id="handoverModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h3>Initiate Handover Request</h3>

    <div class="modal-body">
      <form id="handoverForm" method="POST" action="{{ route('handover.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="recipientReportID" id="recipientReportID">

        <label for="senderReportID">Select Your Report:</label>
        <select name="senderReportID" id="senderReportID" required>
          <!-- Options filled dynamically via JS -->
        </select>

        <label for="proofText">Proof / Verification Note:</label>
        <input type="text" name="proofText" id="proofText" placeholder="Describe your proof..." required>

        <label for="proofImg">Upload Proof Image (optional):</label>
        <input type="file" name="proofImg" id="proofImg" accept="image/*">
        <img id="proofImgPreview" alt="Proof preview">
      </form>
    </div>

    <div class="modal-footer">
      <button type="submit" class="btn" form="handoverForm">Send Handover Request</button>
    </div>
  </div>
</div>
