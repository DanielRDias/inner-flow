# Setup Inner Flow WordPress Site Locally

# This script will help you set up the Inner Flow WordPress site on your local machine

Write-Host "================================================" -ForegroundColor Cyan
Write-Host "  Inner Flow WordPress Setup Script" -ForegroundColor Cyan
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""

# Check if Chocolatey is installed
Write-Host "Checking for Chocolatey package manager..." -ForegroundColor Yellow
if (!(Get-Command choco -ErrorAction SilentlyContinue)) {
    Write-Host "Chocolatey not found. Installing Chocolatey..." -ForegroundColor Yellow
    Set-ExecutionPolicy Bypass -Scope Process -Force
    [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072
    Invoke-Expression ((New-Object System.Net.WebClient).DownloadString('https://community.chocolatey.org/install.ps1'))
    
    Write-Host "Chocolatey installed successfully!" -ForegroundColor Green
    Write-Host "Please restart PowerShell and run this script again." -ForegroundColor Yellow
    exit
} else {
    Write-Host "Chocolatey is already installed." -ForegroundColor Green
}

Write-Host ""

# Install PHP
Write-Host "Checking for PHP..." -ForegroundColor Yellow
if (!(Get-Command php -ErrorAction SilentlyContinue)) {
    Write-Host "Installing PHP..." -ForegroundColor Yellow
    choco install php -y
    refreshenv
} else {
    Write-Host "PHP is already installed." -ForegroundColor Green
    php --version
}

Write-Host ""

# Install Composer
Write-Host "Checking for Composer..." -ForegroundColor Yellow
if (!(Get-Command composer -ErrorAction SilentlyContinue)) {
    Write-Host "Installing Composer..." -ForegroundColor Yellow
    choco install composer -y
    refreshenv
} else {
    Write-Host "Composer is already installed." -ForegroundColor Green
}

Write-Host ""

# Install MariaDB/MySQL
Write-Host "Checking for MySQL/MariaDB..." -ForegroundColor Yellow
if (!(Get-Command mysql -ErrorAction SilentlyContinue)) {
    Write-Host "Installing MariaDB..." -ForegroundColor Yellow
    choco install mariadb -y
    refreshenv
} else {
    Write-Host "MySQL/MariaDB is already installed." -ForegroundColor Green
}

Write-Host ""
Write-Host "================================================" -ForegroundColor Cyan
Write-Host "  Installation Complete!" -ForegroundColor Green
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Next steps:" -ForegroundColor Yellow
Write-Host "1. Restart your PowerShell terminal" -ForegroundColor White
Write-Host "2. Run: .\setup-database.ps1" -ForegroundColor White
Write-Host "3. Run: .\start-server.ps1" -ForegroundColor White
Write-Host ""
