<?php 
include 'includes/header.php';
include 'config/config.php';
?>

<!-- 🌅 HOW IT WORKS PAGE -->
<section class="how-section">
  <div class="container py-5 fade-in-up" style="margin-top: 100px;">

    <h2 class="text-center fw-bold mb-3" data-aos="fade-up">How Primera Living Works</h2>
    <p class="lead text-center text-muted mb-5" data-aos="fade-up" data-aos-delay="150">
      Primera Living helps you manage rent, payments, and communication — all in one seamless platform.
    </p>

    <div class="timeline-wrapper">
      <?php 
      $steps = [
        ['icon' => 'fas fa-user-plus', 'title' => '1. Sign Up', 'desc' => 'Create your account as a renter and personalize your profile.'],
        ['icon' => 'fas fa-file-invoice-dollar', 'title' => '2. Track & Pay', 'desc' => 'View payment schedules and settle dues easily online.'],
        ['icon' => 'fas fa-comments', 'title' => '3. Communicate', 'desc' => 'Chat directly with your admin, submit tickets, and receive updates.'],
      ];

      foreach ($steps as $index => $step): 
        $side = $index % 2 == 0 ? 'left' : 'right';
      ?>
      <div class="timeline-step <?php echo $side; ?>" data-aos="fade-up" data-aos-delay="<?php echo $index * 200; ?>">
        <div class="timeline-bubble">
          <i class="<?php echo $step['icon']; ?>"></i>
        </div>
        <div class="timeline-card shadow-sm">
          <h5 class="fw-bold mb-2"><?php echo $step['title']; ?></h5>
          <p class="mb-0"><?php echo $step['desc']; ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- FAQ Section -->
<section class="faq-section py-5" id="faq">
  <div class="container">
    <h3 class="text-center fw-bold mb-4" data-aos="fade-up">Frequently Asked Questions</h3>

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
        <div class="accordion-item" data-aos="fade-up" data-aos-delay="<?php echo $index * 150; ?>">
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
</section>

<!-- CTA Section -->
<?php if (!isset($_SESSION['user_id'])): ?>
<section class="cta-section text-center py-5" data-aos="fade-up">
  <a href="listings.php" class="btn btn-action btn-lg px-4 py-2">Discover Homes</a>
</section>

<?php endif; ?>

<?php include 'includes/footer.php'; ?>

