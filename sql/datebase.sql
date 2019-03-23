-- DROPs

DROP TABLE IF EXISTS "User", Administrator, Developer CASCADE;
DROP TABLE IF EXISTS RemovedDeveloper, ProjectManager,TeamMember CASCADE;
DROP TABLE IF EXISTS Follow, Project, Favorite, Forum, Thread CASCADE;
DROP TABLE IF EXISTS Comment, TaskComment, ThreadComment CASCADE;
DROP TABLE IF EXISTS Task, Team, TeamProject, TeamTask CASCADE;
DROP TABLE IF EXISTS TaskGroup, Milestone CASCADE;

DROP TYPE IF EXISTS ProjectStatus CASCADE;

-- Types

CREATE TYPE ProjectStatus AS ENUM ('active', 'canceled', 'closed');

-- TABLES

-- R01
CREATE TABLE "User" (
    id SERIAL PRIMARY KEY,
    username text NOT NULL CONSTRAINT username_uk UNIQUE,
    first_name text NOT NULL,
    last_anem text NOT NULL,
    email text NOT NULL,
    password text NOT NULL
);

-- R02
CREATE TABLE Administrator (
    id_user INTEGER PRIMARY KEY REFERENCES "User" (id) ON UPDATE cascade
);

-- R03
CREATE TABLE Developer (
    id_user INTEGER PRIMARY KEY REFERENCES "User" (id) ON UPDATE cascade
);

-- R04
CREATE TABLE RemovedDeveloper (
    id_developer INTEGER PRIMARY KEY REFERENCES Developer (id_user) ON UPDATE cascade
);

-- R05
CREATE TABLE ProjectManager (
    id_developer INTEGER PRIMARY KEY REFERENCES Developer (id_user) ON UPDATE cascade
);

-- R08
CREATE TABLE Follow (
    id_follower INTEGER REFERENCES "User" (id) ON UPDATE cascade,
    id_followee INTEGER REFERENCES "User" (id) ON UPDATE cascade,
    CONSTRAINT follow_ck CHECK (id_followee <> id_follower),
    CONSTRAINT follwo_pk PRIMARY KEY (id_follower, id_followee)
);

-- R09
CREATE TABLE Project (
    id SERIAL PRIMARY KEY,
    name text NOT NULL CONSTRAINT project_name_uk UNIQUE,
    color text NOT NULL DEFAULT '0,0,255',
    description text NOT NULL, 
    status ProjectStatus NOT NULL DEFAULT 'active',
    id_manager INTEGER REFERENCES ProjectManager ON UPDATE cascade
);

-- R10
CREATE TABLE Favorite (
    id_user INTEGER REFERENCES "User" ON UPDATE cascade,
    id_project INTEGER REFERENCES Project ON UPDATE cascade,
    CONSTRAINT favorite_pk PRIMARY KEY (id_user, id_project)
);

-- R11
CREATE TABLE Forum (
    id SERIAL PRIMARY KEY,
    id_project INTEGER REFERENCES Project ON UPDATE cascade
);

-- R12
CREATE TABLE Thread (
    id SERIAL PRIMARY KEY,
    title text NOT NULL,
    description text NOT NULL,
    last_edit_date timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
    id_author INTEGER REFERENCES "User" ON UPDATE cascade,
    id_forum INTEGER REFERENCES Forum ON UPDATE cascade,
    CONSTRAINT thread_uk UNIQUE(title, id_forum)
);

-- R13
CREATE TABLE Comment (
    id SERIAL PRIMARY KEY,
    "text" text NOT NULL,
    last_edit_date timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
    id_author INTEGER REFERENCES "User" ON UPDATE cascade
);

-- R14
CREATE TABLE ThreadComment (
    id_comment INTEGER REFERENCES Comment ON UPDATE cascade,
    id_thread INTEGER REFERENCES Thread ON UPDATE cascade,
    CONSTRAINT thread_comment_pk PRIMARY KEY (id_comment, id_thread)
);

-- R17
CREATE TABLE TaskGroup (
    id SERIAL PRIMARY KEY,
    title text NOT NULL,
    id_project INTEGER NOT NULL REFERENCES Project ON UPDATE cascade,
    CONSTRAINT task_group_uk UNIQUE(title, id_project)
);

-- R18
CREATE TABLE Milestone (
    id SERIAL PRIMARY KEY,
    name text NOT NULL,
    deadline date NOT NULL,
    id_project INTEGER NOT NULL REFERENCES Project ON UPDATE cascade,
    CONSTRAINT milestone_uk UNIQUE(name, id_project)
);

-- R16
CREATE TABLE Task (
    id SERIAL PRIMARY KEY,
    title text NOT NULL,
    description text NOT NULL,
    progress INTEGER NOT NULL CONSTRAINT percentage_ck CHECK ((progress >= 0) AND (progress <= 100)),
    creation_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
    last_edit_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
    id_project INTEGER NOT NULL REFERENCES Project ON UPDATE cascade,
    id_group INTEGER NOT NULL REFERENCES TaskGroup ON UPDATE cascade,
    id_milestone INTEGER NOT NULL REFERENCES Milestone ON UPDATE cascade,
    CONSTRAINT task_uk UNIQUE(title, id_project)
);

-- R15
CREATE TABLE TaskComment (
    id_comment INTEGER REFERENCES Comment ON UPDATE cascade,
    id_task INTEGER REFERENCES Task ON UPDATE cascade,
    CONSTRAINT task_comment_pk PRIMARY KEY (id_comment, id_task)
);

-- R19
CREATE TABLE Team (
    id SERIAL PRIMARY KEY,
    name text NOT NULL CONSTRAINT team_name_uk UNIQUE,
    skill text
);

-- R20
CREATE TABLE TeamTask (
    id_team INTEGER REFERENCES Team ON UPDATE cascade,
    id_task INTEGER REFERENCES Task ON UPDATE cascade,
    CONSTRAINT team_task_pk PRIMARY KEY (id_team, id_task)
);

-- R21
CREATE TABLE TeamProject (
    id_team INTEGER REFERENCES Team ON UPDATE cascade,
    id_project INTEGER REFERENCES Project ON UPDATE cascade,
    CONSTRAINT team_project_pk PRIMARY KEY (id_team, id_project)
);

-- R06
CREATE TABLE TeamMember (
    id_developer INTEGER PRIMARY KEY REFERENCES Developer (id_user) ON UPDATE cascade,
    id_team INTEGER REFERENCES Team (id) ON UPDATE cascade
);

-- R07
CREATE TABLE TeamLeader (
    id_team_member INTEGER PRIMARY KEY REFERENCES TeamMember (id_developer) ON UPDATE cascade,
    id_team INTEGER REFERENCES Team (id) ON UPDATE cascade
);