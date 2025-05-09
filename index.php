<?php include 'includes/header.php';
include 'config/config.php';

?>

<!-- Hero Section -->
<section class="text-center d-flex align-items-center" style="min-height: 80vh;">
  <div class="container py-5">
    <h5 class="mb-2 fs-3">For Homeowners, Landlords, and Renters</h5>
    <h1 class="fw-bold mb-3">Primera Living</h1>
    <p class="lead mb-4 fs-3">
      Making it easy to track, manage, and pay rent —<br>
      from Paper to Platform.
    </p>
    <a href="#" class="btn px-3 py-2 fs-4" style="background-color: #7e57c2; color: white; border: none;">
      View recent listings
    </a>
  </div>
</section>


<!-- Features Section -->
<section class="py-5 bg-light text-dark">
  <div class="container text-center">
    <?php 
    // Features Array
    $features = [
      [
        'icon' => 'fas fa-shield-alt',
        'title' => 'Secure Payments',
        'desc' => 'Pay community dues safely through encrypted digital channels.'
      ],
      [
        'icon' => 'fas fa-chart-line',
        'title' => 'Track & Manage',
        'desc' => 'Monitor contributions, bills, and reports all in one place.'
      ],
      [
        'icon' => 'fas fa-mobile-alt',
        'title' => 'Mobile Ready',
        'desc' => 'Access and pay your dues anytime, anywhere via mobile.'
      ],
    ];
    ?>
    <div class="row">
      <?php foreach ($features as $feature): ?>
        <div class="col-md-4 mb-4">
          <i class="<?php echo $feature['icon']; ?> fa-2x mb-3 text-primary"></i>
          <h5 class="fw-bold"><?php echo $feature['title']; ?></h5>
          <p><?php echo $feature['desc']; ?></p>
        </div>
      <?php endforeach; ?> 
    </div>
  </div>
</section>

<!-- Testimonial Section -->
<section class="py-5 text-white" style="background-color: #7e57c2;">
  <div class="container text-center">
    <h4 class="mb-4">What users are saying</h4>
    <blockquote class="blockquote">
      <p class="mb-0">"Primera Living made it so much easier to handle our rent payments. It's a game changer!"</p>
      <footer class="blockquote-footer text-white-50 mt-2">Maria Santos, Renter</footer>
    </blockquote>
  </div>
</section>
<?php if (!isset($_SESSION['user'])): ?>
<!-- Call to Action -->
<section class="text-center py-5">
  <div class="container">
    <h2 class="mb-3">Ready to simplify your rental payments?</h2>
    <a href="auth/register.php" class="btn btn-lg px-4 py-2" style="background-color: #7e57c2; color: white;">Get Started Now</a>
  </div>
</section>
<?php endif; ?>


<?php include 'includes/footer.php'; ?>
