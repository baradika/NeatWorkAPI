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

---

# API Documentation

Base URL: `http://127.0.0.1:8000/api`

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
