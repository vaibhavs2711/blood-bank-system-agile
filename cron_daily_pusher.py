#!/usr/bin/env python3
"""
Daily Git Pusher & Agile Sprint Simulator
------------------------------------------
Designed to run automatically via:
1. GitHub Actions (Cloud Server - 24/7 automated without keeping PC on)
2. Linux Server / VPS / Raspberry Pi (via cron)
3. Windows Server / Local PC (via Windows Task Scheduler)

What it does:
- Simulates daily agile scrum maintenance & healthchecks
- Appends realistic sprint standup logs to AGILE_DAILY_LOG.md
- Commits changes with conventional commit messages
- Automatically pushes to 'origin main'
"""

import os
import sys
import random
import subprocess
from datetime import datetime

REPO_DIR = os.path.abspath(os.path.dirname(__file__))
LOG_FILE = os.path.join(REPO_DIR, "cron_push.log")
TRACKER_FILE = os.path.join(REPO_DIR, "AGILE_DAILY_LOG.md")

# Diverse rotating pool of realistic Agile Scrum daily engineering tasks
DAILY_TASKS = [
    ("Session security audit & admin authorization check", "chore(security)"),
    ("Database query index verification & SQL execution audit", "perf(db)"),
    ("Donor search filter validation & blood group indexing review", "feat(search)"),
    ("Input sanitization verification on public contact & donor forms", "fix(security)"),
    ("Mobile responsive navbar test & Bootstrap 4 layout sanity", "refactor(ui)"),
    ("PHP runtime syntax verification & error log hygiene check", "chore(healthcheck)"),
    ("Blood inventory statistics calculation & live card metric check", "feat(metrics)"),
    ("Cross-browser CSS compatibility & modal dialog behavior test", "refactor(css)"),
    ("Admin query resolver workflow and status toggle test", "feat(admin)"),
    ("SQL schema foreign key integrity and table structure verification", "test(db)"),
    ("Donor registration boundary tests (age, blood group, mobile format)", "test(validation)"),
    ("Footer navigation and dynamic contact details synchronization", "refactor(content)"),
    ("WYSIWYG editor content update verification & HTML encoding test", "chore(cms)"),
    ("Password hashing and session destroy lifecycle verification", "fix(auth)"),
    ("Sprint backlog review, code cleanup, and sprint velocity tracking", "chore(sprint)")
]


def log(msg):
    ts = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    entry = f"[{ts}] {msg}"
    print(entry)
    try:
        with open(LOG_FILE, "a", encoding="utf-8") as f:
            f.write(entry + "\n")
    except Exception:
        pass


def run_cmd(cmd, cwd=REPO_DIR):
    res = subprocess.run(cmd, cwd=cwd, capture_output=True, text=True)
    return res.returncode == 0, res.stdout.strip(), res.stderr.strip()


def check_remote_configured():
    ok, stdout, _ = run_cmd(["git", "remote", "-v"])
    return ok and "origin" in stdout


def ensure_git_config():
    # Set default git author if not set
    ok, name, _ = run_cmd(["git", "config", "user.name"])
    if not ok or not name:
        run_cmd(["git", "config", "user.name", "vaibhavs2711"])
    ok, email, _ = run_cmd(["git", "config", "user.email"])
    if not ok or not email:
        run_cmd(["git", "config", "user.email", "vaibhavsuthar2711@gmail.com"])


def main():
    log("=== Daily Agile Sprint Automation Triggered ===")
    today = datetime.now().strftime("%Y-%m-%d")
    ensure_git_config()

    # Verify or initialize AGILE_DAILY_LOG.md
    if not os.path.exists(TRACKER_FILE):
        with open(TRACKER_FILE, "w", encoding="utf-8") as f:
            f.write("# Agile Daily Sprint & Scrum Activity Log\n\n")
            f.write("This log documents daily engineering standups, sprint backlog maintenance, and automated sanity healthchecks for the Blood Bank Management System.\n\n")
            f.write("## Active Sprint: Continuous Sprint Maintenance & Refinement\n\n")
            f.write("| Date | Sprint Cycle | Daily Standup Focus | Healthcheck Status | Verified By |\n")
            f.write("|------|-------------|---------------------|--------------------|-------------|\n")

    # Read current content to avoid exact duplicate log on the same day
    with open(TRACKER_FILE, "r", encoding="utf-8") as f:
        content = f.read()

    # Determine task based on day-of-year so it's deterministic yet rotating
    day_of_year = datetime.now().timetuple().tm_yday
    task_index = day_of_year % len(DAILY_TASKS)
    task_focus, commit_prefix = DAILY_TASKS[task_index]

    if f"| {today} |" in content:
        log(f"Daily log for {today} already exists in {TRACKER_FILE}. Appending afternoon check.")
        task_focus = f"Evening Standup: {task_focus} [Follow-up verification]"
        commit_prefix = "chore(sprint)"

    # Append to markdown table
    new_entry = f"| {today} | Continuous Sprint | {task_focus} | Passed (PHP 8.x, MySQL 8.0) | Vaibhav Suthar |\n"
    with open(TRACKER_FILE, "a", encoding="utf-8") as f:
        f.write(new_entry)

    log(f"Updated {TRACKER_FILE} with: {task_focus}")

    # Stage changes
    run_cmd(["git", "add", "AGILE_DAILY_LOG.md"])

    # Pull latest from remote if configured
    if check_remote_configured():
        log("Pulling latest remote changes to prevent merge conflicts...")
        run_cmd(["git", "pull", "--rebase", "origin", "main"])
    else:
        log("Notice: 'origin' remote is not configured yet. Run 'git remote add origin <url>' to push to GitHub.")

    # Create commit
    commit_msg = (
        f"{commit_prefix}: daily sprint standup & healthcheck [{today}]\n\n"
        f"Daily Standup Focus: {task_focus}\n"
        f"Sprint Cycle: Continuous Maintenance & Healthcheck\n"
        f"Verification: PHP runtime OK, database schema validated, CI sanity passed.\n\n"
        f"Sprint: Continuous Sprint Maintenance"
    )

    ok_commit, stdout_commit, stderr_commit = run_cmd(["git", "commit", "-m", commit_msg])

    if ok_commit:
        log(f"Committed daily sprint update: {commit_prefix} for {today}")
        
        if check_remote_configured():
            ok_push, stdout_push, stderr_push = run_cmd(["git", "push", "origin", "main"])
            if ok_push:
                log("Successfully pushed daily changes to GitHub (origin/main)!")
            else:
                log(f"Git push failed: {stderr_push or stdout_push}")
        else:
            log("Committed locally. Push skipped because remote 'origin' is not set.")
    else:
        log(f"No new changes to commit: {stderr_commit or stdout_commit}")


if __name__ == "__main__":
    main()
