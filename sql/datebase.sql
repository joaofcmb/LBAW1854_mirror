-- DROPs

DROP TABLE IF EXISTS "user", administrator, developer CASCADE;
DROP TABLE IF EXISTS follow, project, favorite, forum, thread CASCADE;
DROP TABLE IF EXISTS comment, task_comment, thread_comment CASCADE;
DROP TABLE IF EXISTS task, team, team_project, team_task CASCADE;
DROP TABLE IF EXISTS task_group, milestone, team_leader CASCADE;

DROP TYPE IF EXISTS ProjectStatus CASCADE;

-- Types

CREATE TYPE ProjectStatus AS ENUM ('active', 'canceled', 'closed');

-- TABLES

-- R01
CREATE TABLE "user" (
    id SERIAL PRIMARY KEY,
    username TEXT NOT NULL CONSTRAINT username_uk UNIQUE,
    first_name TEXT NOT NULL,
    last_name TEXT NOT NULL,
    email TEXT NOT NULL,
    password TEXT NOT NULL,
    biography TEXT
);

-- R02
CREATE TABLE administrator (
    id_user INTEGER PRIMARY KEY REFERENCES "user" ON UPDATE CASCADE ON DELETE CASCADE
);

-- R03
CREATE TABLE developer (
    id_user INTEGER PRIMARY KEY REFERENCES "user" ON UPDATE CASCADE ON DELETE CASCADE,
    id_team INTEGER,
    active BOOLEAN NOT NULL
);

-- R04
CREATE TABLE team_leader (
    id_developer INTEGER PRIMARY KEY REFERENCES developer ON UPDATE CASCADE ON DELETE CASCADE
);

-- R05
CREATE TABLE follow (
    id_follower INTEGER REFERENCES "user" ON UPDATE CASCADE ON DELETE CASCADE,
    id_followee INTEGER REFERENCES "user" ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT follow_ck CHECK (id_followee <> id_follower),
    CONSTRAINT follow_pk PRIMARY KEY (id_follower, id_followee)
);

-- R06
CREATE TABLE project (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL CONSTRAINT project_name_uk UNIQUE,
    color TEXT NOT NULL DEFAULT '0000ff',
    description TEXT NOT NULL, 
    status ProjectStatus NOT NULL DEFAULT 'active',
    id_manager INTEGER NOT NULL REFERENCES developer ON UPDATE CASCADE ON DELETE RESTRICT
);

-- R07
CREATE TABLE favorite (
    id_user INTEGER REFERENCES "user" ON UPDATE CASCADE ON DELETE CASCADE,
    id_project INTEGER REFERENCES project ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT favorite_pk PRIMARY KEY (id_user, id_project)
);

-- R08
CREATE TABLE forum (
    id SERIAL PRIMARY KEY,
    id_project INTEGER REFERENCES project ON UPDATE CASCADE ON DELETE CASCADE
);

-- R09
CREATE TABLE thread (
    id SERIAL PRIMARY KEY,
    title TEXT NOT NULL,
    description TEXT NOT NULL,
    last_edit_date timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
    id_author INTEGER REFERENCES "user" ON UPDATE CASCADE ON DELETE SET NULL,
    id_forum INTEGER NOT NULL REFERENCES forum ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT thread_uk UNIQUE(title, id_forum)
);

-- R10
CREATE TABLE comment (
    id SERIAL PRIMARY KEY,
    "text" TEXT NOT NULL,
    last_edit_date timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
    id_author INTEGER REFERENCES "user" ON UPDATE CASCADE ON DELETE SET NULL
);

-- R11
CREATE TABLE thread_comment (
    id_comment INTEGER REFERENCES comment ON UPDATE CASCADE ON DELETE CASCADE,
    id_thread INTEGER REFERENCES thread ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT thread_comment_pk PRIMARY KEY (id_comment, id_thread)
);

-- R14
CREATE TABLE task_group (
    id SERIAL PRIMARY KEY,
    title TEXT NOT NULL,
    id_project INTEGER NOT NULL REFERENCES project ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT task_group_uk UNIQUE(title, id_project)
);

-- R15
CREATE TABLE milestone (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    deadline date NOT NULL,
    id_project INTEGER NOT NULL REFERENCES project ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT milestone_uk UNIQUE(name, id_project)
);

-- R13
CREATE TABLE task (
    id SERIAL PRIMARY KEY,
    title TEXT NOT NULL,
    description TEXT NOT NULL,
    progress INTEGER NOT NULL ,
    creation_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
    last_edit_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
    id_project INTEGER NOT NULL REFERENCES project ON UPDATE CASCADE ON DELETE CASCADE,
    id_group INTEGER REFERENCES task_group ON UPDATE CASCADE ON DELETE SET NULL ,
    id_milestone INTEGER REFERENCES milestone ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT percentage_ck CHECK ((progress >= 0) AND (progress <= 100)),
    CONSTRAINT task_uk UNIQUE(title, id_project)
);

-- R12
CREATE TABLE task_comment (
    id_comment INTEGER REFERENCES comment ON UPDATE CASCADE ON DELETE CASCADE,
    id_task INTEGER REFERENCES task ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT task_comment_pk PRIMARY KEY (id_comment, id_task)
);

-- R16
CREATE TABLE team (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL CONSTRAINT team_name_uk UNIQUE,
    skill TEXT,
    id_leader INTEGER NOT NULL REFERENCES team_leader ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT leader_uk UNIQUE (id_leader)
);

-- R17
CREATE TABLE team_task (
    id_team INTEGER REFERENCES team ON UPDATE CASCADE ON DELETE CASCADE,
    id_task INTEGER REFERENCES task ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT team_task_pk PRIMARY KEY (id_team, id_task)
);

-- R18
CREATE TABLE team_project (
    id_team INTEGER REFERENCES team ON UPDATE CASCADE ON DELETE CASCADE,
    id_project INTEGER REFERENCES project ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT team_project_pk PRIMARY KEY (id_team, id_project)
);


ALTER TABLE developer ADD FOREIGN KEY (id_team) REFERENCES team ON UPDATE CASCADE ON DELETE SET NULL