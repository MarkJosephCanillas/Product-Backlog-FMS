-- ============================================================
-- Agile Scheduling Portal: Product Backlog & Sprint Backlog
-- Run this once in phpMyAdmin / MySQL to create the tables.
-- ============================================================

CREATE TABLE IF NOT EXISTS product_backlog (
    id INT AUTO_INCREMENT PRIMARY KEY,
    story_code VARCHAR(10) NOT NULL,
    persona VARCHAR(100) NOT NULL,
    goal VARCHAR(255) NOT NULL,
    benefit VARCHAR(255) DEFAULT NULL,
    full_story TEXT NOT NULL,
    priority ENUM('High','Medium','Low') NOT NULL DEFAULT 'Medium',
    points INT NOT NULL DEFAULT 5,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS sprint_backlog (
    id INT AUTO_INCREMENT PRIMARY KEY,
    story_code VARCHAR(10) NOT NULL,
    task TEXT NOT NULL,
    priority ENUM('High','Medium','Low') NOT NULL DEFAULT 'Medium',
    points INT NOT NULL DEFAULT 5,
    assignee VARCHAR(100) NOT NULL DEFAULT 'Unassigned',
    status ENUM('To Do','In Progress','Completed') NOT NULL DEFAULT 'To Do',
    committed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Optional seed data matching the reference sample. Remove this block
-- if you want to start with empty tables.

INSERT INTO product_backlog (story_code, persona, goal, benefit, full_story, priority, points) VALUES
('US-04','student','grade viewing','I can check my progress','As a student, I want grade viewing, so that I can check my progress.','Medium',5),
('US-05','staff member','system reports',NULL,'As a staff member, I want system reports.','Medium',8),
('US-06','user','email notifications',NULL,'As a user, I want email notifications.','Low',3);

INSERT INTO sprint_backlog (story_code, task, priority, points, assignee, status) VALUES
('US-01','Login Module Integration','High',5,'Developer A','In Progress'),
('US-02','Registration Form Design','High',8,'Developer B','To Do'),
('US-03','Database Schema Setup','High',13,'Database Admin','Completed');
