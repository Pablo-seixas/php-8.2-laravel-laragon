# Sistema de Controle de Estoque - Laravel 11

# Sistema de Controle de Estoque - Laravel

<p align="center">
  <img src="https://github.com/Pablo-seixas/php-8.2-laravel-laragon/blob/main/Screenshots/libraM.png?raw=true" alt="Dashboard do Sistema" width="900">
</p>

<p align="center">
  <img src="https://github.com/Pablo-seixas/php-8.2-laravel-laragon/blob/main/Screenshots/grafico%20.png?raw=true" alt="Gráficos Analíticos" width="900">
</p>

## Objetivo

...
<p align="center">

![Laravel](https://img.shields.io/badge/Laravel-11-red)
![PHP](https://img.shields.io/badge/PHP-8.2-blue)
![SQLite](https://img.shields.io/badge/SQLite-3-green)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-purple)
![License](https://img.shields.io/badge/license-MIT-brightgreen)

</p>

---

# Sistema de Controle de Estoque

Sistema desenvolvido em **Laravel 11** com foco no gerenciamento de materiais e controle de estoque, utilizando arquitetura MVC e boas práticas de desenvolvimento.

O projeto foi criado para servir como base para ambientes corporativos, permitindo o gerenciamento de produtos, entradas, saídas, relatórios, usuários, logs e recursos administrativos.

---

# Objetivo

Disponibilizar uma aplicação simples, organizada e escalável para controle de estoque, permitindo o gerenciamento de diversos tipos de materiais, como:

- Material de informática
- Material de limpeza
- Material de escritório
- Equipamentos
- Periféricos
- Ferramentas
- Demais itens patrimoniais

---

# Funcionalidades

Atualmente o sistema possui:

- Cadastro de produtos
- Cadastro de categorias
- Controle de entradas
- Controle de saídas
- Controle automático de estoque
- Estoque mínimo com alerta visual
- Dashboard administrativo
- Relatórios
- Extrato de movimentações
- Controle de usuários
- Controle de permissões
- Alteração de senha
- Registro de Logs
- Central Analítica
- Módulo de Backup
- Organização automática dos backups por Ano → Mês → Semana
- Download dos backups
- Política de retenção dos backups

---

# Tecnologias Utilizadas

- PHP 8.2
- Laravel 11
- SQLite
- MySQL
- Blade
- Bootstrap 5
- Eloquent ORM
- Laragon
- Git
- GitHub

---

# Arquitetura

O projeto utiliza a arquitetura MVC do Laravel:

- Controllers
- Models
- Services
- Middleware
- Migrations
- Seeders
- Blade Templates

---

# Banco de Dados

Banco principal do sistema:

```env
DB_CONNECTION=sqlite
```

O módulo de Backup utiliza um banco MySQL independente:

```env
BACKUP_DB_HOST=127.0.0.1
BACKUP_DB_PORT=3306
BACKUP_DB_DATABASE=backup_laravel
BACKUP_DB_USERNAME=root
BACKUP_DB_PASSWORD=
```

---

# Instalação

Clone o projeto:

```bash
git clone https://github.com/Pablo-seixas/php-8.2-laravel-laragon.git
```

Entre na pasta:

```bash
cd php-8.2-laravel-laragon
```

Instale as dependências:

```bash
composer install
```

Configure o arquivo `.env`.

Execute as migrations:

```bash
php artisan migrate --seed
```

Inicie o servidor:

```bash
php artisan serve
```

Acesse:

```
http://127.0.0.1:8000
```

---

# Recursos Administrativos

O sistema possui uma área exclusiva para administradores contendo:

- Gestão de usuários
- Categorias
- Logs do sistema
- Backup
- Dashboard
- Central Analítica

---

# Estrutura do Projeto

```
app/
 ├── Http/
 ├── Models/
 ├── Services/
 ├── Middleware/

database/
 ├── migrations/
 ├── seeders/

resources/
 ├── views/

routes/
 ├── web.php
```

---

# Funcionalidades em Desenvolvimento

Planejadas para as próximas versões:

- Backup automático utilizando mysqldump
- Compactação em ZIP
- Restauração de Backup
- Dashboard Analítico Avançado
- Machine Learning para previsão de consumo
- Previsão de ruptura de estoque
- Indicadores inteligentes
- Exportação para PDF
- Exportação para Excel
- API REST
- Auditoria completa

---

# Licença


---

# Autor

**Pablo Seixas**

GitHub:

https://github.com/Pablo-seixas
