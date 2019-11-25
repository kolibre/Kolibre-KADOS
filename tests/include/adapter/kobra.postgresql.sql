--
-- PostgreSQL database dump
--

-- Dumped from database version 11.3 (Debian 11.3-1.pgdg90+1)
-- Dumped by pg_dump version 11.5

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

SET default_with_oids = false;

--
-- Name: announcement_audios; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.announcement_audios (
    id bigint NOT NULL,
    announcement_text_id bigint,
    size integer NOT NULL,
    length integer NOT NULL,
    mime_type text NOT NULL,
    audio text NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.announcement_audios OWNER TO postgres;

--
-- Name: announcement_audios_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.announcement_audios_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.announcement_audios_id_seq OWNER TO postgres;

--
-- Name: announcement_audios_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.announcement_audios_id_seq OWNED BY public.announcement_audios.id;


--
-- Name: announcement_texts; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.announcement_texts (
    id bigint NOT NULL,
    announcement_id bigint,
    text text NOT NULL,
    language_id bigint,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.announcement_texts OWNER TO postgres;

--
-- Name: announcement_texts_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.announcement_texts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.announcement_texts_id_seq OWNER TO postgres;

--
-- Name: announcement_texts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.announcement_texts_id_seq OWNED BY public.announcement_texts.id;


--
-- Name: announcements; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.announcements (
    id bigint NOT NULL,
    category text,
    priority text NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.announcements OWNER TO postgres;

--
-- Name: announcements_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.announcements_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.announcements_id_seq OWNER TO postgres;

--
-- Name: announcements_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.announcements_id_seq OWNED BY public.announcements.id;


--
-- Name: ar_internal_metadata; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ar_internal_metadata (
    key character varying NOT NULL,
    value character varying,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.ar_internal_metadata OWNER TO postgres;

--
-- Name: categories; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.categories (
    id bigint NOT NULL,
    name text NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.categories OWNER TO postgres;

--
-- Name: categories_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.categories_id_seq OWNER TO postgres;

--
-- Name: categories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.categories_id_seq OWNED BY public.categories.id;


--
-- Name: content_audios; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.content_audios (
    id bigint NOT NULL,
    content_id bigint,
    size integer NOT NULL,
    length integer NOT NULL,
    mime_type text NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    audio character varying NOT NULL
);


ALTER TABLE public.content_audios OWNER TO postgres;

--
-- Name: content_audios_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.content_audios_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.content_audios_id_seq OWNER TO postgres;

--
-- Name: content_audios_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.content_audios_id_seq OWNED BY public.content_audios.id;


--
-- Name: content_lists; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.content_lists (
    id bigint NOT NULL,
    name text NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.content_lists OWNER TO postgres;

--
-- Name: content_lists_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.content_lists_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.content_lists_id_seq OWNER TO postgres;

--
-- Name: content_lists_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.content_lists_id_seq OWNED BY public.content_lists.id;


--
-- Name: content_metadata; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.content_metadata (
    id bigint NOT NULL,
    content_id bigint,
    key text NOT NULL,
    value text NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.content_metadata OWNER TO postgres;

--
-- Name: content_metadata_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.content_metadata_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.content_metadata_id_seq OWNER TO postgres;

--
-- Name: content_metadata_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.content_metadata_id_seq OWNED BY public.content_metadata.id;


--
-- Name: content_resources; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.content_resources (
    id bigint NOT NULL,
    content_id bigint,
    file_name text NOT NULL,
    bytes integer DEFAULT 0 NOT NULL,
    mime_type text NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    resource character varying NOT NULL
);


ALTER TABLE public.content_resources OWNER TO postgres;

--
-- Name: content_resources_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.content_resources_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.content_resources_id_seq OWNER TO postgres;

--
-- Name: content_resources_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.content_resources_id_seq OWNED BY public.content_resources.id;


--
-- Name: contents; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.contents (
    id bigint NOT NULL,
    category_id bigint,
    daisy_format_id bigint,
    title character varying(256) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.contents OWNER TO postgres;

--
-- Name: contents_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.contents_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.contents_id_seq OWNER TO postgres;

--
-- Name: contents_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.contents_id_seq OWNED BY public.contents.id;


--
-- Name: daisy_formats; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.daisy_formats (
    id bigint NOT NULL,
    format text NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.daisy_formats OWNER TO postgres;

--
-- Name: daisy_formats_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.daisy_formats_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.daisy_formats_id_seq OWNER TO postgres;

--
-- Name: daisy_formats_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.daisy_formats_id_seq OWNED BY public.daisy_formats.id;


--
-- Name: languages; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.languages (
    id bigint NOT NULL,
    lang text NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.languages OWNER TO postgres;

--
-- Name: languages_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.languages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.languages_id_seq OWNER TO postgres;

--
-- Name: languages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.languages_id_seq OWNED BY public.languages.id;


--
-- Name: question_audios; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.question_audios (
    id bigint NOT NULL,
    question_text_id bigint,
    size integer NOT NULL,
    length integer NOT NULL,
    mime_type text NOT NULL,
    audio text NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.question_audios OWNER TO postgres;

--
-- Name: question_audios_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.question_audios_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.question_audios_id_seq OWNER TO postgres;

--
-- Name: question_audios_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.question_audios_id_seq OWNED BY public.question_audios.id;


--
-- Name: question_inputs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.question_inputs (
    id bigint NOT NULL,
    question_id bigint,
    allow_multiple_selections integer,
    text_numeric integer,
    text_alphanumeric integer,
    audio integer,
    default_value text,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.question_inputs OWNER TO postgres;

--
-- Name: question_inputs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.question_inputs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.question_inputs_id_seq OWNER TO postgres;

--
-- Name: question_inputs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.question_inputs_id_seq OWNED BY public.question_inputs.id;


--
-- Name: question_question_texts; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.question_question_texts (
    id bigint NOT NULL,
    question_id bigint,
    question_text_id bigint,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.question_question_texts OWNER TO postgres;

--
-- Name: question_question_texts_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.question_question_texts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.question_question_texts_id_seq OWNER TO postgres;

--
-- Name: question_question_texts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.question_question_texts_id_seq OWNED BY public.question_question_texts.id;


--
-- Name: question_texts; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.question_texts (
    id bigint NOT NULL,
    language_id bigint,
    text text NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.question_texts OWNER TO postgres;

--
-- Name: question_texts_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.question_texts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.question_texts_id_seq OWNER TO postgres;

--
-- Name: question_texts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.question_texts_id_seq OWNED BY public.question_texts.id;


--
-- Name: question_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.question_types (
    id bigint NOT NULL,
    name text NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.question_types OWNER TO postgres;

--
-- Name: question_types_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.question_types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.question_types_id_seq OWNER TO postgres;

--
-- Name: question_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.question_types_id_seq OWNED BY public.question_types.id;


--
-- Name: questions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.questions (
    id bigint NOT NULL,
    parent_id bigint,
    question_type_id bigint,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.questions OWNER TO postgres;

--
-- Name: questions_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.questions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.questions_id_seq OWNER TO postgres;

--
-- Name: questions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.questions_id_seq OWNED BY public.questions.id;


--
-- Name: schema_migrations; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.schema_migrations (
    version character varying NOT NULL
);


ALTER TABLE public.schema_migrations OWNER TO postgres;

--
-- Name: states; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.states (
    id bigint NOT NULL,
    state text NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.states OWNER TO postgres;

--
-- Name: states_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.states_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.states_id_seq OWNER TO postgres;

--
-- Name: states_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.states_id_seq OWNED BY public.states.id;


--
-- Name: user_announcements; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.user_announcements (
    id bigint NOT NULL,
    user_id bigint,
    announcement_id bigint,
    read_at timestamp without time zone,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.user_announcements OWNER TO postgres;

--
-- Name: user_announcements_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.user_announcements_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.user_announcements_id_seq OWNER TO postgres;

--
-- Name: user_announcements_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.user_announcements_id_seq OWNED BY public.user_announcements.id;


--
-- Name: user_bookmarks; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.user_bookmarks (
    id bigint NOT NULL,
    user_id bigint,
    content_id bigint,
    bookmark_set text NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.user_bookmarks OWNER TO postgres;

--
-- Name: user_bookmarks_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.user_bookmarks_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.user_bookmarks_id_seq OWNER TO postgres;

--
-- Name: user_bookmarks_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.user_bookmarks_id_seq OWNED BY public.user_bookmarks.id;


--
-- Name: user_contents; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.user_contents (
    id bigint NOT NULL,
    user_id bigint,
    content_id bigint,
    content_list_id bigint DEFAULT 1,
    content_list_v1_id bigint DEFAULT 2,
    return boolean DEFAULT false NOT NULL,
    returned boolean DEFAULT false NOT NULL,
    return_at timestamp without time zone,
    state_id bigint DEFAULT 1,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.user_contents OWNER TO postgres;

--
-- Name: user_contents_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.user_contents_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.user_contents_id_seq OWNER TO postgres;

--
-- Name: user_contents_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.user_contents_id_seq OWNED BY public.user_contents.id;


--
-- Name: user_logs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.user_logs (
    id bigint NOT NULL,
    user_id bigint,
    ip text,
    request text,
    response text,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.user_logs OWNER TO postgres;

--
-- Name: user_logs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.user_logs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.user_logs_id_seq OWNER TO postgres;

--
-- Name: user_logs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.user_logs_id_seq OWNED BY public.user_logs.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    username text NOT NULL,
    password text,
    terms_accepted boolean DEFAULT false NOT NULL,
    log boolean DEFAULT false NOT NULL,
    activated boolean DEFAULT false NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.users OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: announcement_audios id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.announcement_audios ALTER COLUMN id SET DEFAULT nextval('public.announcement_audios_id_seq'::regclass);


--
-- Name: announcement_texts id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.announcement_texts ALTER COLUMN id SET DEFAULT nextval('public.announcement_texts_id_seq'::regclass);


--
-- Name: announcements id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.announcements ALTER COLUMN id SET DEFAULT nextval('public.announcements_id_seq'::regclass);


--
-- Name: categories id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categories ALTER COLUMN id SET DEFAULT nextval('public.categories_id_seq'::regclass);


--
-- Name: content_audios id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.content_audios ALTER COLUMN id SET DEFAULT nextval('public.content_audios_id_seq'::regclass);


--
-- Name: content_lists id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.content_lists ALTER COLUMN id SET DEFAULT nextval('public.content_lists_id_seq'::regclass);


--
-- Name: content_metadata id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.content_metadata ALTER COLUMN id SET DEFAULT nextval('public.content_metadata_id_seq'::regclass);


--
-- Name: content_resources id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.content_resources ALTER COLUMN id SET DEFAULT nextval('public.content_resources_id_seq'::regclass);


--
-- Name: contents id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contents ALTER COLUMN id SET DEFAULT nextval('public.contents_id_seq'::regclass);


--
-- Name: daisy_formats id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.daisy_formats ALTER COLUMN id SET DEFAULT nextval('public.daisy_formats_id_seq'::regclass);


--
-- Name: languages id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.languages ALTER COLUMN id SET DEFAULT nextval('public.languages_id_seq'::regclass);


--
-- Name: question_audios id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.question_audios ALTER COLUMN id SET DEFAULT nextval('public.question_audios_id_seq'::regclass);


--
-- Name: question_inputs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.question_inputs ALTER COLUMN id SET DEFAULT nextval('public.question_inputs_id_seq'::regclass);


--
-- Name: question_question_texts id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.question_question_texts ALTER COLUMN id SET DEFAULT nextval('public.question_question_texts_id_seq'::regclass);


--
-- Name: question_texts id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.question_texts ALTER COLUMN id SET DEFAULT nextval('public.question_texts_id_seq'::regclass);


--
-- Name: question_types id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.question_types ALTER COLUMN id SET DEFAULT nextval('public.question_types_id_seq'::regclass);


--
-- Name: questions id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.questions ALTER COLUMN id SET DEFAULT nextval('public.questions_id_seq'::regclass);


--
-- Name: states id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.states ALTER COLUMN id SET DEFAULT nextval('public.states_id_seq'::regclass);


--
-- Name: user_announcements id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_announcements ALTER COLUMN id SET DEFAULT nextval('public.user_announcements_id_seq'::regclass);


--
-- Name: user_bookmarks id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_bookmarks ALTER COLUMN id SET DEFAULT nextval('public.user_bookmarks_id_seq'::regclass);


--
-- Name: user_contents id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_contents ALTER COLUMN id SET DEFAULT nextval('public.user_contents_id_seq'::regclass);


--
-- Name: user_logs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_logs ALTER COLUMN id SET DEFAULT nextval('public.user_logs_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Data for Name: announcement_audios; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.announcement_audios (id, announcement_text_id, size, length, mime_type, audio, created_at, updated_at) FROM stdin;
1	1	24677	2594	audio/ogg	announcement_1.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
2	2	27392	2880	audio/ogg	announcement_2.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
3	3	53310	5680	audio/ogg	announcement_3.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
4	4	63226	6952	audio/ogg	announcement_4.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
\.


--
-- Data for Name: announcement_texts; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.announcement_texts (id, announcement_id, text, language_id, created_at, updated_at) FROM stdin;
1	1	Welcome to the Kolibre Daisy Online demo service.	1	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
2	1	Välkommen till Kolibres Daisy Online demo tjänst.	2	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
3	2	Feel free to use and explore this service. Remember to mark the announcements as read when you have read them.	1	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
4	2	Du kan fritt använda och utforska denna tjänst. Kom ihåg att markera meddelandena som lästa efter att du läst dem.	2	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
\.


--
-- Data for Name: announcements; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.announcements (id, category, priority, created_at, updated_at) FROM stdin;
1	INFORMATION	MEDIUM	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
2	INFORMATION	LOW	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
\.


--
-- Data for Name: ar_internal_metadata; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ar_internal_metadata (key, value, created_at, updated_at) FROM stdin;
environment	production	2019-11-22 05:39:11.827694	2019-11-22 05:39:11.827694
\.


--
-- Data for Name: categories; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.categories (id, name, created_at, updated_at) FROM stdin;
1	BOOK	2019-11-22 05:39:11.706311	2019-11-22 05:39:11.706311
2	MAGAZINE	2019-11-22 05:39:11.707667	2019-11-22 05:39:11.707667
3	NEWSPAPER	2019-11-22 05:39:11.70873	2019-11-22 05:39:11.70873
4	OTHER	2019-11-22 05:39:11.709688	2019-11-22 05:39:11.709688
\.


--
-- Data for Name: content_audios; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.content_audios (id, content_id, size, length, mime_type, created_at, updated_at, audio) FROM stdin;
1	1	44724	6315	audio/ogg	2019-11-22 05:39:21.089022	2019-11-22 05:39:21.089022	content_1.ogg
2	2	16139	5201	audio/ogg	2019-11-22 05:39:21.120144	2019-11-22 05:39:21.120144	content_2.ogg
3	3	20335	1945	audio/ogg	2019-11-22 05:39:21.151092	2019-11-22 05:39:21.151092	content_3.ogg
\.


--
-- Data for Name: content_lists; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.content_lists (id, name, created_at, updated_at) FROM stdin;
1	bookshelf	2019-11-22 05:39:11.762724	2019-11-22 05:39:11.762724
2	new	2019-11-22 05:39:11.764246	2019-11-22 05:39:11.764246
3	issued	2019-11-22 05:39:11.765272	2019-11-22 05:39:11.765272
4	expired	2019-11-22 05:39:11.766226	2019-11-22 05:39:11.766226
20	search	2019-11-22 05:39:11.767179	2019-11-22 05:39:11.767179
30	browse	2019-11-22 05:39:11.768333	2019-11-22 05:39:11.768333
\.


--
-- Data for Name: content_metadata; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.content_metadata (id, content_id, key, value, created_at, updated_at) FROM stdin;
1	1	dc:creator	Henry James	2019-11-22 05:39:19.024223	2019-11-22 05:39:19.024223
2	1	dc:date	2009-06-22	2019-11-22 05:39:19.054721	2019-11-22 05:39:19.054721
3	1	dc:format	Daisy 2.02	2019-11-22 05:39:19.08152	2019-11-22 05:39:19.08152
4	1	dc:identifier	Unknown	2019-11-22 05:39:19.110341	2019-11-22 05:39:19.110341
5	1	dc:language	en	2019-11-22 05:39:19.13659	2019-11-22 05:39:19.13659
6	1	dc:publisher	DC	2019-11-22 05:39:19.163197	2019-11-22 05:39:19.163197
7	1	dc:source	The galaxy vol. 8 no. 1 (July 1869),   pages 49-68	2019-11-22 05:39:19.193505	2019-11-22 05:39:19.193505
8	1	dc:subject	Relationships, betrayal	2019-11-22 05:39:19.22135	2019-11-22 05:39:19.22135
9	1	dc:title	A light Man	2019-11-22 05:39:19.249544	2019-11-22 05:39:19.249544
10	1	ncc:charset	utf-8	2019-11-22 05:39:19.279466	2019-11-22 05:39:19.279466
11	1	ncc:depth	1	2019-11-22 05:39:19.311064	2019-11-22 05:39:19.311064
12	1	ncc:files	36	2019-11-22 05:39:19.344446	2019-11-22 05:39:19.344446
13	1	ncc:footnotes	0	2019-11-22 05:39:19.376105	2019-11-22 05:39:19.376105
14	1	ncc:generator	EasePublisher 2.13 Build 163 # 044FS2212172434	2019-11-22 05:39:19.40812	2019-11-22 05:39:19.40812
15	1	ncc:kbytesize	35930	2019-11-22 05:39:19.437541	2019-11-22 05:39:19.437541
16	1	ncc:maxpagenormal	26	2019-11-22 05:39:19.470102	2019-11-22 05:39:19.470102
17	1	ncc:multimediatype	audioNcc	2019-11-22 05:39:19.497307	2019-11-22 05:39:19.497307
18	1	ncc:narrator	OM	2019-11-22 05:39:19.526687	2019-11-22 05:39:19.526687
19	1	ncc:pagefront	0	2019-11-22 05:39:19.562042	2019-11-22 05:39:19.562042
20	1	ncc:pagenormal	26	2019-11-22 05:39:19.607629	2019-11-22 05:39:19.607629
21	1	ncc:pagespecial	0	2019-11-22 05:39:19.63361	2019-11-22 05:39:19.63361
22	1	ncc:prodnotes	0	2019-11-22 05:39:19.661267	2019-11-22 05:39:19.661267
23	1	ncc:setinfo	1 of 1	2019-11-22 05:39:19.68882	2019-11-22 05:39:19.68882
24	1	ncc:sidebars	0	2019-11-22 05:39:19.71524	2019-11-22 05:39:19.71524
25	1	ncc:sourcedate	2009-07-18	2019-11-22 05:39:19.749072	2019-11-22 05:39:19.749072
26	1	ncc:sourceedition	1.	2019-11-22 05:39:19.790074	2019-11-22 05:39:19.790074
27	1	ncc:sourcepublisher	The galaxy vol. 8 no. 1 (July 1869),   pages 49-68	2019-11-22 05:39:19.817979	2019-11-22 05:39:19.817979
28	1	ncc:sourcerights	Gutenberg project	2019-11-22 05:39:19.84975	2019-11-22 05:39:19.84975
29	1	ncc:sourcetitle	A light man	2019-11-22 05:39:19.880266	2019-11-22 05:39:19.880266
30	1	ncc:tocitems	43	2019-11-22 05:39:19.913065	2019-11-22 05:39:19.913065
31	1	ncc:totaltime	01:27:15	2019-11-22 05:39:19.946279	2019-11-22 05:39:19.946279
32	2	dc:creator	WikiHow	2019-11-22 05:39:20.035043	2019-11-22 05:39:20.035043
33	2	dc:date	2009-04-26	2019-11-22 05:39:20.062418	2019-11-22 05:39:20.062418
34	2	dc:format	ANSI/NIZO Z39.86-2005	2019-11-22 05:39:20.097301	2019-11-22 05:39:20.097301
35	2	dc:identifier	5078727220897727718	2019-11-22 05:39:20.132108	2019-11-22 05:39:20.132108
36	2	dc:language	en-IN	2019-11-22 05:39:20.16045	2019-11-22 05:39:20.16045
37	2	dc:publisher	DAISY India	2019-11-22 05:39:20.192855	2019-11-22 05:39:20.192855
38	2	dc:subject	A Short Manual for Disaster Management	2019-11-22 05:39:20.221251	2019-11-22 05:39:20.221251
39	2	dc:title	ARE YOU READY?	2019-11-22 05:39:20.245567	2019-11-22 05:39:20.245567
40	3	dc:title	Climbing the Highest Mountain	2019-11-22 05:39:20.276679	2019-11-22 05:39:20.276679
41	3	dc:creator	Per Sennels	2019-11-22 05:39:20.310431	2019-11-22 05:39:20.310431
42	3	dc:date	2003-05-08	2019-11-22 05:39:20.34014	2019-11-22 05:39:20.34014
43	3	dc:format	Daisy 2.02	2019-11-22 05:39:20.36911	2019-11-22 05:39:20.36911
44	3	dc:identifier	skipdemo-amsterdam	2019-11-22 05:39:20.399217	2019-11-22 05:39:20.399217
45	3	dc:language	en	2019-11-22 05:39:20.429004	2019-11-22 05:39:20.429004
46	3	dc:publisher	DAISY Consortium	2019-11-22 05:39:20.455047	2019-11-22 05:39:20.455047
47	3	dc:subject	Mountains	2019-11-22 05:39:20.487511	2019-11-22 05:39:20.487511
48	3	ncc:pagefront	0	2019-11-22 05:39:20.51782	2019-11-22 05:39:20.51782
49	3	ncc:pagenormal	8	2019-11-22 05:39:20.548006	2019-11-22 05:39:20.548006
50	3	ncc:pagespecial	0	2019-11-22 05:39:20.583188	2019-11-22 05:39:20.583188
51	3	ncc:setinfo	1 of 1	2019-11-22 05:39:20.612758	2019-11-22 05:39:20.612758
52	3	ncc:depth	3	2019-11-22 05:39:20.639625	2019-11-22 05:39:20.639625
53	3	ncc:maxpagenormal	8	2019-11-22 05:39:20.665994	2019-11-22 05:39:20.665994
54	3	ncc:multimediatype	audioNcc	2019-11-22 05:39:20.703604	2019-11-22 05:39:20.703604
55	3	ncc:narrator	Joan Dahm-Simonsen	2019-11-22 05:39:20.730968	2019-11-22 05:39:20.730968
56	3	ncc:producer	Huseby kompetansesenter	2019-11-22 05:39:20.759756	2019-11-22 05:39:20.759756
57	3	ncc:generator	Skippability Tweaker 0.1.53	2019-11-22 05:39:20.791626	2019-11-22 05:39:20.791626
58	3	ncc:sidebars	4	2019-11-22 05:39:20.818834	2019-11-22 05:39:20.818834
59	3	ncc:prodnotes	2	2019-11-22 05:39:20.848051	2019-11-22 05:39:20.848051
60	3	ncc:footnotes	1	2019-11-22 05:39:20.878251	2019-11-22 05:39:20.878251
61	3	ncc:totaltime	00:05:27	2019-11-22 05:39:20.909877	2019-11-22 05:39:20.909877
62	3	ncc:charset	windows-1252	2019-11-22 05:39:20.940995	2019-11-22 05:39:20.940995
63	3	ncc:files	20	2019-11-22 05:39:20.973435	2019-11-22 05:39:20.973435
64	3	ncc:kbytesize	1975	2019-11-22 05:39:21.000827	2019-11-22 05:39:21.000827
65	3	ncc:tocitems	23	2019-11-22 05:39:21.029932	2019-11-22 05:39:21.029932
\.


--
-- Data for Name: content_resources; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.content_resources (id, content_id, file_name, bytes, mime_type, created_at, updated_at, resource) FROM stdin;
1	1	01_A_light_Man.mp3	45531	audio/mpeg	2019-11-22 05:39:21.195311	2019-11-22 05:39:21.195311	01_A_light_Man.mp3
2	1	02_Epigraph.mp3	176822	audio/mpeg	2019-11-22 05:39:21.234125	2019-11-22 05:39:21.234125	02_Epigraph.mp3
3	1	03_April_4th_1857.mp3	1540205	audio/mpeg	2019-11-22 05:39:21.317594	2019-11-22 05:39:21.317594	03_April_4th_1857.mp3
4	1	04_7th_.mp3	1204845	audio/mpeg	2019-11-22 05:39:21.403613	2019-11-22 05:39:21.403613	04_7th_.mp3
5	1	05_D_the_14th_.mp3	8934948	audio/mpeg	2019-11-22 05:39:21.693496	2019-11-22 05:39:21.693496	05_D_the_14th_.mp3
6	1	06_19th_.mp3	2863908	audio/mpeg	2019-11-22 05:39:21.819904	2019-11-22 05:39:21.819904	06_19th_.mp3
7	1	07_22d_.mp3	4464640	audio/mpeg	2019-11-22 05:39:21.96217	2019-11-22 05:39:21.96217	07_22d_.mp3
8	1	08_26th_.mp3	1939748	audio/mpeg	2019-11-22 05:39:22.055794	2019-11-22 05:39:22.055794	08_26th_.mp3
9	1	09_May_3d_.mp3	1940662	audio/mpeg	2019-11-22 05:39:22.139902	2019-11-22 05:39:22.139902	09_May_3d_.mp3
10	1	10_8th_.mp3	2005028	audio/mpeg	2019-11-22 05:39:22.217897	2019-11-22 05:39:22.217897	10_8th_.mp3
11	1	11_13th_.mp3	741302	audio/mpeg	2019-11-22 05:39:22.285153	2019-11-22 05:39:22.285153	11_13th_.mp3
12	1	12_18th_.mp3	691565	audio/mpeg	2019-11-22 05:39:22.337592	2019-11-22 05:39:22.337592	12_18th_.mp3
13	1	13_20th_.mp3	2834468	audio/mpeg	2019-11-22 05:39:22.468553	2019-11-22 05:39:22.468553	13_20th_.mp3
14	1	14_21st_.mp3	1639497	audio/mpeg	2019-11-22 05:39:22.552933	2019-11-22 05:39:22.552933	14_21st_.mp3
15	1	15_22d_.mp3	451657	audio/mpeg	2019-11-22 05:39:22.600912	2019-11-22 05:39:22.600912	15_22d_.mp3
16	1	16_23d_.mp3	4836205	audio/mpeg	2019-11-22 05:39:22.787401	2019-11-22 05:39:22.787401	16_23d_.mp3
17	1	17_24th_.mp3	348342	audio/mpeg	2019-11-22 05:39:22.838233	2019-11-22 05:39:22.838233	17_24th_.mp3
18	1	er_book_info.xml	1108	text/plain	2019-11-22 05:39:22.878571	2019-11-22 05:39:22.878571	er_book_info.xml
19	1	icth0001.smil	1086	text/plain	2019-11-22 05:39:22.907948	2019-11-22 05:39:22.907948	icth0001.smil
20	1	icth0002.smil	2128	text/plain	2019-11-22 05:39:22.938116	2019-11-22 05:39:22.938116	icth0002.smil
21	1	icth0003.smil	6888	text/plain	2019-11-22 05:39:22.979715	2019-11-22 05:39:22.979715	icth0003.smil
22	1	icth0004.smil	4667	text/plain	2019-11-22 05:39:23.009491	2019-11-22 05:39:23.009491	icth0004.smil
23	1	icth0005.smil	27641	text/plain	2019-11-22 05:39:23.043299	2019-11-22 05:39:23.043299	icth0005.smil
24	1	icth0006.smil	10464	text/plain	2019-11-22 05:39:23.076001	2019-11-22 05:39:23.076001	icth0006.smil
25	1	icth0007.smil	12533	text/plain	2019-11-22 05:39:23.110723	2019-11-22 05:39:23.110723	icth0007.smil
26	1	icth0008.smil	6417	text/plain	2019-11-22 05:39:23.145115	2019-11-22 05:39:23.145115	icth0008.smil
27	1	icth0009.smil	6967	text/plain	2019-11-22 05:39:23.175098	2019-11-22 05:39:23.175098	icth0009.smil
28	1	icth000a.smil	6154	text/plain	2019-11-22 05:39:23.212334	2019-11-22 05:39:23.212334	icth000a.smil
29	1	icth000b.smil	2248	text/plain	2019-11-22 05:39:23.244698	2019-11-22 05:39:23.244698	icth000b.smil
30	1	icth000c.smil	2417	text/plain	2019-11-22 05:39:23.275491	2019-11-22 05:39:23.275491	icth000c.smil
31	1	icth000d.smil	7990	text/plain	2019-11-22 05:39:23.31042	2019-11-22 05:39:23.31042	icth000d.smil
32	1	icth000e.smil	5311	text/plain	2019-11-22 05:39:23.341945	2019-11-22 05:39:23.341945	icth000e.smil
33	1	icth000f.smil	2517	text/plain	2019-11-22 05:39:23.375045	2019-11-22 05:39:23.375045	icth000f.smil
34	1	icth0010.smil	17455	text/plain	2019-11-22 05:39:23.407023	2019-11-22 05:39:23.407023	icth0010.smil
35	1	icth0011.smil	2217	text/plain	2019-11-22 05:39:23.438977	2019-11-22 05:39:23.438977	icth0011.smil
36	1	master.smil	1866	text/plain	2019-11-22 05:39:23.480378	2019-11-22 05:39:23.480378	master.smil
37	1	ncc.html	5759	text/html	2019-11-22 05:39:23.508537	2019-11-22 05:39:23.508537	ncc.html
38	1	smil10.dtd	7496	text/html	2019-11-22 05:39:23.543195	2019-11-22 05:39:23.543195	smil10.dtd
39	1	xhtml-lat1.ent	11789	text/html	2019-11-22 05:39:23.573529	2019-11-22 05:39:23.573529	xhtml-lat1.ent
40	1	xhtml-special.ent	4144	text/html	2019-11-22 05:39:23.607706	2019-11-22 05:39:23.607706	xhtml-special.ent
41	1	xhtml-symbol.ent	14127	text/html	2019-11-22 05:39:23.637081	2019-11-22 05:39:23.637081	xhtml-symbol.ent
42	1	xhtml1-transitional.dtd	33441	text/html	2019-11-22 05:39:23.66616	2019-11-22 05:39:23.66616	xhtml1-transitional.dtd
43	2	AreYouReadyV3.xml	89235	text/plain	2019-11-22 05:39:23.70048	2019-11-22 05:39:23.70048	AreYouReadyV3.xml
44	2	dtbookbasic.css	12801	text/x-c	2019-11-22 05:39:23.73943	2019-11-22 05:39:23.73943	dtbookbasic.css
45	2	image1.jpg	6168	image/jpeg	2019-11-22 05:39:23.773016	2019-11-22 05:39:23.773016	image1.jpg
46	2	image11.jpg	5586	image/jpeg	2019-11-22 05:39:23.807964	2019-11-22 05:39:23.807964	image11.jpg
47	2	image12.jpg	4826	image/jpeg	2019-11-22 05:39:23.841538	2019-11-22 05:39:23.841538	image12.jpg
48	2	image13.jpg	12586	image/jpeg	2019-11-22 05:39:23.877824	2019-11-22 05:39:23.877824	image13.jpg
49	2	image14.jpg	17587	image/jpeg	2019-11-22 05:39:23.909259	2019-11-22 05:39:23.909259	image14.jpg
50	2	image2.jpg	8577	image/jpeg	2019-11-22 05:39:23.941519	2019-11-22 05:39:23.941519	image2.jpg
51	2	image3.jpg	5981	image/jpeg	2019-11-22 05:39:23.980088	2019-11-22 05:39:23.980088	image3.jpg
52	2	image4.jpg	4720	image/jpeg	2019-11-22 05:39:24.019313	2019-11-22 05:39:24.019313	image4.jpg
53	2	image5.jpg	6589	image/jpeg	2019-11-22 05:39:24.057278	2019-11-22 05:39:24.057278	image5.jpg
54	2	speechgen.ncx	18280	text/plain	2019-11-22 05:39:24.08951	2019-11-22 05:39:24.08951	speechgen.ncx
55	2	speechgen.opf	8897	text/plain	2019-11-22 05:39:24.123956	2019-11-22 05:39:24.123956	speechgen.opf
56	2	speechgen0001.mp3	32757	audio/mpeg	2019-11-22 05:39:24.15648	2019-11-22 05:39:24.15648	speechgen0001.mp3
57	2	speechgen0001.smil	882	text/plain	2019-11-22 05:39:24.192238	2019-11-22 05:39:24.192238	speechgen0001.smil
58	2	speechgen0002.mp3	215823	audio/mpeg	2019-11-22 05:39:24.230319	2019-11-22 05:39:24.230319	speechgen0002.mp3
59	2	speechgen0002.smil	2716	text/plain	2019-11-22 05:39:24.269222	2019-11-22 05:39:24.269222	speechgen0002.smil
60	2	speechgen0003.mp3	285257	audio/mpeg	2019-11-22 05:39:24.311355	2019-11-22 05:39:24.311355	speechgen0003.mp3
61	2	speechgen0003.smil	5457	text/plain	2019-11-22 05:39:24.34358	2019-11-22 05:39:24.34358	speechgen0003.smil
62	2	speechgen0004.mp3	228832	audio/mpeg	2019-11-22 05:39:24.392418	2019-11-22 05:39:24.392418	speechgen0004.mp3
63	2	speechgen0004.smil	1477	text/plain	2019-11-22 05:39:24.422694	2019-11-22 05:39:24.422694	speechgen0004.smil
64	2	speechgen0005.mp3	1865456	audio/mpeg	2019-11-22 05:39:24.501089	2019-11-22 05:39:24.501089	speechgen0005.mp3
65	2	speechgen0005.smil	10463	text/plain	2019-11-22 05:39:24.541202	2019-11-22 05:39:24.541202	speechgen0005.smil
66	2	speechgen0006.mp3	735399	audio/mpeg	2019-11-22 05:39:24.593608	2019-11-22 05:39:24.593608	speechgen0006.mp3
67	2	speechgen0006.smil	4470	text/plain	2019-11-22 05:39:24.625362	2019-11-22 05:39:24.625362	speechgen0006.smil
68	2	speechgen0007.mp3	105482	audio/mpeg	2019-11-22 05:39:24.664576	2019-11-22 05:39:24.664576	speechgen0007.mp3
69	2	speechgen0007.smil	1438	text/plain	2019-11-22 05:39:24.695678	2019-11-22 05:39:24.695678	speechgen0007.smil
70	2	speechgen0008.mp3	95294	audio/mpeg	2019-11-22 05:39:24.727315	2019-11-22 05:39:24.727315	speechgen0008.mp3
71	2	speechgen0008.smil	1491	text/plain	2019-11-22 05:39:24.759578	2019-11-22 05:39:24.759578	speechgen0008.smil
72	2	speechgen0009.mp3	794801	audio/mpeg	2019-11-22 05:39:24.824445	2019-11-22 05:39:24.824445	speechgen0009.mp3
73	2	speechgen0009.smil	7443	text/plain	2019-11-22 05:39:24.860133	2019-11-22 05:39:24.860133	speechgen0009.smil
74	2	speechgen0010.mp3	258299	audio/mpeg	2019-11-22 05:39:24.899423	2019-11-22 05:39:24.899423	speechgen0010.mp3
75	2	speechgen0010.smil	2873	text/plain	2019-11-22 05:39:24.92922	2019-11-22 05:39:24.92922	speechgen0010.smil
76	2	speechgen0011.mp3	249835	audio/mpeg	2019-11-22 05:39:24.967221	2019-11-22 05:39:24.967221	speechgen0011.mp3
77	2	speechgen0011.smil	2192	text/plain	2019-11-22 05:39:25.003771	2019-11-22 05:39:25.003771	speechgen0011.smil
78	2	speechgen0012.mp3	225071	audio/mpeg	2019-11-22 05:39:25.043569	2019-11-22 05:39:25.043569	speechgen0012.mp3
79	2	speechgen0012.smil	2285	text/plain	2019-11-22 05:39:25.072955	2019-11-22 05:39:25.072955	speechgen0012.smil
80	2	speechgen0013.mp3	1677374	audio/mpeg	2019-11-22 05:39:25.146988	2019-11-22 05:39:25.146988	speechgen0013.mp3
81	2	speechgen0013.smil	11510	text/plain	2019-11-22 05:39:25.178576	2019-11-22 05:39:25.178576	speechgen0013.smil
82	2	speechgen0014.mp3	372872	audio/mpeg	2019-11-22 05:39:25.221571	2019-11-22 05:39:25.221571	speechgen0014.mp3
83	2	speechgen0014.smil	2480	text/plain	2019-11-22 05:39:25.254165	2019-11-22 05:39:25.254165	speechgen0014.smil
84	2	speechgen0015.mp3	439327	audio/mpeg	2019-11-22 05:39:25.31059	2019-11-22 05:39:25.31059	speechgen0015.mp3
85	2	speechgen0015.smil	3243	text/plain	2019-11-22 05:39:25.34263	2019-11-22 05:39:25.34263	speechgen0015.smil
86	2	speechgen0016.mp3	121469	audio/mpeg	2019-11-22 05:39:25.382846	2019-11-22 05:39:25.382846	speechgen0016.mp3
87	2	speechgen0016.smil	1691	text/plain	2019-11-22 05:39:25.413781	2019-11-22 05:39:25.413781	speechgen0016.smil
88	2	speechgen0017.mp3	2289110	audio/mpeg	2019-11-22 05:39:25.504245	2019-11-22 05:39:25.504245	speechgen0017.mp3
89	2	speechgen0017.smil	17070	text/plain	2019-11-22 05:39:25.544431	2019-11-22 05:39:25.544431	speechgen0017.smil
90	2	speechgen0018.mp3	1220493	audio/mpeg	2019-11-22 05:39:25.610659	2019-11-22 05:39:25.610659	speechgen0018.mp3
91	2	speechgen0018.smil	9825	text/plain	2019-11-22 05:39:25.651325	2019-11-22 05:39:25.651325	speechgen0018.smil
92	2	speechgen0019.mp3	181185	audio/mpeg	2019-11-22 05:39:25.688022	2019-11-22 05:39:25.688022	speechgen0019.mp3
93	2	speechgen0019.smil	1640	text/plain	2019-11-22 05:39:25.725112	2019-11-22 05:39:25.725112	speechgen0019.smil
94	2	speechgen0020.mp3	1113756	audio/mpeg	2019-11-22 05:39:25.793172	2019-11-22 05:39:25.793172	speechgen0020.mp3
95	2	speechgen0020.smil	7023	text/plain	2019-11-22 05:39:25.832352	2019-11-22 05:39:25.832352	speechgen0020.smil
96	2	speechgen0021.mp3	446694	audio/mpeg	2019-11-22 05:39:25.880516	2019-11-22 05:39:25.880516	speechgen0021.mp3
97	2	speechgen0021.smil	3453	text/plain	2019-11-22 05:39:25.91595	2019-11-22 05:39:25.91595	speechgen0021.smil
98	2	speechgen0022.mp3	191216	audio/mpeg	2019-11-22 05:39:25.955159	2019-11-22 05:39:25.955159	speechgen0022.mp3
99	2	speechgen0022.smil	1492	text/plain	2019-11-22 05:39:25.99873	2019-11-22 05:39:25.99873	speechgen0022.smil
100	2	speechgen0023.mp3	1083977	audio/mpeg	2019-11-22 05:39:26.057982	2019-11-22 05:39:26.057982	speechgen0023.mp3
101	2	speechgen0023.smil	6373	text/plain	2019-11-22 05:39:26.100809	2019-11-22 05:39:26.100809	speechgen0023.smil
102	2	speechgen0024.mp3	354690	audio/mpeg	2019-11-22 05:39:26.148334	2019-11-22 05:39:26.148334	speechgen0024.mp3
103	2	speechgen0024.smil	2690	text/plain	2019-11-22 05:39:26.182661	2019-11-22 05:39:26.182661	speechgen0024.smil
104	2	speechgen0025.mp3	193410	audio/mpeg	2019-11-22 05:39:26.218451	2019-11-22 05:39:26.218451	speechgen0025.mp3
105	2	speechgen0025.smil	1835	text/plain	2019-11-22 05:39:26.253478	2019-11-22 05:39:26.253478	speechgen0025.smil
106	2	speechgen0026.mp3	112222	audio/mpeg	2019-11-22 05:39:26.294308	2019-11-22 05:39:26.294308	speechgen0026.mp3
107	2	speechgen0026.smil	1493	text/plain	2019-11-22 05:39:26.325067	2019-11-22 05:39:26.325067	speechgen0026.smil
108	2	speechgen0027.mp3	605466	audio/mpeg	2019-11-22 05:39:26.375325	2019-11-22 05:39:26.375325	speechgen0027.mp3
109	2	speechgen0027.smil	4580	text/plain	2019-11-22 05:39:26.409548	2019-11-22 05:39:26.409548	speechgen0027.smil
110	2	speechgen0028.mp3	136516	audio/mpeg	2019-11-22 05:39:26.455204	2019-11-22 05:39:26.455204	speechgen0028.mp3
111	2	speechgen0028.smil	1430	text/plain	2019-11-22 05:39:26.49358	2019-11-22 05:39:26.49358	speechgen0028.smil
112	2	speechgen0029.mp3	92943	audio/mpeg	2019-11-22 05:39:26.530018	2019-11-22 05:39:26.530018	speechgen0029.mp3
113	2	speechgen0029.smil	1439	text/plain	2019-11-22 05:39:26.563912	2019-11-22 05:39:26.563912	speechgen0029.smil
114	2	speechgen0030.mp3	112535	audio/mpeg	2019-11-22 05:39:26.59702	2019-11-22 05:39:26.59702	speechgen0030.mp3
115	2	speechgen0030.smil	1493	text/plain	2019-11-22 05:39:26.628352	2019-11-22 05:39:26.628352	speechgen0030.smil
116	2	speechgen0031.mp3	1319863	audio/mpeg	2019-11-22 05:39:26.691514	2019-11-22 05:39:26.691514	speechgen0031.mp3
117	2	speechgen0031.smil	10153	text/plain	2019-11-22 05:39:26.727993	2019-11-22 05:39:26.727993	speechgen0031.smil
118	2	speechgen0032.mp3	584150	audio/mpeg	2019-11-22 05:39:26.774719	2019-11-22 05:39:26.774719	speechgen0032.mp3
119	2	speechgen0032.smil	5658	text/plain	2019-11-22 05:39:26.81274	2019-11-22 05:39:26.81274	speechgen0032.smil
120	2	speechgen0033.mp3	256104	audio/mpeg	2019-11-22 05:39:26.85222	2019-11-22 05:39:26.85222	speechgen0033.mp3
121	2	speechgen0033.smil	1850	text/plain	2019-11-22 05:39:26.890091	2019-11-22 05:39:26.890091	speechgen0033.smil
122	2	speechgen0034.mp3	284630	audio/mpeg	2019-11-22 05:39:26.937137	2019-11-22 05:39:26.937137	speechgen0034.mp3
123	2	speechgen0034.smil	2480	text/plain	2019-11-22 05:39:26.969076	2019-11-22 05:39:26.969076	speechgen0034.smil
124	2	tpbnarrator.res	9458	text/html	2019-11-22 05:39:27.002871	2019-11-22 05:39:27.002871	tpbnarrator.res
125	2	tpbnarrator_res.mp3	117656	audio/mpeg	2019-11-22 05:39:27.044079	2019-11-22 05:39:27.044079	tpbnarrator_res.mp3
126	3	bagw0008.smil	2714	application/xml	2019-11-22 05:39:27.097268	2019-11-22 05:39:27.097268	bagw0008.smil
127	3	bagw0017.mp3	124760	audio/mpeg	2019-11-22 05:39:27.132466	2019-11-22 05:39:27.132466	bagw0017.mp3
128	3	bagw0019.mp3	180558	audio/mpeg	2019-11-22 05:39:27.17204	2019-11-22 05:39:27.17204	bagw0019.mp3
129	3	bagw0006.smil	2765	application/xml	2019-11-22 05:39:27.206581	2019-11-22 05:39:27.206581	bagw0006.smil
130	3	bagw001B.mp3	285570	audio/mpeg	2019-11-22 05:39:27.244565	2019-11-22 05:39:27.244565	bagw001B.mp3
131	3	bagw0014.mp3	391836	audio/mpeg	2019-11-22 05:39:27.289495	2019-11-22 05:39:27.289495	bagw0014.mp3
132	3	bagw0007.smil	1567	application/xml	2019-11-22 05:39:27.327562	2019-11-22 05:39:27.327562	bagw0007.smil
133	3	bagw0018.mp3	65515	audio/mpeg	2019-11-22 05:39:27.360778	2019-11-22 05:39:27.360778	bagw0018.mp3
134	3	bagw001A.mp3	129776	audio/mpeg	2019-11-22 05:39:27.403142	2019-11-22 05:39:27.403142	bagw001A.mp3
135	3	bagw0005.smil	1130	application/xml	2019-11-22 05:39:27.439121	2019-11-22 05:39:27.439121	bagw0005.smil
136	3	master.smil	1196	application/xml	2019-11-22 05:39:27.474017	2019-11-22 05:39:27.474017	master.smil
137	3	bagw0003.smil	2419	application/xml	2019-11-22 05:39:27.509135	2019-11-22 05:39:27.509135	bagw0003.smil
138	3	ncc.html	4296	application/xml	2019-11-22 05:39:27.541972	2019-11-22 05:39:27.541972	ncc.html
139	3	bagw0002.smil	1334	application/xml	2019-11-22 05:39:27.581271	2019-11-22 05:39:27.581271	bagw0002.smil
140	3	narrator_1.css	5204	text/plain	2019-11-22 05:39:27.613618	2019-11-22 05:39:27.613618	narrator_1.css
141	3	default_1.css	5053	text/plain	2019-11-22 05:39:27.651105	2019-11-22 05:39:27.651105	default_1.css
142	3	bagw001C.mp3	451552	audio/mpeg	2019-11-22 05:39:27.698785	2019-11-22 05:39:27.698785	bagw001C.mp3
143	3	bagw0001.smil	1728	application/xml	2019-11-22 05:39:27.728	2019-11-22 05:39:27.728	bagw0001.smil
144	3	bagw001D.mp3	335882	audio/mpeg	2019-11-22 05:39:27.793962	2019-11-22 05:39:27.793962	bagw001D.mp3
145	3	bagw0004.smil	3047	application/xml	2019-11-22 05:39:27.831405	2019-11-22 05:39:27.831405	bagw0004.smil
\.


--
-- Data for Name: contents; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.contents (id, category_id, daisy_format_id, title, created_at, updated_at) FROM stdin;
1	1	1	A light Man	2019-11-22 05:39:18.907771	2019-11-22 05:39:18.907771
2	1	2	Are you ready?	2019-11-22 05:39:18.944121	2019-11-22 05:39:18.944121
3	1	1	Climbing the Highest Mountain	2019-11-22 05:39:18.98311	2019-11-22 05:39:18.98311
\.


--
-- Data for Name: daisy_formats; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.daisy_formats (id, format, created_at, updated_at) FROM stdin;
1	Daisy 2.02	2019-11-22 05:39:11.71968	2019-11-22 05:39:11.71968
2	ANSI/NISO Z39.86-2005	2019-11-22 05:39:11.721074	2019-11-22 05:39:11.721074
3	PDTB2	2019-11-22 05:39:11.721968	2019-11-22 05:39:11.721968
\.


--
-- Data for Name: languages; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.languages (id, lang, created_at, updated_at) FROM stdin;
1	en	2019-11-22 05:39:11.73176	2019-11-22 05:39:11.73176
2	sv	2019-11-22 05:39:11.733134	2019-11-22 05:39:11.733134
3	fi	2019-11-22 05:39:11.734092	2019-11-22 05:39:11.734092
\.


--
-- Data for Name: question_audios; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.question_audios (id, question_text_id, size, length, mime_type, audio, created_at, updated_at) FROM stdin;
1	1	13817	1303	audio/ogg	question_1.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
2	2	14634	1049	audio/ogg	question_2.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
3	3	13649	1016	audio/ogg	question_3.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
4	4	14255	1358	audio/ogg	question_4.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
5	5	13414	971	audio/ogg	question_5.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
6	6	14323	1427	audio/ogg	question_6.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
7	7	10317	776	audio/ogg	question_7.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
8	8	11936	865	audio/ogg	question_8.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
9	9	16948	1731	audio/ogg	question_9.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
10	10	15906	1495	audio/ogg	question_10.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
11	11	12455	920	audio/ogg	question_11.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
12	12	12857	1475	audio/ogg	question_12.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
13	13	10729	1016	audio/ogg	question_13.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
14	14	11504	1092	audio/ogg	question_14.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
15	15	11760	926	audio/ogg	question_15.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
16	16	13190	1380	audio/ogg	question_16.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
17	17	9871	972	audio/ogg	question_17.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
18	18	9975	997	audio/ogg	question_18.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
19	19	22087	1985	audio/ogg	question_19.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
20	20	21288	2002	audio/ogg	question_20.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
21	21	9715	972	audio/ogg	question_21.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
22	22	13854	1339	audio/ogg	question_22.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
23	23	19397	2018	audio/ogg	question_23.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
24	24	20361	2267	audio/ogg	question_24.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
25	25	19313	2052	audio/ogg	question_25.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
26	26	21900	2292	audio/ogg	question_26.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
27	27	17356	1507	audio/ogg	question_27.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
28	28	21999	2198	audio/ogg	question_28.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
29	29	11525	1027	audio/ogg	question_29.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
30	30	15271	1219	audio/ogg	question_30.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
31	31	8239	635	audio/ogg	question_31.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
32	32	9086	662	audio/ogg	question_32.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
33	33	5465	324	audio/ogg	question_33.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
34	34	8102	447	audio/ogg	question_34.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
35	35	7485	394	audio/ogg	question_35.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
36	36	6234	424	audio/ogg	question_36.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
37	37	5358	318	audio/ogg	question_37.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
38	38	7797	429	audio/ogg	question_38.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
39	39	15523	1368	audio/ogg	question_39.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
40	40	14041	1320	audio/ogg	question_40.ogg	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
\.


--
-- Data for Name: question_inputs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.question_inputs (id, question_id, allow_multiple_selections, text_numeric, text_alphanumeric, audio, default_value, created_at, updated_at) FROM stdin;
1	1	\N	\N	\N	\N	\N	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
2	20	\N	\N	\N	\N	\N	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
3	30	\N	\N	\N	\N	\N	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
4	40	\N	\N	\N	\N	\N	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
5	23	\N	\N	1	\N	\N	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
6	24	\N	\N	1	\N	\N	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
7	41	\N	\N	1	\N	\N	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
\.


--
-- Data for Name: question_question_texts; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.question_question_texts (id, question_id, question_text_id, created_at, updated_at) FROM stdin;
1	1	1	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
2	1	2	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
3	2	3	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
4	2	4	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
5	3	5	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
6	3	6	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
7	4	7	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
8	4	8	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
9	20	9	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
10	20	10	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
11	21	11	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
12	21	12	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
13	22	13	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
14	22	14	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
15	23	15	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
16	23	16	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
17	24	17	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
18	24	18	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
19	30	19	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
20	30	20	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
21	31	21	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
22	31	22	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
23	32	23	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
24	32	24	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
25	33	25	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
26	33	26	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
27	40	27	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
28	40	28	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
29	41	29	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
30	41	30	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
31	42	31	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
32	42	32	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
33	43	33	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
34	43	34	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
35	44	35	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
36	44	26	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
37	45	37	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
38	45	38	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
39	46	39	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
40	46	40	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
41	47	39	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
42	47	40	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
43	48	39	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
44	48	40	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
45	49	39	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
46	49	40	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
47	50	39	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
48	50	40	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
\.


--
-- Data for Name: question_texts; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.question_texts (id, language_id, text, created_at, updated_at) FROM stdin;
1	1	What would you like to do?	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
2	2	Vad vill du göra?	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
3	1	Search the library.	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
4	2	Söka i biblioteket.	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
5	1	Browse the library.	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
6	2	Utforska biblioteket.	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
7	1	Give feedback.	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
8	2	Ge feedback.	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
9	1	What do you want to search by?	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
10	2	Vad vill du söka enligt?	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
11	1	Search by author.	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
12	2	Sök bland författare.	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
13	1	Search by title.	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
14	2	Sök bland titel.	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
15	1	Author keywords:	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
16	2	Sökord författare:	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
17	1	Title keywords:	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
18	2	Sökord titel:	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
19	1	How do you want to browse the library?	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
20	2	Hur vill du utforska biblioteket?	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
21	1	Browse by title.	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
22	2	Utforska bland titlar.	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
23	1	Browse by Daisy 2 content format.	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
24	2	Utforska bland Daisy 2 filformat.	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
25	1	Browse by Daisy 3 content format.	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
26	2	Utforska bland Daisy 3 filformat.	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
27	1	How would you rate this service?	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
28	2	Hur skulle du betygsätta denna tjänst?	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
29	1	Optional feedback?	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
30	2	Frivillig feedback?	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
31	1	Excellent.	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
32	2	Utmärkt.	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
33	1	Good.	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
34	2	Bra.	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
35	1	Fair.	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
36	2	Dålig.	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
37	1	Poor.	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
38	2	Usel.	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
39	1	Thank you for your feedback.	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
40	2	Tack för din feedback.	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
\.


--
-- Data for Name: question_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.question_types (id, name, created_at, updated_at) FROM stdin;
1	multipleChoiceQuestion	2019-11-22 05:39:11.78067	2019-11-22 05:39:11.78067
2	inputQuestion	2019-11-22 05:39:11.782145	2019-11-22 05:39:11.782145
3	choice	2019-11-22 05:39:11.783169	2019-11-22 05:39:11.783169
4	contentListRef	2019-11-22 05:39:11.784065	2019-11-22 05:39:11.784065
5	label	2019-11-22 05:39:11.784977	2019-11-22 05:39:11.784977
\.


--
-- Data for Name: questions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.questions (id, parent_id, question_type_id, created_at, updated_at) FROM stdin;
1	0	1	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
2	1	3	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
3	1	3	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
4	1	3	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
20	2	1	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
21	20	3	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
22	20	3	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
23	21	2	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
24	22	2	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
25	23	4	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
26	24	4	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
30	3	1	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
31	30	3	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
32	30	3	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
33	30	3	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
34	31	4	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
35	32	4	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
36	33	4	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
40	4	1	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
41	4	2	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
42	40	3	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
43	40	3	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
44	40	3	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
45	40	3	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
46	41	5	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
47	42	5	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
48	43	5	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
49	44	5	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
50	45	5	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
\.


--
-- Data for Name: schema_migrations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.schema_migrations (version) FROM stdin;
20190111182636
20190220111925
20190220113222
20190220113815
20190220160412
20190220162144
20190220181504
20190220182034
20190220182555
20190220183617
20190220185209
20190220190259
20190220190600
20190221164321
20190221164819
20190221165021
20190221171607
20190221173404
20190221181516
20190221181952
20190221182750
20190221183224
20190508030826
20190524011519
20190630123411
20190630161849
20190722194144
20190722194727
20190723021432
20191031065020
20191031075631
20191101043055
20191101043110
20191101043121
20191101043131
20191101043140
20191101043148
20191101142340
20191120045434
20191120045444
\.


--
-- Data for Name: states; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.states (id, state, created_at, updated_at) FROM stdin;
1	NULL	2019-11-22 05:39:11.744281	2019-11-22 05:39:11.744281
2	START	2019-11-22 05:39:11.745635	2019-11-22 05:39:11.745635
3	PAUSE	2019-11-22 05:39:11.746668	2019-11-22 05:39:11.746668
4	RESUME	2019-11-22 05:39:11.74761	2019-11-22 05:39:11.74761
5	FINISH	2019-11-22 05:39:11.748409	2019-11-22 05:39:11.748409
\.


--
-- Data for Name: user_announcements; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.user_announcements (id, user_id, announcement_id, read_at, created_at, updated_at) FROM stdin;
1	1	1	\N	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
2	1	2	\N	2019-11-22 05:39:29.097506	2019-11-22 05:39:29.097506
\.


--
-- Data for Name: user_bookmarks; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.user_bookmarks (id, user_id, content_id, bookmark_set, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: user_contents; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.user_contents (id, user_id, content_id, content_list_id, content_list_v1_id, return, returned, return_at, state_id, created_at, updated_at) FROM stdin;
1	1	1	1	2	t	f	\N	1	2019-11-22 05:39:27.889951	2019-11-22 05:39:27.889951
2	1	2	1	2	t	f	\N	1	2019-11-22 05:39:27.925078	2019-11-22 05:39:27.925078
3	1	3	1	2	t	f	\N	1	2019-11-22 05:39:27.962312	2019-11-22 05:39:27.962312
\.


--
-- Data for Name: user_logs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.user_logs (id, user_id, ip, request, response, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, username, password, terms_accepted, log, activated, created_at, updated_at) FROM stdin;
1	kolibre	Wz2fuBzjbhCrm/Dmx38DCgpWHigWf8aaEDlvpDCO5gImGDI=\n	f	t	f	2019-11-22 05:39:18.843354	2019-11-22 05:39:18.843354
\.


--
-- Name: announcement_audios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.announcement_audios_id_seq', 4, true);


--
-- Name: announcement_texts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.announcement_texts_id_seq', 4, true);


--
-- Name: announcements_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.announcements_id_seq', 2, true);


--
-- Name: categories_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.categories_id_seq', 4, true);


--
-- Name: content_audios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.content_audios_id_seq', 3, true);


--
-- Name: content_lists_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.content_lists_id_seq', 4, true);


--
-- Name: content_metadata_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.content_metadata_id_seq', 65, true);


--
-- Name: content_resources_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.content_resources_id_seq', 145, true);


--
-- Name: contents_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.contents_id_seq', 3, true);


--
-- Name: daisy_formats_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.daisy_formats_id_seq', 3, true);


--
-- Name: languages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.languages_id_seq', 3, true);


--
-- Name: question_audios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.question_audios_id_seq', 40, true);


--
-- Name: question_inputs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.question_inputs_id_seq', 7, true);


--
-- Name: question_question_texts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.question_question_texts_id_seq', 48, true);


--
-- Name: question_texts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.question_texts_id_seq', 40, true);


--
-- Name: question_types_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.question_types_id_seq', 5, true);


--
-- Name: questions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.questions_id_seq', 51, false);


--
-- Name: states_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.states_id_seq', 5, true);


--
-- Name: user_announcements_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.user_announcements_id_seq', 2, true);


--
-- Name: user_bookmarks_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.user_bookmarks_id_seq', 1, false);


--
-- Name: user_contents_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.user_contents_id_seq', 3, true);


--
-- Name: user_logs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.user_logs_id_seq', 1, false);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 1, true);


--
-- Name: announcement_audios announcement_audios_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.announcement_audios
    ADD CONSTRAINT announcement_audios_pkey PRIMARY KEY (id);


--
-- Name: announcement_texts announcement_texts_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.announcement_texts
    ADD CONSTRAINT announcement_texts_pkey PRIMARY KEY (id);


--
-- Name: announcements announcements_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.announcements
    ADD CONSTRAINT announcements_pkey PRIMARY KEY (id);


--
-- Name: ar_internal_metadata ar_internal_metadata_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ar_internal_metadata
    ADD CONSTRAINT ar_internal_metadata_pkey PRIMARY KEY (key);


--
-- Name: categories categories_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_pkey PRIMARY KEY (id);


--
-- Name: content_audios content_audios_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.content_audios
    ADD CONSTRAINT content_audios_pkey PRIMARY KEY (id);


--
-- Name: content_lists content_lists_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.content_lists
    ADD CONSTRAINT content_lists_pkey PRIMARY KEY (id);


--
-- Name: content_metadata content_metadata_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.content_metadata
    ADD CONSTRAINT content_metadata_pkey PRIMARY KEY (id);


--
-- Name: content_resources content_resources_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.content_resources
    ADD CONSTRAINT content_resources_pkey PRIMARY KEY (id);


--
-- Name: contents contents_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contents
    ADD CONSTRAINT contents_pkey PRIMARY KEY (id);


--
-- Name: daisy_formats daisy_formats_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.daisy_formats
    ADD CONSTRAINT daisy_formats_pkey PRIMARY KEY (id);


--
-- Name: languages languages_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.languages
    ADD CONSTRAINT languages_pkey PRIMARY KEY (id);


--
-- Name: question_audios question_audios_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.question_audios
    ADD CONSTRAINT question_audios_pkey PRIMARY KEY (id);


--
-- Name: question_inputs question_inputs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.question_inputs
    ADD CONSTRAINT question_inputs_pkey PRIMARY KEY (id);


--
-- Name: question_question_texts question_question_texts_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.question_question_texts
    ADD CONSTRAINT question_question_texts_pkey PRIMARY KEY (id);


--
-- Name: question_texts question_texts_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.question_texts
    ADD CONSTRAINT question_texts_pkey PRIMARY KEY (id);


--
-- Name: question_types question_types_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.question_types
    ADD CONSTRAINT question_types_pkey PRIMARY KEY (id);


--
-- Name: questions questions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.questions
    ADD CONSTRAINT questions_pkey PRIMARY KEY (id);


--
-- Name: schema_migrations schema_migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.schema_migrations
    ADD CONSTRAINT schema_migrations_pkey PRIMARY KEY (version);


--
-- Name: states states_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.states
    ADD CONSTRAINT states_pkey PRIMARY KEY (id);


--
-- Name: user_announcements user_announcements_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_announcements
    ADD CONSTRAINT user_announcements_pkey PRIMARY KEY (id);


--
-- Name: user_bookmarks user_bookmarks_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_bookmarks
    ADD CONSTRAINT user_bookmarks_pkey PRIMARY KEY (id);


--
-- Name: user_contents user_contents_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_contents
    ADD CONSTRAINT user_contents_pkey PRIMARY KEY (id);


--
-- Name: user_logs user_logs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_logs
    ADD CONSTRAINT user_logs_pkey PRIMARY KEY (id);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: index_announcement_audios_on_announcement_text_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX index_announcement_audios_on_announcement_text_id ON public.announcement_audios USING btree (announcement_text_id);


--
-- Name: index_announcement_texts_on_announcement_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX index_announcement_texts_on_announcement_id ON public.announcement_texts USING btree (announcement_id);


--
-- Name: index_announcement_texts_on_language_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX index_announcement_texts_on_language_id ON public.announcement_texts USING btree (language_id);


--
-- Name: index_content_audios_on_content_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX index_content_audios_on_content_id ON public.content_audios USING btree (content_id);


--
-- Name: index_content_audios_on_content_id_and_audio; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX index_content_audios_on_content_id_and_audio ON public.content_audios USING btree (content_id, audio);


--
-- Name: index_content_metadata_on_content_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX index_content_metadata_on_content_id ON public.content_metadata USING btree (content_id);


--
-- Name: index_content_resources_on_content_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX index_content_resources_on_content_id ON public.content_resources USING btree (content_id);


--
-- Name: index_content_resources_on_content_id_and_resource; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX index_content_resources_on_content_id_and_resource ON public.content_resources USING btree (content_id, resource);


--
-- Name: index_contents_on_category_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX index_contents_on_category_id ON public.contents USING btree (category_id);


--
-- Name: index_contents_on_daisy_format_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX index_contents_on_daisy_format_id ON public.contents USING btree (daisy_format_id);


--
-- Name: index_contents_on_title; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX index_contents_on_title ON public.contents USING btree (title);


--
-- Name: index_question_audios_on_question_text_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX index_question_audios_on_question_text_id ON public.question_audios USING btree (question_text_id);


--
-- Name: index_question_inputs_on_question_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX index_question_inputs_on_question_id ON public.question_inputs USING btree (question_id);


--
-- Name: index_question_question_texts_on_question_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX index_question_question_texts_on_question_id ON public.question_question_texts USING btree (question_id);


--
-- Name: index_question_question_texts_on_question_text_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX index_question_question_texts_on_question_text_id ON public.question_question_texts USING btree (question_text_id);


--
-- Name: index_question_texts_on_language_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX index_question_texts_on_language_id ON public.question_texts USING btree (language_id);


--
-- Name: index_questions_on_parent_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX index_questions_on_parent_id ON public.questions USING btree (parent_id);


--
-- Name: index_questions_on_question_type_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX index_questions_on_question_type_id ON public.questions USING btree (question_type_id);


--
-- Name: index_user_announcements_on_announcement_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX index_user_announcements_on_announcement_id ON public.user_announcements USING btree (announcement_id);


--
-- Name: index_user_announcements_on_user_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX index_user_announcements_on_user_id ON public.user_announcements USING btree (user_id);


--
-- Name: index_user_bookmarks_on_content_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX index_user_bookmarks_on_content_id ON public.user_bookmarks USING btree (content_id);


--
-- Name: index_user_bookmarks_on_user_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX index_user_bookmarks_on_user_id ON public.user_bookmarks USING btree (user_id);


--
-- Name: index_user_contents_on_content_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX index_user_contents_on_content_id ON public.user_contents USING btree (content_id);


--
-- Name: index_user_contents_on_content_list_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX index_user_contents_on_content_list_id ON public.user_contents USING btree (content_list_id);


--
-- Name: index_user_contents_on_content_list_v1_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX index_user_contents_on_content_list_v1_id ON public.user_contents USING btree (content_list_v1_id);


--
-- Name: index_user_contents_on_state_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX index_user_contents_on_state_id ON public.user_contents USING btree (state_id);


--
-- Name: index_user_contents_on_user_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX index_user_contents_on_user_id ON public.user_contents USING btree (user_id);


--
-- Name: index_user_contents_on_user_id_content_id_content_list_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX index_user_contents_on_user_id_content_id_content_list_id ON public.user_contents USING btree (user_id, content_id, content_list_id);


--
-- Name: index_user_contents_on_user_id_content_id_content_list_v1_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX index_user_contents_on_user_id_content_id_content_list_v1_id ON public.user_contents USING btree (user_id, content_id, content_list_v1_id);


--
-- Name: index_user_logs_on_user_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX index_user_logs_on_user_id ON public.user_logs USING btree (user_id);


--
-- Name: user_contents fk_rails_1b2d7668e7; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_contents
    ADD CONSTRAINT fk_rails_1b2d7668e7 FOREIGN KEY (user_id) REFERENCES public.users(id);


--
-- Name: announcement_audios fk_rails_273934291a; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.announcement_audios
    ADD CONSTRAINT fk_rails_273934291a FOREIGN KEY (announcement_text_id) REFERENCES public.announcement_texts(id);


--
-- Name: question_audios fk_rails_2c14c16967; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.question_audios
    ADD CONSTRAINT fk_rails_2c14c16967 FOREIGN KEY (question_text_id) REFERENCES public.question_texts(id);


--
-- Name: user_announcements fk_rails_2ee5e7f73c; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_announcements
    ADD CONSTRAINT fk_rails_2ee5e7f73c FOREIGN KEY (announcement_id) REFERENCES public.announcements(id);


--
-- Name: announcement_texts fk_rails_32523d9eda; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.announcement_texts
    ADD CONSTRAINT fk_rails_32523d9eda FOREIGN KEY (language_id) REFERENCES public.languages(id);


--
-- Name: questions fk_rails_3308b63de5; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.questions
    ADD CONSTRAINT fk_rails_3308b63de5 FOREIGN KEY (question_type_id) REFERENCES public.question_types(id);


--
-- Name: content_resources fk_rails_346a96bba8; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.content_resources
    ADD CONSTRAINT fk_rails_346a96bba8 FOREIGN KEY (content_id) REFERENCES public.contents(id);


--
-- Name: question_question_texts fk_rails_3d496c7bca; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.question_question_texts
    ADD CONSTRAINT fk_rails_3d496c7bca FOREIGN KEY (question_id) REFERENCES public.questions(id);


--
-- Name: user_bookmarks fk_rails_6282c98c14; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_bookmarks
    ADD CONSTRAINT fk_rails_6282c98c14 FOREIGN KEY (user_id) REFERENCES public.users(id);


--
-- Name: contents fk_rails_7adb78c5d8; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contents
    ADD CONSTRAINT fk_rails_7adb78c5d8 FOREIGN KEY (daisy_format_id) REFERENCES public.daisy_formats(id);


--
-- Name: question_question_texts fk_rails_828aa31938; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.question_question_texts
    ADD CONSTRAINT fk_rails_828aa31938 FOREIGN KEY (question_text_id) REFERENCES public.question_texts(id);


--
-- Name: user_logs fk_rails_903088cca6; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_logs
    ADD CONSTRAINT fk_rails_903088cca6 FOREIGN KEY (user_id) REFERENCES public.users(id);


--
-- Name: user_announcements fk_rails_9dd0a6ef76; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_announcements
    ADD CONSTRAINT fk_rails_9dd0a6ef76 FOREIGN KEY (user_id) REFERENCES public.users(id);


--
-- Name: question_texts fk_rails_a55bd375cb; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.question_texts
    ADD CONSTRAINT fk_rails_a55bd375cb FOREIGN KEY (language_id) REFERENCES public.languages(id);


--
-- Name: content_audios fk_rails_a8a52d340d; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.content_audios
    ADD CONSTRAINT fk_rails_a8a52d340d FOREIGN KEY (content_id) REFERENCES public.contents(id);


--
-- Name: user_contents fk_rails_be7bc6e0c5; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_contents
    ADD CONSTRAINT fk_rails_be7bc6e0c5 FOREIGN KEY (content_id) REFERENCES public.contents(id);


--
-- Name: contents fk_rails_d914bbe5f3; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contents
    ADD CONSTRAINT fk_rails_d914bbe5f3 FOREIGN KEY (category_id) REFERENCES public.categories(id);


--
-- Name: announcement_texts fk_rails_d95e91bd06; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.announcement_texts
    ADD CONSTRAINT fk_rails_d95e91bd06 FOREIGN KEY (announcement_id) REFERENCES public.announcements(id);


--
-- Name: user_bookmarks fk_rails_dc9914be68; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_bookmarks
    ADD CONSTRAINT fk_rails_dc9914be68 FOREIGN KEY (content_id) REFERENCES public.contents(id);


--
-- Name: content_metadata fk_rails_e30c04a905; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.content_metadata
    ADD CONSTRAINT fk_rails_e30c04a905 FOREIGN KEY (content_id) REFERENCES public.contents(id);


--
-- Name: question_inputs fk_rails_e9a58aaea9; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.question_inputs
    ADD CONSTRAINT fk_rails_e9a58aaea9 FOREIGN KEY (question_id) REFERENCES public.questions(id);


--
-- Name: user_contents fk_rails_f89e2bfeb4; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_contents
    ADD CONSTRAINT fk_rails_f89e2bfeb4 FOREIGN KEY (state_id) REFERENCES public.states(id);


--
-- PostgreSQL database dump complete
--

