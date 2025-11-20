# BISINDO Sign Language Learning Platform

## Ikhtisar / Overview
Platform pembelajaran BISINDO berbasis web/mobile yang memadukan kamus interaktif, game edukatif, dan computer vision untuk memvalidasi gestur tangan pengguna. Dirancang modular agar backend (CodeIgniter/PHP) dapat melayani aplikasi web SPA maupun mobile (Flutter/React Native).

## Tujuan Produk / Product Goals
- Mempercepat pembelajaran BISINDO melalui kombinasi referensi kamus, latihan aktif, dan umpan balik instan.
- Menyediakan kanal resmi bagi komunitas/organisasi untuk mengelola konten kamus dan kurikulum.
- Memotivasi pengguna dengan gamifikasi (skor, badge, leaderboard mingguan/musiman).

## Persona & Peran Pengguna
1. **Learner / Pelajar**: registrasi, belajar, bermain game, melacak progres.
2. **Content Manager / Admin**: unggah/kurasi entri kamus, paket latihan, badge.
3. **Computer Vision Service**: microservice Python (FastAPI) yang memproses video/stream gestur.

## Fitur Utama
### 1. Autentikasi & Onboarding
- Registrasi email/nomor telepon + verifikasi OTP.
- Login + refresh token, dukungan OAuth komunitas di fase lanjut.
- Profil pengguna: level, preferensi tangan, aksesibilitas (warna kontras tinggi, teks besar).

### 2. Kamus BISINDO
- Pencarian teks, alfabet finger spelling, filter kategori (alfabet, angka, frasa harian, ekspresi emosi, kata kerja, dsb).
- Tiap entri: video demonstrasi, deskripsi teks, tips posisi tangan, variasi regional.
- Favorit & riwayat pencarian.

### 3. Pengelolaan Kamus (Admin)
- CRUD entri (judul, slug, kategori, tingkat kesulitan, video utama, GIF, metadata).
- Versi & publikasi (draft → review → published) agar konten terjamin.
- Bulk import CSV/JSON + unggah batch video ke storage (S3/MinIO).

### 4. Game & Modul Latihan
- **Sign Sprint**: multiple choice cepat; pengguna melihat video/animasi lalu memilih arti atau sebaliknya.
- **Gesture Coach (CV)**: pengguna meniru gestur; kamera menangkap lalu model CV memberi skor kesesuaian.
- **Finger Spell Builder**: pengguna mengeja kata dengan rangkaian gestur alfabet.
- Timer, life points, multiplier combo, adaptive difficulty berdasarkan mastery map.

### 5. Leaderboard & Badge
- Leaderboard global, komunitas, teman (filter harian/mingguan/sepanjang masa).
- Badge bertingkat (perunggu/perak/emas) berbasis XP, streak, accuracy CV, kontribusi konten.
- Riwayat pencapaian + share sosial.

### 6. Analitik & Insight
- Heatmap gestur yang sering gagal untuk rekomendasi latihan otomatis.
- Dashboard admin untuk memantau retensi, completion rate game, akurasi model CV.

## Arsitektur Sistem
```
[Client Web/Mobile]
        |  (HTTPS / WebSocket)
[API Gateway / Load Balancer]
        |-- REST API (CodeIgniter + PHP 8.x)
        |     |- Auth, Kamus, Game logic, Leaderboard
        |     |- Event queue publisher (Redis / RabbitMQ)
        |
        |-- CV Service (Python + FastAPI + MediaPipe/YOLO)
                |- Model inference GPU/CPU
                |- WebSocket/WebRTC stream handler

[Database] PostgreSQL/MySQL (relasional)
[Object Storage] S3/MinIO untuk video/gambar
[Cache] Redis untuk session, leaderboard snapshot
[Analytics/Data Lake] optional BigQuery/Snowflake export
```
- Gunakan JWT akses + refresh, tersimpan aman (HttpOnly cookies untuk web).
- WebSocket channel untuk push skor real-time & CV feedback.
- Mikroservis CV dapat diskalakan terpisah; antrean (Redis Streams/AMQP) menangani batch evaluasi.

## Model Data Inti (skema ringkas)
- `users(id, email, password_hash, display_name, role, streak, xp_total, avatar_url, created_at)`
- `user_profiles(user_id, handedness, preferences_json, bio)`
- `dictionary_entries(id, title, slug, category, difficulty, description, tips, video_url, thumb_url, status, created_by, updated_at)`
- `dictionary_variants(id, entry_id, region, video_url, notes)`
- `lessons(id, title, type, metadata_json, is_published)`
- `games(id, code, name, mode, config_json)`
- `game_sessions(id, user_id, game_id, score, accuracy, duration, started_at, completed_at)`
- `game_events(id, session_id, prompt_id, user_answer, is_correct, cv_score, media_url)`
- `leaderboards(id, scope, period_start, period_end)` + `leaderboard_entries(leaderboard_id, user_id, score)`
- `badges(id, code, name, tier, requirement_json)` + `user_badges(user_id, badge_id, earned_at)`
- `cv_inferences(id, session_id, model_version, raw_score, landmarks_json, verdict)`

## API Garis Besar
- `POST /auth/register`, `POST /auth/login`, `POST /auth/refresh`, `POST /auth/logout`.
- `GET /dictionary?search=&category=&difficulty=` dan `GET /dictionary/{slug}`.
- Admin: `POST/PUT/DELETE /dictionary`, `POST /dictionary/import` (multipart + CSV metadata).
- `GET /games` (jenis & status), `POST /games/{code}/sessions` untuk mulai, `POST /games/{code}/sessions/{id}/events` kirim jawaban.
- CV streaming: `POST /cv/evaluate` (upload clip) atau WebSocket `/cv/stream` untuk realtime (mengirim landmarks/frames, menerima skor & instruksi).
- `GET /leaderboards?scope=global&period=weekly`, `GET /me/badges`.

## Game & Scoring Logic
- XP = base_score * difficulty_multiplier * streak_multiplier.
- CV-based game: skor gabungan antara `gesture_accuracy` (0-1), waktu respon, dan penalti kesalahan anatomi.
- Adaptive difficulty: setelah setiap sesi, update `skill_mastery` vector per kategori → engine memilih soal berikut.

## Computer Vision Pipeline
1. Capture frame (WebRTC → backend → CV service).
2. Preprocess: normalisasi warna, background subtraction opsional, resize ke 256x256.
3. Landmark detection: MediaPipe Hands + Pose; atau YOLOv8n pose model custom BISINDO.
4. Feature extraction: 3D coordinates + temporal smoothing (Kalman filter).
5. Classifier: LSTM/Transformer atau Random Forest jika dataset kecil.
6. Confidence threshold + heuristik orientation; kirim skor + feedback (benar/salah, apa yang perlu diperbaiki).
7. Logging untuk retraining (opt-in privacy, enkripsi at-rest).

## Leaderboard & Badge Engine
- Leaderboard dihitung incremental menggunakan Redis sorted set → flush ke DB periodik.
- Badge engine worker membaca event stream (user mencapai skor tertentu, streak 7 hari, kontribusi kamus ≥10, dsb) → assign badge + notifikasi.

## Keamanan & Privasi
- Password Argon2id, rate limiting login, reCAPTCHA invisible.
- Role-based access (pelajar, admin konten, super admin).
- Video CV disimpan hanya bila user setuju; gunakan anonymization (blur wajah) sebelum training.
- Audit trail untuk perubahan kamus.

## Observability
- Centralized logging (ELK/Opensearch), metrics via Prometheus + Grafana.
- CV inference latency, success rate, GPU utilization dipantau terpisah.

## Rencana Implementasi (Roadmap)
1. **MVP (8-10 minggu)**
   - Autentikasi dasar, kamus publik, Sign Sprint versi MCQ, leaderboard sederhana, badge XP.
2. **Phase 2**
   - Pengelolaan kamus lanjutan, Finger Spell Builder, admin dashboard.
3. **Phase 3**
   - Integrasi CV real-time + Gesture Coach, WebSocket feedback, analytics lanjutan.
4. **Phase 4**
   - Mobile offline mode, komunitas (kelas privat), open API untuk sekolah SLB.

## Testing & Quality
- Unit + feature test (PHPUnit) untuk API, Pest/Codeception opsional.
- Frontend e2e (Playwright/Cypress) untuk flow registrasi → main game.
- CV model validation: train/val split per signer, confusion matrix per kelas, latency benchmark.
- Accessibility audit (WCAG 2.1 AA), termasuk subtitle & narasi audio.

## Teknologi yang Disarankan
- Backend: CodeIgniter 3/4 (repo ini) atau upgrade ke CI4 untuk fitur modern.
- Frontend: React + Vite/Next.js atau Flutter untuk multi-platform.
- CV: Python 3.11, MediaPipe, PyTorch/ONNX, FastAPI, Redis message broker.
- Infra: Docker compose untuk dev; produksi gunakan Kubernetes + autoscaling CV worker.

