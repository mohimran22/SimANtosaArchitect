--
-- PostgreSQL database dump
--

-- Dumped from database version 14.18
-- Dumped by pg_dump version 14.18

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Data for Name: menus; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (1, NULL, 'Beranda', 'ti ti-home', 'dashboard', 'route', 0, true, '2025-11-07 06:28:27', '2025-11-07 06:28:27', NULL);
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (3, NULL, 'Akun', 'ti ti-user-circle', '#', 'url', 2, true, '2025-11-07 06:28:27', '2025-11-07 06:28:27', 'lihat daftar user');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (4, 3, 'Manajemen User', 'ti ti-user-circle', '/users', 'url', 0, true, '2025-11-07 06:28:27', '2025-11-07 06:28:27', 'lihat daftar user');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (5, 3, 'Manajemen Role', 'ti ti-user-check', '/roles', 'url', 1, true, '2025-11-07 06:28:27', '2025-11-07 06:28:27', 'lihat daftar role');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (6, 3, 'Manajemen Akun', 'ti ti-user', '/accounts', 'url', 2, true, '2025-11-07 06:28:27', '2025-11-07 06:28:27', 'kelola akun');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (7, NULL, 'SDM', 'ti ti-briefcase', '#', 'url', 3, true, '2025-11-07 06:28:27', '2025-11-07 06:28:27', 'lihat daftar karyawan|lihat data karyawan');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (9, 7, 'Absensi', 'ti ti-article', '/licenses', 'url', 1, true, '2025-11-07 06:28:27', '2025-11-07 06:28:27', 'lihat daftar absensi');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (10, 7, 'Pelatihan', 'ti ti-article', '/license_holders', 'url', 2, true, '2025-11-07 06:28:27', '2025-11-07 06:28:27', 'lihat daftar pelatihan');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (11, 7, 'Penilaian Kinerja', 'ti ti-article', '/accounting', 'url', 3, true, '2025-11-07 06:28:27', '2025-11-07 06:28:27', 'lihat daftar penilaian kinerja');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (12, NULL, 'Recruitment', 'ti ti-users', '#', 'url', 4, true, '2025-11-07 06:28:27', '2025-11-07 06:28:27', 'lihat daftar recruitment');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (13, 12, 'Template Lowongan Kerja', 'ti ti-article', '/recruitment', 'url', 0, true, '2025-11-07 06:28:27', '2025-11-07 06:28:27', 'lihat daftar recruitment');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (16, 14, 'Edit Profile', 'ti ti-notebook', '/customer/profile', 'url', 1, true, '2025-11-07 06:28:27', '2025-11-07 06:28:27', 'ubah data customer');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (20, 18, 'Riwayat Performa', 'ti ti-notebook', '/affiliator/history', 'url', 1, true, '2025-11-07 06:28:27', '2025-11-07 06:28:27', 'riwayat performa affiliator');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (23, 21, 'Riwayat Pembelian', 'ti ti-notebook', '/history/supplier', 'url', 1, true, '2025-11-07 06:28:27', '2025-11-07 06:28:27', 'riwayat pembelian supplier');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (24, NULL, 'Investor', 'ti ti-building-bank', '#', 'url', 8, true, '2025-11-07 06:28:27', '2025-11-07 06:28:27', 'lihat daftar investor');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (26, 24, 'Informasi Kepemilikan', 'ti ti-notebook', '/investor/modal', 'url', 1, true, '2025-11-07 06:28:28', '2025-11-07 06:28:28', 'saham investor');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (27, NULL, 'Tukang', 'ti ti-building-bank', '#', 'url', 9, true, '2025-11-07 06:28:28', '2025-11-07 06:28:28', 'lihat daftar tukang');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (29, 27, 'Riwayat Penggajian', 'ti ti-notebook', '/worker/history', 'url', 2, true, '2025-11-07 06:28:28', '2025-11-07 06:28:28', 'riwayat penggajian tukang');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (35, 33, 'Riwayat Gudang', 'ti ti-package', '/wareheouse/history', 'url', 1, true, '2025-11-07 06:28:28', '2025-11-07 06:28:28', 'riwayat transaksi gudang');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (39, NULL, 'Finance', 'ti ti-building-bank', '#', 'url', 16, true, '2025-11-07 06:28:28', '2025-11-07 06:28:28', 'lihat akun-akuntansi');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (40, 39, 'Daftar Akun Akuntansi', 'ti ti-book', '/accounting/accounts', 'url', 0, true, '2025-11-07 06:28:28', '2025-11-07 06:28:28', 'lihat akun-akuntansi');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (41, 39, 'Jurnal', 'ti ti-notebook', '/accounting/journals', 'url', 1, true, '2025-11-07 06:28:28', '2025-11-07 06:28:28', 'lihat jurnal');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (45, NULL, 'Dokumen', 'ti ti-files', '#', 'url', 13, true, '2025-11-07 06:28:28', '2025-11-07 06:28:28', 'lihat daftar dokumen');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (46, 45, 'Daftar Dokumen', 'ti ti-file-text', '/document', 'url', 0, true, '2025-11-07 06:28:28', '2025-11-07 06:28:28', 'lihat daftar dokumen');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (47, NULL, 'Transaksi', 'ti ti-building-bank', '#', 'url', 14, true, '2025-11-07 06:28:28', '2025-11-07 06:28:28', 'riwayat pembelian produk');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (48, 47, 'Pembelian Produk', 'ti ti-book', '/product/pembelian', 'url', 0, true, '2025-11-07 06:28:28', '2025-11-07 06:28:28', 'riwayat pembelian produk');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (49, 47, 'Penjualan Produk', 'ti ti-notebook', '/product/penjualan', 'url', 1, true, '2025-11-07 06:28:28', '2025-11-07 06:28:28', 'riwayat penjualan produk');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (2, NULL, 'Menu', 'ti ti-building-bank', '/menus', 'url', 1, true, '2025-11-07 06:28:27', '2025-11-07 06:34:14', 'lihat daftar menu');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (8, 7, 'Daftar Karyawan', 'ti ti-briefcase', '/employees', 'url', 0, true, '2025-11-07 06:28:27', '2025-11-07 08:33:42', 'lihat data karyawan');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (17, 14, 'Riwayat Transaksi', 'ti ti-building-bank', '/customer/history', 'url', 2, true, '2025-11-07 06:28:27', '2025-11-07 08:35:35', 'riwayat transaksi customer');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (32, 30, 'Riwayat Penggajian', 'ti ti-building-bank', '/contractor/history', 'url', 1, true, '2025-11-07 06:28:28', '2025-11-07 08:36:44', 'riwayat penggajian kontraktor');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (43, 42, 'Daftar Proyek', 'ti ti-building-bank', '/projects', 'url', 0, true, '2025-11-07 06:28:28', '2025-12-01 07:11:31', 'lihat daftar proyek|lihat data proyek');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (30, NULL, 'Mitra Kontraktor', 'ti ti-building-bank', '#', 'url', 10, true, '2025-11-07 06:28:28', '2025-11-20 02:01:39', 'lihat data kontraktor');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (19, 18, 'Daftar Affiliator', 'ti ti-building-bank', '/affiliators', 'url', 0, true, '2025-11-07 06:28:27', '2025-11-12 05:21:24', 'lihat data affiliator');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (14, NULL, 'Customer', 'ti ti-building-bank', '#', 'url', 5, true, '2025-11-07 06:28:27', '2025-11-13 03:02:05', 'lihat data customer');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (15, 14, 'Daftar Customer', 'ti ti-building-bank', '/customers', 'url', 0, true, '2025-11-07 06:28:27', '2025-11-13 03:03:01', 'lihat data customer');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (22, 21, 'Daftar Supplier', 'ti ti-building-bank', '/suppliers', 'url', 0, true, '2025-11-07 06:28:27', '2025-11-13 04:49:03', 'lihat data supplier');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (25, 24, 'Daftar Investor', 'ti ti-building-bank', '/investors', 'url', 0, true, '2025-11-07 06:28:27', '2025-11-13 04:53:30', 'lihat data investor');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (18, NULL, 'Affiliator', 'ti ti-building-bank', '#', 'url', 6, true, '2025-11-07 06:28:27', '2025-11-17 06:12:52', 'lihat data affiliator');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (31, 30, 'Daftar Kontraktor', 'ti ti-home', '/contractors', 'url', 0, true, '2025-11-07 06:28:28', '2025-11-14 02:54:34', 'lihat data kontraktor');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (28, 27, 'Daftar Tukang', 'ti ti-home', '/workers', 'url', 0, true, '2025-11-07 06:28:28', '2025-11-14 05:50:14', 'lihat data tukang');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (51, 50, 'Daftar Arsitek', 'ti ti-building-bank', '/architects', 'url', 0, true, '2025-11-13 05:51:16', '2025-11-17 06:15:52', 'lihat data arsitek');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (36, NULL, 'Produk', 'ti ti-building-bank', '#', 'url', 12, true, '2025-11-07 06:28:28', '2025-11-20 01:59:13', 'lihat daftar produk');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (50, NULL, 'Mitra Arsitek', 'ti ti-building-bank', '#', 'url', 8, true, '2025-11-13 05:49:02', '2025-11-17 06:16:52', 'lihat data arsitek');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (52, 50, 'Riwayat Penggajian', 'ti ti-building-bank', '/history/architect', 'url', 1, true, '2025-11-13 07:02:41', '2025-11-19 01:49:46', 'riwayat penggajian arsitek');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (34, 33, 'Daftar Gudang', 'ti ti-building-bank', '/warehouses', 'url', 0, true, '2025-11-07 06:28:28', '2025-11-19 03:37:59', 'lihat data gudang');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (57, 36, 'Daftar Warna', 'ti ti-building-bank', '/product_colors', 'url', 7, true, '2025-11-19 06:35:11', '2025-12-01 03:20:15', NULL);
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (33, NULL, 'Gudang', 'ti ti-building-bank', '#', 'url', 11, true, '2025-11-07 06:28:28', '2025-11-20 01:59:52', 'lihat daftar gudang');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (21, NULL, 'Mitra Supplier', 'ti ti-building-bank', '#', 'url', 7, true, '2025-11-07 06:28:27', '2025-11-20 02:00:59', 'lihat data supplier');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (37, 36, 'Master Data', 'ti ti-building-bank', '/products', 'url', 0, true, '2025-11-07 06:28:28', '2025-11-18 04:21:06', 'lihat data produk');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (54, 36, 'Daftar Tipe', 'ti ti-building-bank', '/product_types', 'url', 3, true, '2025-11-19 01:46:42', '2025-12-01 03:13:11', 'lihat daftar tipe');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (38, 36, 'Riwayat Transaksi', 'ti ti-building-bank', '/product/history', 'url', 3, true, '2025-11-07 06:28:28', '2025-12-01 03:22:47', 'riwayat pembelian produk|riwayat penjualan produk');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (55, 36, 'Daftar Merk', 'ti ti-building-bank', '/product_brands', 'url', 7, true, '2025-11-19 01:47:40', '2025-12-01 03:18:32', 'lihat daftar merk');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (42, NULL, 'Proyek', 'ti ti-building-bank', '#', 'url', 15, true, '2025-11-07 06:28:28', '2025-12-22 02:15:24', 'lihat daftar proyek|lihat data proyek');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (44, 42, 'Proyek Desain', 'ti ti-building-bank', '/design-packages', 'url', 1, true, '2025-11-07 06:28:28', '2025-12-09 02:26:33', 'lihat daftar proyek');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (58, 36, 'Katalog Produk Customer', 'ti ti-building-bank', '/catalog/customer', 'url', 1, true, '2025-11-28 02:07:45', '2025-12-01 03:09:55', 'lihat data produk');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (56, 36, 'Katalog Produk Supplier', 'ti ti-building-bank', '/catalog/supplier', 'url', 2, true, '2025-11-19 05:54:35', '2025-12-01 03:21:40', 'lihat data produk');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (53, 36, 'Daftar Kategori', 'ti ti-building-bank', '/product_categories', 'url', 4, true, '2025-11-19 01:40:54', '2025-12-01 03:24:11', 'lihat daftar kategori');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (61, 42, 'Proyek Build', 'ti ti-building-community', '/project/buid', 'url', 2, true, NULL, '2025-12-08 06:40:27', 'lihat daftar proyek');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (62, 42, 'Tenaga', 'ti ti-building-bank', '/labor_costs', 'url', 3, true, NULL, '2025-12-08 08:36:50', 'lihat daftar proyek');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (63, 42, 'Proyek RAB', 'ti ti-building-bank', '/job-categories', 'url', 4, true, '2025-12-23 07:50:31', '2025-12-30 07:33:15', 'lihat daftar proyek|lihat data proyek');
INSERT INTO public.menus (id, parent_id, text, icon, url, type, "order", is_active, created_at, updated_at, permission_name) VALUES (64, 42, 'Peralatan', 'ti ti-building-bank', '/equipment_costs', 'url', 5, true, '2025-12-30 07:34:29', '2025-12-30 08:57:48', 'lihat daftar proyek|lihat data proyek');


--
-- Name: menus_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.menus_id_seq', 64, true);


--
-- PostgreSQL database dump complete
--

