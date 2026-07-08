# Sistema de Controle de Estoque - Laravel

## Objetivo

Este projeto é um sistema simples de controle de estoque feito com Laravel.
Ele permite cadastrar, listar, editar, excluir e consultar produtos.

O sistema foi pensado para materiais como:

- Material de limpeza
- Material tecnológico
- Material de escritório
- Outros materiais

---

## Tecnologias utilizadas

- PHP
- Laravel
- MySQL
- Blade
- Bootstrap 5
- Laragon

---

## Banco de dados

No arquivo .env configure:

DB_DATABASE=estoque_laravel
DB_USERNAME=root
DB_PASSWORD=

---

## Comandos principais

Criar tabelas e categorias:

php artisan migrate --seed

Rodar o projeto:

php artisan serve

Abrir no navegador:

http://127.0.0.1:8000

---

## Resumo

Sistema de controle de estoque desenvolvido em Laravel utilizando:

- MVC
- Migrations
- Seeders
- Relacionamentos Eloquent
- Blade
- Bootstrap

Possui cadastro de categorias e produtos, controle de estoque mínimo e indicação visual de estoque baixo.
