@echo off
cd /d "%~dp0"
py cron_daily_pusher.py >> cron_push.log 2>&1
