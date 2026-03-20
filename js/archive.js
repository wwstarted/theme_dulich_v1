/**
 * WANDERLAND — archive.js
 * AJAX pagination: fetch page → extract grid + pagination → swap DOM
 */

(function () {
  "use strict";

  var wrap = null; // #wl-ajax-wrap
  var body = null; // .wl-archive-body container
  var isLoading = false;

  // ── Helpers ──────────────────────────────────────────────────

  function buildUrl(page) {
    var base = wrap.dataset.baseUrl;
    var cat = wrap.dataset.cat;
    var sort = wrap.dataset.sort;
    var year = wrap.dataset.year;
    var params = new URLSearchParams();

    if (cat && cat !== "0") params.set("cat", cat);
    if (sort && sort !== "date_desc") params.set("sort", sort);
    if (year && year !== "0") params.set("year", year);
    if (page > 1) params.set("paged", page);

    var qs = params.toString();
    return base + (qs ? "?" + qs : "");
  }

  function setLoading(on) {
    isLoading = on;
    wrap.classList.toggle("is-loading", on);
  }

  function initCards() {
    if (!("IntersectionObserver" in window)) return;
    var cards = wrap.querySelectorAll(".wl-archive-card");
    var obs = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add("wl-card-visible");
            obs.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.06, rootMargin: "0px 0px -20px 0px" },
    );

    cards.forEach(function (card, i) {
      card.style.transitionDelay = (i % 3) * 0.07 + "s";
      card.classList.add("wl-card-hidden");
      obs.observe(card);
    });
  }

  function bindPaginationClicks() {
    wrap
      .querySelectorAll(".wl-page-num[data-page], .wl-page-btn[data-page]")
      .forEach(function (el) {
        el.addEventListener("click", function (e) {
          e.preventDefault();
          if (
            isLoading ||
            el.classList.contains("is-current") ||
            el.classList.contains("is-disabled")
          )
            return;
          loadPage(parseInt(el.dataset.page, 10));
        });
      });
  }

  // ── Core: fetch page HTML, extract inner HTML, swap ──────────

  function loadPage(page) {
    if (isLoading) return;
    setLoading(true);

    var url = buildUrl(page);

    fetch(url, { headers: { "X-Requested-With": "XMLHttpRequest" } })
      .then(function (res) {
        if (!res.ok) throw new Error("Network error " + res.status);
        return res.text();
      })
      .then(function (html) {
        // Parse the fetched HTML
        var parser = new DOMParser();
        var doc = parser.parseFromString(html, "text/html");
        var newWrap = doc.getElementById("wl-ajax-wrap");

        if (!newWrap) throw new Error("wl-ajax-wrap not found in response");

        // Swap innerHTML
        wrap.innerHTML = newWrap.innerHTML;

        // Update data attrs on wrap
        wrap.dataset.currentPage = page;
        wrap.dataset.maxPages = newWrap.dataset.maxPages;

        // Update browser URL without reload
        history.pushState({ page: page }, "", url);

        // Scroll to grid top (minus header)
        var headerH =
          parseInt(
            getComputedStyle(document.documentElement).getPropertyValue(
              "--topbar-height",
            ) || "44",
            10,
          ) +
          parseInt(
            getComputedStyle(document.documentElement).getPropertyValue(
              "--header-height-desktop",
            ) || "90",
            10,
          );
        var bodyEl = document.querySelector(".wl-archive-body");
        if (bodyEl) {
          var top =
            bodyEl.getBoundingClientRect().top + window.scrollY - headerH - 16;
          window.scrollTo({ top: Math.max(0, top), behavior: "smooth" });
        }

        // Re-init cards + pagination events
        initCards();
        bindPaginationClicks();
        setLoading(false);
      })
      .catch(function (err) {
        console.error("[wl-archive] AJAX error:", err);
        setLoading(false);
        // Fallback: hard navigate
        window.location.href = url;
      });
  }

  // ── popstate: handle browser back/forward ────────────────────

  window.addEventListener("popstate", function (e) {
    var page = e.state && e.state.page ? e.state.page : 1;
    loadPage(page);
  });

  // ── Active select highlight ───────────────────────────────────

  function initSelects() {
    document.querySelectorAll(".wl-filter-select").forEach(function (sel) {
      var first = sel.options[0] ? sel.options[0].value : "";
      function check() {
        sel
          .closest(".wl-filter-select-wrap")
          .classList.toggle("wl-select-active", sel.value !== first);
      }
      check();
      sel.addEventListener("change", check);
    });
  }

  // ── Mobile cats fade mask ─────────────────────────────────────

  function initCatsFade() {
    var cats = document.querySelector(".wl-filter-cats");
    if (!cats) return;
    function checkFade() {
      var atEnd = cats.scrollLeft + cats.clientWidth >= cats.scrollWidth - 4;
      var mask =
        !atEnd && cats.scrollWidth > cats.clientWidth
          ? "linear-gradient(to right, #000 80%, transparent 100%)"
          : "";
      cats.style.webkitMaskImage = mask;
      cats.style.maskImage = mask;
    }
    cats.addEventListener("scroll", checkFade, { passive: true });
    window.addEventListener("resize", checkFade);
    checkFade();
  }

  // ── Init ─────────────────────────────────────────────────────

  document.addEventListener("DOMContentLoaded", function () {
    wrap = document.getElementById("wl-ajax-wrap");
    body = document.querySelector(".wl-archive-body");
    if (!wrap) return;

    // Replace PHP pagination links with data-page click handlers
    // (PHP renders links with href; we intercept via data-page attr injection)
    injectDataPageAttrs();
    bindPaginationClicks();
    initCards();
    initSelects();
    initCatsFade();

    // Save initial state
    history.replaceState(
      { page: parseInt(wrap.dataset.currentPage || "1", 10) },
      "",
    );
  });

  // ── Inject data-page on PHP-rendered pagination ───────────────

  function injectDataPageAttrs() {
    // Page number links
    wrap.querySelectorAll(".wl-page-num").forEach(function (el) {
      var text = el.textContent.trim();
      var num = parseInt(text, 10);
      if (!isNaN(num)) el.dataset.page = num;
    });

    // Prev button
    var prev = wrap.querySelector(".wl-page-prev:not(.is-disabled)");
    if (prev) {
      var cur = parseInt(wrap.dataset.currentPage || "1", 10);
      prev.dataset.page = Math.max(1, cur - 1);
    }

    // Next button
    var next = wrap.querySelector(".wl-page-next:not(.is-disabled)");
    if (next) {
      var cur2 = parseInt(wrap.dataset.currentPage || "1", 10);
      var max = parseInt(wrap.dataset.maxPages || "1", 10);
      next.dataset.page = Math.min(max, cur2 + 1);
    }
  }
})();
