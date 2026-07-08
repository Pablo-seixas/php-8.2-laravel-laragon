(function () {
    const root = document.documentElement;
    const body = document.body;

    const savedFont = localStorage.getItem('a11y_font') || '100';
    const contrast = localStorage.getItem('a11y_contrast') === 'true';
    const dark = localStorage.getItem('a11y_dark') === 'true';

    root.style.setProperty('--a11y-font-size', savedFont + '%');

    contrast ? body.classList.add('a11y-contrast') : body.classList.remove('a11y-contrast');
    dark ? body.classList.add('a11y-dark') : body.classList.remove('a11y-dark');

    window.a11yFontMais = function () {
        const atual = parseInt(localStorage.getItem('a11y_font') || '100');
        const novo = atual >= 140 ? 140 : atual + 10;
        localStorage.setItem('a11y_font', novo);
        root.style.setProperty('--a11y-font-size', novo + '%');
    };

    window.a11yFontMenos = function () {
        const atual = parseInt(localStorage.getItem('a11y_font') || '100');
        const novo = atual <= 80 ? 80 : atual - 10;
        localStorage.setItem('a11y_font', novo);
        root.style.setProperty('--a11y-font-size', novo + '%');
    };

    window.a11yContraste = function () {
        body.classList.toggle('a11y-contrast');
        localStorage.setItem('a11y_contrast', body.classList.contains('a11y-contrast'));
    };

    window.a11yDark = function () {
        body.classList.toggle('a11y-dark');
        localStorage.setItem('a11y_dark', body.classList.contains('a11y-dark'));
    };

    window.a11yReset = function () {
        localStorage.removeItem('a11y_font');
        localStorage.removeItem('a11y_contrast');
        localStorage.removeItem('a11y_dark');
        location.reload();
    };

    window.a11yFalar = function () {
        const texto = document.querySelector('h1, h2, title')?.innerText || document.title || 'Sistema de controle de estoque';
        speechSynthesis.cancel();
        speechSynthesis.speak(new SpeechSynthesisUtterance(texto));
    };

    document.addEventListener('keydown', function (e) {
        if (!e.altKey) return;

        const rotas = {
            d: '/dashboard',
            p: '/produtos',
            c: '/categorias',
            e: '/entradas',
            s: '/saidas',
            r: '/relatorio',
            u: '/usuarios'
        };

        const rota = rotas[e.key.toLowerCase()];
        rota ? window.location.href = rota : null;
    });
})();
