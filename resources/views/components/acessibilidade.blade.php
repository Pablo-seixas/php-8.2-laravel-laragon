@if(env('ACESSIBILIDADE_ATIVA', true))
<link rel="stylesheet" href="{{ asset('acessibilidade/acessibilidade.css') }}">

<div class="a11y-bar" aria-label="Barra de acessibilidade">
    <button type="button" onclick="a11yFontMais()" aria-label="Aumentar fonte">A+</button>
    <button type="button" onclick="a11yFontMenos()" aria-label="Diminuir fonte">A-</button>
    <button type="button" onclick="a11yContraste()" aria-label="Ativar alto contraste">Contraste</button>
    <button type="button" onclick="a11yDark()" aria-label="Ativar modo escuro">Escuro</button>
    <button type="button" onclick="a11yFalar()" aria-label="Ler tela em voz alta">Voz</button>
    <button type="button" onclick="a11yReset()" aria-label="Resetar acessibilidade">Reset</button>
</div>

<script src="{{ asset('acessibilidade/acessibilidade.js') }}"></script>

<div vw class="enabled">
    <div vw-access-button class="active"></div>
    <div vw-plugin-wrapper>
        <div class="vw-plugin-top-wrapper"></div>
    </div>
</div>

<script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
<script>
    new window.VLibras.Widget('https://vlibras.gov.br/app');
</script>
@endif
