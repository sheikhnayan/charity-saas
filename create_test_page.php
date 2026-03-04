<?php
require __DIR__ . '/vendor/autoload.php';

// Load Laravel app
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Page;

// Create a test page with parallax background
$page = new Page();
$page->name = 'Parallax Test Page';
$page->state = json_encode([
    [
        'type' => 'inner-section',
        'innerSectionData' => [
            'fullWidth' => true,
            'backgroundType' => 'image',
            'backgroundImage' => 'https://images.unsplash.com/photo-1611224923853-80b023f02d71?w=1200',
            'backgroundAttachment' => 'fixed',
            'padding' => '4rem 2rem',
            'columns' => 1
        ],
        'nestedComponents' => [
            [
                [
                    'type' => 'text',
                    'html' => '<div style="text-align: center; color: white;"><h2>Parallax Test Section</h2><p>This section should have a fixed parallax background.</p><p>Scroll up and down to see the effect!</p></div>'
                ]
            ]
        ]
    ],
    [
        'type' => 'text',
        'html' => '<div style="padding: 3rem; text-align: center; background: #f8f9fa;"><h3>Regular Content Section</h3><p>This is regular content to enable scrolling and test the parallax effect above.</p></div>'
    ],
    [
        'type' => 'inner-section',
        'innerSectionData' => [
            'fullWidth' => true,
            'backgroundType' => 'image',
            'backgroundImage' => 'https://images.unsplash.com/photo-1559136555-9303baea8ebd?w=1200',
            'backgroundAttachment' => 'fixed',
            'padding' => '4rem 2rem',
            'columns' => 1
        ],
        'nestedComponents' => [
            [
                [
                    'type' => 'text',
                    'html' => '<div style="text-align: center; color: white;"><h2>Second Parallax Section</h2><p>Another parallax background to test multiple sections.</p></div>'
                ]
            ]
        ]
    ]
]);

$page->save();

echo "Created test page with ID: " . $page->id . "\n";
echo "Access it at: http://127.0.0.1/charity/page/" . $page->id . "\n";
?>