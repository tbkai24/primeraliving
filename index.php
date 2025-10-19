<?php 
include 'includes/header.php';
?>

<!-- ========== HERO SECTION ========== -->
<section class="modern-hero d-flex align-items-center">
  <div class="container">
    <div class="row align-items-center">
      
      <!-- Left Content -->
      <div class="col-md-6 text-start hero-text">
        <h5 class="text-uppercase text-muted fw-semibold mb-2">Primera Living</h5>
        <h1 class="display-4 fw-bold">
  Find Your <span id="changing-word" class="changing-word">Perfect Home</span>
</h1>
<p class="lead mb-4">
  Discover verified rentals, manage payments, and enjoy seamless living — all in one trusted platform.
</p>

       <a href="listings.php" class="btn btn-action btn-lg me-3">Browse Listings</a>

<?php if (!isset($_SESSION['user'])): ?>
  <a href="auth/register.php" class="btn btn-outline-primary btn-lg">Get Started</a>
<?php endif; ?>

      </div>

      <!-- Right Image -->
      <div class="col-md-6 text-center hero-image">
        <img src="<?php echo APPURL; ?>/asset/images/hii.jpg" alt="Home Interior" class="img-fluid rounded-4 shadow-lg">
      </div>

    </div>
  </div>
</section>

<!-- ========== FEATURED RENTALS ========== -->
<section class="modern-featured py-5" data-aos="fade-up">
  <div class="container">
    <h2 class="fw-bold mb-4 text-brand text-center" data-aos="zoom-in">Featured Rentals</h2>

    <div id="featuredCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
      <div class="carousel-inner">
        <?php for ($i=1; $i<=3; $i++): ?>
          <div class="carousel-item <?php echo $i === 1 ? 'active' : ''; ?>">
            <div class="d-flex justify-content-center">
              <div class="modern-card shadow-sm" style="max-width: 400px;" data-aos="zoom-in-up">
                <img src="<?php echo APPURL; ?>/asset/images/bg.jpg" class="img-fluid rounded-top" alt="Rental Image">
                <div class="p-4 text-center">
                  <h5 class="fw-bold mb-2">Cozy Apartment #<?php echo $i; ?></h5>
                  <p class="text-muted mb-3">
                    ₱<?php echo number_format(12000 + ($i*1000)); ?>/month · 2BR · Manila
                  </p>
                  <a href="listings.php" class="btn btn-sm btn-action">View Details</a>
                </div>
              </div>
            </div>
          </div>
        <?php endfor; ?>
      </div>

      <!-- Carousel controls -->
      <button class="carousel-control-prev" type="button" data-bs-target="#featuredCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#featuredCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
      </button>
    </div>
  </div>
</section>


<!-- ========== WHY CHOOSE US ========== -->
<section class="modern-features py-5 bg-light" data-aos="fade-up">
  <div class="container text-center">
    <h2 class="fw-bold text-brand mb-5" data-aos="zoom-in">Why Choose Primera Living?</h2>
    <div class="row g-4">
      <div class="col-md-3" data-aos="flip-left" data-aos-delay="100">
        <i class="fas fa-lock fa-2x text-brand mb-3"></i>
        <h6 class="fw-bold">Secure Payments</h6>
        <p>Encrypted and protected transactions for peace of mind.</p>
      </div>
      <div class="col-md-3" data-aos="flip-left" data-aos-delay="200">
        <i class="fas fa-home fa-2x text-brand mb-3"></i>
        <h6 class="fw-bold">Verified Rentals</h6>
        <p>Every listing is checked for accuracy and reliability.</p>
      </div>
      <div class="col-md-3" data-aos="flip-left" data-aos-delay="300">
        <i class="fas fa-handshake fa-2x text-brand mb-3"></i>
        <h6 class="fw-bold">Trusted Network</h6>
        <p>Connecting honest renters with reliable landlords.</p>
      </div>
      <div class="col-md-3" data-aos="flip-left" data-aos-delay="400">
        <i class="fas fa-globe fa-2x text-brand mb-3"></i>
        <h6 class="fw-bold">Access Anywhere</h6>
        <p>Use your dashboard from mobile or desktop anytime.</p>
      </div>
    </div>
  </div>
</section>

<!-- ========== TESTIMONIALS ========== -->
<section class="modern-testimonials py-5" data-aos="fade-up">
  <div class="container text-center">
    <h2 class="fw-bold mb-5 text-brand" data-aos="zoom-in">What Our Clients Say</h2>
    <div class="row g-4">
      <div class="col-md-4" data-aos="fade-right" data-aos-delay="100">
        <div class="testimonial p-4 shadow-sm rounded">
          <p>"Primera Living made renting easy and stress-free."</p>
          <h6 class="fw-bold mt-3">- Maria Santos</h6>
        </div>
      </div>
      <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
        <div class="testimonial p-4 shadow-sm rounded">
          <p>"A great platform for transparent rent management."</p>
          <h6 class="fw-bold mt-3">- John Doe</h6>
        </div>
      </div>
      <div class="col-md-4" data-aos="fade-left" data-aos-delay="300">
        <div class="testimonial p-4 shadow-sm rounded">
          <p>"I love how easy it is to pay rent on the go!"</p>
          <h6 class="fw-bold mt-3">- Lisa Kim</h6>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ========== CALL TO ACTION ========== -->
<?php if (!isset($_SESSION['user'])): ?>
<section class="modern-cta py-5 text-center text-white" data-aos="zoom-in-up">
  <div class="container">
    <h2 class="fw-bold mb-3">Ready to Simplify Your Rental Experience?</h2>
    <a href="auth/register.php" class="btn btn-light btn-lg">Get Started</a>
  </div>
</section>
<?php endif; ?>


<?php include 'includes/footer.php'; ?>

