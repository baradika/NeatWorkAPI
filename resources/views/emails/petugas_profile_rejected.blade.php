<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pembaruan Verifikasi Petugas</title>
  <style>
    body{font-family:Arial,Helvetica,sans-serif;background:#f6f7fb;margin:0;padding:24px;color:#111827}
    .card{max-width:640px;margin:0 auto;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 8px 24px rgba(17,24,39,.08)}
    .header{background:#ef4444;color:#fff;padding:20px 24px}
    .brand{display:flex;align-items:center;gap:12px}
    .brand-title{font-size:18px;font-weight:700;letter-spacing:.3px}
    .content{padding:24px}
    .btn{display:inline-block;background:#0ea5e9;color:#fff;text-decoration:none;padding:10px 16px;border-radius:8px;font-weight:600}
    .muted{color:#6b7280;font-size:14px}
    .reason{background:#fef2f2;border:1px solid #fecaca;color:#7f1d1d;padding:12px;border-radius:8px}
    .footer{padding:16px 24px;border-top:1px solid #eef2f7;color:#6b7280;font-size:12px}
  </style>
</head>
<body>
  <div class="card">
    <div class="header">
      <div class="brand">
        @if(!empty($logoCid))
          <img src="{{ $logoCid }}" alt="NeatWork" width="28" height="28" style="border-radius:6px;background:#fff;padding:2px">
        @else
          <div class="brand-title">NeatWork</div>
        @endif
      </div>
    </div>
    <div class="content">
      <p>Halo {{ $fullName }},</p>
      <p>Terima kasih atas pengajuan Anda sebagai Petugas di NeatWork. Setelah proses peninjauan, saat ini pengajuan Anda <strong>belum dapat kami setujui</strong> ({{ $date }}).</p>
      @if(!empty($reason))
      <div class="reason">
        <strong>Alasan penolakan:</strong>
        <div>{{ $reason }}</div>
      </div>
      @endif
      <p>Anda dapat memperbarui data profil dan mengajukan kembali melalui tautan berikut:</p>
      <p>
        <a class="btn" href="{{ config('app.url') }}/auth/profile-petugas" target="_blank" rel="noopener">Perbarui Pengajuan</a>
      </p>
      <p class="muted">Jika tombol di atas tidak berfungsi, salin dan tempel URL berikut ke peramban Anda:<br>
        {{ config('app.url') }}/auth/profile-petugas
      </p>
      <p>Apabila Anda membutuhkan bantuan lebih lanjut, silakan balas email ini atau hubungi tim dukungan kami.</p>
      <p>Terima kasih,<br>Tim NeatWork</p>
    </div>
    <div class="footer">
      Email ini dikirim otomatis. Mohon tidak membalas email ini.
    </div>
  </div>
</body>
</html>
