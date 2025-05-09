<?php 
include 'includes/header.php';
include 'config/config.php';
?>

<div class="container py-5">
  <h2 class="text-center mb-4">How Primera Living Works</h2>

  <p class="lead text-center mb-5">
    Primera Living is your digital companion for managing rent, dues, and community services in one convenient platform.
  </p>

  <!-- Steps -->
  <div class="row text-center mb-5">
    <?php 
    $steps = [
      ['icon' => 'fas fa-user-plus', 'title' => '1. Sign Up', 'desc' => 'Create an account as a renter.'],
      ['icon' => 'fas fa-file-invoice-dollar', 'title' => '2. Track & Pay', 'desc' => 'View payment schedules and settle your dues online securely.'],
      ['icon' => 'fas fa-comments', 'title' => '3. Communicate', 'desc' => 'Submit tickets, receive updates, and message your admin.'],
    ];
    foreach ($steps as $step): ?>
      <div class="col-md-4 mb-4">
        <i class="<?php echo $step['icon']; ?> fa-2x mb-3" style="color: #7e57c2;"></i>
        <h5 class="fw-bold"><?php echo $step['title']; ?></h5>
        <p><?php echo $step['desc']; ?></p>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- FAQ -->
  <h3 class="mb-4">Frequently Asked Questions</h3>

  <div class="accordion" id="faqAccordion">
    <?php 
    $faqs = [
      ['question' => 'How do I register an account?', 'answer' => 'Just click the "Register" button on the homepage and fill in your details to get started.'],
      ['question' => 'Is my payment information secure?', 'answer' => 'Yes, we use industry-standard encryption and secure payment gateways to protect your data.'],
      ['question' => 'Can I view my payment history?', 'answer' => 'Absolutely! Just go to your dashboard and click on "Transaction History" to see your past payments.'],
    ];
    foreach ($faqs as $index => $faq): 
      $faqId = 'faq' . ($index + 1);
      $collapseId = 'answer' . ($index + 1);
      $isFirst = $index === 0;
    ?>
      <div class="accordion-item">
        <h2 class="accordion-header" id="<?php echo $faqId; ?>">
          <button class="accordion-button <?php echo $isFirst ? '' : 'collapsed'; ?>" type="button"
                  data-bs-toggle="collapse" data-bs-target="#<?php echo $collapseId; ?>" 
                  aria-expanded="<?php echo $isFirst ? 'true' : 'false'; ?>" 
                  aria-controls="<?php echo $collapseId; ?>">
            <?php echo $faq['question']; ?>
          </button>
        </h2>
        <div id="<?php echo $collapseId; ?>" class="accordion-collapse collapse <?php echo $isFirst ? 'show' : ''; ?>"
             aria-labelledby="<?php echo $faqId; ?>" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            <?php echo $faq['answer']; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
