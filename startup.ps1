#Requires -RunAsAdministrator
<#
.SYNOPSIS
Moroccan Legal Chatbot - Complete Startup Script
Starts all services: MySQL, Backend API, Frontend, Admin Panel

.DESCRIPTION
This script starts all required services for the chatbot system.
Make sure MySQL, PHP, and Node.js are properly installed before running.

.EXAMPLE
PS> .\startup.ps1
#>

param(
    [switch]$SkipFrontend = $false,
    [switch]$SkipAdmin = $false,
    [switch]$SkipBackend = $false
)

$ErrorActionPreference = "Continue"

Write-Host "`n" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host " Moroccan Legal Assistant Chatbot" -ForegroundColor Cyan
Write-Host " Complete System Startup" -ForegroundColor Cyan
Write-Host "========================================`n" -ForegroundColor Cyan

$basePath = "c:\Users\pc\Desktop\New chatBot"
$phpBin = "C:\xampp2\php\php.exe"
$mysqlBin = "C:\xampp2\mysql\bin\mysql.exe"

# Configuration
$backendPort = 8000
$frontendPort = 5173
$adminPort = 5174

# Check prerequisites
Write-Host "[*] Checking prerequisites..." -ForegroundColor Yellow

if (-not (Test-Path $phpBin)) {
    Write-Host "[!] ERROR: PHP not found at $phpBin" -ForegroundColor Red
    Write-Host "   Please install PHP or update the path in this script." -ForegroundColor Red
    exit 1
}
Write-Host "[+] PHP found: $phpBin" -ForegroundColor Green

if (-not (Test-Path $mysqlBin)) {
    Write-Host "[!] WARNING: MySQL not found at $mysqlBin" -ForegroundColor Yellow
}

# Test MySQL connection
Write-Host "`n[*] Testing MySQL connection..." -ForegroundColor Yellow
try {
    $result = & $mysqlBin -u root legal_chatbot -e "SELECT COUNT(*) as tables FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='legal_chatbot';" 2>&1
    if ($LASTEXITCODE -eq 0) {
        Write-Host "[+] MySQL is running and database 'legal_chatbot' exists" -ForegroundColor Green
    } else {
        Write-Host "[!] WARNING: MySQL not responding" -ForegroundColor Yellow
        Write-Host "   Start MySQL: C:\xampp2\mysql_start.bat" -ForegroundColor Yellow
    }
} catch {
    Write-Host "[!] Could not connect to MySQL" -ForegroundColor Yellow
}

# Check Node.js
Write-Host "`n[*] Checking Node.js..." -ForegroundColor Yellow
$nodeFound = (Get-Command node -ErrorAction SilentlyContinue) -ne $null
$npmFound = (Get-Command npm -ErrorAction SilentlyContinue) -ne $null

if ($nodeFound -and $npmFound) {
    $nodeVersion = & node --version
    $npmVersion = & npm --version
    Write-Host "[+] Node.js $nodeVersion and npm $npmVersion found" -ForegroundColor Green
} else {
    Write-Host "[!] WARNING: Node.js or npm not found" -ForegroundColor Yellow
    Write-Host "   Download from: https://nodejs.org/en/download/" -ForegroundColor Yellow
}

# Start Backend API
if (-not $SkipBackend) {
    Write-Host "`n[+] Starting Backend API Server..." -ForegroundColor Cyan
    Write-Host "   URL: http://localhost:$backendPort" -ForegroundColor Green
    Write-Host "   Command: $phpBin -S localhost:$backendPort $basePath\backend\server.php" -ForegroundColor Gray
    
    Start-Process -FilePath $phpBin -ArgumentList "-S", "localhost:$backendPort", "$basePath\backend\server.php" -WindowStyle Normal -PassThru
    Start-Sleep -Seconds 2
} else {
    Write-Host "`n[-] Skipping Backend (use -SkipBackend:`$false to enable)" -ForegroundColor Yellow
}

# Start Frontend
if (-not $SkipFrontend -and $nodeFound -and $npmFound) {
    Write-Host "`n[+] Starting Frontend Dev Server..." -ForegroundColor Cyan
    Write-Host "   URL: http://localhost:$frontendPort" -ForegroundColor Green
    Write-Host "   Path: $basePath\frontend" -ForegroundColor Gray
    
    $scriptBlock = {
        param($path, $port)
        Set-Location $path
        if (-not (Test-Path "node_modules")) {
            Write-Host "[*] Installing dependencies..." -ForegroundColor Yellow
            npm install
        }
        npm run dev
    }
    
    Start-Process -FilePath "powershell.exe" -ArgumentList "-NoExit", "-Command", {Set-Location 'c:\Users\pc\Desktop\New chatBot\frontend'; if (-not (Test-Path 'node_modules')) { npm install }; npm run dev} -WindowStyle Normal -PassThru
    Start-Sleep -Seconds 2
} elseif (-not $nodeFound) {
    Write-Host "`n[-] Skipping Frontend (Node.js not installed)" -ForegroundColor Yellow
} else {
    Write-Host "`n[-] Skipping Frontend (use -SkipFrontend:`$false to enable)" -ForegroundColor Yellow
}

# Start Admin Panel
if (-not $SkipAdmin -and $nodeFound -and $npmFound) {
    Write-Host "`n[+] Starting Admin Panel Dev Server..." -ForegroundColor Cyan
    Write-Host "   URL: http://localhost:$adminPort" -ForegroundColor Green
    Write-Host "   Path: $basePath\admin-panel" -ForegroundColor Gray
    
    Start-Process -FilePath "powershell.exe" -ArgumentList "-NoExit", "-Command", {Set-Location 'c:\Users\pc\Desktop\New chatBot\admin-panel'; if (-not (Test-Path 'node_modules')) { npm install }; npm run dev} -WindowStyle Normal -PassThru
} elseif (-not $nodeFound) {
    Write-Host "`n[-] Skipping Admin Panel (Node.js not installed)" -ForegroundColor Yellow
} else {
    Write-Host "`n[-] Skipping Admin Panel (use -SkipAdmin:`$false to enable)" -ForegroundColor Yellow
}

Write-Host "`n========================================" -ForegroundColor Cyan
Write-Host " Services Started!" -ForegroundColor Cyan
Write-Host "========================================`n" -ForegroundColor Cyan

Write-Host "Backend API:    http://localhost:$backendPort" -ForegroundColor Green
Write-Host "Frontend:       http://localhost:$frontendPort" -ForegroundColor Green
Write-Host "Admin Panel:    http://localhost:$adminPort" -ForegroundColor Green

Write-Host "`nAdmin Credentials:" -ForegroundColor Cyan
Write-Host "  Email:    admin@example.com"
Write-Host "  Password: password123"

Write-Host "`nDatabase:" -ForegroundColor Cyan
Write-Host "  Host:     localhost"
Write-Host "  Database: legal_chatbot"
Write-Host "  User:     root"
Write-Host "  Password: (empty)"

Write-Host "`nTips:" -ForegroundColor Yellow
Write-Host "  - Use Ctrl+C in any window to stop a server"
Write-Host "  - Check server windows for output and errors"
Write-Host "  - All windows will be independent`n" -ForegroundColor Yellow

Write-Host "Press Enter to exit this script (servers will continue running)..." -ForegroundColor Gray
[void][System.Console]::ReadLine()
