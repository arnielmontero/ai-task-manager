# AI Task Manager — Backend API Documentation

> Built with **Laravel 10** + **Sanctum Authentication** + **MySQL**

---

## 📋 Table of Contents

- [Tech Stack](#tech-stack)
- [Getting Started](#getting-started)
- [Environment Setup](#environment-setup)
- [Authentication](#authentication)
- [Projects API](#projects-api)
- [Tasks API](#tasks-api)
- [AI Suggestions API](#ai-suggestions-api)
- [Error Responses](#error-responses)
- [HTTP Status Codes](#http-status-codes)

---

## 🛠 Tech Stack

| Technology | Version | Purpose |
|---|---|---|
| PHP | 8.1+ | Server language |
| Laravel | 10.x | Backend framework |
| Laravel Sanctum | 3.x | API token authentication |
| MySQL | 8.0+ | Database |
| OpenAI PHP SDK | latest | AI task suggestions |

---

## 🚀 Getting Started

### 1. Clone the repository
```bash
git clone https://github.com/yourusername/ai-task-manager.git
cd ai-task-manager/backend
```

### 2. Install dependencies
```bash
composer install
```

### 3. Copy environment file
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure `.env` file
```env
APP_NAME=AITaskManager
APP_ENV=local
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ai_task_manager
DB_USERNAME=root
DB_PASSWORD=

OPENAI_API_KEY=sk-your-openai-key-here
```

### 5. Run migrations
```bash
php artisan migrate
```

### 6. Start the server
```bash
php artisan serve
```

Server runs at: `http://127.0.0.1:8000`

---

## ⚙️ Environment Setup

### Required Headers for ALL requests

| Header | Value |
|---|---|
| `Accept` | `application/json` |
| `Content-Type` | `application/json` |

### Required Header for Protected routes

| Header | Value |
|---|---|
| `Authorization` | `Bearer {your_token}` |

> You get your token after **Register** or **Login**. Save it and use it for all other requests.

---

## 🔐 Authentication

Base URL: `http://127.0.0.1:8000/api`

All auth endpoints are **public** — no token required.

---

### ➕ Register

**Endpoint**
```
POST /api/register
```

**Headers**
```
Accept: application/json
Content-Type: application/json
```

**Request Body**
```json
{
    "name": "Juan dela Cruz",
    "email": "juan@example.com",
    "password": "password123"
}
```

**Field Rules**
| Field | Type | Rules |
|---|---|---|
| `name` | string | required |
| `email` | string | required, valid email, unique |
| `password` | string | required, min 6 characters |

**Success Response** `201`
```json
{
    "token": "1|abc123xyz456def789...",
    "user": {
        "id": 1,
        "name": "Juan dela Cruz",
        "email": "juan@example.com",
        "created_at": "2024-11-01T10:00:00.000000Z",
        "updated_at": "2024-11-01T10:00:00.000000Z"
    }
}
```

> ⚠️ **Save the token!** You will need it for all protected endpoints.

---

### 🔑 Login

**Endpoint**
```
POST /api/login
```

**Headers**
```
Accept: application/json
Content-Type: application/json
```

**Request Body**
```json
{
    "email": "juan@example.com",
    "password": "password123"
}
```

**Success Response** `200`
```json
{
    "token": "2|newtoken123abc456...",
    "user": {
        "id": 1,
        "name": "Juan dela Cruz",
        "email": "juan@example.com",
        "created_at": "2024-11-01T10:00:00.000000Z",
        "updated_at": "2024-11-01T10:00:00.000000Z"
    }
}
```

**Error Response** `401`
```json
{
    "message": "Invalid credentials"
}
```

---

### 🚪 Logout

**Endpoint**
```
POST /api/logout
```

**Headers**
```
Accept: application/json
Authorization: Bearer {your_token}
```

**Success Response** `200`
```json
{
    "message": "Logged out"
}
```

---

## 📁 Projects API

> All project endpoints require **Authorization: Bearer {token}**

---

### 📋 Get All Projects

**Endpoint**
```
GET /api/projects
```

**Headers**
```
Accept: application/json
Authorization: Bearer {your_token}
```

**Success Response** `200`
```json
[
    {
        "id": 1,
        "user_id": 1,
        "title": "Portfolio Website",
        "description": "Build my personal portfolio using Next.js",
        "tasks_count": 5,
        "created_at": "2024-11-01T10:00:00.000000Z",
        "updated_at": "2024-11-01T10:00:00.000000Z"
    },
    {
        "id": 2,
        "user_id": 1,
        "title": "E-Commerce App",
        "description": "Online store with payment integration",
        "tasks_count": 8,
        "created_at": "2024-11-02T08:00:00.000000Z",
        "updated_at": "2024-11-02T08:00:00.000000Z"
    }
]
```

---

### 🔍 Get Single Project

**Endpoint**
```
GET /api/projects/{id}
```

**Example**
```
GET /api/projects/1
```

**Headers**
```
Accept: application/json
Authorization: Bearer {your_token}
```

**Success Response** `200`
```json
{
    "id": 1,
    "user_id": 1,
    "title": "Portfolio Website",
    "description": "Build my personal portfolio using Next.js",
    "tasks_count": 5,
    "created_at": "2024-11-01T10:00:00.000000Z",
    "updated_at": "2024-11-01T10:00:00.000000Z"
}
```

**Error Response** `404`
```json
{
    "message": "No query results for model [App\\Models\\Project] 1"
}
```

---

### ➕ Create Project

**Endpoint**
```
POST /api/projects
```

**Headers**
```
Accept: application/json
Content-Type: application/json
Authorization: Bearer {your_token}
```

**Request Body**
```json
{
    "title": "Portfolio Website",
    "description": "Build my personal portfolio using Next.js"
}
```

**Field Rules**
| Field | Type | Rules |
|---|---|---|
| `title` | string | required |
| `description` | string | optional |

**Success Response** `201`
```json
{
    "id": 1,
    "user_id": 1,
    "title": "Portfolio Website",
    "description": "Build my personal portfolio using Next.js",
    "created_at": "2024-11-01T10:00:00.000000Z",
    "updated_at": "2024-11-01T10:00:00.000000Z"
}
```

---

### ✏️ Update Project

**Endpoint**
```
PATCH /api/projects/{id}
```

**Example**
```
PATCH /api/projects/1
```

**Headers**
```
Accept: application/json
Content-Type: application/json
Authorization: Bearer {your_token}
```

**Request Body** (send only fields you want to update)
```json
{
    "title": "Portfolio Website v2",
    "description": "Updated description"
}
```

**Success Response** `200`
```json
{
    "id": 1,
    "user_id": 1,
    "title": "Portfolio Website v2",
    "description": "Updated description",
    "created_at": "2024-11-01T10:00:00.000000Z",
    "updated_at": "2024-11-01T11:00:00.000000Z"
}
```

---

### 🗑️ Delete Project

**Endpoint**
```
DELETE /api/projects/{id}
```

**Example**
```
DELETE /api/projects/1
```

**Headers**
```
Accept: application/json
Authorization: Bearer {your_token}
```

**Success Response** `204`
```
No Content
```

> ⚠️ Deleting a project will also delete **all its tasks** (cascade delete).

---

## ✅ Tasks API

> All task endpoints require **Authorization: Bearer {token}**
> Tasks are always nested under a project: `/api/projects/{project_id}/tasks`

---

### 📋 Get All Tasks of a Project

**Endpoint**
```
GET /api/projects/{project_id}/tasks
```

**Example**
```
GET /api/projects/1/tasks
```

**Headers**
```
Accept: application/json
Authorization: Bearer {your_token}
```

**Success Response** `200`
```json
[
    {
        "id": 1,
        "project_id": 1,
        "title": "Design the homepage",
        "description": "Create wireframes for the main landing page",
        "status": "done",
        "priority": "high",
        "due_date": "2024-12-01",
        "created_at": "2024-11-01T10:00:00.000000Z",
        "updated_at": "2024-11-01T10:00:00.000000Z"
    },
    {
        "id": 2,
        "project_id": 1,
        "title": "Setup Next.js project",
        "description": "Initialize project with Tailwind CSS",
        "status": "in-progress",
        "priority": "medium",
        "due_date": "2024-12-10",
        "created_at": "2024-11-01T11:00:00.000000Z",
        "updated_at": "2024-11-01T11:00:00.000000Z"
    }
]
```

---

### ➕ Create Task

**Endpoint**
```
POST /api/projects/{project_id}/tasks
```

**Example**
```
POST /api/projects/1/tasks
```

**Headers**
```
Accept: application/json
Content-Type: application/json
Authorization: Bearer {your_token}
```

**Request Body**
```json
{
    "title": "Design the homepage",
    "description": "Create wireframes for the main landing page",
    "status": "todo",
    "priority": "high",
    "due_date": "2024-12-31"
}
```

**Field Rules**
| Field | Type | Rules | Values |
|---|---|---|---|
| `title` | string | required | any text |
| `description` | string | optional | any text |
| `status` | enum | optional, default: `todo` | `todo` `in-progress` `done` |
| `priority` | enum | optional, default: `medium` | `low` `medium` `high` |
| `due_date` | date | optional | `YYYY-MM-DD` format |

**Success Response** `201`
```json
{
    "id": 1,
    "project_id": 1,
    "title": "Design the homepage",
    "description": "Create wireframes for the main landing page",
    "status": "todo",
    "priority": "high",
    "due_date": "2024-12-31",
    "created_at": "2024-11-01T10:00:00.000000Z",
    "updated_at": "2024-11-01T10:00:00.000000Z"
}
```

---

### ✏️ Update Task

**Endpoint**
```
PATCH /api/projects/{project_id}/tasks/{task_id}
```

**Example**
```
PATCH /api/projects/1/tasks/1
```

**Headers**
```
Accept: application/json
Content-Type: application/json
Authorization: Bearer {your_token}
```

**Request Body — Update status only (Kanban drag)**
```json
{
    "status": "in-progress"
}
```

**Request Body — Update priority only**
```json
{
    "priority": "low"
}
```

**Request Body — Update everything**
```json
{
    "title": "Design homepage v2",
    "description": "Updated wireframes with dark mode",
    "status": "done",
    "priority": "high",
    "due_date": "2024-12-25"
}
```

**Success Response** `200`
```json
{
    "id": 1,
    "project_id": 1,
    "title": "Design homepage v2",
    "description": "Updated wireframes with dark mode",
    "status": "done",
    "priority": "high",
    "due_date": "2024-12-25",
    "created_at": "2024-11-01T10:00:00.000000Z",
    "updated_at": "2024-11-01T12:00:00.000000Z"
}
```

---

### 🗑️ Delete Task

**Endpoint**
```
DELETE /api/projects/{project_id}/tasks/{task_id}
```

**Example**
```
DELETE /api/projects/1/tasks/1
```

**Headers**
```
Accept: application/json
Authorization: Bearer {your_token}
```

**Success Response** `204`
```
No Content
```

---

## 🤖 AI Suggestions API

> Requires **Authorization: Bearer {token}**
> Powered by **OpenAI GPT-3.5-turbo**

---

### 💡 Get AI Task Suggestions

**Endpoint**
```
POST /api/ai/suggest-tasks
```

**Headers**
```
Accept: application/json
Content-Type: application/json
Authorization: Bearer {your_token}
```

**Request Body**
```json
{
    "project_description": "Build a portfolio website for a software developer using Next.js and Laravel"
}
```

**Success Response** `200`
```json
{
    "suggestions": "Here are 5 actionable tasks for your project:\n\n1. Setup Next.js project with Tailwind CSS\n2. Design homepage layout and wireframes\n3. Build Laravel REST API backend\n4. Create projects showcase section\n5. Deploy frontend on Vercel and backend on Railway"
}
```

---

## ❌ Error Responses

### Validation Error `422`
```json
{
    "message": "The title field is required.",
    "errors": {
        "title": [
            "The title field is required."
        ],
        "email": [
            "The email has already been taken."
        ]
    }
}
```

### Unauthenticated `401`
```json
{
    "message": "Unauthenticated."
}
```

### Forbidden `403`
```json
{
    "message": "Forbidden"
}
```

### Not Found `404`
```json
{
    "message": "No query results for model [App\\Models\\Project] 1"
}
```

### Server Error `500`
```json
{
    "message": "Server Error"
}
```

---

## 📊 HTTP Status Codes

| Code | Meaning | When |
|---|---|---|
| `200` | OK | Successful GET, PATCH, PUT |
| `201` | Created | Successful POST (new record created) |
| `204` | No Content | Successful DELETE |
| `401` | Unauthenticated | Missing or invalid token |
| `403` | Forbidden | Token valid but not your resource |
| `404` | Not Found | Record doesn't exist |
| `422` | Unprocessable | Validation failed |
| `500` | Server Error | Something crashed on the server |

---

## 🗺️ All Endpoints Summary

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| POST | `/api/register` | ❌ | Register new user |
| POST | `/api/login` | ❌ | Login user |
| POST | `/api/logout` | ✅ | Logout user |
| GET | `/api/projects` | ✅ | Get all projects |
| POST | `/api/projects` | ✅ | Create project |
| GET | `/api/projects/{id}` | ✅ | Get single project |
| PATCH | `/api/projects/{id}` | ✅ | Update project |
| DELETE | `/api/projects/{id}` | ✅ | Delete project |
| GET | `/api/projects/{id}/tasks` | ✅ | Get all tasks |
| POST | `/api/projects/{id}/tasks` | ✅ | Create task |
| PATCH | `/api/projects/{id}/tasks/{id}` | ✅ | Update task |
| DELETE | `/api/projects/{id}/tasks/{id}` | ✅ | Delete task |
| POST | `/api/ai/suggest-tasks` | ✅ | Get AI suggestions |

---

## 👨‍💻 Developer

Built by **[Your Name]** as part of a portfolio project.

- GitHub: [github.com/yourusername](https://github.com/yourusername)
- Portfolio: [yourportfolio.com](https://yourportfolio.com)


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

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
