@echo off
title Import Database - Blood Bank Management System
echo ==============================================================
echo Importing sql\blood_bank_database.sql into MySQL...
echo ==============================================================

set MYSQL_BIN="C:\Program Files\MySQL\MySQL Server 8.0\bin\mysql.exe"

if not exist %MYSQL_BIN% (
    set MYSQL_BIN=mysql
)

echo Please enter your MySQL root password below when prompted:
%MYSQL_BIN% -u root -p < "sql\blood_bank_database.sql"

if %errorlevel% equ 0 (
    echo.
    echo [SUCCESS] Database 'blood_donation' imported successfully!
) else (
    echo.
    echo [ERROR] Database import failed. Please verify your root password.
)

echo.
pause
