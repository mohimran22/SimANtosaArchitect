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
-- Data for Name: permissions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.permissions (id, name, guard_name, created_at, updated_at, modules) FROM stdin;
cf59e2eb-b158-46b9-adc9-3ad1df243397	tambah data user	web	2025-11-05 03:25:30	2025-11-05 03:25:30	User
1b286864-2b8b-4f84-99cb-974867febecf	lihat daftar user	web	2025-11-05 03:25:31	2025-11-05 03:25:31	User
f11cfcaf-1706-4462-b980-209a3f0f3ed8	ubah data user	web	2025-11-05 03:25:31	2025-11-05 03:25:31	User
52900632-fc59-43dc-9df2-e090c925bc46	hapus data user	web	2025-11-05 03:25:31	2025-11-05 03:25:31	User
14b674de-4f63-4766-8460-83a69933682a	tambah data role	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Role
f8df263c-4897-4545-93ae-7912fd4c0f85	lihat daftar role	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Role
e88a5d11-3fd1-403e-88af-7200b03f0cab	ubah data role	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Role
0caa62bc-a0a5-4e7f-be4a-6ef7566560c0	hapus data role	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Role
79503f3a-0436-4a9c-9b62-3442704684e5	tambah data karyawan	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Karyawan
ad7817f8-e854-4516-bacd-3f1e5b9956aa	lihat data karyawan	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Karyawan
4efc89f1-cb5d-400e-9ed7-e6872050ce96	lihat daftar karyawan	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Karyawan
67c66f84-b0cc-47e9-991a-80b377c3a1b6	ubah data karyawan	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Karyawan
53839520-65b2-4e7f-887c-9c0d0915f17d	hapus data karyawan	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Karyawan
a0d13912-83a4-4fd6-89f4-c63c8a232785	riwayat penggajian karyawan	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Karyawan
4c39c421-8378-41ec-8a21-428f24137a0d	lihat daftar gudang	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Gudang
d27f379d-7ad4-43d1-8305-762b66c7ab69	tambah data gudang	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Gudang
2f3a436b-e722-4ba9-9730-221a40c5b495	lihat data gudang	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Gudang
6cb0fe58-de30-48f4-8a1f-79415a09d251	ubah data gudang	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Gudang
d65c7bc5-a9d5-4b75-9302-b176cfcb0c30	hapus data gudang	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Gudang
52534c8c-9f56-434e-8583-3c5757a71431	riwayat transaksi gudang	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Gudang
7e70f30b-ea3f-4295-ac6f-81b1f88dc169	lihat daftar produk	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Produk
499c6ba0-5121-487f-bd80-d50ee9a148d3	tambah data produk	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Produk
909f7843-3ac6-40bc-bc94-6657cf0442da	lihat data produk	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Produk
33d48a29-8907-4bbb-ba1f-7929e66612ea	ubah data produk	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Produk
fbc35649-e4a2-4c30-b927-931de6b59cfe	hapus data produk	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Produk
1690b0c9-7034-4383-9143-1d3927790b28	riwayat pembelian produk	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Produk
42a86d3b-e058-4c4f-9ba8-b0d7433a315b	riwayat penjualan produk	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Produk
53df7096-6554-4641-8a39-3e37746cf40b	lihat daftar customer	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Customer
920abdf8-10e8-4e3a-b298-22031201fb8c	tambah data customer	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Customer
a67b8ed1-39b5-46be-b2b3-c02f4f52ab4a	lihat data customer	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Customer
28e42dc7-7c32-4b99-a186-0179a73d6999	ubah data customer	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Customer
25179d75-b22a-44a8-aaf8-391a242810e8	hapus data customer	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Customer
5f4ac2de-0274-4bd8-8ae6-6be10b51363f	riwayat transaksi customer	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Customer
d780dd91-6218-42ea-9642-301374163d3b	lihat daftar affiliator	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Affiliator
debd0be5-7230-4642-a135-6aa5fdb6e52a	tambah data affiliator	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Affiliator
74e662ce-0b5c-4529-9455-cdd6dcaeb2c0	lihat data affiliator	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Affiliator
51808506-f5d1-48f1-bd9d-00df27fa97ba	ubah data affiliator	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Affiliator
20e34087-1b4a-4107-b3cd-192209d431a4	hapus data affiliator	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Affiliator
8a684cef-6147-4156-8b08-dbb5ea3c3ecd	riwayat performa affiliator	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Affiliator
e3de40f1-0886-4391-9284-3cbc83a31094	lihat daftar supplier	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Supplier
2452e656-1607-4701-ac44-3ebd648083ba	tambah data supplier	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Supplier
c40978b5-6395-4989-b4c1-82de756a9bd7	lihat data supplier	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Supplier
221b855e-7661-414c-a745-cf2f56df3037	ubah data supplier	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Supplier
1b4ee238-869e-434f-923e-e11e433db115	hapus data supplier	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Supplier
617586ea-3d10-4880-9fbb-f57cf34e3f11	riwayat pembelian supplier	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Supplier
1053789c-5afb-4042-9816-d6c2bf25fe01	lihat daftar investor	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Investor
ef0b55fb-4cf1-4cf7-a663-e7891f9c1032	tambah data investor	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Investor
abe46296-b668-40a1-934f-856dfdbfcaee	lihat data investor	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Investor
4ac380fe-8d9e-43d5-8524-eeab8dd37306	ubah data investor	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Investor
f02b7d8a-2652-443c-b3ef-5b09b7afc946	hapus data investor	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Investor
96e3fc99-8b18-4f08-af47-e2baa872e180	saham investor	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Investor
6dfb190a-99bf-4bb1-bfa8-a369846e23bc	lihat daftar tukang	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Tukang
36407a88-33e9-40e4-b5c9-4768adee1139	tambah data tukang	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Tukang
ed8ba686-c053-46dd-bc35-4a088301a07e	lihat data tukang	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Tukang
aa6afecc-c161-402c-856c-3fe3978c687a	ubah data tukang	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Tukang
e822fda6-3b7b-42ce-b2cd-29356f000128	hapus data tukang	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Tukang
02517552-1db3-4d63-8990-0c9a72215a2a	riwayat penggajian tukang	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Tukang
0c9fa9ec-5f12-4c1d-bb62-6a4eb5c72623	lihat daftar kontraktor	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Kontraktor
19ca8120-23ff-4fff-8c0f-a0d29b3a057c	tambah data kontraktor	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Kontraktor
f346d5ca-c914-4617-bbd4-4e461378cd57	lihat data kontraktor	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Kontraktor
ad1fc726-5cca-429c-a350-ea2130d1f854	ubah data kontraktor	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Kontraktor
758fcf16-df2c-4eed-8b20-2dd8dd1c5684	hapus data kontraktor	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Kontraktor
4e3739e5-9eee-4a82-b4ba-adfcfe59a3cb	riwayat penggajian kontraktor	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Kontraktor
bb0a2dd5-7207-49da-bb03-4956eb41b568	lihat daftar dokumen	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Dokumen
92667b89-0294-48b7-8e7a-2046b6c6f93a	tambah dokumen	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Dokumen
d570fa3b-8cdd-479e-a573-55f721a57178	lihat dokumen	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Dokumen
3ad421b7-463a-44e1-837a-41c429c69d3d	ubah dokumen	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Dokumen
8605e49d-d5c7-4e6b-9ce6-6a95990e4dc0	hapus dokumen	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Dokumen
1d9d999c-9093-4a71-a376-f3d3cecbbc39	lihat daftar pembelian produk	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Produk
777b5e72-6d81-40f2-b6a1-0ed2830e71a1	tambah data pembelian produk	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Produk
0e366d19-4e8e-43f4-ba81-65d184cff0b5	lihat data pembelian produk	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Produk
6b16e5ef-4f28-4b47-a7ea-83c9471eaecf	ubah data pembelian produk	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Produk
d552862f-38a7-4aff-9ecb-55f772283405	hapus data pembelian produk	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Produk
558cef3e-8c47-43f1-9704-f97e4e37d65e	persetujuan pembelian produk	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Produk
f36edd61-6060-48d0-9e4d-2dddd24f286a	lihat daftar penjualan produk	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Produk
42248c88-140a-4e00-b934-9253f2f81557	tambah data penjualan produk	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Produk
4e7c86cf-a7ac-4f4a-b219-0bed7f04598c	lihat data penjualan produk	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Produk
01e52775-be2c-4006-bd5e-ae29d36e6346	ubah data penjualan produk	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Produk
28ad4d0a-73d7-456a-9173-12b8b25fed03	hapus data penjualan produk	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Produk
d7b2c800-6c69-4501-a724-dcb0e165fcde	persetujuan penjualan produk	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Produk
1ea15443-2a13-4b38-bf48-1d0b18a2a018	lihat daftar proyek	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Proyek
53e06429-af16-4396-a400-557368b00a82	tambah data proyek	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Proyek
3e2c299c-e0b7-48e7-ade4-7c5962dc01a9	lihat data proyek	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Proyek
c5c77eee-a715-4482-937b-df83766f69a9	ubah data proyek	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Proyek
7f56ee88-4939-4259-ab80-6696d3dec3fb	hapus data proyek	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Proyek
3a6e9d7c-66ab-4f1e-9e5d-506e560c4c46	tambah data rab	web	2025-11-05 03:25:31	2025-11-05 03:25:31	RAB
1016a6d2-9765-44c7-a88f-cd7f1ac1d2e3	lihat data rab	web	2025-11-05 03:25:31	2025-11-05 03:25:31	RAB
7b95306a-5d14-4ccc-b4ae-28b7d608c3ef	ubah data rab	web	2025-11-05 03:25:31	2025-11-05 03:25:31	RAB
88e19fb0-ed90-4104-a052-0036765af3d4	hapus data rab	web	2025-11-05 03:25:31	2025-11-05 03:25:31	RAB
69ddb720-ec32-4efe-8c8d-52684507eff5	tambah akun-akuntansi	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Akun Akuntansi
a843b415-28db-4df7-b3a1-83c4d358077b	lihat akun-akuntansi	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Akun Akuntansi
96162038-3978-429d-85c8-a3d347a0a9f1	ubah akun-akuntansi	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Akun Akuntansi
ef5980fc-24ca-4ad8-a1a0-6951d3c9368d	hapus akun-akuntansi	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Akun Akuntansi
02c1fd53-09ed-4084-9128-554004267a81	tambah jurnal	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Jurnal
0dcc994a-e9bf-4aab-b2ae-d6aa6438a192	lihat jurnal	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Jurnal
b90b2b57-9191-420d-9012-d9daae1d04d1	ubah jurnal	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Jurnal
5327f1d6-ef69-4f54-8d26-02e441df2e6c	hapus jurnal	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Jurnal
c557e2be-ab53-46da-82fc-2142a73d9ece	lihat daftar absensi	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Absensi
8f6da3fa-d9f0-458f-9ba9-29433db120a6	tambah data absensi	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Absensi
e5f9999c-011f-45b7-92dd-c72ba384510e	lihat data absensi	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Absensi
fbca25b9-d211-4a70-9852-4e23c9daf3e1	ubah data absensi	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Absensi
ab7b8552-a96e-4a07-9872-89b62005ce24	hapus data absensi	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Absensi
b99366bc-f40b-4bbb-93a2-2d7c09bd54c3	lihat daftar pelatihan	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Pelatihan
abba752b-e6e7-4d51-b218-df31deebef5d	tambah data pelatihan	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Pelatihan
6d449cfc-b164-45c5-9024-fa5b04bc47d0	lihat data pelatihan	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Pelatihan
19710c8e-a1af-4a0a-b704-a442179ad419	ubah data pelatihan	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Pelatihan
7104d249-cd17-4e4e-8368-01f8f4c133e7	hapus data pelatihan	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Pelatihan
6ae87d92-7736-411d-8ae8-620324483a66	lihat daftar penilaian kinerja	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Kinerja
0cac9117-fb0f-4892-9e6a-eb82aaf3576e	tambah data penilaian kinerja	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Kinerja
b91c042f-e3b0-4800-b1a8-3e4e460ed0ff	lihat data penilaian kinerja	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Kinerja
91332df3-f812-467d-8384-3edc6287ea4d	ubah data penilaian kinerja	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Kinerja
bc2a37c7-73d9-4fe5-8844-488f16dbb1a1	hapus data penilaian kinerja	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Kinerja
b5524e12-14c5-454b-9b92-190b012639c7	kelola akun	web	2025-11-05 03:25:31	2025-11-05 03:25:31	Manajemen Akun
b1a5738c-80c0-4577-9000-5c0c1e5a111f	tambah data menu	web	2025-11-07 06:24:23	2025-11-07 06:24:23	Menu
655d3ceb-3205-4986-b61e-b0da1a448ec7	lihat daftar menu	web	2025-11-07 06:24:23	2025-11-07 06:24:23	Menu
a993c8e4-dff8-4481-85ce-f447020dfe54	ubah data menu	web	2025-11-07 06:24:23	2025-11-07 06:24:23	Menu
4aaba02b-47cf-4e32-be3f-8a5f449b4939	hapus data menu	web	2025-11-07 06:24:23	2025-11-07 06:24:23	Menu
7ec8f89c-6fa3-4f03-ab04-62eb3ca862e1	tambah data arsitek	web	2025-11-13 05:11:50	2025-11-13 05:11:50	Arsitek
f8707ff6-1ade-4c73-90f5-90d8f7e187ee	lihat daftar arsitek	web	2025-11-13 05:11:50	2025-11-13 05:11:50	Arsitek
15396a12-2a08-47af-9666-bf75f420401a	lihat data arsitek	web	2025-11-13 05:11:50	2025-11-13 05:11:50	Arsitek
af4af1e0-ff89-41e2-9ed8-7e221c779676	ubah data arsitek	web	2025-11-13 05:11:50	2025-11-13 05:11:50	Arsitek
c523084b-404f-4abc-85cf-f5e811f47da2	hapus data arsitek	web	2025-11-13 05:11:50	2025-11-13 05:11:50	Arsitek
0e6b6e7f-00d3-496d-93b9-6b240cefe48d	riwayat penggajian arsitek	web	2025-11-13 05:11:50	2025-11-13 05:11:50	Arsitek
4481f552-dc53-4bce-9b28-f272ca2acbdf	tambah data kategori	web	2025-11-19 01:54:34	2025-11-19 01:54:34	Kategori
4b2109f0-7658-44b5-b7b7-0b3f537be933	lihat daftar kategori	web	2025-11-19 01:54:34	2025-11-19 01:54:34	Kategori
e7e6c38b-695c-4fef-98e2-0930b464370f	ubah data kategori	web	2025-11-19 01:54:34	2025-11-19 01:54:34	Kategori
32fa6a88-dfdf-43fe-8c9b-f00b78103f7c	hapus data kategori	web	2025-11-19 01:54:34	2025-11-19 01:54:34	Kategori
e360c6f2-0d9a-4eef-acbf-b5c74526d5e7	tambah data merk	web	2025-11-19 01:54:34	2025-11-19 01:54:34	Merk
7c939cfb-b00f-421a-a461-0fe989fc1a7a	lihat daftar merk	web	2025-11-19 01:54:34	2025-11-19 01:54:34	Merk
145dc11e-616a-461f-93f2-e52b90e0bc79	ubah data merk	web	2025-11-19 01:54:34	2025-11-19 01:54:34	Merk
f6c6d547-90d2-4d35-8f12-1a6d97ce8a63	hapus data merk	web	2025-11-19 01:54:34	2025-11-19 01:54:34	Merk
9a756938-5e7f-455e-91f9-647eba6e9534	tambah data tipe	web	2025-11-19 01:54:34	2025-11-19 01:54:34	Tipe
de3c57b7-903d-49c9-98e9-ea46b62d5fd3	lihat daftar tipe	web	2025-11-19 01:54:34	2025-11-19 01:54:34	Tipe
521235fd-f8df-412f-a010-ee005fb35a66	ubah data tipe	web	2025-11-19 01:54:34	2025-11-19 01:54:34	Tipe
355cda9e-fbc8-4537-ac40-b11f9b9ebaef	hapus data tipe	web	2025-11-19 01:54:34	2025-11-19 01:54:34	Tipe
\.


--
-- PostgreSQL database dump complete
--

