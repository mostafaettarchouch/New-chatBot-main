@echo off
REM Moroccan Legal Chatbot - Complete Startup Script
REM Run this batch file to start all servers
REM Requires: PHP, MySQL, and Node.js to be installed

setlocal enabledelayedexpansion

echo.
echo ========================================
echo  Moroccan Legal Assistant Chatbot
echo  Complete System Startup
echo ========================================
echo.

set BASE_PATH=c:\Users\pc\Desktop\New chatBot
set PHP_BIN=C:\xampp2\php\php.exe
set MYSQL_BIN=C:\xampp2\mysql\bin\mysql.exe

REM Check if PHP is available
if not exist "%PHP_BIN%" (
    echo ERROR: PHP not found at %PHP_BIN%
    echo Please install PHP or update the path in this script.
    pause
    exit /b 1
)

REM Check if MySQL is running
echo.
echo [*] Checking MySQL connection...
"%MYSQL_BIN%" -u root legal_chatbot -e "SELECT COUNT(*) as tables FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='legal_chatbot';" >nul 2>&1
if %ERRORLEVEL% neq 0 (
    echo [!] WARNING: MySQL not responding. Make sure MySQL is running.
    echo Start MySQL: C:\xampp2\mysql_start.bat
    pause
)

REM Check if Node.js is installed
echo [*] Checking Node.js...
where node >nul 2>&1
if %ERRORLEVEL% neq 0 (
    echo [!] WARNING: Node.js not found in PATH
    echo Download from: https://nodejs.org/en/download/
    echo Install and then run this script again.
    pause
)

echo.
echo [*] Starting services...
echo.

REM Start backend API server (new window)
echo [+] Starting Backend API Server on http://localhost:8000
start "Backend API" "%PHP_BIN%" -S localhost:8000 "%BASE_PATH%\backend\server.php"
timeout /t 2 /nobreak

REM Start frontend (new window)
where npm >nul 2>&1
if %ERRORLEVEL% equ 0 (
    echo [+] Starting Frontend on http://localhost:5173
    start "Frontend" cmd /k "cd /d %BASE_PATH%\frontend && npm run dev"
    timeout /t 2 /nobreak
) else (
    echo [!] Node.js not found - skipping frontend
)

REM Start admin panel (new window)
where npm >nul 2>&1
if %ERRORLEVEL% equ 0 (
    echo [+] Starting Admin Panel on http://localhost:5174
    start "Admin Panel" cmd /k "cd /d %BASE_PATH%\admin-panel && npm run dev"
) else (
    echo [!] Node.js not found - skipping admin panel
)

echo.
echo ========================================
echo  Services Started!
echo ========================================
echo.
echo Backend API:    http://localhost:8000
echo Frontend:       http://localhost:5173
echo Admin Panel:    http://localhost:5174
echo.
echo Admin Credentials:
echo   Email:    admin@example.com
echo   Password: password123
echo.
echo Database:
echo   Host:     localhost
echo   Database: legal_chatbot
echo   User:     root
echo.
echo Press Ctrl+C in any window to stop a server.
echo All windows will close when main process ends.
echo.
pause
