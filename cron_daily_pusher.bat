@echo off
cd /d "%~dp0"
where python >nul 2>nul
if %errorlevel% equ 0 (
    python cron_daily_pusher.py >> cron_push.log 2>&1
) else (
    py cron_daily_pusher.py >> cron_push.log 2>&1
)
