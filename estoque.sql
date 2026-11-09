-- Banco do sistema de estoque com dados de exemplo.
-- Alternativa ao "php artisan migrate:fresh --seed": crie um banco vazio e rode
--   psql -U postgres -h 127.0.0.1 -d estoque -f estoque.sql

--
-- PostgreSQL database dump
--


-- Dumped from database version 16.15 (Ubuntu 16.15-0ubuntu0.24.04.1)
-- Dumped by pg_dump version 16.15 (Ubuntu 16.15-0ubuntu0.24.04.1)

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
-- Name: cache; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration bigint NOT NULL
);


--
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration bigint NOT NULL
);


--
-- Name: categoria; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.categoria (
    id bigint NOT NULL,
    descricao character varying(255) NOT NULL,
    margem_lucro numeric(5,2) DEFAULT '0'::numeric NOT NULL
);


--
-- Name: categoria_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.categoria_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: categoria_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.categoria_id_seq OWNED BY public.categoria.id;


--
-- Name: cliente; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cliente (
    id bigint NOT NULL,
    nome character varying(255) NOT NULL,
    endereco character varying(255),
    telefone character varying(255),
    email character varying(255) NOT NULL,
    foto character varying(255) DEFAULT ''::character varying NOT NULL
);


--
-- Name: cliente_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.cliente_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: cliente_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.cliente_id_seq OWNED BY public.cliente.id;


--
-- Name: compra; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.compra (
    id bigint NOT NULL,
    fornecedor_id bigint NOT NULL,
    data date NOT NULL,
    documento character varying(255) NOT NULL,
    valor numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    orcamento_compra_id bigint
);


--
-- Name: compra_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.compra_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: compra_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.compra_id_seq OWNED BY public.compra.id;


--
-- Name: compra_item; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.compra_item (
    id bigint NOT NULL,
    compra_id bigint NOT NULL,
    produto_id bigint NOT NULL,
    quantidade integer NOT NULL,
    valor numeric(10,2) NOT NULL
);


--
-- Name: compra_item_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.compra_item_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: compra_item_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.compra_item_id_seq OWNED BY public.compra_item.id;


--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection character varying(255) NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: fornecedor; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.fornecedor (
    id bigint NOT NULL,
    razao_social character varying(255) NOT NULL,
    nome_fantasia character varying(255),
    endereco character varying(255),
    fone character varying(255),
    email character varying(255),
    cnpj character varying(255)
);


--
-- Name: fornecedor_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.fornecedor_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: fornecedor_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.fornecedor_id_seq OWNED BY public.fornecedor.id;


--
-- Name: job_batches; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


--
-- Name: jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: orcamento_compra; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.orcamento_compra (
    id bigint NOT NULL,
    fornecedor_id bigint NOT NULL,
    data date NOT NULL,
    documento character varying(255) NOT NULL,
    valor numeric(10,2) DEFAULT '0'::numeric NOT NULL
);


--
-- Name: orcamento_compra_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.orcamento_compra_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: orcamento_compra_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.orcamento_compra_id_seq OWNED BY public.orcamento_compra.id;


--
-- Name: orcamento_compra_item; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.orcamento_compra_item (
    id bigint NOT NULL,
    orcamento_compra_id bigint NOT NULL,
    produto_id bigint NOT NULL,
    quantidade integer NOT NULL,
    valor numeric(10,2) NOT NULL
);


--
-- Name: orcamento_compra_item_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.orcamento_compra_item_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: orcamento_compra_item_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.orcamento_compra_item_id_seq OWNED BY public.orcamento_compra_item.id;


--
-- Name: orcamento_venda; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.orcamento_venda (
    id bigint NOT NULL,
    cliente_id bigint NOT NULL,
    data date NOT NULL,
    documento character varying(255) NOT NULL,
    valor numeric(10,2) DEFAULT '0'::numeric NOT NULL
);


--
-- Name: orcamento_venda_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.orcamento_venda_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: orcamento_venda_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.orcamento_venda_id_seq OWNED BY public.orcamento_venda.id;


--
-- Name: orcamento_venda_item; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.orcamento_venda_item (
    id bigint NOT NULL,
    orcamento_venda_id bigint NOT NULL,
    produto_id bigint NOT NULL,
    quantidade integer NOT NULL,
    valor numeric(10,2) NOT NULL
);


--
-- Name: orcamento_venda_item_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.orcamento_venda_item_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: orcamento_venda_item_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.orcamento_venda_item_id_seq OWNED BY public.orcamento_venda_item.id;


--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


--
-- Name: produto; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.produto (
    id bigint NOT NULL,
    nome character varying(255) NOT NULL,
    embalagem character varying(255),
    codigo_barra character varying(255),
    qtde_minima integer DEFAULT 0 NOT NULL,
    qtde_maxima integer DEFAULT 0 NOT NULL,
    quantidade_estoque integer DEFAULT 0 NOT NULL,
    valor_compra numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    valor_venda numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    categoria_id bigint
);


--
-- Name: produto_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.produto_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: produto_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.produto_id_seq OWNED BY public.produto.id;


--
-- Name: sessions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


--
-- Name: users; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: venda; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.venda (
    id bigint NOT NULL,
    cliente_id bigint NOT NULL,
    data date NOT NULL,
    documento character varying(255) NOT NULL,
    valor numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    orcamento_venda_id bigint
);


--
-- Name: venda_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.venda_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: venda_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.venda_id_seq OWNED BY public.venda.id;


--
-- Name: venda_item; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.venda_item (
    id bigint NOT NULL,
    venda_id bigint NOT NULL,
    produto_id bigint NOT NULL,
    quantidade integer NOT NULL,
    valor numeric(10,2) NOT NULL
);


--
-- Name: venda_item_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.venda_item_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: venda_item_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.venda_item_id_seq OWNED BY public.venda_item.id;


--
-- Name: categoria id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.categoria ALTER COLUMN id SET DEFAULT nextval('public.categoria_id_seq'::regclass);


--
-- Name: cliente id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cliente ALTER COLUMN id SET DEFAULT nextval('public.cliente_id_seq'::regclass);


--
-- Name: compra id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.compra ALTER COLUMN id SET DEFAULT nextval('public.compra_id_seq'::regclass);


--
-- Name: compra_item id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.compra_item ALTER COLUMN id SET DEFAULT nextval('public.compra_item_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: fornecedor id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fornecedor ALTER COLUMN id SET DEFAULT nextval('public.fornecedor_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: orcamento_compra id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.orcamento_compra ALTER COLUMN id SET DEFAULT nextval('public.orcamento_compra_id_seq'::regclass);


--
-- Name: orcamento_compra_item id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.orcamento_compra_item ALTER COLUMN id SET DEFAULT nextval('public.orcamento_compra_item_id_seq'::regclass);


--
-- Name: orcamento_venda id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.orcamento_venda ALTER COLUMN id SET DEFAULT nextval('public.orcamento_venda_id_seq'::regclass);


--
-- Name: orcamento_venda_item id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.orcamento_venda_item ALTER COLUMN id SET DEFAULT nextval('public.orcamento_venda_item_id_seq'::regclass);


--
-- Name: produto id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.produto ALTER COLUMN id SET DEFAULT nextval('public.produto_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Name: venda id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.venda ALTER COLUMN id SET DEFAULT nextval('public.venda_id_seq'::regclass);


--
-- Name: venda_item id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.venda_item ALTER COLUMN id SET DEFAULT nextval('public.venda_item_id_seq'::regclass);


--
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.cache (key, value, expiration) FROM stdin;
\.


--
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- Data for Name: categoria; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.categoria (id, descricao, margem_lucro) FROM stdin;
1	Alimentos	40.00
2	Bebidas	60.00
3	Limpeza	80.00
4	Higiene	70.00
5	Eletrônicos	30.00
\.


--
-- Data for Name: cliente; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.cliente (id, nome, endereco, telefone, email, foto) FROM stdin;
1	Anderson Silva	Rua Rui Barbosa, 123	(67)9999-8888	anderson@gmail.com	
2	Bianca Souza	Av. Afonso Pena, 456	(67)9988-7766	bianca@gmail.com	
3	Carlos Alberto	Rua Ceará, 333	(67)9977-5543	carlos@gmail.com	
\.


--
-- Data for Name: compra; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.compra (id, fornecedor_id, data, documento, valor, orcamento_compra_id) FROM stdin;
\.


--
-- Data for Name: compra_item; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.compra_item (id, compra_id, produto_id, quantidade, valor) FROM stdin;
\.


--
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- Data for Name: fornecedor; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.fornecedor (id, razao_social, nome_fantasia, endereco, fone, email, cnpj) FROM stdin;
1	Distribuidora ABC Ltda	ABC Distribuidora	Av. Principal, 100	(67)3333-1111	contato@abc.com.br	12.345.678/0001-99
2	Comércio XYZ S/A	XYZ Comércio	Rua Central, 200	(67)3333-2222	vendas@xyz.com.br	98.765.432/0001-11
\.


--
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
\.


--
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_users_table	1
2	0001_01_01_000001_create_cache_table	1
3	0001_01_01_000002_create_jobs_table	1
4	2026_10_26_000001_create_categoria	1
5	2026_10_26_000002_create_cliente	1
6	2026_10_26_000003_create_fornecedor	1
7	2026_10_26_000004_create_produto	1
8	2026_10_26_000005_create_orcamento_compra	1
9	2026_10_26_000006_create_orcamento_compra_item	1
10	2026_10_26_000007_create_compra	1
11	2026_10_26_000008_create_compra_item	1
12	2026_10_26_000009_create_orcamento_venda	1
13	2026_10_26_000010_create_orcamento_venda_item	1
14	2026_10_26_000011_create_venda	1
15	2026_10_26_000012_create_venda_item	1
\.


--
-- Data for Name: orcamento_compra; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.orcamento_compra (id, fornecedor_id, data, documento, valor) FROM stdin;
1	1	2026-10-26	OC-001	267.50
\.


--
-- Data for Name: orcamento_compra_item; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.orcamento_compra_item (id, orcamento_compra_id, produto_id, quantidade, valor) FROM stdin;
1	1	1	10	18.00
2	1	5	50	1.75
\.


--
-- Data for Name: orcamento_venda; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.orcamento_venda (id, cliente_id, data, documento, valor) FROM stdin;
1	2	2026-10-26	OV-001	103.30
\.


--
-- Data for Name: orcamento_venda_item; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.orcamento_venda_item (id, orcamento_venda_id, produto_id, quantidade, valor) FROM stdin;
1	1	3	6	8.90
2	1	6	1	49.90
\.


--
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- Data for Name: produto; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.produto (id, nome, embalagem, codigo_barra, qtde_minima, qtde_maxima, quantidade_estoque, valor_compra, valor_venda, categoria_id) FROM stdin;
1	Arroz 5kg	Pacote	7891000000011	20	200	100	18.50	29.90	1
2	Feijão 1kg	Pacote	7891000000028	20	150	80	7.80	12.90	1
3	Refrigerante 2L	Garrafa	7891000000035	30	120	60	5.00	8.90	2
4	Detergente 500ml	Frasco	7891000000042	15	100	50	2.50	4.90	3
5	Sabonete 90g	Unidade	7891000000059	25	200	10	1.80	3.20	4
6	Fone de ouvido	Caixa	7891000000066	5	30	12	25.00	49.90	5
\.


--
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: venda; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.venda (id, cliente_id, data, documento, valor, orcamento_venda_id) FROM stdin;
\.


--
-- Data for Name: venda_item; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.venda_item (id, venda_id, produto_id, quantidade, valor) FROM stdin;
\.


--
-- Name: categoria_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.categoria_id_seq', 5, true);


--
-- Name: cliente_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.cliente_id_seq', 3, true);


--
-- Name: compra_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.compra_id_seq', 1, false);


--
-- Name: compra_item_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.compra_item_id_seq', 1, false);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Name: fornecedor_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.fornecedor_id_seq', 2, true);


--
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.migrations_id_seq', 15, true);


--
-- Name: orcamento_compra_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.orcamento_compra_id_seq', 1, true);


--
-- Name: orcamento_compra_item_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.orcamento_compra_item_id_seq', 2, true);


--
-- Name: orcamento_venda_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.orcamento_venda_id_seq', 1, true);


--
-- Name: orcamento_venda_item_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.orcamento_venda_item_id_seq', 2, true);


--
-- Name: produto_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.produto_id_seq', 6, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.users_id_seq', 1, false);


--
-- Name: venda_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.venda_id_seq', 1, false);


--
-- Name: venda_item_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.venda_item_id_seq', 1, false);


--
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- Name: categoria categoria_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.categoria
    ADD CONSTRAINT categoria_pkey PRIMARY KEY (id);


--
-- Name: cliente cliente_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cliente
    ADD CONSTRAINT cliente_pkey PRIMARY KEY (id);


--
-- Name: compra_item compra_item_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.compra_item
    ADD CONSTRAINT compra_item_pkey PRIMARY KEY (id);


--
-- Name: compra compra_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.compra
    ADD CONSTRAINT compra_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: fornecedor fornecedor_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fornecedor
    ADD CONSTRAINT fornecedor_pkey PRIMARY KEY (id);


--
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: orcamento_compra_item orcamento_compra_item_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.orcamento_compra_item
    ADD CONSTRAINT orcamento_compra_item_pkey PRIMARY KEY (id);


--
-- Name: orcamento_compra orcamento_compra_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.orcamento_compra
    ADD CONSTRAINT orcamento_compra_pkey PRIMARY KEY (id);


--
-- Name: orcamento_venda_item orcamento_venda_item_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.orcamento_venda_item
    ADD CONSTRAINT orcamento_venda_item_pkey PRIMARY KEY (id);


--
-- Name: orcamento_venda orcamento_venda_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.orcamento_venda
    ADD CONSTRAINT orcamento_venda_pkey PRIMARY KEY (id);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: produto produto_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.produto
    ADD CONSTRAINT produto_pkey PRIMARY KEY (id);


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: venda_item venda_item_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.venda_item
    ADD CONSTRAINT venda_item_pkey PRIMARY KEY (id);


--
-- Name: venda venda_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.venda
    ADD CONSTRAINT venda_pkey PRIMARY KEY (id);


--
-- Name: cache_expiration_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX cache_expiration_index ON public.cache USING btree (expiration);


--
-- Name: cache_locks_expiration_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX cache_locks_expiration_index ON public.cache_locks USING btree (expiration);


--
-- Name: failed_jobs_connection_queue_failed_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX failed_jobs_connection_queue_failed_at_index ON public.failed_jobs USING btree (connection, queue, failed_at);


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- Name: compra compra_fornecedor_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.compra
    ADD CONSTRAINT compra_fornecedor_id_foreign FOREIGN KEY (fornecedor_id) REFERENCES public.fornecedor(id);


--
-- Name: compra_item compra_item_compra_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.compra_item
    ADD CONSTRAINT compra_item_compra_id_foreign FOREIGN KEY (compra_id) REFERENCES public.compra(id) ON DELETE CASCADE;


--
-- Name: compra_item compra_item_produto_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.compra_item
    ADD CONSTRAINT compra_item_produto_id_foreign FOREIGN KEY (produto_id) REFERENCES public.produto(id);


--
-- Name: compra compra_orcamento_compra_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.compra
    ADD CONSTRAINT compra_orcamento_compra_id_foreign FOREIGN KEY (orcamento_compra_id) REFERENCES public.orcamento_compra(id) ON DELETE SET NULL;


--
-- Name: orcamento_compra orcamento_compra_fornecedor_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.orcamento_compra
    ADD CONSTRAINT orcamento_compra_fornecedor_id_foreign FOREIGN KEY (fornecedor_id) REFERENCES public.fornecedor(id);


--
-- Name: orcamento_compra_item orcamento_compra_item_orcamento_compra_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.orcamento_compra_item
    ADD CONSTRAINT orcamento_compra_item_orcamento_compra_id_foreign FOREIGN KEY (orcamento_compra_id) REFERENCES public.orcamento_compra(id) ON DELETE CASCADE;


--
-- Name: orcamento_compra_item orcamento_compra_item_produto_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.orcamento_compra_item
    ADD CONSTRAINT orcamento_compra_item_produto_id_foreign FOREIGN KEY (produto_id) REFERENCES public.produto(id);


--
-- Name: orcamento_venda orcamento_venda_cliente_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.orcamento_venda
    ADD CONSTRAINT orcamento_venda_cliente_id_foreign FOREIGN KEY (cliente_id) REFERENCES public.cliente(id);


--
-- Name: orcamento_venda_item orcamento_venda_item_orcamento_venda_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.orcamento_venda_item
    ADD CONSTRAINT orcamento_venda_item_orcamento_venda_id_foreign FOREIGN KEY (orcamento_venda_id) REFERENCES public.orcamento_venda(id) ON DELETE CASCADE;


--
-- Name: orcamento_venda_item orcamento_venda_item_produto_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.orcamento_venda_item
    ADD CONSTRAINT orcamento_venda_item_produto_id_foreign FOREIGN KEY (produto_id) REFERENCES public.produto(id);


--
-- Name: produto produto_categoria_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.produto
    ADD CONSTRAINT produto_categoria_id_foreign FOREIGN KEY (categoria_id) REFERENCES public.categoria(id) ON DELETE SET NULL;


--
-- Name: venda venda_cliente_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.venda
    ADD CONSTRAINT venda_cliente_id_foreign FOREIGN KEY (cliente_id) REFERENCES public.cliente(id);


--
-- Name: venda_item venda_item_produto_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.venda_item
    ADD CONSTRAINT venda_item_produto_id_foreign FOREIGN KEY (produto_id) REFERENCES public.produto(id);


--
-- Name: venda_item venda_item_venda_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.venda_item
    ADD CONSTRAINT venda_item_venda_id_foreign FOREIGN KEY (venda_id) REFERENCES public.venda(id) ON DELETE CASCADE;


--
-- Name: venda venda_orcamento_venda_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.venda
    ADD CONSTRAINT venda_orcamento_venda_id_foreign FOREIGN KEY (orcamento_venda_id) REFERENCES public.orcamento_venda(id) ON DELETE SET NULL;


--
-- PostgreSQL database dump complete
--


