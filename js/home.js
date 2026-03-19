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

// ═══════════════════════════════════════════════════════════════
//  SECTION 4 — FEATURED BLOG POSTS SLIDER
//  3 visible desktop / 2 tablet / 1 mobile
//  Step 1 post per click, arrows disable at edges
// ═══════════════════════════════════════════════════════════════

(function () {
  "use strict";

  document.addEventListener("DOMContentLoaded", function () {
    const sliderWrap = document.querySelector(".wl-featured-slider-wrap");
    if (!sliderWrap) return;

    const track = sliderWrap.querySelector(".wl-featured-track");
    const cards = Array.from(track.querySelectorAll(".wl-featured-card"));
    const btnPrev = sliderWrap.querySelector(".wl-featured-arrow--prev");
    const btnNext = sliderWrap.querySelector(".wl-featured-arrow--next");
    const viewport = sliderWrap.querySelector(".wl-featured-viewport");
    const GAP = 22; // px — matches CSS gap
    const total = cards.length;

    let current = 0;

    // ── Get visible count based on breakpoint ────────────────
    function getVisible() {
      const w = window.innerWidth;
      if (w <= 767) return 1;
      if (w <= 1199) return 2;
      return 3;
    }

    // ── Calculate card width & set on each card ───────────────
    function getCardWidth() {
      const visible = getVisible();
      const totalGaps = GAP * (visible - 1);
      return (viewport.offsetWidth - totalGaps) / visible;
    }

    function setCardWidths() {
      const w = getCardWidth();
      cards.forEach(function (card) {
        card.style.width = w + "px";
      });
    }

    // ── Move track to position ────────────────────────────────
    function moveTo(index) {
      const visible = getVisible();
      const cardWidth = getCardWidth();
      const maxIndex = Math.max(0, total - visible);

      current = Math.max(0, Math.min(index, maxIndex));

      const offset = current * (cardWidth + GAP);
      track.style.transform = "translateX(-" + offset + "px)";

      // Update arrow states
      btnPrev.disabled = current === 0;
      btnNext.disabled = current >= maxIndex;
    }

    // ── Arrow clicks ─────────────────────────────────────────
    btnPrev.addEventListener("click", function () {
      moveTo(current - 1);
    });

    btnNext.addEventListener("click", function () {
      moveTo(current + 1);
    });

    // ── Touch/swipe ──────────────────────────────────────────
    var touchStartX = 0;
    var touchStartY = 0;
    var isSwiping = false;

    viewport.addEventListener(
      "touchstart",
      function (e) {
        touchStartX = e.touches[0].clientX;
        touchStartY = e.touches[0].clientY;
        isSwiping = true;
      },
      { passive: true },
    );

    viewport.addEventListener(
      "touchmove",
      function (e) {
        if (!isSwiping) return;
        var dy = Math.abs(e.touches[0].clientY - touchStartY);
        var dx = Math.abs(e.touches[0].clientX - touchStartX);
        if (dy > dx) isSwiping = false;
      },
      { passive: true },
    );

    viewport.addEventListener(
      "touchend",
      function (e) {
        if (!isSwiping) return;
        isSwiping = false;
        var dx = e.changedTouches[0].clientX - touchStartX;
        if (Math.abs(dx) > 48) {
          dx < 0 ? moveTo(current + 1) : moveTo(current - 1);
        }
      },
      { passive: true },
    );

    // ── Keyboard ─────────────────────────────────────────────
    sliderWrap.setAttribute("tabindex", "0");
    sliderWrap.addEventListener("keydown", function (e) {
      if (e.key === "ArrowLeft") {
        e.preventDefault();
        moveTo(current - 1);
      }
      if (e.key === "ArrowRight") {
        e.preventDefault();
        moveTo(current + 1);
      }
    });

    // ── Resize: recalculate ───────────────────────────────────
    var resizeTimer;
    window.addEventListener("resize", function () {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function () {
        setCardWidths();
        moveTo(current); // clamp + re-translate
      }, 120);
    });

    // ── Init ─────────────────────────────────────────────────
    setCardWidths();
    moveTo(0);
  });
})();

// ═══════════════════════════════════════════════════════════════
//  SECTION 5 — DESTINATIONS TIMELINE  (v4)
//  - Fixed height (no vertical overflow)
//  - Horizontal scroll if items > viewport
//  - Items visible fix + line extends past endpoints
// ═══════════════════════════════════════════════════════════════

(function () {
  "use strict";

  document.addEventListener("DOMContentLoaded", function () {
    var scrollWrap = document.querySelector(".wl-dest-scroll-wrap");
    var track = document.querySelector(".wl-dest-track");
    var lineSvg = document.querySelector(".wl-dest-line-svg");
    var pathEl = document.querySelector(".wl-dest-path");
    var pinsGroup = document.querySelector(".wl-dest-pins");
    var items = Array.from(document.querySelectorAll(".wl-dest-item"));

    if (!scrollWrap || !track || items.length === 0) return;

    var TRACK_H = 320;
    var ABOVE_Y = 110;
    var BELOW_Y = 210;
    var ITEM_W = 190;
    var ITEM_H = 85;
    var STEP_MIN = 220;
    var LINE_EXT = 100;

    function isMobile() {
      return window.innerWidth <= 767;
    }

    function layoutItems() {
      if (isMobile()) {
        resetMobile();
        return;
      }

      var total = items.length;
      var viewW = scrollWrap.offsetWidth || window.innerWidth;
      var minW = LINE_EXT * 2 + STEP_MIN * (total - 1) + ITEM_W;
      var trackW = Math.max(viewW, minW);
      var spanW = trackW - LINE_EXT * 2;
      var step = total > 1 ? spanW / (total - 1) : 0;

      track.style.position = "relative";
      track.style.display = "block";
      track.style.width = trackW + "px";
      track.style.height = TRACK_H + "px";

      if (lineSvg) {
        lineSvg.style.display = "";
        lineSvg.setAttribute("viewBox", "0 0 " + trackW + " " + TRACK_H);
        lineSvg.style.width = trackW + "px";
        lineSvg.style.height = TRACK_H + "px";
      }

      var pinPoints = [];

      items.forEach(function (item, i) {
        var isAbove = item.classList.contains("wl-dest-item--above");
        var pinX = LINE_EXT + i * step;
        var pinY = isAbove ? ABOVE_Y : BELOW_Y;
        var itemLeft = pinX - ITEM_W / 2;
        // Flush item edge right against pinY — no gap, SVG circle sits on the line
        var itemTop = isAbove ? pinY - ITEM_H : pinY;

        item.style.position = "absolute";
        item.style.left = itemLeft + "px";
        item.style.top = itemTop + "px";
        item.style.width = ITEM_W + "px";
        item.style.transform = "none";
        item.style.margin = "0";
        item.style.opacity = "0";
        item.classList.remove("is-visible");

        pinPoints.push({ x: pinX, y: pinY });
      });

      setTimeout(function () {
        drawPath(trackW, pinPoints);
      }, 30);

      // Stagger animate-in — use inline opacity directly to avoid class timing issues
      items.forEach(function (item, i) {
        (function (el, delay) {
          setTimeout(function () {
            el.style.opacity = "1";
            el.classList.add("is-visible");
          }, delay);
        })(item, 80 + i * 140);
      });
    }

    function drawPath(trackW, pinPoints) {
      if (!pathEl || !pinsGroup || pinPoints.length === 0) return;
      var first = pinPoints[0];
      var last = pinPoints[pinPoints.length - 1];
      var all = [{ x: first.x - LINE_EXT, y: first.y }]
        .concat(pinPoints)
        .concat([{ x: last.x + LINE_EXT, y: last.y }]);
      var d = "M " + all[0].x + " " + all[0].y;
      for (var i = 1; i < all.length; i++) {
        var p0 = all[i - 1],
          p1 = all[i],
          cx = (p0.x + p1.x) / 2;
        d +=
          " C " +
          cx +
          " " +
          p0.y +
          " " +
          cx +
          " " +
          p1.y +
          " " +
          p1.x +
          " " +
          p1.y;
      }
      pathEl.setAttribute("d", d);
      pinsGroup.innerHTML = "";
      pinPoints.forEach(function (p) {
        var c = document.createElementNS(
          "http://www.w3.org/2000/svg",
          "circle",
        );
        c.setAttribute("cx", p.x);
        c.setAttribute("cy", p.y);
        c.setAttribute("r", "5");
        c.setAttribute("fill", "#8a9e8b");
        c.setAttribute("stroke", "#f4f1eb");
        c.setAttribute("stroke-width", "2");
        pinsGroup.appendChild(c);
      });
    }

    function resetMobile() {
      items.forEach(function (item) {
        item.style.cssText = "";
        item.classList.add("is-visible");
      });
      track.style.cssText = "";
      if (lineSvg) lineSvg.style.display = "none";
    }

    // Drag to scroll
    var isDragging = false,
      startX = 0,
      scrollL = 0,
      moved = false;
    scrollWrap.addEventListener("mousedown", function (e) {
      isDragging = true;
      moved = false;
      startX = e.pageX - scrollWrap.offsetLeft;
      scrollL = scrollWrap.scrollLeft;
      scrollWrap.style.cursor = "grabbing";
    });
    document.addEventListener("mouseup", function () {
      isDragging = false;
      scrollWrap.style.cursor = "";
    });
    scrollWrap.addEventListener("mousemove", function (e) {
      if (!isDragging) return;
      e.preventDefault();
      var walk = (e.pageX - scrollWrap.offsetLeft - startX) * 1.3;
      if (Math.abs(walk) > 3) moved = true;
      scrollWrap.scrollLeft = scrollL - walk;
    });
    scrollWrap.addEventListener(
      "click",
      function (e) {
        if (moved) {
          e.preventDefault();
          e.stopPropagation();
        }
      },
      true,
    );

    var resizeTimer;
    window.addEventListener("resize", function () {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(layoutItems, 150);
    });

    layoutItems();
  });
})();
