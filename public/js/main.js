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

/* ── Photo upload (max 4, min 1) ────────────────────────────────── */
(function () {
  const input = document.getElementById("photoInput");
  const preview = document.getElementById("photoPreview");
  const zone = document.getElementById("uploadZone");
  if (!input || !preview) return;

  const MAX = 4;
  let files = [];

  input.addEventListener("change", () => {
    Array.from(input.files).forEach((f) => {
      if (files.length < MAX) files.push(f);
    });
    sync();
    render();
  });

  function sync() {
    const dt = new DataTransfer();
    files.forEach((f) => dt.items.add(f));
    input.files = dt.files;
    zone.dataset.count = files.length;
    input.required = files.length === 0;
  }

  function formatSize(bytes) {
    if (bytes < 1024 * 1024) return Math.round(bytes / 1024) + "\u00a0Ko";
    return (bytes / (1024 * 1024)).toFixed(1) + "\u00a0Mo";
  }

  function render() {
    preview.innerHTML = "";
    preview.className = "photo-preview count-" + files.length;
    files.forEach((f, i) => {
      const reader = new FileReader();
      reader.onload = (e) => {
        const item = document.createElement("div");
        item.className = "photo-preview-item";
        const label =
          f.name.length > 24 ? f.name.slice(0, 22) + "\u2026" : f.name;
        const badge =
          i === 0 ? `<span class="photo-badge">Photo principale</span>` : "";
        item.innerHTML = `
          <div class="photo-preview-img">
            <img src="${e.target.result}" alt="${label}">
            ${badge}
            <button type="button" class="photo-remove" aria-label="Supprimer">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
              </svg>
            </button>
          </div>
          <div class="photo-info">
            <span class="photo-name" title="${f.name}">${label}</span>
            <span class="photo-size">${formatSize(f.size)}</span>
          </div>`;
        item.querySelector(".photo-remove").addEventListener("click", () => {
          files.splice(i, 1);
          sync();
          render();
        });
        preview.appendChild(item);
      };
      reader.readAsDataURL(f);
    });
  }
})();

/* ── Contact textarea char counter ──────────────────────── */
(function () {
  const ta = document.getElementById("content");
  const count = document.getElementById("charCount");
  if (!ta || !count) return;

  ta.addEventListener("input", () => {
    count.textContent = ta.value.length;
  });
})();

/* ── Strudel player ──────────────────────── */
initStrudel({
  prebake() {
    return Promise.all([
      samples(
        {
          pipeorgan_loud_pedal: [
            "Aerophones/Edge-blown Aerophones/Pipe Organ/Loud Pedal/Rode_Pedal_C1.wav",
            "Aerophones/Edge-blown Aerophones/Pipe Organ/Loud Pedal/Rode_Pedal_C2.wav",
            "Aerophones/Edge-blown Aerophones/Pipe Organ/Loud Pedal/Rode_Pedal_C3.wav",
            "Aerophones/Edge-blown Aerophones/Pipe Organ/Loud Pedal/Rode_Pedal_A1.wav",
            "Aerophones/Edge-blown Aerophones/Pipe Organ/Loud Pedal/Rode_Pedal_A2.wav",
          ],
          kalimba: [
            "Idiophones/Plucked Idiophones/Kalimba, Kenya/Mbira6_Normal_MainSpirit_B2_k8_vl3_rr2.wav",
            "Idiophones/Plucked Idiophones/Kalimba, Kenya/Mbira6_Normal_MainSpirit_B3_k3_vl3_rr2.wav",
            "Idiophones/Plucked Idiophones/Kalimba, Kenya/Mbira6_Normal_MainSpirit_A4_k1_vl3_rr2.wav",
            "Idiophones/Plucked Idiophones/Kalimba, Kenya/Mbira6_Normal_MainSpirit_B4_k15_vl3_rr2.wav",
          ],
          super64_vib: [
            "Aerophones/Free Aerophones/Harmonica-Hohner-Super64/Sustains/Vib/Hohner-Super64_Vib_C2.wav",
            "Aerophones/Free Aerophones/Harmonica-Hohner-Super64/Sustains/Vib/Hohner-Super64_Vib_E2.wav",
            "Aerophones/Free Aerophones/Harmonica-Hohner-Super64/Sustains/Vib/Hohner-Super64_Vib_G2.wav",
            "Aerophones/Free Aerophones/Harmonica-Hohner-Super64/Sustains/Vib/Hohner-Super64_Vib_C3.wav",
            "Aerophones/Free Aerophones/Harmonica-Hohner-Super64/Sustains/Vib/Hohner-Super64_Vib_E3.wav",
          ],
          cajon: [
            "Idiophones/Struck Idiophones/Cajon/Cajon_hit1_mp_rr1.wav",
            "Idiophones/Struck Idiophones/Cajon/Cajon_hit1_f_rr1.wav",
            "Idiophones/Struck Idiophones/Cajon/Cajon_hit1_fff_rr1.wav",
            "Idiophones/Struck Idiophones/Cajon/Cajon_hit2_mp_rr1.wav",
            "Idiophones/Struck Idiophones/Cajon/Cajon_hit2_f_rr1.wav",
            "Idiophones/Struck Idiophones/Cajon/Cajon_hit3_mp_rr1.wav",
            "Idiophones/Struck Idiophones/Cajon/Cajon_hit3_f_rr1.wav",
          ],
          cowbell: [
            "Idiophones/Struck Idiophones/Cowbells/Cowbell1_Hit_v2_rr1_Mid.wav",
            "Idiophones/Struck Idiophones/Cowbells/Cowbell1_Hit_v3_rr1_Mid.wav",
            "Idiophones/Struck Idiophones/Cowbells/Cowbell1_Hit_v4_rr1_Mid.wav",
            "Idiophones/Struck Idiophones/Cowbells/Cowbell1_Normal_v2_rr1_Mid.wav",
            "Idiophones/Struck Idiophones/Cowbells/Cowbell2_Normal_v2_rr1_Mid.wav",
          ],
          clave: [
            "Idiophones/Struck Idiophones/Claves/Claves1_Hit_v1_rr1_Mid.wav",
            "Idiophones/Struck Idiophones/Claves/Claves1_Hit_v2_rr1_Mid.wav",
            "Idiophones/Struck Idiophones/Claves/Claves1_Hit_v3_rr1_Mid.wav",
            "Idiophones/Struck Idiophones/Claves/Claves2_Hit_v1_rr1_Mid.wav",
            "Idiophones/Struck Idiophones/Claves/Claves2_Hit_v2_rr1_Mid.wav",
          ],
        },
        "https://raw.githubusercontent.com/sgossner/VCSL/master/",
      ),
      samples("github:tidalcycles/Dirt-Samples"),
    ]);
  },
});
const play = document.getElementById("play");
play.addEventListener("click", () =>
  stack(
    note("d4 d4 d5 a4 ~ ab4 g4 f4 d4 f4 g4")
      .sound("pipeorgan_loud_pedal").gain(0.1).cutoff(2000).release(0.08),

    note("<[bb3,d4,f4] [c4,e4,g4] [d4,f4,a4] [b3,d4,fs4]>")
      .sound("kalimba").gain(0.25).cutoff(900).release(0.5),

    note("<bb1 c2 d2 b1>")
      .sound("super64_vib").gain(0.5).cutoff(500).release(0.2),

    s("clave ~ cajon ~").n(irand(7)).gain(1),

    s("~ cowbell ~ clave").n(irand(5)).gain(0.9),
  )
  .cpm(50)
  .play(),
);
document.getElementById("stop").addEventListener("click", () => hush());
