<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# Evaluations Module

## Overview

The Evaluations module is responsible for managing volunteer performance evaluations, awarding badges automatically based on predefined conditions, generating reports, and issuing certificates.  
It plays a core role in tracking volunteer contributions and motivating them through a transparent evaluation system.

This module follows a **Service-Oriented Architecture** and integrates with:

- Applications Module
- Core Module (Users, Roles, Permissions)
- Spatie Permissions & Activity Log

---

## Features

- Create and manage evaluations for volunteers
- Calculate performance scores
- Automatically award badges when conditions are met
- Manage volunteer badges
- Generate evaluation reports
- Role-based access control (RBAC)

---

## Database Tables

### evaluations

Stores evaluation results for volunteers and tasks.

| Column       | Description        |
| ------------ | ------------------ |
| id           | Primary key        |
| task_id      | Related task       |
| evaluator_id | User who evaluated |
| score        | Evaluation score   |
| strengths    | Strength points    |
| improvement  | Improvement notes  |
| evaluated_at | Evaluation date    |
| created_at   | Created at         |

---

### badges

Defines available badges and their conditions.

| Column      | Description       |
| ----------- | ----------------- |
| id          | Primary key       |
| name        | Badge name        |
| condition   | Badge condition   |
| description | Badge description |

---

### volunteer_badges

Pivot table linking volunteers with awarded badges.

| Column       | Description      |
| ------------ | ---------------- |
| volunteer_id | Volunteer        |
| badge_id     | Badge            |
| awarded_by   | User who awarded |
| created_at   | Awarded date     |

---

### certificate

### report

## Authorization & Policies

### Full Access Roles

- System Admin
- Volunteer Coordinator

These roles can:

- Create, update, delete evaluations
- Create and award badges
- View all evaluations and volunteer badges

---

### Volunteer Access

Volunteers can:

- View their own certificate
- View their own badges only

Volunteers cannot:

- Award badges
- View other volunteers' evaluations or badges

Authorization is implemented using:

- Laravel Policies
- Spatie Permissions

---

## Services Layer

### BadgeServices

Responsible for:

- CRUD operations for badges
- Validating badge conditions

---

### VolunteerBadgeServices

Responsible for:

- Automatically awarding badges
- Preventing duplicate badge awards
- Linking volunteers with badges

#### Awarding Flow

---

## Automatic Badge Awarding

Badges are awarded automatically when predefined conditions are met (e.g. number of completed tasks, evaluation score thresholds).

This logic is handled inside `VolunteerBadgeServices`.

---

## Activity Logging

All critical actions are logged using **Spatie Activity Log** with the log name `audit`, including:

- Badge creation
- Badge updates
- Badge deletion
- Awarding badges to volunteers

---

## Running Seeders

To run all Evaluation module seeders:

```bash
php artisan module:seed Evaluations
```
