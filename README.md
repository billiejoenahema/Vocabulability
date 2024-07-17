## Introduction

This application allows you to learn English vocabulary necessary for engineers.

## Container structures

├── app<br>
├── web<br>
└── db

### app container

- Base image
  - php:8.1-fpm-bullseye
  - composer:2.2

### web container

- Base image
  - nginx:1.20

### db container

- Base image
  - mysql/mysql-server:8.0

## Authentication

Sanctum with Cookie.

## architecture

- Laravel 11.15.0
- PHP 8.2.7
- Vue 3.2
- Vuex 4.0
- Vue-router 4.1
- Docker
- nginx 1.2
- MySQL 8.0
