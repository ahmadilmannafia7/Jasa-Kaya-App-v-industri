# Fitur Kemitraan PBPHH-to-PBPHH

## Overview
Fitur ini memungkinkan kolaborasi antara sesama PBPHH (industri pengolahan kayu) untuk berbagi kapasitas produksi, supply chain material, atau joint venture.

## Database Schema

### Tabel: `pbphh_partnerships`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `partnership_id` | BIGINT (PK) | Primary key |
| `requester_pbphh_id` | Foreign Key | PBPHH yang mengajukan |
| `partner_pbphh_id` | Foreign Key | PBPHH yang diajak bermitra |
| `partnership_type` | ENUM | Jenis kemitraan |
| `description` | TEXT | Deskripsi detail kemitraan |
| `material_type` | VARCHAR(100) | Jenis material (opsional) |
| `volume_needed_m3` | FLOAT | Volume yang dibutuhkan |
| `duration_months` | VARCHAR(50) | Durasi kemitraan |
| `status` | ENUM | Status partnership |
| `rejection_reason` | TEXT | Alasan penolakan/pembatalan |
| `negotiation_notes` | TEXT | Catatan negosiasi |
| `approved_at` | TIMESTAMP | Waktu disetujui |
| `started_at` | TIMESTAMP | Waktu dimulai |
| `ended_at` | TIMESTAMP | Waktu selesai |

### Jenis Kemitraan (`partnership_type`)
1. **Pasokan Material** - Berbagi atau supply material/bahan baku
2. **Kapasitas Produksi** - Berbagi kapasitas produksi/mesin
3. **Joint Venture** - Kerjasama bisnis bersama
4. **Distribusi** - Kerjasama distribusi produk
5. **Lainnya** - Jenis kemitraan lainnya

### Status Workflow
```
1. Terkirim              → Permintaan baru
2. Ditolak               → Partner menolak (FINAL)
3. Disetujui             → Partner menyetujui
4. Dalam Negosiasi       → Proses negosiasi detail
5. Kesepakatan Dibuat    → Kesepakatan formal dibuat
6. Aktif                 → Kemitraan berjalan
7. Selesai               → Kemitraan selesai (FINAL)
8. Dibatalkan            → Dibatalkan (FINAL)
```

## Routes & Endpoints

### Eksplorasi Partner
- **GET** `/pbphh/industry-partners` - Lihat daftar PBPHH lain
- **GET** `/pbphh/industry-partners/{id}/detail` - Detail PBPHH partner

### Manajemen Permintaan
- **POST** `/pbphh/industry-partners/request` - Ajukan permintaan kemitraan
- **GET** `/pbphh/industry-partnerships/requests` - Lihat permintaan masuk
- **POST** `/pbphh/industry-partnerships/{id}/respond` - Terima/tolak permintaan

### Manajemen Kemitraan
- **GET** `/pbphh/industry-partnerships` - Lihat semua kemitraan
- **POST** `/pbphh/industry-partnerships/{id}/cancel` - Batalkan permintaan
- **PUT** `/pbphh/industry-partnerships/{id}/negotiate` - Update negosiasi
- **POST** `/pbphh/industry-partnerships/{id}/finalize` - Aktifkan kemitraan

## Controller Methods

### `PbphhController` - Metode Baru

1. **exploreIndustryPartners()** - Eksplorasi PBPHH lain dengan filter
2. **getIndustryPartnerDetail($id)** - API untuk detail partner
3. **requestIndustryPartnership()** - Ajukan permintaan kemitraan
4. **industryPartnerships()** - Lihat daftar kemitraan (sent & received)
5. **industryPartnershipRequests()** - Lihat inbox permintaan masuk
6. **respondToIndustryPartnership($id)** - Approve/reject permintaan
7. **cancelIndustryPartnership($id)** - Batalkan permintaan
8. **negotiateIndustryPartnership($id)** - Update catatan negosiasi
9. **finalizeIndustryPartnership($id)** - Aktifkan kemitraan

## Model: PbphhPartnership

### Relasi
- `requester()` - belongsTo PbphhProfile (yang mengajukan)
- `partner()` - belongsTo PbphhProfile (yang diajak)

### Scopes
- `sentBy($pbphhId)` - Filter permintaan yang dikirim
- `receivedBy($pbphhId)` - Filter permintaan yang diterima
- `withStatus($status)` - Filter berdasarkan status
- `active()` - Filter partnership aktif

### Helper Methods
- `isNegotiable()` - Cek apakah bisa dinegosiasikan
- `isCancellable()` - Cek apakah bisa dibatalkan

### Accessors
- `getStatusBadgeAttribute()` - Warna badge untuk status
- `getFormattedVolumeAttribute()` - Format volume dengan satuan

## Relasi di PbphhProfile

Tambahkan 2 relasi baru:
```php
public function sentPartnerships(): HasMany
public function receivedPartnerships(): HasMany
```

## Dashboard Statistics

Dashboard PBPHH sekarang menampilkan:
- `industry_partnerships_sent` - Total yang dikirim
- `industry_partnerships_received` - Total yang diterima
- `industry_partnerships_active` - Total yang aktif
- `industry_partnerships_pending` - Permintaan masuk yang pending

## Use Cases

### Use Case 1: Pasokan Material
```
PT Kayu Jati membutuhkan pasokan kayu mahoni 100 m³/bulan.
→ Ajukan permintaan ke PT Mahoni Makmur (tipe: Pasokan Material)
→ PT Mahoni Makmur approve
→ Negosiasi harga dan jadwal
→ Finalize → Status: Aktif
```

### Use Case 2: Joint Venture
```
PT Furniture Indah ingin joint venture dengan PT Ekspor Kayu.
→ Ajukan permintaan (tipe: Joint Venture)
→ Partner approve
→ Negosiasi detail bisnis
→ Finalize dengan kesepakatan formal
→ Status: Aktif
```

### Use Case 3: Kapasitas Produksi
```
PT Pengolahan A kelebihan order, butuh bantuan produksi.
→ Ajukan ke PT Pengolahan B (tipe: Kapasitas Produksi)
→ Tentukan volume dan durasi
→ Partner approve dan negosiasi jadwal
→ Aktifkan kemitraan
```

## Validasi & Business Rules

1. **Tidak bisa mengajukan ke diri sendiri**
2. **Tidak bisa duplikasi permintaan aktif** ke partner yang sama
3. **Hanya bisa cancel** jika status: Terkirim, Disetujui, atau Dalam Negosiasi
4. **Hanya bisa negosiasi** jika status: Terkirim, Disetujui, atau Dalam Negosiasi
5. **Partner harus status Approved** di sistem
6. **Respond hanya oleh partner** yang diminta (authorization check)

## Migrasi Database

Jalankan migration untuk membuat tabel:

```bash
php artisan migrate
```

File migration:
- `2025_12_18_000001_create_pbphh_partnerships_table.php`

## Testing Checklist

- [ ] PBPHH bisa eksplorasi partner dengan filter
- [ ] PBPHH bisa ajukan permintaan kemitraan
- [ ] Partner bisa approve/reject permintaan
- [ ] Kedua pihak bisa negosiasi
- [ ] PBPHH bisa cancel permintaan sebelum finalize
- [ ] Dashboard menampilkan statistik kemitraan industri
- [ ] Validasi mencegah duplikasi dan self-partnership
- [ ] Status workflow berjalan sesuai alur

## Future Enhancements

1. **Notifikasi real-time** untuk setiap perubahan status
2. **Document upload** untuk kesepakatan formal (MoU, kontrak)
3. **Rating & review** setelah kemitraan selesai
4. **Chat/messaging** untuk komunikasi langsung
5. **Dashboard CDK** untuk monitoring kemitraan industri
6. **Ekspor laporan** kemitraan industri per region
7. **Financial tracking** untuk nilai kemitraan
8. **Auto-expire** untuk kemitraan yang sudah lewat durasi

## Catatan Penting

- Fitur ini **independen** dari kemitraan KTHR-PBPHH
- Tidak memerlukan **fasilitasi CDK** (direct B2B)
- Status workflow **lebih fleksibel** untuk negosiasi
- Dashboard terpisah untuk **industry partnerships**
