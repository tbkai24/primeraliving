<?php include 'includes/header.php'; ?>

<!-- Application Form Section -->
<section class="py-5 text-dark" style="background-color: #EBE7DB;">
  <div class="container">
    <h2 class="text-center mb-4">Rent A Property</h2>
    <p class="text-center mb-5">Please fill out the form below to apply for the property you’re interested in.</p>

    <!-- Rent Application Form -->
    <form action="submit_application.php" method="POST" enctype="multipart/form-data">
      <div class="row">
        <!-- Renter Information -->
        <div class="col-md-6 mb-4">
          <h5>Renter Information</h5>
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
            <label for="dob" class="form-label">Date of Birth</label>
            <input type="date" class="form-control" id="dob" name="dob" required>
          </div>
          <div class="mb-3">
            <label for="current_address" class="form-label">Current Address</label>
            <input type="text" class="form-control" id="current_address" name="current_address" required>
          </div>
          <div class="mb-3">
            <label for="valid_id" class="form-label">Upload Valid ID</label>
            <input type="file" class="form-control" id="valid_id" name="valid_id" accept="image/*,application/pdf" required>
          </div>
        </div>

        <!-- Employment Details -->
        <div class="col-md-6 mb-4">
          <h5>Employment Details / Source of Income</h5>
          <div class="mb-3">
            <label for="employment_status" class="form-label">Employment Status</label>
            <select class="form-control" id="employment_status" name="employment_status" required>
              <option value="Employed">Employed</option>
              <option value="Self-Employed">Self-Employed</option>
              <option value="Unemployed">Unemployed</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="occupation" class="form-label">Occupation</label>
            <input type="text" class="form-control" id="occupation" name="occupation" required>
          </div>
          <div class="mb-3">
            <label for="employer_name" class="form-label">Employer/Business Name</label>
            <input type="text" class="form-control" id="employer_name" name="employer_name" required>
          </div>
          <div class="mb-3">
            <label for="monthly_income" class="form-label">Monthly Income</label>
            <input type="number" class="form-control" id="monthly_income" name="monthly_income" required>
          </div>
          <div class="mb-3">
            <label for="proof_of_income" class="form-label">Upload Proof of Income</label>
            <input type="file" class="form-control" id="proof_of_income" name="proof_of_income" accept="image/*,application/pdf" required>
          </div>
        </div>
      </div>

      <!-- Living Details -->
      <div class="mb-4">
        <h5>Living Details</h5>
        <div class="mb-3">
          <label for="num_people" class="form-label">Number of People Moving In</label>
          <input type="number" class="form-control" id="num_people" name="num_people" required>
        </div>
        <div class="mb-3">
        <div class="mb-3">
  <label for="pets" class="form-label">Do you have Pets?</label>
  <select class="form-control" id="pets" name="pets" required>
    <option value="" selected disabled hidden></option>
    <option value="Yes">Yes</option>
    <option value="No">No</option>
  </select>
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

      <!-- Payment Agreement -->
      <div class="mb-4">
  <h5>Payment Agreement</h5>
  <div class="mb-3">
    <label for="payment_method" class="form-label">Preferred Payment Method</label>
    <select class="form-control" id="payment_method" name="payment_method" required>
      <option value="" selected disabled hidden></option>
      <option value="Xendit">Xendit</option>
    </select>
  </div>
</div>

        <div class="form-check mb-3">
  <input class="form-check-input" type="checkbox" id="security_deposit" name="security_deposit" required>
  <label class="form-check-label" for="security_deposit">
    I acknowledge that I will pay a security deposit as required.
  </label>
</div>

<div class="form-check mb-3">
  <input class="form-check-input" type="checkbox" id="terms_agreement" name="terms_agreement" required>
  <label class="form-check-label" for="terms_agreement">
    I confirm that all information provided is accurate and I agree to the rental terms.
  </label>
</div>


      <!-- Submit Button -->
      <div class="text-center">
      <button type="submit" class="btn" style="background-color: #7e57c2; color: white;">Submit Application</button>

      </div>
    </form>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
