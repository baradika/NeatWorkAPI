

# API Documentation

Base URL: `http://127.0.0.1:8000/api`

## ERD (Schema Overview)

Mermaid ER diagram for core entities (admin/mitra/pelanggan cleaning platform) based on current models/migrations:

```mermaid
erDiagram
  USERS ||--o{ JADWAL_PETUGAS : has
  USERS ||--o{ PEMESANAN : pelanggan
  USERS ||--o{ PEMESANAN : petugas
  USERS ||--|| PETUGAS_PROFILES : has
  USERS ||--o{ SERVICE_AREAS : has
  USERS ||--o{ PETUGAS_SERVICES : offers

  SERVICE_CATEGORIES ||--o{ SERVICE_CATEGORIES : parent_children
  SERVICE_CATEGORIES ||--o{ SERVICES : has

  SERVICES ||--o{ PETUGAS_SERVICES : by
  SERVICES ||--o{ ORDER_ITEMS : in

  JADWAL_PETUGAS ||--o{ PEMESANAN : used_by

  PEMESANAN ||--o{ ORDER_ITEMS : has
  PEMESANAN ||--o{ ORDER_STATUS_HISTORIES : has
  PEMESANAN ||--|| RATING_PESANAN : has
  PEMESANAN ||--|| ADDRESSES : lokasi (morph)

  PETUGAS_PROFILES ||--o{ PETUGAS_DOCUMENTS : has

  USERS {
    int id_user PK
    string nama
    string email
    string password
    enum role  "admin|petugas|pelanggan"
    string no_hp
    text alamat
    decimal rating
    timestamp created_at
  }
  JADWAL_PETUGAS {
    int id_jadwal PK
    int id_petugas FK
    date tanggal
    time waktu_mulai
    time waktu_selesai
    enum status "tersedia|dipesan|selesai"
  }
  PEMESANAN {
    int id_pemesanan PK
    int id_pelanggan FK
    int id_petugas FK
    int id_jadwal FK
    text lokasi
    text catatan
    enum status "menunggu|dikonfirmasi|selesai|dibatalkan"
    timestamp tanggal_pesan
  }
  RATING_PESANAN {
    int id_rating PK
    int id_pemesanan FK
    tinyint rating
    text ulasan
    timestamp created_at
  }
  SERVICE_CATEGORIES {
    int id_category PK
    string nama
    text deskripsi
    int parent_id FK
    bool aktif
  }
  SERVICES {
    int id_service PK
    int id_category FK
    string nama
    text deskripsi
    enum satuan "jam|m2|unit"
    int durasi_default_menit
    bool aktif
  }
  PETUGAS_SERVICES {
    int id PK
    int id_petugas FK
    int id_service FK
    decimal harga_satuan
    int min_order_qty
    bool aktif
  }
  PETUGAS_PROFILES {
    int id_profile PK
    int id_user FK
    string ktp_no
    string foto_ktp
    string selfie_ktp
    date tanggal_lahir
    enum jenis_kelamin "L|P"
    enum status_verifikasi "menunggu|diterima|ditolak"
    text bio
    tinyint pengalaman_tahun
  }
  PETUGAS_DOCUMENTS {
    int id_document PK
    int id_profile FK
    string tipe
    string file_url
    enum status "menunggu|diterima|ditolak"
  }
  SERVICE_AREAS {
    int id_area PK
    int id_petugas FK
    string kecamatan
    string kota
    decimal radius_km
    bool aktif
  }
  ORDER_ITEMS {
    int id_item PK
    int id_pemesanan FK
    int id_service FK
    int qty
    decimal harga_satuan
    decimal total
  }
  ORDER_STATUS_HISTORIES {
    int id_history PK
    int id_pemesanan FK
    string status_from
    string status_to
    text keterangan
    timestamp created_at
  }
  ADDRESSES {
    int id_address PK
    string addressable_type
    int addressable_id
    string label
    text alamat
    decimal latitude
    decimal longitude
    text catatan
  }
```

### Migrate schema

```bash
php artisan migrate
```

Jika ingin bersih ulang dengan seeder default yang sudah ada:

```bash
php artisan migrate:fresh --seed
```

## Auth

- **POST /auth/register**
  - Registers a customer (role: `pelanggan`).
  - Request body:
```json
{
  "email": "customer@example.com",
  "password": "secret123",
  "password_confirmation": "secret123"
}
```
  - Example response (201):
```json
{
  "id_user": 4,
  "nama": "customer",
  "email": "customer@example.com",
  "role": "pelanggan",
  "no_hp": null,
  "alamat": null,
  "rating": null,
  "created_at": "2025-10-15 08:20:00"
}
```

## Users

- **GET /users**
- **GET /users/{id}**
- **POST /users**
```json
{
  "nama": "Admin Utama",
  "email": "admin@cleaning.com",
  "password": "admin123",
  "role": "admin",
  "no_hp": "081234567890",
  "alamat": "Jakarta",
  "rating": 4.5
}
```
- **PATCH /users/{id}** same body as POST (all fields optional)
- **DELETE /users/{id}**

Example response:
```json
{
  "id_user": 1,
  "nama": "Admin Utama",
  "email": "admin@cleaning.com",
  "role": "admin",
  "no_hp": "081234567890",
  "alamat": "Jakarta",
  "rating": 4.5,
  "created_at": "2025-10-14 19:12:28"
}
```

## Jadwal Petugas

- **GET /jadwal-petugas**
- **GET /jadwal-petugas/{id}**
- **POST /jadwal-petugas**
```json
{
  "id_petugas": 2,
  "tanggal": "2025-10-16",
  "waktu_mulai": "08:00:00",
  "waktu_selesai": "12:00:00",
  "status": "tersedia"
}
```
- **PATCH /jadwal-petugas/{id}** same body as POST (all fields optional)
- **DELETE /jadwal-petugas/{id}**

Example response:
```json
{
  "id_jadwal": 1,
  "id_petugas": 2,
  "tanggal": "2025-10-16",
  "waktu_mulai": "08:00:00",
  "waktu_selesai": "12:00:00",
  "status": "tersedia"
}
```

## Pemesanan

- **GET /pemesanan**
- **GET /pemesanan/{id}**
- **POST /pemesanan**
```json
{
  "id_pelanggan": 3,
  "id_petugas": 2,
  "id_jadwal": 1,
  "lokasi": "Jakarta Timur",
  "catatan": "Datang tepat waktu",
  "status": "menunggu"
}
```
- **PATCH /pemesanan/{id}** same body as POST (all fields optional)
- **DELETE /pemesanan/{id}**

Example response:
```json
{
  "id_pemesanan": 1,
  "id_pelanggan": 3,
  "id_petugas": 2,
  "id_jadwal": 1,
  "lokasi": "Jakarta Timur",
  "catatan": "Datang tepat waktu",
  "status": "menunggu",
  "tanggal_pesan": "2025-10-15 07:41:00"
}
```

## Rating Pesanan

- **GET /rating-pesanan**
- **GET /rating-pesanan/{id}**
- **POST /rating-pesanan**
```json
{
  "id_pemesanan": 1,
  "rating": 5,
  "ulasan": "Sangat memuaskan"
}
```
- **PATCH /rating-pesanan/{id}** same body as POST (all fields optional)
- **DELETE /rating-pesanan/{id}**

Example response:
```json
{
  "id_rating": 1,
  "id_pemesanan": 1,
  "rating": 5,
  "ulasan": "Sangat memuaskan",
  "created_at": "2025-10-15 07:45:00"
}
```

## Notes

- **Validation** is applied in controllers; responses are JSON.
- **Auth** is not enabled; endpoints are public by default. Consider adding Sanctum if needed.
