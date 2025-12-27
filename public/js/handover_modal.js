const modal = document.getElementById('handoverModal');
const closeBtn = modal.querySelector('.close');
const recipientInput = document.getElementById('recipientReportID');
const senderSelect = document.getElementById('senderReportID');
const handoverForm = document.getElementById('handoverForm');
const proofImgInput = document.getElementById('proofImg');
const proofImgPreview = document.getElementById('proofImgPreview');
const submitBtn = document.querySelector('button[form="handoverForm"]');

// ========== SHARED FUNCTION TO OPEN HANDOVER MODAL ==========
function openHandoverModal(reportID) {
  recipientInput.value = reportID;
  senderSelect.innerHTML = '<option>Loading...</option>';
  submitBtn.disabled = true;

  fetch(`${window.location.origin}/handover/opposite-reports/${reportID}`)
    .then(res => res.json())
    .then(data => {
      senderSelect.innerHTML = '';
      submitBtn.disabled = true;

      // Handle different response formats
      if (data.noneAvailable) {
        senderSelect.innerHTML = '<option disabled>No matching report found.</option>';
      } else if (data.allUsed) {
        senderSelect.innerHTML = '<option disabled>No available reports. All reports are already used in other requests.</option>';
      } else if (Array.isArray(data) && data.length > 0) {
        // Direct array response
        data.forEach(r => {
          const option = document.createElement('option');
          option.value = r.reportID;
          option.textContent = `${r.itemName} (${r.reportDate})`;
          senderSelect.appendChild(option);
        });
        submitBtn.disabled = false;
      } else if (data.reports && Array.isArray(data.reports) && data.reports.length > 0) {
        // Object with reports array
        data.reports.forEach(r => {
          const option = document.createElement('option');
          option.value = r.reportID;
          option.textContent = `${r.itemName} (${r.reportDate})`;
          senderSelect.appendChild(option);
        });
        submitBtn.disabled = false;
      }
    })
    .catch(err => {
      console.error('Failed to fetch opposite reports', err);
      senderSelect.innerHTML = '<option disabled>Error loading reports</option>';
      submitBtn.disabled = true;
    });

  modal.classList.add('show');
}

// ========== EXISTING: .initiate-handover-btn (from view.blade.php) ==========
document.querySelectorAll('.initiate-handover-btn').forEach(button => {
  button.addEventListener('click', () => {
    const reportID = button.dataset.reportId;
    openHandoverModal(reportID);
  });
});

// ========== NEW: .handover-btn (from suggested_matches.blade.php) ==========
document.querySelectorAll('.handover-btn').forEach(button => {
  button.addEventListener('click', () => {
    const reportID = button.dataset.recipientReportId;
    openHandoverModal(reportID);
  });
});

// ========== CLOSE MODAL ==========
closeBtn.addEventListener('click', () => modal.classList.remove('show'));
window.addEventListener('click', e => {
  if (e.target === modal) modal.classList.remove('show');
});

// ========== PROOF IMAGE PREVIEW ==========
if (proofImgInput) {
  proofImgInput.addEventListener('change', () => {
    const file = proofImgInput.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = e => {
        proofImgPreview.src = e.target.result;
        proofImgPreview.style.display = 'block';
      };
      reader.readAsDataURL(file);
    } else {
      proofImgPreview.src = '';
      proofImgPreview.style.display = 'none';
    }
  });
}