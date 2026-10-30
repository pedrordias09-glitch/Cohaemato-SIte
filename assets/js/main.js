(function () {
  'use strict';

  // Menu móvel
  var btn = document.querySelector('.menu-btn');
  var menu = document.getElementById('menu');
  if (btn && menu) {
    btn.addEventListener('click', function () {
      var aberto = menu.classList.toggle('aberto');
      btn.setAttribute('aria-expanded', aberto ? 'true' : 'false');
    });
  }

  // Animação de entrada
  var rev = document.querySelectorAll('.rev');
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (es) {
      es.forEach(function (e) {
        if (e.isIntersecting) { e.target.classList.add('vis'); io.unobserve(e.target); }
      });
    }, { threshold: 0.12 });
    rev.forEach(function (el) { io.observe(el); });
  } else {
    rev.forEach(function (el) { el.classList.add('vis'); });
  }

  // Equipas: separadores (com link direto #slug)
  var tabs = document.querySelectorAll('.tab[data-equipa]');
  if (tabs.length) {
    var mostrar = function (slug) {
      tabs.forEach(function (t) {
        var on = t.getAttribute('data-equipa') === slug;
        t.classList.toggle('ativo', on); t.setAttribute('aria-selected', on ? 'true' : 'false');
        document.getElementById('painel-' + t.getAttribute('data-equipa')).hidden = !on;
      });
    };
    tabs.forEach(function (t) {
      t.addEventListener('click', function () { var s = t.getAttribute('data-equipa'); mostrar(s); history.replaceState(null, '', '#' + s); });
    });
    var h = location.hash.slice(1);
    if (h && document.getElementById('painel-' + h)) mostrar(h);
  }

  // Jogos: separadores por equipa
  var tj = document.querySelectorAll('.tab[data-jogos]');
  if (tj.length) {
    tj.forEach(function (t) {
      t.addEventListener('click', function () {
        tj.forEach(function (x) {
          var on = x === t;
          x.classList.toggle('ativo', on); x.setAttribute('aria-selected', on ? 'true' : 'false');
          document.getElementById('jg-' + x.getAttribute('data-jogos')).hidden = !on;
        });
      });
    });
  }

  // Filtros (galeria e jogos): botões .filtro[data-filtro] filtram itens [data-cat]
  var filtros = document.querySelectorAll('.filtro');
  filtros.forEach(function (f) {
    f.addEventListener('click', function () {
      var alvo = f.getAttribute('data-filtro');
      filtros.forEach(function (x) { x.classList.remove('ativo'); });
      f.classList.add('ativo');
      document.querySelectorAll('[data-cat]').forEach(function (it) {
        it.hidden = !(alvo === 'todos' || it.getAttribute('data-cat') === alvo);
      });
    });
  });

  // Lightbox da galeria
  var lb = document.getElementById('lightbox');
  if (lb) {
    var corpo = lb.querySelector('figure');
    var fechar = function () { lb.classList.remove('aberto'); corpo.innerHTML = ''; };
    document.querySelectorAll('.foto').forEach(function (f) {
      f.addEventListener('click', function () {
        var img = f.querySelector('img');
        var leg = f.getAttribute('data-titulo') || '';
        corpo.innerHTML = '';
        if (img) {
          var i = document.createElement('img'); i.src = img.src; i.alt = img.alt; corpo.appendChild(i);
        } else {
          var d = document.createElement('div'); d.className = 'ph-grande'; d.innerHTML = '<img src="assets/img/logo.png" alt="">'; corpo.appendChild(d);
        }
        var c = document.createElement('figcaption'); c.textContent = leg; corpo.appendChild(c);
        lb.classList.add('aberto');
      });
    });
    lb.addEventListener('click', function (e) { if (e.target === lb || e.target.closest('button')) fechar(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') fechar(); });
  }
})();
