--
-- PostgreSQL database dump
--

\connect - mesonet

SET search_path = public, pg_catalog;

--
-- TOC entry 4 (OID 5037690)
-- Name: site_stats; Type: TABLE; Schema: public; Owner: mesonet
--

CREATE TABLE site_stats (
    station character varying(5),
    "valid" timestamp with time zone DEFAULT ('now'::text)::timestamp(6) with time zone NOT NULL,
    ip inet,
    app character(2)
);


--
-- TOC entry 5 (OID 5037690)
-- Name: site_stats; Type: ACL; Schema: public; Owner: mesonet
--

REVOKE ALL ON TABLE site_stats FROM PUBLIC;
GRANT INSERT,SELECT ON TABLE site_stats TO kcci;


--
-- TOC entry 6 (OID 5037696)
-- Name: clicktru; Type: TABLE; Schema: public; Owner: mesonet
--

CREATE TABLE clicktru (
    station character varying(5),
    "valid" timestamp with time zone DEFAULT ('now'::text)::timestamp(6) with time zone,
    ip inet
);


--
-- TOC entry 7 (OID 5037696)
-- Name: clicktru; Type: ACL; Schema: public; Owner: mesonet
--

REVOKE ALL ON TABLE clicktru FROM PUBLIC;
GRANT INSERT,SELECT ON TABLE clicktru TO kcci;


--
-- TOC entry 8 (OID 5037702)
-- Name: site_stats_old; Type: TABLE; Schema: public; Owner: mesonet
--

CREATE TABLE site_stats_old (
    station character varying(5),
    "valid" timestamp with time zone,
    ip inet,
    app character(2)
);


--
-- TOC entry 9 (OID 5037707)
-- Name: clicktru_old; Type: TABLE; Schema: public; Owner: mesonet
--

CREATE TABLE clicktru_old (
    station character varying(5),
    "valid" timestamp with time zone,
    ip inet
);


\connect - akrherz

SET search_path = public, pg_catalog;

--
-- TOC entry 10 (OID 5037712)
-- Name: xref; Type: TABLE; Schema: public; Owner: akrherz
--

CREATE TABLE xref (
    sid character varying(5),
    fips smallint,
    cname character varying(30)
);


--
-- TOC entry 11 (OID 5037712)
-- Name: xref; Type: ACL; Schema: public; Owner: akrherz
--

REVOKE ALL ON TABLE xref FROM PUBLIC;
GRANT SELECT ON TABLE xref TO kcci;


--
-- TOC entry 12 (OID 5037714)
-- Name: walerts; Type: TABLE; Schema: public; Owner: akrherz
--

CREATE TABLE walerts (
    sid character varying(5),
    uid integer
);


--
-- TOC entry 13 (OID 5037714)
-- Name: walerts; Type: ACL; Schema: public; Owner: akrherz
--

REVOKE ALL ON TABLE walerts FROM PUBLIC;
GRANT ALL ON TABLE walerts TO kcci;


--
-- TOC entry 2 (OID 5037716)
-- Name: accounts_uid_seq; Type: SEQUENCE; Schema: public; Owner: akrherz
--

CREATE SEQUENCE accounts_uid_seq
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 3 (OID 5037716)
-- Name: accounts_uid_seq; Type: ACL; Schema: public; Owner: akrherz
--

REVOKE ALL ON TABLE accounts_uid_seq FROM PUBLIC;
GRANT ALL ON TABLE accounts_uid_seq TO kcci;


--
-- TOC entry 14 (OID 5037718)
-- Name: accounts; Type: TABLE; Schema: public; Owner: akrherz
--

CREATE TABLE accounts (
    uid integer DEFAULT nextval('"accounts_uid_seq"'::text) NOT NULL,
    email character varying(100),
    initials character varying(3),
    never boolean
);


--
-- TOC entry 15 (OID 5037718)
-- Name: accounts; Type: ACL; Schema: public; Owner: akrherz
--

REVOKE ALL ON TABLE accounts FROM PUBLIC;
GRANT ALL ON TABLE accounts TO kcci;


\connect - mesonet

SET search_path = public, pg_catalog;

--
-- TOC entry 16 (OID 5037721)
-- Name: cameras; Type: TABLE; Schema: public; Owner: mesonet
--

CREATE TABLE cameras (
    id character varying,
    name character varying,
    ip inet
);


--
-- TOC entry 17 (OID 5037721)
-- Name: cameras; Type: ACL; Schema: public; Owner: mesonet
--

REVOKE ALL ON TABLE cameras FROM PUBLIC;
GRANT SELECT ON TABLE cameras TO nobody;


--
-- TOC entry 18 (OID 5037728)
-- Name: camera_schedule; Type: TABLE; Schema: public; Owner: mesonet
--

CREATE TABLE camera_schedule (
    id serial NOT NULL,
    camera_id character varying,
    start_ts timestamp without time zone,
    end_ts timestamp without time zone,
    p smallint,
    t smallint,
    z smallint
);


--
-- TOC entry 19 (OID 5037728)
-- Name: camera_schedule; Type: ACL; Schema: public; Owner: mesonet
--

REVOKE ALL ON TABLE camera_schedule FROM PUBLIC;
GRANT SELECT ON TABLE camera_schedule TO nobody;


--
-- TOC entry 20 (OID 11839373)
-- Name: site_stats_station_idx; Type: INDEX; Schema: public; Owner: mesonet
--

CREATE INDEX site_stats_station_idx ON site_stats USING btree (station);


--
-- TOC entry 21 (OID 11839374)
-- Name: clicktru_station_idx; Type: INDEX; Schema: public; Owner: mesonet
--

CREATE INDEX clicktru_station_idx ON clicktru USING btree (station);


\connect - akrherz

SET search_path = public, pg_catalog;

--
-- TOC entry 24 (OID 11839375)
-- Name: accounts_uid_key; Type: INDEX; Schema: public; Owner: akrherz
--

CREATE UNIQUE INDEX accounts_uid_key ON accounts USING btree (uid);


--
-- TOC entry 23 (OID 11839376)
-- Name: accounts_email_idx; Type: INDEX; Schema: public; Owner: akrherz
--

CREATE UNIQUE INDEX accounts_email_idx ON accounts USING btree (email);


--
-- TOC entry 22 (OID 11839377)
-- Name: walerts_idx; Type: INDEX; Schema: public; Owner: akrherz
--

CREATE UNIQUE INDEX walerts_idx ON walerts USING btree (sid, uid);


