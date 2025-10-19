<?php 
include 'includes/header.php';
include 'config/config.php';

// Sample listings (replace with DB query later)
$listings = [
  [
    'id' => 1,
    'images' => ['bg.jpg', 'bg.jpg'],
    'title' => 'Affordable Bedspace',
    'location' => 'Caloocan City',
    'price' => 2500,
    'desc' => 'Safe and budget-friendly.',
    'status' => 'Available',
    'category' => 'Most Rated'
  ],
  [
    'id' => 2,
    'images' => ['bg.jpg'],
    'title' => 'Spacious 2BR Apartment',
    'location' => 'Las Piñas City',
    'price' => 10000,
    'desc' => 'Comfortable living for small families.',
    'status' => 'Available',
    'category' => 'Top Choice'
  ],
  [
    'id' => 3,
    'images' => ['bg.jpg'],
    'title' => 'Elegant Townhouse',
    'location' => 'San Juan City',
    'price' => 20000,
    'desc' => 'Close to shopping malls and schools.',
    'status' => 'Not Available',
    'category' => 'Most Rated'
  ],
];

// Render listing card
function renderListingCard($item) { ?>
  <div class="col">
    <div class="card h-100 border-0 shadow-sm rounded position-relative">

<!-- Status Badge -->
<div class="position-absolute top-0 end-0 m-2">
  <span class="badge bg-<?php echo ($item['status'] === 'Available') ? 'success' : 'danger'; ?>">
    <?php echo htmlspecialchars($item['status']); ?>
  </span>
</div>

      <img src="asset/images/<?php echo $item['images'][0]; ?>" 
           class="card-img-top rounded-top" 
           alt="<?php echo htmlspecialchars($item['title']); ?>">

      <div class="card-body d-flex flex-column">
        <h5 class="fw-bold"><?php echo htmlspecialchars($item['title']); ?></h5>
        <p class="mb-1">
          <i class="fas fa-map-marker-alt me-2"></i>
          <?php echo htmlspecialchars($item['location']); ?>
        </p>
        <p class="text-muted small mb-2"><?php echo htmlspecialchars($item['desc']); ?></p>
        <p class="fw-bold mb-2">₱<?php echo number_format($item['price']); ?> / MONTH</p>
        <button class="btn btn-action w-100 mt-auto"  
                data-bs-toggle="modal" 
                data-bs-target="#modal<?php echo $item['id']; ?>">
          View Property
        </button>
      </div>
    </div>
  </div>
<?php } 

// Render modal
function renderListingModal($item) { ?>
  <div class="modal fade" id="modal<?php echo $item['id']; ?>" 
       tabindex="-1" aria-labelledby="modalLabel<?php echo $item['id']; ?>" 
       aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel<?php echo $item['id']; ?>">
            <?php echo htmlspecialchars($item['title']); ?>
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Carousel -->
          <div id="carousel<?php echo $item['id']; ?>" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
              <?php foreach ($item['images'] as $index => $image): ?>
                <div class="carousel-item <?php echo ($index == 0) ? 'active' : ''; ?>">
                  <img src="asset/images/<?php echo $image; ?>" 
                       class="d-block w-100" 
                       alt="<?php echo htmlspecialchars($item['title']); ?> Image <?php echo $index+1; ?>">
                </div>
              <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carousel<?php echo $item['id']; ?>" data-bs-slide="prev">
              <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carousel<?php echo $item['id']; ?>" data-bs-slide="next">
              <span class="carousel-control-next-icon"></span>
            </button>
          </div>
          <!-- Details -->
          <div class="mt-4">
            <p><strong>Description:</strong> <?php echo htmlspecialchars($item['desc']); ?></p>
            <p><strong>Price:</strong> ₱<?php echo number_format($item['price']); ?> / MONTH</p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($item['location']); ?></p>
            <p><strong>Status:</strong> 
              <span class="fw-bold text-<?php echo ($item['status'] === 'Available') ? 'success' : 'danger'; ?>">
                <?php echo htmlspecialchars($item['status']); ?>
              </span>
            </p>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <?php if ($item['status'] === 'Available'): ?>
            <a href="applicationform.php?property_id=<?php echo $item['id']; ?>" class="btn btn-action">Rent</a>
          <?php endif; ?>
          <button type="button" class="btn btn-close-custom" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<!-- Page Content -->
<section class="listings-page py-5 text-dark">

  <div class="container">
    
    <!-- Search & Filters -->
    <form class="row g-3 mb-5 bg-light p-4 rounded shadow-sm">
      <div class="col-md-3">
        <input type="text" class="form-control" name="keyword" placeholder="Search by keyword">
      </div>
      <div class="col-md-3">
        <input type="text" class="form-control" name="location" placeholder="Location">
      </div>
      <div class="col-md-2">
        <select class="form-control" name="price">
          <option value="">Price Range</option>
          <option value="0-5000">₱0 - ₱5,000</option>
          <option value="5001-10000">₱5,001 - ₱10,000</option>
          <option value="10001-20000">₱10,001 - ₱20,000</option>
          <option value="20001">₱20,001+</option>
        </select>
      </div>
      <div class="col-md-2">
        <select class="form-control" name="category">
          <option value="">Category</option>
          <option value="Most Rated">Most Rated</option>
          <option value="Top Choice">Top Choice</option>
        </select>
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-action w-100" >Apply Filter</button>
      </div>
    </form>

    <!-- Listings -->
<div class="row row-cols-1 row-cols-md-3 g-4">
  <?php 
  foreach ($listings as $item) {
    if ($item['status'] !== 'Available') continue;
    renderListingCard($item);
  }
  ?>
</div>
</div>
</section>

<!-- ✅ Place modals AFTER section -->
<?php 
foreach ($listings as $item) {
  if ($item['status'] !== 'Available') continue;
  renderListingModal($item);
}
?>

<?php include 'includes/footer.php'; ?>
