/**
 * WANDERLAND — page-cms.js
 * CMS page slider: pure JS, infinite loop, 3-up → 2-up → 1-up responsive
 * No external library needed
 */

(function () {
  "use strict";

  document.addEventListener("DOMContentLoaded", function () {
    var slider = document.getElementById("wl-cms-slider");
    var track = document.getElementById("wl-cms-slider-track");
    var btnPrev = document.getElementById("wl-cms-prev");
    var btnNext = document.getElementById("wl-cms-next");

    if (!slider || !track || !btnPrev || !btnNext) return;

    var slides = Array.from(track.querySelectorAll(".wl-cms-slide"));
    var total = slides.length;
    if (total < 2) {
      btnPrev.style.display = btnNext.style.display = "none";
      return;
    }

    var gap = 12; // px — matches CSS gap
    var currentIndex = 0; // logical index (0-based)
    var isAnimating = false;
    var autoTimer = null;

    // ── How many slides visible at current viewport ──────────
    function getVisible() {
      var w = window.innerWidth;
      if (w <= 480) return 1;
      if (w <= 767) return 2;
      return 3;
    }

    // ── Slide width based on container & visible count ───────
    function getSlideW() {
      var visible = getVisible();
      var trackW = track.parentElement.clientWidth;
      var totalGap = gap * (visible - 1);
      return (trackW - totalGap) / visible;
    }

    // ── Recalc slide widths on resize ────────────────────────
    function recalcWidths() {
      var sw = getSlideW();
      slides.forEach(function (s) {
        s.style.minWidth = sw + "px";
        s.style.flex = "0 0 " + sw + "px";
      });
      // Also update clones
      Array.from(track.querySelectorAll(".wl-cms-slide-clone")).forEach(
        function (s) {
          s.style.minWidth = sw + "px";
          s.style.flex = "0 0 " + sw + "px";
        },
      );
      goTo(currentIndex, false);
    }

    // ── Build clones for infinite loop ───────────────────────
    // Clone enough slides at front and back
    var cloneCount = Math.min(total, getVisible() + 1);

    function buildClones() {
      // Remove old clones
      Array.from(track.querySelectorAll(".wl-cms-slide-clone")).forEach(
        function (c) {
          c.parentNode.removeChild(c);
        },
      );

      cloneCount = Math.min(total, getVisible() + 1);

      // Prepend clones of last N slides
      for (var i = cloneCount - 1; i >= 0; i--) {
        var clone = slides[total - 1 - (i % total)].cloneNode(true);
        clone.classList.add("wl-cms-slide-clone");
        track.insertBefore(clone, track.firstChild);
      }

      // Append clones of first N slides
      for (var j = 0; j < cloneCount; j++) {
        var clone2 = slides[j % total].cloneNode(true);
        clone2.classList.add("wl-cms-slide-clone");
        track.appendChild(clone2);
      }
    }

    // ── Get offset for index ─────────────────────────────────
    function getOffset(index) {
      var sw = getSlideW();
      return -((cloneCount + index) * (sw + gap));
    }

    // ── Move to index ─────────────────────────────────────────
    function goTo(index, animate) {
      if (animate === undefined) animate = true;
      var offset = getOffset(index);
      track.style.transition = animate
        ? "transform 0.6s cubic-bezier(0.4,0,0.2,1)"
        : "none";
      track.style.transform = "translateX(" + offset + "px)";
    }

    // ── After transition: reset position if on clone ─────────
    track.addEventListener("transitionend", function () {
      isAnimating = false;

      if (currentIndex < 0) {
        currentIndex = total - 1;
        goTo(currentIndex, false);
      } else if (currentIndex >= total) {
        currentIndex = 0;
        goTo(currentIndex, false);
      }
    });

    // ── Navigate ─────────────────────────────────────────────
    function next() {
      if (isAnimating) return;
      isAnimating = true;
      currentIndex++;
      goTo(currentIndex);
      resetAuto();
    }

    function prev() {
      if (isAnimating) return;
      isAnimating = true;
      currentIndex--;
      goTo(currentIndex);
      resetAuto();
    }

    // ── Auto-play ─────────────────────────────────────────────
    function startAuto() {
      autoTimer = setInterval(next, 5000);
    }

    function resetAuto() {
      clearInterval(autoTimer);
      startAuto();
    }

    // ── Touch/swipe support ───────────────────────────────────
    var touchStartX = 0;
    var touchEndX = 0;

    slider.addEventListener(
      "touchstart",
      function (e) {
        touchStartX = e.changedTouches[0].clientX;
      },
      { passive: true },
    );

    slider.addEventListener(
      "touchend",
      function (e) {
        touchEndX = e.changedTouches[0].clientX;
        var diff = touchStartX - touchEndX;
        if (Math.abs(diff) > 40) {
          diff > 0 ? next() : prev();
        }
      },
      { passive: true },
    );

    // ── Keyboard ─────────────────────────────────────────────
    slider.setAttribute("tabindex", "0");
    slider.addEventListener("keydown", function (e) {
      if (e.key === "ArrowLeft") prev();
      if (e.key === "ArrowRight") next();
    });

    // ── Pause on hover ────────────────────────────────────────
    slider.addEventListener("mouseenter", function () {
      clearInterval(autoTimer);
    });
    slider.addEventListener("mouseleave", startAuto);

    // ── Button events ─────────────────────────────────────────
    btnPrev.addEventListener("click", prev);
    btnNext.addEventListener("click", next);

    // ── Resize ───────────────────────────────────────────────
    var resizeTimer;
    window.addEventListener("resize", function () {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function () {
        buildClones();
        recalcWidths();
      }, 150);
    });

    // ── Init ─────────────────────────────────────────────────
    buildClones();
    recalcWidths();
    startAuto();
  }); // end DOMContentLoaded
})();
