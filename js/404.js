/**
 * WANDERLAND — 404.js
 * 404: header transparent float + content entrance animation
 */

(function () {
  "use strict";

  document.addEventListener("DOMContentLoaded", function () {
    // ── 1. Make header transparent + float (same as home/single) ─
    var pageHeader = document.querySelector(".mkdf-page-header");
    var topBar = document.querySelector(".mkdf-top-bar");

    if (pageHeader) {
      pageHeader.classList.add("is-transparent");
    }

    // Disable sticky behavior on 404 — header stays transparent
    // (The global header.js scroll handler will still fire but
    //  404 page body has overflow:hidden so no scroll happens)

    // ── 2. Staggered content entrance ────────────────────────
    var els = [
      ".wl-404-number",
      ".wl-404-tagline",
      ".wl-404-title",
      ".wl-404-search",
      ".wl-404-btns",
    ];

    els.forEach(function (sel, i) {
      var el = document.querySelector(sel);
      if (!el) return;
      el.style.opacity = "0";
      el.style.transform = "translateY(22px)";
      el.style.transition =
        "opacity 0.7s cubic-bezier(0.22,1,0.36,1) " +
        (i * 0.12 + 0.15) +
        "s," +
        "transform 0.7s cubic-bezier(0.22,1,0.36,1) " +
        (i * 0.12 + 0.15) +
        "s";

      // Trigger animation
      requestAnimationFrame(function () {
        requestAnimationFrame(function () {
          el.style.opacity = "1";
          el.style.transform = "translateY(0)";
        });
      });
    });

    // ── 3. Auto-focus search ──────────────────────────────────
    var input = document.querySelector(".wl-404-search-input");
    if (input) {
      setTimeout(function () {
        input.focus();
      }, 800);
    }
  });
})();
