<?php include 'includes/header.php'; ?>
<?php
// 1️ Redirect guests (not logged in)
if (!isset($_SESSION['user'])) {
    header('Location: auth/login.php');
    exit();
}

// 2️ Redirect if no property selected (didn't come from listings)
if (!isset($_GET['property_id']) && !isset($_SESSION['selected_property'])) {
    header('Location: listings.php');
    exit();
}

// Store selected property in session (optional, for later steps)
$property_id = $_GET['property_id'] ?? $_SESSION['selected_property'];
$_SESSION['selected_property'] = $property_id;
?>
?>
<!-- Rental Application Section -->
<section class="application-section py-5 text-dark">
  <div class="container">
    <div class="row g-4">
      
      <!-- Property Details -->
      <div class="col-md-5">
        <div class="card shadow-sm h-90 mt-5 fade-in-up">
          <img src="asset/images/bg.jpg" class="card-img-top" alt="Property Image">
          <div class="card-body">
            <h4 class="card-title">2-Bedroom Apartment</h4>
            <p class="card-text">
              📍 Makati City <br>
              🛏 2 Bedrooms | 🚿 1 Bathroom <br>
              🏠 Fully Furnished
            </p>
            <h5 class="mt-3" style="color:#2A6F9E;">₱25,000 / month</h5>
          </div>
        </div>
      </div>

      <!-- Application Form -->
      <div class="col-md-7">
        <div class="card shadow-sm mt-5 fade-in-up">
          <div class="card-body">
            <h4 class="mb-4">Rental Application Form</h4>
            <form action="payment.php" method="POST" enctype="multipart/form-data">
              
              <!-- Hidden property info -->
              <input type="hidden" name="property_id" value="123">
              <input type="hidden" name="rent_amount" value="25000">

              <div class="mb-3">
                <label for="full_name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="full_name" name="full_name" required>
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
              </div>
              <div class="mb-3">
                <label for="mobile_number" class="form-label">Mobile Number</label>
                <input type="text" class="form-control" id="mobile_number" name="mobile_number" required>
              </div>
              <div class="mb-3">
                <label for="valid_id" class="form-label">Upload Valid ID</label>
                <input type="file" class="form-control" id="valid_id" name="valid_id" accept="image/*,application/pdf" required>
              </div>

              <div class="mb-3">
                <label for="move_in_date" class="form-label">Expected Move-in Date</label>
                <input type="date" class="form-control" id="move_in_date" name="move_in_date" required>
              </div>
              <div class="mb-3">
                <label for="length_of_stay" class="form-label">Length of Stay</label>
                <select class="form-control" id="length_of_stay" name="length_of_stay" required>
                  <option value="" selected disabled hidden></option>
                  <option value="1 month">1 month</option>
                  <option value="3 months">3 months</option>
                  <option value="6 months">6 months</option>
                  <option value="12 months">12 months</option>
                </select>
              </div>

              <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="terms_agreement" name="terms_agreement" required>
                <label class="form-check-label" for="terms_agreement">
                  I confirm that the details provided are accurate and I agree to the rental terms.
                </label>
              </div>

              <div class="d-flex justify-content-between align-items-center border-top pt-3">
                <h5>Total: <span style="color:#2A6F9E;">₱25,000</span></h5>
                <button type="submit" class="btn btn-primary px-4">Proceed to Payment</button>
              </div>

            </form>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
