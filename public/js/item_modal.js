document.addEventListener("DOMContentLoaded", function() {
  // ---------- ITEM MODAL ----------
  const modal = document.getElementById("itemDetailModal");
  const closeBtn = modal.querySelector(".close");
  const actionBtn = document.getElementById("itemActionBtn");

  // Open item modal
  document.querySelectorAll(".view-details-btn").forEach(btn => {
    btn.addEventListener("click", function() {
      const imgElement = document.getElementById("itemImg");
      const imageSrc = this.dataset.image;

      // Image display
      if (imageSrc === 'N/A') {
        imgElement.style.display = 'none';
        document.querySelector('.modal-image-na').style.display = 'flex';
      } else {
        imgElement.src = imageSrc;
        imgElement.style.display = 'block';
        document.querySelector('.modal-image-na').style.display = 'none';
      }

      // Populate details
      document.getElementById("itemName").textContent = this.dataset.name;
      document.getElementById("itemCategory").textContent = this.dataset.category;
      document.getElementById("reportType").textContent = this.dataset.status;
      document.getElementById("itemLocation").textContent = this.dataset.location;
      document.getElementById("reportDate").textContent = this.dataset.date;
      document.getElementById("itemDescription").textContent = this.dataset.description;

      // Show/hide action button based on class
      if (this.classList.contains('no-action-btn')) {
        actionBtn.style.display = 'none';
      } else {
        actionBtn.style.display = 'inline-block';
        // Set button text & classes as before
        if (this.dataset.status.toLowerCase() === 'lost') {
          actionBtn.textContent = 'Found it!';
          actionBtn.classList.remove('claim');
          actionBtn.classList.add('found');
        } else {
          actionBtn.textContent = 'Claim it!';
          actionBtn.classList.remove('found');
          actionBtn.classList.add('claim');
        }
      }


      // Store report info
      actionBtn.dataset.reportId = this.dataset.reportId;
      actionBtn.dataset.reportType = this.dataset.status;

      modal.classList.add("show");
    });
  });

  // Close modal
  closeBtn.onclick = () => modal.classList.remove("show");
  window.onclick = e => { if (e.target === modal) modal.classList.remove("show"); };

  // ---------- HANOVER MODAL TRIGGER ----------
  actionBtn.addEventListener('click', () => {
    const reportId = actionBtn.dataset.reportId;
    if (!reportId) return;

    const handoverModal = document.getElementById('handoverModal');
    const recipientInput = document.getElementById('recipientReportID');
    const senderSelect = document.getElementById('senderReportID');
    const submitBtn = document.querySelector('button[form="handoverForm"]');

    recipientInput.value = reportId;
    senderSelect.innerHTML = '<option>Loading...</option>';
    submitBtn.disabled = true;

    // Fetch opposite reports
    fetch(`${window.location.origin}/handover/opposite-reports/${reportId}`)
      .then(res => {
        if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
        return res.json();
      })
      .then(data => {
        senderSelect.innerHTML = '';
        if (!Array.isArray(data) || data.length === 0) {
          senderSelect.innerHTML = '<option disabled>No matching report found</option>';
          submitBtn.disabled = true;
        } else {
          data.forEach(r => {
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

    handoverModal.classList.add('show');
  });
});