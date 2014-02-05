/*
 * PostgreSQL
 */

DROP INDEX IF EXISTS tbl_messages_receiver_index;

DROP TABLE IF EXISTS tbl_users;
DROP TABLE IF EXISTS tbl_messages;

CREATE TABLE tbl_users (
	id serial NOT NULL,
	username character varying(24) NOT NULL,
	password character varying(32) NOT NULL,
	nickname character varying(24) NOT NULL DEFAULT '',
	created_at timestamp NOT NULL DEFAULT clock_timestamp(),
	updated_at timestamp NOT NULL DEFAULT clock_timestamp(),
	last_visit timestamp DEFAULT NULL
);

CREATE TABLE tbl_messages (
	id serial,
	posted_at timestamp NOT NULL DEFAULT clock_timestamp(),
	receiver_id integer NOT NULL,
	sender_id integer NOT NULL,
	message text NOT NULL,
	is_deleted boolean NOT NULL DEFAULT false
);

CREATE INDEX tbl_messages_receiver_index ON tbl_messages (receiver_id) WHERE is_deleted = false;

-- Paassword in plain view only for dramatical effect =)
INSERT INTO tbl_users (username, password, nickname) VALUES ('user1', 'user1', 'SuperPoncho');
INSERT INTO tbl_users (username, password, nickname) VALUES ('user2', 'user2', 'Beer Kong');
INSERT INTO tbl_users (username, password, nickname) VALUES ('user3', 'user3', 'Core Loop');
