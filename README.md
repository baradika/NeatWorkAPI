# NeatWork API

This is the API documentation for the NeatWork application, a platform that connects users with various home services.

## Service Types

### Available Services

| Kode Service | Nama Layanan | Deskripsi | Harga |
|--------------|--------------|-----------|-------|
| S-ART | Asisten Rumah Tangga | Membantu pekerjaan rumah tangga harian | Rp 50,000/jam |
| S-DC | Deep Cleaning | Pembersihan menyeluruh ruangan | Rp 75,000/jam |
| S-LN | Laundry | Jasa laundry dan setrika pakaian | Rp 40,000/kg |
| S-TK | Tukang Kebun | Perawatan dan perbaikan taman | Rp 60,000/jam |
| S-BB | Baby Sitter | Pengasuhan dan perawatan anak | Rp 80,000/jam |

## Authentication

### Register a New User

- **URL**: `/api/auth/register`
- **Method**: `POST`
- **Authentication**: Not required

**Request Body (JSON):**
\`\`\`json
{
    "email": "user@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "role": "pelanggan"
}
\`\`\`

**Success Response (201 Created):**
\`\`\`json
{
    "status": "success",
    "message": "Registrasi berhasil",
    "data": {
        "id_user": 1,
        "nama": "user",
        "email": "user@example.com",
        "role": "pelanggan",
        "created_at": "2025-11-10T02:30:00.000000Z"
    }
}
\`\`\`

### Login

- **URL**: `/api/auth/login`
- **Method**: `POST`
- **Authentication**: Not required

**Request Body (JSON):**
\`\`\`json
{
    "email": "user@example.com",
    "password": "password123"
}
\`\`\`

**Success Response (200 OK):**
\`\`\`json
{
    "status": "success",
    "message": "Login berhasil",
    "data": {
        "id_user": 1,
        "nama": "user",
        "email": "user@example.com",
        "role": "pelanggan",
        "created_at": "2025-11-10T02:30:00.000000Z"
    },
    "token": "1|abcdefghijklmnopqrstuvwxyz"
}
```

## Booking Services

### Create New Booking

- **URL**: `/api/bookings`
- **Method**: `POST`
- **Authentication**: Required (Bearer Token)
- **Content-Type**: `application/json`

**Request Body (JSON):**
```json
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

- **URL**: `/api/bookings`
- **Method**: `GET`
- **Authentication**: Required (Bearer Token)

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
    ]
}
```

### Get Booking Details

- **URL**: `/api/bookings/{id}`
- **Method**: `GET`
- **Authentication**: Required (Bearer Token)

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
        }
    }
}
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
