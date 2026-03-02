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
-- Name: build_daily_reports; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.build_daily_reports (
    id bigint NOT NULL,
    project_id uuid NOT NULL,
    tanggal date NOT NULL,
    cuaca character varying(255),
    jam_mulai time(0) without time zone,
    jam_selesai time(0) without time zone,
    pekerjaan text,
    catatan text,
    created_by uuid,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    total_jam integer,
    mk character varying(200),
    kontraktor_ttd character varying(200),
    uraian_manual character varying(150)
);


ALTER TABLE public.build_daily_reports OWNER TO postgres;

--
-- Name: build_daily_reports_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.build_daily_reports_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.build_daily_reports_id_seq OWNER TO postgres;

--
-- Name: build_daily_reports_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.build_daily_reports_id_seq OWNED BY public.build_daily_reports.id;


--
-- Name: build_daily_reports id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.build_daily_reports ALTER COLUMN id SET DEFAULT nextval('public.build_daily_reports_id_seq'::regclass);


--
-- Data for Name: build_daily_reports; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.build_daily_reports (id, project_id, tanggal, cuaca, jam_mulai, jam_selesai, pekerjaan, catatan, created_by, created_at, updated_at, total_jam, mk, kontraktor_ttd, uraian_manual) FROM stdin;
22	019c97e0-d4ef-7254-b5b6-f401008c5578	2026-02-27	Baik	\N	\N	\N	nihil	\N	2026-02-27 06:56:42	2026-02-27 06:56:42	2	\N	\N	\N
23	019c97e0-d4ef-7254-b5b6-f401008c5578	2026-03-14	Baik	08:00:00	09:00:00	\N	\N	\N	2026-02-27 07:21:18	2026-02-27 07:21:18	1	\N	\N	\N
26	019c97e0-d4ef-7254-b5b6-f401008c5578	2026-03-07	Baik	08:59:00	09:00:00	\N	\N	\N	2026-02-27 07:26:16	2026-02-27 07:26:16	2	\N	\N	\N
28	019c97e0-d4ef-7254-b5b6-f401008c5578	2026-03-21	Baik	08:59:00	09:00:00	\N	nihil	\N	2026-02-27 07:31:16	2026-02-27 07:31:16	2	\N	\N	\N
\.


--
-- Name: build_daily_reports_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.build_daily_reports_id_seq', 28, true);


--
-- Name: build_daily_reports build_daily_reports_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.build_daily_reports
    ADD CONSTRAINT build_daily_reports_pkey PRIMARY KEY (id);


--
-- Name: build_daily_reports build_daily_reports_created_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.build_daily_reports
    ADD CONSTRAINT build_daily_reports_created_by_foreign FOREIGN KEY (created_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: build_daily_reports build_daily_reports_project_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.build_daily_reports
    ADD CONSTRAINT build_daily_reports_project_id_foreign FOREIGN KEY (project_id) REFERENCES public.projects(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

