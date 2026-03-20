/**
 * WANDERLAND — single.js
 * Single post: reading progress, lightbox, share popups, scroll animations
 */

(function () {
  "use strict";

  document.addEventListener("DOMContentLoaded", function () {
    // ── 1. Reading Progress Bar ──────────────────────────────
    const progressBar = document.createElement("div");
    progressBar.className = "wl-reading-progress";
    progressBar.setAttribute("aria-hidden", "true");
    document.body.appendChild(progressBar);

    const article = document.querySelector(".wl-single-content");

    function updateProgress() {
      if (!article) return;
      const articleTop = article.getBoundingClientRect().top + window.scrollY;
      const articleHeight = article.offsetHeight;
      const scrolled = window.scrollY - articleTop;
      const progress = Math.min(Math.max(scrolled / articleHeight, 0), 1);
      progressBar.style.width = progress * 100 + "%";
    }

    window.addEventListener("scroll", updateProgress, { passive: true });
    updateProgress();

    // ── 2. Image Lightbox ─────────────────────────────────────
    const contentImgs = document.querySelectorAll(".wl-single-entry img");

    if (contentImgs.length) {
      const overlay = document.createElement("div");
      overlay.className = "wl-lightbox-overlay";
      overlay.setAttribute("role", "dialog");
      overlay.setAttribute("aria-modal", "true");
      overlay.innerHTML =
        '<button class="wl-lightbox-close" aria-label="Close">&times;</button>' +
        '<div class="wl-lightbox-img-wrap">' +
        '<img class="wl-lightbox-img" src="" alt="">' +
        "</div>";
      document.body.appendChild(overlay);

      var lbImg = overlay.querySelector(".wl-lightbox-img");
      var lbClose = overlay.querySelector(".wl-lightbox-close");

      function openLightbox(src, alt) {
        lbImg.src = src;
        lbImg.alt = alt || "";
        overlay.classList.add("is-open");
        document.body.style.overflow = "hidden";
      }

      function closeLightbox() {
        overlay.classList.remove("is-open");
        document.body.style.overflow = "";
        setTimeout(function () {
          lbImg.src = "";
        }, 300);
      }

      contentImgs.forEach(function (img) {
        img.style.cursor = "zoom-in";
        img.addEventListener("click", function () {
          openLightbox(img.src, img.alt);
        });
      });

      lbClose.addEventListener("click", closeLightbox);
      overlay.addEventListener("click", function (e) {
        if (e.target === overlay) closeLightbox();
      });
      document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") closeLightbox();
      });
    }

    // ── 3. Share buttons — open popup ────────────────────────
    var shareBtns = document.querySelectorAll(".wl-share-btn");
    shareBtns.forEach(function (btn) {
      btn.addEventListener("click", function (e) {
        var href = btn.getAttribute("href");
        if (href && href.startsWith("http")) {
          e.preventDefault();
          window.open(href, "_blank", "width=640,height=480,scrollbars=yes");
        }
      });
    });

    // ── 4. Comment form placeholders ─────────────────────────
    var placeholders = {
      "#author": "Name *",
      "#email": "E-mail *",
      "#url": "Website",
      "#comment": "Comment *",
    };
    Object.keys(placeholders).forEach(function (sel) {
      var el = document.querySelector(sel);
      if (el) el.setAttribute("placeholder", placeholders[sel]);
    });

    // ── 5. Scroll fade-in (Intersection Observer) ────────────
    if ("IntersectionObserver" in window) {
      var animEls = document.querySelectorAll(
        ".wl-single-entry p, " +
          ".wl-single-entry blockquote, " +
          ".wl-single-entry h2, " +
          ".wl-single-entry h3, " +
          ".wl-single-entry img, " +
          ".wl-author-box, " +
          ".wl-single-nav",
      );

      var observer = new IntersectionObserver(
        function (entries) {
          entries.forEach(function (entry) {
            if (entry.isIntersecting) {
              entry.target.classList.add("wl-fade-in");
              observer.unobserve(entry.target);
            }
          });
        },
        { threshold: 0.08, rootMargin: "0px 0px -32px 0px" },
      );

      animEls.forEach(function (el) {
        el.classList.add("wl-will-animate");
        observer.observe(el);
      });
    }

    // ── 6. Sticky sidebar top offset ─────────────────────────
    var sidebar = document.querySelector(".wl-single-sidebar");
    if (sidebar && window.innerWidth > 767) {
      var topbarH = parseInt(
        getComputedStyle(document.documentElement).getPropertyValue(
          "--topbar-height",
        ) || "44",
        10,
      );
      var headerH = parseInt(
        getComputedStyle(document.documentElement).getPropertyValue(
          "--header-height-desktop",
        ) || "90",
        10,
      );
      sidebar.style.top = topbarH + headerH + 24 + "px";
    }

    // ── 7. Table of Contents ─────────────────────────────────
    const toc = document.getElementById("wl-toc");
    const tocList = toc ? toc.querySelector(".wl-toc-list") : null;
    const tocToggle = toc ? toc.querySelector(".wl-toc-toggle") : null;
    const tocHeader = toc ? toc.querySelector(".wl-toc-header") : null;
    const entry = document.querySelector(".wl-single-entry");

    if (toc && tocList && entry) {
      const headings = Array.from(entry.querySelectorAll("h2, h3")).filter(
        function (h) {
          return h.textContent.trim().length > 0;
        },
      );

      if (headings.length < 2) {
        // Ít hơn 2 heading — ẩn TOC hoàn toàn
        toc.remove();
      } else {
        // Build TOC list
        headings.forEach(function (heading, i) {
          if (!heading.id) {
            var slug = heading.textContent
              .trim()
              .toLowerCase()
              .replace(/[^a-z0-9\s-]/g, "")
              .replace(/\s+/g, "-")
              .replace(/-+/g, "-")
              .substring(0, 60);
            heading.id = "toc-" + i + "-" + slug;
          }

          var li = document.createElement("li");
          if (heading.tagName === "H3") li.classList.add("wl-toc-h3");

          var a = document.createElement("a");
          a.href = "#" + heading.id;
          a.textContent = heading.textContent.trim();

          a.addEventListener("click", function (e) {
            e.preventDefault();
            var target = document.getElementById(heading.id);
            if (target) {
              var offset =
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
                ) +
                16;
              window.scrollTo({
                top:
                  target.getBoundingClientRect().top + window.scrollY - offset,
                behavior: "smooth",
              });
            }
          });

          li.appendChild(a);
          tocList.appendChild(li);
        });

        // Move TOC: đặt sau paragraph đầu tiên trong entry
        var firstP = entry.querySelector("p");
        if (firstP && firstP.nextSibling) {
          entry.insertBefore(toc, firstP.nextSibling);
        } else {
          entry.insertBefore(toc, entry.firstChild);
        }
        toc.style.display = ""; // bỏ style="display:none"

        // Toggle
        if (tocHeader) {
          tocHeader.addEventListener("click", function () {
            var collapsed = toc.classList.toggle("is-collapsed");
            if (tocToggle)
              tocToggle.setAttribute(
                "aria-expanded",
                collapsed ? "false" : "true",
              );
          });
        }

        // Highlight active on scroll
        var tocLinks = Array.from(tocList.querySelectorAll("a"));

        function updateActiveToc() {
          var offset =
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
            ) +
            24;
          var current = "";
          headings.forEach(function (h) {
            if (window.scrollY >= h.offsetTop - offset - 8) current = h.id;
          });
          tocLinks.forEach(function (link) {
            link.parentElement.classList.toggle(
              "is-active",
              link.getAttribute("href") === "#" + current,
            );
          });
        }

        window.addEventListener("scroll", updateActiveToc, { passive: true });
        updateActiveToc();

        // Auto-collapse trên mobile
        if (window.innerWidth <= 767) {
          toc.classList.add("is-collapsed");
          if (tocToggle) tocToggle.setAttribute("aria-expanded", "false");
        }
      }
    }
  }); // end DOMContentLoaded
})();
