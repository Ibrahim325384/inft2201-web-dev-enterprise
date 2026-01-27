CREATE TABLE IF NOT EXISTS demo_ping (
    id SERIAL PRIMARY KEY,
    message TEXT NOT NULL
);

INSERT INTO demo_ping(message) Values ('Postgres is up!');