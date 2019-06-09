-- Migrate to Version 1 

BEGIN;

CREATE TABLE posts
(
    id      UUID PRIMARY KEY NOT NULL,
    created TIMESTAMP        NOT NULL,
    content TEXT             NOT NULL
);

COMMIT;