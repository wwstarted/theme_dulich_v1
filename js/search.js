/**
 * WANDERLAND — search.js
 * Search results: card entrance + AJAX pagination
 */

(function () {
  "use strict";

  var wrap = null;
  var isLoading = false;

  // ── Build URL for a given page ────────────────────────────
  function buildSearchUrl(page) {
    var keyword = wrap.dataset.keyword || "";
    var sort = wrap.dataset.sort || "relevance";
    var params = new URLSearchParams();

    params.set("s", keyword);
    if (sort && sort !== "relevance") params.set("sort", sort);
    if (page > 1) params.set("paged", page);

    return window.location.pathname + "?" + params.toString();
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

  function loadPage(page) {
    if (isLoading) return;
    setLoading(true);

    var url = buildSearchUrl(page);

    fetch(url, { headers: { "X-Requested-With": "XMLHttpRequest" } })
      .then(function (res) {
        if (!res.ok) throw new Error("HTTP " + res.status);
        return res.text();
      })
      .then(function (html) {
        var doc = new DOMParser().parseFromString(html, "text/html");
        var newWrap = doc.getElementById("wl-search-ajax-wrap");
        if (!newWrap) throw new Error("wl-search-ajax-wrap not found");

        wrap.innerHTML = newWrap.innerHTML;
        wrap.dataset.currentPage = page;
        wrap.dataset.maxPages = newWrap.dataset.maxPages;

        history.pushState({ page: page }, "", url);

        // Scroll to top of results
        var body = document.querySelector(".wl-archive-body");
        if (body) {
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
          window.scrollTo({
            top:
              body.getBoundingClientRect().top + window.scrollY - headerH - 16,
            behavior: "smooth",
          });
        }

        initCards();
        bindPaginationClicks();
        setLoading(false);
      })
      .catch(function (err) {
        console.error("[wl-search] AJAX error:", err);
        setLoading(false);
        window.location.href = url;
      });
  }

  window.addEventListener("popstate", function (e) {
    loadPage(e.state && e.state.page ? e.state.page : 1);
  });

  // ── Autocomplete / suggestions (debounced) ────────────────
  function initSearchSuggest() {
    var input = document.querySelector(".wl-search-input");
    if (!input) return;

    // Focus the input & select text for quick re-search
    input.addEventListener("focus", function () {
      input.select();
    });

    // Submit on Enter already handled by form, just UX polish
    input.addEventListener("keydown", function (e) {
      if (e.key === "Escape") {
        input.blur();
      }
    });
  }

  // ── Init ────────────────────────────────────────────────────
  document.addEventListener("DOMContentLoaded", function () {
    wrap = document.getElementById("wl-search-ajax-wrap");
    if (!wrap) return;

    history.replaceState(
      { page: parseInt(wrap.dataset.currentPage || "1", 10) },
      "",
    );
    initCards();
    bindPaginationClicks();
    initSearchSuggest();
  });
})();
