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
-- Name: build_daily_materials; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.build_daily_materials (
    id bigint NOT NULL,
    daily_report_id bigint NOT NULL,
    nama_bahan character varying(255) NOT NULL,
    qty numeric(14,2),
    satuan character varying(50),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    diterima character varying(255) NOT NULL,
    ditolak character varying(255) NOT NULL
);


ALTER TABLE public.build_daily_materials OWNER TO postgres;

--
-- Name: build_daily_materials_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.build_daily_materials_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.build_daily_materials_id_seq OWNER TO postgres;

--
-- Name: build_daily_materials_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.build_daily_materials_id_seq OWNED BY public.build_daily_materials.id;


--
-- Name: build_daily_materials id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.build_daily_materials ALTER COLUMN id SET DEFAULT nextval('public.build_daily_materials_id_seq'::regclass);


--
-- Data for Name: build_daily_materials; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.build_daily_materials (id, daily_report_id, nama_bahan, qty, satuan, created_at, updated_at, diterima, ditolak) FROM stdin;
4	22	Semen Gresik	\N	\N	2026-02-27 06:56:42	2026-02-27 06:56:42	10	0
5	22	hhhv	\N	\N	2026-02-27 06:56:42	2026-02-27 06:56:42	10	0
6	23	aaaa	\N	\N	2026-02-27 07:21:18	2026-02-27 07:21:18	12	12
7	26	ddd	\N	\N	2026-02-27 07:26:16	2026-02-27 07:26:16	12	0
9	28	ddd	\N	\N	2026-02-27 07:31:16	2026-02-27 07:31:16	12	0
\.


--
-- Name: build_daily_materials_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.build_daily_materials_id_seq', 9, true);


--
-- Name: build_daily_materials build_daily_materials_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.build_daily_materials
    ADD CONSTRAINT build_daily_materials_pkey PRIMARY KEY (id);


--
-- Name: build_daily_materials build_daily_materials_daily_report_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.build_daily_materials
    ADD CONSTRAINT build_daily_materials_daily_report_id_foreign FOREIGN KEY (daily_report_id) REFERENCES public.build_daily_reports(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

