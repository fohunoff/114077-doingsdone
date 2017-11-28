CREATE DATABASE doingsdone;

USE doingsdone;

CREATE TABLE users (
    id              int AUTO_INCREMENT PRIMARY KEY,
    email           varchar(128) NOT NULL,
    password        varchar(32) NOT NULL,
    name            varchar(64) NOT NULL,
    avatar_path     varchar(128),
    group_id        int(5),
    is_deleted      tinyint(1)
);

CREATE UNIQUE INDEX email ON users(email);

CREATE TABLE groups (
    id      int AUTO_INCREMENT PRIMARY KEY,
    name    varchar(32) NOT NULL
);

CREATE TABLE projects (
    id          int AUTO_INCREMENT PRIMARY KEY,
    name        varchar(32) NOT NULL,
    user_id     int(10) NOT NULL
);

CREATE INDEX project_name ON projects(name);

CREATE TABLE tasks (
    id                  int AUTO_INCREMENT PRIMARY KEY,
    name                varchar(32) NOT NULL,
    date                date,
    user_id             int(10) NOT NULL,
    project_id          int(10) NOT NULL,
    is_done             tinyint(1),
    file_path           varchar(128)
);

CREATE INDEX task_deadline ON tasks(date);