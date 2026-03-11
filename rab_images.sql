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
-- Name: rab_images; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.rab_images (
    id bigint NOT NULL,
    path character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.rab_images OWNER TO postgres;

--
-- Name: rab_images_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.rab_images_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.rab_images_id_seq OWNER TO postgres;

--
-- Name: rab_images_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.rab_images_id_seq OWNED BY public.rab_images.id;


--
-- Name: rab_images id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.rab_images ALTER COLUMN id SET DEFAULT nextval('public.rab_images_id_seq'::regclass);


--
-- Data for Name: rab_images; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.rab_images (id, path, created_at, updated_at) FROM stdin;
\.


--
-- Name: rab_images_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.rab_images_id_seq', 10, true);


--
-- Name: rab_images rab_images_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.rab_images
    ADD CONSTRAINT rab_images_pkey PRIMARY KEY (id);


--
-- PostgreSQL database dump complete
--

