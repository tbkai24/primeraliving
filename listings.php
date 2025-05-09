<?php include 'includes/header.php'; 
include 'config/config.php';
?>

<!-- Recent Listings Section -->
<section class="py-5 text-dark" style="background-color: #EBE7DB;">
  <div class="container">
    <div class="text-center mb-5">
      <h3 class="fw-bold">Recent Listings</h3>
      <p class="text-muted">
        We aim to make the rental process straightforward and transparent, ensuring that <br>
        you find the perfect home and have a positive experience from start to finish.
      </p>
    </div>

    <div class="row row-cols-1 row-cols-md-3 g-4">
      <?php
      $recent = [
        ['id' => 4, 'images' => ['bg.jpg', 'bg.jpg', 'bg.jpg'], 'title' => 'Affordable Bedspace', 'location' => 'Caloocan City', 'price' => '₱2,500 / MONTH', 'desc' => 'Safe and budget-friendly.', 'status' => 'Available'],
        ['id' => 5, 'images' => ['bg.jpg', 'bg.jpg'], 'title' => 'Spacious 2BR Apartment', 'location' => 'Las Piñas City', 'price' => '₱10,000 / MONTH', 'desc' => 'Comfortable living for small families.', 'status' => 'Occupied'],
        ['id' => 6, 'images' => ['bg.jpg', 'bg.jpg'], 'title' => 'Elegant Townhouse', 'location' => 'San Juan City', 'price' => '₱20,000 / MONTH', 'desc' => 'Close to shopping malls and schools.', 'status' => 'Available'],
      ];
      foreach ($recent as $item):
      ?>
      <div class="col">
        <div class="card h-100 border-0 shadow-sm rounded">
          <img src="asset/images/<?php echo $item['images'][0]; ?>" class="card-img-top rounded-top" alt="Recent Property">
          <div class="card-body">
            <h5 class="fw-bold"><?php echo $item['title']; ?></h5>
            <p class="mb-1"><i class="fas fa-map-marker-alt me-2"></i><?php echo $item['location']; ?></p>
            <div class="mb-2">
              <i class="fas fa-star text-warning"></i>
              <i class="fas fa-star text-warning"></i>
              <i class="fas fa-star text-warning"></i>
              <i class="fas fa-star-half-alt text-warning"></i>
              <i class="far fa-star text-warning"></i>
            </div>
            <p class="text-muted small"><?php echo $item['desc']; ?></p>
            <p class="fw-bold"><?php echo $item['price']; ?></p>
            <p>Status: <span class="fw-bold text-<?php echo ($item['status'] == 'Available') ? 'success' : 'danger'; ?>"><?php echo $item['status']; ?></span></p>
            <!-- View Property Button that triggers modal -->
            <button class="btn w-100 mt-3" style="background-color: #7e57c2; color: white;" data-bs-toggle="modal" data-bs-target="#modal<?php echo $item['id']; ?>">View Property</button>
          </div>
        </div>
      </div>

      <!-- Modal for Recent Listings -->
      <div class="modal fade" id="modal<?php echo $item['id']; ?>" tabindex="-1" aria-labelledby="modalLabel<?php echo $item['id']; ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modalLabel<?php echo $item['id']; ?>"><?php echo $item['title']; ?></h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div id="carousel<?php echo $item['id']; ?>" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                  <?php foreach ($item['images'] as $index => $image): ?>
                    <div class="carousel-item <?php echo ($index == 0) ? 'active' : ''; ?>">
                      <img src="asset/images/<?php echo $image; ?>" class="d-block w-100" alt="Property Image">
                    </div>
                  <?php endforeach; ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carousel<?php echo $item['id']; ?>" data-bs-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carousel<?php echo $item['id']; ?>" data-bs-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Next</span>
                </button>
              </div>
              <p class="mt-4"><strong>Description:</strong> <?php echo $item['desc']; ?></p>
              <p><strong>Price:</strong> <?php echo $item['price']; ?></p>
              <p><strong>Status:</strong> <span class="fw-bold text-<?php echo ($item['status'] == 'Available') ? 'success' : 'danger'; ?>"><?php echo $item['status']; ?></span></p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <!-- Rent Button inside Modal, links to the applicationform.php -->
              <a href="applicationform.php?property_id=<?php echo $item['id']; ?>" class="btn btn-primary">Rent</a>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
