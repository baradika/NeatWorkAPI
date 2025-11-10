# NeatWork API

[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com/)
[![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)

NeatWork API is the backend service for the NeatWork platform, a comprehensive solution connecting users with various home services. This API handles user authentication, service management, and booking operations.

## 📋 Table of Contents

- [Features](#-features)
- [Prerequisites](#-prerequisites)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [API Documentation](#-api-documentation)
  - [Authentication](#authentication)
  - [Services](#services)
  - [Bookings](#bookings)
- [Environment Variables](#-environment-variables)
- [Testing](#-testing)
- [Contributing](#-contributing)
- [License](#-license)

## ✨ Features

- 🔐 JWT Authentication
- 📱 RESTful API endpoints
- 📅 Booking management
- 🛠️ Service catalog
- 🔄 Real-time updates
- 🔒 Role-based access control

## 🚀 Prerequisites

- PHP >= 8.1
- Composer
- MySQL >= 5.7 or MariaDB >= 10.3
- Node.js & NPM (for frontend assets)

## 🛠 Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/neatwork-api.git
   cd neatwork-api
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Install NPM dependencies:
   ```bash
   npm install
   npm run build
   ```

4. Copy the environment file:
   ```bash
   cp .env.example .env
   ```

5. Generate application key:
   ```bash
   php artisan key:generate
   ```

6. Configure your `.env` file with database credentials and other settings.

7. Run migrations and seed the database:
   ```bash
   php artisan migrate --seed
   ```

8. Start the development server:
   ```bash
   php artisan serve
   ```

## ⚙️ Configuration

### Environment Variables

Key environment variables to configure in your `.env` file:

```env
APP_NAME=NeatWork
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=neatwork
DB_USERNAME=root
DB_PASSWORD=

JWT_SECRET=your_jwt_secret_here
```

## 📚 API Documentation

### Authentication

All API endpoints (except login/register) require authentication. Include the JWT token in the `Authorization` header:

```
Authorization: Bearer your_jwt_token_here
```

### Services

#### Available Services

| Kode Service | Nama Layanan | Deskripsi | Harga |
|--------------|--------------|-----------|-------|
| S-ART | Asisten Rumah Tangga | Membantu pekerjaan rumah tangga harian | Rp 50,000/jam |
| S-DC | Deep Cleaning | Pembersihan menyeluruh ruangan | Rp 75,000/jam |
| S-LN | Laundry | Jasa laundry dan setrika pakaian | Rp 40,000/kg |
| S-TK | Tukang Kebun | Perawatan dan perbaikan taman | Rp 60,000/jam |
| S-BB | Baby Sitter | Pengasuhan dan perawatan anak | Rp 80,000/jam |

### Bookings

#### Get Available Time Slots

- **URL**: `/api/available-slots`
- **Method**: `GET`
- **Authentication**: Required
- **Query Parameters**:
  - `date` (required): Date in YYYY-MM-DD format
  - `service_id` (required): ID of the service

**Success Response (200 OK):**
```json
{
    "status": "success",
    "data": [
        "09:00", "10:00", "13:00", "14:00"
    ]
}
```

## 🔧 Testing

Run the test suite:

```bash
php artisan test
```

## 🤝 Contributing

1. Fork the project
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🔑 Authentication

### Register a New User

Register a new user account.

- **URL**: `/api/auth/register`
- **Method**: `POST`
- **Authentication**: Not required
- **Content-Type**: `application/json`

**Request Body:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| name | string | Yes | User's full name |
| email | string | Yes | User's email address |
| password | string | Yes | Account password (min: 8 characters) |
| password_confirmation | string | Yes | Password confirmation |
| role | string | No | User role (default: 'pelanggan') |

**Example Request:**
```http
POST /api/auth/register
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "securepassword123",
    "password_confirmation": "securepassword123",
    "role": "pelanggan"
}
```

**Success Response (201 Created):**
```json
{
    "status": "success",
    "message": "Registrasi berhasil",
    "data": {
        "id_user": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "role": "pelanggan",
        "created_at": "2025-11-10T02:30:00.000000Z"
    }
}
```

**Error Responses:**
- `422 Unprocessable Entity` - Validation error
- `500 Internal Server Error` - Server error

### Login

Authenticate user and retrieve JWT token.

- **URL**: `/api/auth/login`
- **Method**: `POST`
- **Authentication**: Not required
- **Content-Type**: `application/json`

**Request Body:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| email | string | Yes | User's email |
| password | string | Yes | User's password |

**Example Request:**
```http
POST /api/auth/login
Content-Type: application/json

{
    "email": "john@example.com",
    "password": "securepassword123"
}
```

**Success Response (200 OK):**
```json
{
    "status": "success",
    "message": "Login berhasil",
    "data": {
        "id_user": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "role": "pelanggan",
        "created_at": "2025-11-10T02:30:00.000000Z"
    },
    "token": "1|abcdefghijklmnopqrstuvwxyz"
}
```

**Error Responses:**
- `401 Unauthorized` - Invalid credentials
- `422 Unprocessable Entity` - Validation error
- `500 Internal Server Error` - Server error
```

## 📅 Booking Services

### Create New Booking

Create a new service booking.

- **URL**: `/api/bookings`
- **Method**: `POST`
- **Authentication**: Required (Bearer Token)
- **Content-Type**: `application/json`

**Request Body:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| jenis_service_id | integer | Yes | ID of the service to book |
| alamat | string | Yes | Service address |
| service_date | date (YYYY-MM-DD) | Yes | Date of service |
| duration | integer | Yes | Duration in hours |
| preferred_gender | string | No | Preferred worker gender (male/female/any) |
| catatan | string | No | Additional notes |

**Example Request:**
```http
POST /api/bookings
Authorization: Bearer your_jwt_token_here
Content-Type: application/json

{
    "jenis_service_id": 1,
    "alamat": "Jl. Contoh No. 123, Jakarta",
    "service_date": "2025-11-15",
    "duration": 2,
    "preferred_gender": "female",
    "catatan": "Mohon bawa peralatan lengkap"
}
```

**Success Response (201 Created):**
```json
{
    "status": "success",
    "message": "Pemesanan berhasil dibuat",
    "data": {
        "id": 1,
        "user_id": 1,
        "jenis_service_id": 1,
        "alamat": "Jl. Contoh No. 123, Jakarta",
        "service_date": "2025-11-15",
        "duration": 2,
        "preferred_gender": "female",
        "status": "pending",
        "catatan": "Mohon bawa peralatan lengkap",
        "total_harga": 100000,
        "created_at": "2025-11-10T11:30:00.000000Z",
        "updated_at": "2025-11-10T11:30:00.000000Z",
        "jenis_service": {
            "id": 1,
            "kode_service": "S-ART",
            "nama_service": "Asisten Rumah Tangga",
            "deskripsi": "Membantu pekerjaan rumah tangga harian",
            "harga": 50000,
            "estimasi_waktu": 1
        }
    }
}
```

### Get User's Bookings

Retrieve all bookings for the authenticated user.

- **URL**: `/api/bookings`
- **Method**: `GET`
- **Authentication**: Required (Bearer Token)
- **Query Parameters**:
  - `status` (optional): Filter by status (pending/confirmed/completed/cancelled)
  - `start_date` (optional): Filter by start date (YYYY-MM-DD)
  - `end_date` (optional): Filter by end date (YYYY-MM-DD)
  - `per_page` (optional): Items per page (default: 15)
  - `page` (optional): Page number (default: 1)

**Example Request:**
```http
GET /api/bookings?status=pending&per_page=10&page=1
Authorization: Bearer your_jwt_token_here
```

**Success Response (200 OK):**
```json
{
    "status": "success",
    "data": [
        {
            "id": 1,
            "user_id": 1,
            "jenis_service_id": 1,
            "alamat": "Jl. Contoh No. 123, Jakarta",
            "service_date": "2025-11-15",
            "duration": 2,
            "preferred_gender": "female",
            "status": "pending",
            "catatan": "Mohon bawa peralatan lengkap",
            "total_harga": 100000,
            "created_at": "2025-11-10T11:30:00.000000Z",
            "updated_at": "2025-11-10T11:30:00.000000Z",
            "jenis_service": {
                "id": 1,
                "kode_service": "S-ART",
                "nama_service": "Asisten Rumah Tangga",
                "deskripsi": "Membantu pekerjaan rumah tangga harian",
                "harga": 50000,
                "estimasi_waktu": 1
            }
        }
    ],
    "links": {
        "first": "http://localhost:8000/api/bookings?page=1",
        "last": "http://localhost:8000/api/bookings?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "path": "http://localhost:8000/api/bookings",
        "per_page": 15,
        "to": 1,
        "total": 1
    }
}
```

### Get Booking Details

Retrieve details of a specific booking.

- **URL**: `/api/bookings/{id}`
- **Method**: `GET`
- **Authentication**: Required (Bearer Token)
- **URL Parameters**:
  - `id` (required): The ID of the booking to retrieve

**Example Request:**
```http
GET /api/bookings/1
Authorization: Bearer your_jwt_token_here
```

**Success Response (200 OK):**
```json
{
    "status": "success",
    "data": {
        "id": 1,
        "user_id": 1,
        "jenis_service_id": 1,
        "alamat": "Jl. Contoh No. 123, Jakarta",
        "service_date": "2025-11-15",
        "duration": 2,
        "preferred_gender": "female",
        "status": "pending",
        "catatan": "Mohon bawa peralatan lengkap",
        "total_harga": 100000,
        "created_at": "2025-11-10T11:30:00.000000Z",
        "updated_at": "2025-11-10T11:30:00.000000Z",
        "jenis_service": {
            "id": 1,
            "kode_service": "S-ART",
            "nama_service": "Asisten Rumah Tangga",
            "deskripsi": "Membantu pekerjaan rumah tangga harian",
            "harga": 50000,
            "estimasi_waktu": 1
        },
        "worker": null,
        "reviews": []
    }
}
```

### Cancel Booking

Cancel a pending booking.

- **URL**: `/api/bookings/{id}/cancel`
- **Method**: `POST`
- **Authentication**: Required (Bearer Token)
- **URL Parameters**:
  - `id` (required): The ID of the booking to cancel

**Example Request:**
```http
POST /api/bookings/1/cancel
Authorization: Bearer your_jwt_token_here
```

**Success Response (200 OK):**
```json
{
    "status": "success",
    "message": "Pesanan berhasil dibatalkan",
    "data": {
        "id": 1,
        "status": "cancelled"
    }
}
```
```

## Petugas Profile

### Submit Petugas Profile

- **URL**: `/api/form-profile-petugas`
- **Method**: `POST`
- **Authentication**: Required (Bearer Token)
- **Content-Type**: `multipart/form-data`

**Request Body (Form Data):**
- `ktp_number` (required, string, 16 digits): Nomor KTP
- `ktp_photo` (required, file): Foto KTP (max 2MB, jpeg/png/jpg)
- `selfie_with_ktp` (required, file): Foto selfie dengan KTP (max 2MB, jpeg/png/jpg)
- `full_name` (required, string): Nama lengkap
- `date_of_birth` (required, date): Tanggal lahir (YYYY-MM-DD)
- `phone_number` (required, string): Nomor telepon
- `address` (required, string): Alamat lengkap

**Headers:**
\`\`\`
Authorization: Bearer your_token_here
Accept: application/json
\`\`\`

**Success Response (201 Created):**
\`\`\`json
{
    "status": "success",
    "message": "Profil petugas berhasil diajukan. Menunggu verifikasi admin.",
    "data": {
        "id": 1,
        "user_id": 1,
        "ktp_number": "1234567890123456",
        "full_name": "John Doe",
        "date_of_birth": "1990-01-01",
        "phone_number": "081234567890",
        "address": "Jl. Contoh No. 123, Jakarta",
        "status": "pending",
        "ktp_photo_path": "petugas/ktp/abc123.jpg",
        "selfie_with_ktp_path": "petugas/selfie/def456.jpg",
        "created_at": "2025-11-10T02:30:00.000000Z",
        "updated_at": "2025-11-10T02:30:00.000000Z"
    }
}
\`\`\`

**Error Response (400 Bad Request - Already Submitted):**
\`\`\`json
{
    "status": "error",
    "message": "Anda sudah mengajukan verifikasi petugas sebelumnya"
}
\`\`\`

## Error Responses

### 401 Unauthorized
\`\`\`json
{
    "status": "error",
    "message": "Unauthenticated."
}
\`\`\`

### 422 Unprocessable Entity (Validation Error)
\`\`\`json
{
    "status": "error",
    "message": "Validasi gagal",
    "errors": {
        "email": [
            "Email harus diisi"
        ],
        "password": [
            "Password harus diisi"
        ]
    }
}
\`\`\`

### 500 Internal Server Error
\`\`\`json
{
    "status": "error",
    "message": "Terjadi kesalahan",
    "error": "Error message details (only in development)"
}
\`\`\`

## Setup

1. Clone the repository
2. Install dependencies: `composer install`
3. Copy `.env.example` to `.env` and configure your environment
4. Generate application key: `php artisan key:generate`
5. Run migrations: `php artisan migrate`
6. Link storage: `php artisan storage:link`
7. Start the development server: `php artisan serve`

## License

This project is open-source and available under the [MIT License](LICENSE).
