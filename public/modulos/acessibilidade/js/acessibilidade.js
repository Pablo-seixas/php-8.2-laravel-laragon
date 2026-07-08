(function () {
    function aplicarPreferencias() {
        const body = document.body;
        const root = document.documentElement;

        const font = localStorage.getItem('a11y_font') || '100';
        const dark = localStorage.getItem('a11y_dark') === 'true';
        const contrast = localStorage.getItem('a11y_contrast') === 'true';

        root.style.setProperty('--a11y-font-size', font + '%');

        body.classList.remove('a11y-dark', 'a11y-contrast');

        dark ? body.classList.add('a11y-dark') : null;
        contrast ? body.classList.add('a11y-contrast') : null;
    }

    window.a11yEscuro = function () {
        localStorage.setItem('a11y_dark', 'true');
        localStorage.setItem('a11y_contrast', 'false');
        aplicarPreferencias();
    };

    window.a11yClaro = function () {
        localStorage.setItem('a11y_dark', 'false');
        localStorage.setItem('a11y_contrast', 'false');
        aplicarPreferencias();
    };

    window.a11yContraste = function () {
        localStorage.setItem('a11y_dark', 'false');
        localStorage.setItem('a11y_contrast', 'true');
        aplicarPreferencias();
    };

    window.a11yFontMais = function () {
        const atual = parseInt(localStorage.getItem('a11y_font') || '100');
        localStorage.setItem('a11y_font', atual >= 140 ? 140 : atual + 10);
        aplicarPreferencias();
    };

    window.a11yFontMenos = function () {
        const atual = parseInt(localStorage.getItem('a11y_font') || '100');
        localStorage.setItem('a11y_font', atual <= 80 ? 80 : atual - 10);
        aplicarPreferencias();
    };

    window.a11yFalar = function () {
        const texto = document.querySelector('h1, h2')?.innerText || document.title || 'Sistema de controle de estoque';
        speechSynthesis.cancel();
        speechSynthesis.speak(new SpeechSynthesisUtterance(texto));
    };

    window.a11yReset = function () {
        localStorage.removeItem('a11y_font');
        localStorage.removeItem('a11y_dark');
        localStorage.removeItem('a11y_contrast');
        aplicarPreferencias();
    };

    document.readyState === 'loading'
        ? document.addEventListener('DOMContentLoaded', aplicarPreferencias)
        : aplicarPreferencias();
})();
