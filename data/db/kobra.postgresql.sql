--
-- PostgreSQL database dump
--

-- Dumped from database version 11.2
-- Dumped by pg_dump version 11.2

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: announcement_audios; Type: TABLE; Schema: public; Owner: johan
--

CREATE TABLE public.announcement_audios (
    id bigint NOT NULL,
    announcement_text_id integer,
    size integer NOT NULL,
    length integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.announcement_audios OWNER TO johan;

--
-- Name: announcement_audios_id_seq; Type: SEQUENCE; Schema: public; Owner: johan
--

CREATE SEQUENCE public.announcement_audios_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.announcement_audios_id_seq OWNER TO johan;

--
-- Name: announcement_audios_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: johan
--

ALTER SEQUENCE public.announcement_audios_id_seq OWNED BY public.announcement_audios.id;


--
-- Name: announcement_texts; Type: TABLE; Schema: public; Owner: johan
--

CREATE TABLE public.announcement_texts (
    id bigint NOT NULL,
    announcement_id integer,
    text text NOT NULL,
    language_id integer,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.announcement_texts OWNER TO johan;

--
-- Name: announcement_texts_id_seq; Type: SEQUENCE; Schema: public; Owner: johan
--

CREATE SEQUENCE public.announcement_texts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.announcement_texts_id_seq OWNER TO johan;

--
-- Name: announcement_texts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: johan
--

ALTER SEQUENCE public.announcement_texts_id_seq OWNED BY public.announcement_texts.id;


--
-- Name: announcements; Type: TABLE; Schema: public; Owner: johan
--

CREATE TABLE public.announcements (
    id bigint NOT NULL,
    category text,
    priority text DEFAULT 'LOW'::text NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.announcements OWNER TO johan;

--
-- Name: announcements_id_seq; Type: SEQUENCE; Schema: public; Owner: johan
--

CREATE SEQUENCE public.announcements_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.announcements_id_seq OWNER TO johan;

--
-- Name: announcements_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: johan
--

ALTER SEQUENCE public.announcements_id_seq OWNED BY public.announcements.id;


--
-- Name: ar_internal_metadata; Type: TABLE; Schema: public; Owner: johan
--

CREATE TABLE public.ar_internal_metadata (
    key character varying NOT NULL,
    value character varying,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.ar_internal_metadata OWNER TO johan;

--
-- Name: categories; Type: TABLE; Schema: public; Owner: johan
--

CREATE TABLE public.categories (
    id bigint NOT NULL,
    name text NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.categories OWNER TO johan;

--
-- Name: categories_id_seq; Type: SEQUENCE; Schema: public; Owner: johan
--

CREATE SEQUENCE public.categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.categories_id_seq OWNER TO johan;

--
-- Name: categories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: johan
--

ALTER SEQUENCE public.categories_id_seq OWNED BY public.categories.id;


--
-- Name: content_audios; Type: TABLE; Schema: public; Owner: johan
--

CREATE TABLE public.content_audios (
    id bigint NOT NULL,
    content_id integer,
    size integer NOT NULL,
    length integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.content_audios OWNER TO johan;

--
-- Name: content_audios_id_seq; Type: SEQUENCE; Schema: public; Owner: johan
--

CREATE SEQUENCE public.content_audios_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.content_audios_id_seq OWNER TO johan;

--
-- Name: content_audios_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: johan
--

ALTER SEQUENCE public.content_audios_id_seq OWNED BY public.content_audios.id;


--
-- Name: content_lists; Type: TABLE; Schema: public; Owner: johan
--

CREATE TABLE public.content_lists (
    id bigint NOT NULL,
    name text NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.content_lists OWNER TO johan;

--
-- Name: content_lists_id_seq; Type: SEQUENCE; Schema: public; Owner: johan
--

CREATE SEQUENCE public.content_lists_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.content_lists_id_seq OWNER TO johan;

--
-- Name: content_lists_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: johan
--

ALTER SEQUENCE public.content_lists_id_seq OWNED BY public.content_lists.id;


--
-- Name: content_metadata; Type: TABLE; Schema: public; Owner: johan
--

CREATE TABLE public.content_metadata (
    id bigint NOT NULL,
    content_id integer,
    key text NOT NULL,
    value text NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.content_metadata OWNER TO johan;

--
-- Name: content_metadata_id_seq; Type: SEQUENCE; Schema: public; Owner: johan
--

CREATE SEQUENCE public.content_metadata_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.content_metadata_id_seq OWNER TO johan;

--
-- Name: content_metadata_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: johan
--

ALTER SEQUENCE public.content_metadata_id_seq OWNED BY public.content_metadata.id;


--
-- Name: content_resources; Type: TABLE; Schema: public; Owner: johan
--

CREATE TABLE public.content_resources (
    id bigint NOT NULL,
    content_id integer,
    file_name text DEFAULT ''::text NOT NULL,
    bytes integer DEFAULT 0 NOT NULL,
    mime_type text DEFAULT 'unknown'::text NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.content_resources OWNER TO johan;

--
-- Name: content_resources_id_seq; Type: SEQUENCE; Schema: public; Owner: johan
--

CREATE SEQUENCE public.content_resources_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.content_resources_id_seq OWNER TO johan;

--
-- Name: content_resources_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: johan
--

ALTER SEQUENCE public.content_resources_id_seq OWNED BY public.content_resources.id;


--
-- Name: contents; Type: TABLE; Schema: public; Owner: johan
--

CREATE TABLE public.contents (
    id bigint NOT NULL,
    category_id integer,
    daisy_format_id integer,
    title text NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.contents OWNER TO johan;

--
-- Name: contents_id_seq; Type: SEQUENCE; Schema: public; Owner: johan
--

CREATE SEQUENCE public.contents_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.contents_id_seq OWNER TO johan;

--
-- Name: contents_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: johan
--

ALTER SEQUENCE public.contents_id_seq OWNED BY public.contents.id;


--
-- Name: daisy_formats; Type: TABLE; Schema: public; Owner: johan
--

CREATE TABLE public.daisy_formats (
    id bigint NOT NULL,
    format text NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.daisy_formats OWNER TO johan;

--
-- Name: daisy_formats_id_seq; Type: SEQUENCE; Schema: public; Owner: johan
--

CREATE SEQUENCE public.daisy_formats_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.daisy_formats_id_seq OWNER TO johan;

--
-- Name: daisy_formats_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: johan
--

ALTER SEQUENCE public.daisy_formats_id_seq OWNED BY public.daisy_formats.id;


--
-- Name: languages; Type: TABLE; Schema: public; Owner: johan
--

CREATE TABLE public.languages (
    id bigint NOT NULL,
    lang text NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.languages OWNER TO johan;

--
-- Name: languages_id_seq; Type: SEQUENCE; Schema: public; Owner: johan
--

CREATE SEQUENCE public.languages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.languages_id_seq OWNER TO johan;

--
-- Name: languages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: johan
--

ALTER SEQUENCE public.languages_id_seq OWNED BY public.languages.id;


--
-- Name: question_audios; Type: TABLE; Schema: public; Owner: johan
--

CREATE TABLE public.question_audios (
    id bigint NOT NULL,
    question_text_id integer,
    size integer NOT NULL,
    length integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.question_audios OWNER TO johan;

--
-- Name: question_audios_id_seq; Type: SEQUENCE; Schema: public; Owner: johan
--

CREATE SEQUENCE public.question_audios_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.question_audios_id_seq OWNER TO johan;

--
-- Name: question_audios_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: johan
--

ALTER SEQUENCE public.question_audios_id_seq OWNED BY public.question_audios.id;


--
-- Name: question_inputs; Type: TABLE; Schema: public; Owner: johan
--

CREATE TABLE public.question_inputs (
    id bigint NOT NULL,
    question_id integer,
    allow_multiple_selections integer,
    text_numeric integer,
    text_alphanumeric integer,
    audio integer,
    default_value text,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.question_inputs OWNER TO johan;

--
-- Name: question_inputs_id_seq; Type: SEQUENCE; Schema: public; Owner: johan
--

CREATE SEQUENCE public.question_inputs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.question_inputs_id_seq OWNER TO johan;

--
-- Name: question_inputs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: johan
--

ALTER SEQUENCE public.question_inputs_id_seq OWNED BY public.question_inputs.id;


--
-- Name: question_question_texts; Type: TABLE; Schema: public; Owner: johan
--

CREATE TABLE public.question_question_texts (
    id bigint NOT NULL,
    question_id integer,
    question_text_id integer,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.question_question_texts OWNER TO johan;

--
-- Name: question_question_texts_id_seq; Type: SEQUENCE; Schema: public; Owner: johan
--

CREATE SEQUENCE public.question_question_texts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.question_question_texts_id_seq OWNER TO johan;

--
-- Name: question_question_texts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: johan
--

ALTER SEQUENCE public.question_question_texts_id_seq OWNED BY public.question_question_texts.id;


--
-- Name: question_texts; Type: TABLE; Schema: public; Owner: johan
--

CREATE TABLE public.question_texts (
    id bigint NOT NULL,
    language_id integer,
    text text NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.question_texts OWNER TO johan;

--
-- Name: question_texts_id_seq; Type: SEQUENCE; Schema: public; Owner: johan
--

CREATE SEQUENCE public.question_texts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.question_texts_id_seq OWNER TO johan;

--
-- Name: question_texts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: johan
--

ALTER SEQUENCE public.question_texts_id_seq OWNED BY public.question_texts.id;


--
-- Name: question_types; Type: TABLE; Schema: public; Owner: johan
--

CREATE TABLE public.question_types (
    id bigint NOT NULL,
    name text NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.question_types OWNER TO johan;

--
-- Name: question_types_id_seq; Type: SEQUENCE; Schema: public; Owner: johan
--

CREATE SEQUENCE public.question_types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.question_types_id_seq OWNER TO johan;

--
-- Name: question_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: johan
--

ALTER SEQUENCE public.question_types_id_seq OWNED BY public.question_types.id;


--
-- Name: questions; Type: TABLE; Schema: public; Owner: johan
--

CREATE TABLE public.questions (
    id bigint NOT NULL,
    parent_id integer,
    question_type_id integer,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.questions OWNER TO johan;

--
-- Name: questions_id_seq; Type: SEQUENCE; Schema: public; Owner: johan
--

CREATE SEQUENCE public.questions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.questions_id_seq OWNER TO johan;

--
-- Name: questions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: johan
--

ALTER SEQUENCE public.questions_id_seq OWNED BY public.questions.id;


--
-- Name: schema_migrations; Type: TABLE; Schema: public; Owner: johan
--

CREATE TABLE public.schema_migrations (
    version character varying NOT NULL
);


ALTER TABLE public.schema_migrations OWNER TO johan;

--
-- Name: states; Type: TABLE; Schema: public; Owner: johan
--

CREATE TABLE public.states (
    id bigint NOT NULL,
    state text NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.states OWNER TO johan;

--
-- Name: states_id_seq; Type: SEQUENCE; Schema: public; Owner: johan
--

CREATE SEQUENCE public.states_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.states_id_seq OWNER TO johan;

--
-- Name: states_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: johan
--

ALTER SEQUENCE public.states_id_seq OWNED BY public.states.id;


--
-- Name: user_announcements; Type: TABLE; Schema: public; Owner: johan
--

CREATE TABLE public.user_announcements (
    id bigint NOT NULL,
    user_id integer,
    announcement_id integer,
    read_at timestamp without time zone,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.user_announcements OWNER TO johan;

--
-- Name: user_announcements_id_seq; Type: SEQUENCE; Schema: public; Owner: johan
--

CREATE SEQUENCE public.user_announcements_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.user_announcements_id_seq OWNER TO johan;

--
-- Name: user_announcements_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: johan
--

ALTER SEQUENCE public.user_announcements_id_seq OWNED BY public.user_announcements.id;


--
-- Name: user_bookmarks; Type: TABLE; Schema: public; Owner: johan
--

CREATE TABLE public.user_bookmarks (
    id bigint NOT NULL,
    user_id integer,
    content_id integer,
    bookmark_set text NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.user_bookmarks OWNER TO johan;

--
-- Name: user_bookmarks_id_seq; Type: SEQUENCE; Schema: public; Owner: johan
--

CREATE SEQUENCE public.user_bookmarks_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.user_bookmarks_id_seq OWNER TO johan;

--
-- Name: user_bookmarks_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: johan
--

ALTER SEQUENCE public.user_bookmarks_id_seq OWNED BY public.user_bookmarks.id;


--
-- Name: user_contents; Type: TABLE; Schema: public; Owner: johan
--

CREATE TABLE public.user_contents (
    id bigint NOT NULL,
    user_id integer,
    content_id integer,
    content_list_id integer,
    content_list_v1_id integer DEFAULT 3,
    return integer NOT NULL,
    returned integer DEFAULT 0 NOT NULL,
    return_at timestamp without time zone,
    state_id integer,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.user_contents OWNER TO johan;

--
-- Name: user_contents_id_seq; Type: SEQUENCE; Schema: public; Owner: johan
--

CREATE SEQUENCE public.user_contents_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.user_contents_id_seq OWNER TO johan;

--
-- Name: user_contents_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: johan
--

ALTER SEQUENCE public.user_contents_id_seq OWNED BY public.user_contents.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: johan
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    username text NOT NULL,
    password text,
    terms_accepted integer DEFAULT 0 NOT NULL,
    log integer DEFAULT 0 NOT NULL,
    activated boolean DEFAULT false NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE public.users OWNER TO johan;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: johan
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO johan;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: johan
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: announcement_audios id; Type: DEFAULT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.announcement_audios ALTER COLUMN id SET DEFAULT nextval('public.announcement_audios_id_seq'::regclass);


--
-- Name: announcement_texts id; Type: DEFAULT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.announcement_texts ALTER COLUMN id SET DEFAULT nextval('public.announcement_texts_id_seq'::regclass);


--
-- Name: announcements id; Type: DEFAULT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.announcements ALTER COLUMN id SET DEFAULT nextval('public.announcements_id_seq'::regclass);


--
-- Name: categories id; Type: DEFAULT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.categories ALTER COLUMN id SET DEFAULT nextval('public.categories_id_seq'::regclass);


--
-- Name: content_audios id; Type: DEFAULT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.content_audios ALTER COLUMN id SET DEFAULT nextval('public.content_audios_id_seq'::regclass);


--
-- Name: content_lists id; Type: DEFAULT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.content_lists ALTER COLUMN id SET DEFAULT nextval('public.content_lists_id_seq'::regclass);


--
-- Name: content_metadata id; Type: DEFAULT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.content_metadata ALTER COLUMN id SET DEFAULT nextval('public.content_metadata_id_seq'::regclass);


--
-- Name: content_resources id; Type: DEFAULT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.content_resources ALTER COLUMN id SET DEFAULT nextval('public.content_resources_id_seq'::regclass);


--
-- Name: contents id; Type: DEFAULT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.contents ALTER COLUMN id SET DEFAULT nextval('public.contents_id_seq'::regclass);


--
-- Name: daisy_formats id; Type: DEFAULT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.daisy_formats ALTER COLUMN id SET DEFAULT nextval('public.daisy_formats_id_seq'::regclass);


--
-- Name: languages id; Type: DEFAULT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.languages ALTER COLUMN id SET DEFAULT nextval('public.languages_id_seq'::regclass);


--
-- Name: question_audios id; Type: DEFAULT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.question_audios ALTER COLUMN id SET DEFAULT nextval('public.question_audios_id_seq'::regclass);


--
-- Name: question_inputs id; Type: DEFAULT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.question_inputs ALTER COLUMN id SET DEFAULT nextval('public.question_inputs_id_seq'::regclass);


--
-- Name: question_question_texts id; Type: DEFAULT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.question_question_texts ALTER COLUMN id SET DEFAULT nextval('public.question_question_texts_id_seq'::regclass);


--
-- Name: question_texts id; Type: DEFAULT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.question_texts ALTER COLUMN id SET DEFAULT nextval('public.question_texts_id_seq'::regclass);


--
-- Name: question_types id; Type: DEFAULT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.question_types ALTER COLUMN id SET DEFAULT nextval('public.question_types_id_seq'::regclass);


--
-- Name: questions id; Type: DEFAULT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.questions ALTER COLUMN id SET DEFAULT nextval('public.questions_id_seq'::regclass);


--
-- Name: states id; Type: DEFAULT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.states ALTER COLUMN id SET DEFAULT nextval('public.states_id_seq'::regclass);


--
-- Name: user_announcements id; Type: DEFAULT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.user_announcements ALTER COLUMN id SET DEFAULT nextval('public.user_announcements_id_seq'::regclass);


--
-- Name: user_bookmarks id; Type: DEFAULT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.user_bookmarks ALTER COLUMN id SET DEFAULT nextval('public.user_bookmarks_id_seq'::regclass);


--
-- Name: user_contents id; Type: DEFAULT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.user_contents ALTER COLUMN id SET DEFAULT nextval('public.user_contents_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Data for Name: announcement_audios; Type: TABLE DATA; Schema: public; Owner: johan
--

COPY public.announcement_audios (id, announcement_text_id, size, length, created_at, updated_at) FROM stdin;
1	1	24677	2594	2019-05-17 19:47:32.833369	2019-05-17 19:47:32.833369
2	2	27392	2880	2019-05-17 19:47:32.835999	2019-05-17 19:47:32.835999
3	3	53310	5680	2019-05-17 19:47:32.838435	2019-05-17 19:47:32.838435
4	4	63226	6952	2019-05-17 19:47:32.841532	2019-05-17 19:47:32.841532
\.


--
-- Data for Name: announcement_texts; Type: TABLE DATA; Schema: public; Owner: johan
--

COPY public.announcement_texts (id, announcement_id, text, language_id, created_at, updated_at) FROM stdin;
1	1	Welcome to the Kolibre Daisy Online demo service.	1	2019-05-17 19:47:32.811281	2019-05-17 19:47:32.811281
2	1	Välkommen till Kolibres Daisy Online demo tjänst.	2	2019-05-17 19:47:32.81681	2019-05-17 19:47:32.81681
3	2	Feel free to use and explore this service. Remember to mark the announcements as read when you have read them.	1	2019-05-17 19:47:32.819698	2019-05-17 19:47:32.819698
4	2	Du kan fritt använda och utforska denna tjänst. Kom ihåg att markera meddelandena som lästa efter att du läst dem.	2	2019-05-17 19:47:32.823132	2019-05-17 19:47:32.823132
\.


--
-- Data for Name: announcements; Type: TABLE DATA; Schema: public; Owner: johan
--

COPY public.announcements (id, category, priority, created_at, updated_at) FROM stdin;
1	INFORMATION	MEDIUM	2019-05-17 19:47:32.765478	2019-05-17 19:47:32.765478
2	INFORMATION	LOW	2019-05-17 19:47:32.767662	2019-05-17 19:47:32.767662
\.


--
-- Data for Name: ar_internal_metadata; Type: TABLE DATA; Schema: public; Owner: johan
--

COPY public.ar_internal_metadata (key, value, created_at, updated_at) FROM stdin;
environment	development	2019-05-17 19:46:34.723135	2019-05-17 19:46:34.723135
\.


--
-- Data for Name: categories; Type: TABLE DATA; Schema: public; Owner: johan
--

COPY public.categories (id, name, created_at, updated_at) FROM stdin;
1	BOOK	2019-05-17 19:47:32.67828	2019-05-17 19:47:32.67828
2	MAGAZINE	2019-05-17 19:47:32.683665	2019-05-17 19:47:32.683665
3	NEWSPAPER	2019-05-17 19:47:32.685381	2019-05-17 19:47:32.685381
4	OTHER	2019-05-17 19:47:32.687061	2019-05-17 19:47:32.687061
\.


--
-- Data for Name: content_audios; Type: TABLE DATA; Schema: public; Owner: johan
--

COPY public.content_audios (id, content_id, size, length, created_at, updated_at) FROM stdin;
1	1	44724	6315	2019-05-17 19:47:32.870894	2019-05-17 19:47:32.870894
2	2	16139	5201	2019-05-17 19:47:32.874011	2019-05-17 19:47:32.874011
3	3	20335	1945	2019-05-17 19:47:32.876255	2019-05-17 19:47:32.876255
\.


--
-- Data for Name: content_lists; Type: TABLE DATA; Schema: public; Owner: johan
--

COPY public.content_lists (id, name, created_at, updated_at) FROM stdin;
1	bookshelf	2019-05-17 19:47:32.726133	2019-05-17 19:47:32.726133
2	new	2019-05-17 19:47:32.728068	2019-05-17 19:47:32.728068
3	issued	2019-05-17 19:47:32.729634	2019-05-17 19:47:32.729634
4	expired	2019-05-17 19:47:32.731149	2019-05-17 19:47:32.731149
20	search	2019-05-17 19:47:32.732838	2019-05-17 19:47:32.732838
30	browse	2019-05-17 19:47:32.734572	2019-05-17 19:47:32.734572
\.


--
-- Data for Name: content_metadata; Type: TABLE DATA; Schema: public; Owner: johan
--

COPY public.content_metadata (id, content_id, key, value, created_at, updated_at) FROM stdin;
1	1	dc:creator	Henry James	2019-05-17 19:47:32.88678	2019-05-17 19:47:32.88678
2	1	dc:date	2009-06-22	2019-05-17 19:47:32.889865	2019-05-17 19:47:32.889865
3	1	dc:format	Daisy 2.02	2019-05-17 19:47:32.892881	2019-05-17 19:47:32.892881
4	1	dc:identifier	Unknown	2019-05-17 19:47:32.895463	2019-05-17 19:47:32.895463
5	1	dc:language	en	2019-05-17 19:47:32.897571	2019-05-17 19:47:32.897571
6	1	dc:publisher	DC	2019-05-17 19:47:32.899989	2019-05-17 19:47:32.899989
7	1	dc:source	The galaxy vol. 8 no. 1 (July 1869),   pages 49-68	2019-05-17 19:47:32.902089	2019-05-17 19:47:32.902089
8	1	dc:subject	Relationships, betrayal	2019-05-17 19:47:32.904243	2019-05-17 19:47:32.904243
9	1	dc:title	A light Man	2019-05-17 19:47:32.906378	2019-05-17 19:47:32.906378
10	1	ncc:charset	utf-8	2019-05-17 19:47:32.908534	2019-05-17 19:47:32.908534
11	1	ncc:depth	1	2019-05-17 19:47:32.910961	2019-05-17 19:47:32.910961
12	1	ncc:files	36	2019-05-17 19:47:32.912992	2019-05-17 19:47:32.912992
13	1	ncc:footnotes	0	2019-05-17 19:47:32.918475	2019-05-17 19:47:32.918475
14	1	ncc:generator	EasePublisher 2.13 Build 163 # 044FS2212172434	2019-05-17 19:47:32.921072	2019-05-17 19:47:32.921072
15	1	ncc:kbytesize	35930	2019-05-17 19:47:32.923483	2019-05-17 19:47:32.923483
16	1	ncc:maxpagenormal	26	2019-05-17 19:47:32.925791	2019-05-17 19:47:32.925791
17	1	ncc:multimediatype	audioNcc	2019-05-17 19:47:32.928266	2019-05-17 19:47:32.928266
18	1	ncc:narrator	OM	2019-05-17 19:47:32.930323	2019-05-17 19:47:32.930323
19	1	ncc:pagefront	0	2019-05-17 19:47:32.932424	2019-05-17 19:47:32.932424
20	1	ncc:pagenormal	26	2019-05-17 19:47:32.934471	2019-05-17 19:47:32.934471
21	1	ncc:pagespecial	0	2019-05-17 19:47:32.936946	2019-05-17 19:47:32.936946
22	1	ncc:prodnotes	0	2019-05-17 19:47:32.939265	2019-05-17 19:47:32.939265
23	1	ncc:setinfo	1 of 1	2019-05-17 19:47:32.941402	2019-05-17 19:47:32.941402
24	1	ncc:sidebars	0	2019-05-17 19:47:32.944068	2019-05-17 19:47:32.944068
25	1	ncc:sourcedate	2009-07-18	2019-05-17 19:47:32.946427	2019-05-17 19:47:32.946427
26	1	ncc:sourceedition	1.	2019-05-17 19:47:32.94885	2019-05-17 19:47:32.94885
27	1	ncc:sourcepublisher	The galaxy vol. 8 no. 1 (July 1869),   pages 49-68	2019-05-17 19:47:32.950977	2019-05-17 19:47:32.950977
28	1	ncc:sourcerights	Gutenberg project	2019-05-17 19:47:32.953364	2019-05-17 19:47:32.953364
29	1	ncc:sourcetitle	A light man	2019-05-17 19:47:32.955693	2019-05-17 19:47:32.955693
30	1	ncc:tocitems	43	2019-05-17 19:47:32.957795	2019-05-17 19:47:32.957795
31	1	ncc:totaltime	01:27:15	2019-05-17 19:47:32.960514	2019-05-17 19:47:32.960514
32	1	prod:ep_update		2019-05-17 19:47:32.96293	2019-05-17 19:47:32.96293
33	1	prod:last_used_id		2019-05-17 19:47:32.965215	2019-05-17 19:47:32.965215
34	2	dc:creator	WikiHow	2019-05-17 19:47:32.967299	2019-05-17 19:47:32.967299
35	2	dc:date	2009-04-26	2019-05-17 19:47:32.96972	2019-05-17 19:47:32.96972
36	2	dc:format	ANSI/NIZO Z39.86-2005	2019-05-17 19:47:32.972016	2019-05-17 19:47:32.972016
37	2	dc:identifier	5078727220897727718	2019-05-17 19:47:32.974239	2019-05-17 19:47:32.974239
38	2	dc:language	en-IN	2019-05-17 19:47:32.976761	2019-05-17 19:47:32.976761
39	2	dc:publisher	DAISY India	2019-05-17 19:47:32.979175	2019-05-17 19:47:32.979175
40	2	dc:subject	A Short Manual for Disaster Management	2019-05-17 19:47:32.981384	2019-05-17 19:47:32.981384
41	2	dc:title	ARE YOU READY?	2019-05-17 19:47:32.983332	2019-05-17 19:47:32.983332
42	3	dc:title	Climbing the Highest Mountain	2019-05-17 19:47:32.985318	2019-05-17 19:47:32.985318
43	3	dc:creator	Per Sennels	2019-05-17 19:47:32.987692	2019-05-17 19:47:32.987692
44	3	dc:date	2003-05-08	2019-05-17 19:47:32.989925	2019-05-17 19:47:32.989925
45	3	dc:format	Daisy 2.02	2019-05-17 19:47:32.992124	2019-05-17 19:47:32.992124
46	3	dc:identifier	skipdemo-amsterdam	2019-05-17 19:47:32.994336	2019-05-17 19:47:32.994336
47	3	dc:language	en	2019-05-17 19:47:32.996384	2019-05-17 19:47:32.996384
48	3	dc:publisher	DAISY Consortium	2019-05-17 19:47:32.998502	2019-05-17 19:47:32.998502
49	3	dc:subject	Mountains	2019-05-17 19:47:33.000619	2019-05-17 19:47:33.000619
50	3	ncc:pagefront	0	2019-05-17 19:47:33.0029	2019-05-17 19:47:33.0029
51	3	ncc:pagenormal	8	2019-05-17 19:47:33.005252	2019-05-17 19:47:33.005252
52	3	ncc:pagespecial	0	2019-05-17 19:47:33.007329	2019-05-17 19:47:33.007329
53	3	ncc:setinfo	1 of 1	2019-05-17 19:47:33.009553	2019-05-17 19:47:33.009553
54	3	ncc:depth	3	2019-05-17 19:47:33.011837	2019-05-17 19:47:33.011837
55	3	ncc:maxpagenormal	8	2019-05-17 19:47:33.013925	2019-05-17 19:47:33.013925
56	3	ncc:multimediatype	audioNcc	2019-05-17 19:47:33.016065	2019-05-17 19:47:33.016065
57	3	ncc:narrator	Joan Dahm-Simonsen	2019-05-17 19:47:33.018393	2019-05-17 19:47:33.018393
58	3	ncc:producer	Huseby kompetansesenter	2019-05-17 19:47:33.020683	2019-05-17 19:47:33.020683
59	3	ncc:generator	Skippability Tweaker 0.1.53	2019-05-17 19:47:33.023235	2019-05-17 19:47:33.023235
60	3	ncc:sidebars	4	2019-05-17 19:47:33.025401	2019-05-17 19:47:33.025401
61	3	ncc:prodnotes	2	2019-05-17 19:47:33.027465	2019-05-17 19:47:33.027465
62	3	ncc:footnotes	1	2019-05-17 19:47:33.029606	2019-05-17 19:47:33.029606
63	3	ncc:totaltime	00:05:27	2019-05-17 19:47:33.031786	2019-05-17 19:47:33.031786
64	3	ncc:charset	windows-1252	2019-05-17 19:47:33.034054	2019-05-17 19:47:33.034054
65	3	ncc:files	20	2019-05-17 19:47:33.036315	2019-05-17 19:47:33.036315
66	3	ncc:kbytesize	1975	2019-05-17 19:47:33.03825	2019-05-17 19:47:33.03825
67	3	ncc:tocitems	23	2019-05-17 19:47:33.040429	2019-05-17 19:47:33.040429
\.


--
-- Data for Name: content_resources; Type: TABLE DATA; Schema: public; Owner: johan
--

COPY public.content_resources (id, content_id, file_name, bytes, mime_type, created_at, updated_at) FROM stdin;
1	1	01_A_light_Man.mp3	45531	application/octet-stream	2019-05-17 19:47:33.050566	2019-05-17 19:47:33.050566
2	1	02_Epigraph.mp3	176822	application/octet-stream	2019-05-17 19:47:33.053542	2019-05-17 19:47:33.053542
3	1	03_April_4th_1857.mp3	1540205	application/octet-stream	2019-05-17 19:47:33.055973	2019-05-17 19:47:33.055973
4	1	04_7th_.mp3	1204845	application/octet-stream	2019-05-17 19:47:33.058755	2019-05-17 19:47:33.058755
5	1	05_D_the_14th_.mp3	8934948	application/octet-stream	2019-05-17 19:47:33.061123	2019-05-17 19:47:33.061123
6	1	06_19th_.mp3	2863908	application/octet-stream	2019-05-17 19:47:33.063388	2019-05-17 19:47:33.063388
7	1	07_22d_.mp3	4464640	application/octet-stream	2019-05-17 19:47:33.065553	2019-05-17 19:47:33.065553
8	1	08_26th_.mp3	1939748	application/octet-stream	2019-05-17 19:47:33.067965	2019-05-17 19:47:33.067965
9	1	09_May_3d_.mp3	1940662	application/octet-stream	2019-05-17 19:47:33.070172	2019-05-17 19:47:33.070172
10	1	10_8th_.mp3	2005028	application/octet-stream	2019-05-17 19:47:33.075418	2019-05-17 19:47:33.075418
11	1	11_13th_.mp3	741302	application/octet-stream	2019-05-17 19:47:33.078323	2019-05-17 19:47:33.078323
12	1	12_18th_.mp3	691565	application/octet-stream	2019-05-17 19:47:33.080687	2019-05-17 19:47:33.080687
13	1	13_20th_.mp3	2834468	application/octet-stream	2019-05-17 19:47:33.082743	2019-05-17 19:47:33.082743
14	1	14_21st_.mp3	1639497	application/octet-stream	2019-05-17 19:47:33.085208	2019-05-17 19:47:33.085208
15	1	15_22d_.mp3	451657	application/octet-stream	2019-05-17 19:47:33.087732	2019-05-17 19:47:33.087732
16	1	16_23d_.mp3	4836205	application/octet-stream	2019-05-17 19:47:33.089978	2019-05-17 19:47:33.089978
17	1	17_24th_.mp3	348342	application/octet-stream	2019-05-17 19:47:33.092338	2019-05-17 19:47:33.092338
18	1	er_book_info.xml	1108	text/plain	2019-05-17 19:47:33.094843	2019-05-17 19:47:33.094843
19	1	icth0001.smil	1086	text/plain	2019-05-17 19:47:33.097005	2019-05-17 19:47:33.097005
20	1	icth0002.smil	2128	text/plain	2019-05-17 19:47:33.098873	2019-05-17 19:47:33.098873
21	1	icth0003.smil	6888	text/plain	2019-05-17 19:47:33.101254	2019-05-17 19:47:33.101254
22	1	icth0004.smil	4667	text/plain	2019-05-17 19:47:33.103693	2019-05-17 19:47:33.103693
23	1	icth0005.smil	27641	text/plain	2019-05-17 19:47:33.105992	2019-05-17 19:47:33.105992
24	1	icth0006.smil	10464	text/plain	2019-05-17 19:47:33.108539	2019-05-17 19:47:33.108539
25	1	icth0007.smil	12533	text/plain	2019-05-17 19:47:33.110692	2019-05-17 19:47:33.110692
26	1	icth0008.smil	6417	text/plain	2019-05-17 19:47:33.113099	2019-05-17 19:47:33.113099
27	1	icth0009.smil	6967	text/plain	2019-05-17 19:47:33.115078	2019-05-17 19:47:33.115078
28	1	icth000a.smil	6154	text/plain	2019-05-17 19:47:33.117152	2019-05-17 19:47:33.117152
29	1	icth000b.smil	2248	text/plain	2019-05-17 19:47:33.119311	2019-05-17 19:47:33.119311
30	1	icth000c.smil	2417	text/plain	2019-05-17 19:47:33.121554	2019-05-17 19:47:33.121554
31	1	icth000d.smil	7990	text/plain	2019-05-17 19:47:33.123922	2019-05-17 19:47:33.123922
32	1	icth000e.smil	5311	text/plain	2019-05-17 19:47:33.126618	2019-05-17 19:47:33.126618
33	1	icth000f.smil	2517	text/plain	2019-05-17 19:47:33.129553	2019-05-17 19:47:33.129553
34	1	icth0010.smil	17455	text/plain	2019-05-17 19:47:33.131553	2019-05-17 19:47:33.131553
35	1	icth0011.smil	2217	text/plain	2019-05-17 19:47:33.133445	2019-05-17 19:47:33.133445
36	1	master.smil	1866	text/plain	2019-05-17 19:47:33.135556	2019-05-17 19:47:33.135556
37	1	ncc.html	5759	text/html	2019-05-17 19:47:33.137704	2019-05-17 19:47:33.137704
38	1	smil10.dtd	7496	text/html	2019-05-17 19:47:33.140195	2019-05-17 19:47:33.140195
39	1	xhtml-lat1.ent	11789	text/html	2019-05-17 19:47:33.142347	2019-05-17 19:47:33.142347
40	1	xhtml-special.ent	4144	text/html	2019-05-17 19:47:33.144419	2019-05-17 19:47:33.144419
41	1	xhtml-symbol.ent	14127	text/html	2019-05-17 19:47:33.146633	2019-05-17 19:47:33.146633
42	1	xhtml1-transitional.dtd	33441	text/html	2019-05-17 19:47:33.148774	2019-05-17 19:47:33.148774
43	2	AreYouReadyV3.xml	89235	text/plain	2019-05-17 19:47:33.150757	2019-05-17 19:47:33.150757
44	2	dtbookbasic.css	12801	text/x-c	2019-05-17 19:47:33.152928	2019-05-17 19:47:33.152928
45	2	image1.jpg	6168	image/jpeg	2019-05-17 19:47:33.154896	2019-05-17 19:47:33.154896
46	2	image11.jpg	5586	image/jpeg	2019-05-17 19:47:33.157505	2019-05-17 19:47:33.157505
47	2	image12.jpg	4826	image/jpeg	2019-05-17 19:47:33.159784	2019-05-17 19:47:33.159784
48	2	image13.jpg	12586	image/jpeg	2019-05-17 19:47:33.161829	2019-05-17 19:47:33.161829
49	2	image14.jpg	17587	image/jpeg	2019-05-17 19:47:33.164047	2019-05-17 19:47:33.164047
50	2	image2.jpg	8577	image/jpeg	2019-05-17 19:47:33.16614	2019-05-17 19:47:33.16614
51	2	image3.jpg	5981	image/jpeg	2019-05-17 19:47:33.168317	2019-05-17 19:47:33.168317
52	2	image4.jpg	4720	image/jpeg	2019-05-17 19:47:33.170714	2019-05-17 19:47:33.170714
53	2	image5.jpg	6589	image/jpeg	2019-05-17 19:47:33.172862	2019-05-17 19:47:33.172862
54	2	speechgen.ncx	18280	text/plain	2019-05-17 19:47:33.174951	2019-05-17 19:47:33.174951
55	2	speechgen.opf	8897	text/plain	2019-05-17 19:47:33.17714	2019-05-17 19:47:33.17714
56	2	speechgen0001.mp3	32757	application/octet-stream	2019-05-17 19:47:33.179269	2019-05-17 19:47:33.179269
57	2	speechgen0001.smil	882	text/plain	2019-05-17 19:47:33.181648	2019-05-17 19:47:33.181648
58	2	speechgen0002.mp3	215823	application/octet-stream	2019-05-17 19:47:33.183765	2019-05-17 19:47:33.183765
59	2	speechgen0002.smil	2716	text/plain	2019-05-17 19:47:33.185965	2019-05-17 19:47:33.185965
60	2	speechgen0003.mp3	285257	application/octet-stream	2019-05-17 19:47:33.188147	2019-05-17 19:47:33.188147
61	2	speechgen0003.smil	5457	text/plain	2019-05-17 19:47:33.190364	2019-05-17 19:47:33.190364
62	2	speechgen0004.mp3	228832	application/octet-stream	2019-05-17 19:47:33.192637	2019-05-17 19:47:33.192637
63	2	speechgen0004.smil	1477	text/plain	2019-05-17 19:47:33.194926	2019-05-17 19:47:33.194926
64	2	speechgen0005.mp3	1865456	application/octet-stream	2019-05-17 19:47:33.197467	2019-05-17 19:47:33.197467
65	2	speechgen0005.smil	10463	text/plain	2019-05-17 19:47:33.199875	2019-05-17 19:47:33.199875
66	2	speechgen0006.mp3	735399	application/octet-stream	2019-05-17 19:47:33.202122	2019-05-17 19:47:33.202122
67	2	speechgen0006.smil	4470	text/plain	2019-05-17 19:47:33.204442	2019-05-17 19:47:33.204442
68	2	speechgen0007.mp3	105482	application/octet-stream	2019-05-17 19:47:33.207011	2019-05-17 19:47:33.207011
69	2	speechgen0007.smil	1438	text/plain	2019-05-17 19:47:33.209166	2019-05-17 19:47:33.209166
70	2	speechgen0008.mp3	95294	application/octet-stream	2019-05-17 19:47:33.211546	2019-05-17 19:47:33.211546
71	2	speechgen0008.smil	1491	text/plain	2019-05-17 19:47:33.213959	2019-05-17 19:47:33.213959
72	2	speechgen0009.mp3	794801	application/octet-stream	2019-05-17 19:47:33.216187	2019-05-17 19:47:33.216187
73	2	speechgen0009.smil	7443	text/plain	2019-05-17 19:47:33.221013	2019-05-17 19:47:33.221013
74	2	speechgen0010.mp3	258299	application/octet-stream	2019-05-17 19:47:33.223469	2019-05-17 19:47:33.223469
75	2	speechgen0010.smil	2873	text/plain	2019-05-17 19:47:33.225741	2019-05-17 19:47:33.225741
76	2	speechgen0011.mp3	249835	application/octet-stream	2019-05-17 19:47:33.227973	2019-05-17 19:47:33.227973
77	2	speechgen0011.smil	2192	text/plain	2019-05-17 19:47:33.230194	2019-05-17 19:47:33.230194
78	2	speechgen0012.mp3	225071	application/octet-stream	2019-05-17 19:47:33.232513	2019-05-17 19:47:33.232513
79	2	speechgen0012.smil	2285	text/plain	2019-05-17 19:47:33.23481	2019-05-17 19:47:33.23481
80	2	speechgen0013.mp3	1677374	application/octet-stream	2019-05-17 19:47:33.237023	2019-05-17 19:47:33.237023
81	2	speechgen0013.smil	11510	text/plain	2019-05-17 19:47:33.239132	2019-05-17 19:47:33.239132
82	2	speechgen0014.mp3	372872	application/octet-stream	2019-05-17 19:47:33.241653	2019-05-17 19:47:33.241653
83	2	speechgen0014.smil	2480	text/plain	2019-05-17 19:47:33.243982	2019-05-17 19:47:33.243982
84	2	speechgen0015.mp3	439327	application/octet-stream	2019-05-17 19:47:33.246145	2019-05-17 19:47:33.246145
85	2	speechgen0015.smil	3243	text/plain	2019-05-17 19:47:33.24864	2019-05-17 19:47:33.24864
86	2	speechgen0016.mp3	121469	application/octet-stream	2019-05-17 19:47:33.251008	2019-05-17 19:47:33.251008
87	2	speechgen0016.smil	1691	text/plain	2019-05-17 19:47:33.253211	2019-05-17 19:47:33.253211
88	2	speechgen0017.mp3	2289110	application/octet-stream	2019-05-17 19:47:33.255404	2019-05-17 19:47:33.255404
89	2	speechgen0017.smil	17070	text/plain	2019-05-17 19:47:33.258212	2019-05-17 19:47:33.258212
90	2	speechgen0018.mp3	1220493	application/octet-stream	2019-05-17 19:47:33.260872	2019-05-17 19:47:33.260872
91	2	speechgen0018.smil	9825	text/plain	2019-05-17 19:47:33.263698	2019-05-17 19:47:33.263698
92	2	speechgen0019.mp3	181185	application/octet-stream	2019-05-17 19:47:33.2662	2019-05-17 19:47:33.2662
93	2	speechgen0019.smil	1640	text/plain	2019-05-17 19:47:33.268345	2019-05-17 19:47:33.268345
94	2	speechgen0020.mp3	1113756	application/octet-stream	2019-05-17 19:47:33.270906	2019-05-17 19:47:33.270906
95	2	speechgen0020.smil	7023	text/plain	2019-05-17 19:47:33.273884	2019-05-17 19:47:33.273884
96	2	speechgen0021.mp3	446694	application/octet-stream	2019-05-17 19:47:33.276066	2019-05-17 19:47:33.276066
97	2	speechgen0021.smil	3453	text/plain	2019-05-17 19:47:33.278713	2019-05-17 19:47:33.278713
98	2	speechgen0022.mp3	191216	application/octet-stream	2019-05-17 19:47:33.281248	2019-05-17 19:47:33.281248
99	2	speechgen0022.smil	1492	text/plain	2019-05-17 19:47:33.283567	2019-05-17 19:47:33.283567
100	2	speechgen0023.mp3	1083977	application/octet-stream	2019-05-17 19:47:33.285654	2019-05-17 19:47:33.285654
101	2	speechgen0023.smil	6373	text/plain	2019-05-17 19:47:33.287965	2019-05-17 19:47:33.287965
102	2	speechgen0024.mp3	354690	application/octet-stream	2019-05-17 19:47:33.290116	2019-05-17 19:47:33.290116
103	2	speechgen0024.smil	2690	text/plain	2019-05-17 19:47:33.292403	2019-05-17 19:47:33.292403
104	2	speechgen0025.mp3	193410	application/octet-stream	2019-05-17 19:47:33.294566	2019-05-17 19:47:33.294566
105	2	speechgen0025.smil	1835	text/plain	2019-05-17 19:47:33.296513	2019-05-17 19:47:33.296513
106	2	speechgen0026.mp3	112222	application/octet-stream	2019-05-17 19:47:33.298483	2019-05-17 19:47:33.298483
107	2	speechgen0026.smil	1493	text/plain	2019-05-17 19:47:33.300503	2019-05-17 19:47:33.300503
108	2	speechgen0027.mp3	605466	application/octet-stream	2019-05-17 19:47:33.302571	2019-05-17 19:47:33.302571
109	2	speechgen0027.smil	4580	text/plain	2019-05-17 19:47:33.304732	2019-05-17 19:47:33.304732
110	2	speechgen0028.mp3	136516	application/octet-stream	2019-05-17 19:47:33.306912	2019-05-17 19:47:33.306912
111	2	speechgen0028.smil	1430	text/plain	2019-05-17 19:47:33.309023	2019-05-17 19:47:33.309023
112	2	speechgen0029.mp3	92943	application/octet-stream	2019-05-17 19:47:33.311054	2019-05-17 19:47:33.311054
113	2	speechgen0029.smil	1439	text/plain	2019-05-17 19:47:33.313045	2019-05-17 19:47:33.313045
114	2	speechgen0030.mp3	112535	application/octet-stream	2019-05-17 19:47:33.315476	2019-05-17 19:47:33.315476
115	2	speechgen0030.smil	1493	text/plain	2019-05-17 19:47:33.317865	2019-05-17 19:47:33.317865
116	2	speechgen0031.mp3	1319863	application/octet-stream	2019-05-17 19:47:33.320062	2019-05-17 19:47:33.320062
117	2	speechgen0031.smil	10153	text/plain	2019-05-17 19:47:33.322254	2019-05-17 19:47:33.322254
118	2	speechgen0032.mp3	584150	application/octet-stream	2019-05-17 19:47:33.324873	2019-05-17 19:47:33.324873
119	2	speechgen0032.smil	5658	text/plain	2019-05-17 19:47:33.327289	2019-05-17 19:47:33.327289
120	2	speechgen0033.mp3	256104	application/octet-stream	2019-05-17 19:47:33.329404	2019-05-17 19:47:33.329404
121	2	speechgen0033.smil	1850	text/plain	2019-05-17 19:47:33.331826	2019-05-17 19:47:33.331826
122	2	speechgen0034.mp3	284630	application/octet-stream	2019-05-17 19:47:33.334217	2019-05-17 19:47:33.334217
123	2	speechgen0034.smil	2480	text/plain	2019-05-17 19:47:33.336402	2019-05-17 19:47:33.336402
124	2	tpbnarrator.res	9458	text/html	2019-05-17 19:47:33.338726	2019-05-17 19:47:33.338726
125	2	tpbnarrator_res.mp3	117656	application/octet-stream	2019-05-17 19:47:33.341141	2019-05-17 19:47:33.341141
126	3	ncconlydemo.html	3642	application/xml	2019-05-17 19:47:33.343542	2019-05-17 19:47:33.343542
127	3	bagw0008.smil	2714	application/xml	2019-05-17 19:47:33.345632	2019-05-17 19:47:33.345632
128	3	bagw0017.mp3	124760	audio/mpeg	2019-05-17 19:47:33.347848	2019-05-17 19:47:33.347848
129	3	bagw0019.mp3	180558	audio/mpeg	2019-05-17 19:47:33.350135	2019-05-17 19:47:33.350135
130	3	bagw0006.smil	2765	application/xml	2019-05-17 19:47:33.352177	2019-05-17 19:47:33.352177
131	3	bagw001B.mp3	285570	audio/mpeg	2019-05-17 19:47:33.356723	2019-05-17 19:47:33.356723
132	3	bagw0014.mp3	391836	audio/mpeg	2019-05-17 19:47:33.358988	2019-05-17 19:47:33.358988
133	3	bagw0007.smil	1567	application/xml	2019-05-17 19:47:33.361162	2019-05-17 19:47:33.361162
134	3	bagw0018.mp3	65515	audio/mpeg	2019-05-17 19:47:33.363097	2019-05-17 19:47:33.363097
135	3	bagw001A.mp3	129776	audio/mpeg	2019-05-17 19:47:33.365241	2019-05-17 19:47:33.365241
136	3	bagw0005.smil	1130	application/xml	2019-05-17 19:47:33.367507	2019-05-17 19:47:33.367507
137	3	master.smil	1196	application/xml	2019-05-17 19:47:33.369514	2019-05-17 19:47:33.369514
138	3	bagw0003.smil	2419	application/xml	2019-05-17 19:47:33.371761	2019-05-17 19:47:33.371761
139	3	ncc.html	4296	application/xml	2019-05-17 19:47:33.373919	2019-05-17 19:47:33.373919
140	3	bagw0002.smil	1334	application/xml	2019-05-17 19:47:33.376124	2019-05-17 19:47:33.376124
141	3	narrator_1.css	5204	text/plain	2019-05-17 19:47:33.378044	2019-05-17 19:47:33.378044
142	3	default_1.css	5053	text/plain	2019-05-17 19:47:33.380081	2019-05-17 19:47:33.380081
143	3	bagw001C.mp3	451552	audio/mpeg	2019-05-17 19:47:33.382307	2019-05-17 19:47:33.382307
144	3	bagw0001.smil	1728	application/xml	2019-05-17 19:47:33.384218	2019-05-17 19:47:33.384218
145	3	bagw001D.mp3	335882	audio/mpeg	2019-05-17 19:47:33.386328	2019-05-17 19:47:33.386328
146	3	bagw0004.smil	3047	application/xml	2019-05-17 19:47:33.388309	2019-05-17 19:47:33.388309
\.


--
-- Data for Name: contents; Type: TABLE DATA; Schema: public; Owner: johan
--

COPY public.contents (id, category_id, daisy_format_id, title, created_at, updated_at) FROM stdin;
1	1	1	A light Man	2019-05-17 19:47:32.853192	2019-05-17 19:47:32.853192
2	1	2	Are you ready?	2019-05-17 19:47:32.856845	2019-05-17 19:47:32.856845
3	1	1	Climbing the Highest Mountain	2019-05-17 19:47:32.859973	2019-05-17 19:47:32.859973
\.


--
-- Data for Name: daisy_formats; Type: TABLE DATA; Schema: public; Owner: johan
--

COPY public.daisy_formats (id, format, created_at, updated_at) FROM stdin;
1	Daisy 2.02	2019-05-17 19:47:32.755182	2019-05-17 19:47:32.755182
2	ANSI/NISO Z39.86-2005	2019-05-17 19:47:32.75693	2019-05-17 19:47:32.75693
3	PDTB2	2019-05-17 19:47:32.758217	2019-05-17 19:47:32.758217
\.


--
-- Data for Name: languages; Type: TABLE DATA; Schema: public; Owner: johan
--

COPY public.languages (id, lang, created_at, updated_at) FROM stdin;
1	en	2019-05-17 19:47:32.744855	2019-05-17 19:47:32.744855
2	sv	2019-05-17 19:47:32.746566	2019-05-17 19:47:32.746566
3	fi	2019-05-17 19:47:32.748114	2019-05-17 19:47:32.748114
\.


--
-- Data for Name: question_audios; Type: TABLE DATA; Schema: public; Owner: johan
--

COPY public.question_audios (id, question_text_id, size, length, created_at, updated_at) FROM stdin;
1	1	13817	1303	2019-05-17 19:47:33.569827	2019-05-17 19:47:33.569827
2	2	14634	1049	2019-05-17 19:47:33.573146	2019-05-17 19:47:33.573146
3	3	13649	1016	2019-05-17 19:47:33.576211	2019-05-17 19:47:33.576211
4	4	14255	1358	2019-05-17 19:47:33.579634	2019-05-17 19:47:33.579634
5	5	13414	971	2019-05-17 19:47:33.582901	2019-05-17 19:47:33.582901
6	6	14323	1427	2019-05-17 19:47:33.586686	2019-05-17 19:47:33.586686
7	7	10317	776	2019-05-17 19:47:33.589913	2019-05-17 19:47:33.589913
8	8	11936	865	2019-05-17 19:47:33.593277	2019-05-17 19:47:33.593277
9	9	16948	1731	2019-05-17 19:47:33.596336	2019-05-17 19:47:33.596336
10	10	15906	1495	2019-05-17 19:47:33.599554	2019-05-17 19:47:33.599554
11	11	12455	920	2019-05-17 19:47:33.602708	2019-05-17 19:47:33.602708
12	12	12857	1475	2019-05-17 19:47:33.605815	2019-05-17 19:47:33.605815
13	13	10729	1016	2019-05-17 19:47:33.609271	2019-05-17 19:47:33.609271
14	14	11504	1092	2019-05-17 19:47:33.612624	2019-05-17 19:47:33.612624
15	15	11760	926	2019-05-17 19:47:33.616008	2019-05-17 19:47:33.616008
16	16	13190	1380	2019-05-17 19:47:33.619009	2019-05-17 19:47:33.619009
17	17	9871	972	2019-05-17 19:47:33.622287	2019-05-17 19:47:33.622287
18	18	9975	997	2019-05-17 19:47:33.62992	2019-05-17 19:47:33.62992
19	19	22087	1985	2019-05-17 19:47:33.632455	2019-05-17 19:47:33.632455
20	20	21288	2002	2019-05-17 19:47:33.634932	2019-05-17 19:47:33.634932
21	21	9715	972	2019-05-17 19:47:33.637775	2019-05-17 19:47:33.637775
22	22	13854	1339	2019-05-17 19:47:33.640351	2019-05-17 19:47:33.640351
23	23	19397	2018	2019-05-17 19:47:33.642557	2019-05-17 19:47:33.642557
24	24	20361	2267	2019-05-17 19:47:33.644983	2019-05-17 19:47:33.644983
25	25	19313	2052	2019-05-17 19:47:33.647316	2019-05-17 19:47:33.647316
26	26	21900	2292	2019-05-17 19:47:33.649824	2019-05-17 19:47:33.649824
27	27	17356	1507	2019-05-17 19:47:33.652076	2019-05-17 19:47:33.652076
28	28	21999	2198	2019-05-17 19:47:33.654428	2019-05-17 19:47:33.654428
29	29	11525	1027	2019-05-17 19:47:33.656861	2019-05-17 19:47:33.656861
30	30	15271	1219	2019-05-17 19:47:33.659146	2019-05-17 19:47:33.659146
31	31	8239	635	2019-05-17 19:47:33.661569	2019-05-17 19:47:33.661569
32	32	9086	662	2019-05-17 19:47:33.663792	2019-05-17 19:47:33.663792
33	33	5465	324	2019-05-17 19:47:33.665951	2019-05-17 19:47:33.665951
34	34	8102	447	2019-05-17 19:47:33.668141	2019-05-17 19:47:33.668141
35	35	7485	394	2019-05-17 19:47:33.67015	2019-05-17 19:47:33.67015
36	36	6234	424	2019-05-17 19:47:33.67229	2019-05-17 19:47:33.67229
37	37	5358	318	2019-05-17 19:47:33.674533	2019-05-17 19:47:33.674533
38	38	7797	429	2019-05-17 19:47:33.676682	2019-05-17 19:47:33.676682
39	39	15523	1368	2019-05-17 19:47:33.67917	2019-05-17 19:47:33.67917
40	40	14041	1320	2019-05-17 19:47:33.681338	2019-05-17 19:47:33.681338
\.


--
-- Data for Name: question_inputs; Type: TABLE DATA; Schema: public; Owner: johan
--

COPY public.question_inputs (id, question_id, allow_multiple_selections, text_numeric, text_alphanumeric, audio, default_value, created_at, updated_at) FROM stdin;
1	1	\N	\N	\N	\N	\N	2019-05-17 19:47:33.434455	2019-05-17 19:47:33.434455
2	20	\N	\N	\N	\N	\N	2019-05-17 19:47:33.437342	2019-05-17 19:47:33.437342
3	23	\N	\N	1	\N	\N	2019-05-17 19:47:33.43966	2019-05-17 19:47:33.43966
4	24	\N	\N	1	\N	\N	2019-05-17 19:47:33.442015	2019-05-17 19:47:33.442015
5	30	\N	\N	\N	\N	\N	2019-05-17 19:47:33.44426	2019-05-17 19:47:33.44426
6	40	\N	\N	\N	\N	\N	2019-05-17 19:47:33.446914	2019-05-17 19:47:33.446914
7	41	\N	\N	1	\N	\N	2019-05-17 19:47:33.449334	2019-05-17 19:47:33.449334
\.


--
-- Data for Name: question_question_texts; Type: TABLE DATA; Schema: public; Owner: johan
--

COPY public.question_question_texts (id, question_id, question_text_id, created_at, updated_at) FROM stdin;
1	1	1	2019-05-17 19:47:33.692503	2019-05-17 19:47:33.692503
2	1	2	2019-05-17 19:47:33.695701	2019-05-17 19:47:33.695701
3	2	3	2019-05-17 19:47:33.698432	2019-05-17 19:47:33.698432
4	2	4	2019-05-17 19:47:33.701113	2019-05-17 19:47:33.701113
5	3	5	2019-05-17 19:47:33.703577	2019-05-17 19:47:33.703577
6	3	6	2019-05-17 19:47:33.70672	2019-05-17 19:47:33.70672
7	4	7	2019-05-17 19:47:33.70975	2019-05-17 19:47:33.70975
8	4	8	2019-05-17 19:47:33.712428	2019-05-17 19:47:33.712428
9	20	9	2019-05-17 19:47:33.714895	2019-05-17 19:47:33.714895
10	20	10	2019-05-17 19:47:33.717444	2019-05-17 19:47:33.717444
11	21	11	2019-05-17 19:47:33.720053	2019-05-17 19:47:33.720053
12	21	12	2019-05-17 19:47:33.722672	2019-05-17 19:47:33.722672
13	22	13	2019-05-17 19:47:33.725649	2019-05-17 19:47:33.725649
14	22	14	2019-05-17 19:47:33.728291	2019-05-17 19:47:33.728291
15	23	15	2019-05-17 19:47:33.730719	2019-05-17 19:47:33.730719
16	23	16	2019-05-17 19:47:33.733049	2019-05-17 19:47:33.733049
17	24	17	2019-05-17 19:47:33.735786	2019-05-17 19:47:33.735786
18	24	18	2019-05-17 19:47:33.738957	2019-05-17 19:47:33.738957
19	30	19	2019-05-17 19:47:33.741832	2019-05-17 19:47:33.741832
20	30	20	2019-05-17 19:47:33.744971	2019-05-17 19:47:33.744971
21	31	21	2019-05-17 19:47:33.747712	2019-05-17 19:47:33.747712
22	31	22	2019-05-17 19:47:33.750128	2019-05-17 19:47:33.750128
23	32	23	2019-05-17 19:47:33.752824	2019-05-17 19:47:33.752824
24	32	24	2019-05-17 19:47:33.755659	2019-05-17 19:47:33.755659
25	33	25	2019-05-17 19:47:33.758713	2019-05-17 19:47:33.758713
26	33	26	2019-05-17 19:47:33.761472	2019-05-17 19:47:33.761472
27	40	27	2019-05-17 19:47:33.764178	2019-05-17 19:47:33.764178
28	40	28	2019-05-17 19:47:33.76692	2019-05-17 19:47:33.76692
29	41	29	2019-05-17 19:47:33.769299	2019-05-17 19:47:33.769299
30	41	30	2019-05-17 19:47:33.772175	2019-05-17 19:47:33.772175
31	42	31	2019-05-17 19:47:33.774948	2019-05-17 19:47:33.774948
32	42	32	2019-05-17 19:47:33.777655	2019-05-17 19:47:33.777655
33	43	33	2019-05-17 19:47:33.780499	2019-05-17 19:47:33.780499
34	43	34	2019-05-17 19:47:33.783093	2019-05-17 19:47:33.783093
35	44	35	2019-05-17 19:47:33.7859	2019-05-17 19:47:33.7859
36	44	26	2019-05-17 19:47:33.788555	2019-05-17 19:47:33.788555
37	45	37	2019-05-17 19:47:33.791305	2019-05-17 19:47:33.791305
38	45	38	2019-05-17 19:47:33.793986	2019-05-17 19:47:33.793986
39	46	39	2019-05-17 19:47:33.796862	2019-05-17 19:47:33.796862
40	46	40	2019-05-17 19:47:33.799453	2019-05-17 19:47:33.799453
41	47	39	2019-05-17 19:47:33.802291	2019-05-17 19:47:33.802291
42	47	40	2019-05-17 19:47:33.805443	2019-05-17 19:47:33.805443
43	48	39	2019-05-17 19:47:33.808014	2019-05-17 19:47:33.808014
44	48	40	2019-05-17 19:47:33.810598	2019-05-17 19:47:33.810598
45	49	39	2019-05-17 19:47:33.813536	2019-05-17 19:47:33.813536
46	49	40	2019-05-17 19:47:33.816776	2019-05-17 19:47:33.816776
47	50	39	2019-05-17 19:47:33.819874	2019-05-17 19:47:33.819874
48	50	40	2019-05-17 19:47:33.822945	2019-05-17 19:47:33.822945
\.


--
-- Data for Name: question_texts; Type: TABLE DATA; Schema: public; Owner: johan
--

COPY public.question_texts (id, language_id, text, created_at, updated_at) FROM stdin;
1	1	What would you like to do?	2019-05-17 19:47:33.459063	2019-05-17 19:47:33.459063
2	2	Vad vill du göra?	2019-05-17 19:47:33.461807	2019-05-17 19:47:33.461807
3	1	Search the library.	2019-05-17 19:47:33.463989	2019-05-17 19:47:33.463989
4	2	Söka i biblioteket.	2019-05-17 19:47:33.466663	2019-05-17 19:47:33.466663
5	1	Browse the library.	2019-05-17 19:47:33.469231	2019-05-17 19:47:33.469231
6	2	Utforska biblioteket.	2019-05-17 19:47:33.47163	2019-05-17 19:47:33.47163
7	1	Give feedback.	2019-05-17 19:47:33.474217	2019-05-17 19:47:33.474217
8	2	Ge feedback.	2019-05-17 19:47:33.476466	2019-05-17 19:47:33.476466
9	1	What do you want to search by?	2019-05-17 19:47:33.478754	2019-05-17 19:47:33.478754
10	2	Vad vill du söka enligt?	2019-05-17 19:47:33.480829	2019-05-17 19:47:33.480829
11	1	Search by author.	2019-05-17 19:47:33.48287	2019-05-17 19:47:33.48287
12	2	Sök bland författare.	2019-05-17 19:47:33.48533	2019-05-17 19:47:33.48533
13	1	Search by title.	2019-05-17 19:47:33.48946	2019-05-17 19:47:33.48946
14	2	Sök bland titel.	2019-05-17 19:47:33.491834	2019-05-17 19:47:33.491834
15	1	Author keywords:	2019-05-17 19:47:33.49406	2019-05-17 19:47:33.49406
16	2	Sökord författare:	2019-05-17 19:47:33.496065	2019-05-17 19:47:33.496065
17	1	Title keywords:	2019-05-17 19:47:33.498515	2019-05-17 19:47:33.498515
18	2	Sökord titel:	2019-05-17 19:47:33.500713	2019-05-17 19:47:33.500713
19	1	How do you want to browse the library?	2019-05-17 19:47:33.502996	2019-05-17 19:47:33.502996
20	2	Hur vill du utforska biblioteket?	2019-05-17 19:47:33.505282	2019-05-17 19:47:33.505282
21	1	Browse by title.	2019-05-17 19:47:33.507832	2019-05-17 19:47:33.507832
22	2	Utforska bland titlar.	2019-05-17 19:47:33.510245	2019-05-17 19:47:33.510245
23	1	Browse by Daisy 2 content format.	2019-05-17 19:47:33.512414	2019-05-17 19:47:33.512414
24	2	Utforska bland Daisy 2 filformat.	2019-05-17 19:47:33.51487	2019-05-17 19:47:33.51487
25	1	Browse by Daisy 3 content format.	2019-05-17 19:47:33.517333	2019-05-17 19:47:33.517333
26	2	Utforska bland Daisy 3 filformat.	2019-05-17 19:47:33.51973	2019-05-17 19:47:33.51973
27	1	How would you rate this service?	2019-05-17 19:47:33.522257	2019-05-17 19:47:33.522257
28	2	Hur skulle du betygsätta denna tjänst?	2019-05-17 19:47:33.524982	2019-05-17 19:47:33.524982
29	1	Optional feedback?	2019-05-17 19:47:33.527241	2019-05-17 19:47:33.527241
30	2	Frivillig feedback?	2019-05-17 19:47:33.529332	2019-05-17 19:47:33.529332
31	1	Excellent.	2019-05-17 19:47:33.531725	2019-05-17 19:47:33.531725
32	2	Utmärkt.	2019-05-17 19:47:33.534078	2019-05-17 19:47:33.534078
33	1	Good.	2019-05-17 19:47:33.536667	2019-05-17 19:47:33.536667
34	2	Bra.	2019-05-17 19:47:33.539026	2019-05-17 19:47:33.539026
35	1	Fair.	2019-05-17 19:47:33.541486	2019-05-17 19:47:33.541486
36	2	Dålig.	2019-05-17 19:47:33.543936	2019-05-17 19:47:33.543936
37	1	Poor.	2019-05-17 19:47:33.546101	2019-05-17 19:47:33.546101
38	2	Usel.	2019-05-17 19:47:33.548818	2019-05-17 19:47:33.548818
39	1	Thank you for your feedback.	2019-05-17 19:47:33.551508	2019-05-17 19:47:33.551508
40	2	Tack för din feedback.	2019-05-17 19:47:33.554347	2019-05-17 19:47:33.554347
\.


--
-- Data for Name: question_types; Type: TABLE DATA; Schema: public; Owner: johan
--

COPY public.question_types (id, name, created_at, updated_at) FROM stdin;
1	multipleChoiceQuestion	2019-05-17 19:47:32.695017	2019-05-17 19:47:32.695017
2	inputQuestion	2019-05-17 19:47:32.696832	2019-05-17 19:47:32.696832
3	choice	2019-05-17 19:47:32.698229	2019-05-17 19:47:32.698229
4	contentListRef	2019-05-17 19:47:32.699682	2019-05-17 19:47:32.699682
5	label	2019-05-17 19:47:32.701149	2019-05-17 19:47:32.701149
\.


--
-- Data for Name: questions; Type: TABLE DATA; Schema: public; Owner: johan
--

COPY public.questions (id, parent_id, question_type_id, created_at, updated_at) FROM stdin;
1	0	1	2019-05-17 19:47:33.389466	2019-05-17 19:47:33.389466
2	1	3	2019-05-17 19:47:33.397162	2019-05-17 19:47:33.397162
3	1	3	2019-05-17 19:47:33.39804	2019-05-17 19:47:33.39804
4	1	3	2019-05-17 19:47:33.399066	2019-05-17 19:47:33.399066
20	2	1	2019-05-17 19:47:33.400201	2019-05-17 19:47:33.400201
21	20	3	2019-05-17 19:47:33.401154	2019-05-17 19:47:33.401154
22	20	3	2019-05-17 19:47:33.402093	2019-05-17 19:47:33.402093
23	21	2	2019-05-17 19:47:33.402869	2019-05-17 19:47:33.402869
24	22	2	2019-05-17 19:47:33.403611	2019-05-17 19:47:33.403611
25	23	4	2019-05-17 19:47:33.404371	2019-05-17 19:47:33.404371
26	24	4	2019-05-17 19:47:33.405019	2019-05-17 19:47:33.405019
30	3	1	2019-05-17 19:47:33.405572	2019-05-17 19:47:33.405572
31	30	3	2019-05-17 19:47:33.406399	2019-05-17 19:47:33.406399
32	30	3	2019-05-17 19:47:33.407318	2019-05-17 19:47:33.407318
33	30	3	2019-05-17 19:47:33.408132	2019-05-17 19:47:33.408132
34	31	4	2019-05-17 19:47:33.408735	2019-05-17 19:47:33.408735
35	32	4	2019-05-17 19:47:33.40943	2019-05-17 19:47:33.40943
36	33	4	2019-05-17 19:47:33.410089	2019-05-17 19:47:33.410089
40	4	1	2019-05-17 19:47:33.410565	2019-05-17 19:47:33.410565
41	4	2	2019-05-17 19:47:33.411234	2019-05-17 19:47:33.411234
42	40	3	2019-05-17 19:47:33.411697	2019-05-17 19:47:33.411697
43	40	3	2019-05-17 19:47:33.412087	2019-05-17 19:47:33.412087
44	40	3	2019-05-17 19:47:33.412586	2019-05-17 19:47:33.412586
45	40	3	2019-05-17 19:47:33.413097	2019-05-17 19:47:33.413097
46	41	5	2019-05-17 19:47:33.413584	2019-05-17 19:47:33.413584
47	42	5	2019-05-17 19:47:33.414265	2019-05-17 19:47:33.414265
48	43	5	2019-05-17 19:47:33.414995	2019-05-17 19:47:33.414995
49	44	5	2019-05-17 19:47:33.41551	2019-05-17 19:47:33.41551
50	45	5	2019-05-17 19:47:33.416088	2019-05-17 19:47:33.416088
\.


--
-- Data for Name: schema_migrations; Type: TABLE DATA; Schema: public; Owner: johan
--

COPY public.schema_migrations (version) FROM stdin;
20190508030826
20190111182636
20190220181504
20190220113815
20190220183617
20190221164321
20190220185209
20190221183224
20190221182750
20190220162144
20190221181952
20190220190600
20190220160412
20190220111925
20190221164819
20190221165021
20190221181516
20190220113222
20190220190259
20190220182555
20190221173404
20190221171607
20190220182034
\.


--
-- Data for Name: states; Type: TABLE DATA; Schema: public; Owner: johan
--

COPY public.states (id, state, created_at, updated_at) FROM stdin;
1	START	2019-05-17 19:47:32.707818	2019-05-17 19:47:32.707818
2	PAUSE	2019-05-17 19:47:32.709994	2019-05-17 19:47:32.709994
3	RESUME	2019-05-17 19:47:32.7115	2019-05-17 19:47:32.7115
4	FINISH	2019-05-17 19:47:32.712928	2019-05-17 19:47:32.712928
\.


--
-- Data for Name: user_announcements; Type: TABLE DATA; Schema: public; Owner: johan
--

COPY public.user_announcements (id, user_id, announcement_id, read_at, created_at, updated_at) FROM stdin;
1	1	1	\N	2019-05-17 19:47:33.846995	2019-05-17 19:47:33.846995
2	1	2	\N	2019-05-17 19:47:33.850783	2019-05-17 19:47:33.850783
\.


--
-- Data for Name: user_bookmarks; Type: TABLE DATA; Schema: public; Owner: johan
--

COPY public.user_bookmarks (id, user_id, content_id, bookmark_set, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: user_contents; Type: TABLE DATA; Schema: public; Owner: johan
--

COPY public.user_contents (id, user_id, content_id, content_list_id, content_list_v1_id, return, returned, return_at, state_id, created_at, updated_at) FROM stdin;
1	1	1	1	2	1	0	\N	\N	2019-05-17 19:47:33.852037	2019-05-17 19:47:33.852037
2	1	2	1	2	1	0	\N	\N	2019-05-17 19:47:33.853344	2019-05-17 19:47:33.853344
3	1	3	1	2	1	0	\N	\N	2019-05-17 19:47:33.853978	2019-05-17 19:47:33.853978
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: johan
--

COPY public.users (id, username, password, terms_accepted, log, activated, created_at, updated_at) FROM stdin;
1	kolibre	Wz2fuBzjbhCrm/Dmx38DCgpWHigWf8aaEDlvpDCO5gImGDI=	0	1	f	2019-05-17 19:47:33.834244	2019-05-17 19:47:33.834244
\.


--
-- Name: announcement_audios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: johan
--

SELECT pg_catalog.setval('public.announcement_audios_id_seq', 4, true);


--
-- Name: announcement_texts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: johan
--

SELECT pg_catalog.setval('public.announcement_texts_id_seq', 4, true);


--
-- Name: announcements_id_seq; Type: SEQUENCE SET; Schema: public; Owner: johan
--

SELECT pg_catalog.setval('public.announcements_id_seq', 2, true);


--
-- Name: categories_id_seq; Type: SEQUENCE SET; Schema: public; Owner: johan
--

SELECT pg_catalog.setval('public.categories_id_seq', 4, true);


--
-- Name: content_audios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: johan
--

SELECT pg_catalog.setval('public.content_audios_id_seq', 3, true);


--
-- Name: content_lists_id_seq; Type: SEQUENCE SET; Schema: public; Owner: johan
--

SELECT pg_catalog.setval('public.content_lists_id_seq', 6, true);


--
-- Name: content_metadata_id_seq; Type: SEQUENCE SET; Schema: public; Owner: johan
--

SELECT pg_catalog.setval('public.content_metadata_id_seq', 67, true);


--
-- Name: content_resources_id_seq; Type: SEQUENCE SET; Schema: public; Owner: johan
--

SELECT pg_catalog.setval('public.content_resources_id_seq', 146, true);


--
-- Name: contents_id_seq; Type: SEQUENCE SET; Schema: public; Owner: johan
--

SELECT pg_catalog.setval('public.contents_id_seq', 3, true);


--
-- Name: daisy_formats_id_seq; Type: SEQUENCE SET; Schema: public; Owner: johan
--

SELECT pg_catalog.setval('public.daisy_formats_id_seq', 3, true);


--
-- Name: languages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: johan
--

SELECT pg_catalog.setval('public.languages_id_seq', 3, true);


--
-- Name: question_audios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: johan
--

SELECT pg_catalog.setval('public.question_audios_id_seq', 40, true);


--
-- Name: question_inputs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: johan
--

SELECT pg_catalog.setval('public.question_inputs_id_seq', 7, true);


--
-- Name: question_question_texts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: johan
--

SELECT pg_catalog.setval('public.question_question_texts_id_seq', 48, true);


--
-- Name: question_texts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: johan
--

SELECT pg_catalog.setval('public.question_texts_id_seq', 40, true);


--
-- Name: question_types_id_seq; Type: SEQUENCE SET; Schema: public; Owner: johan
--

SELECT pg_catalog.setval('public.question_types_id_seq', 5, true);


--
-- Name: questions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: johan
--

SELECT pg_catalog.setval('public.questions_id_seq', 1, false);


--
-- Name: states_id_seq; Type: SEQUENCE SET; Schema: public; Owner: johan
--

SELECT pg_catalog.setval('public.states_id_seq', 4, true);


--
-- Name: user_announcements_id_seq; Type: SEQUENCE SET; Schema: public; Owner: johan
--

SELECT pg_catalog.setval('public.user_announcements_id_seq', 2, true);


--
-- Name: user_bookmarks_id_seq; Type: SEQUENCE SET; Schema: public; Owner: johan
--

SELECT pg_catalog.setval('public.user_bookmarks_id_seq', 1, false);


--
-- Name: user_contents_id_seq; Type: SEQUENCE SET; Schema: public; Owner: johan
--

SELECT pg_catalog.setval('public.user_contents_id_seq', 3, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: johan
--

SELECT pg_catalog.setval('public.users_id_seq', 1, true);


--
-- Name: announcement_audios announcement_audios_pkey; Type: CONSTRAINT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.announcement_audios
    ADD CONSTRAINT announcement_audios_pkey PRIMARY KEY (id);


--
-- Name: announcement_texts announcement_texts_pkey; Type: CONSTRAINT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.announcement_texts
    ADD CONSTRAINT announcement_texts_pkey PRIMARY KEY (id);


--
-- Name: announcements announcements_pkey; Type: CONSTRAINT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.announcements
    ADD CONSTRAINT announcements_pkey PRIMARY KEY (id);


--
-- Name: ar_internal_metadata ar_internal_metadata_pkey; Type: CONSTRAINT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.ar_internal_metadata
    ADD CONSTRAINT ar_internal_metadata_pkey PRIMARY KEY (key);


--
-- Name: categories categories_pkey; Type: CONSTRAINT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_pkey PRIMARY KEY (id);


--
-- Name: content_audios content_audios_pkey; Type: CONSTRAINT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.content_audios
    ADD CONSTRAINT content_audios_pkey PRIMARY KEY (id);


--
-- Name: content_lists content_lists_pkey; Type: CONSTRAINT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.content_lists
    ADD CONSTRAINT content_lists_pkey PRIMARY KEY (id);


--
-- Name: content_metadata content_metadata_pkey; Type: CONSTRAINT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.content_metadata
    ADD CONSTRAINT content_metadata_pkey PRIMARY KEY (id);


--
-- Name: content_resources content_resources_pkey; Type: CONSTRAINT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.content_resources
    ADD CONSTRAINT content_resources_pkey PRIMARY KEY (id);


--
-- Name: contents contents_pkey; Type: CONSTRAINT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.contents
    ADD CONSTRAINT contents_pkey PRIMARY KEY (id);


--
-- Name: daisy_formats daisy_formats_pkey; Type: CONSTRAINT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.daisy_formats
    ADD CONSTRAINT daisy_formats_pkey PRIMARY KEY (id);


--
-- Name: languages languages_pkey; Type: CONSTRAINT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.languages
    ADD CONSTRAINT languages_pkey PRIMARY KEY (id);


--
-- Name: question_audios question_audios_pkey; Type: CONSTRAINT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.question_audios
    ADD CONSTRAINT question_audios_pkey PRIMARY KEY (id);


--
-- Name: question_inputs question_inputs_pkey; Type: CONSTRAINT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.question_inputs
    ADD CONSTRAINT question_inputs_pkey PRIMARY KEY (id);


--
-- Name: question_question_texts question_question_texts_pkey; Type: CONSTRAINT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.question_question_texts
    ADD CONSTRAINT question_question_texts_pkey PRIMARY KEY (id);


--
-- Name: question_texts question_texts_pkey; Type: CONSTRAINT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.question_texts
    ADD CONSTRAINT question_texts_pkey PRIMARY KEY (id);


--
-- Name: question_types question_types_pkey; Type: CONSTRAINT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.question_types
    ADD CONSTRAINT question_types_pkey PRIMARY KEY (id);


--
-- Name: questions questions_pkey; Type: CONSTRAINT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.questions
    ADD CONSTRAINT questions_pkey PRIMARY KEY (id);


--
-- Name: schema_migrations schema_migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.schema_migrations
    ADD CONSTRAINT schema_migrations_pkey PRIMARY KEY (version);


--
-- Name: states states_pkey; Type: CONSTRAINT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.states
    ADD CONSTRAINT states_pkey PRIMARY KEY (id);


--
-- Name: user_announcements user_announcements_pkey; Type: CONSTRAINT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.user_announcements
    ADD CONSTRAINT user_announcements_pkey PRIMARY KEY (id);


--
-- Name: user_bookmarks user_bookmarks_pkey; Type: CONSTRAINT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.user_bookmarks
    ADD CONSTRAINT user_bookmarks_pkey PRIMARY KEY (id);


--
-- Name: user_contents user_contents_pkey; Type: CONSTRAINT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.user_contents
    ADD CONSTRAINT user_contents_pkey PRIMARY KEY (id);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: johan
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: index_announcement_audios_on_announcement_text_id; Type: INDEX; Schema: public; Owner: johan
--

CREATE INDEX index_announcement_audios_on_announcement_text_id ON public.announcement_audios USING btree (announcement_text_id);


--
-- Name: index_announcement_texts_on_announcement_id; Type: INDEX; Schema: public; Owner: johan
--

CREATE INDEX index_announcement_texts_on_announcement_id ON public.announcement_texts USING btree (announcement_id);


--
-- Name: index_announcement_texts_on_language_id; Type: INDEX; Schema: public; Owner: johan
--

CREATE INDEX index_announcement_texts_on_language_id ON public.announcement_texts USING btree (language_id);


--
-- Name: index_content_audios_on_content_id; Type: INDEX; Schema: public; Owner: johan
--

CREATE INDEX index_content_audios_on_content_id ON public.content_audios USING btree (content_id);


--
-- Name: index_content_metadata_on_content_id; Type: INDEX; Schema: public; Owner: johan
--

CREATE INDEX index_content_metadata_on_content_id ON public.content_metadata USING btree (content_id);


--
-- Name: index_content_resources_on_content_id; Type: INDEX; Schema: public; Owner: johan
--

CREATE INDEX index_content_resources_on_content_id ON public.content_resources USING btree (content_id);


--
-- Name: index_contents_on_category_id; Type: INDEX; Schema: public; Owner: johan
--

CREATE INDEX index_contents_on_category_id ON public.contents USING btree (category_id);


--
-- Name: index_contents_on_daisy_format_id; Type: INDEX; Schema: public; Owner: johan
--

CREATE INDEX index_contents_on_daisy_format_id ON public.contents USING btree (daisy_format_id);


--
-- Name: index_question_audios_on_question_text_id; Type: INDEX; Schema: public; Owner: johan
--

CREATE INDEX index_question_audios_on_question_text_id ON public.question_audios USING btree (question_text_id);


--
-- Name: index_question_inputs_on_question_id; Type: INDEX; Schema: public; Owner: johan
--

CREATE INDEX index_question_inputs_on_question_id ON public.question_inputs USING btree (question_id);


--
-- Name: index_question_question_texts_on_question_id; Type: INDEX; Schema: public; Owner: johan
--

CREATE INDEX index_question_question_texts_on_question_id ON public.question_question_texts USING btree (question_id);


--
-- Name: index_question_question_texts_on_question_text_id; Type: INDEX; Schema: public; Owner: johan
--

CREATE INDEX index_question_question_texts_on_question_text_id ON public.question_question_texts USING btree (question_text_id);


--
-- Name: index_question_texts_on_language_id; Type: INDEX; Schema: public; Owner: johan
--

CREATE INDEX index_question_texts_on_language_id ON public.question_texts USING btree (language_id);


--
-- Name: index_questions_on_parent_id; Type: INDEX; Schema: public; Owner: johan
--

CREATE INDEX index_questions_on_parent_id ON public.questions USING btree (parent_id);


--
-- Name: index_questions_on_question_type_id; Type: INDEX; Schema: public; Owner: johan
--

CREATE INDEX index_questions_on_question_type_id ON public.questions USING btree (question_type_id);


--
-- Name: index_user_announcements_on_announcement_id; Type: INDEX; Schema: public; Owner: johan
--

CREATE INDEX index_user_announcements_on_announcement_id ON public.user_announcements USING btree (announcement_id);


--
-- Name: index_user_announcements_on_user_id; Type: INDEX; Schema: public; Owner: johan
--

CREATE INDEX index_user_announcements_on_user_id ON public.user_announcements USING btree (user_id);


--
-- Name: index_user_bookmarks_on_content_id; Type: INDEX; Schema: public; Owner: johan
--

CREATE INDEX index_user_bookmarks_on_content_id ON public.user_bookmarks USING btree (content_id);


--
-- Name: index_user_bookmarks_on_user_id; Type: INDEX; Schema: public; Owner: johan
--

CREATE INDEX index_user_bookmarks_on_user_id ON public.user_bookmarks USING btree (user_id);


--
-- Name: index_user_contents_on_content_id; Type: INDEX; Schema: public; Owner: johan
--

CREATE INDEX index_user_contents_on_content_id ON public.user_contents USING btree (content_id);


--
-- Name: index_user_contents_on_content_list_id; Type: INDEX; Schema: public; Owner: johan
--

CREATE INDEX index_user_contents_on_content_list_id ON public.user_contents USING btree (content_list_id);


--
-- Name: index_user_contents_on_content_list_v1_id; Type: INDEX; Schema: public; Owner: johan
--

CREATE INDEX index_user_contents_on_content_list_v1_id ON public.user_contents USING btree (content_list_v1_id);


--
-- Name: index_user_contents_on_state_id; Type: INDEX; Schema: public; Owner: johan
--

CREATE INDEX index_user_contents_on_state_id ON public.user_contents USING btree (state_id);


--
-- Name: index_user_contents_on_user_id; Type: INDEX; Schema: public; Owner: johan
--

CREATE INDEX index_user_contents_on_user_id ON public.user_contents USING btree (user_id);


--
-- PostgreSQL database dump complete
--

