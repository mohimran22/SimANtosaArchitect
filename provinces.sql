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
-- Data for Name: provinces; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.provinces (id, name, country_id, is_active) VALUES (1, 'Aceh', 1, true);
INSERT INTO public.provinces (id, name, country_id, is_active) VALUES (2, 'Sumatera Utara', 1, true);
INSERT INTO public.provinces (id, name, country_id, is_active) VALUES (3, 'Sumatera Barat', 1, true);
INSERT INTO public.provinces (id, name, country_id, is_active) VALUES (4, 'Riau', 1, true);
INSERT INTO public.provinces (id, name, country_id, is_active) VALUES (5, 'Jambi', 1, true);
INSERT INTO public.provinces (id, name, country_id, is_active) VALUES (6, 'Sumatera Selatan', 1, true);
INSERT INTO public.provinces (id, name, country_id, is_active) VALUES (7, 'Bengkulu', 1, true);
INSERT INTO public.provinces (id, name, country_id, is_active) VALUES (8, 'Lampung', 1, true);
INSERT INTO public.provinces (id, name, country_id, is_active) VALUES (9, 'Kepulauan Bangka Belitung', 1, true);
INSERT INTO public.provinces (id, name, country_id, is_active) VALUES (10, 'Kepulauan Riau', 1, true);
INSERT INTO public.provinces (id, name, country_id, is_active) VALUES (11, 'Dki Jakarta', 1, true);
INSERT INTO public.provinces (id, name, country_id, is_active) VALUES (12, 'Jawa Barat', 1, true);
INSERT INTO public.provinces (id, name, country_id, is_active) VALUES (13, 'Jawa Tengah', 1, true);
INSERT INTO public.provinces (id, name, country_id, is_active) VALUES (14, 'Di Yogyakarta', 1, true);
INSERT INTO public.provinces (id, name, country_id, is_active) VALUES (15, 'Jawa Timur', 1, true);
INSERT INTO public.provinces (id, name, country_id, is_active) VALUES (16, 'Banten', 1, true);
INSERT INTO public.provinces (id, name, country_id, is_active) VALUES (17, 'Bali', 1, true);
INSERT INTO public.provinces (id, name, country_id, is_active) VALUES (18, 'Nusa Tenggara Barat', 1, true);
INSERT INTO public.provinces (id, name, country_id, is_active) VALUES (19, 'Nusa Tenggara Timur', 1, true);
INSERT INTO public.provinces (id, name, country_id, is_active) VALUES (20, 'Kalimantan Barat', 1, true);
INSERT INTO public.provinces (id, name, country_id, is_active) VALUES (21, 'Kalimantan Tengah', 1, true);
INSERT INTO public.provinces (id, name, country_id, is_active) VALUES (22, 'Kalimantan Selatan', 1, true);
INSERT INTO public.provinces (id, name, country_id, is_active) VALUES (23, 'Kalimantan Timur', 1, true);
INSERT INTO public.provinces (id, name, country_id, is_active) VALUES (24, 'Kalimantan Utara', 1, true);
INSERT INTO public.provinces (id, name, country_id, is_active) VALUES (25, 'Sulawesi Utara', 1, true);
INSERT INTO public.provinces (id, name, country_id, is_active) VALUES (26, 'Sulawesi Tengah', 1, true);
INSERT INTO public.provinces (id, name, country_id, is_active) VALUES (27, 'Sulawesi Selatan', 1, true);
INSERT INTO public.provinces (id, name, country_id, is_active) VALUES (28, 'Sulawesi Tenggara', 1, true);
INSERT INTO public.provinces (id, name, country_id, is_active) VALUES (29, 'Gorontalo', 1, true);
INSERT INTO public.provinces (id, name, country_id, is_active) VALUES (30, 'Sulawesi Barat', 1, true);
INSERT INTO public.provinces (id, name, country_id, is_active) VALUES (31, 'Maluku', 1, true);
INSERT INTO public.provinces (id, name, country_id, is_active) VALUES (32, 'Maluku Utara', 1, true);
INSERT INTO public.provinces (id, name, country_id, is_active) VALUES (33, 'Papua Barat', 1, true);
INSERT INTO public.provinces (id, name, country_id, is_active) VALUES (34, 'Papua', 1, true);


--
-- Name: provinces_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.provinces_id_seq', 1, false);


--
-- PostgreSQL database dump complete
--

