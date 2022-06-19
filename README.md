## Introduction

This application allows you to learn English vocabulary necessary for engineers.

## Container structures

├── app
├── web
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
- architecture
  - Laravel 9
  - Vue 3.2
  - Vuex
  - Vue-router
  - Docker
  - nginx 1.2
  - MySQL 8.0

## Authentication

Sanctum with Cookie.

## Tables

- users []

  - id
  - name [ユーザー名]
  - email [メールアドレス]
  - password [パスワード]

- questions [問題]

  - id
  - word [単語]
  - correct_answer [正解]
  - example [例文]

- answers [回答]

  - id
  - question_id [問題 ID]
  - user_id [ユーザー ID]
  - answer [回答]

- results
  - id
  - user_id [ユーザー ID]
  - question_id [問題 ID]
  - execute_date [実施日]
