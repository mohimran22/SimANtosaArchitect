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
-- Name: build_daily_works; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.build_daily_works (
    id bigint NOT NULL,
    build_daily_report_id bigint NOT NULL,
    rab_process_item_id bigint,
    volume numeric(8,2) NOT NULL,
    satuan character varying(255) NOT NULL,
    keterangan character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    uraian_manual character varying(150)
);


ALTER TABLE public.build_daily_works OWNER TO postgres;

--
-- Name: build_daily_works_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.build_daily_works_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.build_daily_works_id_seq OWNER TO postgres;

--
-- Name: build_daily_works_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.build_daily_works_id_seq OWNED BY public.build_daily_works.id;


--
-- Name: build_daily_works id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.build_daily_works ALTER COLUMN id SET DEFAULT nextval('public.build_daily_works_id_seq'::regclass);


--
-- Data for Name: build_daily_works; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.build_daily_works (id, build_daily_report_id, rab_process_item_id, volume, satuan, keterangan, created_at, updated_at, uraian_manual) FROM stdin;
6	26	76	70.00	m2	\N	2026-02-27 07:26:16	2026-02-27 07:26:16	\N
8	28	\N	20.00	m2	jja	2026-02-27 07:31:16	2026-02-27 07:31:16	nanan
\.


--
-- Name: build_daily_works_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.build_daily_works_id_seq', 8, true);


--
-- Name: build_daily_works build_daily_works_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.build_daily_works
    ADD CONSTRAINT build_daily_works_pkey PRIMARY KEY (id);


--
-- Name: build_daily_works build_daily_works_build_daily_report_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.build_daily_works
    ADD CONSTRAINT build_daily_works_build_daily_report_id_foreign FOREIGN KEY (build_daily_report_id) REFERENCES public.build_daily_reports(id) ON DELETE CASCADE;


--
-- Name: build_daily_works build_daily_works_rab_process_item_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.build_daily_works
    ADD CONSTRAINT build_daily_works_rab_process_item_id_foreign FOREIGN KEY (rab_process_item_id) REFERENCES public.rab_process_items(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

