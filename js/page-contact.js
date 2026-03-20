/**
 * WANDERLAND — page-contact.js
 * Contact form: client-side validation + UX enhancements
 */

(function () {
  "use strict";

  document.addEventListener("DOMContentLoaded", function () {
    var form = document.getElementById("wl-contact-form");
    var submit = document.getElementById("wl-cf-submit");
    if (!form || !submit) return;

    // ── Real-time validation ──────────────────────────────────
    var inputs = form.querySelectorAll(".wl-cf-input, .wl-cf-textarea");

    inputs.forEach(function (el) {
      // Validate on blur
      el.addEventListener("blur", function () {
        validateField(el);
      });
      // Clear error on input
      el.addEventListener("input", function () {
        if (el.classList.contains("is-invalid")) {
          validateField(el);
        }
      });
    });

    function validateField(el) {
      var valid = true;

      if (el.required && !el.value.trim()) {
        valid = false;
      } else if (el.type === "email" && el.value.trim()) {
        valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(el.value.trim());
      }

      el.classList.toggle("is-invalid", !valid);
      el.classList.toggle("is-valid", valid && el.value.trim() !== "");
      return valid;
    }

    function validateAll() {
      var allValid = true;
      inputs.forEach(function (el) {
        if (!validateField(el)) allValid = false;
      });
      return allValid;
    }

    // ── Submit: validate + loading state ─────────────────────
    form.addEventListener("submit", function (e) {
      if (!validateAll()) {
        e.preventDefault();
        // Focus first invalid
        var first = form.querySelector(".is-invalid");
        if (first) first.focus();
        return;
      }

      // Show loading state
      submit.classList.add("is-loading");
      submit.setAttribute("disabled", "disabled");
    });

    // ── Textarea: auto-grow ───────────────────────────────────
    var textarea = form.querySelector(".wl-cf-textarea");
    if (textarea) {
      textarea.addEventListener("input", function () {
        textarea.style.height = "auto";
        textarea.style.height = Math.max(160, textarea.scrollHeight) + "px";
      });
    }

    // ── Floating label effect on filled inputs ────────────────
    inputs.forEach(function (el) {
      function checkFilled() {
        el.classList.toggle("is-filled", el.value.trim() !== "");
      }
      el.addEventListener("input", checkFilled);
      checkFilled();
    });
  }); // end DOMContentLoaded
})();
