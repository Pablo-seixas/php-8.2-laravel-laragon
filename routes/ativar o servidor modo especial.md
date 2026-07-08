Perfeito. Salve este bloco no seu Bloco de Notas.

# Iniciar o projeto Laravel (Empresa)

```powershell
cd C:\Users\pablo.seixas\Documents\code4\code\Crud-php-main
```

## Limpar cache

```powershell
& "C:\Users\pablo.seixas\Downloads\php-8.2.31-nts-Win32-vs16-x64\php.exe" artisan optimize:clear
```

## Iniciar servidor (PHP 8.2)

```powershell
& "C:\Users\pablo.seixas\Downloads\php-8.2.31-nts-Win32-vs16-x64\php.exe" artisan serve --host=127.0.0.1 --port=8000
```

---

## Abrir no navegador

```text
http://127.0.0.1:8000
```

---

## Verificar se está usando PHP 8.2

```powershell
& "C:\Users\pablo.seixas\Downloads\php-8.2.31-nts-Win32-vs16-x64\php.exe" -v
```

---

## Listar todas as rotas

```powershell
& "C:\Users\pablo.seixas\Downloads\php-8.2.31-nts-Win32-vs16-x64\php.exe" artisan route:list
```

---

## Atualizar banco de dados

```powershell
& "C:\Users\pablo.seixas\Downloads\php-8.2.31-nts-Win32-vs16-x64\php.exe" artisan migrate
```

---

## Recriar o banco (CUIDADO: apaga os dados)

```powershell
& "C:\Users\pablo.seixas\Downloads\php-8.2.31-nts-Win32-vs16-x64\php.exe" artisan migrate:fresh --seed
```

---

### Dica

Como você sempre vai usar o **PHP 8.2**, recomendo criar um arquivo `iniciar_projeto.bat`. Assim, você só dá dois cliques e ele já abre o projeto com o PHP correto. Isso evita erros quando houver PHP 8.3 instalado na máquina.
