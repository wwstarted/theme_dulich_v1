/**
 * WANDERLAND — Footer JS
 * Back-to-top button: show/hide on scroll + smooth scroll to top
 */

(function () {
  "use strict";

  const backToTop = document.getElementById("mkdf-back-to-top");
  if (!backToTop) return;

  const SHOW_AFTER = 400; // px scrolled before button appears

  // ── Show / hide on scroll ────────────────────────────────
  let ticking = false;

  function updateBackToTop() {
    ticking = false;
    if (window.scrollY > SHOW_AFTER) {
      backToTop.classList.add("on");
    } else {
      backToTop.classList.remove("on");
    }
  }

  window.addEventListener(
    "scroll",
    function () {
      if (!ticking) {
        requestAnimationFrame(updateBackToTop);
        ticking = true;
      }
    },
    { passive: true },
  );

  // Run once on load
  updateBackToTop();

  // ── Smooth scroll to top ─────────────────────────────────
  backToTop.addEventListener("click", function (e) {
    e.preventDefault();
    window.scrollTo({
      top: 0,
      behavior: "smooth",
    });
  });
})();
