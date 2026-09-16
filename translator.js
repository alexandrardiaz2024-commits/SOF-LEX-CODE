// ── Google Translate helper ──
// Inserta el widget invisible y expone función cambiarIdioma(langCode)

function googleTranslateElementInit() {
  new google.translate.TranslateElement(
    {
      pageLanguage: 'es',
      includedLanguages: 'es,en,fr,pt,it',
      autoDisplay: false
    },
    'gte-hidden'
  );
}

function cambiarIdioma(lang) {
  // Activa la cookie de traducción de Google directamente
  const select = document.querySelector('.goog-te-combo');
  if (select) {
    select.value = lang;
    select.dispatchEvent(new Event('change'));
  }
  // Guardar preferencia
  localStorage.setItem('sl_lang', lang);
  // Actualizar label del botón
  const labels = {es:'Español',en:'English',fr:'Français',pt:'Português',it:'Italiano'};
  const btn = document.getElementById('lang-btn-label');
  if (btn) btn.textContent = labels[lang] || 'Idioma';
  // Cerrar dropdown
  document.getElementById('lang-dropdown').classList.remove('open');
  // Marcar activo
  document.querySelectorAll('.lang-option').forEach(o => {
    o.classList.toggle('active', o.dataset.lang === lang);
  });
}

// Toggle dropdown
function toggleLangMenu() {
  document.getElementById('lang-dropdown').classList.toggle('open');
}

// Cerrar al click fuera
document.addEventListener('click', function(e) {
  const wrap = document.querySelector('.translator-wrap');
  if (wrap && !wrap.contains(e.target)) {
    const dd = document.getElementById('lang-dropdown');
    if (dd) dd.classList.remove('open');
  }
});

// Restaurar idioma guardado
window.addEventListener('load', function() {
  const saved = localStorage.getItem('sl_lang');
  if (saved && saved !== 'es') {
    setTimeout(() => cambiarIdioma(saved), 1200);
  }
});
