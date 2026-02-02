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
-- Name: districts; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.districts (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    city_id bigint NOT NULL,
    is_active boolean NOT NULL
);


ALTER TABLE public.districts OWNER TO postgres;

--
-- Name: districts_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.districts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.districts_id_seq OWNER TO postgres;

--
-- Name: districts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.districts_id_seq OWNED BY public.districts.id;


--
-- Name: districts id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.districts ALTER COLUMN id SET DEFAULT nextval('public.districts_id_seq'::regclass);


--
-- Data for Name: districts; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.districts (id, name, city_id, is_active) FROM stdin;
1	 Teupah Selatan	1	t
2	 Simeulue Timur	1	t
3	 Teupah Barat	1	t
4	 Teupah Tengah	1	t
5	 Simeulue Tengah	1	t
6	 Teluk Dalam	1	t
7	 Simeulue Cut	1	t
8	 Salang	1	t
9	 Simeulue Barat	1	t
10	 Alafan	1	t
11	 Pulau Banyak	2	t
12	 Pulau Banyak Barat	2	t
13	 Singkil	2	t
14	 Singkil Utara	2	t
15	 Kuala Baru	2	t
16	 Simpang Kanan	2	t
17	 Gunung Meriah	2	t
18	 Danau Paris	2	t
19	 Suro	2	t
20	 Singkohor	2	t
21	 Kota Baharu	2	t
22	 Trumon	3	t
23	 Trumon Timur	3	t
24	 Trumon Tengah	3	t
25	 Bakongan	3	t
26	 Bakongan Timur	3	t
27	 Kota Bahagia	3	t
28	 Kluet Selatan	3	t
29	 Kluet Timur	3	t
30	 Kluet Utara	3	t
31	 Pasie Raja	3	t
32	 Kluet Tengah	3	t
33	 Tapak Tuan	3	t
34	 Sama Dua	3	t
35	 Sawang	3	t
36	 Meukek	3	t
37	 Labuhan Haji	3	t
38	 Labuhan Haji Timur	3	t
39	 Labuhan Haji Barat	3	t
40	 Lawe Alas	4	t
41	 Babul Rahmad	4	t
42	 Tanoh Alas	4	t
43	 Lawe Sigala-gala	4	t
44	 Babul Makmur	4	t
45	 Semadam	4	t
46	 Lauser	4	t
47	 Bambel	4	t
48	 Bukit Tusam	4	t
49	 Lawe Sumur	4	t
50	 Babussalam	4	t
51	 Lawe Bulan	4	t
52	 Badar	4	t
53	 Darul Hasanah	4	t
54	 Ketambe	4	t
55	 Deleng Pokhkisen	4	t
56	 Serba Jadi	5	t
57	 Simpang Jernih	5	t
58	 Peunaron	5	t
59	 Birem Bayeun	5	t
60	 Rantau Selamat	5	t
61	 Sungai Raya	5	t
62	 Peureulak	5	t
63	 Peureulak Timur	5	t
64	 Peureulak Barat	5	t
65	 Ranto Peureulak	5	t
66	 Idi Rayeuk	5	t
67	 Peudawa	5	t
68	 Banda Alam	5	t
69	 Idi Tunong	5	t
70	 Darul Ihsan	5	t
71	 Idi Timur	5	t
72	 Darul Aman	5	t
73	 Nurussalam	5	t
74	 Darul Falah	5	t
75	 Julok	5	t
76	 Indra Makmur	5	t
77	 Pante Bidari	5	t
78	 Simpang Ulim	5	t
79	 Madat	5	t
80	 Linge	6	t
81	 Atu Lintang	6	t
82	 Jagong Jeget	6	t
83	 Bintang	6	t
84	 Lut Tawar	6	t
85	 Kebayakan	6	t
86	 Pegasing	6	t
87	 Bies	6	t
88	 Bebesen	6	t
89	 Kute Panang	6	t
90	 Silih Nara	6	t
91	 Ketol	6	t
92	 Celala	6	t
93	 Rusip Antara	6	t
94	 Johan Pahlawan	7	t
95	 Samatiga	7	t
96	 Bubon	7	t
97	 Arongan Lambalek	7	t
98	 Woyla	7	t
99	 Woyla Barat	7	t
100	 Woyla Timur	7	t
101	 Kaway Xvi	7	t
102	 Meureubo	7	t
103	 Pantai Ceuremen	7	t
104	 Panton Reu	7	t
105	 Sungai Mas	7	t
106	 Lhoong	8	t
107	 Lhoknga	8	t
108	 Leupung	8	t
109	 Indrapuri	8	t
110	 Kuta Cot Glie	8	t
111	 Seulimeum	8	t
112	 Kota Jantho	8	t
113	 Lembah Seulawah	8	t
114	 Mesjid Raya	8	t
115	 Darussalam	8	t
116	 Baitussalam	8	t
117	 Kuta Baro	8	t
118	 Montasik	8	t
119	 Blang Bintang	8	t
120	 Ingin Jaya	8	t
121	 Krueng Barona Jaya	8	t
122	 Suka Makmur	8	t
123	 Kuta Malaka	8	t
124	 Simpang Tiga	8	t
125	 Darul Imarah	8	t
126	 Darul Kamal	8	t
127	 Peukan Bada	8	t
128	 Pulo Aceh	8	t
129	 Geumpang	9	t
130	 Mane	9	t
131	 Glumpang Tiga	9	t
132	 Glumpang Baro	9	t
133	 Mutiara	9	t
134	 Mutiara Timur	9	t
135	 Tiro/truseb	9	t
136	 Tangse	9	t
137	 Keumala	9	t
138	 Titeue	9	t
139	 Sakti	9	t
140	 Mila	9	t
141	 Padang Tiji	9	t
142	 Delima	9	t
143	 Grong Grong	9	t
144	 Indrajaya	9	t
145	 Peukan Baro	9	t
146	 Kembang Tanjong	9	t
147	 Simpang Tiga	9	t
148	 Kota Sigli	9	t
149	 Pidie	9	t
150	 Batee	9	t
151	 Muara Tiga	9	t
152	 Samalanga	10	t
153	 Simpang Mamplam	10	t
154	 Pandrah	10	t
155	 Jeunieb	10	t
156	 Peulimbang	10	t
157	 Peudada	10	t
158	 Juli	10	t
159	 Jeumpa	10	t
160	 Kota Juang	10	t
161	 Kuala	10	t
162	 Jangka	10	t
163	 Peusangan	10	t
164	 Peusangan Selatan	10	t
165	 Peusangan Siblah Krueng	10	t
166	 Makmur	10	t
167	 Ganda Pura	10	t
168	 Kuta Blang	10	t
169	 Sawang	11	t
170	 Nisam	11	t
171	 Nisam Antara	11	t
172	 Banda Baro	11	t
173	 Kuta Makmur	11	t
174	 Simpang Keramat	11	t
175	 Syamtalira Bayu	11	t
176	 Geureudong Pase	11	t
177	 Meurah Mulia	11	t
178	 Matangkuli	11	t
179	 Paya Bakong	11	t
180	 Pirak Timu	11	t
181	 Cot Girek	11	t
182	 Tanah Jambo Aye	11	t
183	 Langkahan	11	t
184	 Seunudon	11	t
185	 Baktiya	11	t
186	 Baktiya Barat	11	t
187	 Lhoksukon	11	t
188	 Tanah Luas	11	t
189	 Nibong	11	t
190	 Samudera	11	t
191	 Syamtalira Aron	11	t
192	 Tanah Pasir	11	t
193	 Lapang	11	t
194	 Muara Batu	11	t
195	 Dewantara	11	t
196	 Manggeng	12	t
197	 Lembah Sabil	12	t
198	 Tangan-tangan	12	t
199	 Setia	12	t
200	 Blang Pidie	12	t
201	 Jeumpa	12	t
202	 Susoh	12	t
203	 Kuala Batee	12	t
204	 Babah Rot	12	t
205	 Kuta Panjang	13	t
206	 Blang Jerango	13	t
207	 Blangkejeren	13	t
208	 Putri Betung	13	t
209	 Dabun Gelang	13	t
210	 Blang Pegayon	13	t
211	 Pining	13	t
212	 Rikit Gaib	13	t
213	 Pantan Cuaca	13	t
214	 Terangun	13	t
215	 Tripe Jaya	13	t
216	 Tamiang Hulu	14	t
217	 Bandar Pusaka	14	t
218	 Kejuruan Muda	14	t
219	 Tenggulun	14	t
220	 Rantau	14	t
221	 Kota Kuala Simpang	14	t
222	 Seruway	14	t
223	 Bendahara	14	t
224	 Banda Mulia	14	t
225	 Karang Baru	14	t
226	 Sekerak	14	t
227	 Manyak Payed	14	t
228	 Darul Makmur	15	t
229	 Tripa Makmur	15	t
230	 Kuala	15	t
231	 Kuala Pesisir	15	t
232	 Tadu Raya	15	t
233	 Beutong	15	t
234	 Beutong Ateuh Banggalang	15	t
235	 Seunagan	15	t
236	 Suka Makmue	15	t
237	 Seunagan Timur	15	t
238	 Teunom	16	t
239	 Pasie Raya	16	t
240	 Panga	16	t
241	 Krueng Sabee	16	t
242	 Setia Bakti	16	t
243	 Sampoiniet	16	t
244	 Darul Hikmah	16	t
245	 Jaya	16	t
246	 Indra Jaya	16	t
247	 Timang Gajah	17	t
248	 Gajah Putih	17	t
249	 Pintu Rime Gayo	17	t
250	 Bukit	17	t
251	 Wih Pesam	17	t
252	 Bandar	17	t
253	 Bener Kelipah	17	t
254	 Syiah Utama	17	t
255	 Mesidah	17	t
256	 Permata	17	t
257	 Meureudu	18	t
258	 Meurah Dua	18	t
259	 Bandar Dua	18	t
260	 Jangka Buya	18	t
261	 Ulim	18	t
262	 Trienggadeng	18	t
263	 Panteraja	18	t
264	 Bandar Baru	18	t
265	 Meuraxa	19	t
266	 Jaya Baru	19	t
267	 Banda Raya	19	t
268	 Baiturrahman	19	t
269	 Lueng Bata	19	t
270	 Kuta Alam	19	t
271	 Kuta Raja	19	t
272	 Syiah Kuala	19	t
273	 Ulee Kareng	19	t
274	 Sukajaya	20	t
275	 Sukakarya	20	t
276	 Langsa Timur	21	t
277	 Langsa Lama	21	t
278	 Langsa Barat	21	t
279	 Langsa Baro	21	t
280	 Langsa Kota	21	t
281	 Blang Mangat	22	t
282	 Muara Dua	22	t
283	 Muara Satu	22	t
284	 Banda Sakti	22	t
285	 Simpang Kiri	23	t
286	 Penanggalan	23	t
287	 Rundeng	23	t
288	 Sultan Daulat	23	t
289	 Longkib	23	t
290	 Idano Gawo	24	t
291	 Bawolato	24	t
292	 Ulugawo	24	t
293	 Gido	24	t
294	 Ma U	24	t
295	 Somolo - Molo	24	t
296	 Hiliduho	24	t
297	 Hili Serangkai	24	t
298	 Botomuzoi	24	t
299	 Batahan	25	t
300	 Sinunukan	25	t
301	 Batang Natal	25	t
302	 Lingga Bayu	25	t
303	 Ranto Baek	25	t
304	 Kotanopan	25	t
305	 Ulu Pungkut	25	t
306	 Tambangan	25	t
307	 Lembah Sorik Marapi	25	t
308	 Puncak Sorik Marapi	25	t
309	 Muara Sipongi	25	t
310	 Pakantan	25	t
311	 Panyabungan	25	t
312	 Panyabungan Selatan	25	t
313	 Panyabungan Barat	25	t
314	 Panyabungan Utara	25	t
315	 Panyabungan Timur	25	t
316	 Huta Bargot	25	t
317	 Natal	25	t
318	 Muara Batang Gadis	25	t
319	 Siabu	25	t
320	 Bukit Malintang	25	t
321	 Naga Juang	25	t
322	 Batang Angkola	26	t
323	 Sayur Matinggi	26	t
324	 Tano Tombangan Angkola	26	t
325	 Angkola Timur	26	t
326	 Angkola Selatan	26	t
327	 Angkola  Barat	26	t
328	 Angkola Sangkunur	26	t
329	 Batang Toru	26	t
330	 Marancar	26	t
331	 Muara Batang Toru	26	t
332	 Sipirok	26	t
333	 Arse	26	t
334	 Saipar Dolok Hole	26	t
335	 Aek Bilah	26	t
336	 Pinang Sori	27	t
337	 Badiri	27	t
338	 Sibabangun	27	t
339	 Lumut	27	t
340	 Sukabangun	27	t
341	 Pandan	27	t
342	 Tukka	27	t
343	 Sarudik	27	t
344	 Tapian Nauli	27	t
345	 Sitahuis	27	t
346	 Kolang	27	t
347	 Sorkam	27	t
348	 Sorkam Barat	27	t
349	 Pasaribu Tobing	27	t
350	 Barus	27	t
351	 Sosor Gadong	27	t
352	 Andam Dewi	27	t
353	 Barus Utara	27	t
354	 Manduamas	27	t
355	 Sirandorung	27	t
356	 Parmonangan	28	t
357	 Adian Koting	28	t
358	 Sipoholon	28	t
359	 Tarutung	28	t
360	 Siatas Barita	28	t
361	 Pahae Julu	28	t
362	 Pahae Jae	28	t
363	 Purbatua	28	t
364	 Simangumban	28	t
365	 Pangaribuan	28	t
366	 Garoga	28	t
367	 Sipahutar	28	t
368	 Siborong-borong	28	t
369	 Pagaran	28	t
370	 Muara	28	t
371	 Balige	29	t
372	 Tampahan	29	t
373	 Laguboti	29	t
374	 Habinsaran	29	t
375	 Borbor	29	t
376	 Nassau	29	t
377	 Silaen	29	t
378	 Sigumpar	29	t
379	 Porsea	29	t
380	 Pintu Pohan Meranti	29	t
381	 Siantar Narumonda	29	t
382	 Parmaksian	29	t
383	 Lumban Julu	29	t
384	 Uluan	29	t
385	 Ajibata	29	t
386	 Bonatua Lunasi	29	t
387	 Bilah Hulu	30	t
388	 Pangkatan	30	t
389	 Bilah Barat	30	t
390	 Bilah Hilir	30	t
391	 Panai Hulu	30	t
392	 Panai Tengah	30	t
393	 Panai Hilir	30	t
394	 Rantau Selatan	30	t
395	 Rantau Utara	30	t
396	 Bandar Pasir Mandoge	31	t
397	 Bandar Pulau	31	t
398	 Aek Songsongan	31	t
399	 Rahuning	31	t
400	 Pulau Rakyat	31	t
401	 Aek Kuasan	31	t
402	 Aek Ledong	31	t
403	 Sei Kepayang	31	t
404	 Sei Kepayang Barat	31	t
405	 Sei Kepayang Timur	31	t
406	 Tanjung Balai	31	t
407	 Simpang Empat	31	t
408	 Teluk Dalam	31	t
409	 Air Batu	31	t
410	 Sei Dadap	31	t
411	 Buntu Pane	31	t
412	 Tinggi Raja	31	t
413	 Setia Janji	31	t
414	 Meranti	31	t
415	 Pulo Bandring	31	t
416	 Rawang Panca Arga	31	t
417	 Air Joman	31	t
418	 Silau Laut	31	t
419	 Kisaran Barat	31	t
420	 Kisaran Timur	31	t
421	 Silimakuta	32	t
422	 Pematang Silimahuta	32	t
423	 Purba	32	t
424	 Haranggaol Horison	32	t
425	 Dolok Pardamean	32	t
426	 Sidamanik	32	t
427	 Pematang Sidamanik	32	t
428	 Girsang Sipangan Bolon	32	t
429	 Tanah Jawa	32	t
430	 Hatonduhan	32	t
431	 Dolok Panribuan	32	t
432	 Jorlang Hataran	32	t
433	 Panei	32	t
434	 Panombean Panei	32	t
435	 Raya	32	t
436	 Dolok Silau	32	t
437	 Silau Kahean	32	t
438	 Raya Kahean	32	t
439	 Tapian Dolok	32	t
440	 Dolok Batu Nanggar	32	t
441	 Siantar	32	t
442	 Gunung Malela	32	t
443	 Gunung Maligas	32	t
444	 Hutabayu Raja	32	t
445	 Jawa Maraja Bah Jambi	32	t
446	 Pematang Bandar	32	t
447	 Bandar Huluan	32	t
448	 Bandar	32	t
449	 Bandar Masilam	32	t
450	 Bosar Maligas	32	t
451	 Ujung Padang	32	t
452	 Sidikalang	33	t
453	 Berampu	33	t
454	 Sitinjo	33	t
455	 Parbuluan	33	t
456	 Sumbul	33	t
457	 Silahi Sabungan	33	t
458	 Silima Pungga-pungga	33	t
459	 Lae Parira	33	t
460	 Siempat Nempu	33	t
461	 Siempat Nempu Hulu	33	t
462	 Siempat Nempu Hilir	33	t
463	 Tiga Lingga	33	t
464	 Gunung Sitember	33	t
465	 Pegagan Hilir	33	t
466	 Tanah Pinem	33	t
467	 Mardingding	34	t
468	 Laubaleng	34	t
469	 Tiga Binanga	34	t
470	 Juhar	34	t
471	 Munte	34	t
472	 Kuta Buluh	34	t
473	 Payung	34	t
474	 Tiganderket	34	t
475	 Simpang Empat	34	t
476	 Naman Teran	34	t
477	 Merdeka	34	t
478	 Kabanjahe	34	t
479	 Berastagi	34	t
480	 Tigapanah	34	t
481	 Dolat Rayat	34	t
482	 Merek	34	t
483	 Barusjahe	34	t
484	 Gunung Meriah	35	t
485	 Sinembah Tanjung Muda Hulu	35	t
486	 Sibolangit	35	t
487	 Kutalimbaru	35	t
488	 Pancur Batu	35	t
489	 Namo Rambe	35	t
490	 Biru-biru	35	t
491	 Sinembah Tanjung Muda Hilir	35	t
492	 Bangun Purba	35	t
493	 Galang	35	t
494	 Tanjung Morawa	35	t
495	 Patumbak	35	t
496	 Deli Tua	35	t
497	 Sunggal	35	t
498	 Hamparan Perak	35	t
499	 Labuhan Deli	35	t
500	 Percut Sei Tuan	35	t
501	 Batang Kuis	35	t
502	 Pantai Labu	35	t
503	 Beringin	35	t
504	 Lubuk Pakam	35	t
505	 Pagar Merbau	35	t
506	 Bohorok	36	t
507	 Sirapit	36	t
508	 Salapian	36	t
509	 Kutambaru	36	t
510	 Sei Bingai	36	t
511	 Kuala	36	t
512	 Selesai	36	t
513	 Binjai	36	t
514	 Stabat	36	t
515	 Wampu	36	t
516	 Batang Serangan	36	t
517	 Sawit Seberang	36	t
518	 Padang Tualang	36	t
519	 Hinai	36	t
520	 Secanggang	36	t
521	 Tanjung Pura	36	t
522	 Gebang	36	t
523	 Babalan	36	t
524	 Sei Lepan	36	t
525	 Brandan Barat	36	t
526	 Besitang	36	t
527	 Pangkalan Susu	36	t
528	 Pematang Jaya	36	t
529	 Hibala	37	t
530	 Pulau-pulau Batu	37	t
531	 Pulau-pulau Batu Timur	37	t
532	 Teluk Dalam	37	t
533	 Fanayama	37	t
534	 Toma	37	t
535	 Maniamolo	37	t
536	 Mazino	37	t
537	 Amandraya	37	t
538	 Aramo	37	t
539	 Lahusa	37	t
540	 Gomo	37	t
541	 Susua	37	t
542	 Mazo	37	t
543	 Umbunasi	37	t
544	 Lolomatua	37	t
545	 Lolowau	37	t
546	 Hilimegai	37	t
547	 Pakkat	38	t
548	 Onan Ganjang	38	t
549	 Sijama Polang	38	t
550	 Dolok Sanggul	38	t
551	 Lintong Nihuta	38	t
552	 Paranginan	38	t
553	 Bakti Raja	38	t
554	 Pollung	38	t
555	 Parlilitan	38	t
556	 Tara Bintang	38	t
557	 Salak	39	t
558	 Sitellu Tali Urang Jehe	39	t
559	 Pagindar	39	t
560	 Sitellu Tali Urang Julu	39	t
561	 Pergetteng-getteng Sengkut	39	t
562	 Kerajaan	39	t
563	 Tinada	39	t
564	 Siempat Rube	39	t
565	 Sianjur Mula Mula	40	t
566	 Harian	40	t
567	 Sitio-tio	40	t
568	 Onan Runggu	40	t
569	 Nainggolan	40	t
570	 Palipi	40	t
571	 Ronggur Nihuta	40	t
572	 Pangururan	40	t
573	 Simanindo	40	t
574	 Kotarih	41	t
575	 Silinda	41	t
576	 Bintang Bayu	41	t
577	 Dolok Masihul	41	t
578	 Serbajadi	41	t
579	 Sipispis	41	t
580	 Dolok Merawan	41	t
581	 Tebingtinggi	41	t
582	 Tebing Syahbandar	41	t
583	 Bandar Khalipah	41	t
584	 Tanjung Beringin	41	t
585	 Sei Rampah	41	t
586	 Sei Bamban	41	t
587	 Teluk Mengkudu	41	t
588	 Perbaungan	41	t
589	 Pegajahan	41	t
590	 Pantai Cermin	41	t
591	 Sei Balai	42	t
592	 Tanjung Tiram	42	t
593	 Talawi	42	t
594	 Limapuluh	42	t
595	 Air Putih	42	t
596	 Sei Suka	42	t
597	 Medang Deras	42	t
598	 Batang Onang	43	t
599	 Padang Bolak Julu	43	t
600	 Portibi	43	t
601	 Padang Bolak	43	t
602	 Simangambat	43	t
603	 Halongonan	43	t
604	 Dolok	43	t
605	 Dolok Sigompulon	43	t
606	 Hulu Sihapas	43	t
607	 Sosopan	44	t
608	 Ulu Barumun	44	t
609	 Barumun	44	t
610	 Barumun Selatan	44	t
611	 Lubuk Barumun	44	t
612	 Sosa	44	t
613	 Batang Lubu Sutam	44	t
614	 Huta Raja Tinggi	44	t
615	 Huristak	44	t
616	 Barumun Tengah	44	t
617	 Aek Nabara Barumun	44	t
618	 Sihapas Barumun	44	t
619	 Sungai Kanan	45	t
620	 Torgamba	45	t
621	 Kota Pinang	45	t
622	 Silangkitang	45	t
623	 Kampung Rakyat	45	t
624	 Na Ix-x	46	t
625	 Marbau	46	t
626	 Aek Kuo	46	t
627	 Aek Natas	46	t
628	 Kualuh Selatan	46	t
629	 Kualuh Hilir	46	t
630	 Kualuh Hulu	46	t
631	 Kualuh Leidong	46	t
632	 Tugala Oyo	47	t
633	 Alasa	47	t
634	 Alasa Talu Muzoi	47	t
635	 Namohalu Esiwa	47	t
636	 Sitolu Ori	47	t
637	 Tuhemberua	47	t
638	 Sawo	47	t
639	 Lotu	47	t
640	 Lahewa Timur	47	t
641	 Afulu	47	t
642	 Lahewa	47	t
643	 Sirombu	48	t
644	 Lahomi	48	t
645	 Ulu Moro O	48	t
646	 Lolofitu Moi	48	t
647	 Mandrehe Utara	48	t
648	 Mandrehe	48	t
649	 Mandrehe Barat	48	t
650	 Moro O	48	t
651	 Sibolga Utara	49	t
652	 Sibolga Kota	49	t
653	 Sibolga Selatan	49	t
654	 Sibolga Sambas	49	t
655	 Datuk Bandar	50	t
656	 Datuk Bandar Timur	50	t
657	 Tanjung Balai Selatan	50	t
658	 Tanjung Balai Utara	50	t
659	 Sei Tualang Raso	50	t
660	 Teluk Nibung	50	t
661	 Siantar Marihat	51	t
662	 Siantar Marimbun	51	t
663	 Siantar Selatan	51	t
664	 Siantar Barat	51	t
665	 Siantar Utara	51	t
666	 Siantar Timur	51	t
667	 Siantar Martoba	51	t
668	 Siantar Sitalasari	51	t
669	 Padang Hulu	52	t
670	 Tebing Tinggi Kota	52	t
671	 Rambutan	52	t
672	 Bajenis	52	t
673	 Padang Hilir	52	t
674	 Medan Tuntungan	53	t
675	 Medan Johor	53	t
676	 Medan Amplas	53	t
677	 Medan Denai	53	t
678	 Medan Area	53	t
679	 Medan Kota	53	t
680	 Medan Maimun	53	t
681	 Medan Polonia	53	t
682	 Medan Baru	53	t
683	 Medan Selayang	53	t
684	 Medan Sunggal	53	t
685	 Medan Helvetia	53	t
686	 Medan Petisah	53	t
687	 Medan Barat	53	t
688	 Medan Timur	53	t
689	 Medan Perjuangan	53	t
690	 Medan Tembung	53	t
691	 Medan Deli	53	t
692	 Medan Labuhan	53	t
693	 Medan Marelan	53	t
694	 Medan Belawan	53	t
695	 Binjai Selatan	54	t
696	 Binjai Kota	54	t
697	 Binjai Timur	54	t
698	 Binjai Utara	54	t
699	 Binjai Barat	54	t
700	 Padangsidimpuan Tenggara	55	t
701	 Padangsidimpuan Selatan	55	t
702	 Padangsidimpuan Batunadua	55	t
703	 Padangsidimpuan Utara	55	t
704	 Padangsidimpuan Hutaimbaru	55	t
705	 Padangsidimpuan Angkola Julu	55	t
706	 Gunungsitoli Idanoi	56	t
707	 Gunungsitoli Selatan	56	t
708	 Gunungsitoli Barat	56	t
709	 Gunung Sitoli	56	t
710	 Gunungsitoli Alo Oa	56	t
711	 Gunungsitoli Utara	56	t
712	 Pagai Selatan	57	t
713	 Sikakap	57	t
714	 Pagai Utara	57	t
715	 Sipora Selatan	57	t
716	 Sipora Utara	57	t
835	 Sungai Rumbai	67	t
717	 Siberut Selatan	57	t
718	 Seberut Barat Daya	57	t
719	 Siberut Tengah	57	t
720	 Siberut Utara	57	t
721	 Siberut Barat	57	t
722	 Lunang Silaut	58	t
723	 Basa Ampek Balai Tapan	58	t
724	 Pancung Soal	58	t
725	 Linggo Sari Baganti	58	t
726	 Ranah Pesisir	58	t
727	 Lengayang	58	t
728	 Sutera	58	t
729	 Batang Kapas	58	t
730	 Iv Jurai	58	t
731	 Bayang	58	t
732	 Iv  Nagari Bayang Utara	58	t
733	 Koto Xi Tarusan	58	t
734	 Pantai Cermin	59	t
735	 Lembah Gumanti	59	t
736	 Hiliran Gumanti	59	t
737	 Payung Sekaki	59	t
738	 Tigo Lurah	59	t
739	 Lembang Jaya	59	t
740	 Danau Kembar	59	t
741	 Gunung Talang	59	t
742	 Bukit Sundi	59	t
743	 Ix Koto Sungai Lasi	59	t
744	 Kubung	59	t
745	 X Koto Diatas	59	t
746	 X Koto Singkarak	59	t
747	 Junjung Sirih	59	t
748	 Kamang Baru	60	t
749	 Tanjung Gadang	60	t
750	 Sijunjung	60	t
751	 Lubuk Tarok	60	t
752	 Iv Nagari	60	t
753	 Kupitan	60	t
754	 Koto Tujuh	60	t
755	 Sumpur Kudus	60	t
756	 Sepuluh Koto	61	t
757	 Batipuh	61	t
758	 Batipuh Selatan	61	t
759	 Pariangan	61	t
760	 Rambatan	61	t
761	 Lima Kaum	61	t
762	 Tanjung Emas	61	t
763	 Padang Ganting	61	t
764	 Lintau Buo	61	t
765	 Lintau Buo Utara	61	t
766	 Sungayang	61	t
767	 Sungai Tarab	61	t
768	 Salimpaung	61	t
769	 Tanjung Baru	61	t
770	 Batang Anai	62	t
771	 Lubuk Alung	62	t
772	 Sintuk Toboh Gadang	62	t
773	 Ulakan Tapakis	62	t
774	 Nan Sabaris	62	t
775	 2 X 11 Enam Lingkung	62	t
776	 Enam Lingkung	62	t
777	 2 X 11 Kayu Tanam	62	t
778	 Vii Koto Sungai Sariak	62	t
779	 Patamuan	62	t
780	 Padang Sago	62	t
781	 V Koto Kp Dalam	62	t
782	 V Koto Timur	62	t
783	 Sungai Limau	62	t
784	 Batang Gasan	62	t
785	 Sungai Geringging	62	t
786	 Iv Koto Aur Malintang	62	t
787	 Tanjung Mutiara	63	t
788	 Lubuk Basung	63	t
789	 Ampek Nagari	63	t
790	 Tanjung Raya	63	t
791	 Matur	63	t
792	 Iv Koto	63	t
793	 Malalak	63	t
794	 Banuhampu	63	t
795	 Sungai Pua	63	t
796	 Ampek Angkek	63	t
797	 Canduang	63	t
798	 Baso	63	t
799	 Tilatang Kamang	63	t
800	 Kamang Magek	63	t
801	 Palembayan	63	t
802	 Palupuh	63	t
803	 Payakumbuh	64	t
804	 Akabiluru	64	t
805	 Luak	64	t
806	 Lareh Sago Halaban	64	t
807	 Situjuah Limo Nagari	64	t
808	 Harau	64	t
809	 Guguak	64	t
810	 Mungka	64	t
811	 Suliki	64	t
812	 Bukik Barisan	64	t
813	 Gunuang Omeh	64	t
814	 Kapur Ix	64	t
815	 Pangkalan Koto Baru	64	t
816	 Bonjol	65	t
817	 Tigo Nagari	65	t
818	 Simpang Alahan Mati	65	t
819	 Lubuk Sikaping	65	t
820	 Dua Koto	65	t
821	 Panti	65	t
822	 Padang Gelugur	65	t
823	 Rao	65	t
824	 Mapat Tunggul	65	t
825	 Mapat Tunggul Selatan	65	t
826	 Rao Selatan	65	t
827	 Rao Utara	65	t
828	 Sangir	66	t
829	 Sangir Jujuan	66	t
830	 Sangir Balai Janggo	66	t
831	 Sangir Batang Hari	66	t
832	 Sungai Pagu	66	t
833	 Pauah Duo	66	t
834	 Koto Parik Gadang Diateh	66	t
836	 Koto Besar	67	t
837	 Asam Jujuhan	67	t
838	 Koto Baru	67	t
839	 Koto Salak	67	t
840	 Tiumang	67	t
841	 Padang Laweh	67	t
842	 Sitiung	67	t
843	 Timpeh	67	t
844	 Pulau Punjung	67	t
845	 Ix Koto	67	t
846	 Sungai Beremas	68	t
847	 Ranah Batahan	68	t
848	 Koto Balingka	68	t
849	 Sungai Aur	68	t
850	 Lembah Malintang	68	t
851	 Gunung Tuleh	68	t
852	 Talamau	68	t
853	 Pasaman	68	t
854	 Luhak Nan Duo	68	t
855	 Sasak Ranah Pasisie	68	t
856	 Kinali	68	t
857	 Bungus Teluk Kabung	69	t
858	 Lubuk Kilangan	69	t
859	 Lubuk Begalung	69	t
860	 Padang Selatan	69	t
861	 Padang Timur	69	t
862	 Padang Barat	69	t
863	 Padang Utara	69	t
864	 Nanggalo	69	t
865	 Kuranji	69	t
866	 Pauh	69	t
867	 Koto Tangah	69	t
868	 Lubuk Sikarah	70	t
869	 Tanjung Harapan	70	t
870	 Silungkang	71	t
871	 Lembah Segar	71	t
872	 Barangin	71	t
873	 Talawi	71	t
874	 Padang Panjang Barat	72	t
875	 Padang Panjang Timur	72	t
876	 Guguk Panjang	73	t
877	 Mandiangin Koto Selayan	73	t
878	 Aur Birugo Tigo Baleh	73	t
879	 Payakumbuh Barat	74	t
880	 Payakumbuh Selatan	74	t
881	 Payakumbuh Timur	74	t
882	 Payakumbuh Utara	74	t
883	 Lamposi Tigo Nagori	74	t
884	 Pariaman Selatan	75	t
885	 Pariaman Tengah	75	t
886	 Pariaman Timur	75	t
887	 Pariaman Utara	75	t
888	 Kuantan Mudik	76	t
889	 Hulu Kuantan	76	t
890	 Gunung Toar	76	t
891	 Pucuk Rantau	76	t
892	 Singingi	76	t
893	 Singingi Hilir	76	t
894	 Kuantan Tengah	76	t
895	 Sentajo Raya	76	t
896	 Benai	76	t
897	 Kuantan Hilir	76	t
898	 Pangean	76	t
899	 Logas Tanah Darat	76	t
900	 Kuantan Hilir Seberang	76	t
901	 Cerenti	76	t
902	 Inuman	76	t
903	 Peranap	77	t
904	 Batang Peranap	77	t
905	 Seberida	77	t
906	 Batang Cenaku	77	t
907	 Batang Gansal	77	t
908	 Kelayang	77	t
909	 Rakit Kulim	77	t
910	 Pasir Penyu	77	t
911	 Lirik	77	t
912	 Sungai Lala	77	t
913	 Lubuk Batu Jaya	77	t
914	 Rengat Barat	77	t
915	 Rengat	77	t
916	 Kuala Cenaku	77	t
917	 Keritang	78	t
918	 Kemuning	78	t
919	 Reteh	78	t
920	 Sungai Batang	78	t
921	 Enok	78	t
922	 Tanah Merah	78	t
923	 Kuala Indragiri	78	t
924	 Concong	78	t
925	 Tembilahan	78	t
926	 Tembilahan Hulu	78	t
927	 Tempuling	78	t
928	 Kempas	78	t
929	 Batang Tuaka	78	t
930	 Gaung Anak Serka	78	t
931	 Gaung	78	t
932	 Mandah	78	t
933	 Kateman	78	t
934	 Pelangiran	78	t
935	 Teluk Belengkong	78	t
936	 Pulau Burung	78	t
937	 Langgam	79	t
938	 Pangkalan Kerinci	79	t
939	 Bandar Seikijang	79	t
940	 Pangkalan Kuras	79	t
941	 Ukui	79	t
942	 Pangkalan Lesung	79	t
943	 Bunut	79	t
944	 Pelalawan	79	t
945	 Bandar Petalangan	79	t
946	 Kuala Kampar	79	t
947	 Kerumutan	79	t
948	 Teluk Meranti	79	t
949	 Minas	80	t
950	 Sungai Mandau	80	t
951	 Kandis	80	t
952	 Siak	80	t
953	 Kerinci Kanan	80	t
954	 Tualang	80	t
955	 Dayun	80	t
956	 Lubuk Dalam	80	t
957	 Koto Gasib	80	t
958	 Mempura	80	t
959	 Sungai Apit	80	t
960	 Bunga Raya	80	t
961	 Sabak Auh	80	t
962	 Pusako	80	t
963	 Kampar Kiri	81	t
964	 Kampar Kiri Hulu	81	t
965	 Kampar Kiri Hilir	81	t
966	 Gunung Sahilan	81	t
967	 Kampar Kiri Tengah	81	t
968	 Xiii Koto Kampar	81	t
969	 Koto Kampar Hulu	81	t
970	 Kuok	81	t
971	 Salo	81	t
972	 Tapung	81	t
973	 Tapung Hulu	81	t
974	 Tapung Hilir	81	t
975	 Bangkinang	81	t
976	 Bangkinang Seberang	81	t
977	 Kampar	81	t
978	 Kampar Timur	81	t
979	 Rumbio Jaya	81	t
980	 Kampar Utara	81	t
981	 Tambang	81	t
982	 Siak Hulu	81	t
983	 Perhentian Raja	81	t
984	 Rokan Iv Koto	82	t
985	 Pendalian Iv Koto	82	t
986	 Tandun	82	t
987	 Kabun	82	t
988	 Ujung Batu	82	t
989	 Rambah Samo	82	t
990	 Rambah	82	t
991	 Rambah Hilir	82	t
992	 Bangun Purba	82	t
993	 Tambusai	82	t
994	 Tambusai Utara	82	t
995	 Kepenuhan	82	t
996	 Kepenuhan Hulu	82	t
997	 Kunto Darussalam	82	t
998	 Pagaran Tapah Darussalam	82	t
999	 Bonai Darussalam	82	t
1000	 Mandau	83	t
1001	 Pinggir	83	t
1002	 Bukit Batu	83	t
1003	 Siak Kecil	83	t
1004	 Rupat	83	t
1005	 Rupat Utara	83	t
1006	 Bengkalis	83	t
1007	 Bantan	83	t
1008	 Tanah Putih	84	t
1009	 Pujud	84	t
1010	 Tanah Putih Tanjung Melawan	84	t
1011	 Rantau Kopar	84	t
1012	 Bagan Sinembah	84	t
1013	 Simpang Kanan	84	t
1014	 Kubu	84	t
1015	 Pasir Limau Kapas	84	t
1016	 Kubu Babussalam	84	t
1017	 Bangko	84	t
1018	 Sinaboi	84	t
1019	 Batu Hampar	84	t
1020	 Pekaitan	84	t
1021	 Rimba Melintang	84	t
1022	 Bangko Pusako	84	t
1023	 Tebing Tinggi Barat	85	t
1024	 Tebing Tinggi	85	t
1025	 Tebing Tinggi Timur	85	t
1026	 Rangsang	85	t
1027	 Rangsang Pesisir	85	t
1028	 Rangsang Barat	85	t
1029	 Merbau	85	t
1030	 Pulau Merbau	85	t
1031	 Putri Puyu	85	t
1032	 Tampan	86	t
1033	 Payung Sekaki	86	t
1034	 Bukit Raya	86	t
1035	 Marpoyan Damai	86	t
1036	 Tenayan Raya	86	t
1037	 Limapuluh	86	t
1038	 Sail	86	t
1039	 Pekanbaru Kota	86	t
1040	 Sukajadi	86	t
1041	 Senapelan	86	t
1042	 Rumbai	86	t
1043	 Rumbai Pesisir	86	t
1044	 Bukit Kapur	87	t
1045	 Medang Kampai	87	t
1046	 Sungai Sembilan	87	t
1047	 Dumai Barat	87	t
1048	 Dumai Selatan	87	t
1049	 Dumai Timur	87	t
1050	 Dumai Kota	87	t
1051	 Gunung Raya	88	t
1052	 Bukit Kerman	88	t
1053	 Batang Merangin	88	t
1054	 Keliling Danau	88	t
1055	 Danau Kerinci	88	t
1056	 Sitinjau Laut	88	t
1057	 Air Hangat	88	t
1058	 Air Hangat Timur	88	t
1059	 Depati Vii	88	t
1060	 Air Hangat Barat	88	t
1061	 Gunung Kerinci	88	t
1062	 Siulak	88	t
1063	 Siulak Mukai	88	t
1064	 Kayu Aro	88	t
1065	 Gunung Tujuh	88	t
1066	 Kayu Aro Barat	88	t
1067	 Jangkat	89	t
1068	 Sungai Tenang	89	t
1069	 Muara Siau	89	t
1070	 Lembah Masurai	89	t
1071	 Tiang Pumpung	89	t
1072	 Pamenang	89	t
1073	 Pamenang Barat	89	t
1074	 Renah Pamenang	89	t
1075	 Pamenang Selatan	89	t
1076	 Bangko	89	t
1077	 Bangko Barat	89	t
1078	 Nalo Tantan	89	t
1079	 Batang Masumai	89	t
1080	 Sungai Manau	89	t
1081	 Renah Pembarap	89	t
1082	 Pangkalan Jambu	89	t
1083	 Tabir	89	t
1084	 Tabir Ulu	89	t
1085	 Tabir Selatan	89	t
1086	 Tabir Ilir	89	t
1087	 Tabir Timur	89	t
1088	 Tabir Lintas	89	t
1089	 Margo Tabir	89	t
1090	 Tabir Barat	89	t
1091	 Batang Asai	90	t
1092	 Limun	90	t
1093	 Cermin Nan Gedang	90	t
1094	 Pelawan	90	t
1095	 Singkut	90	t
1096	 Sarolangun	90	t
1097	 Bathin Viii	90	t
1098	 Pauh	90	t
1099	 Air Hitam	90	t
1100	 Mandiangin	90	t
1101	 Mersam	91	t
1102	 Maro Sebo Ulu	91	t
1103	 Batin Xxiv	91	t
1104	 Muara Tembesi	91	t
1105	 Muara Bulian	91	t
1106	 Bajubang	91	t
1107	 Maro Sebo Ilir	91	t
1108	 Pemayung	91	t
1109	 Mestong	92	t
1110	 Sungai Bahar	92	t
1111	 Bahar Selatan	92	t
1112	 Bahar Utara	92	t
1113	 Kumpeh Ulu	92	t
1114	 Sungai Gelam	92	t
1115	 Kumpeh	92	t
1116	 Maro Sebo	92	t
1117	 Taman Rajo	92	t
1118	 Jambi Luar Kota	92	t
1119	 Sekernan	92	t
1120	 Mendahara	93	t
1121	 Mendahara Ulu	93	t
1122	 Geragai	93	t
1123	 Dendang	93	t
1124	 Muara Sabak Barat	93	t
1125	 Muara Sabak Timur	93	t
1126	 Kuala Jambi	93	t
1127	 Rantau Rasau	93	t
1128	 Berbak	93	t
1129	 Nipah Panjang	93	t
1130	 Sadu	93	t
1131	 Tungkal Ulu	94	t
1132	 Merlung	94	t
1133	 Batang Asam	94	t
1134	 Tebing Tinggi	94	t
1135	 Renah Mendaluh	94	t
1136	 Muara Papalik	94	t
1137	 Pengabuan	94	t
1138	 Senyerang	94	t
1139	 Tungkal Ilir	94	t
1140	 Bram Itam	94	t
1141	 Seberang Kota	94	t
1142	 Betara	94	t
1143	 Kuala Betara	94	t
1144	 Tebo Ilir	95	t
1145	 Muara Tabir	95	t
1146	 Tebo Tengah	95	t
1147	 Sumay	95	t
1148	 Tengah Ilir	95	t
1149	 Rimbo Bujang	95	t
1150	 Rimbo Ulu	95	t
1151	 Rimbo Ilir	95	t
1152	 Tebo Ulu	95	t
1153	 Vii Koto	95	t
1154	 Serai Serumpun	95	t
1155	 Vii Koto Ilir	95	t
1156	 Pelepat	96	t
1157	 Pelepat Ilir	96	t
1158	 Bathin Ii Babeko	96	t
1159	 Rimbo Tengah	96	t
1160	 Bungo Dani	96	t
1161	 Pasar Muara Bungo	96	t
1162	 Bathin Iii	96	t
1163	 Rantau Pandan	96	t
1164	 Muko-muko Bathin Vii	96	t
1165	 Bathin Iii Ulu	96	t
1166	 Tanah Sepenggal	96	t
1167	 Tanah Sepenggal Lintas	96	t
1168	 Tanah Tumbuh	96	t
1169	 Limbur Lubuk Mengkuang	96	t
1170	 Bathin Ii Pelayang	96	t
1171	 Jujuhan	96	t
1172	 Jujuhan Ilir	96	t
1173	 Kota Baru	97	t
1174	 Jambi Selatan	97	t
1175	 Jelutung	97	t
1176	 Pasar Jambi	97	t
1177	 Telanaipura	97	t
1178	 Danau Teluk	97	t
1179	 Pelayangan	97	t
1180	 Jambi Timur	97	t
1181	 Tanah Kampung	98	t
1182	 Kumun Debai	98	t
1183	 Sungai Penuh	98	t
1184	 Pondok Tingggi	98	t
1185	 Sungai Bungkal	98	t
1186	 Hamparan Rawang	98	t
1187	 Pesisir Bukit	98	t
1188	 Koto Baru	98	t
1189	 Lengkiti	99	t
1190	 Sosoh Buay Rayap	99	t
1191	 Pengandonan	99	t
1192	 Semidang Aji	99	t
1193	 Ulu Ogan	99	t
1194	 Muara Jaya	99	t
1195	 Peninjauan	99	t
1196	 Lubuk Batang	99	t
1197	 Sinar Peninjauan	99	t
1198	 Batu Raja Timur	99	t
1199	 Lubuk Raja	99	t
1200	 Batu Raja Barat	99	t
1201	 Lempuing	100	t
1202	 Lempuing Jaya	100	t
1203	 Mesuji	100	t
1204	 Sungai Menang	100	t
1205	 Mesuji Makmur	100	t
1206	 Mesuji Raya	100	t
1207	 Tulung Selapan	100	t
1208	 Cengal	100	t
1209	 Pedamaran	100	t
1210	 Pedamaran Timur	100	t
1211	 Tanjung Lubuk	100	t
1212	 Teluk Gelam	100	t
1213	 Kota Kayu Agung	100	t
1214	 Sirah Pulau Padang	100	t
1215	 Jejawi	100	t
1216	 Pampangan	100	t
1217	 Pangkalan Lapam	100	t
1218	 Air Sugihan	100	t
1219	 Semendo Darat Laut	101	t
1220	 Semendo Darat Ulu	101	t
1221	 Semendo Darat Tengah	101	t
1222	 Tanjung Agung	101	t
1223	 Rambang	101	t
1224	 Lubai	101	t
1225	 Lawang Kidul	101	t
1226	 Muara Enim	101	t
1227	 Ujan Mas	101	t
1228	 Gunung Megang	101	t
1229	 Benakat	101	t
1230	 Belimbing	101	t
1231	 Rambang Dangku	101	t
1232	 Talang Ubi	101	t
1233	 Penukal	101	t
1234	 Tanah Abang	101	t
1235	 Penukal Utara	101	t
1236	 Abab	101	t
1237	 Gelumbang	101	t
1238	 Lembak	101	t
1239	 Sungai Rotan	101	t
1240	 Muara Belida	101	t
1241	 Kelekar	101	t
1242	 Belida Darat	101	t
1243	 Tanjung Sakti Pumi	102	t
1244	 Tanjung Sakti Pumu	102	t
1245	 Kota Agung	102	t
1246	 Mulak Ulu	102	t
1247	 Tanjung Tebat	102	t
1248	 Pulau Pinang	102	t
1249	 Pagar Gunung	102	t
1250	 Gumay Ulu	102	t
1251	 Jarai	102	t
1252	 Pajar Bulan	102	t
1253	 Muara Payang	102	t
1254	 Sukamerindu	102	t
1255	 Kikim Barat	102	t
1256	 Kikim Timur	102	t
1257	 Kikim Selatan	102	t
1258	 Kikim Tengah	102	t
1259	 Lahat	102	t
1260	 Gumay Talang	102	t
1261	 Pseksu	102	t
1262	 Merapi Barat	102	t
1263	 Merapi Timur	102	t
1264	 Merapi Selatan	102	t
1265	 Rawas Ulu	103	t
1266	 Ulu Rawas	103	t
1267	 Rupit	103	t
1268	 Karang Jaya	103	t
1269	 Suku Tengah Lakitan Ulu	103	t
1270	 Selangit	103	t
1271	 Sumber Harta	103	t
1272	 Tugumulyo	103	t
1273	 Purwodadi	103	t
1274	 Muara Beliti	103	t
1275	 Tiang Pumpung Kepungut	103	t
1276	 Jayaloka	103	t
1277	 Suka Karya	103	t
1278	 Muara Kelingi	103	t
1279	 Bulang Tengah Suku Ulu	103	t
1280	 Tuah Negeri	103	t
1281	 Muara Lakitan	103	t
1282	 Megang Sakti	103	t
1283	 Rawas Ilir	103	t
1284	 Karang Dapo	103	t
1285	 Nibung	103	t
1286	 Sanga Desa	104	t
1287	 Babat Toman	104	t
1288	 Batanghari Leko	104	t
1289	 Plakat Tinggi	104	t
1290	 Lawang Wetan	104	t
1291	 Sungai Keruh	104	t
1292	 Sekayu	104	t
1293	 Lais	104	t
1294	 Sungai Lilin	104	t
1295	 Keluang	104	t
1296	 Babat Supat	104	t
1297	 Bayung Lencir	104	t
1298	 Lalan	104	t
1299	 Tungkal Jaya	104	t
1300	 Rantau Bayur	105	t
1301	 Betung	105	t
1302	 Suak Tapeh	105	t
1303	 Pulau Rimau	105	t
1304	 Tungkal Ilir	105	t
1305	 Banyuasin Iii	105	t
1306	 Sembawa	105	t
1307	 Talang Kelapa	105	t
1308	 Tanjung Lago	105	t
1309	 Banyuasin I	105	t
1310	 Air Kumbang	105	t
1311	 Rambutan	105	t
1312	 Muara Padang	105	t
1313	 Muara Sugihan	105	t
1314	 Makarti Jaya	105	t
1315	 Air Saleh	105	t
1316	 Banyuasin Ii	105	t
1317	 Muara Telang	105	t
1318	 Sumber Marga Telang	105	t
1319	 Mekakau Ilir	106	t
1320	 Banding Agung	106	t
1321	 Warkuk Ranau Selatan	106	t
1322	 Buay Pemaca	106	t
1323	 Simpang	106	t
1324	 Buana Pemaca	106	t
1325	 Muaradua	106	t
1326	 Buay Rawan	106	t
1327	 Buay Sandang Aji	106	t
1328	 Tiga Dihaji	106	t
1329	 Buay Runjung	106	t
1330	 Runjung Agung	106	t
1331	 Kisam Tinggi	106	t
1332	 Muaradua Kisam	106	t
1333	 Kisam Ilir	106	t
1334	 Pulau Beringin	106	t
1335	 Sindang Danau	106	t
1336	 Sungai Are	106	t
1337	 Martapura	107	t
1338	 Bunga Mayang	107	t
1339	 Jaya Pura	107	t
1340	 Buay Pemuka Peliung	107	t
1341	 Buay Madang	107	t
1342	 Buay Madang Timur	107	t
1343	 Buay Pemuka Bangsa Raja	107	t
1344	 Madang Suku Ii	107	t
1345	 Madang Suku Iii	107	t
1346	 Madang Suku I	107	t
1347	 Belitang Madang Raya	107	t
1348	 Belitang	107	t
1349	 Belitang Jaya	107	t
1350	 Belitang Iii	107	t
1351	 Belitang Ii	107	t
1352	 Belitang Mulya	107	t
1353	 Semendawai Suku Iii	107	t
1354	 Semendawai Timur	107	t
1355	 Cempaka	107	t
1356	 Semendawai Barat	107	t
1357	 Muara Kuang	108	t
1358	 Rambang Kuang	108	t
1359	 Lubuk Keliat	108	t
1360	 Tanjung Batu	108	t
1361	 Payaraman	108	t
1362	 Rantau Alai	108	t
1363	 Kandis	108	t
1364	 Tanjung Raja	108	t
1365	 Rantau Panjang	108	t
1366	 Sungai Pinang	108	t
1367	 Pemulutan	108	t
1368	 Pemulutan Selatan	108	t
1369	 Pemulutan Barat	108	t
1370	 Indralaya	108	t
1371	 Indralaya Utara	108	t
1372	 Indralaya Selatan	108	t
1373	 Muara Pinang	109	t
1374	 Lintang Kanan	109	t
1375	 Pendopo	109	t
1376	 Pendopo Barat	109	t
1377	 Pasemah Air Keruh	109	t
1378	 Ulu Musi	109	t
1379	 Sikap Dalam	109	t
1380	 Talang Padang	109	t
1381	 Tebing Tinggi	109	t
1382	 Saling	109	t
1383	 Ilir Barat Ii	110	t
1384	 Gandus	110	t
1385	 Seberang Ulu I	110	t
1386	 Kertapati	110	t
1387	 Seberang Ulu Ii	110	t
1388	 Plaju	110	t
1389	 Ilir Barat I	110	t
1390	 Bukit Kecil	110	t
1391	 Ilir Timur I	110	t
1392	 Kemuning	110	t
1393	 Ilir Timur Ii	110	t
1394	 Kalidoni	110	t
1395	 Sako	110	t
1396	 Sematang Borang	110	t
1397	 Sukarami	110	t
1398	 Alang Alang Lebar	110	t
1399	 Rambang Kapak Tengah	111	t
1400	 Prabumulih Timur	111	t
1401	 Prabumulih Selatan	111	t
1402	 Prabumulih Barat	111	t
1403	 Prabumulih Utara	111	t
1404	 Cambai	111	t
1405	 Dempo Selatan	112	t
1406	 Dempo Tengah	112	t
1407	 Dempo Utara	112	t
1408	 Pagar Alam Selatan	112	t
1409	 Pagar Alam Utara	112	t
1410	 Lubuk Linggau Barat I	113	t
1411	 Lubuk Linggau Barat Ii	113	t
1412	 Lubuk Linggau Selatan I	113	t
1413	 Lubuk Linggau Selatan Ii	113	t
1414	 Lubuk Linggau Timur I	113	t
1415	 Lubuk Linggau Timur Ii	113	t
1416	 Lubuk Linggau Utara I	113	t
1417	 Lubuk Linggau Utara Ii	113	t
1418	 Manna	114	t
1419	 Kota Manna	114	t
1420	 Kedurang	114	t
1421	 Bunga Mas	114	t
1422	 Pasar Manna	114	t
1423	 Kedurang Ilir	114	t
1424	 Seginim	114	t
1425	 Air Nipis	114	t
1426	 Pino	114	t
1427	 Pinoraya	114	t
1428	 Ulu Manna	114	t
1429	 Kota Padang	115	t
1430	 Sindang Beliti Ilir	115	t
1431	 Padang Ulak Tanding	115	t
1432	 Sindang Kelingi	115	t
1433	 Bindu Riang	115	t
1434	 Sindang Beliti Ulu	115	t
1435	 Sindang Dataran	115	t
1436	 Curup	115	t
1437	 Bermani Ulu	115	t
1438	 Selupu Rejang	115	t
1439	 Curup Selatan	115	t
1440	 Curup Tengah	115	t
1441	 Bermani Ulu Raya	115	t
1442	 Curup Utara	115	t
1443	 Curup Timur	115	t
1444	 Enggano	116	t
1445	 Kerkap	116	t
1446	 Air Napal	116	t
1447	 Air Besi	116	t
1448	 Hulu Palik	116	t
1449	 Tanjung Agung Palik	116	t
1450	 Arga Makmur	116	t
1451	 Arma Jaya	116	t
1452	 Lais	116	t
1453	 Batik Nau	116	t
1454	 Giri Mulya	116	t
1455	 Air Padang	116	t
1456	 Padang Jaya	116	t
1457	 Ketahun	116	t
1458	 Napal Putih	116	t
1459	 Ulok Kupai	116	t
1460	 Putri Hijau	116	t
1461	 Nasal	117	t
1462	 Maje	117	t
1463	 Kaur Selatan	117	t
1464	 Tetap	117	t
1465	 Kaur Tengah	117	t
1466	 Luas	117	t
1467	 Muara Sahung	117	t
1468	 Kinal	117	t
1469	 Semidang Gumay	117	t
1470	 Tanjung Kemuning	117	t
1471	 Kelam Tengah	117	t
1472	 Kaur Utara	117	t
1473	 Padang Guci Hilir	117	t
1474	 Lungkang Kule	117	t
1475	 Padang Guci Hulu	117	t
1476	 Semidang Alas Maras	118	t
1477	 Semidang Alas	118	t
1478	 Talo	118	t
1479	 Ilir Talo	118	t
1480	 Talo Kecil	118	t
1481	 Ulu Talo	118	t
1482	 Seluma	118	t
1483	 Seluma Selatan	118	t
1484	 Seluma Barat	118	t
1485	 Seluma Timur	118	t
1486	 Seluma Utara	118	t
1487	 Sukaraja	118	t
1488	 Air Periukan	118	t
1489	 Lubuk Sandi	118	t
1490	 Ipuh	119	t
1491	 Air Rami	119	t
1492	 Malin Deman	119	t
1493	 Pondok Suguh	119	t
1494	 Sungai Rumbai	119	t
1495	 Teramang Jaya	119	t
1496	 Teras Terunjam	119	t
1497	 Penarik	119	t
1498	 Selagan Raya	119	t
1499	 Kota Mukomuko	119	t
1500	 Air Dikit	119	t
1501	 Xiv Koto	119	t
1502	 Lubuk Pinang	119	t
1503	 Air Manjunto	119	t
1504	 V Koto	119	t
1505	 Rimbo Pengadang	120	t
1506	 Topos	120	t
1507	 Lebong Selatan	120	t
1508	 Bingin Kuning	120	t
1509	 Lebong Tengah	120	t
1510	 Lebong Sakti	120	t
1511	 Lebong Atas	120	t
1512	 Padang Bano	120	t
1513	 Pelabai	120	t
1514	 Lebong Utara	120	t
1515	 Amen	120	t
1516	 Uram Jaya	120	t
1517	 Pinang Belapis	120	t
1518	 Muara Kemumu	121	t
1519	 Bermani Ilir	121	t
1520	 Seberang Musi	121	t
1521	 Tebat Karai	121	t
1522	 Kepahiang	121	t
1523	 Kaba Wetan	121	t
1524	 Ujan Mas	121	t
1525	 Merigi	121	t
1526	 Talang Empat	122	t
1527	 Karang Tinggi	122	t
1528	 Taba Penanjung	122	t
1529	 Merigi Kelindang	122	t
1530	 Pagar Jati	122	t
1531	 Merigi Sakti	122	t
1532	 Pondok Kelapa	122	t
1533	 Pondok Kubang	122	t
1534	 Pematang Tiga	122	t
1535	 Bang Haji	122	t
1536	 Selebar	123	t
1537	 Kampung Melayu	123	t
1538	 Gading Cempaka	123	t
1539	 Ratu Agung	123	t
1540	 Ratu Samban	123	t
1541	 Singaran Pati	123	t
1542	 Teluk Segara	123	t
1543	 Sungai Serut	123	t
1544	 Muara Bangka Hulu	123	t
1545	 Balik Bukit	124	t
1546	 Sukau	124	t
1547	 Lumbok Seminung	124	t
1548	 Belalau	124	t
1549	 Sekincau	124	t
1550	 Suoh	124	t
1551	 Batu Brak	124	t
1552	 Pagar Dewa	124	t
1553	 Batu Ketulis	124	t
1554	 Bandar Negeri Suoh	124	t
1555	 Sumber Jaya	124	t
1556	 Way Tenong	124	t
1557	 Gedung Surian	124	t
1558	 Kebun Tebu	124	t
1559	 Air Hitam	124	t
1560	 Wonosobo	125	t
1561	 Semaka	125	t
1562	 Bandar Negeri Semuong	125	t
1563	 Kota Agung	125	t
1564	 Pematang Sawa	125	t
1565	 Kota Agung Timur	125	t
1566	 Kota Agung Barat	125	t
1567	 Pulau Panggung	125	t
1568	 Ulubelu	125	t
1569	 Air Naningan	125	t
1570	 Talang Padang	125	t
1571	 Sumberejo	125	t
1572	 Gisting	125	t
1573	 Gunung Alip	125	t
1574	 Pugung	125	t
1575	 Bulok	125	t
1576	 Cukuh Balak	125	t
1577	 Kelumbayan	125	t
1578	 Limau	125	t
1579	 Kelumbayan Barat	125	t
1580	 Natar	126	t
1581	 Jati Agung	126	t
1582	 Tanjung Bintang	126	t
1583	 Tanjung Sari	126	t
1584	 Katibung	126	t
1585	 Merbau Mataram	126	t
1586	 Way Sulan	126	t
1587	 Sidomulyo	126	t
1588	 Candipuro	126	t
1589	 Way Panji	126	t
1590	 Kalianda	126	t
1591	 Rajabasa	126	t
1592	 Palas	126	t
1593	 Sragi	126	t
1594	 Penengahan	126	t
1595	 Ketapang	126	t
1596	 Bakauheni	126	t
1597	 Metro Kibang	127	t
1598	 Batanghari	127	t
1599	 Sekampung	127	t
1600	 Margatiga	127	t
1601	 Sekampung Udik	127	t
1602	 Jabung	127	t
1603	 Pasir Sakti	127	t
1604	 Waway Karya	127	t
1605	 Marga Sekampung	127	t
1606	 Labuhan Maringgai	127	t
1607	 Mataram Baru	127	t
1608	 Bandar Sribawono	127	t
1609	 Melinting	127	t
1610	 Gunung Pelindung	127	t
1611	 Way Jepara	127	t
1612	 Braja Slebah	127	t
1613	 Labuhan Ratu	127	t
1614	 Sukadana	127	t
1615	 Bumi Agung	127	t
1616	 Batanghari Nuban	127	t
1617	 Pekalongan	127	t
1618	 Raman Utara	127	t
1619	 Purbolinggo	127	t
1620	 Way Bungur	127	t
1621	 Padang Ratu	128	t
1622	 Selagai Lingga	128	t
1623	 Pubian	128	t
1624	 Anak Tuha	128	t
1625	 Anak Ratu Aji	128	t
1626	 Kalirejo	128	t
1627	 Sendang Agung	128	t
1628	 Bangunrejo	128	t
1629	 Gunung Sugih	128	t
1630	 Bekri	128	t
1631	 Bumi Ratu Nuban	128	t
1632	 Trimurjo	128	t
1633	 Punggur	128	t
1634	 Kota Gajah	128	t
1635	 Seputih Raman	128	t
1636	 Terbanggi Besar	128	t
1637	 Seputih Agung	128	t
1638	 Way Pengubuan	128	t
1639	 Terusan Nunyai	128	t
1640	 Seputih Mataram	128	t
1641	 Bandar Mataram	128	t
1642	 Seputih Banyak	128	t
1643	 Way Seputih	128	t
1644	 Rumbia	128	t
1645	 Bumi Nabung	128	t
1646	 Putra Rumbia	128	t
1647	 Seputih Surabaya	128	t
1648	 Bandar Surabaya	128	t
1649	 Bukit Kemuning	129	t
1650	 Abung Tinggi	129	t
1651	 Tanjung Raja	129	t
1652	 Abung Barat	129	t
1653	 Abung Tengah	129	t
1654	 Abung  Kunang	129	t
1655	 Abung Pekurun	129	t
1656	 Kotabumi	129	t
1657	 Kotabumi Utara	129	t
1658	 Kotabumi Selatan	129	t
1659	 Abung Selatan	129	t
1660	 Abung Semuli	129	t
1661	 Blambangan Pagar	129	t
1662	 Abung Timur	129	t
1663	 Abung Surakarta	129	t
1664	 Sungkai Selatan	129	t
1665	 Muara Sungkai	129	t
1666	 Bunga Mayang	129	t
1667	 Sungkai  Barat	129	t
1668	 Sungkai Jaya	129	t
1669	 Sungkai Utara	129	t
1670	 Hulusungkai	129	t
1671	 Sungkai Tengah	129	t
1672	 Banjit	130	t
1673	 Baradatu	130	t
1674	 Gunung Labuhan	130	t
1675	 Kasui	130	t
1676	 Rebang Tangkas	130	t
1677	 Blambangan Umpu	130	t
1678	 Way Tuba	130	t
1679	 Negeri Agung	130	t
1680	 Bahuga	130	t
1681	 Buay  Bahuga	130	t
1682	 Bumi Agung	130	t
1683	 Pakuan Ratu	130	t
1684	 Negara Batin	130	t
1685	 Negeri Besar	130	t
1686	 Banjar Agung	131	t
1687	 Banjar Margo	131	t
1688	 Banjar Baru	131	t
1689	 Gedung Aji	131	t
1690	 Penawar Aji	131	t
1691	 Meraksa Aji	131	t
1692	 Menggala	131	t
1693	 Penawar Tama	131	t
1694	 Rawajitu Selatan	131	t
1695	 Gedung Meneng	131	t
1696	 Rawajitu Timur	131	t
1697	 Rawa Pitu	131	t
1698	 Gedung Aji Baru	131	t
1699	 Dente Teladas	131	t
1700	 Menggala Timur	131	t
1701	 Punduh Pidada	132	t
1702	 Marga Punduh	132	t
1703	 Padang Cermin	132	t
1704	 Kedondong	132	t
1705	 Way Khilau	132	t
1706	 Way Lima	132	t
1707	 Gedung Tataan	132	t
1708	 Negeri Katon	132	t
1709	 Tegineneng	132	t
1710	 Pardasuka	133	t
1711	 Ambarawa	133	t
1712	 Pagelaran	133	t
1713	 Pagelaran Utara	133	t
1714	 Pringsewu	133	t
1715	 Gading Rejo	133	t
1716	 Sukoharjo	133	t
1717	 Banyumas	133	t
1718	 Adi Luwih	133	t
1719	 Way Serdang	134	t
1720	 Simpang Pematang	134	t
1721	 Panca Jaya	134	t
1722	 Tanjung Raya	134	t
1723	 Mesuji	134	t
1724	 Mesuji Timur	134	t
1725	 Rawajitu Utara	134	t
1726	 Tulang Bawang Udik	135	t
1727	 Tumi Jajar	135	t
1728	 Tulang Bawang Tengah	135	t
1729	 Pagar Dewa	135	t
1730	 Lambu Kibang	135	t
1731	 Gunung Terang	135	t
1732	 Gunung Agung	135	t
1733	 Way Kenanga	135	t
1734	 Lemong	136	t
1735	 Pesisir Utara	136	t
1736	 Pulau Pisang	136	t
1737	 Karya Penggawa	136	t
1738	 Way Krui	136	t
1739	 Pesisir Tengah	136	t
1740	 Krui Selatan	136	t
1741	 Pesisir Selatan	136	t
1742	 Ngambur	136	t
1743	 Bengkunat	136	t
1744	 Bengkunat Belimbing	136	t
1745	 Teluk Betung Barat	137	t
1746	 Telukbetung Timur	137	t
1747	 Teluk Betung Selatan	137	t
1748	 Bumi Waras	137	t
1749	 Panjang	137	t
1750	 Tanjung Karang Timur	137	t
1751	 Kedamaian	137	t
1752	 Teluk Betung Utara	137	t
1753	 Tanjung Karang Pusat	137	t
1754	 Enggal	137	t
1755	 Tanjung Karang Barat	137	t
1756	 Kemiling	137	t
1757	 Langkapura	137	t
1758	 Kedaton	137	t
1759	 Rajabasa	137	t
1760	 Tanjung Senang	137	t
1761	 Labuhan Ratu	137	t
1762	 Sukarame	137	t
1763	 Sukabumi	137	t
1764	 Way Halim	137	t
1765	 Metro Selatan	138	t
1766	 Metro Barat	138	t
1767	 Metro Timur	138	t
1768	 Metro Pusat	138	t
1769	 Metro Utara	138	t
1770	 Mendo Barat	139	t
1771	 Merawang	139	t
1772	 Puding Besar	139	t
1773	 Sungai Liat	139	t
1774	 Pemali	139	t
1775	 Bakam	139	t
1776	 Belinyu	139	t
1777	 Riau Silip	139	t
1778	 Membalong	140	t
1779	 Tanjung Pandan	140	t
1780	 Badau	140	t
1781	 Sijuk	140	t
1782	 Selat Nasik	140	t
1783	 Kelapa	141	t
1784	 Tempilang	141	t
1785	 Mentok	141	t
1786	 Simpang Teritip	141	t
1787	 Jebus	141	t
1788	 Parittiga	141	t
1789	 Koba	142	t
1790	 Lubuk Besar	142	t
1791	 Pangkalan Baru	142	t
1792	 Namang	142	t
1793	 Sungai Selan	142	t
1794	 Simpang Katis	142	t
1795	 Payung	143	t
1796	 Pulau Besar	143	t
1797	 Simpang Rimba	143	t
1798	 Toboali	143	t
1799	 Tukak Sadai	143	t
1800	 Air Gegas	143	t
1801	 Lepar Pongok	143	t
1802	 Kepulauan Pongok	143	t
1803	 Dendang	144	t
1804	 Simpang Pesak	144	t
1805	 Gantung	144	t
1806	 Simpang Renggiang	144	t
1807	 Manggar	144	t
1808	 Damar	144	t
1809	 Kelapa Kampit	144	t
1810	 Rangkui	145	t
1811	 Bukit Intan	145	t
1812	 Girimaya	145	t
1813	 Pangkal Balam	145	t
1814	 Gabek	145	t
1815	 Taman Sari	145	t
1816	 Gerunggang	145	t
1817	 Moro	146	t
1818	 Durai	146	t
1819	 Kundur	146	t
1820	 Kundur Utara	146	t
1821	 Kundur Barat	146	t
1822	 Karimun	146	t
1823	 Buru	146	t
1824	 Meral	146	t
1825	 Tebing	146	t
1826	 Teluk Bintan	147	t
1827	 Bintan Utara	147	t
1828	 Teluk Sebong	147	t
1829	 Seri Kuala Lobam	147	t
1830	 Bintan Timur	147	t
1831	 Gunung Kijang	147	t
1832	 Mantang	147	t
1833	 Bintan Pesisir	147	t
1834	 Toapaya	147	t
1835	 Tambelan	147	t
1836	 Midai	148	t
1837	 Bunguran Barat	148	t
1838	 Bunguran Utara	148	t
1839	 Pulau Laut	148	t
1840	 Pulau Tiga	148	t
1841	 Bunguran Timur	148	t
1842	 Bunguran Timur Laut	148	t
1843	 Bunguran Tengah	148	t
1844	 Bunguran Selatan	148	t
1845	 Serasan	148	t
1846	 Subi	148	t
1847	 Serasan Timur	148	t
1848	 Singkep Barat	149	t
1849	 Singkep	149	t
1850	 Singkep Selatan	149	t
1851	 Singkep Pesisir	149	t
1852	 Lingga	149	t
1853	 Selayar	149	t
1854	 Lingga Timur	149	t
1855	 Lingga Utara	149	t
1856	 Senayang	149	t
1857	 Jemaja	150	t
1858	 Jemaja Timur	150	t
1859	 Siantan Selatan	150	t
1860	 Siantan	150	t
1861	 Siantan Timur	150	t
1862	 Siantan Tengah	150	t
1863	 Palmatak	150	t
1864	 Belakang Padang	151	t
1865	 Bulang	151	t
1866	 Galang	151	t
1867	 Sei Beduk	151	t
1868	 Sagulung	151	t
1869	 Nongsa	151	t
1870	 Batam Kota	151	t
1871	 Sekupang	151	t
1872	 Batu Aji	151	t
1873	 Lubuk Baja	151	t
1874	 Batu Ampar	151	t
1875	 Bengkong	151	t
1876	 Bukit Bestari	152	t
1877	 Tanjungpinang Timur	152	t
1878	 Tanjungpinang Kota	152	t
1879	 Tanjungpinang Barat	152	t
1880	 Kepulauan Seribu Selatan	153	t
1881	 Kepulauan Seribu Utara	153	t
1882	 Jagakarsa	154	t
1883	 Pasar Minggu	154	t
1884	 Cilandak	154	t
1885	 Pesanggrahan	154	t
1886	 Kebayoran Lama	154	t
1887	 Kebayoran Baru	154	t
1888	 Mampang Prapatan	154	t
1889	 Pancoran	154	t
1890	 Tebet	154	t
1891	 Setia Budi	154	t
1892	 Pasar Rebo	155	t
1893	 Ciracas	155	t
1894	 Cipayung	155	t
1895	 Makasar	155	t
1896	 Kramat Jati	155	t
1897	 Jatinegara	155	t
1898	 Duren Sawit	155	t
1899	 Cakung	155	t
1900	 Pulo Gadung	155	t
1901	 Matraman	155	t
1902	 Tanah Abang	156	t
1903	 Menteng	156	t
1904	 Senen	156	t
1905	 Johar Baru	156	t
1906	 Cempaka Putih	156	t
1907	 Kemayoran	156	t
1908	 Sawah Besar	156	t
1909	 Gambir	156	t
1910	 Kembangan	157	t
1911	 Kebon Jeruk	157	t
1912	 Palmerah	157	t
1913	 Grogol Petamburan	157	t
1914	 Tambora	157	t
1915	 Taman Sari	157	t
1916	 Cengkareng	157	t
1917	 Kali Deres	157	t
1918	 Penjaringan	158	t
1919	 Pademangan	158	t
1920	 Tanjung Priok	158	t
1921	 Koja	158	t
1922	 Kelapa Gading	158	t
1923	 Cilincing	158	t
1924	 Nanggung	159	t
1925	 Leuwiliang	159	t
1926	 Leuwisadeng	159	t
1927	 Pamijahan	159	t
1928	 Cibungbulang	159	t
1929	 Ciampea	159	t
1930	 Tenjolaya	159	t
1931	 Dramaga	159	t
1932	 Ciomas	159	t
1933	 Tamansari	159	t
1934	 Cijeruk	159	t
1935	 Cigombong	159	t
1936	 Caringin	159	t
1937	 Ciawi	159	t
1938	 Cisarua	159	t
1939	 Megamendung	159	t
1940	 Sukaraja	159	t
1941	 Babakan Madang	159	t
1942	 Sukamakmur	159	t
1943	 Cariu	159	t
1944	 Tanjungsari	159	t
1945	 Jonggol	159	t
1946	 Cileungsi	159	t
1947	 Kelapa Nunggal	159	t
1948	 Gunung Putri	159	t
1949	 Citeureup	159	t
1950	 Cibinong	159	t
1951	 Bojong Gede	159	t
1952	 Tajur Halang	159	t
1953	 Kemang	159	t
1954	 Ranca Bungur	159	t
1955	 Parung	159	t
1956	 Ciseeng	159	t
1957	 Gunung Sindur	159	t
1958	 Rumpin	159	t
1959	 Cigudeg	159	t
1960	 Sukajaya	159	t
1961	 Jasinga	159	t
1962	 Tenjo	159	t
1963	 Parung Panjang	159	t
1964	 Ciemas	160	t
1965	 Ciracap	160	t
1966	 Waluran	160	t
1967	 Surade	160	t
1968	 Cibitung	160	t
1969	 Jampang Kulon	160	t
1970	 Cimanggu	160	t
1971	 Kali Bunder	160	t
1972	 Tegal Buleud	160	t
1973	 Cidolog	160	t
1974	 Sagaranten	160	t
1975	 Cidadap	160	t
1976	 Curugkembar	160	t
1977	 Pabuaran	160	t
1978	 Lengkong	160	t
1979	 Palabuhanratu	160	t
1980	 Simpenan	160	t
1981	 Warung Kiara	160	t
1982	 Bantargadung	160	t
1983	 Jampang Tengah	160	t
1984	 Purabaya	160	t
1985	 Cikembar	160	t
1986	 Nyalindung	160	t
1987	 Geger Bitung	160	t
1988	 Sukaraja	160	t
1989	 Kebonpedes	160	t
1990	 Cireunghas	160	t
1991	 Sukalarang	160	t
1992	 Sukabumi	160	t
1993	 Kadudampit	160	t
1994	 Cisaat	160	t
1995	 Gunungguruh	160	t
1996	 Cibadak	160	t
1997	 Cicantayan	160	t
1998	 Caringin	160	t
1999	 Nagrak	160	t
2000	 Ciambar	160	t
2001	 Cicurug	160	t
2002	 Cidahu	160	t
2003	 Parakan Salak	160	t
2004	 Parung Kuda	160	t
2005	 Bojong Genteng	160	t
2006	 Kalapa Nunggal	160	t
2007	 Cikidang	160	t
2008	 Cisolok	160	t
2009	 Cikakak	160	t
2010	 Kabandungan	160	t
2011	 Agrabinta	161	t
2012	 Leles	161	t
2013	 Sindangbarang	161	t
2014	 Cidaun	161	t
2015	 Naringgul	161	t
2016	 Cibinong	161	t
2017	 Cikadu	161	t
2018	 Tanggeung	161	t
2019	 Pasirkuda	161	t
2020	 Kadupandak	161	t
2021	 Cijati	161	t
2022	 Takokak	161	t
2023	 Sukanagara	161	t
2024	 Pagelaran	161	t
2025	 Campaka	161	t
2026	 Campaka Mulya	161	t
2027	 Cibeber	161	t
2028	 Warungkondang	161	t
2029	 Gekbrong	161	t
2030	 Cilaku	161	t
2031	 Sukaluyu	161	t
2032	 Bojongpicung	161	t
2033	 Haurwangi	161	t
2034	 Ciranjang	161	t
2035	 Mande	161	t
2036	 Karangtengah	161	t
2037	 Cianjur	161	t
2038	 Cugenang	161	t
2039	 Pacet	161	t
2040	 Cipanas	161	t
2041	 Sukaresmi	161	t
2042	 Cikalongkulon	161	t
2043	 Ciwidey	162	t
2044	 Rancabali	162	t
2045	 Pasirjambu	162	t
2046	 Cimaung	162	t
2047	 Pangalengan	162	t
2048	 Kertasari	162	t
2049	 Pacet	162	t
2050	 Ibun	162	t
2051	 Paseh	162	t
2052	 Cikancung	162	t
2053	 Cicalengka	162	t
2054	 Nagreg	162	t
2055	 Rancaekek	162	t
2056	 Majalaya	162	t
2057	 Solokan Jeruk	162	t
2058	 Ciparay	162	t
2059	 Baleendah	162	t
2060	 Arjasari	162	t
2061	 Banjaran	162	t
2062	 Cangkuang	162	t
2063	 Pameungpeuk	162	t
2064	 Katapang	162	t
2065	 Soreang	162	t
2066	 Kutawaringin	162	t
2067	 Margaasih	162	t
2068	 Margahayu	162	t
2069	 Dayeuhkolot	162	t
2070	 Bojongsoang	162	t
2071	 Cileunyi	162	t
2072	 Cilengkrang	162	t
2073	 Cimenyan	162	t
2074	 Cisewu	163	t
2075	 Caringin	163	t
2076	 Talegong	163	t
2077	 Bungbulang	163	t
2078	 Mekarmukti	163	t
2079	 Pamulihan	163	t
2080	 Pakenjeng	163	t
2081	 Cikelet	163	t
2082	 Pameungpeuk	163	t
2083	 Cibalong	163	t
2084	 Cisompet	163	t
2085	 Peundeuy	163	t
2086	 Singajaya	163	t
2087	 Cihurip	163	t
2088	 Cikajang	163	t
2089	 Banjarwangi	163	t
2090	 Cilawu	163	t
2091	 Bayongbong	163	t
2092	 Cigedug	163	t
2093	 Cisurupan	163	t
2094	 Sukaresmi	163	t
2095	 Samarang	163	t
2096	 Pasirwangi	163	t
2097	 Tarogong Kidul	163	t
2098	 Tarogong Kaler	163	t
2099	 Garut Kota	163	t
2100	 Karangpawitan	163	t
2101	 Wanaraja	163	t
2102	 Sucinaraja	163	t
2103	 Pangatikan	163	t
2104	 Sukawening	163	t
2105	 Karangtengah	163	t
2106	 Banyuresmi	163	t
2107	 Leles	163	t
2108	 Leuwigoong	163	t
2109	 Cibatu	163	t
2110	 Kersamanah	163	t
2111	 Cibiuk	163	t
2112	 Kadungora	163	t
2113	 Blubur Limbangan	163	t
2114	 Selaawi	163	t
2115	 Malangbong	163	t
2116	 Cipatujah	164	t
2117	 Karangnunggal	164	t
2118	 Cikalong	164	t
2119	 Pancatengah	164	t
2120	 Cikatomas	164	t
2121	 Cibalong	164	t
2122	 Parungponteng	164	t
2123	 Bantarkalong	164	t
2124	 Bojongasih	164	t
2125	 Culamega	164	t
2126	 Bojonggambir	164	t
2127	 Sodonghilir	164	t
2128	 Taraju	164	t
2129	 Salawu	164	t
2130	 Puspahiang	164	t
2131	 Tanjungjaya	164	t
2132	 Sukaraja	164	t
2133	 Salopa	164	t
2134	 Jatiwaras	164	t
2135	 Cineam	164	t
2136	 Karangjaya	164	t
2137	 Manonjaya	164	t
2138	 Gunungtanjung	164	t
2139	 Singaparna	164	t
2140	 Sukarame	164	t
2141	 Mangunreja	164	t
2142	 Cigalontang	164	t
2143	 Leuwisari	164	t
2144	 Sariwangi	164	t
2145	 Padakembang	164	t
2146	 Sukaratu	164	t
2147	 Cisayong	164	t
2148	 Sukahening	164	t
2149	 Rajapolah	164	t
2150	 Jamanis	164	t
2151	 Ciawi	164	t
2152	 Kadipaten	164	t
2153	 Pagerageung	164	t
2154	 Sukaresik	164	t
2155	 Banjarsari	165	t
2156	 Lakbok	165	t
2157	 Purwadadi	165	t
2158	 Pamarican	165	t
2159	 Cidolog	165	t
2160	 Cimaragas	165	t
2161	 Cijeungjing	165	t
2162	 Cisaga	165	t
2163	 Tambaksari	165	t
2164	 Rancah	165	t
2165	 Rajadesa	165	t
2166	 Sukadana	165	t
2167	 Ciamis	165	t
2168	 Baregbeg	165	t
2169	 Cikoneng	165	t
2170	 Sindangkasih	165	t
2171	 Cihaurbeuti	165	t
2172	 Sadananya	165	t
2173	 Cipaku	165	t
2174	 Jatinagara	165	t
2175	 Panawangan	165	t
2176	 Kawali	165	t
2177	 Lumbung	165	t
2178	 Panjalu	165	t
2179	 Sukamantri	165	t
2180	 Panumbangan	165	t
2181	 Darma	166	t
2182	 Kadugede	166	t
2183	 Nusaherang	166	t
2184	 Ciniru	166	t
2185	 Hantara	166	t
2186	 Selajambe	166	t
2187	 Subang	166	t
2188	 Cilebak	166	t
2189	 Ciwaru	166	t
2190	 Karangkancana	166	t
2191	 Cibingbin	166	t
2192	 Cibeureum	166	t
2193	 Luragung	166	t
2194	 Cimahi	166	t
2195	 Cidahu	166	t
2196	 Kalimanggis	166	t
2197	 Ciawigebang	166	t
2198	 Cipicung	166	t
2199	 Lebakwangi	166	t
2200	 Maleber	166	t
2201	 Garawangi	166	t
2202	 Sidangagung	166	t
2203	 Kuningan	166	t
2204	 Cigugur	166	t
2205	 Kramatmulya	166	t
2206	 Jalaksana	166	t
2207	 Japara	166	t
2208	 Cilimus	166	t
2209	 Cigandamekar	166	t
2210	 Mandirancan	166	t
2211	 Pancalang	166	t
2212	 Pasawahan	166	t
2213	 Waled	167	t
2214	 Pasaleman	167	t
2215	 Ciledug	167	t
2216	 Pabuaran	167	t
2217	 Losari	167	t
2218	 Pabedilan	167	t
2219	 Babakan	167	t
2220	 Gebang	167	t
2221	 Karangsembung	167	t
2222	 Karangwareng	167	t
2223	 Lemahabang	167	t
2224	 Susukanlebak	167	t
2225	 Sedong	167	t
2226	 Astanajapura	167	t
2227	 Pangenan	167	t
2228	 Mundu	167	t
2229	 Beber	167	t
2230	 Greged	167	t
2231	 Talun	167	t
2232	 Sumber	167	t
2233	 Dukupuntang	167	t
2234	 Palimanan	167	t
2235	 Plumbon	167	t
2236	 Depok	167	t
2237	 Weru	167	t
2238	 Plered	167	t
2239	 Tengah Tani	167	t
2240	 Kedawung	167	t
2241	 Gunungjati	167	t
2242	 Kapetakan	167	t
2243	 Suranenggala	167	t
2244	 Klangenan	167	t
2245	 Jamblang	167	t
2246	 Arjawinangun	167	t
2247	 Panguragan	167	t
2248	 Ciwaringin	167	t
2249	 Gempol	167	t
2250	 Susukan	167	t
2251	 Gegesik	167	t
2252	 Kaliwedi	167	t
2253	 Lemahsugih	168	t
2254	 Bantarujeg	168	t
2255	 Malausma	168	t
2256	 Cikijing	168	t
2257	 Cingambul	168	t
2258	 Talaga	168	t
2259	 Banjaran	168	t
2260	 Argapura	168	t
2261	 Maja	168	t
2262	 Majalengka	168	t
2263	 Cigasong	168	t
2264	 Sukahaji	168	t
2265	 Sindang	168	t
2266	 Rajagaluh	168	t
2267	 Sindangwangi	168	t
2268	 Leuwimunding	168	t
2269	 Palasah	168	t
2270	 Jatiwangi	168	t
2271	 Dawuan	168	t
2272	 Kasokandel	168	t
2273	 Panyingkiran	168	t
2274	 Kadipaten	168	t
2275	 Kertajati	168	t
2276	 Jatitujuh	168	t
2277	 Ligung	168	t
2278	 Sumberjaya	168	t
2279	 Jatinangor	169	t
2280	 Cimanggung	169	t
2281	 Tanjungsari	169	t
2282	 Sukasari	169	t
2283	 Pamulihan	169	t
2284	 Rancakalong	169	t
2285	 Sumedang Selatan	169	t
2286	 Sumedang Utara	169	t
2287	 Ganeas	169	t
2288	 Situraja	169	t
2289	 Cisitu	169	t
2290	 Darmaraja	169	t
2291	 Cibugel	169	t
2292	 Wado	169	t
2293	 Jatinunggal	169	t
2294	 Jatigede	169	t
2295	 Tomo	169	t
2296	 Ujung Jaya	169	t
2297	 Conggeang	169	t
2298	 Paseh	169	t
2299	 Cimalaka	169	t
2300	 Cisarua	169	t
2301	 Tanjungkerta	169	t
2302	 Tanjungmedar	169	t
2303	 Buahdua	169	t
2304	 Surian	169	t
2305	 Haurgeulis	170	t
2306	 Gantar	170	t
2307	 Kroya	170	t
2308	 Gabuswetan	170	t
2309	 Cikedung	170	t
2310	 Terisi	170	t
2311	 Lelea	170	t
2312	 Bangodua	170	t
2313	 Tukdana	170	t
2314	 Widasari	170	t
2315	 Kertasemaya	170	t
2316	 Sukagumiwang	170	t
2317	 Krangkeng	170	t
2318	 Karangampel	170	t
2319	 Kedokan Bunder	170	t
2320	 Juntinyuat	170	t
2321	 Sliyeg	170	t
2322	 Jatibarang	170	t
2323	 Balongan	170	t
2324	 Indramayu	170	t
2325	 Sindang	170	t
2326	 Cantigi	170	t
2327	 Pasekan	170	t
2328	 Lohbener	170	t
2329	 Arahan	170	t
2330	 Losarang	170	t
2331	 Kandanghaur	170	t
2332	 Bongas	170	t
2333	 Anjatan	170	t
2334	 Sukra	170	t
2335	 Patrol	170	t
2336	 Sagalaherang	171	t
2337	 Serangpanjang	171	t
2338	 Jalancagak	171	t
2339	 Ciater	171	t
2340	 Cisalak	171	t
2341	 Kasomalang	171	t
2342	 Tanjungsiang	171	t
2343	 Cijambe	171	t
2344	 Cibogo	171	t
2345	 Subang	171	t
2346	 Kalijati	171	t
2347	 Dawuan	171	t
2348	 Cipeundeuy	171	t
2349	 Pabuaran	171	t
2350	 Patokbeusi	171	t
2351	 Purwadadi	171	t
2352	 Cikaum	171	t
2353	 Pagaden	171	t
2354	 Pagaden Barat	171	t
2355	 Cipunagara	171	t
2356	 Compreng	171	t
2357	 Binong	171	t
2358	 Tambakdahan	171	t
2359	 Ciasem	171	t
2360	 Pamanukan	171	t
2361	 Sukasari	171	t
2362	 Pusakanagara	171	t
2363	 Pusakajaya	171	t
2364	 Legonkulon	171	t
2365	 Blanakan	171	t
2366	 Jatiluhur	172	t
2367	 Sukasari	172	t
2368	 Maniis	172	t
2369	 Tegal Waru	172	t
2370	 Plered	172	t
2371	 Sukatani	172	t
2372	 Darangdan	172	t
2373	 Bojong	172	t
2374	 Wanayasa	172	t
2375	 Kiarapedes	172	t
2376	 Pasawahan	172	t
2377	 Pondok Salam	172	t
2378	 Purwakarta	172	t
2379	 Babakancikao	172	t
2380	 Campaka	172	t
2381	 Cibatu	172	t
2382	 Bungursari	172	t
2383	 Pangkalan	173	t
2384	 Tegalwaru	173	t
2385	 Ciampel	173	t
2386	 Telukjambe Timur	173	t
2387	 Telukjambe Barat	173	t
2388	 Klari	173	t
2389	 Cikampek	173	t
2390	 Purwasari	173	t
2391	 Tirtamulya	173	t
2392	 Jatisari	173	t
2393	 Banyusari	173	t
2394	 Kotabaru	173	t
2395	 Cilamaya Wetan	173	t
2396	 Cilamaya Kulon	173	t
2397	 Lemahabang	173	t
2398	 Talagasari	173	t
2399	 Majalaya	173	t
2400	 Karawang Timur	173	t
2401	 Karawang Barat	173	t
2402	 Rawamerta	173	t
2403	 Tempuran	173	t
2404	 Kutawaluya	173	t
2405	 Rengasdengklok	173	t
2406	 Jayakerta	173	t
2407	 Pedes	173	t
2408	 Cilebar	173	t
2409	 Cibuaya	173	t
2410	 Tirtajaya	173	t
2411	 Batujaya	173	t
2412	 Pakisjaya	173	t
2413	 Setu	174	t
2414	 Serang Baru	174	t
2415	 Cikarang Pusat	174	t
2416	 Cikarang Selatan	174	t
2417	 Cibarusah	174	t
2418	 Bojongmangu	174	t
2419	 Cikarang Timur	174	t
2420	 Kedungwaringin	174	t
2421	 Cikarang Utara	174	t
2422	 Karangbahagia	174	t
2423	 Cibitung	174	t
2424	 Cikarang Barat	174	t
2425	 Tambun Selatan	174	t
2426	 Tambun Utara	174	t
2427	 Babelan	174	t
2428	 Tarumajaya	174	t
2429	 Tambelang	174	t
2430	 Sukawangi	174	t
2431	 Sukatani	174	t
2432	 Sukakarya	174	t
2433	 Pebayuran	174	t
2434	 Cabangbungin	174	t
2435	 Muara Gembong	174	t
2436	 Rongga	175	t
2437	 Gununghalu	175	t
2438	 Sindangkerta	175	t
2439	 Cililin	175	t
2440	 Cihampelas	175	t
2441	 Cipongkor	175	t
2442	 Batujajar	175	t
2443	 Saguling	175	t
2444	 Cipatat	175	t
2445	 Padalarang	175	t
2446	 Ngamprah	175	t
2447	 Parongpong	175	t
2448	 Lembang	175	t
2449	 Cisarua	175	t
2450	 Cikalong Wetan	175	t
2451	 Cipeundeuy	175	t
2452	 Cimerak	176	t
2453	 Cijulang	176	t
2454	 Cigugur	176	t
2455	 Langkaplancar	176	t
2456	 Parigi	176	t
2457	 Sidamulih	176	t
2458	 Pangandaran	176	t
2459	 Kalipucang	176	t
2460	 Padaherang	176	t
2461	 Mangunjaya	176	t
2462	 Bogor Selatan	177	t
2463	 Bogor Timur	177	t
2464	 Bogor Utara	177	t
2465	 Bogor Tengah	177	t
2466	 Bogor Barat	177	t
2467	 Tanah Sereal	177	t
2468	 Baros	178	t
2469	 Lembursitu	178	t
2470	 Cibeureum	178	t
2471	 Citamiang	178	t
2472	 Warudoyong	178	t
2473	 Gunung Puyuh	178	t
2474	 Cikole	178	t
2475	 Bandung Kulon	179	t
2476	 Babakan Ciparay	179	t
2477	 Bojongloa Kaler	179	t
2478	 Bojongloa Kidul	179	t
2479	 Astanaanyar	179	t
2480	 Regol	179	t
2481	 Lengkong	179	t
2482	 Bandung Kidul	179	t
2483	 Buahbatu	179	t
2484	 Rancasari	179	t
2485	 Gedebage	179	t
2486	 Cibiru	179	t
2487	 Panyileukan	179	t
2488	 Ujung Berung	179	t
2489	 Cinambo	179	t
2490	 Arcamanik	179	t
2491	 Antapani	179	t
2492	 Mandalajati	179	t
2493	 Kiaracondong	179	t
2494	 Batununggal	179	t
2495	 Sumur Bandung	179	t
2496	 Andir	179	t
2497	 Cicendo	179	t
2498	 Bandung Wetan	179	t
2499	 Cibeunying Kidul	179	t
2500	 Cibeunying Kaler	179	t
2501	 Coblong	179	t
2502	 Sukajadi	179	t
2503	 Sukasari	179	t
2504	 Cidadap	179	t
2505	 Harjamukti	180	t
2506	 Lemahwungkuk	180	t
2507	 Pekalipan	180	t
2508	 Kesambi	180	t
2509	 Kejaksan	180	t
2510	 Pondokgede	181	t
2511	 Jatisampurna	181	t
2512	 Pondokmelati	181	t
2513	 Jatiasih	181	t
2514	 Bantargebang	181	t
2515	 Mustikajaya	181	t
2516	 Bekasi Timur	181	t
2517	 Rawalumbu	181	t
2518	 Bekasi Selatan	181	t
2519	 Bekasi Barat	181	t
2520	 Medan Satria	181	t
2521	 Bekasi Utara	181	t
2522	 Sawangan	182	t
2523	 Bojongsari	182	t
2524	 Pancoran Mas	182	t
2525	 Cipayung	182	t
2526	 Sukma Jaya	182	t
2527	 Cilodong	182	t
2528	 Cimanggis	182	t
2529	 Tapos	182	t
2530	 Beji	182	t
2531	 Limo	182	t
2532	 Cinere	182	t
2533	 Cimahi Selatan	183	t
2534	 Cimahi Tengah	183	t
2535	 Cimahi Utara	183	t
2536	 Kawalu	184	t
2537	 Tamansari	184	t
2538	 Cibeureum	184	t
2539	 Purbaratu	184	t
2540	 Tawang	184	t
2541	 Cihideung	184	t
2542	 Mangkubumi	184	t
2543	 Indihiang	184	t
2544	 Bungursari	184	t
2545	 Cipedes	184	t
2546	 Banjar	185	t
2547	 Purwaharja	185	t
2548	 Pataruman	185	t
2549	 Langensari	185	t
2550	 Dayeuhluhur	186	t
2551	 Wanareja	186	t
2552	 Majenang	186	t
2553	 Cimanggu	186	t
2554	 Karangpucung	186	t
2555	 Cipari	186	t
2556	 Sidareja	186	t
2557	 Kedungreja	186	t
2558	 Patimuan	186	t
2559	 Gandrungmangu	186	t
2560	 Bantarsari	186	t
2561	 Kawunganten	186	t
2562	 Kampung Laut	186	t
2563	 Jeruklegi	186	t
2564	 Kesugihan	186	t
2565	 Adipala	186	t
2566	 Maos	186	t
2567	 Sampang	186	t
2568	 Kroya	186	t
2569	 Binangun	186	t
2570	 Nusawungu	186	t
2571	 Cilacap Selatan	186	t
2572	 Cilacap Tengah	186	t
2573	 Cilacap Utara	186	t
2574	 Lumbir	187	t
2575	 Wangon	187	t
2576	 Jatilawang	187	t
2577	 Rawalo	187	t
2578	 Kebasen	187	t
2579	 Kemranjen	187	t
2580	 Sumpiuh	187	t
2581	 Tambak	187	t
2582	 Somagede	187	t
2583	 Kalibagor	187	t
2584	 Banyumas	187	t
2585	 Patikraja	187	t
2586	 Purwojati	187	t
2587	 Ajibarang	187	t
2588	 Gumelar	187	t
2589	 Pekuncen	187	t
2590	 Cilongok	187	t
2591	 Karanglewas	187	t
2592	 Kedung Banteng	187	t
2593	 Baturraden	187	t
2594	 Sumbang	187	t
2595	 Kembaran	187	t
2596	 Sokaraja	187	t
2597	 Purwokerto Selatan	187	t
2598	 Purwokerto Barat	187	t
2599	 Purwokerto Timur	187	t
2600	 Purwokerto Utara	187	t
2601	 Kemangkon	188	t
2602	 Bukateja	188	t
2603	 Kejobong	188	t
2604	 Pengadegan	188	t
2605	 Kaligondang	188	t
2606	 Purbalingga	188	t
2607	 Kalimanah	188	t
2608	 Padamara	188	t
2609	 Kutasari	188	t
2610	 Bojongsari	188	t
2611	 Mrebet	188	t
2612	 Bobotsari	188	t
2613	 Karangreja	188	t
2614	 Karangjambu	188	t
2615	 Karanganyar	188	t
2616	 Kertanegara	188	t
2617	 Karangmoncol	188	t
2618	 Rembang	188	t
2619	 Susukan	189	t
2620	 Purwareja Klampok	189	t
2621	 Mandiraja	189	t
2622	 Purwanegara	189	t
2623	 Bawang	189	t
2624	 Banjarnegara	189	t
2625	 Pagedongan	189	t
2626	 Sigaluh	189	t
2627	 Madukara	189	t
2628	 Banjarmangu	189	t
2629	 Wanadadi	189	t
2630	 Rakit	189	t
2631	 Punggelan	189	t
2632	 Karangkobar	189	t
2633	 Pagentan	189	t
2634	 Pejawaran	189	t
2635	 Batur	189	t
2636	 Wanayasa	189	t
2637	 Kalibening	189	t
2638	 Pandanarum	189	t
2639	 Ayah	190	t
2640	 Buayan	190	t
2641	 Puring	190	t
2642	 Petanahan	190	t
2643	 Klirong	190	t
2644	 Buluspesantren	190	t
2645	 Ambal	190	t
2646	 Mirit	190	t
2647	 Bonorowo	190	t
2648	 Prembun	190	t
2649	 Padureso	190	t
2650	 Kutowinangun	190	t
2651	 Alian	190	t
2652	 Poncowarno	190	t
2653	 Kebumen	190	t
2654	 Pejagoan	190	t
2655	 Sruweng	190	t
2656	 Adimulyo	190	t
2657	 Kuwarasan	190	t
2658	 Rowokele	190	t
2659	 Sempor	190	t
2660	 Gombong	190	t
2661	 Karanganyar	190	t
2662	 Karanggayam	190	t
2663	 Sadang	190	t
2664	 Karangsambung	190	t
2665	 Grabag	191	t
2666	 Ngombol	191	t
2667	 Purwodadi	191	t
2668	 Bagelen	191	t
2669	 Kaligesing	191	t
2670	 Purworejo	191	t
2671	 Banyu Urip	191	t
2672	 Bayan	191	t
2673	 Kutoarjo	191	t
2674	 Butuh	191	t
2675	 Pituruh	191	t
2676	 Kemiri	191	t
2677	 Bruno	191	t
2678	 Gebang	191	t
2679	 Loano	191	t
2680	 Bener	191	t
2681	 Wadaslintang	192	t
2682	 Kepil	192	t
2683	 Sapuran	192	t
2684	 Kalibawang	192	t
2685	 Kaliwiro	192	t
2686	 Leksono	192	t
2687	 Sukoharjo	192	t
2688	 Selomerto	192	t
2689	 Kalikajar	192	t
2690	 Kertek	192	t
2691	 Wonosobo	192	t
2692	 Watumalang	192	t
2693	 Mojotengah	192	t
2694	 Garung	192	t
2695	 Kejajar	192	t
2696	 Salaman	193	t
2697	 Borobudur	193	t
2698	 Ngluwar	193	t
2699	 Salam	193	t
2700	 Srumbung	193	t
2701	 Dukun	193	t
2702	 Muntilan	193	t
2703	 Mungkid	193	t
2704	 Sawangan	193	t
2705	 Candimulyo	193	t
2706	 Mertoyudan	193	t
2707	 Tempuran	193	t
2708	 Kajoran	193	t
2709	 Kaliangkrik	193	t
2710	 Bandongan	193	t
2711	 Windusari	193	t
2712	 Secang	193	t
2713	 Tegalrejo	193	t
2714	 Pakis	193	t
2715	 Grabag	193	t
2716	 Ngablak	193	t
2717	 Selo	194	t
2718	 Ampel	194	t
2719	 Cepogo	194	t
2720	 Musuk	194	t
2721	 Boyolali	194	t
2722	 Mojosongo	194	t
2723	 Teras	194	t
2724	 Sawit	194	t
2725	 Banyudono	194	t
2726	 Sambi	194	t
2727	 Ngemplak	194	t
2728	 Nogosari	194	t
2729	 Simo	194	t
2730	 Karanggede	194	t
2731	 Klego	194	t
2732	 Andong	194	t
2733	 Kemusu	194	t
2734	 Wonosegoro	194	t
2735	 Juwangi	194	t
2736	 Prambanan	195	t
2737	 Gantiwarno	195	t
2738	 Wedi	195	t
2739	 Bayat	195	t
2740	 Cawas	195	t
2741	 Trucuk	195	t
2742	 Kalikotes	195	t
2743	 Kebonarum	195	t
2744	 Jogonalan	195	t
2745	 Manisrenggo	195	t
2746	 Karangnongko	195	t
2747	 Ngawen	195	t
2748	 Ceper	195	t
2749	 Pedan	195	t
2750	 Karangdowo	195	t
2751	 Juwiring	195	t
2752	 Wonosari	195	t
2753	 Delanggu	195	t
2754	 Polanharjo	195	t
2755	 Karanganom	195	t
2756	 Tulung	195	t
2757	 Jatinom	195	t
2758	 Kemalang	195	t
2759	 Klaten Selatan	195	t
2760	 Klaten Tengah	195	t
2761	 Klaten Utara	195	t
2762	 Weru	196	t
2763	 Bulu	196	t
2764	 Tawangsari	196	t
2765	 Sukoharjo	196	t
2766	 Nguter	196	t
2767	 Bendosari	196	t
2768	 Polokarto	196	t
2769	 Mojolaban	196	t
2770	 Grogol	196	t
2771	 Baki	196	t
2772	 Gatak	196	t
2773	 Kartasura	196	t
2774	 Pracimantoro	197	t
2775	 Paranggupito	197	t
2776	 Giritontro	197	t
2777	 Giriwoyo	197	t
2778	 Batuwarno	197	t
2779	 Karangtengah	197	t
2780	 Tirtomoyo	197	t
2781	 Nguntoronadi	197	t
2782	 Baturetno	197	t
2783	 Eromoko	197	t
2784	 Wuryantoro	197	t
2785	 Manyaran	197	t
2786	 Selogiri	197	t
2787	 Wonogiri	197	t
2788	 Ngadirojo	197	t
2789	 Sidoharjo	197	t
2790	 Jatiroto	197	t
2791	 Kismantoro	197	t
2792	 Purwantoro	197	t
2793	 Bulukerto	197	t
2794	 Puhpelem	197	t
2795	 Slogohimo	197	t
2796	 Jatisrono	197	t
2797	 Jatipurno	197	t
2798	 Girimarto	197	t
2799	 Jatipuro	198	t
2800	 Jatiyoso	198	t
2801	 Jumapolo	198	t
2802	 Jumantono	198	t
2803	 Matesih	198	t
2804	 Tawangmangu	198	t
2805	 Ngargoyoso	198	t
2806	 Karangpandan	198	t
2807	 Karanganyar	198	t
2808	 Tasikmadu	198	t
2809	 Jaten	198	t
2810	 Colomadu	198	t
2811	 Gondangrejo	198	t
2812	 Kebakkramat	198	t
2813	 Mojogedang	198	t
2814	 Kerjo	198	t
2815	 Jenawi	198	t
2816	 Kalijambe	199	t
2817	 Plupuh	199	t
2818	 Masaran	199	t
2819	 Kedawung	199	t
2820	 Sambirejo	199	t
2821	 Gondang	199	t
2822	 Sambung Macan	199	t
2823	 Ngrampal	199	t
2824	 Karangmalang	199	t
2825	 Sragen	199	t
2826	 Sidoharjo	199	t
2827	 Tanon	199	t
2828	 Gemolong	199	t
2829	 Miri	199	t
2830	 Sumberlawang	199	t
2831	 Mondokan	199	t
2832	 Sukodono	199	t
2833	 Gesi	199	t
2834	 Tangen	199	t
2835	 Jenar	199	t
2836	 Kedungjati	200	t
2837	 Karangrayung	200	t
2838	 Penawangan	200	t
2839	 Toroh	200	t
2840	 Geyer	200	t
2841	 Pulokulon	200	t
2842	 Kradenan	200	t
2843	 Gabus	200	t
2844	 Ngaringan	200	t
2845	 Wirosari	200	t
2846	 Tawangharjo	200	t
2847	 Grobogan	200	t
2848	 Purwodadi	200	t
2849	 Brati	200	t
2850	 Klambu	200	t
2851	 Godong	200	t
2852	 Gubug	200	t
2853	 Tegowanu	200	t
2854	 Tanggungharjo	200	t
2855	 Jati	201	t
2856	 Randublatung	201	t
2857	 Kradenan	201	t
2858	 Kedungtuban	201	t
2859	 Cepu	201	t
2860	 Sambong	201	t
2861	 Jiken	201	t
2862	 Bogorejo	201	t
2863	 Jepon	201	t
2864	 Kota Blora	201	t
2865	 Banjarejo	201	t
2866	 Tunjungan	201	t
2867	 Japah	201	t
2868	 Ngawen	201	t
2869	 Kunduran	201	t
2870	 Todanan	201	t
2871	 Sumber	202	t
2872	 Bulu	202	t
2873	 Gunem	202	t
2874	 Sale	202	t
2875	 Sarang	202	t
2876	 Sedan	202	t
2877	 Pamotan	202	t
2878	 Sulang	202	t
2879	 Kaliori	202	t
2880	 Rembang	202	t
2881	 Pancur	202	t
2882	 Kragan	202	t
2883	 Sluke	202	t
2884	 Lasem	202	t
2885	 Sukolilo	203	t
2886	 Kayen	203	t
2887	 Tambakromo	203	t
2888	 Winong	203	t
2889	 Pucakwangi	203	t
2890	 Jaken	203	t
2891	 Batangan	203	t
2892	 Juwana	203	t
2893	 Jakenan	203	t
2894	 Pati	203	t
2895	 Gabus	203	t
2896	 Margorejo	203	t
2897	 Gembong	203	t
2898	 Tlogowungu	203	t
2899	 Wedarijaksa	203	t
2900	 Trangkil	203	t
2901	 Margoyoso	203	t
2902	 Gunung Wungkal	203	t
2903	 Cluwak	203	t
2904	 Tayu	203	t
2905	 Dukuhseti	203	t
2906	 Kaliwungu	204	t
2907	 Kota Kudus	204	t
2908	 Jati	204	t
2909	 Undaan	204	t
2910	 Mejobo	204	t
2911	 Jekulo	204	t
2912	 Bae	204	t
2913	 Gebog	204	t
2914	 Dawe	204	t
2915	 Kedung	205	t
2916	 Pecangaan	205	t
2917	 Kalinyamatan	205	t
2918	 Welahan	205	t
2919	 Mayong	205	t
2920	 Nalumsari	205	t
2921	 Batealit	205	t
2922	 Tahunan	205	t
2923	 Jepara	205	t
2924	 Mlonggo	205	t
2925	 Pakis Aji	205	t
2926	 Bangsri	205	t
2927	 Kembang	205	t
2928	 Keling	205	t
2929	 Donorojo	205	t
2930	 Karimunjawa	205	t
2931	 Mranggen	206	t
2932	 Karangawen	206	t
2933	 Guntur	206	t
2934	 Sayung	206	t
2935	 Karang Tengah	206	t
2936	 Bonang	206	t
2937	 Demak	206	t
2938	 Wonosalam	206	t
2939	 Dempet	206	t
2940	 Kebonagung	206	t
2941	 Gajah	206	t
2942	 Karanganyar	206	t
2943	 Mijen	206	t
2944	 Wedung	206	t
2945	 Getasan	207	t
2946	 Tengaran	207	t
2947	 Susukan	207	t
2948	 Kaliwungu	207	t
2949	 Suruh	207	t
2950	 Pabelan	207	t
2951	 Tuntang	207	t
2952	 Banyubiru	207	t
2953	 Jambu	207	t
2954	 Sumowono	207	t
2955	 Ambarawa	207	t
2956	 Bandungan	207	t
2957	 Bawen	207	t
2958	 Bringin	207	t
2959	 Bancak	207	t
2960	 Pringapus	207	t
2961	 Bergas	207	t
2962	 Ungaran Barat	207	t
2963	 Ungaran Timur	207	t
2964	 Parakan	208	t
2965	 Kledung	208	t
2966	 Bansari	208	t
2967	 Bulu	208	t
2968	 Temanggung	208	t
2969	 Tlogomulyo	208	t
2970	 Tembarak	208	t
2971	 Selopampang	208	t
2972	 Kranggan	208	t
2973	 Pringsurat	208	t
2974	 Kaloran	208	t
2975	 Kandangan	208	t
2976	 Kedu	208	t
2977	 Ngadirejo	208	t
2978	 Jumo	208	t
2979	 Gemawang	208	t
2980	 Candiroto	208	t
2981	 Bejen	208	t
2982	 Tretep	208	t
2983	 Wonoboyo	208	t
2984	 Plantungan	209	t
2985	 Sukorejo	209	t
2986	 Pagerruyung	209	t
2987	 Patean	209	t
2988	 Singorojo	209	t
2989	 Limbangan	209	t
2990	 Boja	209	t
2991	 Kaliwungu	209	t
2992	 Kaliwungu Selatan	209	t
2993	 Brangsong	209	t
2994	 Pegandon	209	t
2995	 Ngampel	209	t
2996	 Gemuh	209	t
2997	 Ringinarum	209	t
2998	 Weleri	209	t
2999	 Rowosari	209	t
3000	 Kangkung	209	t
3001	 Cepiring	209	t
3002	 Patebon	209	t
3003	 Kota Kendal	209	t
3004	 Wonotunggal	210	t
3005	 Bandar	210	t
3006	 Blado	210	t
3007	 Reban	210	t
3008	 Bawang	210	t
3009	 Tersono	210	t
3010	 Gringsing	210	t
3011	 Limpung	210	t
3012	 Banyuputih	210	t
3013	 Subah	210	t
3014	 Pecalungan	210	t
3015	 Tulis	210	t
3016	 Kandeman	210	t
3017	 Batang	210	t
3018	 Warung Asem	210	t
3019	 Kandangserang	211	t
3020	 Paninggaran	211	t
3021	 Lebakbarang	211	t
3022	 Petungkriono	211	t
3023	 Talun	211	t
3024	 Doro	211	t
3025	 Karanganyar	211	t
3026	 Kajen	211	t
3027	 Kesesi	211	t
3028	 Sragi	211	t
3029	 Siwalan	211	t
3030	 Bojong	211	t
3031	 Wonopringgo	211	t
3032	 Kedungwuni	211	t
3033	 Karangdadap	211	t
3034	 Buaran	211	t
3035	 Tirto	211	t
3036	 Wiradesa	211	t
3037	 Wonokerto	211	t
3038	 Moga	212	t
3039	 Warungpring	212	t
3040	 Pulosari	212	t
3041	 Belik	212	t
3042	 Watukumpul	212	t
3043	 Bodeh	212	t
3044	 Bantarbolang	212	t
3045	 Randudongkal	212	t
3046	 Pemalang	212	t
3047	 Taman	212	t
3048	 Petarukan	212	t
3049	 Ampelgading	212	t
3050	 Comal	212	t
3051	 Ulujami	212	t
3052	 Margasari	213	t
3053	 Bumijawa	213	t
3054	 Bojong	213	t
3055	 Balapulang	213	t
3056	 Pagerbarang	213	t
3057	 Lebaksiu	213	t
3058	 Jatinegara	213	t
3059	 Kedung Banteng	213	t
3060	 Pangkah	213	t
3061	 Slawi	213	t
3062	 Dukuhwaru	213	t
3063	 Adiwerna	213	t
3064	 Dukuhturi	213	t
3065	 Talang	213	t
3066	 Tarub	213	t
3067	 Kramat	213	t
3068	 Suradadi	213	t
3069	 Warureja	213	t
3070	 Salem	214	t
3071	 Bantarkawung	214	t
3072	 Bumiayu	214	t
3073	 Paguyangan	214	t
3074	 Sirampog	214	t
3075	 Tonjong	214	t
3076	 Larangan	214	t
3077	 Ketanggungan	214	t
3078	 Banjarharjo	214	t
3079	 Losari	214	t
3080	 Tanjung	214	t
3081	 Kersana	214	t
3082	 Bulakamba	214	t
3083	 Wanasari	214	t
3084	 Songgom	214	t
3085	 Jatibarang	214	t
3086	 Brebes	214	t
3087	 Magelang Selatan	215	t
3088	 Magelang Tengah	215	t
3089	 Magelang Utara	215	t
3090	 Laweyan	216	t
3091	 Serengan	216	t
3092	 Pasar Kliwon	216	t
3093	 Jebres	216	t
3094	 Banjarsari	216	t
3095	 Argomulyo	217	t
3096	 Tingkir	217	t
3097	 Sidomukti	217	t
3098	 Sidorejo	217	t
3099	 Mijen	218	t
3100	 Gunung Pati	218	t
3101	 Banyumanik	218	t
3102	 Gajah Mungkur	218	t
3103	 Semarang Selatan	218	t
3104	 Candisari	218	t
3105	 Tembalang	218	t
3106	 Pedurungan	218	t
3107	 Genuk	218	t
3108	 Gayamsari	218	t
3109	 Semarang Timur	218	t
3110	 Semarang Utara	218	t
3111	 Semarang Tengah	218	t
3112	 Semarang Barat	218	t
3113	 Tugu	218	t
3114	 Ngaliyan	218	t
3115	 Pekalongan Barat	219	t
3116	 Pekalongan Timur	219	t
3117	 Pekalongan Selatan	219	t
3118	 Pekalongan Utara	219	t
3119	 Tegal Selatan	220	t
3120	 Tegal Timur	220	t
3121	 Tegal Barat	220	t
3122	 Margadana	220	t
3123	 Temon	221	t
3124	 Wates	221	t
3125	 Panjatan	221	t
3126	 Galur	221	t
3127	 Lendah	221	t
3128	 Sentolo	221	t
3129	 Pengasih	221	t
3130	 Kokap	221	t
3131	 Girimulyo	221	t
3132	 Nanggulan	221	t
3133	 Kalibawang	221	t
3134	 Samigaluh	221	t
3135	 Srandakan	222	t
3136	 Sanden	222	t
3137	 Kretek	222	t
3138	 Pundong	222	t
3139	 Bambang Lipuro	222	t
3140	 Pandak	222	t
3141	 Bantul	222	t
3142	 Jetis	222	t
3143	 Imogiri	222	t
3144	 Dlingo	222	t
3145	 Pleret	222	t
3146	 Piyungan	222	t
3147	 Banguntapan	222	t
3148	 Sewon	222	t
3149	 Kasihan	222	t
3150	 Pajangan	222	t
3151	 Sedayu	222	t
3152	 Panggang	223	t
3153	 Purwosari	223	t
3154	 Paliyan	223	t
3155	 Sapto Sari	223	t
3156	 Tepus	223	t
3157	 Tanjungsari	223	t
3158	 Rongkop	223	t
3159	 Girisubo	223	t
3160	 Semanu	223	t
3161	 Ponjong	223	t
3162	 Karangmojo	223	t
3163	 Wonosari	223	t
3164	 Playen	223	t
3165	 Patuk	223	t
3166	 Gedang Sari	223	t
3167	 Nglipar	223	t
3168	 Ngawen	223	t
3169	 Semin	223	t
3170	 Moyudan	224	t
3171	 Minggir	224	t
3172	 Seyegan	224	t
3173	 Godean	224	t
3174	 Gamping	224	t
3175	 Mlati	224	t
3176	 Depok	224	t
3177	 Berbah	224	t
3178	 Prambanan	224	t
3179	 Kalasan	224	t
3180	 Ngemplak	224	t
3181	 Ngaglik	224	t
3182	 Sleman	224	t
3183	 Tempel	224	t
3184	 Turi	224	t
3185	 Pakem	224	t
3186	 Cangkringan	224	t
3187	 Mantrijeron	225	t
3188	 Kraton	225	t
3189	 Mergangsan	225	t
3190	 Umbulharjo	225	t
3191	 Kotagede	225	t
3192	 Gondokusuman	225	t
3193	 Danurejan	225	t
3194	 Pakualaman	225	t
3195	 Gondomanan	225	t
3196	 Ngampilan	225	t
3197	 Wirobrajan	225	t
3198	 Gedong Tengen	225	t
3199	 Jetis	225	t
3200	 Tegalrejo	225	t
3201	 Donorojo	226	t
3202	 Punung	226	t
3203	 Pringkuku	226	t
3204	 Pacitan	226	t
3205	 Kebonagung	226	t
3206	 Arjosari	226	t
3207	 Nawangan	226	t
3208	 Bandar	226	t
3209	 Tegalombo	226	t
3210	 Tulakan	226	t
3211	 Ngadirojo	226	t
3212	 Sudimoro	226	t
3213	 Ngrayun	227	t
3214	 Slahung	227	t
3215	 Bungkal	227	t
3216	 Sambit	227	t
3217	 Sawoo	227	t
3218	 Sooko	227	t
3219	 Pudak	227	t
3220	 Pulung	227	t
3221	 Mlarak	227	t
3222	 Siman	227	t
3223	 Jetis	227	t
3224	 Balong	227	t
3225	 Kauman	227	t
3226	 Jambon	227	t
3227	 Badegan	227	t
3228	 Sampung	227	t
3229	 Sukorejo	227	t
3230	 Ponorogo	227	t
3231	 Babadan	227	t
3232	 Jenangan	227	t
3233	 Ngebel	227	t
3234	 Panggul	228	t
3235	 Munjungan	228	t
3236	 Watulimo	228	t
3237	 Kampak	228	t
3238	 Dongko	228	t
3239	 Pule	228	t
3240	 Karangan	228	t
3241	 Suruh	228	t
3242	 Gandusari	228	t
3243	 Durenan	228	t
3244	 Pogalan	228	t
3245	 Trenggalek	228	t
3246	 Tugu	228	t
3247	 Bendungan	228	t
3248	 Besuki	229	t
3249	 Bandung	229	t
3250	 Pakel	229	t
3251	 Campur Darat	229	t
3252	 Tanggung Gunung	229	t
3253	 Kalidawir	229	t
3254	 Pucang Laban	229	t
3255	 Rejotangan	229	t
3256	 Ngunut	229	t
3257	 Sumbergempol	229	t
3258	 Boyolangu	229	t
3259	 Tulungagung	229	t
3260	 Kedungwaru	229	t
3261	 Ngantru	229	t
3262	 Karangrejo	229	t
3263	 Kauman	229	t
3264	 Gondang	229	t
3265	 Pager Wojo	229	t
3266	 Sendang	229	t
3267	 Bakung	230	t
3268	 Wonotirto	230	t
3269	 Panggungrejo	230	t
3270	 Wates	230	t
3271	 Binangun	230	t
3272	 Sutojayan	230	t
3273	 Kademangan	230	t
3274	 Kanigoro	230	t
3275	 Talun	230	t
3276	 Selopuro	230	t
3277	 Kesamben	230	t
3278	 Selorejo	230	t
3279	 Doko	230	t
3280	 Wlingi	230	t
3281	 Gandusari	230	t
3282	 Garum	230	t
3283	 Nglegok	230	t
3284	 Sanankulon	230	t
3285	 Ponggok	230	t
3286	 Srengat	230	t
3287	 Wonodadi	230	t
3288	 Udanawu	230	t
3289	 Mojo	231	t
3290	 Semen	231	t
3291	 Ngadiluwih	231	t
3292	 Kras	231	t
3293	 Ringinrejo	231	t
3294	 Kandat	231	t
3295	 Wates	231	t
3296	 Ngancar	231	t
3297	 Plosoklaten	231	t
3298	 Gurah	231	t
3299	 Puncu	231	t
3300	 Kepung	231	t
3301	 Kandangan	231	t
3302	 Pare	231	t
3303	 Badas	231	t
3304	 Kunjang	231	t
3305	 Plemahan	231	t
3306	 Purwoasri	231	t
3307	 Papar	231	t
3308	 Pagu	231	t
3309	 Kayen Kidul	231	t
3310	 Gampengrejo	231	t
3311	 Ngasem	231	t
3312	 Banyakan	231	t
3313	 Grogol	231	t
3314	 Tarokan	231	t
3315	 Donomulyo	232	t
3316	 Kalipare	232	t
3317	 Pagak	232	t
3318	 Bantur	232	t
3319	 Gedangan	232	t
3320	 Sumbermanjing	232	t
3321	 Dampit	232	t
3322	 Tirto Yudo	232	t
3323	 Ampelgading	232	t
3324	 Poncokusumo	232	t
3325	 Wajak	232	t
3326	 Turen	232	t
3327	 Bululawang	232	t
3328	 Gondanglegi	232	t
3329	 Pagelaran	232	t
3330	 Kepanjen	232	t
3331	 Sumber Pucung	232	t
3332	 Kromengan	232	t
3333	 Ngajum	232	t
3334	 Wonosari	232	t
3335	 Wagir	232	t
3336	 Pakisaji	232	t
3337	 Tajinan	232	t
3338	 Tumpang	232	t
3339	 Pakis	232	t
3340	 Jabung	232	t
3341	 Lawang	232	t
3342	 Singosari	232	t
3343	 Karangploso	232	t
3344	 Dau	232	t
3345	 Pujon	232	t
3346	 Ngantang	232	t
3347	 Kasembon	232	t
3348	 Tempursari	233	t
3349	 Pronojiwo	233	t
3350	 Candipuro	233	t
3351	 Pasirian	233	t
3352	 Tempeh	233	t
3353	 Lumajang	233	t
3354	 Sumbersuko	233	t
3355	 Tekung	233	t
3356	 Kunir	233	t
3357	 Yosowilangun	233	t
3358	 Rowokangkung	233	t
3359	 Jatiroto	233	t
3360	 Randuagung	233	t
3361	 Sukodono	233	t
3362	 Padang	233	t
3363	 Pasrujambe	233	t
3364	 Senduro	233	t
3365	 Gucialit	233	t
3366	 Kedungjajang	233	t
3367	 Klakah	233	t
3368	 Ranuyoso	233	t
3369	 Kencong	234	t
3370	 Gumuk Mas	234	t
3371	 Puger	234	t
3372	 Wuluhan	234	t
3373	 Ambulu	234	t
3374	 Tempurejo	234	t
3375	 Silo	234	t
3376	 Mayang	234	t
3377	 Mumbulsari	234	t
3378	 Jenggawah	234	t
3379	 Ajung	234	t
3380	 Rambipuji	234	t
3381	 Balung	234	t
3382	 Umbulsari	234	t
3383	 Semboro	234	t
3384	 Jombang	234	t
3385	 Sumber Baru	234	t
3386	 Tanggul	234	t
3387	 Bangsalsari	234	t
3388	 Panti	234	t
3389	 Sukorambi	234	t
3390	 Arjasa	234	t
3391	 Pakusari	234	t
3392	 Kalisat	234	t
3393	 Ledokombo	234	t
3394	 Sumberjambe	234	t
3395	 Sukowono	234	t
3396	 Jelbuk	234	t
3397	 Kaliwates	234	t
3398	 Sumbersari	234	t
3399	 Patrang	234	t
3400	 Pesanggaran	235	t
3401	 Siliragung	235	t
3402	 Bangorejo	235	t
3403	 Purwoharjo	235	t
3404	 Tegaldlimo	235	t
3405	 Muncar	235	t
3406	 Cluring	235	t
3407	 Gambiran	235	t
3408	 Tegalsari	235	t
3409	 Glenmore	235	t
3410	 Kalibaru	235	t
3411	 Genteng	235	t
3412	 Srono	235	t
3413	 Rogojampi	235	t
3414	 Kabat	235	t
3415	 Singojuruh	235	t
3416	 Sempu	235	t
3417	 Songgon	235	t
3418	 Glagah	235	t
3419	 Licin	235	t
3420	 Banyuwangi	235	t
3421	 Giri	235	t
3422	 Kalipuro	235	t
3423	 Wongsorejo	235	t
3424	 Maesan	236	t
3425	 Grujugan	236	t
3426	 Tamanan	236	t
3427	 Jambesari Darus Sholah	236	t
3428	 Pujer	236	t
3429	 Tlogosari	236	t
3430	 Sukosari	236	t
3431	 Sumber Wringin	236	t
3432	 Tapen	236	t
3433	 Wonosari	236	t
3434	 Tenggarang	236	t
3435	 Bondowoso	236	t
3436	 Curah Dami	236	t
3437	 Binakal	236	t
3438	 Pakem	236	t
3439	 Wringin	236	t
3440	 Tegalampel	236	t
3441	 Taman Krocok	236	t
3442	 Klabang	236	t
3443	 Sempol	236	t
3444	 Botolinggo	236	t
3445	 Prajekan	236	t
3446	 Cermee	236	t
3447	 Sumbermalang	237	t
3448	 Jatibanteng	237	t
3449	 Banyuglugur	237	t
3450	 Besuki	237	t
3451	 Suboh	237	t
3452	 Mlandingan	237	t
3453	 Bungatan	237	t
3454	 Kendit	237	t
3455	 Panarukan	237	t
3456	 Situbondo	237	t
3457	 Mangaran	237	t
3458	 Panji	237	t
3459	 Kapongan	237	t
3460	 Arjasa	237	t
3461	 Jangkar	237	t
3462	 Asembagus	237	t
3463	 Banyuputih	237	t
3464	 Sukapura	238	t
3465	 Sumber	238	t
3466	 Kuripan	238	t
3467	 Bantaran	238	t
3468	 Leces	238	t
3469	 Tegalsiwalan	238	t
3470	 Banyuanyar	238	t
3471	 Tiris	238	t
3472	 Krucil	238	t
3473	 Gading	238	t
3474	 Pakuniran	238	t
3475	 Kotaanyar	238	t
3476	 Paiton	238	t
3477	 Besuk	238	t
3478	 Kraksaan	238	t
3479	 Krejengan	238	t
3480	 Pajarakan	238	t
3481	 Maron	238	t
3482	 Gending	238	t
3483	 Dringu	238	t
3484	 Wonomerto	238	t
3485	 Lumbang	238	t
3486	 Tongas	238	t
3487	 Sumberasih	238	t
3488	 Purwodadi	239	t
3489	 Tutur	239	t
3490	 Puspo	239	t
3491	 Tosari	239	t
3492	 Lumbang	239	t
3493	 Pasrepan	239	t
3494	 Kejayan	239	t
3495	 Wonorejo	239	t
3496	 Purwosari	239	t
3497	 Prigen	239	t
3498	 Sukorejo	239	t
3499	 Pandaan	239	t
3500	 Gempol	239	t
3501	 Beji	239	t
3502	 Bangil	239	t
3503	 Rembang	239	t
3504	 Kraton	239	t
3505	 Pohjentrek	239	t
3506	 Gondang Wetan	239	t
3507	 Rejoso	239	t
3508	 Winongan	239	t
3509	 Grati	239	t
3510	 Lekok	239	t
3511	 Nguling	239	t
3512	 Tarik	240	t
3513	 Prambon	240	t
3514	 Krembung	240	t
3515	 Porong	240	t
3516	 Jabon	240	t
3517	 Tanggulangin	240	t
3518	 Candi	240	t
3519	 Tulangan	240	t
3520	 Wonoayu	240	t
3521	 Sukodono	240	t
3522	 Sidoarjo	240	t
3523	 Buduran	240	t
3524	 Sedati	240	t
3525	 Waru	240	t
3526	 Gedangan	240	t
3527	 Taman	240	t
3528	 Krian	240	t
3529	 Balong Bendo	240	t
3530	 Jatirejo	241	t
3531	 Gondang	241	t
3532	 Pacet	241	t
3533	 Trawas	241	t
3534	 Ngoro	241	t
3535	 Pungging	241	t
3536	 Kutorejo	241	t
3537	 Mojosari	241	t
3538	 Bangsal	241	t
3539	 Mojoanyar	241	t
3540	 Dlanggu	241	t
3541	 Puri	241	t
3542	 Trowulan	241	t
3543	 Sooko	241	t
3544	 Gedek	241	t
3545	 Kemlagi	241	t
3546	 Jetis	241	t
3547	 Dawar Blandong	241	t
3548	 Bandar Kedung Mulyo	242	t
3549	 Perak	242	t
3550	 Gudo	242	t
3551	 Diwek	242	t
3552	 Ngoro	242	t
3553	 Mojowarno	242	t
3554	 Bareng	242	t
3555	 Wonosalam	242	t
3556	 Mojoagung	242	t
3557	 Sumobito	242	t
3558	 Jogo Roto	242	t
3559	 Peterongan	242	t
3560	 Jombang	242	t
3561	 Megaluh	242	t
3562	 Tembelang	242	t
3563	 Kesamben	242	t
3564	 Kudu	242	t
3565	 Ngusikan	242	t
3566	 Ploso	242	t
3567	 Kabuh	242	t
3568	 Plandaan	242	t
3569	 Sawahan	243	t
3570	 Ngetos	243	t
3571	 Berbek	243	t
3572	 Loceret	243	t
3573	 Pace	243	t
3574	 Tanjunganom	243	t
3575	 Prambon	243	t
3576	 Ngronggot	243	t
3577	 Kertosono	243	t
3578	 Patianrowo	243	t
3579	 Baron	243	t
3580	 Gondang	243	t
3581	 Sukomoro	243	t
3582	 Nganjuk	243	t
3583	 Bagor	243	t
3584	 Wilangan	243	t
3585	 Rejoso	243	t
3586	 Ngluyu	243	t
3587	 Lengkong	243	t
3588	 Jatikalen	243	t
3589	 Kebonsari	244	t
3590	 Geger	244	t
3591	 Dolopo	244	t
3592	 Dagangan	244	t
3593	 Wungu	244	t
3594	 Kare	244	t
3595	 Gemarang	244	t
3596	 Saradan	244	t
3597	 Pilangkenceng	244	t
3598	 Mejayan	244	t
3599	 Wonoasri	244	t
3600	 Balerejo	244	t
3601	 Madiun	244	t
3602	 Sawahan	244	t
3603	 Jiwan	244	t
3604	 Poncol	245	t
3605	 Parang	245	t
3606	 Lembeyan	245	t
3607	 Takeran	245	t
3608	 Nguntoronadi	245	t
3609	 Kawedanan	245	t
3610	 Magetan	245	t
3611	 Ngariboyo	245	t
3612	 Plaosan	245	t
3613	 Sidorejo	245	t
3614	 Panekan	245	t
3615	 Sukomoro	245	t
3616	 Bendo	245	t
3617	 Maospati	245	t
3618	 Karangrejo	245	t
3619	 Karas	245	t
3620	 Barat	245	t
3621	 Kartoharjo	245	t
3622	 Sine	246	t
3623	 Ngrambe	246	t
3624	 Jogorogo	246	t
3625	 Kendal	246	t
3626	 Geneng	246	t
3627	 Gerih	246	t
3628	 Kwadungan	246	t
3629	 Pangkur	246	t
3630	 Karangjati	246	t
3631	 Bringin	246	t
3632	 Padas	246	t
3633	 Kasreman	246	t
3634	 Ngawi	246	t
3635	 Paron	246	t
3636	 Kedunggalar	246	t
3637	 Pitu	246	t
3638	 Widodaren	246	t
3639	 Mantingan	246	t
3640	 Karanganyar	246	t
3641	 Margomulyo	247	t
3642	 Ngraho	247	t
3643	 Tambakrejo	247	t
3644	 Ngambon	247	t
3645	 Sekar	247	t
3646	 Bubulan	247	t
3647	 Gondang	247	t
3648	 Temayang	247	t
3649	 Sugihwaras	247	t
3650	 Kedungadem	247	t
3651	 Kepoh Baru	247	t
3652	 Baureno	247	t
3653	 Kanor	247	t
3654	 Sumberejo	247	t
3655	 Balen	247	t
3656	 Sukosewu	247	t
3657	 Kapas	247	t
3658	 Bojonegoro	247	t
3659	 Trucuk	247	t
3660	 Dander	247	t
3661	 Ngasem	247	t
3662	 Gayam	247	t
3663	 Kalitidu	247	t
3664	 Malo	247	t
3665	 Purwosari	247	t
3666	 Padangan	247	t
3667	 Kasiman	247	t
3668	 Kedewan	247	t
3669	 Kenduruan	248	t
3670	 Bangilan	248	t
3671	 Senori	248	t
3672	 Singgahan	248	t
3673	 Montong	248	t
3674	 Parengan	248	t
3675	 Soko	248	t
3676	 Rengel	248	t
3677	 Grabagan	248	t
3678	 Plumpang	248	t
3679	 Widang	248	t
3680	 Palang	248	t
3681	 Semanding	248	t
3682	 Tuban	248	t
3683	 Jenu	248	t
3684	 Merakurak	248	t
3685	 Kerek	248	t
3686	 Tambakboyo	248	t
3687	 Jatirogo	248	t
3688	 Bancar	248	t
3689	 Sukorame	249	t
3690	 Bluluk	249	t
3691	 Ngimbang	249	t
3692	 Sambeng	249	t
3693	 Mantup	249	t
3694	 Kembangbahu	249	t
3695	 Sugio	249	t
3696	 Kedungpring	249	t
3697	 Modo	249	t
3698	 Babat	249	t
3699	 Pucuk	249	t
3700	 Sukodadi	249	t
3701	 Lamongan	249	t
3702	 Tikung	249	t
3703	 Sarirejo	249	t
3704	 Deket	249	t
3705	 Glagah	249	t
3706	 Karangbinangun	249	t
3707	 Turi	249	t
3708	 Kalitengah	249	t
3709	 Karang Geneng	249	t
3710	 Sekaran	249	t
3711	 Maduran	249	t
3712	 Laren	249	t
3713	 Solokuro	249	t
3714	 Paciran	249	t
3715	 Brondong	249	t
3716	 Wringinanom	250	t
3717	 Driyorejo	250	t
3718	 Kedamean	250	t
3719	 Menganti	250	t
3720	 Cerme	250	t
3721	 Benjeng	250	t
3722	 Balongpanggang	250	t
3723	 Duduksampeyan	250	t
3724	 Kebomas	250	t
3725	 Gresik	250	t
3726	 Manyar	250	t
3727	 Bungah	250	t
3728	 Sidayu	250	t
3729	 Dukun	250	t
3730	 Panceng	250	t
3731	 Ujungpangkah	250	t
3732	 Sangkapura	250	t
3733	 Tambak	250	t
3734	 Kamal	251	t
3735	 Labang	251	t
3736	 Kwanyar	251	t
3737	 Modung	251	t
3738	 Blega	251	t
3739	 Konang	251	t
3740	 Galis	251	t
3741	 Tanah Merah	251	t
3742	 Tragah	251	t
3743	 Socah	251	t
3744	 Bangkalan	251	t
3745	 Burneh	251	t
3746	 Arosbaya	251	t
3747	 Geger	251	t
3748	 Kokop	251	t
3749	 Tanjungbumi	251	t
3750	 Sepulu	251	t
3751	 Klampis	251	t
3752	 Sreseh	252	t
3753	 Torjun	252	t
3754	 Pangarengan	252	t
3755	 Sampang	252	t
3756	 Camplong	252	t
3757	 Omben	252	t
3758	 Kedungdung	252	t
3759	 Jrengik	252	t
3760	 Tambelangan	252	t
3761	 Banyuates	252	t
3762	 Robatal	252	t
3763	 Karang Penang	252	t
3764	 Ketapang	252	t
3765	 Sokobanah	252	t
3766	 Tlanakan	253	t
3767	 Pademawu	253	t
3768	 Galis	253	t
3769	 Larangan	253	t
3770	 Pamekasan	253	t
3771	 Proppo	253	t
3772	 Palengaan	253	t
3773	 Pegantenan	253	t
3774	 Kadur	253	t
3775	 Pakong	253	t
3776	 Waru	253	t
3777	 Batu Marmar	253	t
3778	 Pasean	253	t
3779	 Pragaan	254	t
3780	 Bluto	254	t
3781	 Saronggi	254	t
3782	 Giligenteng	254	t
3783	 Talango	254	t
3784	 Kalianget	254	t
3785	 Kota Sumenep	254	t
3786	 Batuan	254	t
3787	 Lenteng	254	t
3788	 Ganding	254	t
3789	 Guluk Guluk	254	t
3790	 Pasongsongan	254	t
3791	 Ambunten	254	t
3792	 Rubaru	254	t
3793	 Dasuk	254	t
3794	 Manding	254	t
3795	 Batuputih	254	t
3796	 Gapura	254	t
3797	 Batang Batang	254	t
3798	 Dungkek	254	t
3799	 Nonggunong	254	t
3800	 Gayam	254	t
3801	 Raas	254	t
3802	 Sapeken	254	t
3803	 Arjasa	254	t
3804	 Kangayan	254	t
3805	 Masalembu	254	t
3806	 Mojoroto	255	t
3807	 Kota Kediri	255	t
3808	 Pesantren	255	t
3809	 Sukorejo	256	t
3810	 Kepanjenkidul	256	t
3811	 Sananwetan	256	t
3812	 Kedungkandang	257	t
3813	 Sukun	257	t
3814	 Klojen	257	t
3815	 Blimbing	257	t
3816	 Lowokwaru	257	t
3817	 Kademangan	258	t
3818	 Kedopok	258	t
3819	 Wonoasih	258	t
3820	 Mayangan	258	t
3821	 Kanigaran	258	t
3822	 Gadingrejo	259	t
3823	 Purworejo	259	t
3824	 Bugulkidul	259	t
3825	 Panggungrejo	259	t
3826	 Prajurit Kulon	260	t
3827	 Magersari	260	t
3828	 Mangu Harjo	261	t
3829	 Taman	261	t
3830	 Kartoharjo	261	t
3831	 Karang Pilang	262	t
3832	 Jambangan	262	t
3833	 Gayungan	262	t
3834	 Wonocolo	262	t
3835	 Tenggilis Mejoyo	262	t
3836	 Gunung Anyar	262	t
3837	 Rungkut	262	t
3838	 Sukolilo	262	t
3839	 Mulyorejo	262	t
3840	 Gubeng	262	t
3841	 Wonokromo	262	t
3842	 Dukuh Pakis	262	t
3843	 Wiyung	262	t
3844	 Lakarsantri	262	t
3845	 Sambikerep	262	t
3846	 Tandes	262	t
3847	 Suko Manunggal	262	t
3848	 Sawahan	262	t
3849	 Tegalsari	262	t
3850	 Genteng	262	t
3851	 Tambaksari	262	t
3852	 Kenjeran	262	t
3853	 Bulak	262	t
3854	 Simokerto	262	t
3855	 Semampir	262	t
3856	 Pabean Cantian	262	t
3857	 Bubutan	262	t
3858	 Krembangan	262	t
3859	 Asemrowo	262	t
3860	 Benowo	262	t
3861	 Pakal	262	t
3862	 Batu	263	t
3863	 Junrejo	263	t
3864	 Bumiaji	263	t
3865	 Sumur	264	t
3866	 Cimanggu	264	t
3867	 Cibaliung	264	t
3868	 Cibitung	264	t
3869	 Cikeusik	264	t
3870	 Cigeulis	264	t
3871	 Panimbang	264	t
3872	 Sobang	264	t
3873	 Munjul	264	t
3874	 Angsana	264	t
3875	 Sindangresmi	264	t
3876	 Picung	264	t
3877	 Bojong	264	t
3878	 Saketi	264	t
3879	 Cisata	264	t
3880	 Pagelaran	264	t
3881	 Patia	264	t
3882	 Sukaresmi	264	t
3883	 Labuan	264	t
3884	 Carita	264	t
3885	 Jiput	264	t
3886	 Cikedal	264	t
3887	 Menes	264	t
3888	 Pulosari	264	t
3889	 Mandalawangi	264	t
3890	 Cimanuk	264	t
3891	 Cipeucang	264	t
3892	 Banjar	264	t
3893	 Kaduhejo	264	t
3894	 Mekarjaya	264	t
3895	 Pandeglang	264	t
3896	 Majasari	264	t
3897	 Cadasari	264	t
3898	 Karangtanjung	264	t
3899	 Koroncong	264	t
3900	 Malingping	265	t
3901	 Wanasalam	265	t
3902	 Panggarangan	265	t
3903	 Cihara	265	t
3904	 Bayah	265	t
3905	 Cilograng	265	t
3906	 Cibeber	265	t
3907	 Cijaku	265	t
3908	 Cigemblong	265	t
3909	 Banjarsari	265	t
3910	 Cileles	265	t
3911	 Gunung Kencana	265	t
3912	 Bojongmanik	265	t
3913	 Cirinten	265	t
3914	 Leuwidamar	265	t
3915	 Muncang	265	t
3916	 Sobang	265	t
3917	 Cipanas	265	t
3918	 Lebakgedong	265	t
3919	 Sajira	265	t
3920	 Cimarga	265	t
3921	 Cikulur	265	t
3922	 Warunggunung	265	t
3923	 Cibadak	265	t
3924	 Rangkasbitung	265	t
3925	 Kalanganyar	265	t
3926	 Maja	265	t
3927	 Curugbitung	265	t
3928	 Cisoka	266	t
3929	 Solear	266	t
3930	 Tigaraksa	266	t
3931	 Jambe	266	t
3932	 Cikupa	266	t
3933	 Panongan	266	t
3934	 Curug	266	t
3935	 Kelapa Dua	266	t
3936	 Legok	266	t
3937	 Pagedangan	266	t
3938	 Cisauk	266	t
3939	 Pasarkemis	266	t
3940	 Sindang Jaya	266	t
3941	 Balaraja	266	t
3942	 Jayanti	266	t
3943	 Sukamulya	266	t
3944	 Kresek	266	t
3945	 Gunung Kaler	266	t
3946	 Kronjo	266	t
3947	 Mekar Baru	266	t
3948	 Mauk	266	t
3949	 Kemiri	266	t
3950	 Sukadiri	266	t
3951	 Rajeg	266	t
3952	 Sepatan	266	t
3953	 Sepatan Timur	266	t
3954	 Pakuhaji	266	t
3955	 Teluknaga	266	t
3956	 Kosambi	266	t
3957	 Cinangka	267	t
3958	 Padarincang	267	t
3959	 Ciomas	267	t
3960	 Pabuaran	267	t
3961	 Gunung Sari	267	t
3962	 Baros	267	t
3963	 Petir	267	t
3964	 Tunjung Teja	267	t
3965	 Cikeusal	267	t
3966	 Pamarayan	267	t
3967	 Bandung	267	t
3968	 Jawilan	267	t
3969	 Kopo	267	t
3970	 Cikande	267	t
3971	 Kibin	267	t
3972	 Kragilan	267	t
3973	 Waringinkurung	267	t
3974	 Mancak	267	t
3975	 Anyar	267	t
3976	 Bojonegara	267	t
3977	 Pulo Ampel	267	t
3978	 Kramatwatu	267	t
3979	 Ciruas	267	t
3980	 Pontang	267	t
3981	 Lebak Wangi	267	t
3982	 Carenang	267	t
3983	 Binuang	267	t
3984	 Tirtayasa	267	t
3985	 Tanara	267	t
3986	 Ciledug	268	t
3987	 Larangan	268	t
3988	 Karang Tengah	268	t
3989	 Cipondoh	268	t
3990	 Pinang	268	t
3991	 Tangerang	268	t
3992	 Karawaci	268	t
3993	 Jati Uwung	268	t
3994	 Cibodas	268	t
3995	 Periuk	268	t
3996	 Batuceper	268	t
3997	 Neglasari	268	t
3998	 Benda	268	t
3999	 Ciwandan	269	t
4000	 Citangkil	269	t
4001	 Pulomerak	269	t
4002	 Purwakarta	269	t
4003	 Grogol	269	t
4004	 Cilegon	269	t
4005	 Jombang	269	t
4006	 Cibeber	269	t
4007	 Curug	270	t
4008	 Walantaka	270	t
4009	 Cipocok Jaya	270	t
4010	 Serang	270	t
4011	 Taktakan	270	t
4012	 Kasemen	270	t
4013	 Setu	271	t
4014	 Serpong	271	t
4015	 Pamulang	271	t
4016	 Ciputat	271	t
4017	 Ciputat Timur	271	t
4018	 Pondok Aren	271	t
4019	 Serpong Utara	271	t
4020	 Melaya	272	t
4021	 Negara	272	t
4022	 Jembrana	272	t
4023	 Mendoyo	272	t
4024	 Pekutatan	272	t
4025	 Selemadeg	273	t
4026	 Selemadeg Timur	273	t
4027	 Selemadeg Barat	273	t
4028	 Kerambitan	273	t
4029	 Tabanan	273	t
4030	 Kediri	273	t
4031	 Marga	273	t
4032	 Baturiti	273	t
4033	 Penebel	273	t
4034	 Pupuan	273	t
4035	 Kuta Selatan	274	t
4036	 Kuta	274	t
4037	 Kuta Utara	274	t
4038	 Mengwi	274	t
4039	 Abiansemal	274	t
4040	 Petang	274	t
4041	 Sukawati	275	t
4042	 Blahbatuh	275	t
4043	 Gianyar	275	t
4044	 Tampaksiring	275	t
4045	 Ubud	275	t
4046	 Tegallalang	275	t
4047	 Payangan	275	t
4048	 Nusapenida	276	t
4049	 Banjarangkan	276	t
4050	 Klungkung	276	t
4051	 Dawan	276	t
4052	 Susut	277	t
4053	 Bangli	277	t
4054	 Tembuku	277	t
4055	 Kintamani	277	t
4056	 Rendang	278	t
4057	 Sidemen	278	t
4058	 Manggis	278	t
4059	 Karangasem	278	t
4060	 Abang	278	t
4061	 Bebandem	278	t
4062	 Selat	278	t
4063	 Kubu	278	t
4064	 Gerokgak	279	t
4065	 Seririt	279	t
4066	 Busungbiu	279	t
4067	 Banjar	279	t
4068	 Sukasada	279	t
4069	 Buleleng	279	t
4070	 Sawan	279	t
4071	 Kubutambahan	279	t
4072	 Tejakula	279	t
4073	 Denpasar Selatan	280	t
4074	 Denpasar Timur	280	t
4075	 Denpasar Barat	280	t
4076	 Denpasar Utara	280	t
4077	 Sekotong	281	t
4078	 Lembar	281	t
4079	 Gerung	281	t
4080	 Labu Api	281	t
4081	 Kediri	281	t
4082	 Kuripan	281	t
4083	 Narmada	281	t
4084	 Lingsar	281	t
4085	 Gunung Sari	281	t
4086	 Batu Layar	281	t
4087	 Praya Barat	282	t
4088	 Praya Barat Daya	282	t
4089	 Pujut	282	t
4090	 Praya Timur	282	t
4091	 Janapria	282	t
4092	 Kopang	282	t
4093	 Praya	282	t
4094	 Praya Tengah	282	t
4095	 Jonggat	282	t
4096	 Pringgarata	282	t
4097	 Batukliang	282	t
4098	 Batukliang Utara	282	t
4099	 Keruak	283	t
4100	 Jerowaru	283	t
4101	 Sakra	283	t
4102	 Sakra Barat	283	t
4103	 Sakra Timur	283	t
4104	 Terara	283	t
4105	 Montong Gading	283	t
4106	 Sikur	283	t
4107	 Masbagik	283	t
4108	 Pringgasela	283	t
4109	 Sukamulia	283	t
4110	 Suralaga	283	t
4111	 Selong	283	t
4112	 Labuhan Haji	283	t
4113	 Pringgabaya	283	t
4114	 Suela	283	t
4115	 Aikmel	283	t
4116	 Wanasaba	283	t
4117	 Sembalun	283	t
4118	 Sambelia	283	t
4119	 Lunyuk	284	t
4120	 Orong Telu	284	t
4121	 Alas	284	t
4122	 Alas Barat	284	t
4123	 Buer	284	t
4124	 Utan	284	t
4125	 Rhee	284	t
4126	 Batulanteh	284	t
4127	 Sumbawa	284	t
4128	 Labuhan Badas	284	t
4129	 Unter Iwes	284	t
4130	 Moyohilir	284	t
4131	 Moyo Utara	284	t
4132	 Moyohulu	284	t
4133	 Ropang	284	t
4134	 Lenangguar	284	t
4135	 Lantung	284	t
4136	 Lape	284	t
4137	 Lopok	284	t
4138	 Plampang	284	t
4139	 Labangka	284	t
4140	 Maronge	284	t
4141	 Empang	284	t
4142	 Tarano	284	t
4143	 Huu	285	t
4144	 Pajo	285	t
4145	 Dompu	285	t
4146	 Woja	285	t
4147	 Kilo	285	t
4148	 Kempo	285	t
4149	 Manggalewa	285	t
4150	 Pekat	285	t
4151	 Monta	286	t
4152	 Parado	286	t
4153	 Bolo	286	t
4154	 Mada Pangga	286	t
4155	 Woha	286	t
4156	 Belo	286	t
4157	 Palibelo	286	t
4158	 Wawo	286	t
4159	 Langgudu	286	t
4160	 Lambitu	286	t
4161	 Sape	286	t
4162	 Lambu	286	t
4163	 Wera	286	t
4164	 Ambalawi	286	t
4165	 Donggo	286	t
4166	 Soromandi	286	t
4167	 Sanggar	286	t
4168	 Tambora	286	t
4169	 Sekongkang	287	t
4170	 Jereweh	287	t
4171	 Maluk	287	t
4172	 Taliwang	287	t
4173	 Brang Ene	287	t
4174	 Brang Rea	287	t
4175	 Seteluk	287	t
4176	 Poto Tano	287	t
4177	 Pemenang	288	t
4178	 Tanjung	288	t
4179	 Gangga	288	t
4180	 Kayangan	288	t
4181	 Bayan	288	t
4182	 Ampenan	289	t
4183	 Sekarbela	289	t
4184	 Mataram	289	t
4185	 Selaparang	289	t
4186	 Cakranegara	289	t
4187	 Sandubaya	289	t
4188	 Rasanae Barat	290	t
4189	 Mpunda	290	t
4190	 Rasanae Timur	290	t
4191	 Raba	290	t
4192	 Asakota	290	t
4193	 Lamboya	291	t
4194	 Wanokaka	291	t
4195	 Laboya Barat	291	t
4196	 Loli	291	t
4197	 Kota Waikabubak	291	t
4198	 Tana Righu	291	t
4199	 Lewa	292	t
4200	 Nggaha Oriangu	292	t
4201	 Lewa Tidahu	292	t
4202	 Katala Hamu Lingu	292	t
4203	 Tabundung	292	t
4204	 Pinupahar	292	t
4205	 Paberiwai	292	t
4206	 Karera	292	t
4207	 Matawai La Pawu	292	t
4208	 Kahaungu Eti	292	t
4209	 Mahu	292	t
4210	 Ngadu Ngala	292	t
4211	 Pahunga Lodu	292	t
4212	 Wula Waijelu	292	t
4213	 Rindi	292	t
4214	 Umalulu	292	t
4215	 Pandawai	292	t
4216	 Kambata Mapambuhang	292	t
4217	 Kota Waingapu	292	t
4218	 Kambera	292	t
4219	 Haharu	292	t
4220	 Kanatang	292	t
4221	 Semau	293	t
4222	 Semau Selatan	293	t
4223	 Kupang Barat	293	t
4224	 Nekamese	293	t
4225	 Kupang Tengah	293	t
4226	 Taebenu	293	t
4227	 Amarasi	293	t
4228	 Amarasi Barat	293	t
4229	 Amarasi Selatan	293	t
4230	 Amarasi Timur	293	t
4231	 Kupang Timur	293	t
4232	 Amabi Oefeto Timur	293	t
4233	 Amabi Oefeto	293	t
4234	 Sulamu	293	t
4235	 Fatuleu	293	t
4236	 Fatuleu Tengah	293	t
4237	 Fatuleu Barat	293	t
4238	 Takari	293	t
4239	 Amfoang Selatan	293	t
4240	 Amfoang Barat Daya	293	t
4241	 Amfoang Tengah	293	t
4242	 Amfoang Utara	293	t
4243	 Amfoang Barat Laut	293	t
4244	 Amfoang Timur	293	t
4245	 Mollo Utara	294	t
4246	 Fatumnasi	294	t
4247	 Tobu	294	t
4248	 Nunbena	294	t
4249	 Mollo Selatan	294	t
4250	 Polen	294	t
4251	 Mollo Barat	294	t
4252	 Mollo Tengah	294	t
4253	 Kota Soe	294	t
4254	 Amanuban Barat	294	t
4255	 Batu Putih	294	t
4256	 Kuatnana	294	t
4257	 Amanuban Selatan	294	t
4258	 Noebeba	294	t
4259	 Kuan Fatu	294	t
4260	 Kualin	294	t
4261	 Amanuban Tengah	294	t
4262	 Kolbano	294	t
4263	 Oenino	294	t
4264	 Amanuban Timur	294	t
4265	 Fautmolo	294	t
4266	 Fatukopa	294	t
4267	 Kie	294	t
4268	 Kot olin	294	t
4269	 Amanatun Selatan	294	t
4270	 Boking	294	t
4271	 Nunkolo	294	t
4272	 Noebana	294	t
4273	 Santian	294	t
4274	 Amanatun Utara	294	t
4275	 Toianas	294	t
4276	 Kokbaun	294	t
4277	 Miomaffo Barat	295	t
4278	 Miomaffo Tengah	295	t
4279	 Musi	295	t
4280	 Mutis	295	t
4281	 Miomaffo Timur	295	t
4282	 Noemuti	295	t
4283	 Bikomi Selatan	295	t
4284	 Bikomi Tengah	295	t
4285	 Bikomi Nilulat	295	t
4286	 Bikomi Utara	295	t
4287	 Naibenu	295	t
4288	 Noemuti Timur	295	t
4289	 Kota Kefamenanu	295	t
4290	 Insana	295	t
4291	 Insana Utara	295	t
4292	 Insana Barat	295	t
4293	 Insana Tengah	295	t
4294	 Insana Fafinesu	295	t
4295	 Biboki Selatan	295	t
4296	 Biboki Tanpah	295	t
4297	 Biboki Moenleu	295	t
4298	 Biboki Utara	295	t
4299	 Biboki Anleu	295	t
4300	 Biboki Feotleu	295	t
4301	 Malaka Barat	296	t
4302	 Rinhat	296	t
4303	 Wewiku	296	t
4304	 Weliman	296	t
4305	 Malaka Tengah	296	t
4306	 Sasita Mean	296	t
4307	 Botin Leobele	296	t
4308	 Io Kufeu	296	t
4309	 Malaka Timur	296	t
4310	 Laen Manen	296	t
4311	 Rai Manuk	296	t
4312	 Kobalima	296	t
4313	 Kobalima Timur	296	t
4314	 Tasifeto Barat	296	t
4315	 Kakuluk Mesak	296	t
4316	 Nanaet Dubesi	296	t
4317	 Atambua	296	t
4318	 Atambua Barat	296	t
4319	 Atambua Selatan	296	t
4320	 Tasifeto Timur	296	t
4321	 Raihat	296	t
4322	 Lasiolat	296	t
4323	 Lamaknen	296	t
4324	 Lamaknen Selatan	296	t
4325	 Pantar	297	t
4326	 Pantar Barat	297	t
4327	 Pantar Timur	297	t
4328	 Pantar Barat Laut	297	t
4329	 Pantar Tengah	297	t
4330	 Alor Barat Daya	297	t
4331	 Mataru	297	t
4332	 Alor Selatan	297	t
4333	 Alor Timur	297	t
4334	 Alor Timur Laut	297	t
4335	 Pureman	297	t
4336	 Teluk Mutiara	297	t
4337	 Kabola	297	t
4338	 Alor Barat Laut	297	t
4339	 Alor Tengah Utara	297	t
4340	 Pulau Pura	297	t
4341	 Lembur	297	t
4342	 Nagawutung	298	t
4343	 Wulandoni	298	t
4344	 Atadei	298	t
4345	 Ile Ape	298	t
4346	 Ile Ape Timur	298	t
4347	 Lebatukan	298	t
4348	 Nubatukan	298	t
4349	 Omesuri	298	t
4350	 Buyasari	298	t
4351	 Wulanggitang	299	t
4352	 Titehena	299	t
4353	 Ilebura	299	t
4354	 Tanjung Bunga	299	t
4355	 Lewo Lema	299	t
4356	 Larantuka	299	t
4357	 Ile Mandiri	299	t
4358	 Demon Pagong	299	t
4359	 Solor Barat	299	t
4360	 Solor Selatan	299	t
4361	 Solor Timur	299	t
4362	 Adonara Barat	299	t
4363	 Wotan Ulu Mado	299	t
4364	 Adonara Tengah	299	t
4365	 Adonara Timur	299	t
4366	 Ile Boleng	299	t
4367	 Witihama	299	t
4368	 Kelubagolit	299	t
4369	 Adonara	299	t
4370	 Paga	300	t
4371	 Mego	300	t
4372	 Tana Wawo	300	t
4373	 Lela	300	t
4374	 Bola	300	t
4375	 Doreng	300	t
4376	 Mapitara	300	t
4377	 Talibura	300	t
4378	 Waigete	300	t
4379	 Waiblama	300	t
4380	 Kewapante	300	t
4381	 Hewokloang	300	t
4382	 Kangae	300	t
4383	 Palue	300	t
4384	 Koting	300	t
4385	 Nelle	300	t
4386	 Nita	300	t
4387	 Magepanda	300	t
4388	 Alok	300	t
4389	 Alok Barat	300	t
4390	 Alok Timur	300	t
4391	 Nangapanda	301	t
4392	 Pulau Ende	301	t
4393	 Maukaro	301	t
4394	 Ende	301	t
4395	 Ende Selatan	301	t
4396	 Ende Timur	301	t
4397	 Ende Tengah	301	t
4398	 Ende Utara	301	t
4399	 Ndona	301	t
4400	 Ndona Timur	301	t
4401	 Wolowaru	301	t
4402	 Wolojita	301	t
4403	 Lio Timur	301	t
4404	 Kelimutu	301	t
4405	 Ndori	301	t
4406	 Maurole	301	t
4407	 Kotabaru	301	t
4408	 Detukeli	301	t
4409	 Lepembusu Kelisoke	301	t
4410	 Detusoko	301	t
4411	 Wewaria	301	t
4412	 Aimere	302	t
4413	 Jerebuu	302	t
4414	 Inerie	302	t
4415	 Bajawa	302	t
4416	 Golewa	302	t
4417	 Golewa Selatan	302	t
4418	 Golewa Barat	302	t
4419	 Bajawa Utara	302	t
4420	 Soa	302	t
4421	 Riung	302	t
4422	 Riung Barat	302	t
4423	 Wolomeze	302	t
4424	 Satar Mese	303	t
4425	 Satar Mese Barat	303	t
4426	 Langke Rembong	303	t
4427	 Ruteng	303	t
4428	 Wae Rii	303	t
4429	 Lelak	303	t
4430	 Rahong Utara	303	t
4431	 Cibal	303	t
4432	 Cibal Barat	303	t
4433	 Reok	303	t
4434	 Reok Barat	303	t
4435	 Rote Barat Daya	304	t
4436	 Rote Barat Laut	304	t
4437	 Lobalain	304	t
4438	 Rote Tengah	304	t
4439	 Rote Selatan	304	t
4440	 Pantai Baru	304	t
4441	 Rote Timur	304	t
4442	 Landu Leko	304	t
4443	 Rote Barat	304	t
4444	 Ndao Nuse	304	t
4445	 Komodo	305	t
4446	 Boleng	305	t
4447	 Sano Nggoang	305	t
4448	 Mbeliling	305	t
4449	 Lembor	305	t
4450	 Welak	305	t
4451	 Lembor Selatan	305	t
4452	 Kuwus	305	t
4453	 Ndoso	305	t
4454	 Macang Pacar	305	t
4455	 Katikutana	306	t
4456	 Katikutana Selatan	306	t
4457	 Umbu Ratu Nggay Barat	306	t
4458	 Umbu Ratu Nggay	306	t
4459	 Mamboro	306	t
4460	 Kodi Bangedo	307	t
4461	 Kodi Balaghar	307	t
4462	 Kodi	307	t
4463	 Kodi Utara	307	t
4464	 Wewewa Selatan	307	t
4465	 Wewewa Barat	307	t
4466	 Wewewa Timur	307	t
4467	 Wewewa Tengah	307	t
4468	 Wewewa Utara	307	t
4469	 Loura	307	t
4470	 Kota Tambolaka	307	t
4471	 Mauponggo	308	t
4472	 Keo Tengah	308	t
4473	 Nangaroro	308	t
4474	 Boawae	308	t
4475	 Aesesa Selatan	308	t
4476	 Aesesa	308	t
4477	 Wolowae	308	t
4478	 Borong	309	t
4479	 Rana Mese	309	t
4480	 Kota Komba	309	t
4481	 Elar	309	t
4482	 Elar Selatan	309	t
4483	 Sambi Rampas	309	t
4484	 Poco Ranaka	309	t
4485	 Poco Ranaka Timur	309	t
4486	 Lamba Leda	309	t
4487	 Raijua	310	t
4488	 Hawu Mehara	310	t
4489	 Sabu Liae	310	t
4490	 Sabu Barat	310	t
4491	 Sabu Tengah	310	t
4492	 Sabu Timur	310	t
4493	 Alak	311	t
4494	 Maulafa	311	t
4495	 Oebobo	311	t
4496	 Kota Raja	311	t
4497	 Kelapa Lima	311	t
4498	 Kota Lama	311	t
4499	 Selakau	312	t
4500	 Selakau Timur	312	t
4501	 Pemangkat	312	t
4502	 Semparuk	312	t
4503	 Salatiga	312	t
4504	 Tebas	312	t
4505	 Tekarang	312	t
4506	 Sambas	312	t
4507	 Subah	312	t
4508	 Sebawi	312	t
4509	 Sajad	312	t
4510	 Jawai	312	t
4511	 Jawai Selatan	312	t
4512	 Teluk Keramat	312	t
4513	 Galing	312	t
4514	 Tangaran	312	t
4515	 Sejangkung	312	t
4516	 Sajingan Besar	312	t
4517	 Paloh	312	t
4518	 Sungai Raya	313	t
4519	 Capkala	313	t
4520	 Sungai Raya Kepulauan	313	t
4521	 Samalantan	313	t
4522	 Monterado	313	t
4523	 Lembah Bawang	313	t
4524	 Bengkayang	313	t
4525	 Teriak	313	t
4526	 Sungai Betung	313	t
4527	 Ledo	313	t
4528	 Suti Semarang	313	t
4529	 Lumar	313	t
4530	 Sanggau Ledo	313	t
4531	 Tujuhbelas	313	t
4532	 Seluas	313	t
4533	 Jagoi Babang	313	t
4534	 Siding	313	t
4535	 Sebangki	314	t
4536	 Ngabang	314	t
4537	 Jelimpo	314	t
4538	 Sengah Temila	314	t
4539	 Mandor	314	t
4540	 Menjalin	314	t
4541	 Mempawah Hulu	314	t
4542	 Sompak	314	t
4543	 Menyuke	314	t
4544	 Banyuke Hulu	314	t
4545	 Meranti	314	t
4546	 Kuala Behe	314	t
4547	 Air Besar	314	t
4548	 Siantan	315	t
4549	 Segedong	315	t
4550	 Sungai Pinyuh	315	t
4551	 Anjongan	315	t
4552	 Mempawah Hilir	315	t
4553	 Mempawah Timur	315	t
4554	 Sungai Kunyit	315	t
4555	 Toho	315	t
4556	 Sadaniang	315	t
4557	 Toba	316	t
4558	 Meliau	316	t
4559	 Kapuas	316	t
4560	 Mukok	316	t
4561	 Jangkang	316	t
4562	 Bonti	316	t
4563	 Parindu	316	t
4564	 Tayan Hilir	316	t
4565	 Balai	316	t
4566	 Tayan Hulu	316	t
4567	 Kembayan	316	t
4568	 Beduwai	316	t
4569	 Noyan	316	t
4570	 Sekayam	316	t
4571	 Entikong	316	t
4572	 Kendawangan	317	t
4573	 Manis Mata	317	t
4574	 Marau	317	t
4575	 Singkup	317	t
4576	 Air Upas	317	t
4577	 Jelai Hulu	317	t
4578	 Tumbang Titi	317	t
4579	 Pemahan	317	t
4580	 Sungai Melayu Rayak	317	t
4581	 Matan Hilir Selatan	317	t
4582	 Benua Kayong	317	t
4583	 Matan Hilir Utara	317	t
4584	 Delta Pawan	317	t
4585	 Muara Pawan	317	t
4586	 Nanga Tayap	317	t
4587	 Sandai	317	t
4588	 Hulu Sungai	317	t
4589	 Sungai Laur	317	t
4590	 Simpang Hulu	317	t
4591	 Simpang Dua	317	t
4592	 Serawai	318	t
4593	 Ambalau	318	t
4594	 Kayan Hulu	318	t
4595	 Sepauk	318	t
4596	 Tempunak	318	t
4597	 Sungai Tebelian	318	t
4598	 Sintang	318	t
4599	 Dedai	318	t
4600	 Kayan Hilir	318	t
4601	 Kelam Permai	318	t
4602	 Binjai Hulu	318	t
4603	 Ketungau Hilir	318	t
4604	 Ketungau Tengah	318	t
4605	 Ketungau Hulu	318	t
4606	 Silat Hilir	319	t
4607	 Silat Hulu	319	t
4608	 Hulu Gurung	319	t
4609	 Bunut Hulu	319	t
4610	 Mentebah	319	t
4611	 Bika	319	t
4612	 Kalis	319	t
4613	 Putussibau Selatan	319	t
4614	 Hulu Kapuas	319	t
4615	 Embaloh Hilir	319	t
4616	 Bunut Hilir	319	t
4617	 Boyan Tanjung	319	t
4618	 Pengkadan	319	t
4619	 Jongkong	319	t
4620	 Selimbau	319	t
4621	 Danau Sentarum	319	t
4622	 Suhaid	319	t
4623	 Seberuang	319	t
4624	 Semitau	319	t
4625	 Empanang	319	t
4626	 Puring Kencana	319	t
4627	 Badau	319	t
4628	 Batang Lupar	319	t
4629	 Embaloh Hulu	319	t
4630	 Putussibau Utara	319	t
4631	 Nanga Mahap	320	t
4632	 Nanga Taman	320	t
4633	 Sekadau Hulu	320	t
4634	 Sekadau Hilir	320	t
4635	 Belitang Hilir	320	t
4636	 Belitang	320	t
4637	 Belitang Hulu	320	t
4638	 Sokan	321	t
4639	 Tanah Pinoh	321	t
4640	 Tanah Pinoh Barat	321	t
4641	 Sayan	321	t
4642	 Belimbing	321	t
4643	 Belimbing Hulu	321	t
4644	 Nanga Pinoh	321	t
4645	 Pinoh Selatan	321	t
4646	 Pinoh Utara	321	t
4647	 Ella Hilir	321	t
4648	 Menukung	321	t
4649	 Pulau Maya	322	t
4650	 Kepulauan Karimata	322	t
4651	 Sukadana	322	t
4652	 Simpang Hilir	322	t
4653	 Teluk Batang	322	t
4654	 Seponti	322	t
4655	 Batu Ampar	323	t
4656	 Terentang	323	t
4657	 Kubu	323	t
4658	 Telok Pa kedai	323	t
4659	 Sungai Kakap	323	t
4660	 Rasau Jaya	323	t
4661	 Sungai Raya	323	t
4662	 Sungai Ambawang	323	t
4663	 Kuala Mandor-b	323	t
4664	 Pontianak Selatan	324	t
4665	 Pontianak Tenggara	324	t
4666	 Pontianak Timur	324	t
4667	 Pontianak Barat	324	t
4668	 Pontianak Kota	324	t
4669	 Pontianak Utara	324	t
4670	 Singkawang Selatan	325	t
4671	 Singkawang Timur	325	t
4672	 Singkawang Utara	325	t
4673	 Singkawang Barat	325	t
4674	 Singkawang Tengah	325	t
4675	 Kotawaringin Lama	326	t
4676	 Arut Selatan	326	t
4677	 Kumai	326	t
4678	 Pangkalan Banteng	326	t
4679	 Pangkalan Lada	326	t
4680	 Arut Utara	326	t
4681	 Mentaya Hilir Selatan	327	t
4682	 Teluk Sampit	327	t
4683	 Pulau Hanaut	327	t
4684	 Mentawa Baru/ketapang	327	t
4685	 Seranau	327	t
4686	 Mentaya Hilir Utara	327	t
4687	 Kota Besi	327	t
4688	 Telawang	327	t
4689	 Baamang	327	t
4690	 Cempaga	327	t
4691	 Cempaga Hulu	327	t
4692	 Parenggean	327	t
4693	 Tualan Hulu	327	t
4694	 Mentaya Hulu	327	t
4695	 Bukit Santuai	327	t
4696	 Antang Kalang	327	t
4697	 Telaga Antang	327	t
4698	 Kapuas Kuala	328	t
4699	 Tamban Catur	328	t
4700	 Kapuas Timur	328	t
4701	 Selat	328	t
4702	 Bataguh	328	t
4703	 Basarang	328	t
4704	 Kapuas Hilir	328	t
4705	 Pulau Petak	328	t
4706	 Kapuas Murung	328	t
4707	 Dadahup	328	t
4708	 Kapuas Barat	328	t
4709	 Mantangai	328	t
4710	 Timpah	328	t
4711	 Kapuas Tengah	328	t
4712	 Pasak Talawang	328	t
4713	 Kapuas Hulu	328	t
4714	 Mandau Talawang	328	t
4715	 Jenamas	329	t
4716	 Dusun Hilir	329	t
4717	 Karau Kuala	329	t
4718	 Dusun Selatan	329	t
4719	 Dusun Utara	329	t
4720	 Gunung Bintang Awai	329	t
4721	 Montalat	330	t
4722	 Gunung Timang	330	t
4723	 Gunung Purei	330	t
4724	 Teweh Timur	330	t
4725	 Teweh Tengah	330	t
4726	 Teweh  Baru	330	t
4727	 Teweh Selatan	330	t
4728	 Lahei	330	t
4729	 Lahei Barat	330	t
4730	 Jelai	331	t
4731	 Pantai Lunci	331	t
4732	 Sukamara	331	t
4733	 Balai Riam	331	t
4734	 Permata Kecubung	331	t
4735	 Bulik	332	t
4736	 Sematu Jaya	332	t
4737	 Menthobi Raya	332	t
4738	 Bulik Timur	332	t
4739	 Lamandau	332	t
4740	 Belantikan Raya	332	t
4741	 Delang	332	t
4742	 Batangkawa	332	t
4743	 Seruyan Hilir	333	t
4744	 Seruyan Hilir Timur	333	t
4745	 Danau Sembuluh	333	t
4746	 Seruyan Raya	333	t
4747	 Hanau	333	t
4748	 Danau Seluluk	333	t
4749	 Seruyan Tengah	333	t
4750	 Batu Ampar	333	t
4751	 Seruyan Hulu	333	t
4752	 Suling Tambun	333	t
4753	 Katingan Kuala	334	t
4754	 Mendawai	334	t
4755	 Kamipang	334	t
4756	 Tasik Payawan	334	t
4757	 Katingan Hilir	334	t
4758	 Tewang Sangalang Garing	334	t
4759	 Pulau Malan	334	t
4760	 Katingan Tengah	334	t
4761	 Sanaman Mantikei	334	t
4762	 Petak Malai	334	t
4763	 Marikit	334	t
4764	 Katingan Hulu	334	t
4765	 Bukit Raya	334	t
4766	 Kahayan Kuala	335	t
4767	 Sebangau Kuala	335	t
4768	 Pandih Batu	335	t
4769	 Maliku	335	t
4770	 Kahayan Hilir	335	t
4771	 Jabiren Raya	335	t
4772	 Kahayan Tengah	335	t
4773	 Banama Tingang	335	t
4774	 Manuhing	336	t
4775	 Manuhing Raya	336	t
4776	 Rungan	336	t
4777	 Rungan Hulu	336	t
4778	 Rungan Barat	336	t
4779	 Sepang	336	t
4780	 Mihing Raya	336	t
4781	 Kurun	336	t
4782	 Tewah	336	t
4783	 Kahayan Hulu Utara	336	t
4784	 Damang Batu	336	t
4785	 Miri Manasa	336	t
4786	 Benua Lima	337	t
4787	 Dusun Timur	337	t
4788	 Paju Epat	337	t
4789	 Awang	337	t
4790	 Patangkep Tutui	337	t
4791	 Dusun Tengah	337	t
4792	 Raren Batuah	337	t
4793	 Paku	337	t
4794	 Karusen Janang	337	t
4795	 Pematang Karau	337	t
4796	 Permata Intan	338	t
4797	 Sungai Babuat	338	t
4798	 Murung	338	t
4799	 Laung Tuhup	338	t
4800	 Barito Tuhup Raya	338	t
4801	 Tanah Siang	338	t
4802	 Tanah Siang Selatan	338	t
4803	 Sumber Barito	338	t
4804	 Seribu Riam	338	t
4805	 Uut Murung	338	t
4806	 Pahandut	339	t
4807	 Sebangau	339	t
4808	 Jekan Raya	339	t
4809	 Bukit Batu	339	t
4810	 Rakumpit	339	t
4811	 Panyipatan	340	t
4812	 Takisung	340	t
4813	 Kurau	340	t
4814	 Bumi Makmur	340	t
4815	 Bati - Bati	340	t
4816	 Tambang Ulang	340	t
4817	 Pelaihari	340	t
4818	 Bajuin	340	t
4819	 Batu Ampar	340	t
4820	 Jorong	340	t
4821	 Kintap	340	t
4822	 Pulau Sembilan	341	t
4823	 Pulau Laut Barat	341	t
4824	 Pulau Laut Tanjung Selayar	341	t
4825	 Pulau Laut Selatan	341	t
4826	 Pulau Laut Kepulauan	341	t
4827	 Pulau Laut Timur	341	t
4828	 Pulau Sebuku	341	t
4829	 Pulau Laut Utara	341	t
4830	 Pulau Laut Tengah	341	t
4831	 Kelumpang Selatan	341	t
4832	 Kelumpang Hilir	341	t
4833	 Kelumpang Hulu	341	t
4834	 Hampang	341	t
4835	 Sungai Durian	341	t
4836	 Kelumpang Tengah	341	t
4837	 Kelumpang Barat	341	t
4838	 Kelumpang Utara	341	t
4839	 Pamukan Selatan	341	t
4840	 Sampanahan	341	t
4841	 Pamukan Utara	341	t
4842	 Pamukan Barat	341	t
4843	 Aluh - Aluh	342	t
4844	 Beruntung Baru	342	t
4845	 Gambut	342	t
4846	 Kertak Hanyar	342	t
4847	 Tatah Makmur	342	t
4848	 Sungai Tabuk	342	t
4849	 Martapura	342	t
4850	 Martapura Timur	342	t
4851	 Martapura Barat	342	t
4852	 Astambul	342	t
4853	 Karang Intan	342	t
4854	 Aranio	342	t
4855	 Sungai Pinang	342	t
4856	 Paramasan	342	t
4857	 Pengaron	342	t
4858	 Sambung Makmur	342	t
4859	 Mataraman	342	t
4860	 Simpang Empat	342	t
4861	 Telaga Bauntung	342	t
4862	 Tabunganen	343	t
4863	 Tamban	343	t
4864	 Mekar Sari	343	t
4865	 Anjir Pasar	343	t
4866	 Anjir Muara	343	t
4867	 Alalak	343	t
4868	 Mandastana	343	t
4869	 Jejangkit	343	t
4870	 Belawang	343	t
4871	 Wanaraya	343	t
4872	 Barambai	343	t
4873	 Rantau Badauh	343	t
4874	 Cerbon	343	t
4875	 Bakumpai	343	t
4876	 Marabahan	343	t
4877	 Tabukan	343	t
4878	 Kuripan	343	t
4879	 Binuang	344	t
4880	 Hatungun	344	t
4881	 Tapin Selatan	344	t
4882	 Salam Babaris	344	t
4883	 Tapin Tengah	344	t
4884	 Bungur	344	t
4885	 Piani	344	t
4886	 Lokpaikat	344	t
4887	 Tapin Utara	344	t
4888	 Bakarangan	344	t
4889	 Candi Laras Selatan	344	t
4890	 Candi Laras Utara	344	t
4891	 Padang Batung	345	t
4892	 Loksado	345	t
4893	 Telaga Langsat	345	t
4894	 Angkinang	345	t
4895	 Kandangan	345	t
4896	 Sungai Raya	345	t
4897	 Simpur	345	t
4898	 Kalumpang	345	t
4899	 Daha Selatan	345	t
4900	 Daha Barat	345	t
4901	 Daha Utara	345	t
4902	 Haruyan	346	t
4903	 Batu Benawa	346	t
4904	 Hantakan	346	t
4905	 Batang Alai Selatan	346	t
4906	 Batang Alai Timur	346	t
4907	 Barabai	346	t
4908	 Labuan Amas Selatan	346	t
4909	 Labuan Amas Utara	346	t
4910	 Pandawan	346	t
4911	 Batang Alai Utara	346	t
4912	 Limpasu	346	t
4913	 Danau Panggang	347	t
4914	 Paminggir	347	t
4915	 Babirik	347	t
4916	 Sungai Pandan	347	t
4917	 Sungai Tabukan	347	t
4918	 Amuntai Selatan	347	t
4919	 Amuntai Tengah	347	t
4920	 Banjang	347	t
4921	 Amuntai Utara	347	t
4922	 Haur Gading	347	t
4923	 Banua Lawas	348	t
4924	 Pugaan	348	t
4925	 Kelua	348	t
4926	 Muara Harus	348	t
4927	 Tanta	348	t
4928	 Tanjung	348	t
4929	 Murung Pudak	348	t
4930	 Haruai	348	t
4931	 Bintang Ara	348	t
4932	 Upau	348	t
4933	 Muara Uya	348	t
4934	 Jaro	348	t
4935	 Kusan Hilir	349	t
4936	 Sungai Loban	349	t
4937	 Satui	349	t
4938	 Angsana	349	t
4939	 Kusan Hulu	349	t
4940	 Kuranji	349	t
4941	 Batu Licin	349	t
4942	 Karang Bintang	349	t
4943	 Simpang Empat	349	t
4944	 Mantewe	349	t
4945	 Lampihong	350	t
4946	 Batu Mandi	350	t
4947	 Awayan	350	t
4948	 Tebing Tinggi	350	t
4949	 Paringin	350	t
4950	 Paringin Selatan	350	t
4951	 Juai	350	t
4952	 Halong	350	t
4953	 Banjarmasin Selatan	351	t
4954	 Banjarmasin Timur	351	t
4955	 Banjarmasin Barat	351	t
4956	 Banjarmasin Tengah	351	t
4957	 Banjarmasin Utara	351	t
4958	 Landasan Ulin	352	t
4959	 Liang Anggang	352	t
4960	 Cempaka	352	t
4961	 Banjar Baru Utara	352	t
4962	 Banjar Baru Selatan	352	t
4963	 Batu Sopang	353	t
4964	 Muara Samu	353	t
4965	 Batu Engau	353	t
4966	 Tanjung Harapan	353	t
4967	 Pasir Belengkong	353	t
4968	 Tanah Grogot	353	t
4969	 Kuaro	353	t
4970	 Long Ikis	353	t
4971	 Muara Komam	353	t
4972	 Long Kali	353	t
4973	 Bongan	354	t
4974	 Jempang	354	t
4975	 Penyinggahan	354	t
4976	 Muara Pahu	354	t
4977	 Siluq Ngurai	354	t
4978	 Muara Lawa	354	t
4979	 Bentian Besar	354	t
4980	 Damai	354	t
4981	 Nyuatan	354	t
4982	 Barong Tongkok	354	t
4983	 Linggang Bigung	354	t
4984	 Melak	354	t
4985	 Sekolaq Darat	354	t
4986	 Manor Bulatn	354	t
4987	 Long Iram	354	t
4988	 Tering	354	t
4989	 Long Hubung	354	t
4990	 Laham	354	t
4991	 Long Bagun	354	t
4992	 Long Pahangai	354	t
4993	 Long Apari	354	t
4994	 Semboja	355	t
4995	 Muara Jawa	355	t
4996	 Sanga-sanga	355	t
4997	 Loa Janan	355	t
4998	 Loa Kulu	355	t
4999	 Muara Muntai	355	t
5000	 Muara Wis	355	t
5001	 Kotabangun	355	t
5002	 Tenggarong	355	t
5003	 Sebulu	355	t
5004	 Tenggarong Seberang	355	t
5005	 Anggana	355	t
5006	 Muara Badak	355	t
5007	 Marang Kayu	355	t
5008	 Muara Kaman	355	t
5009	 Kenohan	355	t
5010	 Kembang Janggut	355	t
5011	 Tabang	355	t
5012	 Muara Ancalong	356	t
5013	 Busang	356	t
5014	 Long Mesangat	356	t
5015	 Muara Wahau	356	t
5016	 Telen	356	t
5017	 Kongbeng	356	t
5018	 Muara Bengkal	356	t
5019	 Batu Ampar	356	t
5020	 Sangatta Utara	356	t
5021	 Bengalon	356	t
5022	 Teluk Pandan	356	t
5023	 Sangatta Selatan	356	t
5024	 Rantau Pulung	356	t
5025	 Sangkulirang	356	t
5026	 Kaliorang	356	t
5027	 Sandaran	356	t
5028	 Kaubun	356	t
5029	 Karangan	356	t
5030	 Kelay	357	t
5031	 Talisayan	357	t
5032	 Tabalar	357	t
5033	 Biduk Biduk	357	t
5034	 Pulau Derawan	357	t
5035	 Maratua	357	t
5036	 Sambaliung	357	t
5037	 Tanjung Redeb	357	t
5038	 Gunung Tabur	357	t
5039	 Segah	357	t
5040	 Teluk Bayur	357	t
5041	 Batu Putih	357	t
5042	 Biatan	357	t
5043	 Babulu	358	t
5044	 Waru	358	t
5045	 Penajam	358	t
5046	 Sepaku	358	t
5047	 Balikpapan Selatan	359	t
5048	 Balikpapan Timur	359	t
5049	 Balikpapan Utara	359	t
5050	 Balikpapan Tengah	359	t
5051	 Balikpapan Barat	359	t
5052	 Palaran	360	t
5053	 Samarinda Ilir	360	t
5054	 Samarinda Kota	360	t
5055	 Sambutan	360	t
5056	 Samarinda Seberang	360	t
5057	 Loa Janan Ilir	360	t
5058	 Sungai Kunjang	360	t
5059	 Samarinda Ulu	360	t
5060	 Samarinda Utara	360	t
5061	 Sungai Pinang	360	t
5062	 Bontang Selatan	361	t
5063	 Bontang Utara	361	t
5064	 Bontang Barat	361	t
5065	 Kayan Hulu	362	t
5066	 Sungai Boh	362	t
5067	 Kayan Selatan	362	t
5068	 Kayan Hilir	362	t
5069	 Pujungan	362	t
5070	 Bahau Hulu	362	t
5071	 Malinau Kota	362	t
5072	 Malinau Selatan	362	t
5073	 Malinau Barat	362	t
5074	 Malinau Utara	362	t
5075	 Malinau Selatan Hilir	362	t
5076	 Malinau Selatan Hulu	362	t
5077	 Mentarang	362	t
5078	 Mentarang Hulu	362	t
5079	 Sungai Tubu	362	t
5080	 Peso	363	t
5081	 Peso Hilir	363	t
5082	 Tanjung Palas Barat	363	t
5083	 Tanjung Palas	363	t
5084	 Tanjung Selor	363	t
5085	 Tanjung Palas Timur	363	t
5086	 Tanjung Palas Tengah	363	t
5087	 Tanjung Palas Utara	363	t
5088	 Sekatak	363	t
5089	 Bunyu	363	t
5090	 Sesayap	364	t
5091	 Sesayap Hilir	364	t
5092	 Tana Lia	364	t
5093	 Krayan	365	t
5094	 Krayan Selatan	365	t
5095	 Lumbis	365	t
5096	 Lumbis Ogong	365	t
5097	 Sembakung	365	t
5098	 Sembakung Atulai	365	t
5099	 Nunukan	365	t
5100	 Sebuku	365	t
5101	 Nunukan Selatan	365	t
5102	 Sei Menggaris	365	t
5103	 Tulin Onsoi	365	t
5104	 Sebatik	365	t
5105	 Sebatik Barat	365	t
5106	 Sebatik Tengah	365	t
5107	 Sebatik Utara	365	t
5108	 Sebatik Timur	365	t
5109	 Tarakan Timur	366	t
5110	 Tarakan Tengah	366	t
5111	 Tarakan Barat	366	t
5112	 Tarakan Utara	366	t
5113	 Dumoga Barat	367	t
5114	 Dumoga Utara	367	t
5115	 Dumoga Timur	367	t
5116	 Lolayan	367	t
5117	 Passi Barat	367	t
5118	 Passi Timur	367	t
5119	 Bilalang	367	t
5120	 Poigar	367	t
5121	 Bolaang	367	t
5122	 Bolaang Timur	367	t
5123	 Lolak	367	t
5124	 Sangtombolang	367	t
5125	 Langowan Timur	368	t
5126	 Langowan Barat	368	t
5127	 Langowan Selatan	368	t
5128	 Langowan Utara	368	t
5129	 Tompaso	368	t
5130	 Tompaso Barat	368	t
5131	 Kawangkoan	368	t
5132	 Kawangkoan Barat	368	t
5133	 Kawangkoan Utara	368	t
5134	 Sonder	368	t
5135	 Tombariri	368	t
5136	 Tombariri Timur	368	t
5137	 Pineleng	368	t
5138	 Tombulu	368	t
5139	 Mandolang	368	t
5140	 Tondano Barat	368	t
5141	 Tondano Selatan	368	t
5142	 Remboken	368	t
5143	 Kakas	368	t
5144	 Kakas Barat	368	t
5145	 Lembean Timur	368	t
5146	 Eris	368	t
5147	 Kombi	368	t
5148	 Tondano Timur	368	t
5149	 Tondano Utara	368	t
5150	 Manganitu Selatan	369	t
5151	 Tatoareng	369	t
5152	 Tamako	369	t
5153	 Tabukan Selatan	369	t
5154	 Tabukan Selatan Tengah	369	t
5155	 Tabukan Selatan Tenggara	369	t
5156	 Tabukan Tengah	369	t
5157	 Manganitu	369	t
5158	 Tahuna	369	t
5159	 Tahuna Timur	369	t
5160	 Tahuna Barat	369	t
5161	 Tabukan Utara	369	t
5162	 Nusa Tabukan	369	t
5163	 Kepulauan Marore	369	t
5164	 Kendahe	369	t
5165	 Kabaruan	370	t
5166	 Damau	370	t
5167	 Lirung	370	t
5168	 Salibabu	370	t
5169	 Kalongan	370	t
5170	 Moronge	370	t
5171	 Melonguane	370	t
5172	 Melonguane Timur	370	t
5173	 Beo	370	t
5174	 Beo Utara	370	t
5175	 Beo Selatan	370	t
5176	 Rainis	370	t
5177	 Tampa Na mma	370	t
5178	 Pulutan	370	t
5179	 Essang	370	t
5180	 Essang Selatan	370	t
5181	 Gemeh	370	t
5182	 Nanusa	370	t
5183	 Miangas	370	t
5184	 Modoinding	371	t
5185	 Tompaso Baru	371	t
5186	 Maesaan	371	t
5187	 Ranoyapo	371	t
5188	 Motoling	371	t
5189	 Kumelembuai	371	t
5190	 Motoling Barat	371	t
5191	 Motoling Timur	371	t
5192	 Sinonsayang	371	t
5193	 Tenga	371	t
5194	 Amurang	371	t
5195	 Amurang Barat	371	t
5196	 Amurang Timur	371	t
5197	 Tareran	371	t
5198	 Sulta	371	t
5199	 Tumpaan	371	t
5200	 Tatapaan	371	t
5201	 Kema	372	t
5202	 Kauditan	372	t
5203	 Airmadidi	372	t
5204	 Kalawat	372	t
5205	 Dimembe	372	t
5206	 Talawaan	372	t
5207	 Wori	372	t
5208	 Likupang Barat	372	t
5209	 Likupang Timur	372	t
5210	 Likupang Selatan	372	t
5211	 Sangkub	373	t
5212	 Bintauna	373	t
5213	 Bolang Itang Timur	373	t
5214	 Bolang Itang Barat	373	t
5215	 Kaidipang	373	t
5216	 Pinogaluman	373	t
5217	 Biaro	374	t
5218	 Tagulandang Selatan	374	t
5219	 Tagulandang	374	t
5220	 Tagulandang Utara	374	t
5221	 Siau Barat Selatan	374	t
5222	 Siau Timur Selatan	374	t
5223	 Siau Barat	374	t
5224	 Siau Tengah	374	t
5225	 Siau Timur	374	t
5226	 Siau Barat Utara	374	t
5227	 Ratatotok	375	t
5228	 Pusomaen	375	t
5229	 Belang	375	t
5230	 Ratahan	375	t
5231	 Pasan	375	t
5232	 Ratahan Timur	375	t
5233	 Tombatu	375	t
5234	 Tombatu Timur	375	t
5235	 Tombatu Utara	375	t
5236	 Touluaan	375	t
5237	 Touluaan Selatan	375	t
5238	 Silian Raya	375	t
5239	 Posigadan	376	t
5240	 Bolang Uki	376	t
5241	 Pinolosian	376	t
5242	 Pinolosian Tengah	376	t
5243	 Pinolosian Timur	376	t
5244	 Nuangan	377	t
5245	 Tutuyan	377	t
5246	 Kotabunan	377	t
5247	 Modayag	377	t
5248	 Modayag Barat	377	t
5249	 Malalayang	378	t
5250	 Sario	378	t
5251	 Wanea	378	t
5252	 Wenang	378	t
5253	 Tikala	378	t
5254	 Paal Dua	378	t
5255	 Mapanget	378	t
5256	 Singkil	378	t
5257	 Tuminting	378	t
5258	 Bunaken	378	t
5259	 Bunaken Kepulauan	378	t
5260	 Madidir	379	t
5261	 Matuari	379	t
5262	 Girian	379	t
5263	 Lembeh Selatan	379	t
5264	 Lembeh Utara	379	t
5265	 Aertembaga	379	t
5266	 Maesa	379	t
5267	 Ranowulu	379	t
5268	 Tomohon Selatan	380	t
5269	 Tomohon Tengah	380	t
5270	 Tomohon Timur	380	t
5271	 Tomohon Barat	380	t
5272	 Tomohon Utara	380	t
5273	 Kotamobagu Selatan	381	t
5274	 Kotamobagu Timur	381	t
5275	 Kotamobagu Barat	381	t
5276	 Kotamobagu Utara	381	t
5277	 Labobo	382	t
5278	 Bokan Kepulauan	382	t
5279	 Bangkurung	382	t
5280	 Banggai	382	t
5281	 Banggai Utara	382	t
5282	 Banggai Tengah	382	t
5283	 Banggai Selatan	382	t
5284	 Totikum	382	t
5285	 Totikum Selatan	382	t
5286	 Tinangkung	382	t
5287	 Tinangkung Selatan	382	t
5288	 Tinangkung Utara	382	t
5289	 Liang	382	t
5290	 Peling Tengah	382	t
5291	 Bulagi	382	t
5292	 Bulagi Selatan	382	t
5293	 Bulagi Utara	382	t
5294	 Buko	382	t
5295	 Buko Selatan	382	t
5296	 Toili	383	t
5297	 Toili Barat	383	t
5298	 Moilong	383	t
5299	 Batui	383	t
5300	 Batui Selatan	383	t
5301	 Bunta	383	t
5302	 Nuhon	383	t
5303	 Simpang Raya	383	t
5304	 Kintom	383	t
5305	 Luwuk	383	t
5306	 Luwuk Timur	383	t
5307	 Luwuk Utara	383	t
5308	 Luwuk Selatan	383	t
5309	 Nambo	383	t
5310	 Pagimana	383	t
5311	 Bualemo	383	t
5312	 Lobu	383	t
5313	 Lamala	383	t
5314	 Masama	383	t
5315	 Mantoh	383	t
5316	 Balantak	383	t
5317	 Balantak Selatan	383	t
5318	 Balantak Utara	383	t
5319	 Menui Kepulauan	384	t
5320	 Bungku Selatan	384	t
5321	 Bahodopi	384	t
5322	 Bungku Pesisir	384	t
5323	 Bungku Tengah	384	t
5324	 Bungku Timur	384	t
5325	 Bungku Barat	384	t
5326	 Bumi Raya	384	t
5327	 Wita Ponda	384	t
5328	 Lembo	384	t
5329	 Lembo Raya	384	t
5330	 Mori Atas	384	t
5331	 Mori Utara	384	t
5332	 Petasia	384	t
5333	 Soyo Jaya	384	t
5334	 Petasia Timur	384	t
5335	 Bungku Utara	384	t
5336	 Mamosalato	384	t
5337	 Pamona Selatan	385	t
5338	 Pamona Barat	385	t
5339	 Pamona Tenggara	385	t
5340	 Lore Selatan	385	t
5341	 Lore Barat	385	t
5342	 Pamona Pusalemba	385	t
5343	 Pamona Timur	385	t
5344	 Pamona Utara	385	t
5345	 Lore Utara	385	t
5346	 Lore Tengah	385	t
5347	 Lore Timur	385	t
5348	 Lore Peore	385	t
5349	 Poso Pesisir	385	t
5350	 Poso Pesisir Selatan	385	t
5351	 Poso Pesisir Utara	385	t
5352	 Lage	385	t
5353	 Poso Kota	385	t
5354	 Poso Kota Utara	385	t
5355	 Poso Kota Selatan	385	t
5356	 Rio Pakava	386	t
5357	 Pinembani	386	t
5358	 Banawa	386	t
5359	 Banawa Selatan	386	t
5360	 Banawa Tengah	386	t
5361	 Labuan	386	t
5362	 Tanantovea	386	t
5363	 Sindue	386	t
5364	 Sindue Tombusabora	386	t
5365	 Sindue Tobata	386	t
5366	 Sirenja	386	t
5367	 Balaesang	386	t
5368	 Balaesang Tanjung	386	t
5369	 Damsol	386	t
5370	 Sojol	386	t
5371	 Sojol Utara	386	t
5372	 Dampal Selatan	387	t
5373	 Dampal Utara	387	t
5374	 Dondo	387	t
5375	 Ogodeide	387	t
5376	 Basidondo	387	t
5377	 Baolan	387	t
5378	 Lampasio	387	t
5379	 Galang	387	t
5380	 Tolitoli Utara	387	t
5381	 Dako Pamean	387	t
5382	 Lakea	388	t
5383	 Biau	388	t
5384	 Karamat	388	t
5385	 Momunu	388	t
5386	 Tiloan	388	t
5387	 Bokat	388	t
5388	 Bukal	388	t
5389	 Bunobogu	388	t
5390	 Gadung	388	t
5391	 Paleleh	388	t
5392	 Paleleh Barat	388	t
5393	 Sausu	389	t
5394	 Torue	389	t
5395	 Balinggi	389	t
5396	 Parigi	389	t
5397	 Parigi Selatan	389	t
5398	 Parigi Barat	389	t
5399	 Parigi Utara	389	t
5400	 Parigi Tengah	389	t
5401	 Ampibabo	389	t
5402	 Kasimbar	389	t
5403	 Toribulu	389	t
5404	 Siniu	389	t
5405	 Tinombo	389	t
5406	 Tinombo Selatan	389	t
5407	 Tomini	389	t
5408	 Mepanga	389	t
5409	 Palasa	389	t
5410	 Moutong	389	t
5411	 Bolano Lambunu	389	t
5412	 Taopa	389	t
5413	 Bolano	389	t
5414	 Ongka Malino	389	t
5415	 Tojo Barat	390	t
5416	 Tojo	390	t
5417	 Ulubongka	390	t
5418	 Ampana Tete	390	t
5419	 Ampana Kota	390	t
5420	 Una - Una	390	t
5421	 Togean	390	t
5422	 Walea Kepulauan	390	t
5423	 Walea Besar	390	t
5424	 Pipikoro	391	t
5425	 Kulawi Selatan	391	t
5426	 Kulawi	391	t
5427	 Lindu	391	t
5428	 Nokilalaki	391	t
5429	 Palolo	391	t
5430	 Gumbasa	391	t
5431	 Dolo Selatan	391	t
5432	 Dolo Barat	391	t
5433	 Tanambulava	391	t
5434	 Dolo	391	t
5435	 Sigi Biromaru	391	t
5436	 Marawola	391	t
5437	 Marawola Barat	391	t
5438	 Kinovaro	391	t
5439	 Palu Barat	392	t
5440	 Tatanga	392	t
5441	 Ulujadi	392	t
5442	 Palu Selatan	392	t
5443	 Palu Timur	392	t
5444	 Mantikulore	392	t
5445	 Palu Utara	392	t
5446	 Tawaeli	392	t
5447	 Pasimarannu	393	t
5448	 Pasilambena	393	t
5449	 Pasimassunggu	393	t
5450	 Takabonerate	393	t
5451	 Pasimassunggu Timur	393	t
5452	 Bontosikuyu	393	t
5453	 Bontoharu	393	t
5454	 Benteng	393	t
5455	 Bontomanai	393	t
5456	 Bontomatene	393	t
5457	 Buki	393	t
5458	 Gantarang	394	t
5459	 Ujung Bulu	394	t
5460	 Ujung Loe	394	t
5461	 Bonto Bahari	394	t
5462	 Bontotiro	394	t
5463	 Hero Lange-lange	394	t
5464	 Kajang	394	t
5465	 Bulukumpa	394	t
5466	 Rilau Ale	394	t
5467	 Kindang	394	t
5468	 Bissappu	395	t
5469	 Uluere	395	t
5470	 Sinoa	395	t
5471	 Bantaeng	395	t
5472	 Eremerasa	395	t
5473	 Tompobulu	395	t
5474	 Pa jukukang	395	t
5475	 Gantarangkeke	395	t
5476	 Bangkala	396	t
5477	 Bangkala Barat	396	t
5478	 Tamalatea	396	t
5479	 Bontoramba	396	t
5480	 Binamu	396	t
5481	 Turatea	396	t
5482	 Batang	396	t
5483	 Arungkeke	396	t
5484	 Tarowang	396	t
5485	 Kelara	396	t
5486	 Rumbia	396	t
5487	 Mangara Bombang	397	t
5488	 Mappakasunggu	397	t
5489	 Sanrobone	397	t
5490	 Polombangkeng Selatan	397	t
5491	 Pattallassang	397	t
5492	 Polombangkeng Utara	397	t
5493	 Galesong Selatan	397	t
5494	 Galesong	397	t
5495	 Galesong Utara	397	t
5496	 Bontonompo	398	t
5497	 Bontonompo Selatan	398	t
5498	 Bajeng	398	t
5499	 Bajeng Barat	398	t
5500	 Pallangga	398	t
5501	 Barombong	398	t
5502	 Somba Opu	398	t
5503	 Bontomarannu	398	t
5504	 Pattallassang	398	t
5505	 Parangloe	398	t
5506	 Manuju	398	t
5507	 Tinggimoncong	398	t
5508	 Tombolo Pao	398	t
5509	 Parigi	398	t
5510	 Bungaya	398	t
5511	 Bontolempangan	398	t
5512	 Tompobulu	398	t
5513	 Biringbulu	398	t
5514	 Sinjai Barat	399	t
5515	 Sinjai Borong	399	t
5516	 Sinjai Selatan	399	t
5517	 Tellu Limpoe	399	t
5518	 Sinjai Timur	399	t
5519	 Sinjai Tengah	399	t
5520	 Sinjai Utara	399	t
5521	 Bulupoddo	399	t
5522	 Pulau Sembilan	399	t
5523	 Mandai	400	t
5524	 Moncongloe	400	t
5525	 Maros Baru	400	t
5526	 Marusu	400	t
5527	 Turikale	400	t
5528	 Lau	400	t
5529	 Bontoa	400	t
5530	 Bantimurung	400	t
5531	 Simbang	400	t
5532	 Tanralili	400	t
5533	 Tompu Bulu	400	t
5534	 Camba	400	t
5535	 Cenrana	400	t
5536	 Mallawa	400	t
5537	 Liukang Tangaya	401	t
5538	 Liukang Kalmas	401	t
5539	 Liukang Tupabbiring	401	t
5540	 Liukang Tupabbiring Utara	401	t
5541	 Pangkajene	401	t
5542	 Minasatene	401	t
5543	 Balocci	401	t
5544	 Tondong Tallasa	401	t
5545	 Bungoro	401	t
5546	 Labakkang	401	t
5547	 Ma rang	401	t
5548	 Segeri	401	t
5549	 Mandalle	401	t
5550	 Tanete Riaja	402	t
5551	 Pujananting	402	t
5552	 Tanete Rilau	402	t
5553	 Barru	402	t
5554	 Soppeng Riaja	402	t
5555	 Balusu	402	t
5556	 Mallusetasi	402	t
5557	 Bontocani	403	t
5558	 Kahu	403	t
5559	 Kajuara	403	t
5560	 Salomekko	403	t
5561	 Tonra	403	t
5562	 Patimpeng	403	t
5563	 Libureng	403	t
5564	 Mare	403	t
5565	 Sibulue	403	t
5566	 Cina	403	t
5567	 Barebbo	403	t
5568	 Ponre	403	t
5569	 Lappariaja	403	t
5570	 Lamuru	403	t
5571	 Tellu Limpoe	403	t
5572	 Bengo	403	t
5573	 Ulaweng	403	t
5574	 Palakka	403	t
5575	 Awangpone	403	t
5576	 Tellu Siattinge	403	t
5577	 Amali	403	t
5578	 Ajangale	403	t
5579	 Dua Boccoe	403	t
5580	 Cenrana	403	t
5581	 Tanete Riattang Barat	403	t
5582	 Tanete Riattang	403	t
5583	 Tanete Riattang Timur	403	t
5584	 Mario Riwawo	404	t
5585	 Lalabata	404	t
5586	 Lili Riaja	404	t
5587	 Ganra	404	t
5588	 Citta	404	t
5589	 Lili Rilau	404	t
5590	 Donri Donri	404	t
5591	 Mario Riawa	404	t
5592	 Sabbang Paru	405	t
5593	 Tempe	405	t
5594	 Pammana	405	t
5595	 Bola	405	t
5596	 Takkalalla	405	t
5597	 Sajoanging	405	t
5598	 Penrang	405	t
5599	 Majauleng	405	t
5600	 Tana Sitolo	405	t
5601	 Belawa	405	t
5602	 Maniang Pajo	405	t
5603	 Gilireng	405	t
5604	 Keera	405	t
5605	 Pitumpanua	405	t
5606	 Panca Lautang	406	t
5607	 Tellulimpo E	406	t
5608	 Watang Pulu	406	t
5609	 Baranti	406	t
5610	 Panca Rijang	406	t
5611	 Kulo	406	t
5612	 Maritengngae	406	t
5613	 Watang Sidenreng	406	t
5614	 Pitu Riawa	406	t
5615	 Duapitue	406	t
5616	 Pitu Riase	406	t
5617	 Suppa	407	t
5618	 Mattirosompe	407	t
5619	 Lanrisang	407	t
5620	 Mattiro Bulu	407	t
5621	 Watang Sawitto	407	t
5622	 Paleteang	407	t
5623	 Tiroang	407	t
5624	 Patampanua	407	t
5625	 Cempa	407	t
5626	 Duampanua	407	t
5627	 Batulappa	407	t
5628	 Lembang	407	t
5629	 Maiwa	408	t
5630	 Bungin	408	t
5631	 Enrekang	408	t
5632	 Cendana	408	t
5633	 Baraka	408	t
5634	 Buntu Batu	408	t
5635	 Anggeraja	408	t
5636	 Malua	408	t
5637	 Alla	408	t
5638	 Curio	408	t
5639	 Masalle	408	t
5640	 Baroko	408	t
5641	 Larompong	409	t
5642	 Larompong Selatan	409	t
5643	 Suli	409	t
5644	 Suli Barat	409	t
5645	 Belopa	409	t
5646	 Kamanre	409	t
5647	 Belopa Utara	409	t
5648	 Bajo	409	t
5649	 Bajo Barat	409	t
5650	 Bassesangtempe	409	t
5651	 Latimojong	409	t
5652	 Bassesangtempe Utara	409	t
5653	 Bupon	409	t
5654	 Ponrang	409	t
5655	 Ponrang Selatan	409	t
5656	 Bua	409	t
5657	 Walenrang	409	t
5658	 Walenrang Timur	409	t
5659	 Lamasi	409	t
5660	 Walenrang Utara	409	t
5661	 Walenrang Barat	409	t
5662	 Lamasi Timur	409	t
5663	 Bonggakaradeng	410	t
5664	 Simbuang	410	t
5665	 Rano	410	t
5666	 Mappak	410	t
5667	 Mengkendek	410	t
5668	 Gandang Batu Silanan	410	t
5669	 Sangalla	410	t
5670	 Sangala Selatan	410	t
5671	 Sangalla Utara	410	t
5672	 Makale	410	t
5673	 Makale Selatan	410	t
5674	 Makale Utara	410	t
5675	 Saluputti	410	t
5676	 Bittuang	410	t
5677	 Rembon	410	t
5678	 Masanda	410	t
5679	 Malimbong Balepe	410	t
5680	 Rantetayo	410	t
5681	 Kurra	410	t
5682	 Sabbang	411	t
5683	 Baebunta	411	t
5684	 Malangke	411	t
5685	 Malangke Barat	411	t
5686	 Sukamaju	411	t
5687	 Bone-bone	411	t
5688	 Tana Lili	411	t
5689	 Masamba	411	t
5690	 Mappedeceng	411	t
5691	 Rampi	411	t
5692	 Limbong	411	t
5693	 Seko	411	t
5694	 Burau	412	t
5695	 Wotu	412	t
5696	 Tomoni	412	t
5697	 Tomoni Timur	412	t
5698	 Angkona	412	t
5699	 Malili	412	t
5700	 Towuti	412	t
5701	 Nuha	412	t
5702	 Wasuponda	412	t
5703	 Mangkutana	412	t
5704	 Kalaena	412	t
5705	 Sopai	413	t
5706	 Kesu	413	t
5707	 Sanggalangi	413	t
5708	 Buntao	413	t
5709	 Rantebua	413	t
5710	 Nanggala	413	t
5711	 Tondon	413	t
5712	 Tallunglipu	413	t
5713	 Rantepao	413	t
5714	 Tikala	413	t
5715	 Sesean	413	t
5716	 Balusu	413	t
5717	 Sa dan	413	t
5718	 Bengkelekila	413	t
5719	 Sesean Suloara	413	t
5720	 Kapala Pitu	413	t
5721	 Dende Piongan Napo	413	t
5722	 Awan Rante Karua	413	t
5723	 Rindingalo	413	t
5724	 Buntu Pepasan	413	t
5725	 Baruppu	413	t
5726	 Mariso	414	t
5727	 Mamajang	414	t
5728	 Tamalate	414	t
5729	 Rappocini	414	t
5730	 Makassar	414	t
5731	 Ujung Pandang	414	t
5732	 Wajo	414	t
5733	 Bontoala	414	t
5734	 Ujung Tanah	414	t
5735	 Tallo	414	t
5736	 Panakkukang	414	t
5737	 Manggala	414	t
5738	 Biring Kanaya	414	t
5739	 Tamalanrea	414	t
5740	 Bacukiki	415	t
5741	 Bacukiki Barat	415	t
5742	 Ujung	415	t
5743	 Soreang	415	t
5744	 Wara Selatan	416	t
5745	 Sendana	416	t
5746	 Wara	416	t
5747	 Wara Timur	416	t
5748	 Mungkajang	416	t
5749	 Wara Utara	416	t
5750	 Bara	416	t
5751	 Telluwanua	416	t
5752	 Wara Barat	416	t
5753	 Lasalimu	417	t
5754	 Lasalimu Selatan	417	t
5755	 Siontapina	417	t
5756	 Pasar Wajo	417	t
5757	 Wolowa	417	t
5758	 Wabula	417	t
5759	 Sampolawa	417	t
5760	 Batu Atas	417	t
5761	 Lapandewa	417	t
5762	 Batauga	417	t
5763	 Siompu	417	t
5764	 Kadatua	417	t
5765	 Siompu Barat	417	t
5766	 Kapontori	417	t
5767	 Gu	417	t
5768	 Sangia Wambulu	417	t
5769	 Lakudo	417	t
5770	 Mawasangka	417	t
5771	 Mawasangka Timur	417	t
5772	 Mawasangka Tengah	417	t
5773	 Talaga Raya	417	t
5774	 Tongkuno	418	t
5775	 Tongkuno Selatan	418	t
5776	 Parigi	418	t
5777	 Bone	418	t
5778	 Marobo	418	t
5779	 Kabawo	418	t
5780	 Kabangka	418	t
5781	 Kontukowuna	418	t
5782	 Tiworo Kepulauan	418	t
5783	 Maginti	418	t
5784	 Tiworo Tengah	418	t
5785	 Tiworo Selatan	418	t
5786	 Tiworo Utara	418	t
5787	 Lawa	418	t
5788	 Sawerigadi	418	t
5789	 Barangka	418	t
5790	 Wa Daga	418	t
5791	 Kusambi	418	t
5792	 Kontunaga	418	t
5793	 Watopute	418	t
5794	 Katobu	418	t
5795	 Lohia	418	t
5796	 Duruka	418	t
5797	 Batalaiworu	418	t
5798	 Napabalano	418	t
5799	 Lasalepa	418	t
5800	 Napano Kusambi	418	t
5801	 Towea	418	t
5802	 Wakorumba Selatan	418	t
5803	 Pasir Putih	418	t
5804	 Pasi Kolaga	418	t
5805	 Maligano	418	t
5806	 Batukara	418	t
5807	 Wawonii Selatan	419	t
5808	 Wawonii Barat	419	t
5809	 Wawonii Tengah	419	t
5810	 Wawonii Tenggara	419	t
5811	 Wawonii Timur	419	t
5812	 Wawonii Utara	419	t
5813	 Wawonii Timur Laut	419	t
5814	 Soropia	419	t
5815	 Lalonggasumeeto	419	t
5816	 Sampara	419	t
5817	 Bondoala	419	t
5818	 Besulutu	419	t
5819	 Kapoiala	419	t
5820	 Lambuya	419	t
5821	 Uepai	419	t
5822	 Puriala	419	t
5823	 Onembute	419	t
5824	 Pondidaha	419	t
5825	 Wonggeduku	419	t
5826	 Amonggedo	419	t
5827	 Wawotobi	419	t
5828	 Meluhu	419	t
5829	 Konawe	419	t
5830	 Unaaha	419	t
5831	 Anggaberi	419	t
5832	 Abuki	419	t
5833	 Latoma	419	t
5834	 Tongauna	419	t
5835	 Asinua	419	t
5836	 Routa	419	t
5837	 Watubangga	420	t
5838	 Tanggetada	420	t
5839	 Toari	420	t
5840	 Polinggona	420	t
5841	 Pomalaa	420	t
5842	 Wundulako	420	t
5843	 Baula	420	t
5844	 Ladongi	420	t
5845	 Lambandia	420	t
5846	 Poli-polia	420	t
5847	 Dangia	420	t
5848	 Aere	420	t
5849	 Tirawuta	420	t
5850	 Lalolae	420	t
5851	 Loea	420	t
5852	 Kolaka	420	t
5853	 Latambaga	420	t
5854	 Wolo	420	t
5855	 Samaturu	420	t
5856	 Iwoimendaa	420	t
5857	 Mowewe	420	t
5858	 Uluiwoi	420	t
5859	 Tinondo	420	t
5860	 Ueesi	420	t
5861	 Tinanggea	421	t
5862	 Lalembuu	421	t
5863	 Andoolo	421	t
5864	 Buke	421	t
5865	 Palangga	421	t
5866	 Palangga Selatan	421	t
5867	 Baito	421	t
5868	 Lainea	421	t
5869	 Laeya	421	t
5870	 Kolono	421	t
5871	 Laonti	421	t
5872	 Moramo	421	t
5873	 Moramo Utara	421	t
5874	 Konda	421	t
5875	 Wolasi	421	t
5876	 Ranomeeto	421	t
5877	 Ranomeeto Barat	421	t
5878	 Landono	421	t
5879	 Mowila	421	t
5880	 Angata	421	t
5881	 Benua	421	t
5882	 Basala	421	t
5883	 Kabaena	422	t
5884	 Kabaena Utara	422	t
5885	 Kabaena Selatan	422	t
5886	 Kabaena Barat	422	t
5887	 Kabaena Timur	422	t
5888	 Kabaena Tengah	422	t
5889	 Rumbia	422	t
5890	 Mata Oleo	422	t
5891	 Kep. Masaloka Raya	422	t
5892	 Rumbia Tengah	422	t
5893	 Rarowatu	422	t
5894	 Rarowatu Utara	422	t
5895	 Mata Usu	422	t
5896	 Lantari Jaya	422	t
5897	 Poleang Timur	422	t
5898	 Poleang Utara	422	t
5899	 Poleang Selatan	422	t
5900	 Poleang Tenggara	422	t
5901	 Poleang	422	t
5902	 Poleang Barat	422	t
5903	 Tontonunu	422	t
5904	 Poleang Tengah	422	t
5905	 Binongko	423	t
5906	 Togo Binongko	423	t
5907	 Tomia	423	t
5908	 Tomia Timur	423	t
5909	 Kaledupa	423	t
5910	 Kaledupa Selatan	423	t
5911	 Wangi-wangi	423	t
5912	 Wangi-wangi Selatan	423	t
5913	 Ranteangin	424	t
5914	 Lambai	424	t
5915	 Wawo	424	t
5916	 Lasusua	424	t
5917	 Katoi	424	t
5918	 Kodeoha	424	t
5919	 Tiwu	424	t
5920	 Ngapa	424	t
5921	 Watunohu	424	t
5922	 Pakue	424	t
5923	 Pakue Utara	424	t
5924	 Pakue Tengah	424	t
5925	 Batu Putih	424	t
5926	 Porehu	424	t
5927	 Tolala	424	t
5928	 Bonegunu	425	t
5929	 Kambowa	425	t
5930	 Wakorumba	425	t
5931	 Kulisusu	425	t
5932	 Kulisusu Barat	425	t
5933	 Kulisusu Utara	425	t
5934	 Sawa	426	t
5935	 Motui	426	t
5936	 Lembo	426	t
5937	 Lasolo	426	t
5938	 Molawe	426	t
5939	 Asera	426	t
5940	 Andowia	426	t
5941	 Oheo	426	t
5942	 Langgikima	426	t
5943	 Wiwirano	426	t
5944	 Mandonga	427	t
5945	 Baruga	427	t
5946	 Puuwatu	427	t
5947	 Kadia	427	t
5948	 Wua-wua	427	t
5949	 Poasia	427	t
5950	 Abeli	427	t
5951	 Kambu	427	t
5952	 Kendari	427	t
5953	 Kendari Barat	427	t
5954	 Betoambari	428	t
5955	 Murhum	428	t
5956	 Batupoaro	428	t
5957	 Wolio	428	t
5958	 Kokalukuna	428	t
5959	 Sorawolio	428	t
5960	 Bungi	428	t
5961	 Lea-lea	428	t
5962	 Mananggu	429	t
5963	 Tilamuta	429	t
5964	 Dulupi	429	t
5965	 Botumoito	429	t
5966	 Paguyaman	429	t
5967	 Wonosari	429	t
5968	 Paguyaman Pantai	429	t
5969	 Batudaa Pantai	430	t
5970	 Biluhu	430	t
5971	 Batudaa	430	t
5972	 Bongomeme	430	t
5973	 Tabongo	430	t
5974	 Dungaliyo	430	t
5975	 Tibawa	430	t
5976	 Pulubala	430	t
5977	 Boliyohuto	430	t
5978	 Mootilango	430	t
5979	 Tolangohula	430	t
5980	 Asparaga	430	t
5981	 Bilato	430	t
5982	 Limboto	430	t
5983	 Limboto Barat	430	t
5984	 Telaga	430	t
5985	 Telaga Biru	430	t
5986	 Tilango	430	t
5987	 Telaga Jaya	430	t
5988	 Popayato	431	t
5989	 Popayato Barat	431	t
5990	 Popayato Timur	431	t
5991	 Lemito	431	t
5992	 Wanggarasi	431	t
5993	 Marisa	431	t
5994	 Patilanggio	431	t
5995	 Buntulia	431	t
5996	 Duhiadaa	431	t
5997	 Randangan	431	t
5998	 Taluditi	431	t
5999	 Paguat	431	t
6000	 Dengilo	431	t
6001	 Tapa	432	t
6002	 Bulango Utara	432	t
6003	 Bulango Selatan	432	t
6004	 Bulango Timur	432	t
6005	 Bulango Ulu	432	t
6006	 Kabila	432	t
6007	 Botu Pingge	432	t
6008	 Tilongkabila	432	t
6009	 Suwawa	432	t
6010	 Suwawa Selatan	432	t
6011	 Suwawa Timur	432	t
6012	 Suwawa Tengah	432	t
6013	 Pinogu	432	t
6014	 Bonepantai	432	t
6015	 Kabila Bone	432	t
6016	 Bone Raya	432	t
6017	 Bone	432	t
6018	 Bulawa	432	t
6019	 Atinggola	433	t
6020	 Gentuma Raya	433	t
6021	 Kwandang	433	t
6022	 Tomilito	433	t
6023	 Ponelo Kepulauan	433	t
6024	 Anggrek	433	t
6025	 Monano	433	t
6026	 Sumalata	433	t
6027	 Sumalata Timur	433	t
6028	 Tolinggula	433	t
6029	 Biau	433	t
6030	 Kota Barat	434	t
6031	 Dungingi	434	t
6032	 Kota Selatan	434	t
6033	 Kota Timur	434	t
6034	 Hulonthalangi	434	t
6035	 Dumbo Raya	434	t
6036	 Kota Utara	434	t
6037	 Kota Tengah	434	t
6038	 Sipatana	434	t
6039	 Banggae	435	t
6040	 Banggae Timur	435	t
6041	 Pamboang	435	t
6042	 Sendana	435	t
6043	 Tammerodo	435	t
6044	 Tubo Sendana	435	t
6045	 Malunda	435	t
6046	 Ulumanda	435	t
6047	 Tinambung	436	t
6048	 Balanipa	436	t
6049	 Limboro	436	t
6050	 Tubbi Taramanu	436	t
6051	 Alu	436	t
6052	 Campalagian	436	t
6053	 Luyo	436	t
6054	 Wonomulyo	436	t
6055	 Mapilli	436	t
6056	 Tapango	436	t
6057	 Matakali	436	t
6058	 B U L O	436	t
6059	 Polewali	436	t
6060	 Binuang	436	t
6061	 Anreapi	436	t
6062	 Matangnga	436	t
6063	 Sumarorong	437	t
6064	 Messawa	437	t
6065	 Pana	437	t
6066	 Nosu	437	t
6067	 Tabang	437	t
6068	 Mamasa	437	t
6069	 Tanduk Kalua	437	t
6070	 Balla	437	t
6071	 Sesenapadang	437	t
6072	 Tawalian	437	t
6073	 Mambi	437	t
6074	 Bambang	437	t
6075	 Rantebulahan Timur	437	t
6076	 Mehalaan	437	t
6077	 Aralle	437	t
6078	 Buntu Malangka	437	t
6079	 Tabulahan	437	t
6080	 Tapalang	438	t
6081	 Tapalang Barat	438	t
6082	 Mamuju	438	t
6083	 Simboro	438	t
6084	 Balabalakang	438	t
6085	 Kalukku	438	t
6086	 Papalang	438	t
6087	 Sampaga	438	t
6088	 Tommo	438	t
6089	 Kalumpang	438	t
6090	 Bonehau	438	t
6091	 Budong-budong	438	t
6092	 Pangale	438	t
6093	 Topoyo	438	t
6094	 Karossa	438	t
6095	 Tobadak	438	t
6096	 Sarudu	439	t
6097	 Dapurang	439	t
6098	 Duripoku	439	t
6099	 Baras	439	t
6100	 Bulu Taba	439	t
6101	 Lariang	439	t
6102	 Pasangkayu	439	t
6103	 Tikke Raya	439	t
6104	 Pedongga	439	t
6105	 Bambalamotu	439	t
6106	 Bambaira	439	t
6107	 Sarjo	439	t
6108	 Tanimbar Selatan	440	t
6109	 Wer Tamrian	440	t
6110	 Wer Maktian	440	t
6111	 Selaru	440	t
6112	 Tanimbar Utara	440	t
6113	 Yaru	440	t
6114	 Wuar Labobar	440	t
6115	 Nirunmas	440	t
6116	 Kormomolin	440	t
6117	 Molu Maru	440	t
6118	 Kei Kecil	441	t
6119	 Kei Kecil Barat	441	t
6120	 Kei Kecil Timur	441	t
6121	 Kei Besar	441	t
6122	 Kei Besar Utara Timur	441	t
6123	 Kei Besar Selatan	441	t
6124	 Banda	442	t
6125	 Tehoru	442	t
6126	 Telutih	442	t
6127	 Amahai	442	t
6128	 Kota Masohi	442	t
6129	 Teluk Elpaputih	442	t
6130	 Teon Nila Serua	442	t
6131	 Saparua	442	t
6132	 Nusalaut	442	t
6133	 P. Haruku	442	t
6134	 Salahutu	442	t
6135	 Leihitu	442	t
6136	 Leihitu Barat	442	t
6137	 Seram Utara	442	t
6138	 Seram Utara Barat	442	t
6139	 Seram Utara Timur Kobi	442	t
6140	 Seram Utara Timur Seti	442	t
6141	 Namlea	443	t
6142	 Waeapo	443	t
6143	 Waplau	443	t
6144	 Bata Bual	443	t
6145	 Teluk Kaiely	443	t
6146	 Waelata	443	t
6147	 Lolong Guba	443	t
6148	 Lilialy	443	t
6149	 Air Buaya	443	t
6150	 Fena Leisela	443	t
6151	 Aru Selatan	444	t
6152	 Aru Selatan Timur	444	t
6153	 Aru Tengah	444	t
6154	 Aru Tengah Timur	444	t
6155	 Aru Tengah Selatan	444	t
6156	 Pulau-pulau Aru	444	t
6157	 Aru Utara	444	t
6158	 Huamual Belakang	445	t
6159	 Kepulauan Manipa	445	t
6160	 Seram Barat	445	t
6161	 Huamual	445	t
6162	 Kairatu	445	t
6163	 Kairatu Barat	445	t
6164	 Inamosol	445	t
6165	 Amalatu	445	t
6166	 Elpaputih	445	t
6167	 Taniwel	445	t
6168	 Taniwel Timur	445	t
6169	 Pulau Gorom	446	t
6170	 Wakate	446	t
6171	 Teor	446	t
6172	 Gorom Timur	446	t
6173	 Pulau Panjang	446	t
6174	 Seram Timur	446	t
6175	 Tutuk Tolu	446	t
6176	 Kilmury	446	t
6177	 Werinama	446	t
6178	 Siwalalat	446	t
6179	 Bula	446	t
6180	 Bula Barat	446	t
6181	 Pp. Wetar	447	t
6182	 Pp. Terselatan	447	t
6183	 Leti Moa Lakor	447	t
6184	 Moa Lakor	447	t
6185	 Damer	447	t
6186	 Mdona Hiera	447	t
6187	 Pp. Babar	447	t
6188	 Babar Timur	447	t
6189	 Kepala Madan	448	t
6190	 Leksula	448	t
6191	 Namrole	448	t
6192	 Waisama	448	t
6193	 Ambalau	448	t
6194	 Nusaniwe	449	t
6195	 Sirimau	449	t
6196	 Leitimur Selatan	449	t
6197	 Teluk Ambon Baguala	449	t
6198	 Teluk Ambon	449	t
6199	 Pp. Kur	450	t
6200	 Tayando Tam	450	t
6201	 Pulau Dullah Utara	450	t
6202	 Pulau Dullah Selatan	450	t
6203	 Jailolo	451	t
6204	 Jailolo Selatan	451	t
6205	 Sahu	451	t
6206	 Sahu Timur	451	t
6207	 Ibu	451	t
6208	 Ibu Selatan	451	t
6209	 Ibu Utara	451	t
6210	 Loloda	451	t
6211	 Weda	452	t
6212	 Weda Selatan	452	t
6213	 Weda Utara	452	t
6214	 Weda Tengah	452	t
6215	 Pulau Gebe	452	t
6216	 Patani	452	t
6217	 Patani Utara	452	t
6218	 Patani Barat	452	t
6219	 Sula Besi Barat	453	t
6220	 Sulabesi Selatan	453	t
6221	 Sanana	453	t
6222	 Sula Besi Tengah	453	t
6223	 Sulabesi Timur	453	t
6224	 Sanana Utara	453	t
6225	 Mangoli Timur	453	t
6226	 Mangoli Tengah	453	t
6227	 Mangoli Utara Timur	453	t
6228	 Mangoli Barat	453	t
6229	 Mangoli Utara	453	t
6230	 Mangoli Selatan	453	t
6231	 Taliabu Timur	453	t
6232	 Taliabu Timur Selatan	453	t
6233	 Taliabu Barat	453	t
6234	 Taliabu Utara	453	t
6235	 Taliabu Barat Laut	453	t
6236	 Lede	453	t
6237	 Taliabu Selatan	453	t
6238	 Obi Selatan	454	t
6239	 Obi	454	t
6240	 Obi Barat	454	t
6241	 Obi Timur	454	t
6242	 Obi Utara	454	t
6243	 Bacan	454	t
6244	 Mandioli Selatan	454	t
6245	 Mandioli Utara	454	t
6246	 Bacan Selatan	454	t
6247	 Batang Lomang	454	t
6248	 Bacan Timur	454	t
6249	 Bacan Timur Selatan	454	t
6250	 Bacan Timur Tengah	454	t
6251	 Bacan Barat	454	t
6252	 Kasiruta Barat	454	t
6253	 Kasiruta Timur	454	t
6254	 Bacan Barat Utara	454	t
6255	 Kayoa	454	t
6256	 Kayoa Barat	454	t
6257	 Kayoa Selatan	454	t
6258	 Kayoa Utara	454	t
6259	 Pulau Makian	454	t
6260	 Makian Barat	454	t
6261	 Gane Barat	454	t
6262	 Gane Barat Selatan	454	t
6263	 Gane Barat Utara	454	t
6264	 Kepulauan Joronga	454	t
6265	 Gane Timur	454	t
6266	 Gane Timur Tengah	454	t
6267	 Gane Timur Selatan	454	t
6268	 Malifut	455	t
6269	 Kao Teluk	455	t
6270	 Kao	455	t
6271	 Kao Barat	455	t
6272	 Kao Utara	455	t
6273	 Tobelo Selatan	455	t
6274	 Tobelo Barat	455	t
6275	 Tobelo Timur	455	t
6276	 Tobelo	455	t
6277	 Tobelo Tengah	455	t
6278	 Tobelo Utara	455	t
6279	 Galela	455	t
6280	 Galela Selatan	455	t
6281	 Galela Barat	455	t
6282	 Galela Utara	455	t
6283	 Loloda Utara	455	t
6284	 Loloda Kepulauan	455	t
6285	 Maba Selatan	456	t
6286	 Kota Maba	456	t
6287	 Wasile Selatan	456	t
6288	 Wasile	456	t
6289	 Wasile Timur	456	t
6290	 Wasile Tengah	456	t
6291	 Wasile Utara	456	t
6292	 Maba	456	t
6293	 Maba Tengah	456	t
6294	 Maba Utara	456	t
6295	 Morotai Selatan	457	t
6296	 Morotai Timur	457	t
6297	 Morotai Selatan Barat	457	t
6298	 Morotai Jaya	457	t
6299	 Morotai Utara	457	t
6300	 Pulau Ternate	458	t
6301	 Moti	458	t
6302	 Pulau Batang Dua	458	t
6303	 Pulau Hiri	458	t
6304	 Ternate Selatan	458	t
6305	 Ternate Tengah	458	t
6306	 Ternate Utara	458	t
6307	 Tidore Selatan	459	t
6308	 Tidore Utara	459	t
6309	 Tidore	459	t
6310	 Tidore Timur	459	t
6311	 Oba	459	t
6312	 Oba Selatan	459	t
6313	 Oba Utara	459	t
6314	 Oba Tengah	459	t
6315	 Fakfak Timur	460	t
6316	 Karas	460	t
6317	 Fakfak	460	t
6318	 Fakfak Tengah	460	t
6319	 Fakfak Barat	460	t
6320	 Kokas	460	t
6321	 Teluk Patipi	460	t
6322	 Kramongmongga	460	t
6323	 Bomberay	460	t
6324	 Buruway	461	t
6325	 Teluk Arguni	461	t
6326	 Teluk Arguni Bawah	461	t
6327	 Kaimana	461	t
6328	 Kambrau	461	t
6329	 Teluk Etna	461	t
6330	 Yamor	461	t
6331	 Naikere	462	t
6332	 Wondiboy	462	t
6333	 Rasiey	462	t
6334	 Kuri Wamesa	462	t
6335	 Wasior	462	t
6336	 Duairi	462	t
6337	 Roon	462	t
6338	 Windesi	462	t
6339	 Nikiwar	462	t
6340	 Wamesa	462	t
6341	 Roswar	462	t
6342	 Rumberpon	462	t
6343	 Soug Jaya	462	t
6344	 Fafurwar	463	t
6345	 Babo	463	t
6346	 Sumuri	463	t
6347	 Aroba	463	t
6348	 Kaitaro	463	t
6349	 Kuri	463	t
6350	 Wamesa	463	t
6351	 Bintuni	463	t
6352	 Manimeri	463	t
6353	 Tuhiba	463	t
6354	 Dataran Beimes	463	t
6355	 Tembuni	463	t
6356	 Aranday	463	t
6357	 Kamundan	463	t
6358	 Tomu	463	t
6359	 Weriagar	463	t
6360	 Moskona Selatan	463	t
6361	 Meyado	463	t
6362	 Moskona Barat	463	t
6363	 Merdey	463	t
6364	 Biscoop	463	t
6365	 Masyeta	463	t
6366	 Moskona Utara	463	t
6367	 Moskona Timur	463	t
6368	 Ransiki	464	t
6369	 Momi Waren	464	t
6370	 Nenei	464	t
6371	 Sururey	464	t
6372	 Anggi	464	t
6373	 Taige	464	t
6374	 Membey	464	t
6375	 Oransbari	464	t
6376	 Warmare	464	t
6377	 Prafi	464	t
6378	 Menyambouw	464	t
6379	 Catubouw	464	t
6380	 Manokwari Barat	464	t
6381	 Manokwari Timur	464	t
6382	 Manokwari Utara	464	t
6383	 Manokwari Selatan	464	t
6384	 Testega	464	t
6385	 Tanah Rubu	464	t
6386	 Kebar	464	t
6387	 Senopi	464	t
6388	 Amberbaken	464	t
6389	 Mubarni / Arfu	464	t
6390	 Masni	464	t
6391	 Sidey	464	t
6392	 Tahosta	464	t
6393	 Didohu	464	t
6394	 Dataran Isim	464	t
6395	 Anggi Gida	464	t
6396	 Hingk	464	t
6397	 Inanwatan	465	t
6398	 Metemani	465	t
6399	 Kokoda	465	t
6400	 Kais	465	t
6401	 Kokoda Utara	465	t
6402	 Moswaren	465	t
6403	 Teminabuan	465	t
6404	 Seremuk	465	t
6405	 Wayer	465	t
6406	 Konda	465	t
6407	 Saifi	465	t
6408	 Sawiat	465	t
6409	 Fokour	465	t
6410	 Moraid	466	t
6411	 Klaso	466	t
6412	 Makbon	466	t
6413	 Klayili	466	t
6414	 Beraur	466	t
6415	 Klamono	466	t
6416	 Klabot	466	t
6417	 Klawak	466	t
6418	 Salawati	466	t
6419	 Mayamuk	466	t
6420	 Salawati Timur	466	t
6421	 Seget	466	t
6422	 Segun	466	t
6423	 Salawati Selatan	466	t
6424	 Aimas	466	t
6425	 Mariat	466	t
6426	 Sorong	466	t
6427	 Sayosa	466	t
6428	 Maudus	466	t
6429	 Misool Selatan	467	t
6430	 Misool Barat	467	t
6431	 Misool	467	t
6432	 Kofiau	467	t
6433	 Misool Timur	467	t
6434	 Kepulauan Sembilan	467	t
6435	 Salawati Utara	467	t
6436	 Salawati Tengah	467	t
6437	 Salawati Barat	467	t
6438	 Batanta Selatan	467	t
6439	 Batanta Utara	467	t
6440	 Waigeo Selatan	467	t
6441	 Teluk Mayalibit	467	t
6442	 Meos Mansar	467	t
6443	 Kota Waisai	467	t
6444	 Tiplol Mayalibit	467	t
6445	 Waigeo Barat	467	t
6446	 Waigeo Barat Kepulauan	467	t
6447	 Waigeo Utara	467	t
6448	 Warwarbomi	467	t
6449	 Supnin	467	t
6450	 Kepulauan Ayau	467	t
6451	 Ayau	467	t
6452	 Waigeo Timur	467	t
6453	 Fef	468	t
6454	 Syujak	468	t
6455	 Miyah	468	t
6456	 Abun	468	t
6457	 Kwoor	468	t
6458	 Sausapor	468	t
6459	 Yembun	468	t
6460	 Aitinyo Barat/athabu	469	t
6461	 Ayamaru Selatan Jaya	469	t
6462	 Aitinyo	469	t
6463	 Aitinyo Tengah	469	t
6464	 Aifat Selatan	469	t
6465	 Aifat Timur Selatan	469	t
6466	 Aifat	469	t
6467	 Aitinyo Utara	469	t
6468	 Aitinyo Raya	469	t
6469	 Ayamaru Timur	469	t
6470	 Ayamaru Timur Selatan	469	t
6471	 Ayamaru	469	t
6472	 Ayamaru Selatan	469	t
6473	 Ayamaru Jaya	469	t
6474	 Ayamaru Tengah	469	t
6475	 Ayamaru Barat	469	t
6476	 Ayamaru Utara	469	t
6477	 Ayamaru Utara Timur	469	t
6478	 Mare	469	t
6479	 Mare Selatan	469	t
6480	 Aifat Utara	469	t
6481	 Aifat Timur	469	t
6482	 Aifat Timur Tengah	469	t
6483	 Aifat Timur Jauh	469	t
6484	 Sorong Barat	470	t
6485	 Sorong Kepulauan	470	t
6486	 Sorong Timur	470	t
6487	 Sorong Utara	470	t
6488	 Sorong	470	t
6489	 Sorong Manoi	470	t
6490	 Kimaam	471	t
6491	 Waan	471	t
6492	 Tabonji	471	t
6493	 Ilwayab	471	t
6494	 Okaba	471	t
6495	 Tubang	471	t
6496	 Ngguti	471	t
6497	 Kaptel	471	t
6498	 Kurik	471	t
6499	 Malind	471	t
6500	 Animha	471	t
6501	 Merauke	471	t
6502	 Semangga	471	t
6503	 Tanah Miring	471	t
6504	 Jagebob	471	t
6505	 Sota	471	t
6506	 Naukenjerai	471	t
6507	 Muting	471	t
6508	 Eligobel	471	t
6509	 Ulilin	471	t
6510	 Wamena	472	t
6511	 Asolokobal	472	t
6512	 Walelagama	472	t
6513	 Hubikosi	472	t
6514	 Pelebaga	472	t
6515	 Asologaima	472	t
6516	 Musatfak	472	t
6517	 Kurulu	472	t
6518	 Bolakme	472	t
6519	 Wollo	472	t
6520	 Yalengga	472	t
6521	 Kaureh	473	t
6522	 Airu	473	t
6523	 Yapsi	473	t
6524	 Kemtuk	473	t
6525	 Kemtuk Gresi	473	t
6526	 Gresi Selatan	473	t
6527	 Nimboran	473	t
6528	 Nimboran Timur / Namblong	473	t
6529	 Nimbokrang	473	t
6530	 Unurum Guay	473	t
6531	 Demta	473	t
6532	 Yokari	473	t
6533	 Depapre	473	t
6534	 Ravenirara	473	t
6535	 Sentani Barat	473	t
6536	 Waibu	473	t
6537	 Sentani	473	t
6538	 Ebungfau	473	t
6539	 Sentani Timur	473	t
6540	 Uwapa	474	t
6541	 Menou	474	t
6542	 Dipa	474	t
6543	 Yaur	474	t
6544	 Teluk Umar	474	t
6545	 Wanggar	474	t
6546	 Nabire Barat	474	t
6547	 Nabire	474	t
6548	 Teluk Kimi	474	t
6549	 Napan	474	t
6550	 Makimi	474	t
6551	 Wapoga	474	t
6552	 Siriwo	474	t
6553	 Yaro	474	t
6554	 Yapen Timur	475	t
6555	 Pantura Yapen	475	t
6556	 Teluk Ampimoi	475	t
6557	 Raimbawi	475	t
6558	 Pulau Kurudu	475	t
6559	 Angkaisera	475	t
6560	 Kep. Ambai	475	t
6561	 Yapen Selatan	475	t
6562	 Kosiwo	475	t
6563	 Yapen Barat	475	t
6564	 Wonawa	475	t
6565	 Pulau Yerui	475	t
6566	 Poom	475	t
6567	 Windesi	475	t
6568	 Numfor Barat	476	t
6569	 Orkeri	476	t
6570	 Numfor Timur	476	t
6571	 Bruyadori	476	t
6572	 Poiru	476	t
6573	 Padaido	476	t
6574	 Aimando Padaido	476	t
6575	 Biak Timur	476	t
6576	 Oridek	476	t
6577	 Biak Kota	476	t
6578	 Samofa	476	t
6579	 Yendidori	476	t
6580	 Biak Utara	476	t
6581	 Andey	476	t
6582	 Warsa	476	t
6583	 Yawosi	476	t
6584	 Bondifuar	476	t
6585	 Biak Barat	476	t
6586	 Swandiwe	476	t
6587	 Paniai Timur	477	t
6588	 Yatamo	477	t
6589	 Kebo	477	t
6590	 Bibida	477	t
6591	 Dumadama	477	t
6592	 Aradide	477	t
6593	 Ekadide	477	t
6594	 Paniai Barat	477	t
6595	 Siriwo	477	t
6596	 Bogobaida	477	t
6597	 Fawi	478	t
6598	 Mulia	478	t
6599	 Mewoluk	478	t
6600	 Yamo	478	t
6601	 Ilu	478	t
6602	 Torere	478	t
6603	 Jigonikme	478	t
6604	 Tingginambut	478	t
6605	 Mimika Barat	479	t
6606	 Mimika Barat Jauh	479	t
6607	 Mimika Barat Tengah	479	t
6608	 Mimika Timur	479	t
6609	 Mimika Tengah	479	t
6610	 Mimika Timur Jauh	479	t
6611	 Mimika Baru	479	t
6612	 Kuala Kencana	479	t
6613	 Tembagapura	479	t
6614	 Agimuga	479	t
6615	 Jila	479	t
6616	 Jita	479	t
6617	 Jair	480	t
6618	 Subur	480	t
6619	 Kia	480	t
6620	 Mindiptana	480	t
6621	 Iniyandit	480	t
6622	 Kombut	480	t
6623	 Sesnuk	480	t
6624	 Mandobo	480	t
6625	 Fofi	480	t
6626	 Arimop	480	t
6627	 Kouh	480	t
6628	 Bomakia	480	t
6629	 Firiwage	480	t
6630	 Manggelum	480	t
6631	 Yaniruma	480	t
6632	 Kawagit	480	t
6633	 Kombay	480	t
6634	 Waropko	480	t
6635	 Ambatkwi	480	t
6636	 Ninati	480	t
6637	 Nambioman Bapai	481	t
6638	 Minyamur	481	t
6639	 Edera	481	t
6640	 Venaha	481	t
6641	 Obaa	481	t
6642	 Passue	481	t
6643	 Haju	481	t
6644	 Assue	481	t
6645	 Citakmitak	481	t
6646	 Kaibar	481	t
6647	 Pantai Kasuari	482	t
6648	 Fayit	482	t
6649	 Atsy	482	t
6650	 Suator	482	t
6651	 Kolf Braza	482	t
6652	 Akat	482	t
6653	 Agats	482	t
6654	 Sawa Erma	482	t
6655	 Suru-suru	482	t
6656	 Unir Sirau	482	t
6657	 Kurima	483	t
6658	 Musaik	483	t
6659	 Dekai	483	t
6660	 Obio	483	t
6661	 Pasema	483	t
6662	 Amuma	483	t
6663	 Suru-suru	483	t
6664	 Wusama	483	t
6665	 Silimo	483	t
6666	 Ninia	483	t
6667	 Holuwon	483	t
6668	 Lolat	483	t
6669	 Langda	483	t
6670	 Bomela	483	t
6671	 Suntamon	483	t
6672	 Sobaham	483	t
6673	 Korupun	483	t
6674	 Sela	483	t
6675	 Kwelamdua	483	t
6676	 Anggruk	483	t
6677	 Panggema	483	t
6678	 Walma	483	t
6679	 Kosarek	483	t
6680	 Ubahak	483	t
6681	 Nalca	483	t
6682	 Puldama	483	t
6683	 Nipsan	483	t
6684	 Samenage	483	t
6685	 Tangma	483	t
6686	 Soba	483	t
6687	 Mugi	483	t
6688	 Yogosem	483	t
6689	 Kayo	483	t
6690	 Sumo	483	t
6691	 Hogio	483	t
6692	 Ukha	483	t
6693	 Werima	483	t
6694	 Soloikma	483	t
6695	 Seradala	483	t
6696	 Kabianggama	483	t
6697	 Kwikma	483	t
6698	 Hilipuk	483	t
6699	 Yahuliambut	483	t
6700	 Hereapini	483	t
6701	 Ubalihi	483	t
6702	 Talambo	483	t
6703	 Pronggoli	483	t
6704	 Endomen	483	t
6705	 Kona	483	t
6706	 Duram	483	t
6707	 Dirwemna	483	t
6708	 Iwur	484	t
6709	 Kawor	484	t
6710	 Tarup	484	t
6711	 Awinbon	484	t
6712	 Oksibil	484	t
6713	 Pepera	484	t
6714	 Alemsom	484	t
6715	 Serambakon	484	t
6716	 Kolomdol	484	t
6717	 Oksop	484	t
6718	 Ok Bape	484	t
6719	 Ok Aon	484	t
6720	 Borme	484	t
6721	 Bime	484	t
6722	 Epumek	484	t
6723	 Weime	484	t
6724	 Pamek	484	t
6725	 Nongme	484	t
6726	 Batani	484	t
6727	 Okbi	484	t
6728	 Aboy	484	t
6729	 Okbab	484	t
6730	 Teiraplu	484	t
6731	 Yefta	484	t
6732	 Kiwirok	484	t
6733	 Kiwirok Timur	484	t
6734	 Oksebang	484	t
6735	 Okhika	484	t
6736	 Oklip	484	t
6737	 Oksamol	484	t
6738	 Okbemta	484	t
6739	 Batom	484	t
6740	 Murkim	484	t
6741	 Mofinop	484	t
6742	 Kanggime	485	t
6743	 Woniki	485	t
6744	 Nabunage	485	t
6745	 Gilubandu	485	t
6746	 Wakuo	485	t
6747	 Karubaga	485	t
6748	 Goyage	485	t
6749	 Wunin	485	t
6750	 Kondaga	485	t
6751	 Nelawi	485	t
6752	 Kuari	485	t
6753	 Bokondini	485	t
6754	 Bokoneri	485	t
6755	 Bewani	485	t
6756	 Kembu	485	t
6757	 Wina	485	t
6758	 Umagi	485	t
6759	 Panaga	485	t
6760	 Poganeri	485	t
6761	 Kamboneri	485	t
6762	 Air Garam	485	t
6763	 Dow	485	t
6764	 Wari / Taiyeve	485	t
6765	 Egiam	485	t
6766	 Nunggawi	485	t
6767	 Kubu	485	t
6768	 Geya	485	t
6769	 Numba	485	t
6770	 Dundu	485	t
6771	 Gundagi	485	t
6772	 Timori	485	t
6773	 Yuneri	485	t
6774	 Tagineri	485	t
6775	 Dorman	485	t
6776	 Sbey	485	t
6777	 Pantai Timur Bagian Barat	486	t
6778	 Pantai Timur	486	t
6779	 Bonggo	486	t
6780	 Bonggo Timur	486	t
6781	 Tor Atas	486	t
6782	 Sarmi	486	t
6783	 Sarmi Timur	486	t
6784	 Sarmi Selatan	486	t
6785	 Pantai Barat	486	t
6786	 Apawer Hulu	486	t
6787	 Web	487	t
6788	 Towe Hitam	487	t
6789	 Senggi	487	t
6790	 Waris	487	t
6791	 Arso	487	t
6792	 Arso Timur	487	t
6793	 Skanto	487	t
6794	 Waropen Bawah	488	t
6795	 Inggerus	488	t
6796	 Urei Faisei	488	t
6797	 Oudate	488	t
6798	 Wapoga	488	t
6799	 Masirei	488	t
6800	 Risei Sayati	488	t
6801	 Demba	488	t
6802	 Walani	488	t
6803	 Kirihi	488	t
6804	 Supiori Selatan	489	t
6805	 Kepulauan Aruri	489	t
6806	 Supiori Utara	489	t
6807	 Supiori Barat	489	t
6808	 Supiori Timur	489	t
6809	 Waropen Atas	490	t
6810	 Benuki	490	t
6811	 Sawai	490	t
6812	 Mamberamo Ilir	490	t
6813	 Mamberamo Tengah	490	t
6814	 Mamberamo Tengah Timur	490	t
6815	 Rofaer	490	t
6816	 Mamberamo Ulu	490	t
6817	 Wosak	491	t
6818	 Kenyam	491	t
6819	 Geselma	491	t
6820	 Mapenduma	491	t
6821	 Mugi	491	t
6822	 Yigi	491	t
6823	 Mbuwa	491	t
6824	 Gearek	491	t
6825	 Makki	492	t
6826	 Pirime	492	t
6827	 Tiom	492	t
6828	 Balingga	492	t
6829	 Kuyawage	492	t
6830	 Malagaineri	492	t
6831	 Tiomneri	492	t
6832	 Dimba	492	t
6833	 Gamelia	492	t
6834	 Poga	492	t
6835	 Kobakma	493	t
6836	 Ilugwa	493	t
6837	 Kelila	493	t
6838	 Eragayam	493	t
6839	 Megambilis	493	t
6840	 Welarek	494	t
6841	 Apalapsili	494	t
6842	 Abenaho	494	t
6843	 Elelim	494	t
6844	 Benawa	494	t
6845	 Agadugume	495	t
6846	 Gome	495	t
6847	 Ilaga	495	t
6848	 Sinak	495	t
6849	 Pogoma	495	t
6850	 Wangbe	495	t
6851	 Beoga	495	t
6852	 Doufo	495	t
6853	 Sukikai Selatan	496	t
6854	 Piyaiye	496	t
6855	 Mapia Barat	496	t
6856	 Mapia Tengah	496	t
6857	 Mapia	496	t
6858	 Dogiyai	496	t
6859	 Kamu Selatan	496	t
6860	 Kamu	496	t
6861	 Kamu Timur	496	t
6862	 Kamu Utara	496	t
6863	 Homeyo	497	t
6864	 Sugapa	497	t
6865	 Hitadipa	497	t
6866	 Agisiga	497	t
6867	 Biandoga	497	t
6868	 Wandai	497	t
6869	 Kapiraya	498	t
6870	 Tigi Barat	498	t
6871	 Tigi	498	t
6872	 Tigi Timur	498	t
6873	 Bowobado	498	t
6874	 Muara Tami	499	t
6875	 Abepura	499	t
6876	 Heram	499	t
6877	 Jayapura Selatan	499	t
6878	 Jayapura Utara	499	t
\.


--
-- Name: districts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.districts_id_seq', 1, false);


--
-- Name: districts districts_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.districts
    ADD CONSTRAINT districts_pkey PRIMARY KEY (id);


--
-- Name: districts districts_city_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.districts
    ADD CONSTRAINT districts_city_id_foreign FOREIGN KEY (city_id) REFERENCES public.cities(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

