# Payroll Management System

A RESTful API for managing employee payroll built with Laravel.

## Table of Contents

-   [Getting Started](#getting-started)
-   [API Documentation](#api-documentation)
-   [Software Architecture](#software-architecture)
-   [Workflow](#workflow)

## Getting Started

### Prerequisites

-   PHP 8.2+
-   Composer
-   PostgreSQL
-   Laravel 12+

### Installation

1. Clone the repository:
    ```bash
    git clone https://github.com/IndraGunawan07/payroll-system.git
    cd payroll-system
    ```
2. Install dependencies:

    ```bash
    composer install
    ```

3. Create and configure your `.env` file.

4. Run database migrations:

    ```bash
    php artisan migrate:fresh
    ```

5. Seed the database with initial data:

    ```bash
    php artisan db:seed EmployeeSeeder
    ```

6. Start the local development server:

    ```bash
    php artisan serve
    ```

### Authentication

All endpoints require authentication except for the login endpoint. You must login first to obtain an access token.

Admin credentials:

-   **Username:** `admin@dealls.com`
-   **Password:** `admin123`

## API Documentation

| Method | Endpoint               | Description                       |
| ------ | ---------------------- | --------------------------------- |
| POST   | `/api/login`           | Login for admin and employee      |
| POST   | `/api/logout`          | Logout current authenticated user |
| POST   | `/api/attendance`      | Record employee attendance        |
| POST   | `/api/overtime`        | Record employee overtime request  |
| POST   | `/api/reimbursement`   | Record employee reimbursement     |
| POST   | `/api/payslip`         | Generate payslip for employee     |
| GET    | `/api/payroll-periods` | List all payroll periods          |
| POST   | `/api/payroll-periods` | Create a new payroll period       |
| POST   | `/api/payroll`         | Run payroll for employees         |
| GET    | `/api/payslip`         | Get summary of employee payslips  |
| GET    | `/api/employee`        | List of all employees             |

> **Note:** The attendance, overtime, reimbursement, and payroll period endpoints can be used in any order. However, payroll and payslip endpoints require a payroll period to be created first.

## Workflow

1. Login to get an authentication token via:

    ```bash
    POST /api/login
    ```

2. Use the token for accessing other endpoints (include it in the `Authorization` header as `Bearer <token>`).

3. Create a payroll period via:

    ```bash
    POST /api/payroll-periods
    ```

4. Submit data:

    - Attendance:
        ```bash
        POST /api/attendance
        ```
    - Overtime:
        ```bash
        POST /api/overtime
        ```
    - Reimbursement:
        ```bash
        POST /api/reimbursement
        ```

5. Run payroll and generate payslips:

    - Run payroll:
        ```bash
        POST /api/payroll
        ```
    - Get payslips:
        ```bash
        GET /api/payslip
        ```

## Software Architecture

### 1. API Layer

-   RESTful endpoints
-   Request validation
-   Authentication

### 2. Service Layer

-   Payroll logic
-   Payslip generation
-   Calculations

### 3. Data Layer

-   PostgreSQL database
-   Eloquent ORM models
