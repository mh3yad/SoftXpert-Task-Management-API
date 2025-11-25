# SoftExpert Task Management API

A robust and scalable RESTful API for managing tasks with dependencies, built with Laravel and featuring role-based access control.

## Install
```bash

git clone https://github.com/mh3yad/SoftXpert-Task-Management-API task-api
cd task-api
cp .env.example .env
composer install
./vendor/bin/sail up
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan db:seed

```

## Technology Stack
```bash

Backend: Laravel 12
Database: MySQL 8.0
Authentication: Laravel Sanctum
Containerization: Laravel sail Docker & Docker Compose
```

## Features
```bash

* Task Crud
* Task Validation
* role based AC
* Task Depenedency
* Advanced Filtering by status, due date, assignee
* Docker Containerization for easy deployment
* Database Migrations & Seeders
```

## User Roles
```bash

* Manager
* User
```

Prerequisites

    Docker and Docker Compose

    OR PHP 8.2+, Composer, MySQL

## Request/Response Examples
### Login

### Request:

```bash

POST /api/login
{
"email": "manager@example.com",
"password": "password"
}
```
### Response

```bash

{
    "user": {
        "id": 4,
        "name": "manager 3",
        "role": "manager",
        "email": "manager7@softxpert.com"
    },
    "token": "1|ua3TVvWbd2ddWgBmdhXpDZIgT2VAvlqj92WEAMjc22180722"
}
```

### Create Task (Manager Only)

### Request:

```bash

POST /api/tasks
{
Authorization: Bearer {token}
{
  "title": "New Task",
  "description": "Task description",
  "due_date": "2024-12-31 23:59:59",
  "assignee_id": 2
  }
}
```
### Response

```bash

{
    "user": {
        "id": 4,
        "name": "manager 3",
        "role": "manager",
        "email": "manager7@softxpert.com"
    },
    "token": "1|ua3TVvWbd2ddWgBmdhXpDZIgT2VAvlqj92WEAMjc22180722"
}
```

## Task Dependencies Logic

    Tasks can have dependencies on other tasks

    A task cannot be marked as completed until all its dependencies are completed

    Circular dependencies are automatically prevented

    Dependencies can be added/removed by managers