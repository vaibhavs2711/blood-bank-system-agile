#!/bin/bash
# ==============================================================================
# Daily Git Pusher Cron Wrapper (Linux / macOS / Raspberry Pi)
# ==============================================================================
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR"

# Ensure git and python3 are in PATH
export PATH="/usr/local/bin:/usr/bin:/bin:$PATH"

# Run Python pusher
python3 cron_daily_pusher.py >> cron_push.log 2>&1
