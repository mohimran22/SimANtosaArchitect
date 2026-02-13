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
-- Name: build_process_items; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.build_process_items (
    id bigint NOT NULL,
    project_id uuid NOT NULL,
    rab_item_id bigint,
    uraian character varying(255) NOT NULL,
    volume numeric(14,2),
    satuan character varying(50),
    bobot_percent numeric(6,2) DEFAULT '0'::numeric NOT NULL,
    plan_week_start integer,
    plan_week_end integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    category character varying(200),
    price numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    job_category_id bigint,
    total numeric(15,2) DEFAULT '0'::numeric NOT NULL
);


ALTER TABLE public.build_process_items OWNER TO postgres;

--
-- Name: build_process_items_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.build_process_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.build_process_items_id_seq OWNER TO postgres;

--
-- Name: build_process_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.build_process_items_id_seq OWNED BY public.build_process_items.id;


--
-- Name: build_process_items id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.build_process_items ALTER COLUMN id SET DEFAULT nextval('public.build_process_items_id_seq'::regclass);


--
-- Data for Name: build_process_items; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.build_process_items (id, project_id, rab_item_id, uraian, volume, satuan, bobot_percent, plan_week_start, plan_week_end, created_at, updated_at, category, price, job_category_id, total) FROM stdin;
\.


--
-- Name: build_process_items_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.build_process_items_id_seq', 22, true);


--
-- Name: build_process_items build_process_items_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.build_process_items
    ADD CONSTRAINT build_process_items_pkey PRIMARY KEY (id);


--
-- Name: build_process_items build_process_items_job_category_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.build_process_items
    ADD CONSTRAINT build_process_items_job_category_id_foreign FOREIGN KEY (job_category_id) REFERENCES public.job_categories(id) ON DELETE SET NULL;


--
-- Name: build_process_items build_process_items_project_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.build_process_items
    ADD CONSTRAINT build_process_items_project_id_foreign FOREIGN KEY (project_id) REFERENCES public.projects(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

