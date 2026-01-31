# Database Setup Script for Inner Flow

Write-Host "================================================" -ForegroundColor Cyan
Write-Host "  Inner Flow Database Setup" -ForegroundColor Cyan
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""

$dbName = "inner_flow"
$dbUser = "wordpress"
$dbPassword = "wordpress"

Write-Host "Creating MySQL database and user..." -ForegroundColor Yellow

# Create SQL commands
$sqlCommands = @"
CREATE DATABASE IF NOT EXISTS $dbName CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '$dbUser'@'localhost' IDENTIFIED BY '$dbPassword';
GRANT ALL PRIVILEGES ON $dbName.* TO '$dbUser'@'localhost';
FLUSH PRIVILEGES;
"@

# Save to temporary file
$sqlFile = "temp-db-setup.sql"
$sqlCommands | Out-File -FilePath $sqlFile -Encoding UTF8

# Execute SQL
try {
    Write-Host "Executing database setup..." -ForegroundColor Yellow
    mysql -u root -e "source $sqlFile"
    Write-Host "Database created successfully!" -ForegroundColor Green
} catch {
    Write-Host "Error creating database. Trying with password..." -ForegroundColor Yellow
    Write-Host "Please enter your MySQL root password when prompted." -ForegroundColor Yellow
    mysql -u root -p -e "source $sqlFile"
}

# Cleanup
Remove-Item $sqlFile -ErrorAction SilentlyContinue

Write-Host ""
Write-Host "Database setup complete!" -ForegroundColor Green
Write-Host ""
Write-Host "Database Name: $dbName" -ForegroundColor Cyan
Write-Host "Database User: $dbUser" -ForegroundColor Cyan
Write-Host "Database Password: $dbPassword" -ForegroundColor Cyan
Write-Host ""
