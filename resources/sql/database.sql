-------------------------------
-- DROPS
-------------------------------

DROP TABLE IF EXISTS "user", administrator, developer CASCADE;
DROP TABLE IF EXISTS follow, project, favorite, forum, thread CASCADE;
DROP TABLE IF EXISTS comment, task_comment, thread_comment CASCADE;
DROP TABLE IF EXISTS task, team, team_project, team_task CASCADE;
DROP TABLE IF EXISTS task_group, milestone CASCADE;

DROP TYPE IF EXISTS ProjectStatus CASCADE;

DROP VIEW IF EXISTS comments_of_task, comments_of_thread CASCADE;

DROP FUNCTION IF EXISTS admin_user() CASCADE;
DROP FUNCTION IF EXISTS developer_user() CASCADE;
DROP FUNCTION IF EXISTS company_forum() CASCADE;
DROP FUNCTION IF EXISTS task_in_task_group_or_milestone() CASCADE;
DROP FUNCTION IF EXISTS team_member() CASCADE;
DROP FUNCTION IF EXISTS manage_task_comment() CASCADE;
DROP FUNCTION IF EXISTS manage_thread_comment() CASCADE;
DROP FUNCTION IF EXISTS team_project() CASCADE;
DROP FUNCTION IF EXISTS remove_user() CASCADE;
DROP FUNCTION IF EXISTS add_developer() CASCADE;
DROP FUNCTION IF EXISTS add_forum() CASCADE;

DROP TRIGGER IF EXISTS admin_user ON administrator;
DROP TRIGGER IF EXISTS developer_user ON developer;
DROP TRIGGER IF EXISTS company_forum ON forum;
DROP TRIGGER IF EXISTS task_in_task_group_or_milestone ON task;
DROP TRIGGER IF EXISTS team_member ON developer;
DROP TRIGGER IF EXISTS manage_view_task_comment ON comments_of_task;
DROP TRIGGER IF EXISTS manage_view_thread_comment ON comments_of_thread;
DROP TRIGGER IF EXISTS team_project ON team_task;
DROP TRIGGER IF EXISTS remove_user ON developer;
DROP TRIGGER IF EXISTS add_developer ON "user";
DROP TRIGGER IF EXISTS add_forum ON project;

-------------------------------
-- TYPES
-------------------------------

CREATE TYPE ProjectStatus AS ENUM ('active', 'canceled', 'closed');

-------------------------------
-- TABLES
-------------------------------

-- R01
CREATE TABLE "user" (
    id SERIAL PRIMARY KEY ,
    username TEXT NOT NULL CONSTRAINT username_uk UNIQUE,
    first_name TEXT NOT NULL,
    last_name TEXT NOT NULL,
    email TEXT NOT NULL,
    password TEXT NOT NULL,
    biography TEXT,
    remember_token TEXT DEFAULT NULL
);

-- R02
CREATE TABLE administrator (
    id_user INTEGER PRIMARY KEY REFERENCES "user" ON UPDATE CASCADE ON DELETE CASCADE
);

-- R03
CREATE TABLE developer (
    id_user INTEGER PRIMARY KEY REFERENCES "user" ON UPDATE CASCADE ON DELETE CASCADE,
    id_team INTEGER,
    is_active BOOLEAN NOT NULL
);

-- R04
CREATE TABLE follow (
    id_follower INTEGER REFERENCES "user" ON UPDATE CASCADE ON DELETE CASCADE,
    id_followee INTEGER REFERENCES "user" ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT follow_ck CHECK (id_followee <> id_follower),
    CONSTRAINT follow_pk PRIMARY KEY (id_follower, id_followee)
);

-- R05
CREATE TABLE project (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL CONSTRAINT project_name_uk UNIQUE,
    color TEXT NOT NULL DEFAULT '0000ff',
    description TEXT NOT NULL, 
    status ProjectStatus NOT NULL DEFAULT 'active',
    id_manager INTEGER NOT NULL REFERENCES developer ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT project_manager_uk UNIQUE(id_manager)
);

-- R06
CREATE TABLE favorite (
    id_user INTEGER REFERENCES "user" ON UPDATE CASCADE ON DELETE CASCADE,
    id_project INTEGER REFERENCES project ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT favorite_pk PRIMARY KEY (id_user, id_project)
);

-- R07
CREATE TABLE forum (
    id SERIAL PRIMARY KEY,
    id_project INTEGER REFERENCES project ON UPDATE CASCADE ON DELETE CASCADE
);

-- R08
CREATE TABLE thread (
    id SERIAL PRIMARY KEY,
    title TEXT NOT NULL,
    description TEXT NOT NULL,
    creation_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
    last_edit_date timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
    id_author INTEGER NOT NULL REFERENCES "user" ON UPDATE CASCADE ON DELETE RESTRICT,
    id_forum INTEGER NOT NULL REFERENCES forum ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT thread_uk UNIQUE(title, id_forum)
);

-- R09
CREATE TABLE comment (
    id SERIAL PRIMARY KEY,
    "text" TEXT NOT NULL,
    creation_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
    last_edit_date timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
    id_author INTEGER NOT NULL REFERENCES "user" ON UPDATE CASCADE ON DELETE RESTRICT
);

-- R10
CREATE TABLE thread_comment (
    id_comment INTEGER REFERENCES comment ON UPDATE CASCADE ON DELETE CASCADE,
    id_thread INTEGER REFERENCES thread ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT thread_comment_pk PRIMARY KEY (id_comment, id_thread)
);

-- R13
CREATE TABLE task_group (
    id SERIAL PRIMARY KEY,
    title TEXT NOT NULL,
    id_project INTEGER NOT NULL REFERENCES project ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT task_group_uk UNIQUE(title, id_project)
);

-- R14
CREATE TABLE milestone (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    deadline timestamp NOT NULL,
    id_project INTEGER NOT NULL REFERENCES project ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT milestone_uk UNIQUE(name, id_project)
);

-- R12
CREATE TABLE task (
    id SERIAL PRIMARY KEY,
    title TEXT NOT NULL,
    description TEXT NOT NULL,
    progress INTEGER NOT NULL DEFAULT 0,
    creation_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
    last_edit_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
    id_project INTEGER NOT NULL REFERENCES project ON UPDATE CASCADE ON DELETE CASCADE,
    id_group INTEGER REFERENCES task_group ON UPDATE CASCADE ON DELETE SET NULL ,
    id_milestone INTEGER REFERENCES milestone ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT percentage_ck CHECK ((progress >= 0) AND (progress <= 100)),
    CONSTRAINT task_uk UNIQUE(title, id_project)
);

-- R11
CREATE TABLE task_comment (
    id_comment INTEGER REFERENCES comment ON UPDATE CASCADE ON DELETE CASCADE,
    id_task INTEGER REFERENCES task ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT task_comment_pk PRIMARY KEY (id_comment, id_task)
);

-- R15
CREATE TABLE team (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL CONSTRAINT team_name_uk UNIQUE,
    skill TEXT,
    id_leader INTEGER NOT NULL REFERENCES developer ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT leader_uk UNIQUE (id_leader)
);

-- R16
CREATE TABLE team_task (
    id_team INTEGER REFERENCES team ON UPDATE CASCADE ON DELETE CASCADE,
    id_task INTEGER REFERENCES task ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT team_task_pk PRIMARY KEY (id_team, id_task)
);

-- R17
CREATE TABLE team_project (
    id_team INTEGER REFERENCES team ON UPDATE CASCADE ON DELETE CASCADE,
    id_project INTEGER REFERENCES project ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT team_project_pk PRIMARY KEY (id_team, id_project)
);

ALTER TABLE developer ADD FOREIGN KEY (id_team) REFERENCES team ON UPDATE CASCADE ON DELETE SET NULL;


-------------------------------
--- VIEWS
-------------------------------

CREATE VIEW comments_of_task AS
    SELECT *
    FROM task_comment LEFT JOIN comment 
        ON task_comment.id_comment = comment.id;

CREATE VIEW comments_of_thread AS
   SELECT *
   FROM thread_comment LEFT JOIN comment
       ON thread_comment.id_comment = comment.id;

-------------------------------
--- INDEXES
-------------------------------

CREATE INDEX teamproject_projectid ON team_project USING hash (id_project);

CREATE INDEX teamtask_teamid ON team_task USING hash (id_team);

CREATE INDEX task_projectid ON task USING hash (id_project);

CREATE INDEX comment_creationdate ON comment USING btree (creation_date);

CREATE INDEX thread_creationdate ON thread USING btree (creation_date);

-------------------------------
--- TRIGGERS
-------------------------------

--- TRIGGER01
CREATE FUNCTION admin_user() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (SELECT * FROM developer WHERE developer.id_user = NEW.id_user) THEN
        RAISE EXCEPTION 'A developer cannot be an administrator.';
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER admin_user
    BEFORE INSERT ON administrator
    FOR EACH ROW
    EXECUTE PROCEDURE admin_user();

CREATE FUNCTION developer_user() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (SELECT * FROM administrator WHERE administrator.id_user = NEW.id_user) THEN
        RAISE EXCEPTION 'An administrator cannot be a developer.';
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER developer_user
    BEFORE INSERT ON developer
    FOR EACH ROW
    EXECUTE PROCEDURE developer_user();


--- TRIGGER02
CREATE FUNCTION company_forum() RETURNS TRIGGER AS
$BODY$
DECLARE
    count int;
BEGIN
    SELECT count(*) into count FROM forum WHERE forum.id_project = NULL;
    IF ((count = 1) AND (NEW.id_project IS NULL)) THEN
        RAISE EXCEPTION 'A forum must belong to a project.';
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER company_forum
    BEFORE INSERT ON forum
    FOR EACH ROW
    EXECUTE PROCEDURE company_forum();

--- TRIGGER03
CREATE FUNCTION task_in_task_group_or_milestone() RETURNS TRIGGER AS
$BODY$
DECLARE
    deadline_milestone milestone.deadline%type;
BEGIN
    IF (NEW.id_group IS NOT NULL) THEN
        IF NOT EXISTS (SELECT * FROM task_group WHERE task_group.id = NEW.id_group AND task_group.id_project = NEW.id_project) THEN
            RAISE EXCEPTION 'A task must belong to a task group inserted on the same project.';
        END IF;
    END IF;    
    IF (NEW.id_milestone IS NOT NULL) THEN
        IF NOT EXISTS (SELECT * FROM milestone WHERE milestone.id = NEW.id_milestone AND milestone.id_project = NEW.id_project) THEN
            RAISE EXCEPTION 'A task must belong to a milestone inserted on the same project.';
        END IF;
        
        SELECT deadline INTO deadline_milestone FROM milestone WHERE id = NEW.id_milestone;

        IF (deadline_milestone <= (NEW.creation_date + '1 day'::interval)) THEN
            RAISE EXCEPTION 'A task creation date and milestone deadline must be separated by at least 1 day.';
        END IF;
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER task_in_task_group_or_milestone
    BEFORE INSERT OR UPDATE OF id_group, id_milestone ON task
    FOR EACH ROW
    WHEN (NEW.id_group IS NOT NULL OR NEW.id_milestone IS NOT NULL)
    EXECUTE PROCEDURE task_in_task_group_or_milestone();

--- TRIGGER04
CREATE FUNCTION team_member() RETURNS TRIGGER AS
$BODY$
BEGIN    
    IF EXISTS (SELECT * FROM developer WHERE id_user = NEW.id_user AND is_active = false) THEN
        RAISE EXCEPTION 'A team member must be active (not removed).';
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER team_member
    BEFORE INSERT OR UPDATE OF id_team ON developer
    FOR EACH ROW
    EXECUTE PROCEDURE team_member();

--- TRIGGER05
CREATE FUNCTION manage_task_comment() RETURNS TRIGGER AS
$BODY$
DECLARE
    id_comm comment.id%type;
BEGIN
    IF TG_OP = 'INSERT' THEN
        INSERT INTO comment("text", id_author) VALUES (NEW."text", NEW.id_author) RETURNING id INTO id_comm;
        INSERT INTO task_comment (id_comment, id_task) VALUES (id_comm, NEW.id_task);
        RETURN NEW;
    ELSEIF TG_OP = 'DELETE' THEN
        DELETE FROM comment WHERE id = OLD.id;
        RETURN NULL;
    END IF;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER manage_view_task_comment
   INSTEAD OF INSERT OR DELETE ON comments_of_task
   FOR EACH ROW
   EXECUTE PROCEDURE manage_task_comment();

--- TRIGGER06
CREATE FUNCTION manage_thread_comment() RETURNS TRIGGER AS
$BODY$
DECLARE
    id_comm comment.id%type;
BEGIN
    IF TG_OP = 'INSERT' THEN
        INSERT INTO comment("text", id_author) VALUES (NEW."text", NEW.id_author) RETURNING id INTO id_comm;
        INSERT INTO thread_comment(id_comment, id_thread) VALUES (id_comm, NEW.id_thread);
        RETURN NEW;
    ELSEIF TG_OP = 'DELETE' THEN
        DELETE FROM comment WHERE id = OLD.id;
        RETURN NULL;
    END IF;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER manage_view_thread_comment
   INSTEAD OF INSERT OR DELETE ON comments_of_thread
   FOR EACH ROW
   EXECUTE PROCEDURE manage_thread_comment();


--- TRIGGER07
CREATE FUNCTION team_project() RETURNS TRIGGER AS
$BODY$
DECLARE
    id_project_task task.id_project%type;
BEGIN
    IF TG_OP = 'INSERT' THEN
        SELECT task.id_project INTO id_project_task FROM task WHERE task.id = NEW.id_task;
        IF EXISTS (SELECT * FROM team_project WHERE id_team = NEW.id_team AND id_project = id_project_task) THEN
            RETURN NEW;
        ELSE
            INSERT INTO team_project(id_team,id_project) VALUES (NEW.id_team, id_project_task);
            RETURN NEW;
        END IF;
    ELSEIF TG_OP = 'DELETE' THEN
        SELECT id_project INTO id_project_task FROM task WHERE id = OLD.id_task;
        IF EXISTS (
                SELECT * 
                FROM team_task INNER JOIN task 
                    ON team_task.id_task = task.id 
                WHERE team_task.id_team = OLD.id_team AND task.id_project = id_project_task) THEN
            RETURN NULL;
        ELSE
            DELETE FROM team_project WHERE id_team = OLD.id_team AND id_project = id_project_task;
            RETURN NULL;
        END IF;
    END IF;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER team_project
   BEFORE INSERT OR DELETE ON team_task
   FOR EACH ROW
   EXECUTE PROCEDURE team_project();


--- TRIGGER08
CREATE FUNCTION remove_user() RETURNS TRIGGER AS
$BODY$
DECLARE
BEGIN
    IF EXISTS (SELECT * FROM team WHERE id_leader = NEW.id_user) THEN
        RAISE EXCEPTION 'This user cannot be removed because he’s a Team Leader. You must first reassign his role in order to be able to remove him.';
    END IF;

    IF EXISTS (SELECT * FROM project WHERE id_manager = NEW.id_user) THEN
        RAISE EXCEPTION 'This user cannot be removed because he’s a Project Manager. You must first reassign his role in order to be able to remove him';
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER remove_user
   BEFORE UPDATE OF is_active ON developer
   FOR EACH ROW
   EXECUTE PROCEDURE remove_user();


--- TRIGGER09
CREATE FUNCTION add_developer() RETURNS TRIGGER AS
$BODY$
DECLARE
    id_new_user "user".id%type;
BEGIN
    SELECT id INTO id_new_user FROM "user" WHERE username = NEW.username;
    INSERT INTO developer(id_user, is_active) VALUES (id_new_user, 'TRUE');
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER add_developer
   AFTER INSERT ON "user"
   FOR EACH ROW
   EXECUTE PROCEDURE add_developer();

--- TRIGGER10
CREATE FUNCTION add_forum() RETURNS TRIGGER AS
$BODY$
DECLARE
    id_new_project project.id%type;
BEGIN
    SELECT id INTO id_new_project FROM project WHERE name = NEW.name;
    INSERT INTO forum(id_project) VALUES (id_new_project);
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER add_forum
   AFTER INSERT ON project
   FOR EACH ROW
   EXECUTE PROCEDURE add_forum();