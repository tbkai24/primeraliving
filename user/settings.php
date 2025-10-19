<?php
include __DIR__ . '/../config/CRUD.php';
include __DIR__ . '/../includes/header.php';

if (!isset($_SESSION['user'])) {
    $_SESSION['login_message'] = "You must log in to access settings.";
    header("Location: ../auth/login.php");
    exit();
}

$crud = new CRUD($pdo);
$userId = $_SESSION['user']['user_id'];
$user = $crud->getUserById($userId);
?>

<body class="d-flex flex-column min-vh-100">
  <main class="flex-grow-1 settings-bg fade-in-settings">
    <div class="container-fluid py-5" style="min-height: 100%;">
      <div class="row">
        <!-- LEFT SIDEBAR -->
        <div class="col-md-4 col-lg-3 settings-menu py-4">
          <h4 class="settings-menu-title">Account Settings</h4>
          <div class="list-group">
            <a href="#" class="list-group-item list-group-item-action active" data-section="profile">
              <i class="fas fa-user-cog me-2"></i> Profile
            </a>
            <a href="#" class="list-group-item list-group-item-action" data-section="security">
              <i class="fas fa-lock me-2"></i> Security
            </a>
            <a href="#" class="list-group-item list-group-item-action" data-section="billing">
              <i class="fas fa-credit-card me-2"></i> Billing
            </a>
            <a href="#" class="list-group-item list-group-item-action" data-section="notifications">
              <i class="fas fa-bell me-2"></i> Notifications
            </a>
          </div>
        </div>

        <!-- RIGHT CONTENT -->
        <div class="col-md-8 col-lg-9 py-5">
          <div class="card shadow-sm p-4">

            <!-- ALERT CONTAINER -->
            <div id="settingsAlert"></div>

            <!-- PROFILE SECTION -->
            <div id="profile-section" class="settings-section">
              <h4 class="text-primary mb-4"><i class="fas fa-user-edit me-2"></i>Edit Profile</h4>
              <form id="update_profile" novalidate>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="form-label">First Name</label>
                    <input type="text" name="first_name" class="form-control"
                           value="<?= htmlspecialchars($user['first_name'] ?? '') ?>" required>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="last_name" class="form-control"
                           value="<?= htmlspecialchars($user['last_name'] ?? '') ?>" required>
                  </div>
                </div>
                <div class="mb-3">
                  <label class="form-label">Email Address</label>
                  <input type="email" name="email" class="form-control"
                         value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Mobile Number</label>
                  <input type="text" name="mobile" class="form-control"
                         value="<?= htmlspecialchars($user['mobile_number'] ?? '') ?>" placeholder="Enter your mobile number">
                </div>
                <button type="submit" class="btn px-4" style="background-color:#2A6F9E; color:white;">
                  <i class="fas fa-save me-1"></i> Save Changes
                </button>
              </form>
            </div>

            <!-- SECURITY SECTION -->
            <div id="security-section" class="settings-section" style="display:none;">
              <h4 class="text-primary mb-4"><i class="fas fa-lock me-2"></i>Security</h4>
              <form id="update_password" novalidate>
                <div class="mb-3">
                  <label class="form-label">Current Password</label>
                  <input type="password" name="current_password" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">New Password</label>
                  <input type="password" name="new_password" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Confirm New Password</label>
                  <input type="password" name="confirm_password" class="form-control" required>
                </div>
                <button type="submit" class="btn px-4" style="background-color:#2A6F9E; color:white;">
                  <i class="fas fa-key me-1"></i> Update Password
                </button>
              </form>

              <hr>

              <form id="toggle_2fa" novalidate>
                <div class="form-check form-switch mb-3">
                  <input class="form-check-input" type="checkbox" id="enable2FA" name="enable_2fa" value="1"
                    <?= isset($user['two_factor_enabled']) && $user['two_factor_enabled']==1 ? 'checked' : '' ?>>
                  <label class="form-check-label" for="enable2FA">Enable Two-Factor Authentication (2FA)</label>
                </div>
                <button type="submit" class="btn px-4" style="background-color:#2A6F9E; color:white;">
                  <i class="fas fa-shield-alt me-1"></i> Save 2FA Setting
                </button>
              </form>
            </div>

            <!-- BILLING SECTION -->
            <div id="billing-section" class="settings-section" style="display:none;">
              <h4 class="text-primary mb-4"><i class="fas fa-credit-card me-2"></i>Billing Info</h4>
              <form id="update_billing" novalidate>
                <div class="mb-3">
                  <label class="form-label">Billing Email</label>
                  <input type="email" name="billing_email" class="form-control" 
                         value="<?= htmlspecialchars($user['billing_email'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Payment Method</label>
                  <select name="payment_method" class="form-control" required>
                    <option value="">Select Method</option>
                    <option value="credit_card" <?= isset($user['billing_method']) && $user['billing_method']=='credit_card'?'selected':'' ?>>Credit Card</option>
                    <option value="paypal" <?= isset($user['billing_method']) && $user['billing_method']=='paypal'?'selected':'' ?>>PayPal</option>
                  </select>
                </div>
                <button type="submit" class="btn px-4" style="background-color:#2A6F9E; color:white;">
                  <i class="fas fa-save me-1"></i> Save Billing Info
                </button>
              </form>
            </div>

            <!-- NOTIFICATIONS SECTION -->
            <div id="notifications-section" class="settings-section" style="display:none;">
              <h4 class="text-primary mb-4"><i class="fas fa-bell me-2"></i>Notification Preferences</h4>
              <form id="update_notifications" novalidate>
                <div class="form-check form-switch mb-3">
                  <input class="form-check-input" type="checkbox" id="emailNotifications" name="email_notifications" value="1"
                    <?= isset($user['email_notifications']) && $user['email_notifications']==1 ? 'checked' : '' ?>>
                  <label class="form-check-label" for="emailNotifications">Email Alerts</label>
                </div>
                <button type="submit" class="btn px-4" style="background-color:#2A6F9E; color:white;">
                  <i class="fas fa-bell me-1"></i> Save Notification Preferences
                </button>
              </form>
            </div>

          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <?php include __DIR__ . '/../includes/footer.php'; ?>
  
  <script src="../asset/js/settings.js"></script>

