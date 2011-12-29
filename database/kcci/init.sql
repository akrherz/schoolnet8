BEGIN;
--
-- KCCI database initialization, needs postGIS!
--

CREATE TABLE apphits (
    hits bigint,
    app character(2)
);

CREATE TABLE cameras (
    id character varying,
    name character varying,
    ip inet
);

CREATE TABLE clicktru (
    station character varying(10),
    valid timestamp with time zone DEFAULT ('now'::text)::timestamp(6) with time zone,
    ip inet,
    stype smallint
);

CREATE TABLE clicktru_old (
    station character varying(10),
    valid timestamp with time zone,
    ip inet,
    stype smallint
);

CREATE TABLE climate51 (
    station character varying(6),
    valid date,
    high real,
    low real,
    precip real,
    snow real,
    max_high real,
    max_low real,
    min_high real,
    min_low real,
    max_precip real,
    years real,
    gdd50 real,
    sdd86 real,
    max_high_yr integer,
    max_low_yr integer,
    min_high_yr integer,
    min_low_yr integer,
    max_precip_yr integer,
    max_range smallint,
    min_range smallint,
    precip_mtd real
);

CREATE TABLE site_stats (
    station character varying(10),
    valid timestamp with time zone DEFAULT ('now'::text)::timestamp(6) with time zone NOT NULL,
    ip inet,
    app character(2)
);

CREATE TABLE site_stats_old (
    station character varying(10),
    valid timestamp with time zone,
    ip inet,
    app character(2)
);

CREATE TABLE site_stats_report (
    station character varying(10),
    hits bigint,
    hosts bigint
);


CREATE TABLE stations (
    id varchar(5) UNIQUE,
    sname character varying,
    city character varying,
    online boolean,
    nwn_id smallint,
    climate_site character varying(6),
    geom geometry,
    ftm text,
    moonrise timestamp without time zone,
    moonset timestamp without time zone,
    moonphase character varying,
    CONSTRAINT enforce_dims_geom CHECK ((ndims(geom) = 2)),
    CONSTRAINT enforce_geotype_geom CHECK (((geometrytype(geom) = 'POINT'::text) OR (geom IS NULL))),
    CONSTRAINT enforce_srid_geom CHECK ((srid(geom) = 4326))
);


CREATE TABLE walerts (
    sid character varying(5),
    uid integer
);

CREATE TABLE xref (
    sid character varying(5),
    fips smallint,
    cname character varying(30)
);
CREATE INDEX clicktru_station_idx ON clicktru USING btree (station);
CREATE INDEX climate51_idx ON climate51 USING btree (station, valid);
CREATE UNIQUE INDEX walerts_idx ON walerts USING btree (sid, uid);

GRANT SELECT ON TABLE apphits TO apache;
GRANT ALL ON TABLE clicktru TO apache;
GRANT SELECT ON TABLE climate51 TO apache;
GRANT INSERT ON TABLE site_stats TO apache;
GRANT SELECT ON TABLE site_stats_report TO apache;
GRANT SELECT ON TABLE stations TO apache;

COMMIT;