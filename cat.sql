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
-- Name: rab_process_categories; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.rab_process_categories (
    id bigint NOT NULL,
    rab_process_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    order_no integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.rab_process_categories OWNER TO postgres;

--
-- Name: rab_process_categories_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.rab_process_categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.rab_process_categories_id_seq OWNER TO postgres;

--
-- Name: rab_process_categories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.rab_process_categories_id_seq OWNED BY public.rab_process_categories.id;


--
-- Name: rab_process_categories id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.rab_process_categories ALTER COLUMN id SET DEFAULT nextval('public.rab_process_categories_id_seq'::regclass);


--
-- Name: rab_process_categories rab_process_categories_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.rab_process_categories
    ADD CONSTRAINT rab_process_categories_pkey PRIMARY KEY (id);


--
-- Name: rab_process_categories rab_process_categories_rab_process_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.rab_process_categories
    ADD CONSTRAINT rab_process_categories_rab_process_id_foreign FOREIGN KEY (rab_process_id) REFERENCES public.rab_process(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

