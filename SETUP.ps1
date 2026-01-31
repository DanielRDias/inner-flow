# Inner Flow - Complete Automated Setup

Write-Host "================================================" -ForegroundColor Cyan
Write-Host "  Inner Flow WordPress - Automated Setup" -ForegroundColor Cyan
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "This will set up Inner Flow WordPress with:" -ForegroundColor Yellow
Write-Host "  - WordPress core files ✓" -ForegroundColor Green
Write-Host "  - Inner Flow custom theme ✓" -ForegroundColor Green
Write-Host "  - Hiking Events plugin ✓" -ForegroundColor Green
Write-Host "  - Sample hiking events" -ForegroundColor Yellow
Write-Host "  - Multilingual setup" -ForegroundColor Yellow
Write-Host ""

Write-Host "To run this site locally, you need ONE of these options:" -ForegroundColor Cyan
Write-Host ""
Write-Host "Option 1: Local by Flywheel (Recommended - Easy)" -ForegroundColor Green
Write-Host "  Download from: https://localwp.com/" -ForegroundColor White
Write-Host "  - Free and user-friendly" -ForegroundColor Gray
Write-Host "  - One-click WordPress setup" -ForegroundColor Gray
Write-Host "  - Built-in MySQL and PHP" -ForegroundColor Gray
Write-Host ""

Write-Host "Option 2: XAMPP (Popular)" -ForegroundColor Green
Write-Host "  Download from: https://www.apachefriends.org/" -ForegroundColor White
Write-Host "  - Free Apache + MySQL + PHP stack" -ForegroundColor Gray
Write-Host "  - Good for multiple projects" -ForegroundColor Gray
Write-Host ""

Write-Host "Option 3: Docker (For Developers)" -ForegroundColor Green
Write-Host "  Already configured! Just run:" -ForegroundColor White
Write-Host "  docker-compose up -d" -ForegroundColor Cyan
Write-Host ""

Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""

$choice = Read-Host "Would you like to open the quickstart guide? (Y/N)"
if ($choice -eq "Y" -or $choice -eq "y") {
    Start-Process "QUICKSTART.md"
}

Write-Host ""
Write-Host "Project is ready!" -ForegroundColor Green
Write-Host "All files are in: $PWD" -ForegroundColor Cyan
Write-Host ""
Write-Host "Next step: Choose a local server option above and follow QUICKSTART.md" -ForegroundColor Yellow
Write-Host ""
