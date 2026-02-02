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
-- Name: cities; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cities (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    province_id bigint NOT NULL,
    is_active boolean NOT NULL
);


ALTER TABLE public.cities OWNER TO postgres;

--
-- Name: cities_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.cities_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.cities_id_seq OWNER TO postgres;

--
-- Name: cities_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.cities_id_seq OWNED BY public.cities.id;


--
-- Name: cities id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cities ALTER COLUMN id SET DEFAULT nextval('public.cities_id_seq'::regclass);


--
-- Data for Name: cities; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cities (id, name, province_id, is_active) FROM stdin;
1	Kab. Simeulue	1	t
2	Kab. Aceh Singkil	1	t
3	Kab. Aceh Selatan	1	t
4	Kab. Aceh Tenggara	1	t
5	Kab. Aceh Timur	1	t
6	Kab. Aceh Tengah	1	t
7	Kab. Aceh Barat	1	t
8	Kab. Aceh Besar	1	t
9	Kab. Pidie	1	t
10	Kab. Bireuen	1	t
11	Kab. Aceh Utara	1	t
12	Kab. Aceh Barat Daya	1	t
13	Kab. Gayo Lues	1	t
14	Kab. Aceh Tamiang	1	t
15	Kab. Nagan Raya	1	t
16	Kab. Aceh Jaya	1	t
17	Kab. Bener Meriah	1	t
18	Kab. Pidie Jaya	1	t
19	Kota Banda Aceh	1	t
20	Kota Sabang	1	t
21	Kota Langsa	1	t
22	Kota Lhokseumawe	1	t
23	Kota Subulussalam	1	t
24	Kab. Nias	2	t
25	Kab. Mandailing Natal	2	t
26	Kab. Tapanuli Selatan	2	t
27	Kab. Tapanuli Tengah	2	t
28	Kab. Tapanuli Utara	2	t
29	Kab. Toba Samosir	2	t
30	Kab. Labuhan Batu	2	t
31	Kab. Asahan	2	t
32	Kab. Simalungun	2	t
33	Kab. Dairi	2	t
34	Kab. Karo	2	t
35	Kab. Deli Serdang	2	t
36	Kab. Langkat	2	t
37	Kab. Nias Selatan	2	t
38	Kab. Humbang Hasundutan	2	t
39	Kab. Pakpak Bharat	2	t
40	Kab. Samosir	2	t
41	Kab. Serdang Bedagai	2	t
42	Kab. Batu Bara	2	t
43	Kab. Padang Lawas Utara	2	t
44	Kab. Padang Lawas	2	t
45	Kab. Labuhan Batu Selatan	2	t
46	Kab. Labuhan Batu Utara	2	t
47	Kab. Nias Utara	2	t
48	Kab. Nias Barat	2	t
49	Kota Sibolga	2	t
50	Kota Tanjung Balai	2	t
51	Kota Pematang Siantar	2	t
52	Kota Tebing Tinggi	2	t
53	Kota Medan	2	t
54	Kota Binjai	2	t
55	Kota Padangsidimpuan	2	t
56	Kota Gunungsitoli	2	t
57	Kab. Kepulauan Mentawai	3	t
58	Kab. Pesisir Selatan	3	t
59	Kab. Solok	3	t
60	Kab. Sijunjung	3	t
61	Kab. Tanah Datar	3	t
62	Kab. Padang Pariaman	3	t
63	Kab. Agam	3	t
64	Kab. Lima Puluh Kota	3	t
65	Kab. Pasaman	3	t
66	Kab. Solok Selatan	3	t
67	Kab. Dharmasraya	3	t
68	Kab. Pasaman Barat	3	t
69	Kota Padang	3	t
70	Kota Solok	3	t
71	Kota Sawah Lunto	3	t
72	Kota Padang Panjang	3	t
73	Kota Bukittinggi	3	t
74	Kota Payakumbuh	3	t
75	Kota Pariaman	3	t
76	Kab. Kuantan Singingi	4	t
77	Kab. Indragiri Hulu	4	t
78	Kab. Indragiri Hilir	4	t
79	Kab. Pelalawan	4	t
80	Kab. S I A K	4	t
81	Kab. Kampar	4	t
82	Kab. Rokan Hulu	4	t
83	Kab. Bengkalis	4	t
84	Kab. Rokan Hilir	4	t
85	Kab. Kepulauan Meranti	4	t
86	Kota Pekanbaru	4	t
87	Kota D U M A I	4	t
88	Kab. Kerinci	5	t
89	Kab. Merangin	5	t
90	Kab. Sarolangun	5	t
91	Kab. Batang Hari	5	t
92	Kab. Muaro Jambi	5	t
93	Kab. Tanjung Jabung Timur	5	t
94	Kab. Tanjung Jabung Barat	5	t
95	Kab. Tebo	5	t
96	Kab. Bungo	5	t
97	Kota Jambi	5	t
98	Kota Sungai Penuh	5	t
99	Kab. Ogan Komering Ulu	6	t
100	Kab. Ogan Komering Ilir	6	t
101	Kab. Muara Enim	6	t
102	Kab. Lahat	6	t
103	Kab. Musi Rawas	6	t
104	Kab. Musi Banyuasin	6	t
105	Kab. Banyu Asin	6	t
106	Kab. Ogan Komering Ulu Selatan	6	t
107	Kab. Ogan Komering Ulu Timur	6	t
108	Kab. Ogan Ilir	6	t
109	Kab. Empat Lawang	6	t
110	Kota Palembang	6	t
111	Kota Prabumulih	6	t
112	Kota Pagar Alam	6	t
113	Kota Lubuklinggau	6	t
114	Kab. Bengkulu Selatan	7	t
115	Kab. Rejang Lebong	7	t
116	Kab. Bengkulu Utara	7	t
117	Kab. Kaur	7	t
118	Kab. Seluma	7	t
119	Kab. Mukomuko	7	t
120	Kab. Lebong	7	t
121	Kab. Kepahiang	7	t
122	Kab. Bengkulu Tengah	7	t
123	Kota Bengkulu	7	t
124	Kab. Lampung Barat	8	t
125	Kab. Tanggamus	8	t
126	Kab. Lampung Selatan	8	t
127	Kab. Lampung Timur	8	t
128	Kab. Lampung Tengah	8	t
129	Kab. Lampung Utara	8	t
130	Kab. Way Kanan	8	t
131	Kab. Tulangbawang	8	t
132	Kab. Pesawaran	8	t
133	Kab. Pringsewu	8	t
134	Kab. Mesuji	8	t
135	Kab. Tulang Bawang Barat	8	t
136	Kab. Pesisir Barat	8	t
137	Kota Bandar Lampung	8	t
138	Kota Metro	8	t
139	Kab. Bangka	9	t
140	Kab. Belitung	9	t
141	Kab. Bangka Barat	9	t
142	Kab. Bangka Tengah	9	t
143	Kab. Bangka Selatan	9	t
144	Kab. Belitung Timur	9	t
145	Kota Pangkal Pinang	9	t
146	Kab. Karimun	10	t
147	Kab. Bintan	10	t
148	Kab. Natuna	10	t
149	Kab. Lingga	10	t
150	Kab. Kepulauan Anambas	10	t
151	Kota B A T A M	10	t
152	Kota Tanjung Pinang	10	t
153	Kab. Kepulauan Seribu	11	t
154	Kota Jakarta Selatan	11	t
155	Kota Jakarta Timur	11	t
156	Kota Jakarta Pusat	11	t
157	Kota Jakarta Barat	11	t
158	Kota Jakarta Utara	11	t
159	Kab. Bogor	12	t
160	Kab. Sukabumi	12	t
161	Kab. Cianjur	12	t
162	Kab. Bandung	12	t
163	Kab. Garut	12	t
164	Kab. Tasikmalaya	12	t
165	Kab. Ciamis	12	t
166	Kab. Kuningan	12	t
167	Kab. Cirebon	12	t
168	Kab. Majalengka	12	t
169	Kab. Sumedang	12	t
170	Kab. Indramayu	12	t
171	Kab. Subang	12	t
172	Kab. Purwakarta	12	t
173	Kab. Karawang	12	t
174	Kab. Bekasi	12	t
175	Kab. Bandung Barat	12	t
176	Kab. Pangandaran	12	t
177	Kota Bogor	12	t
178	Kota Sukabumi	12	t
179	Kota Bandung	12	t
180	Kota Cirebon	12	t
181	Kota Bekasi	12	t
182	Kota Depok	12	t
183	Kota Cimahi	12	t
184	Kota Tasikmalaya	12	t
185	Kota Banjar	12	t
186	Kab. Cilacap	13	t
187	Kab. Banyumas	13	t
188	Kab. Purbalingga	13	t
189	Kab. Banjarnegara	13	t
190	Kab. Kebumen	13	t
191	Kab. Purworejo	13	t
192	Kab. Wonosobo	13	t
193	Kab. Magelang	13	t
194	Kab. Boyolali	13	t
195	Kab. Klaten	13	t
196	Kab. Sukoharjo	13	t
197	Kab. Wonogiri	13	t
198	Kab. Karanganyar	13	t
199	Kab. Sragen	13	t
200	Kab. Grobogan	13	t
201	Kab. Blora	13	t
202	Kab. Rembang	13	t
203	Kab. Pati	13	t
204	Kab. Kudus	13	t
205	Kab. Jepara	13	t
206	Kab. Demak	13	t
207	Kab. Semarang	13	t
208	Kab. Temanggung	13	t
209	Kab. Kendal	13	t
210	Kab. Batang	13	t
211	Kab. Pekalongan	13	t
212	Kab. Pemalang	13	t
213	Kab. Tegal	13	t
214	Kab. Brebes	13	t
215	Kota Magelang	13	t
216	Kota Surakarta	13	t
217	Kota Salatiga	13	t
218	Kota Semarang	13	t
219	Kota Pekalongan	13	t
220	Kota Tegal	13	t
221	Kab. Kulon Progo	14	t
222	Kab. Bantul	14	t
223	Kab. Gunung Kidul	14	t
224	Kab. Sleman	14	t
225	Kota Yogyakarta	14	t
226	Kab. Pacitan	15	t
227	Kab. Ponorogo	15	t
228	Kab. Trenggalek	15	t
229	Kab. Tulungagung	15	t
230	Kab. Blitar	15	t
231	Kab. Kediri	15	t
232	Kab. Malang	15	t
233	Kab. Lumajang	15	t
234	Kab. Jember	15	t
235	Kab. Banyuwangi	15	t
236	Kab. Bondowoso	15	t
237	Kab. Situbondo	15	t
238	Kab. Probolinggo	15	t
239	Kab. Pasuruan	15	t
240	Kab. Sidoarjo	15	t
241	Kab. Mojokerto	15	t
242	Kab. Jombang	15	t
243	Kab. Nganjuk	15	t
244	Kab. Madiun	15	t
245	Kab. Magetan	15	t
246	Kab. Ngawi	15	t
247	Kab. Bojonegoro	15	t
248	Kab. Tuban	15	t
249	Kab. Lamongan	15	t
250	Kab. Gresik	15	t
251	Kab. Bangkalan	15	t
252	Kab. Sampang	15	t
253	Kab. Pamekasan	15	t
254	Kab. Sumenep	15	t
255	Kota Kediri	15	t
256	Kota Blitar	15	t
257	Kota Malang	15	t
258	Kota Probolinggo	15	t
259	Kota Pasuruan	15	t
260	Kota Mojokerto	15	t
261	Kota Madiun	15	t
262	Kota Surabaya	15	t
263	Kota Batu	15	t
264	Kab. Pandeglang	16	t
265	Kab. Lebak	16	t
266	Kab. Tangerang	16	t
267	Kab. Serang	16	t
268	Kota Tangerang	16	t
269	Kota Cilegon	16	t
270	Kota Serang	16	t
271	Kota Tangerang Selatan	16	t
272	Kab. Jembrana	17	t
273	Kab. Tabanan	17	t
274	Kab. Badung	17	t
275	Kab. Gianyar	17	t
276	Kab. Klungkung	17	t
277	Kab. Bangli	17	t
278	Kab. Karang Asem	17	t
279	Kab. Buleleng	17	t
280	Kota Denpasar	17	t
281	Kab. Lombok Barat	18	t
282	Kab. Lombok Tengah	18	t
283	Kab. Lombok Timur	18	t
284	Kab. Sumbawa	18	t
285	Kab. Dompu	18	t
286	Kab. Bima	18	t
287	Kab. Sumbawa Barat	18	t
288	Kab. Lombok Utara	18	t
289	Kota Mataram	18	t
290	Kota Bima	18	t
291	Kab. Sumba Barat	19	t
292	Kab. Sumba Timur	19	t
293	Kab. Kupang	19	t
294	Kab. Timor Tengah Selatan	19	t
295	Kab. Timor Tengah Utara	19	t
296	Kab. Belu	19	t
297	Kab. Alor	19	t
298	Kab. Lembata	19	t
299	Kab. Flores Timur	19	t
300	Kab. Sikka	19	t
301	Kab. Ende	19	t
302	Kab. Ngada	19	t
303	Kab. Manggarai	19	t
304	Kab. Rote Ndao	19	t
305	Kab. Manggarai Barat	19	t
306	Kab. Sumba Tengah	19	t
307	Kab. Sumba Barat Daya	19	t
308	Kab. Nagekeo	19	t
309	Kab. Manggarai Timur	19	t
310	Kab. Sabu Raijua	19	t
311	Kota Kupang	19	t
312	Kab. Sambas	20	t
313	Kab. Bengkayang	20	t
314	Kab. Landak	20	t
315	Kab. Pontianak	20	t
316	Kab. Sanggau	20	t
317	Kab. Ketapang	20	t
318	Kab. Sintang	20	t
319	Kab. Kapuas Hulu	20	t
320	Kab. Sekadau	20	t
321	Kab. Melawi	20	t
322	Kab. Kayong Utara	20	t
323	Kab. Kubu Raya	20	t
324	Kota Pontianak	20	t
325	Kota Singkawang	20	t
326	Kab. Kotawaringin Barat	21	t
327	Kab. Kotawaringin Timur	21	t
328	Kab. Kapuas	21	t
329	Kab. Barito Selatan	21	t
330	Kab. Barito Utara	21	t
331	Kab. Sukamara	21	t
332	Kab. Lamandau	21	t
333	Kab. Seruyan	21	t
334	Kab. Katingan	21	t
335	Kab. Pulang Pisau	21	t
336	Kab. Gunung Mas	21	t
337	Kab. Barito Timur	21	t
338	Kab. Murung Raya	21	t
339	Kota Palangka Raya	21	t
340	Kab. Tanah Laut	22	t
341	Kab. Kota Baru	22	t
342	Kab. Banjar	22	t
343	Kab. Barito Kuala	22	t
344	Kab. Tapin	22	t
345	Kab. Hulu Sungai Selatan	22	t
346	Kab. Hulu Sungai Tengah	22	t
347	Kab. Hulu Sungai Utara	22	t
348	Kab. Tabalong	22	t
349	Kab. Tanah Bumbu	22	t
350	Kab. Balangan	22	t
351	Kota Banjarmasin	22	t
352	Kota Banjar Baru	22	t
353	Kab. Paser	23	t
354	Kab. Kutai Barat	23	t
355	Kab. Kutai Kartanegara	23	t
356	Kab. Kutai Timur	23	t
357	Kab. Berau	23	t
358	Kab. Penajam Paser Utara	23	t
359	Kota Balikpapan	23	t
360	Kota Samarinda	23	t
361	Kota Bontang	23	t
362	Kab. Malinau	24	t
363	Kab. Bulungan	24	t
364	Kab. Tana Tidung	24	t
365	Kab. Nunukan	24	t
366	Kota Tarakan	24	t
367	Kab. Bolaang Mongondow	25	t
368	Kab. Minahasa	25	t
369	Kab. Kepulauan Sangihe	25	t
370	Kab. Kepulauan Talaud	25	t
371	Kab. Minahasa Selatan	25	t
372	Kab. Minahasa Utara	25	t
373	Kab. Bolaang Mongondow Utara	25	t
374	Kab. Siau Tagulandang Biaro	25	t
375	Kab. Minahasa Tenggara	25	t
376	Kab. Bolaang Mongondow Selatan	25	t
377	Kab. Bolaang Mongondow Timur	25	t
378	Kota Manado	25	t
379	Kota Bitung	25	t
380	Kota Tomohon	25	t
381	Kota Kotamobagu	25	t
382	Kab. Banggai Kepulauan	26	t
383	Kab. Banggai	26	t
384	Kab. Morowali	26	t
385	Kab. Poso	26	t
386	Kab. Donggala	26	t
387	Kab. Toli-toli	26	t
388	Kab. Buol	26	t
389	Kab. Parigi Moutong	26	t
390	Kab. Tojo Una-una	26	t
391	Kab. Sigi	26	t
392	Kota Palu	26	t
393	Kab. Kepulauan Selayar	27	t
394	Kab. Bulukumba	27	t
395	Kab. Bantaeng	27	t
396	Kab. Jeneponto	27	t
397	Kab. Takalar	27	t
398	Kab. Gowa	27	t
399	Kab. Sinjai	27	t
400	Kab. Maros	27	t
401	Kab. Pangkajene Dan Kepulauan	27	t
402	Kab. Barru	27	t
403	Kab. Bone	27	t
404	Kab. Soppeng	27	t
405	Kab. Wajo	27	t
406	Kab. Sidenreng Rappang	27	t
407	Kab. Pinrang	27	t
408	Kab. Enrekang	27	t
409	Kab. Luwu	27	t
410	Kab. Tana Toraja	27	t
411	Kab. Luwu Utara	27	t
412	Kab. Luwu Timur	27	t
413	Kab. Toraja Utara	27	t
414	Kota Makassar	27	t
415	Kota Parepare	27	t
416	Kota Palopo	27	t
417	Kab. Buton	28	t
418	Kab. Muna	28	t
419	Kab. Konawe	28	t
420	Kab. Kolaka	28	t
421	Kab. Konawe Selatan	28	t
422	Kab. Bombana	28	t
423	Kab. Wakatobi	28	t
424	Kab. Kolaka Utara	28	t
425	Kab. Buton Utara	28	t
426	Kab. Konawe Utara	28	t
427	Kota Kendari	28	t
428	Kota Baubau	28	t
429	Kab. Boalemo	29	t
430	Kab. Gorontalo	29	t
431	Kab. Pohuwato	29	t
432	Kab. Bone Bolango	29	t
433	Kab. Gorontalo Utara	29	t
434	Kota Gorontalo	29	t
435	Kab. Majene	30	t
436	Kab. Polewali Mandar	30	t
437	Kab. Mamasa	30	t
438	Kab. Mamuju	30	t
439	Kab. Mamuju Utara	30	t
440	Kab. Maluku Tenggara Barat	31	t
441	Kab. Maluku Tenggara	31	t
442	Kab. Maluku Tengah	31	t
443	Kab. Buru	31	t
444	Kab. Kepulauan Aru	31	t
445	Kab. Seram Bagian Barat	31	t
446	Kab. Seram Bagian Timur	31	t
447	Kab. Maluku Barat Daya	31	t
448	Kab. Buru Selatan	31	t
449	Kota Ambon	31	t
450	Kota Tual	31	t
451	Kab. Halmahera Barat	32	t
452	Kab. Halmahera Tengah	32	t
453	Kab. Kepulauan Sula	32	t
454	Kab. Halmahera Selatan	32	t
455	Kab. Halmahera Utara	32	t
456	Kab. Halmahera Timur	32	t
457	Kab. Pulau Morotai	32	t
458	Kota Ternate	32	t
459	Kota Tidore Kepulauan	32	t
460	Kab. Fakfak	33	t
461	Kab. Kaimana	33	t
462	Kab. Teluk Wondama	33	t
463	Kab. Teluk Bintuni	33	t
464	Kab. Manokwari	33	t
465	Kab. Sorong Selatan	33	t
466	Kab. Sorong	33	t
467	Kab. Raja Ampat	33	t
468	Kab. Tambrauw	33	t
469	Kab. Maybrat	33	t
470	Kota Sorong	33	t
471	Kab. Merauke	34	t
472	Kab. Jayawijaya	34	t
473	Kab. Jayapura	34	t
474	Kab. Nabire	34	t
475	Kab. Kepulauan Yapen	34	t
476	Kab. Biak Numfor	34	t
477	Kab. Paniai	34	t
478	Kab. Puncak Jaya	34	t
479	Kab. Mimika	34	t
480	Kab. Boven Digoel	34	t
481	Kab. Mappi	34	t
482	Kab. Asmat	34	t
483	Kab. Yahukimo	34	t
484	Kab. Pegunungan Bintang	34	t
485	Kab. Tolikara	34	t
486	Kab. Sarmi	34	t
487	Kab. Keerom	34	t
488	Kab. Waropen	34	t
489	Kab. Supiori	34	t
490	Kab. Mamberamo Raya	34	t
491	Kab. Nduga	34	t
492	Kab. Lanny Jaya	34	t
493	Kab. Mamberamo Tengah	34	t
494	Kab. Yalimo	34	t
495	Kab. Puncak	34	t
496	Kab. Dogiyai	34	t
497	Kab. Intan Jaya	34	t
498	Kab. Deiyai	34	t
499	Kota Jayapura	34	t
\.


--
-- Name: cities_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.cities_id_seq', 1, false);


--
-- Name: cities cities_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cities
    ADD CONSTRAINT cities_pkey PRIMARY KEY (id);


--
-- Name: cities cities_province_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cities
    ADD CONSTRAINT cities_province_id_foreign FOREIGN KEY (province_id) REFERENCES public.provinces(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

