# Create improved package structure
Write-Host "Creating SuperAuth package structure..." -ForegroundColor Green

# Create feature directories
$features = @("Authentication", "Authorization", "Security", "Notifications", "AI")
$subdirs = @("Controllers", "Services", "Models", "Livewire", "Requests", "Middleware")

foreach ($feature in $features) {
    Write-Host "Creating $feature feature directories..." -ForegroundColor Yellow
    foreach ($subdir in $subdirs) {
        $path = "src\Features\$feature\$subdir"
        if (!(Test-Path $path)) {
            New-Item -ItemType Directory -Path $path -Force | Out-Null
            Write-Host "  Created: $path" -ForegroundColor Cyan
        }
    }
}

# Create shared directories
Write-Host "Creating shared directories..." -ForegroundColor Yellow
$sharedDirs = @(
    "src\Core\Contracts",
    "src\Core\Traits", 
    "src\Core\Helpers",
    "src\Core\Constants",
    "src\Shared\Components",
    "src\Shared\Layouts",
    "src\Shared\Middleware",
    "src\Shared\Traits",
    "resources\views\features\authentication",
    "resources\views\features\authorization", 
    "resources\views\features\security",
    "resources\views\features\notifications",
    "resources\views\features\ai",
    "resources\views\shared\layouts",
    "resources\views\shared\components",
    "resources\views\shared\partials",
    "resources\assets\css",
    "resources\assets\js",
    "resources\assets\images",
    "resources\lang\en",
    "routes\features",
    "routes\shared",
    "database\migrations\features\authentication",
    "database\migrations\features\authorization",
    "database\migrations\features\security", 
    "database\migrations\features\notifications",
    "database\migrations\features\ai",
    "database\migrations\shared",
    "database\seeders\features",
    "database\seeders\shared",
    "database\factories\features",
    "database\factories\shared",
    "config\features",
    "tests\Feature\Authentication",
    "tests\Feature\Authorization",
    "tests\Feature\Security", 
    "tests\Feature\Notifications",
    "tests\Feature\AI",
    "tests\Unit\Models",
    "tests\Unit\Services",
    "tests\Unit\Helpers",
    "tests\Integration",
    "docs\features",
    "docs\api",
    "docs\guides"
)

foreach ($dir in $sharedDirs) {
    if (!(Test-Path $dir)) {
        New-Item -ItemType Directory -Path $dir -Force | Out-Null
        Write-Host "  Created: $dir" -ForegroundColor Cyan
    }
}

Write-Host "Package structure created successfully!" -ForegroundColor Green
Write-Host "Total directories created: $($sharedDirs.Count + ($features.Count * $subdirs.Count))" -ForegroundColor Magenta
