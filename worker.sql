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
-- Name: build_daily_workers; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.build_daily_workers (
    id bigint NOT NULL,
    daily_report_id bigint NOT NULL,
    keahlian character varying(255),
    jumlah integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    alat character varying(255) NOT NULL,
    worker_id uuid,
    uraian_manual character varying(200)
);


ALTER TABLE public.build_daily_workers OWNER TO postgres;

--
-- Name: build_daily_workers_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.build_daily_workers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.build_daily_workers_id_seq OWNER TO postgres;

--
-- Name: build_daily_workers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.build_daily_workers_id_seq OWNED BY public.build_daily_workers.id;


--
-- Name: build_daily_workers id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.build_daily_workers ALTER COLUMN id SET DEFAULT nextval('public.build_daily_workers_id_seq'::regclass);


--
-- Data for Name: build_daily_workers; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.build_daily_workers (id, daily_report_id, keahlian, jumlah, created_at, updated_at, alat, worker_id, uraian_manual) FROM stdin;
24	22	Tukang Urug	20	2026-02-27 06:56:42	2026-02-27 06:56:42	Cangkul	\N	\N
25	22	\N	20	2026-02-27 06:56:42	2026-02-27 06:56:42	Kuas	019c9caa-ddec-7016-be99-4ab4a38685f2	\N
26	23	\N	20	2026-02-27 07:21:18	2026-02-27 07:21:18	iaiaao	019c9caa-ddec-7016-be99-4ab4a38685f2	\N
27	23	baabab	20	2026-02-27 07:21:18	2026-02-27 07:21:18	nanaan	\N	\N
29	26	\N	20	2026-02-27 07:26:16	2026-02-27 07:26:16	hhh	019c9caa-ddec-7016-be99-4ab4a38685f2	\N
30	28	ddd	10	2026-02-27 07:31:16	2026-02-27 07:31:16	babab	\N	\N
\.


--
-- Name: build_daily_workers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.build_daily_workers_id_seq', 30, true);


--
-- Name: build_daily_workers build_daily_workers_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.build_daily_workers
    ADD CONSTRAINT build_daily_workers_pkey PRIMARY KEY (id);


--
-- Name: build_daily_workers build_daily_workers_daily_report_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.build_daily_workers
    ADD CONSTRAINT build_daily_workers_daily_report_id_foreign FOREIGN KEY (daily_report_id) REFERENCES public.build_daily_reports(id) ON DELETE CASCADE;


--
-- Name: build_daily_workers build_daily_workers_worker_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.build_daily_workers
    ADD CONSTRAINT build_daily_workers_worker_id_foreign FOREIGN KEY (worker_id) REFERENCES public.workers(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

