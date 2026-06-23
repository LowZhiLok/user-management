# User Management Laravel Assessment

A Laravel user management application with a Blade admin frontend, public REST API, soft deletes, bulk delete, filtering, pagination, validation, tests, API Resources, and Excel export.

## Features

- User CRUD from the admin frontend.
- Create and edit users with modals on the users index page.
- REST API for create, list, detail, update, delete, bulk delete, and Excel export.
- Filtering by name, status, trashed records, and pagination.
- Soft delete and restore support.
- Bulk delete with checkbox selection.
- Excel export using `maatwebsite/excel`.
- Form Request validation for create, update, and bulk delete.
- API responses formatted with `App\Http\Resources\UserResource`.
- PHPUnit feature and unit tests.

## Tech Stack

- Laravel 12
- PHP 8.2+
- MySQL
- Maatwebsite Excel
- PHPUnit

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Configure MySQL in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=user_management
DB_USERNAME=root
DB_PASSWORD=
```

Run migrations:

```bash
php artisan migrate:fresh --seed
```

Start the app:

```bash
php artisan serve
```

Open:

```text
http://127.0.0.1:8000/users
```

## Web Routes

| Method | URL | Description |
| --- | --- | --- |
| GET | `/users` | User management page |
| POST | `/users` | Create user |
| PUT | `/users/{user}` | Update user |
| DELETE | `/users/{user}` | Soft delete user |
| DELETE | `/users` | Bulk delete users |
| POST | `/users/{user}/restore` | Restore soft-deleted user |
| GET | `/users/export` | Export users to Excel |

## API Routes

All API routes are prefixed with `/api/v1`.

| Method | URL | Description |
| --- | --- | --- |
| GET | `/api/v1/users` | List users |
| POST | `/api/v1/users` | Create user |
| GET | `/api/v1/users/{user}` | User detail |
| PUT | `/api/v1/users/{user}` | Update user |
| DELETE | `/api/v1/users/{user}` | Soft delete user |
| DELETE | `/api/v1/users` | Bulk delete users |
| GET | `/api/v1/users-export` | Export users to Excel |

### List Users

```http
GET /api/v1/users?name=Alice&status=active&trashed=0&per_page=15
```

### Create User

```json
{
  "name": "Alice Tan",
  "email": "alice@example.com",
  "phone_number": "1234567890",
  "password": "password123",
  "status": "active"
}
```

### Bulk Delete

```json
{
  "ids": [1, 2, 3]
}
```

### Export

```http
GET /api/v1/users-export?name=Alice&status=active
```

Export columns:

- ID
- Name
- Email
- Phone Number
- Status
- Created At, formatted as `YYYY-MM-DD`

## Validation

### Create User

| Field | Rules |
| --- | --- |
| `name` | required, string, max 255 |
| `email` | required, email, max 255, unique |
| `phone_number` | required, digits only, min 7, max 15, unique |
| `password` | required, string, min 8 |
| `status` | required, active or inactive |

### Update User

| Field | Rules |
| --- | --- |
| `name` | sometimes, string, max 255 |
| `email` | sometimes, email, max 255, unique except current user |
| `phone_number` | sometimes, digits only, min 7, max 15, unique except current user |
| `password` | sometimes, nullable, string, min 8 |
| `status` | sometimes, active or inactive |

### Bulk Delete

| Field | Rules |
| --- | --- |
| `ids` | required, array, min 1 |
| `ids.*` | required, integer, exists in users table |

## Database

The users table includes:

- `name`
- `email`
- `phone_number`
- `password`
- `status`
- `deleted_at`
- `created_at`
- `updated_at`

The `User` model uses Laravel soft deletes.

## Testing

Run all tests:

```bash
php artisan test
```

Current suite covers:

- Web user management flows.
- API user flows.
- Bulk delete.
- Soft delete and restore.
- Validation failures.
- Excel export mapping and filtering.

## Security Notes

- Passwords are hashed through the User model cast.
- Web form submissions use Laravel CSRF protection.
- Inputs are validated server-side with Form Requests.
- The REST API is intentionally public because the assessment states it must be accessible by anyone.
- For production, protect the admin frontend with authentication middleware.

## Submission Notes

Do not commit `.env`, `vendor/`, `.phpunit.result.cache`, `.agents/`, logs, or local cache files. Can install dependencies with `composer install`.
