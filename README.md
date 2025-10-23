

# API Documentation

Base URL: `http://127.0.0.1:8000/api`

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
