/**
 * WANDERLAND — home.js  (v3)
 * Hero Slider — centered layout, slide-up content animation
 *
 * Thay đổi so với v2:
 *  - Content animation dùng CSS @keyframes class thay vì inline transition
 *  - Mỗi lần chuyển slide: exit anim → reset → enter anim
 *  - Slide-up fires mỗi lần, không chỉ lần đầu
 */

(function () {
  "use strict";

  document.addEventListener("DOMContentLoaded", function () {
    const slider = document.querySelector(".wl-hero-slider");
    if (!slider) return;

    const slides = Array.from(slider.querySelectorAll(".wl-hero-slide"));
    const dots = Array.from(slider.querySelectorAll(".wl-hero-dot"));
    const btnPrev = slider.querySelector(".wl-hero-arrow--prev");
    const btnNext = slider.querySelector(".wl-hero-arrow--next");
    const counterCur = slider.querySelector(".wl-hero-counter-current");
    const progressBar = slider.querySelector(".wl-hero-progress-bar");

    const TOTAL = slides.length;
    const AUTOPLAY_MS = 6000;

    // Hide all nav if single slide
    if (TOTAL <= 1) {
      [
        btnPrev,
        btnNext,
        slider.querySelector(".wl-hero-dots"),
        slider.querySelector(".wl-hero-progress"),
        slider.querySelector(".wl-hero-counter"),
      ].forEach(function (el) {
        if (el) el.style.display = "none";
      });

      // Still show content on the single slide
      const singleContent =
        slides[0] && slides[0].querySelector(".wl-hero-content");
      if (singleContent) triggerContentEnter(singleContent);
      return;
    }

    // ── State ────────────────────────────────────────────────
    let current = 0;
    let isAnimating = false;
    let autoTimer = null;
    const prefersReducedMotion = window.matchMedia(
      "(prefers-reduced-motion: reduce)",
    ).matches;

    // ── Content animation helpers ────────────────────────────

    /**
     * Play slide-up enter animation on a content element.
     * Removes any previous animation classes first to allow replay.
     */
    function triggerContentEnter(contentEl) {
      if (!contentEl) return;

      if (prefersReducedMotion) {
        contentEl.style.opacity = "1";
        contentEl.style.transform = "none";
        return;
      }

      // 1. Remove both classes + reset inline styles to base hidden state
      contentEl.classList.remove("is-content-visible", "is-content-exit");
      contentEl.style.opacity = "0";
      contentEl.style.transform = "translateY(44px)";

      // 2. Force reflow so browser sees the reset before adding class
      void contentEl.offsetWidth;

      // 3. Clear inline so animation takes over
      contentEl.style.opacity = "";
      contentEl.style.transform = "";

      // 4. Delay matches slide fade-in start (~400ms offset)
      setTimeout(function () {
        contentEl.classList.add("is-content-visible");
      }, 380);
    }

    /**
     * Play exit animation on content before slide leaves.
     * Resolves when animation ends (or after timeout fallback).
     */
    function triggerContentExit(contentEl, callback) {
      if (!contentEl || prefersReducedMotion) {
        if (callback) callback();
        return;
      }

      contentEl.classList.remove("is-content-visible");
      contentEl.classList.add("is-content-exit");

      // Exit anim is 0.32s — callback after 320ms
      setTimeout(function () {
        contentEl.classList.remove("is-content-exit");
        if (callback) callback();
      }, 340);
    }

    // ── Core slide change ────────────────────────────────────

    function activateSlide(next) {
      if (isAnimating || next === current) return;
      isAnimating = true;

      const prev = current;
      current = next;

      const prevSlide = slides[prev];
      const nextSlide = slides[next];
      const prevContent =
        prevSlide && prevSlide.querySelector(".wl-hero-content");
      const nextContent =
        nextSlide && nextSlide.querySelector(".wl-hero-content");

      // 1. Exit animation on current content
      triggerContentExit(prevContent, function () {
        // 2. Slide transition
        prevSlide.classList.add("is-leaving");
        prevSlide.setAttribute("aria-hidden", "true");
        tabIndexUpdate(prevSlide, "-1");

        nextSlide.classList.add("is-active");
        nextSlide.setAttribute("aria-hidden", "false");
        tabIndexUpdate(nextSlide, "0");

        // 3. Update dots
        dots.forEach(function (dot, i) {
          const active = i === next;
          dot.classList.toggle("is-active", active);
          dot.setAttribute("aria-selected", active ? "true" : "false");
        });

        // 4. Counter
        if (counterCur) {
          counterCur.textContent = pad(next + 1);
        }

        // 5. Enter animation on new content
        triggerContentEnter(nextContent);

        // 6. Cleanup leaving slide after CSS transition (0.9s)
        const cleanupTimer = setTimeout(function () {
          prevSlide.classList.remove("is-active", "is-leaving");
          isAnimating = false;
        }, 950);

        prevSlide.addEventListener("transitionend", function handler(e) {
          if (e.propertyName !== "opacity") return;
          clearTimeout(cleanupTimer);
          prevSlide.classList.remove("is-active", "is-leaving");
          isAnimating = false;
          prevSlide.removeEventListener("transitionend", handler);
        });
      });
    }

    function goNext() {
      activateSlide((current + 1) % TOTAL);
    }
    function goPrev() {
      activateSlide((current - 1 + TOTAL) % TOTAL);
    }
    function goTo(i) {
      activateSlide(i);
    }

    function tabIndexUpdate(slide, value) {
      slide.querySelectorAll("a, button").forEach(function (el) {
        el.setAttribute("tabindex", value);
      });
    }

    // ── Progress bar ─────────────────────────────────────────

    function resetProgress() {
      if (!progressBar) return;
      progressBar.style.transition = "none";
      progressBar.style.width = "0%";
      progressBar.classList.remove("is-running");
      void progressBar.offsetWidth;
    }

    function startProgress() {
      if (!progressBar) return;
      resetProgress();
      requestAnimationFrame(function () {
        requestAnimationFrame(function () {
          progressBar.style.transition = "width " + AUTOPLAY_MS + "ms linear";
          progressBar.style.width = "100%";
          progressBar.classList.add("is-running");
        });
      });
    }

    // ── Autoplay ─────────────────────────────────────────────

    function startAutoplay() {
      stopAutoplay();
      if (!prefersReducedMotion) startProgress();
      autoTimer = setInterval(function () {
        goNext();
        if (!prefersReducedMotion) startProgress();
      }, AUTOPLAY_MS);
    }

    function stopAutoplay() {
      clearInterval(autoTimer);
      autoTimer = null;
      resetProgress();
    }

    // ── Buttons ───────────────────────────────────────────────

    if (btnPrev) {
      btnPrev.addEventListener("click", function () {
        goPrev();
        startAutoplay();
      });
    }

    if (btnNext) {
      btnNext.addEventListener("click", function () {
        goNext();
        startAutoplay();
      });
    }

    // ── Dots ─────────────────────────────────────────────────

    dots.forEach(function (dot) {
      dot.addEventListener("click", function () {
        const idx = parseInt(dot.dataset.index, 10);
        if (!isNaN(idx) && idx !== current) {
          goTo(idx);
          startAutoplay();
        }
      });
    });

    // ── Keyboard ──────────────────────────────────────────────

    slider.setAttribute("tabindex", "0");
    slider.addEventListener("keydown", function (e) {
      if (e.key === "ArrowLeft") {
        e.preventDefault();
        goPrev();
        startAutoplay();
      }
      if (e.key === "ArrowRight") {
        e.preventDefault();
        goNext();
        startAutoplay();
      }
    });

    // ── Pause on hover / focus ────────────────────────────────

    slider.addEventListener("mouseenter", stopAutoplay);
    slider.addEventListener("mouseleave", startAutoplay);
    slider.addEventListener("focusin", stopAutoplay);
    slider.addEventListener("focusout", function (e) {
      if (!slider.contains(e.relatedTarget)) startAutoplay();
    });

    // ── Pause on hidden tab ───────────────────────────────────

    document.addEventListener("visibilitychange", function () {
      document.hidden ? stopAutoplay() : startAutoplay();
    });

    // ── Touch / Swipe ─────────────────────────────────────────

    (function () {
      let startX = 0,
        startY = 0,
        dragging = false;
      const THRESHOLD = 50;

      slider.addEventListener(
        "touchstart",
        function (e) {
          startX = e.touches[0].clientX;
          startY = e.touches[0].clientY;
          dragging = true;
        },
        { passive: true },
      );

      slider.addEventListener(
        "touchmove",
        function (e) {
          if (!dragging) return;
          if (
            Math.abs(e.touches[0].clientY - startY) >
            Math.abs(e.touches[0].clientX - startX)
          ) {
            dragging = false;
          }
        },
        { passive: true },
      );

      slider.addEventListener(
        "touchend",
        function (e) {
          if (!dragging) return;
          dragging = false;
          const dx = e.changedTouches[0].clientX - startX;
          const dy = e.changedTouches[0].clientY - startY;
          if (Math.abs(dx) > THRESHOLD && Math.abs(dx) > Math.abs(dy)) {
            dx < 0 ? goNext() : goPrev();
            startAutoplay();
          }
        },
        { passive: true },
      );
    })();

    // ── Utility ───────────────────────────────────────────────

    function pad(n) {
      return String(n).padStart(2, "0");
    }

    // ── Init ──────────────────────────────────────────────────

    // Make sure first slide + content visible on load
    slides[0].classList.add("is-active");
    slides[0].setAttribute("aria-hidden", "false");

    // Disable links on non-active slides
    slides.forEach(function (slide, i) {
      if (i !== 0) tabIndexUpdate(slide, "-1");
    });

    // Fire enter animation on first slide
    const firstContent = slides[0].querySelector(".wl-hero-content");
    triggerContentEnter(firstContent);

    if (!prefersReducedMotion) startAutoplay();
  }); // end DOMContentLoaded
})();
