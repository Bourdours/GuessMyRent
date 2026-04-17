(function () {
  const range = document.getElementById('guessRange');
  const display = document.getElementById('guessValue');
  if (!range || !display) return;

  function formatValue(v) {
    return parseInt(v).toLocaleString('fr-FR');
  }

  function updateSlider() {
    const min = +range.min,
      max = +range.max,
      val = +range.value;
    const pct = ((val - min) / (max - min) * 100).toFixed(1) + '%';
    range.style.setProperty('--pct', pct);
    display.textContent = formatValue(val);
  }

  range.addEventListener('input', updateSlider);
  updateSlider();

  function step(delta) {
    const val = Math.min(+range.max, Math.max(+range.min, +range.value + delta));
    range.value = val;
    updateSlider();
  }

  document.getElementById('guessMinus').addEventListener('click', () => step(-10));
  document.getElementById('guessPlus').addEventListener('click',  () => step(+10));
})();

function switchPhoto(btn, src) {
  document.getElementById('mainPhoto').src = src;
  document.querySelectorAll('.game-thumb').forEach(t => t.classList.remove('active'));
  btn.classList.add('active');
}

function openLightbox(src) {
  document.getElementById('lightboxImg').src = src;
  document.getElementById('lightbox').classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closeLightbox() {
  document.getElementById('lightbox').classList.remove('open');
  document.body.style.overflow = '';
}

document.addEventListener('keydown', e => {
  if (e.key === 'Escape') closeLightbox();
});
