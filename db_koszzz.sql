-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 05 Jan 2026 pada 10.00
-- Versi server: 8.0.30
-- Versi PHP: 8.5.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Basis data: `db_koszzz`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `alamat_kos`
--

CREATE TABLE `alamat_kos` (
  `id` bigint UNSIGNED NOT NULL,
  `kos_id` bigint UNSIGNED NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `provinsi` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kabupaten` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kecamatan` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan_alamat` text COLLATE utf8mb4_unicode_ci,
  `lat` decimal(10,7) DEFAULT NULL,
  `lon` decimal(10,7) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `booking`
--

CREATE TABLE `booking` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `kos_id` bigint UNSIGNED NOT NULL,
  `kamar_id` bigint UNSIGNED DEFAULT NULL,
  `kamar_snapshot` json DEFAULT NULL,
  `tanggal_checkin` date DEFAULT NULL,
  `kode_checkin` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `durasi_sewa` int DEFAULT NULL,
  `periode_sewa` enum('harian','mingguan','bulanan','3_bulanan','6_bulanan','tahunan') COLLATE utf8mb4_unicode_ci DEFAULT 'bulanan',
  `total_harga` decimal(12,2) DEFAULT NULL,
  `harga_snapshot` json DEFAULT NULL,
  `status` enum('menunggu_konfirmasi','diterima','ditolak','menunggu_pembayaran','dibatalkan','selesai') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan_penyewa` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `chat_message`
--

CREATE TABLE `chat_message` (
  `id` bigint UNSIGNED NOT NULL,
  `chat_room_id` bigint UNSIGNED NOT NULL,
  `sender_id` bigint UNSIGNED NOT NULL,
  `pesan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `chat_room`
--

CREATE TABLE `chat_room` (
  `id` bigint UNSIGNED NOT NULL,
  `kos_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `fasilitas`
--

CREATE TABLE `fasilitas` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_fasilitas` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kategori` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `favorit`
--

CREATE TABLE `favorit` (
  `user_id` bigint UNSIGNED NOT NULL,
  `kos_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `foto_kos`
--

CREATE TABLE `foto_kos` (
  `id` bigint UNSIGNED NOT NULL,
  `kos_id` bigint UNSIGNED NOT NULL,
  `tipe` enum('bangunan','kamar') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `path_foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `harga_sewa`
--

CREATE TABLE `harga_sewa` (
  `id` bigint UNSIGNED NOT NULL,
  `kamar_id` bigint UNSIGNED NOT NULL,
  `periode` enum('harian','mingguan','bulanan','3_bulanan','6_bulanan','tahunan') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `harga` decimal(12,2) DEFAULT NULL,
  `denda_per_hari` decimal(12,2) DEFAULT NULL,
  `batas_hari_denda` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kamar`
--

CREATE TABLE `kamar` (
  `id` bigint UNSIGNED NOT NULL,
  `kos_id` bigint UNSIGNED NOT NULL,
  `nama_kamar` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lantai` int DEFAULT NULL,
  `ukuran_kamar` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('tersedia','terisi') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kontrak`
--

CREATE TABLE `kontrak` (
  `id` bigint UNSIGNED NOT NULL,
  `booking_id` bigint UNSIGNED NOT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `status` enum('aktif','nonaktif','selesai') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_tagihan` decimal(12,2) DEFAULT NULL,
  `denda_aktif` tinyint(1) DEFAULT NULL,
  `batas_hari_denda` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kos`
--

CREATE TABLE `kos` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `nama_kos` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipe_kos` enum('putra','putri','campur') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `mean_rating` double NOT NULL DEFAULT '0',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `total_kamar` int NOT NULL DEFAULT '0',
  `kamar_tersedia` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kos_fasilitas`
--

CREATE TABLE `kos_fasilitas` (
  `kos_id` bigint UNSIGNED NOT NULL,
  `fasilitas_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kos_peraturan`
--

CREATE TABLE `kos_peraturan` (
  `kos_id` bigint UNSIGNED NOT NULL,
  `peraturan_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_12_17_143646_create_kos_table', 1),
(5, '2025_12_17_143700_create_alamat_kos_table', 1),
(6, '2025_12_17_143708_create_fasilitas_table', 1),
(7, '2025_12_17_143717_create_peraturan_table', 1),
(8, '2025_12_17_143739_create_kamar_table', 1),
(9, '2025_12_17_143746_create_harga_sewa_table', 1),
(10, '2025_12_17_143755_create_foto_kos_table', 1),
(11, '2025_12_17_143804_create_kos_fasilitas_table', 1),
(12, '2025_12_17_143810_create_kos_peraturan_table', 1),
(13, '2025_12_17_143816_create_booking_table', 1),
(14, '2025_12_17_143822_create_pembayaran_table', 1),
(15, '2025_12_17_143828_create_kontrak_table', 1),
(16, '2025_12_17_143835_create_favorit_table', 1),
(17, '2025_12_17_143842_create_ulasan_table', 1),
(18, '2025_12_17_143850_create_chat_room_table', 1),
(19, '2025_12_17_143856_create_chat_message_table', 1),
(20, '2025_12_20_125306_add_email_verified_at_remember_token_to_users_table', 1),
(21, '2025_12_20_130659_add_total_kamar_kamar_tersedia_to_kos_table', 1),
(22, '2025_12_25_100803_add_ktp_path_to_users_table', 1),
(23, '2025_12_25_134042_update_periode_sewa_enum_in_booking_table', 1),
(24, '2025_12_26_130100_complete_fix_booking_snapshot_and_relation', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` bigint UNSIGNED NOT NULL,
  `booking_id` bigint UNSIGNED NOT NULL,
  `tanggal_bayar` date DEFAULT NULL,
  `total_bayar` decimal(12,2) DEFAULT NULL,
  `metode_bayar` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','success','failed') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `peraturan`
--

CREATE TABLE `peraturan` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_peraturan` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ulasan`
--

CREATE TABLE `ulasan` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `kos_id` bigint UNSIGNED NOT NULL,
  `rating` int DEFAULT NULL,
  `komentar` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ktp_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_hp` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_profil` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('pemilik','penyewa') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indeks untuk tabel yang dibuang
--

--
-- Indeks untuk tabel `alamat_kos`
--
ALTER TABLE `alamat_kos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `alamat_kos_kos_id_unique` (`kos_id`);

--
-- Indeks untuk tabel `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_kode_checkin_unique` (`kode_checkin`),
  ADD KEY `booking_user_id_foreign` (`user_id`),
  ADD KEY `booking_kos_id_foreign` (`kos_id`),
  ADD KEY `booking_kamar_id_foreign` (`kamar_id`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `chat_message`
--
ALTER TABLE `chat_message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_message_chat_room_id_foreign` (`chat_room_id`),
  ADD KEY `chat_message_sender_id_foreign` (`sender_id`);

--
-- Indeks untuk tabel `chat_room`
--
ALTER TABLE `chat_room`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_room_kos_id_foreign` (`kos_id`),
  ADD KEY `chat_room_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `fasilitas`
--
ALTER TABLE `fasilitas`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `favorit`
--
ALTER TABLE `favorit`
  ADD PRIMARY KEY (`user_id`,`kos_id`),
  ADD KEY `favorit_kos_id_foreign` (`kos_id`);

--
-- Indeks untuk tabel `foto_kos`
--
ALTER TABLE `foto_kos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `foto_kos_kos_id_foreign` (`kos_id`);

--
-- Indeks untuk tabel `harga_sewa`
--
ALTER TABLE `harga_sewa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `harga_sewa_kamar_id_foreign` (`kamar_id`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kamar`
--
ALTER TABLE `kamar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kamar_kos_id_foreign` (`kos_id`);

--
-- Indeks untuk tabel `kontrak`
--
ALTER TABLE `kontrak`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kontrak_booking_id_foreign` (`booking_id`);

--
-- Indeks untuk tabel `kos`
--
ALTER TABLE `kos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kos_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `kos_fasilitas`
--
ALTER TABLE `kos_fasilitas`
  ADD PRIMARY KEY (`kos_id`,`fasilitas_id`),
  ADD KEY `kos_fasilitas_fasilitas_id_foreign` (`fasilitas_id`);

--
-- Indeks untuk tabel `kos_peraturan`
--
ALTER TABLE `kos_peraturan`
  ADD PRIMARY KEY (`kos_id`,`peraturan_id`),
  ADD KEY `kos_peraturan_peraturan_id_foreign` (`peraturan_id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pembayaran_booking_id_foreign` (`booking_id`);

--
-- Indeks untuk tabel `peraturan`
--
ALTER TABLE `peraturan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `ulasan`
--
ALTER TABLE `ulasan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ulasan_user_id_kos_id_unique` (`user_id`,`kos_id`),
  ADD KEY `ulasan_kos_id_foreign` (`kos_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `alamat_kos`
--
ALTER TABLE `alamat_kos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `booking`
--
ALTER TABLE `booking`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `chat_message`
--
ALTER TABLE `chat_message`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `chat_room`
--
ALTER TABLE `chat_room`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `fasilitas`
--
ALTER TABLE `fasilitas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `foto_kos`
--
ALTER TABLE `foto_kos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `harga_sewa`
--
ALTER TABLE `harga_sewa`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kamar`
--
ALTER TABLE `kamar`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kontrak`
--
ALTER TABLE `kontrak`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kos`
--
ALTER TABLE `kos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `peraturan`
--
ALTER TABLE `peraturan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `ulasan`
--
ALTER TABLE `ulasan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `alamat_kos`
--
ALTER TABLE `alamat_kos`
  ADD CONSTRAINT `alamat_kos_kos_id_foreign` FOREIGN KEY (`kos_id`) REFERENCES `kos` (`id`);

--
-- Ketidakleluasaan untuk tabel `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_kamar_id_foreign` FOREIGN KEY (`kamar_id`) REFERENCES `kamar` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `booking_kos_id_foreign` FOREIGN KEY (`kos_id`) REFERENCES `kos` (`id`),
  ADD CONSTRAINT `booking_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `chat_message`
--
ALTER TABLE `chat_message`
  ADD CONSTRAINT `chat_message_chat_room_id_foreign` FOREIGN KEY (`chat_room_id`) REFERENCES `chat_room` (`id`),
  ADD CONSTRAINT `chat_message_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `chat_room`
--
ALTER TABLE `chat_room`
  ADD CONSTRAINT `chat_room_kos_id_foreign` FOREIGN KEY (`kos_id`) REFERENCES `kos` (`id`),
  ADD CONSTRAINT `chat_room_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `favorit`
--
ALTER TABLE `favorit`
  ADD CONSTRAINT `favorit_kos_id_foreign` FOREIGN KEY (`kos_id`) REFERENCES `kos` (`id`),
  ADD CONSTRAINT `favorit_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `foto_kos`
--
ALTER TABLE `foto_kos`
  ADD CONSTRAINT `foto_kos_kos_id_foreign` FOREIGN KEY (`kos_id`) REFERENCES `kos` (`id`);

--
-- Ketidakleluasaan untuk tabel `harga_sewa`
--
ALTER TABLE `harga_sewa`
  ADD CONSTRAINT `harga_sewa_kamar_id_foreign` FOREIGN KEY (`kamar_id`) REFERENCES `kamar` (`id`);

--
-- Ketidakleluasaan untuk tabel `kamar`
--
ALTER TABLE `kamar`
  ADD CONSTRAINT `kamar_kos_id_foreign` FOREIGN KEY (`kos_id`) REFERENCES `kos` (`id`);

--
-- Ketidakleluasaan untuk tabel `kontrak`
--
ALTER TABLE `kontrak`
  ADD CONSTRAINT `kontrak_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`id`);

--
-- Ketidakleluasaan untuk tabel `kos`
--
ALTER TABLE `kos`
  ADD CONSTRAINT `kos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `kos_fasilitas`
--
ALTER TABLE `kos_fasilitas`
  ADD CONSTRAINT `kos_fasilitas_fasilitas_id_foreign` FOREIGN KEY (`fasilitas_id`) REFERENCES `fasilitas` (`id`),
  ADD CONSTRAINT `kos_fasilitas_kos_id_foreign` FOREIGN KEY (`kos_id`) REFERENCES `kos` (`id`);

--
-- Ketidakleluasaan untuk tabel `kos_peraturan`
--
ALTER TABLE `kos_peraturan`
  ADD CONSTRAINT `kos_peraturan_kos_id_foreign` FOREIGN KEY (`kos_id`) REFERENCES `kos` (`id`),
  ADD CONSTRAINT `kos_peraturan_peraturan_id_foreign` FOREIGN KEY (`peraturan_id`) REFERENCES `peraturan` (`id`);

--
-- Ketidakleluasaan untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`id`);

--
-- Ketidakleluasaan untuk tabel `ulasan`
--
ALTER TABLE `ulasan`
  ADD CONSTRAINT `ulasan_kos_id_foreign` FOREIGN KEY (`kos_id`) REFERENCES `kos` (`id`),
  ADD CONSTRAINT `ulasan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
