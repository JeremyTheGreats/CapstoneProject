(function () {
  if (!window.AOS) return;

  // Mark AOS as available before it applies its hidden pre-animation styles.
  document.documentElement.classList.add("aos-ready");

  window.AOS.init({
    duration: 700,
    easing: "ease-out-cubic",
    offset: 28,
    once: true,
    disable: function () {
      return window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    }
  });

  // Dashboard views switch through URL hashes; recalculate after the target view is shown.
  window.addEventListener("hashchange", function () {
    window.requestAnimationFrame(function () {
      window.AOS.refreshHard();
    });
  });
})();
