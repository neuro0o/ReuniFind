@extends('layouts.default')

@section('title', 'Item Location Map')

@section('page-css')
<link rel="stylesheet" href="{{ asset('css/utils/modal.css') }}">
<link rel="stylesheet" href="{{ asset('css/LostFoundReport/item_modal.css') }}">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
      crossorigin=""/>

<style>
html, body {
  height: 100%;
  margin: 0;
  padding: 0;
}
#map {
  height: 100vh;
  width: 100vw;
}
.leaflet-popup-content {
  font-size: var(--fs-sm);
  font-weight: var(--fw-bold);
  color: var(--color-primary);
  text-align: center;
}
</style>
@endsection

@section('content')
<div id="map"></div>
@include('item_report.partials.item_modal')
@include('handover.partials.handover_modal')
@endsection

@section('page-js')
<script src="{{ asset('js/item_modal.js') }}"></script>
<script src="{{ asset('js/handover_modal.js') }}"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {

    // -------------------- DATA --------------------
    const reportId = @json($report->reportID);
    const reportName = @json($report->itemName);
    const reportType = @json($report->reportType);
    const reportCategory = @json($report->category->categoryName);
    const reportDate = @json(\Carbon\Carbon::parse($report->reportDate)->format('d M Y, h:i A'));
    const reportDescription = @json($report->itemDescription);
    const reportImage = @json($report->itemImg ? asset('storage/' . $report->itemImg) : 'N/A');
    const locationName = @json($report->location->locationName);

    const coords = @json($coords);

    // -------------------- INITIALIZE MAP --------------------
    const map = L.map('map').setView(coords, 18);

    L.tileLayer('https://api.maptiler.com/maps/openstreetmap/{z}/{x}/{y}.png?key=3ULgvuwfH7CC9eJwAxIb', {
      maxZoom: 20,
      attribution: '&copy; <a href="https://www.maptiler.com/">MapTiler</a> &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors'
    }).addTo(map);

    // -------------------- MARKER --------------------
    const markerIconUrl = reportType === 'Lost'
      ? 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png'
      : 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-blue.png';

    const customIcon = L.icon({
      iconUrl: markerIconUrl,
      shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
      iconSize: [30, 46],
      iconAnchor: [12, 41],
      popupAnchor: [3, -37],
      shadowSize: [51, 51]
    });

    // Pulsing circle
    const pulse = L.circle(coords, {
      color: reportType === 'Lost' ? 'rgba(192,57,43,0.5)' : 'rgba(41,128,185,0.5)',
      fillColor: reportType === 'Lost' ? 'rgba(192,57,43,0.3)' : 'rgba(41,128,185,0.3)',
      fillOpacity: 0.8,
      radius: 15
    }).addTo(map);

    let scale = 1;
    setInterval(() => {
      scale += 0.125;
      if(scale > 2) scale = 1;
      pulse.setRadius(15 * scale);
    }, 100);

    const marker = L.marker(coords, { icon: customIcon }).addTo(map);

    // -------------------- SHOW POPUP INITIALLY --------------------
    marker.bindPopup('Click marker for details').openPopup();

    // -------------------- MARKER CLICK --------------------
    marker.on('click', () => {
      // Close popup
      marker.closePopup();

      // -------------------- SHOW ITEM MODAL --------------------
      const modal = document.getElementById("itemDetailModal");
      const imgElement = document.getElementById("itemImg");
      const actionBtn = document.getElementById("itemActionBtn");

      if(reportImage === 'N/A'){
        imgElement.style.display = 'none';
        document.querySelector('.modal-image-na').style.display = 'flex';
      } else {
        imgElement.src = reportImage;
        imgElement.style.display = 'block';
        document.querySelector('.modal-image-na').style.display = 'none';
      }

      document.getElementById("itemName").textContent = reportName;
      document.getElementById("itemCategory").textContent = reportCategory;
      document.getElementById("reportType").textContent = reportType;
      document.getElementById("itemLocation").textContent = locationName;
      document.getElementById("reportDate").textContent = reportDate;
      document.getElementById("itemDescription").textContent = reportDescription;

      // Configure action button
      if(reportType.toLowerCase() === 'lost'){
        actionBtn.textContent = 'Found it!';
        actionBtn.classList.remove('claim');
        actionBtn.classList.add('found');
      } else {
        actionBtn.textContent = 'Claim it!';
        actionBtn.classList.remove('found');
        actionBtn.classList.add('claim');
      }

      // Remove old listeners and attach new
      const newBtn = actionBtn.cloneNode(true);
      actionBtn.parentNode.replaceChild(newBtn, actionBtn);

      newBtn.addEventListener('click', () => {
        const handoverModal = document.getElementById('handoverModal');
        const recipientInput = handoverModal.querySelector('#recipientReportID');
        const senderSelect = handoverModal.querySelector('#senderReportID');
        const submitBtn = handoverModal.querySelector('button[form="handoverForm"]');

        recipientInput.value = reportId;
        senderSelect.innerHTML = '<option>Loading...</option>';
        submitBtn.disabled = true;

        fetch(`${window.location.origin}/handover/opposite-reports/${reportId}`)
          .then(res => res.json())
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
        modal.classList.remove('show');
      });

      modal.classList.add('show');
    });

  });
</script>
@endsection