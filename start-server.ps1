# Start Inner Flow WordPress Server

Write-Host "================================================" -ForegroundColor Cyan
Write-Host "  Starting Inner Flow WordPress Server" -ForegroundColor Cyan
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""

# Update wp-config for localhost
$wpConfigPath = "wp-config.php"
if (Test-Path $wpConfigPath) {
    $content = Get-Content $wpConfigPath -Raw
    $content = $content -replace "define\(\s*'DB_HOST',\s*'db:3306'\s*\);", "define( 'DB_HOST', 'localhost' );"
    $content = $content -replace "define\(\s*'DB_USER',\s*'wordpress'\s*\);", "define( 'DB_USER', 'wordpress' );"
    $content | Set-Content $wpConfigPath
    Write-Host "Updated wp-config.php for local development" -ForegroundColor Green
}

Write-Host ""
Write-Host "Starting PHP built-in server..." -ForegroundColor Yellow
Write-Host ""
Write-Host "Your Inner Flow site will be available at:" -ForegroundColor Green
Write-Host "  http://localhost:8080" -ForegroundColor Cyan
Write-Host ""
Write-Host "Press Ctrl+C to stop the server" -ForegroundColor Yellow
Write-Host ""
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""

# Start PHP server
php -S localhost:8080 -t .
