<footer class="footer-custom text-white text-center py-4 mt-auto">
  <div class="container-fluid">
    <p class="mb-2">&copy; 2025 Primera Living. All rights reserved.</p>
    <div>
      <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
      <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
      <a href="#" class="text-white"><i class="fab fa-instagram"></i></a>
    </div>
  </div>
</footer>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script src="<?php echo APPURL; ?>/asset/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const words = [
    "Perfect Home",
    "Next Apartment",
    "Dream Space",
    "Cozy Place",
    "New Beginning"
  ];
  const el = document.getElementById("changing-word");
  let i = 0;

  function changeWord() {
    el.style.opacity = 0;
    el.style.transform = "translateY(10px)";
    setTimeout(() => {
      i = (i + 1) % words.length;
      el.textContent = words[i];
      el.style.opacity = 1;
      el.style.transform = "translateY(0)";
    }, 1000);
  }

  // Change every 6 seconds (slow, readable)
  setInterval(changeWord, 6000);
});
</script>

<!-- AOS (Animate On Scroll) JS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({
    duration: 1000,  /* speed of animation (ms) */
    easing: 'ease-in-out', 
    once: true       /* animation runs once only */
  });
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
  const heroSection = document.querySelector(".modern-hero");

  // Trigger animation on load with slight delay
  setTimeout(() => {
    heroSection.classList.add("hero-visible");
  }, 300);
});
</script>

<script>
  // Only run this animation effect if the navbar has 'animated-navbar'
  const navbar = document.querySelector('.animated-navbar');
  if (navbar) {
    window.addEventListener("scroll", function () {
      if (window.scrollY > 60) {
        navbar.classList.add("scrolled");
      } else {
        navbar.classList.remove("scrolled");
      }
    });
  }
</script>

<script>
  const navbar = document.querySelector('.cool-navbar');
  if (navbar && navbar.classList.contains('animated-navbar')) {
    window.addEventListener("scroll", function () {
      if (window.scrollY > 50) {
        navbar.style.background = "linear-gradient(135deg, #1E4E70, #2A6F9E)";
        navbar.style.boxShadow = "0 4px 20px rgba(0,0,0,0.3)";
      } else {
        navbar.style.background = "linear-gradient(135deg, rgba(42,111,158,0.9), rgba(30,78,112,0.9))";
        navbar.style.boxShadow = "0 4px 12px rgba(0,0,0,0.1)";
      }
    });
  }
</script>

<!-- AOS JS (place before closing body) -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({
    duration: 700,   // animation duration in ms
    easing: 'ease-out-cubic',
    once: true,      // animate only once
    offset: 120      // start animation when element is 120px from viewport
  });
</script>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({
    duration: 800,
    easing: 'ease-out-cubic',
    once: true
  });
</script>




</body>
</html>
