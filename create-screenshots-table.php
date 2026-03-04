<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

DB::statement('DROP TABLE IF EXISTS page_screenshots');

DB::statement("CREATE TABLE page_screenshots (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    website_id BIGINT UNSIGNED NOT NULL,
    page_url VARCHAR(500),
    page_path VARCHAR(191),
    screenshot_path VARCHAR(500),
    viewport_width INT DEFAULT 1920,
    viewport_height INT DEFAULT 1080,
    device_type VARCHAR(20) DEFAULT 'desktop',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX(website_id),
    FOREIGN KEY (website_id) REFERENCES websites(id) ON DELETE CASCADE
)");

echo "page_screenshots table created successfully!\n";
