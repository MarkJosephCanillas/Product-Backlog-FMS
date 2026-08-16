# Agile Scheduling Portal — Functional Setup Guide

This version saves real data to a MySQL database instead of resetting on refresh.

## Files
- `schema.sql` — creates the `product_backlog` and `sprint_backlog` tables (with sample seed data).
- `db_config.php` — your database connection settings.
- `api.php` — backend endpoint that handles Add, Delete, Commit to Sprint, Assign, and Advance Status.
- `agile_backlog.php` — the page you open in the browser.

## Setup steps

1. **Create the database (skip if you already have one, e.g. your FMS database).**
   In phpMyAdmin (or `mysql` CLI), create a database, or reuse your existing
   `faculty_management_system` database.

2. **Import the schema.**
   Open phpMyAdmin → select your database → **Import** → choose `schema.sql` → Go.
   This creates the two tables and adds sample data matching the reference sample.

3. **Set your DB credentials.**
   Open `db_config.php` and update:
   ```php
   $DB_HOST = "localhost";
   $DB_USER = "root";
   $DB_PASS = "";
   $DB_NAME = "faculty_management_system"; // <-- your actual database name
   ```

4. **Upload the 3 PHP files + this README to your server**, in the same folder
   (e.g. inside your existing FMS project, like `/agile_backlog/`).
   Keep `db_config.php`, `api.php`, and `agile_backlog.php` together in the same directory —
   `agile_backlog.php` calls `api.php` using a relative path.

5. **Open it in your browser**, e.g.:
   ```
   https://facultymanagementsystem.site.je/agile_backlog/agile_backlog.php
   ```
   (or `http://localhost/agile_backlog/agile_backlog.php` if testing on XAMPP/WAMP locally.)

## How it works

- **Add to Product Backlog** → inserts a new row into `product_backlog`, auto-generates
  the next `US-XX` code.
- **→ Sprint button** → moves that row into `sprint_backlog` and removes it from
  `product_backlog` (a real "commit" action).
- **Assignee dropdown** → updates `sprint_backlog.assignee` immediately.
- **Advance → button** → cycles `sprint_backlog.status` through
  `To Do → In Progress → Completed`, one click per step.
- Every action calls `api.php` with `fetch()`, gets a JSON response, then reloads
  the two tables from the database — so refreshing the page or opening it on
  another device shows the same, persisted data.

## Notes / things you may want to adjust

- Assignee names are currently a fixed list (`Unassigned`, `Developer A`, `Developer B`,
  `Database Admin`, `QA Team`) inside `agile_backlog.php`. If you want it to pull real
  faculty names from your existing FMS `faculty` table, that's a small follow-up change
  to `api.php` — just say the word.
- There's no login check on this page yet. If it needs to be admin-only or tied to a
  specific faculty account, that's also easy to add if you tell me how your FMS
  currently handles sessions/login.
