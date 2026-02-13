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
-- Name: build_weekly_progress; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.build_weekly_progress (
    id bigint NOT NULL,
    build_process_item_id bigint NOT NULL,
    progress_percent numeric(5,2) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    week_no integer NOT NULL,
    volume numeric(12,3) DEFAULT '0'::numeric NOT NULL,
    bobot_percent numeric(6,3) DEFAULT '0'::numeric NOT NULL
);


ALTER TABLE public.build_weekly_progress OWNER TO postgres;

--
-- Name: build_weekly_progress_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.build_weekly_progress_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.build_weekly_progress_id_seq OWNER TO postgres;

--
-- Name: build_weekly_progress_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.build_weekly_progress_id_seq OWNED BY public.build_weekly_progress.id;


--
-- Name: build_weekly_progress id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.build_weekly_progress ALTER COLUMN id SET DEFAULT nextval('public.build_weekly_progress_id_seq'::regclass);


--
-- Data for Name: build_weekly_progress; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.build_weekly_progress (id, build_process_item_id, progress_percent, created_at, updated_at, week_no, volume, bobot_percent) FROM stdin;
\.


--
-- Name: build_weekly_progress_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.build_weekly_progress_id_seq', 12, true);


--
-- Name: build_weekly_progress build_weekly_progress_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.build_weekly_progress
    ADD CONSTRAINT build_weekly_progress_pkey PRIMARY KEY (id);


--
-- Name: build_weekly_progress_week_no_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX build_weekly_progress_week_no_index ON public.build_weekly_progress USING btree (week_no);


--
-- Name: build_weekly_progress build_weekly_progress_build_process_item_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.build_weekly_progress
    ADD CONSTRAINT build_weekly_progress_build_process_item_id_foreign FOREIGN KEY (build_process_item_id) REFERENCES public.build_process_items(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

