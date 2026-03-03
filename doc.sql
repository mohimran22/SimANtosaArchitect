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
-- Name: daily_documentations; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.daily_documentations (
    id bigint NOT NULL,
    daily_report_id bigint NOT NULL,
    category character varying(255) NOT NULL,
    file_path character varying(255) NOT NULL,
    file_name character varying(255) NOT NULL,
    file_type character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.daily_documentations OWNER TO postgres;

--
-- Name: daily_documentations_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.daily_documentations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.daily_documentations_id_seq OWNER TO postgres;

--
-- Name: daily_documentations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.daily_documentations_id_seq OWNED BY public.daily_documentations.id;


--
-- Name: daily_documentations id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.daily_documentations ALTER COLUMN id SET DEFAULT nextval('public.daily_documentations_id_seq'::regclass);


--
-- Data for Name: daily_documentations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.daily_documentations (id, daily_report_id, category, file_path, file_name, file_type, created_at, updated_at) FROM stdin;
\.


--
-- Name: daily_documentations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.daily_documentations_id_seq', 1, false);


--
-- Name: daily_documentations daily_documentations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.daily_documentations
    ADD CONSTRAINT daily_documentations_pkey PRIMARY KEY (id);


--
-- Name: daily_documentations daily_documentations_daily_report_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.daily_documentations
    ADD CONSTRAINT daily_documentations_daily_report_id_foreign FOREIGN KEY (daily_report_id) REFERENCES public.build_daily_reports(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

