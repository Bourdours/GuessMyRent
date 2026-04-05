/* ============================================================
   GuessMyRent — main.js
   Burger menu
   ============================================================ */

/* ── Burger menu ─────────────────────────────────────────── */
(function () {
  const burger = document.getElementById("burgerBtn");
  const drawer = document.getElementById("navDrawer");
  if (!burger || !drawer) return;

  burger.addEventListener("click", () => {
    const isOpen = drawer.classList.toggle("open");
    burger.classList.toggle("open", isOpen);
    burger.setAttribute("aria-expanded", String(isOpen));
    document.body.style.overflow = isOpen ? "hidden" : "";
  });

  // Close on outside click
  document.addEventListener("click", (e) => {
    if (!drawer.contains(e.target) && !burger.contains(e.target)) {
      drawer.classList.remove("open");
      burger.classList.remove("open");
      burger.setAttribute("aria-expanded", "false");
      document.body.style.overflow = "";
    }
  });

  // Close on nav link click
  drawer.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", () => {
      drawer.classList.remove("open");
      burger.classList.remove("open");
      burger.setAttribute("aria-expanded", "false");
      document.body.style.overflow = "";
    });
  });
})();

/* ── Password strength indicator ────────────────────────── */
(function () {
  const pwd = document.getElementById("pwdInput");
  const bar1 = document.getElementById("pwdBar1");
  const bar2 = document.getElementById("pwdBar2");
  const bar3 = document.getElementById("pwdBar3");
  if (!pwd || !bar1) return;

  pwd.addEventListener("input", () => {
    const v = pwd.value;
    const strong =
      v.length >= 10 &&
      /[A-Z]/.test(v) &&
      /[0-9]/.test(v) &&
      /[^a-zA-Z0-9]/.test(v);
    const medium = v.length >= 8 && (/[A-Z]/.test(v) || /[0-9]/.test(v));

    [bar1, bar2, bar3].forEach((b) => {
      b.className = "pwd-bar";
    });

    if (strong) {
      bar1.classList.add("strong");
      bar2.classList.add("strong");
      bar3.classList.add("strong");
    } else if (medium) {
      bar1.classList.add("medium");
      bar2.classList.add("medium");
    } else if (v.length > 0) {
      bar1.classList.add("weak");
    }
  });
})();
