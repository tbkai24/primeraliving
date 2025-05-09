<?php include 'includes/header.php';
include 'config/config.php';
?>

<!-- Services Section -->
<section class="py-5 text-center" style="background-color: #EBE7DB;">
  <div class="container">
    <div class="mb-5">
      <div class="mb-3">
        <img src="asset/images/service.png" alt="Services Icon" style="width: 200px; height: 130px;">
      </div>
      <h2 class="fw-bold text-dark">Our Services</h2>
      <p class="text-muted">
        Primera Living provides a complete digital solution for tenants — from browsing properties and applying for rentals<br>
        to managing payments and communicating with administrators.
      </p>
    </div>

    <div class="row row-cols-1 row-cols-md-3 g-4">
      <?php
      $services = [
        [
          'image' => 'asset/images/bg.jpg',
          'title' => 'Rental Listings',
          'desc' => 'Browse available rental units with detailed information to help you choose a property that fits your needs and budget.'
        ],
        [
          'image' => 'asset/images/bg.jpg',
          'title' => 'Online Applications',
          'desc' => 'Submit and track your rental applications through our user-friendly online process, anytime, anywhere.'
        ],
        [
          'image' => 'asset/images/bg.jpg',
          'title' => 'Payment Management',
          'desc' => 'Stay on top of your rent with scheduled payment tracking, due date reminders, and digital receipts.'
        ],
        [
          'image' => 'asset/images/bg.jpg',
          'title' => 'Support & Messaging',
          'desc' => 'Easily communicate with admin for inquiries, maintenance requests, or rental concerns through the platform.'
        ],
        [
          'image' => 'asset/images/bg.jpg',
          'title' => 'Secure Transactions',
          'desc' => 'Enjoy peace of mind with secure payment options and transaction history accessible within your account.'
        ],
      ];

      foreach ($services as $service):
        ?>
        <div class="col">
          <div class="card h-100 border-0 shadow-sm rounded">
            <img src="<?php echo $service['image']; ?>" class="card-img-top rounded-top" alt="Service Image">
            <div class="card-body">
              <h5 class="fw-bold"><?php echo $service['title']; ?></h5>
              <p class="text-muted"><?php echo $service['desc']; ?></p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
