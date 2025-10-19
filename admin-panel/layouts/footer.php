<?php if (isset($_SESSION['admin'])): ?>
    </div> <!-- close main-content -->
  </div> <!-- close sidebar + content flex -->
<?php endif; ?>

<style>
  html, body {
    height: 100%;
    margin: 0;
  }

  body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
  }

  /* The main content wrapper fills the space between header and footer */
  .page-wrapper {
    flex: 1;
    display: flex;
    flex-direction: column;
  }

  footer {
    background: #2A6F9E;
    color: white;
    text-align: center;
    padding: 0.6rem 0; /* smaller height */
    width: 100%;
    margin-top: auto;
  }

  footer a {
    color: white;
    text-decoration: none;
  }

  footer a:hover {
    opacity: 0.8;
  }
</style>

</div> <!-- Close any unclosed container above, if needed -->

<footer>
  <div class="container">
    <p class="mb-2">&copy; 2025 Commupay. All rights reserved.</p>
    <div>
      <a href="#" class="me-3"><i class="fab fa-twitter"></i></a>
      <a href="#" class="me-3"><i class="fab fa-facebook-f"></i></a>
      <a href="#"><i class="fab fa-instagram"></i></a>
    </div>
  </div>
</footer>

<script src="<?php echo ADMINURL; ?>/asset/js/bootstrap.bundle.min.js"></script>
</body>
</html>
