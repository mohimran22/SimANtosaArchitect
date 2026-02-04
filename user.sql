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

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id uuid NOT NULL,
    fullname character varying(255) NOT NULL,
    nickname character varying(255),
    gender smallint,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    birth_place character varying(255),
    birth_date character varying(10),
    religion_id bigint,
    address text,
    province_id bigint,
    city_id bigint,
    district_id bigint,
    sub_district_id bigint,
    postal_code_id bigint,
    phone character varying(255),
    photo character varying(255),
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    identity_number character varying(255),
    npwp character varying(255),
    bank_id uuid,
    account_number character varying(255),
    account_holder character varying(255),
    active_role uuid
);


ALTER TABLE public.users OWNER TO postgres;

--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, fullname, nickname, gender, email, email_verified_at, password, birth_place, birth_date, religion_id, address, province_id, city_id, district_id, sub_district_id, postal_code_id, phone, photo, remember_token, created_at, updated_at, identity_number, npwp, bank_id, account_number, account_holder, active_role) FROM stdin;
8a3b75bd-efbe-4168-a82d-7c40feff22e4	User Direktur	\N	\N	direktur@example.com	2025-11-03 01:07:15	$2y$12$1p/fLS4lKp0d7NUoki.nbe.RktaZJbLbkfHlfA23WLq3HxxISM6Wi	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	6Updptgbwx	2025-11-03 01:07:15	2025-11-03 01:07:15	\N	\N	\N	\N	\N	\N
d82162a3-5824-4426-a8f8-690937d06a03	User Spv HRD	\N	\N	hrd@gmail.com	2025-11-03 01:07:15	$2y$12$OEMeHoYCfKZzv6URI9/XyO4usAfQN0kP.Ph32l14NRNvkQtVYQfMu	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	HInf5Kt0Gu	2025-11-03 01:07:15	2025-11-03 01:07:15	\N	\N	\N	\N	\N	\N
aef229d9-64fe-4e65-9910-1091def4abbd	l	\N	1	l@example.com	\N	$2y$12$m9DdtgEFrdfdL6wfcMwhiewC.nzuVlf3b/i9Z502I1f9IBIMvdqPm	\N	\N	\N	\N	\N	\N	\N	\N	\N	0	\N	\N	2025-11-06 02:03:47	2025-11-06 02:03:47	\N	\N	\N	\N	\N	\N
ae9d65a8-9a6c-4ef9-9172-b2bd61a3a9c9	as2	hg7	2	majsk@Gmail.com	\N	$2y$12$zsl1E01ETa2oP6wFOpC9geXWwVLEvTqbVDaMlw9SLnF31fBkFdflm	kj	1990-01-01	1	laksjm	5	91	1104	15935	15935	087	\N	\N	2025-11-12 07:04:03	2025-12-01 03:07:29	3509192207970002	0	\N	01928	laksj	58c3ae6d-c36a-4468-be1e-a6b618086cec
11faaf68-f76c-4c46-80e7-748156c15eb8	Mohammad Imran	Imran	1	imron@gmail.com	\N	$2y$12$Jz2JO/zR93umipEtu5O2dOhStwz2.6kKePTLA..XHtBnTT52B.Sgi	jember	1997-07-22	1	hayam wuruk	15	234	3372	42178	42178	081726	\N	\N	2025-11-03 06:43:18	2025-11-03 06:59:42	3509192207960001	\N	\N	\N	\N	\N
c6268ffe-c142-4589-b6ff-5063dc70df43	Manager Administrasi	\N	\N	manageradm@example.com	2025-11-04 02:11:52	$2y$12$RdFLXVuqB6nUc00LzgTRqegXGpQlKVMR.ir1ndwIj1Yi0ciW3jwUG	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	8Rrf8LoTJU	2025-11-04 02:11:52	2025-11-04 02:11:52	\N	\N	\N	\N	\N	\N
553daa8c-5358-448a-a44d-621c67374e58	Manager Teknik	\N	\N	managerteknik@gmail.com	2025-11-04 02:11:52	$2y$12$f2NDPP4qbhENwUl2wlg3SuxHeaKoiU.KNc7gg9j7mN0.98G.1201K	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	OmvKwSqFdq	2025-11-04 02:11:52	2025-11-04 02:11:52	\N	\N	\N	\N	\N	\N
e0a45d13-0200-4500-bfc5-b1ed88045e93	Spv Marketing	\N	\N	spvmarketing@gmail.com	2025-11-04 02:11:52	$2y$12$S68OCYkA3aBA6GLEpmOwJOjVD/VaSmA7FrNaDM1J39jvl9berDvum	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	LJ10zxdsL6	2025-11-04 02:11:52	2025-11-04 02:11:52	\N	\N	\N	\N	\N	\N
44b31020-c81c-46a0-9a35-a7546610ada1	Spv Finance	\N	\N	spvfinance@gmail.com	2025-11-04 02:11:53	$2y$12$cfYOmq.d4LKfSohf0QQIwu8siT.I4geyDnR3ogPEhBirKwajLuM..	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	7BrcZzao7d	2025-11-04 02:11:53	2025-11-04 02:11:53	\N	\N	\N	\N	\N	\N
a55e4699-0524-44d2-b6d4-4b037b1b974d	Spv Arsitek	\N	\N	spvarsitek@gmail.com	2025-11-04 02:11:53	$2y$12$JzZ5MR50.gVfiyWwZR3RcuO2gPfBOAu2EP385V9REnJRkosO18myC	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	QFOCXPNyk2	2025-11-04 02:11:53	2025-11-04 02:11:53	\N	\N	\N	\N	\N	\N
09c58a4f-2849-410a-b74e-cac4b1b0b58d	Spv Sipil	\N	\N	spvsipil@gmail.com	2025-11-04 02:11:53	$2y$12$1rFJtsWoJ411LfGGbJqOfO.nP.E6YcDwIBRs3gmPPZX04Enq5hnsG	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	kIBDB06Y5d	2025-11-04 02:11:53	2025-11-04 02:11:53	\N	\N	\N	\N	\N	\N
5c4e5be1-af05-4c56-8a92-c8f9af237c49	Staff Marketing	\N	\N	staffmarketing@gmail.com	2025-11-04 02:11:53	$2y$12$dWQkChimy1XhfPZnLf6jwexYa8MWy18am98SivH6uXlJhC4pXe1y6	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	q316WYcJ0A	2025-11-04 02:11:53	2025-11-04 02:11:53	\N	\N	\N	\N	\N	\N
490d2d47-a51d-4cba-818b-4b466b6466d8	Staff Finance	\N	\N	stafffinance@gmail.com	2025-11-04 02:11:53	$2y$12$67Us2hYc0eMV8i2vQxWaROJTZMHo/3byJCeuY/lleoHDuYerd7iDe	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	yWjDFUfd0n	2025-11-04 02:11:53	2025-11-04 02:11:53	\N	\N	\N	\N	\N	\N
b53b33b7-0cfc-4883-b6f7-b96f4ea8d6df	Staff HRD	\N	\N	staffhrd@gmail.com	2025-11-04 02:11:54	$2y$12$gpfNpp2mlOX8dP2zacwPSea3UmXZP4DynJFY6hnFuU7QkWU1aIvQq	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	WXereVTEma	2025-11-04 02:11:54	2025-11-04 02:11:54	\N	\N	\N	\N	\N	\N
eeced012-03a1-4510-a4dc-101016c03809	Quality Control	\N	\N	qc@gmail.com	2025-11-04 02:11:54	$2y$12$IeBFbV5lNT8wFsoXvq0ruOvdSojonrWQsJbI8PzdOPf2CAQMTbQZu	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	qxGD51SAAn	2025-11-04 02:11:54	2025-11-04 02:11:54	\N	\N	\N	\N	\N	\N
fd6384b7-ca91-466c-9947-62d6d4353a80	Mitra Kontraktor	\N	\N	mitrak@gmail.com	2025-11-04 02:11:55	$2y$12$gHhwSevB5./eOQrNU/A6Fen/M6WKRwz0e.P8xDsF91JHYcV5fqKHe	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	av23MP5NLO	2025-11-04 02:11:55	2025-11-04 02:11:55	\N	\N	\N	\N	\N	\N
f2d729fc-9abb-4554-b83c-3126756fb747	Mitra Supplier	\N	\N	mitras@gmail.com	2025-11-04 02:11:55	$2y$12$1B.irogm/cafcsRV0BGypOaiiiYe66hQ6TtlmyyEF/zv7Wz.gCEXC	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	t7xANO2WI6	2025-11-04 02:11:55	2025-11-04 02:11:55	\N	\N	\N	\N	\N	\N
e9d2d324-b540-4e73-b41e-c786498d943b	Mitra Arsitek	\N	\N	mitraa@gmail.com	2025-11-04 02:11:55	$2y$12$p4SOD45DHZ2qOhiesQmXp.VTb00SOMilRxNO0I/55Z14cS2XmnX6.	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	2t8cpwMOfH	2025-11-04 02:11:55	2025-11-04 02:11:55	\N	\N	\N	\N	\N	\N
eda85394-543f-4117-ad37-aee226e87ae0	Customer	\N	\N	customer@gmail.com	2025-11-04 02:11:55	$2y$12$QLsPX0V7IO7V379FEMGSy.A4Q7pMa5Lii0pMst4MxkEnP72/p7ph.	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	q9FEe5ceZe9G6B9rwqypcKyB9teEOVGFS0RAl7jLyDg3BzTJX8ziOSpLHAmU	2025-11-04 02:11:55	2025-11-04 02:11:55	\N	\N	\N	\N	\N	\N
08bdf1f0-3aa3-4b17-831d-0f9a6b101904	coba	\N	\N	hanyacoba@gmail.com	\N	$2y$12$8TdRGC5TOVPOdr/UDPE7KeXKURV2l0xdfjLmoyX9gJq7kPYThbKHm	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	2025-11-05 01:44:20	2025-11-05 01:44:20	\N	\N	\N	\N	\N	\N
9d065ba4-1e79-4f02-adcb-9fb2e40e1f5b	Komisaris	\N	\N	komisaris@gmail.com	2025-11-04 02:11:51	$2y$12$glxRP/uQAKtwoAY5CRwCVOqbZ0nQrXLVYEUR.mD/D2FGzqIC4L4Z2	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	VMMmEwZwgugHVH9tU9HT6souPTGX3UMyyTK2wEdWxo3JNrmij3zK4fN5EWoZ	2025-11-04 02:11:51	2025-11-04 02:11:51	\N	\N	\N	\N	\N	\N
125a9bef-effa-4195-acf1-780ae2f9d03a	kali	kal	1	a@gmail.com	\N	$2y$12$Fczg3Hfop/pwgs9UwIBQ7ODq7Dz7GNrsVnlW3OIribclNqPUtvYqK	kajs	1990-02-01	2	jaks	15	226	3201	40060	40060	098	18cf8bbc-efae-418a-a98a-1985e1e25854.jpeg	\N	2025-11-06 03:44:38	2025-11-10 01:32:41	0	0	d9f59592-4a70-42fd-9a04-5e0823300592	1111	aaa	\N
c60f1220-9538-4818-aa82-69390ba7611e	aaa	aaaa	1	asqwe@gmail.com	\N	$2y$12$DCpa8iybdeXM8my95J.FH.osqrYThYbCI9wbrtXe1E6MLkF2dZbOe	aaa	2025-11-29	3	lll	20	313	4520	55390	55390	0	\N	\N	2025-11-12 02:02:12	2025-11-12 02:02:12	0	0	22101867-319f-4137-a5f3-d04fbeaa7f4f	111	ss	\N
e3565df6-16bd-496f-9d4c-daaaa32c1909	Kokok	lll	1	asj@gmail.com	\N	$2y$12$uLELQ2ybwDGCxH/iHFgsieO0CghS4AVVlyuv.CTi6DAsJtDi38fXi	kkk	1990-01-01	1	laks	15	234	3373	42185	42185	08765	93507f51-e011-4f53-b1a0-8fd84a773271.png	\N	2025-11-10 07:12:19	2026-01-09 01:21:14	350919	0	66366cf0-4cec-43b2-8fd1-c2815fc7e6a7	091827	kajsl	\N
c92da870-8593-4515-88e6-f17a05be01a6	sss	lll	2	kamsj@gmail.com	\N	$2y$12$9M6O8SyW6mv/tmenmkNh9u0vcPXchXczAQ7G9aeEneaE/1eynk0Qi	llll	1990-01-01	1	lll	5	88	1055	15327	15327	087	d5786417-9f96-4872-9d94-f1a6e9628d95.png	\N	2025-11-12 04:10:59	2025-11-12 04:10:59	0	0	66366cf0-4cec-43b2-8fd1-c2815fc7e6a7	0897	lll	\N
9e43e80e-2a6f-4dcd-9fb4-cab5f245be48	www	jjj	1	kmas@gmail.com	\N	$2y$12$gWjnptWrZjzC4sr48kSdTe1CNDDYYIkzBPaDUEQ5Bg3Qu38E.VoVq	ll	1990-01-01	3	oklj	4	80	952	14315	14315	087	\N	\N	2025-11-12 07:08:40	2025-11-12 08:27:38	0	0	57668a90-96b4-4c4d-a2a4-e1d761bcfe37	91827	kajsh	\N
5fe8792a-d08a-404c-b308-b4f7cd75373f	Iqbal	\N	1	drafter@gmail.com	2025-11-03 01:07:16	$2y$12$hYl8xHa0D1Jt.ZENfILpxefjSjqJlO4pMZsE5.w8PiT11j3BZNqZq	\N	2026-07-16	\N	\N	1	2	14	164	164	\N	\N	90sBd2vYRazL8P2dVWUWh2vuFI2a3q6WwRCFhCnPvfnAB1UO3DM3V4JLtSGG	2025-11-03 01:07:16	2026-01-09 01:20:36	\N	\N	\N	\N	\N	4f7f9361-923e-412b-8b5b-e7ebf87f4b1f
b17be317-d05e-4d0d-a69f-b97a9917a43d	Mas Dian	dian	2	masdian@gmail.com	\N	$2y$12$jb2n3lTSzVmSuBFaCW0b.Our/SC9GnlB/qIb8e5GtLAb3ZmwNIdRa	qqqqqqq	1990-01-01	1	jalan	3	58	724	12456	12456	0897	\N	\N	2025-11-13 01:25:25	2026-02-03 03:08:12	3509192808900005	\N	\N	\N	\N	c96629c8-f736-445e-9963-3d8265df2796
067faf69-5bbc-4695-a384-c8ab55ec7a25	Tukang	\N	1	worker@gmail.com	2025-11-04 02:11:55	$2y$12$ihnSBcNdP4Vjqza6NNsXtuMB92.HiUZKw2g2Qt2Mt0b8kmHMp.orq	jember	1990-01-01	1	imam bonjol	15	234	3369	42153	42153	087	aec30eab-bd96-4788-a7dc-57c454fad95b.jpg	izAIirQrDd	2025-11-04 02:11:55	2025-11-14 05:41:41	0	0	66366cf0-4cec-43b2-8fd1-c2815fc7e6a7	123	las	\N
58d05607-004d-4a23-a2fc-ded6b9810d37	Dian	Dian	2	dian@gmail.com	\N	$2y$12$hiWukg.5CTJ/La9kXIFiZeqBl3MAh4AFMP/OOPYcTxXRre6fN6Nfa	ll	1990-01-01	1	jalan hayam wuruk	20	315	4550	55668	55668	08982734652	a1344747-081f-4e05-88a2-3cf440261812.jpg	\N	2025-11-12 03:51:12	2026-01-15 02:44:47	1234567890123456	0	c085f89c-d262-4aeb-bca7-7bad6cb9ef34	089	klj	58c3ae6d-c36a-4468-be1e-a6b618086cec
68a82b32-f0bd-4c37-a5d5-9eb83badd2ac	Reza	\N	\N	reza@gmail.com	2025-11-03 01:07:16	$2y$12$aeotfzo0ALZoROJG64zep.HHf6z8NYAWjZsLnjQ6aYwMPCiZrnjx.	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	0YEzhbf5znGSUxwWFKcnmuWrJzcvK0SpbLmiH6JykMm2rIFmxwJaFR4hIGIw	2025-11-03 01:07:16	2025-12-17 02:04:51	\N	\N	\N	\N	\N	ba96959c-aef8-4e0b-84a2-65b6a7caef05
902a4c29-0328-42f2-a313-0773b166faef	Izul	\N	1	ddddddd@gmail.com	\N	$2y$12$14pb/TCCZ.EoH1WKi2lo0uuO4KjgaYnHHbkmTjE6xv8W20OLEjcTi	werr	2025-11-29	2	eeee	4	79	940	14192	14192	098	\N	\N	2025-11-13 01:21:12	2026-01-09 01:21:59	0	0	d9f59592-4a70-42fd-9a04-5e0823300592	\N	\N	\N
50186183-7180-4635-b3f3-02f209a0cf91	Super Admin	\N	\N	superadmin@gmail.com	2025-11-03 01:07:14	$2y$12$gS7OeyBdqQDKC8o2XXBrEe0s07JRbaANazp38U6fLVrlBnBYrgLuC	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	EoUgLtOyeC5hcaIX5bdbdc5tjvI6SMReuVOAUIv3npQ0KMpCDtV5BesVkgm0	2025-11-03 01:07:15	2025-12-19 01:34:35	\N	\N	\N	\N	\N	\N
7b391d70-5fe3-42cf-b15e-f21c4b2850b0	Kebon Jaya	\N	1	kebon@gmail.com	\N	$2y$12$5AayjCErmgPP162629W9bejU3eVxl2nGYO4gJip3mrMEr6Ji.5Wsq	Jember	2026-02-07	1	jalan hayam	15	226	3201	40060	40060	08716253	\N	\N	2026-01-13 09:13:09	2026-01-13 09:13:09	350919	\N	\N	\N	\N	\N
76d38fa3-44ef-4500-a4c8-29a9c7460bb6	rrrrr	\N	1	ma@gmail.com	\N	$2y$12$WpQIy/gMLMYMlQUlpy44z.mubIOuH7kx77gT2MZNbyDGz.3TQ6Oaa	rrrrr	2025-11-05	1	wwwwwwwww	4	79	937	14169	14169	444	C:\\Users\\User\\AppData\\Local\\Temp\\phpE8CF.tmp	\N	2025-11-14 02:15:23	2025-11-14 02:15:23	111	\N	18ba941a-5f67-4172-9658-f2b24fff8205	123	dddd	\N
d68144e3-0b98-4c09-af53-fd79e0f40b9f	Imron	\N	1	la@gmail.com	\N	$2y$12$ecvG5fXbwKoXYjjYblyng.t6/RayLUF1Dpy/Og8Ro1xai5xAmGOY6	lll	1990-01-01	1	lll	6	99	1191	16807	16807	098	C:\\Users\\User\\AppData\\Local\\Temp\\phpB821.tmp	\N	2025-11-14 02:23:54	2025-11-14 02:28:28	09	\N	66366cf0-4cec-43b2-8fd1-c2815fc7e6a7	098	lll	\N
eae59dd5-0c20-4a11-a284-cd97194abfc3	Affiliator	\N	1	affiliator@gmail.com	2025-11-04 02:11:56	$2y$12$rSSUIFg/VOfeBiZE6i0/8eFhWotoIyz.BgDSa8qIauVn2yVEbSeEC	hajshd	2025-10-31	1	asd	2	25	300	6631	6631	01928	3b49385d-43f3-4fcd-8690-25f7f562200d.png	fcdZVN8PNX	2025-11-04 02:11:56	2025-11-14 03:33:03	1	\N	66366cf0-4cec-43b2-8fd1-c2815fc7e6a7	123	erd	\N
ac6164b9-6d7d-4f3b-9399-64ade1903a67	AKU	\N	2	lm@gmail.com	\N	$2y$12$51NMe.CuGNeQ5P3KiBQ8gOvt7cQENZZ4WeVGJLR7Lt5z02QTW2YMO	qqq	2025-09-18	1	asd	20	314	4537	55525	55525	1	56523f65-da7b-49a7-a403-25ecb901e3a7.jpg	\N	2025-11-14 03:47:54	2025-11-14 03:54:12	123	\N	c085f89c-d262-4aeb-bca7-7bad6cb9ef34	123	sedf	\N
2dc4b834-9279-4c8e-a134-eb16b94fbdc9	Investor	\N	1	investor@gmail.com	2025-11-04 02:11:54	$2y$12$2vt4tDtSpLPaN5SduAKz/u4cNKYVYgkZZiwQDFBSu2GlMe2XljdbO	jember	2025-11-04	2	imam bonjol	2	25	302	6678	6678	081234567	6f36f361-5d19-46de-b392-f7d4c8fad408.jpg	ndV5tzrvEkqYqQ8LayPKg0Rv48ozUVOwK9Qb7fCGPGyMPsSwodFEgWFHeqUQ	2025-11-04 02:11:54	2025-11-14 05:30:37	0	0	d9f59592-4a70-42fd-9a04-5e0823300592	123	dfg	\N
daec5803-4071-47f6-9de0-09321fb2b12b	imron	\N	\N	normi@gmail.com	\N	$2y$12$wWYnIeheOrmcsSfYAwUWsOwpJZ98dVqRGIZ.BWMJMGQS27PszotuS	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	2026-02-03 01:15:21	2026-02-03 01:15:21	\N	\N	\N	\N	\N	\N
7690378e-bed0-4d80-8335-047483f7cd18	aieuo	qwerty	2	aswjhd@gmail.com	\N	$2y$12$xqkvTVpyaXGk5nbeEi96EOHT0h2ddXUaIzYqBJVDSNCs/SlDqrzZ.	jember	2026-02-03	1	jalan piere tendean	3	59	736	12602	12602	0817263	photos/30231355-480c-47c7-a0b6-4a12d3047e92.png	\N	2026-02-03 02:20:21	2026-02-03 03:12:37	1234567893123456	\N	\N	\N	\N	\N
026cd364-db06-49c0-80e4-a6a30708123c	Harimas Catur Wiratmoko, S.T., S.M.	Kokok	1	harimascaturwiratmoko@gmail.com	\N	$2y$12$rUDhsxAoF3NGTIc4THV4ZeZ1e5HFnQpx7MOKfE20Iy.ebtykbuy/O	Jember	1990-01-01	1	Jalan Wijaya Kusuma	15	234	3397	42381	42381	087750708531	\N	\N	2026-02-04 03:53:51	2026-02-04 03:53:51	3510000000000000	\N	\N	\N	\N	\N
659b0715-f174-4899-bfcd-d8fe2e639007	Ir. Ar. Dwiantosa Ahmad Fathony, IAI., IPP	Tosa	1	tosa@gmail.com	\N	$2y$12$WWxUZebr9I2033dleGtFC.1JM59mknJ6UEtb6AzFfsfSyR/bDQmV2	Jember	1992-07-12	1	perum bernady land	15	234	3397	42381	42381	3509190020120002	\N	\N	2026-02-04 03:55:53	2026-02-04 03:55:53	3509190020120002	\N	\N	\N	\N	\N
5fe77e47-0255-433c-b52b-35ec02ecf174	Mohammad Imran	Imron	1	alkajshd@gmail.com	\N	$2y$12$IdNmf/4saa4PCnh7YBmdiOELwI./3qSPaVKFbuDI9ojaL609l5zUK	Jember	1990-02-01	1	Jalan Hayam Wuruk IV No 2	4	78	920	13979	13979	085655996569	7c70e972-c4fb-4b5b-82ba-9e992b3ed73f.png	\N	2025-11-06 06:24:36	2025-12-17 02:07:13	35091929292	0	18ba941a-5f67-4172-9658-f2b24fff8205	019287	mansk	58c3ae6d-c36a-4468-be1e-a6b618086cec
e5836492-a9f2-4771-991c-48d6b31c298e	DWIANTOSA AHMAD FATHONY	Tosa	1	antosaarchitect@gmail.com	\N	$2y$12$nCsAl6yrsY.rt0fMkkaYj.MSsRI./4y8fHeeX12C1jR4kpHXHa9q.	Jember	1992-06-03	1	Bernady Land, Cluster Camelia Blok E6	15	234	3397	42383	42383	085236873007	\N	\N	2025-12-26 01:37:14	2025-12-26 01:37:14	3509190306920005	\N	66366cf0-4cec-43b2-8fd1-c2815fc7e6a7	0241575429	Dwiantosa Ahmad Fathony	\N
31c7a112-e048-43e6-9606-d2813cecdbb4	Heru	Heru	1	heru@gmail.com	\N	$2y$12$aBG3sGVqxdOD1cPWret4/Oo.Qd.kYOIVMvWj1xIntqUng8htMuW7W	Jember	2025-06-30	1	jalan	15	234	3373	42189	42189	08567	profile_photos/iXkt86zkXCzbiFsPwiMrBOpyPPEX5FRKTFZYstb4.png	\N	2025-12-31 03:41:30	2026-01-08 07:05:06	3509	\N	66366cf0-4cec-43b2-8fd1-c2815fc7e6a7	12345	Heru	\N
\.


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: users users_active_role_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_active_role_foreign FOREIGN KEY (active_role) REFERENCES public.roles(id);


--
-- Name: users users_bank_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_bank_id_foreign FOREIGN KEY (bank_id) REFERENCES public.banks(id) ON DELETE SET NULL;


--
-- Name: users users_city_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_city_id_foreign FOREIGN KEY (city_id) REFERENCES public.cities(id) ON DELETE CASCADE;


--
-- Name: users users_district_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_district_id_foreign FOREIGN KEY (district_id) REFERENCES public.districts(id) ON DELETE CASCADE;


--
-- Name: users users_postal_code_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_postal_code_id_foreign FOREIGN KEY (postal_code_id) REFERENCES public.postal_codes(id) ON DELETE CASCADE;


--
-- Name: users users_province_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_province_id_foreign FOREIGN KEY (province_id) REFERENCES public.provinces(id) ON DELETE CASCADE;


--
-- Name: users users_religion_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_religion_id_foreign FOREIGN KEY (religion_id) REFERENCES public.religions(id) ON DELETE CASCADE;


--
-- Name: users users_sub_district_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_sub_district_id_foreign FOREIGN KEY (sub_district_id) REFERENCES public.sub_districts(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

