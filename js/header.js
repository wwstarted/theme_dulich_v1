/**
 * WANDERLAND — Header JS
 * Fix #4: Wrap DOMContentLoaded để tránh offsetHeight = 0
 * Fix #5: iOS Safari scroll lock
 */

(function () {
  "use strict";

  document.addEventListener("DOMContentLoaded", function () {
    // ← FIX #4

    const topBar = document.querySelector(".mkdf-top-bar");
    const pageHeader = document.querySelector(".mkdf-page-header");
    const mobileHeader = document.querySelector(".mkdf-mobile-header");
    const isHomePage = document.body.classList.contains("home");
    const isSinglePost = document.body.classList.contains("single-post");
    const isCmsPage = document.body.classList.contains(
      "page-template-page-cms",
    );
    const isContactPage = document.body.classList.contains(
      "page-template-page-contact",
    );
    const is404Page = document.body.classList.contains("error404");
    const hasHeroBanner =
      isHomePage || isSinglePost || isCmsPage || isContactPage || is404Page;

    // ← FIX #4: đọc offsetHeight sau DOMContentLoaded
    const TOPBAR_HEIGHT = topBar ? topBar.offsetHeight : 44;
    const STICKY_OFFSET = 80;

    // ── Initial state on Home ──────────────────────────────
    // if (isHomePage && pageHeader) {
    //   pageHeader.classList.add("is-transparent");
    // }

    if (hasHeroBanner && pageHeader) {
      pageHeader.classList.add("is-transparent");
    }

    let lastScrollY = 0;
    let ticking = false;

    function onScroll() {
      lastScrollY = window.scrollY;
      if (!ticking) {
        requestAnimationFrame(updateHeader);
        ticking = true;
      }
    }

    function updateHeader() {
      ticking = false;
      const scrollY = lastScrollY;

      // 1. Top bar: ẩn sau khi scroll qua nó
      if (topBar) {
        if (scrollY > TOPBAR_HEIGHT) {
          topBar.classList.add("is-hidden");
          if (pageHeader) pageHeader.classList.add("topbar-hidden");
        } else {
          topBar.classList.remove("is-hidden");
          if (pageHeader) pageHeader.classList.remove("topbar-hidden");
        }
      }

      // 2. Main header: sticky + mất transparent
      if (pageHeader) {
        if (scrollY > STICKY_OFFSET) {
          pageHeader.classList.add("is-sticky");
          pageHeader.classList.remove("is-transparent");
        } else {
          pageHeader.classList.remove("is-sticky");
          if (hasHeroBanner) pageHeader.classList.add("is-transparent");
        }
      }
    }

    window.addEventListener("scroll", onScroll, { passive: true });
    updateHeader(); // chạy 1 lần khi load

    // ── Search toggle ──────────────────────────────────────
    const searchHolder = document.querySelector(".mkdf-search-opener-holder");
    const searchBtn = document.querySelector(".mkdf-search-opener");

    if (searchBtn && searchHolder) {
      searchBtn.addEventListener("click", function (e) {
        e.preventDefault();
        searchHolder.classList.toggle("is-open");
        if (searchHolder.classList.contains("is-open")) {
          const field = searchHolder.querySelector(".mkdf-search-field");
          if (field) field.focus();
        }
      });

      document.addEventListener("click", function (e) {
        if (!searchHolder.contains(e.target)) {
          searchHolder.classList.remove("is-open");
        }
      });
    }

    // ── Mobile menu toggle ─────────────────────────────────
    const mobileOpener = document.querySelector(".mkdf-mobile-menu-opener a");
    let savedScrollY = 0;

    if (mobileOpener && mobileHeader) {
      mobileOpener.addEventListener("click", function (e) {
        e.preventDefault();
        const isOpen = mobileHeader.classList.toggle("is-menu-open");

        // ← FIX #5: iOS Safari scroll lock
        if (isOpen) {
          savedScrollY = window.scrollY;
          document.body.style.position = "fixed";
          document.body.style.top = `-${savedScrollY}px`;
          document.body.style.width = "100%";
          document.body.style.overflowY = "scroll"; // giữ scrollbar width
        } else {
          document.body.style.position = "";
          document.body.style.top = "";
          document.body.style.width = "";
          document.body.style.overflowY = "";
          window.scrollTo(0, savedScrollY);
        }
      });
    }

    // Mobile sub-menu accordion
    const mobileArrows = document.querySelectorAll(
      ".mkdf-mobile-nav .mobile_arrow",
    );

    mobileArrows.forEach(function (arrow) {
      arrow.addEventListener("click", function (e) {
        e.preventDefault();
        const parentLi = arrow.closest("li");
        if (!parentLi) return;

        const isOpen = parentLi.classList.contains("is-open");

        // Đóng siblings cùng cấp
        Array.from(parentLi.parentElement.children).forEach(function (li) {
          li.classList.remove("is-open");
        });

        if (!isOpen) parentLi.classList.add("is-open");
      });
    });

    window.addEventListener("resize", function () {
      if (window.innerWidth > 1199) {
        if (mobileHeader) {
          mobileHeader.classList.remove("is-menu-open");
        }
        document
          .querySelectorAll(".mkdf-mobile-nav li.is-open")
          .forEach(function (li) {
            li.classList.remove("is-open");
          });

        document.body.style.position = "";
        document.body.style.top = "";
        document.body.style.width = "";
        document.body.style.overflowY = "";
      }
    });
  });
})();
