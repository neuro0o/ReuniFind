<div id="editModal" class="modal">
  <div class="modal-content">
    <span id="closeModal" class="close">&times;</span>

    {{-- PROFILE FORM --}}
    <form action="{{ route('account.update') }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="profile-section">
        <h3>Profile Information</h3> 
        <img class="profileImg" 
          src="{{ $user->profileImg
              ? asset('storage/' . $user->profileImg)
              : asset($user->userRole === 'Admin'
                  ? 'images/profiles/admin_default.png'
                  : 'images/profiles/user_default.png') }}" 
          alt="Profile">
        <label for="profileImg" class="upload-label">
          Upload Profile Picture
        </label>
        <input type="file" id="profileImg" name="profileImg" style="display:none;">
      </div>

      <div class="account-section">
        <label>User Name</label>
        <input type="text" name="userName" value="{{ old('userName', $user->userName) }}" placeholder="Enter New User Name...">

        <label>Email Address</label>
        <input type="email" name="userEmail" value="{{ old('userEmail', $user->userEmail) }}" placeholder="Enter New Email Address...">

        <label>Contact Info</label>
        <input type="text" name="contactInfo" value="{{ old('contactInfo', $user->contactInfo) }}" placeholder="Enter New Contact Info...">
      </div>

      
      <div class="password-section">
        <h3>Change Password</h3>
        <label>New Password</label>
        <input type="password" name="new_password" placeholder="Enter New Password...">

        <label>Confirm New Password</label>
        <input type="password" name="new_password_confirmation" placeholder="Confirm New Password...">
      </div>


      <!-- <div class="preferences-section">
        <h3>Preferences</h3>
        <div class="toggle-group">
          <label>Email Notification</label>
          <label class="switch">
            <input type="checkbox" name="email_notify" checked>
            <span class="slider"></span>
          </label>
        </div>

        <div class="toggle-group">
          <label>Dark Mode</label>
          <label class="switch">
            <input type="checkbox" name="dark_mode">
            <span class="slider"></span>
          </label>
        </div>
      </div> -->
      
      <div class="action-section">
        <div style="text-align:center;">
        <button type="submit" class="btn-save">Save Changes</button>
        <button type="button" id="closeModalBtn" class="btn-cancel">Cancel</button>
      </div>
      </div>
    </form>
  </div>
</div>
