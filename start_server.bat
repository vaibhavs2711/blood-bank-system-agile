@echo off
title Blood Bank & Donation Management System
echo ==============================================================
echo Starting Blood Bank & Donation Management System Server...
echo ==============================================================

:: Check if PHP is in PATH, otherwise use the winget installed location
where php >nul 2>nul
if %errorlevel% neq 0 (
    set "PATH=C:\Users\%USERNAME%\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.2_Microsoft.Winget.Source_8wekyb3d8bbwe;%PATH%"
)

echo Server running at http://localhost:8000
echo Press Ctrl+C in this window to stop the server.
echo.

start http://localhost:8000/
php -S localhost:8000
pause
