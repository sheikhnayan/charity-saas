<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SessionRecording;
use App\Models\SessionEvent;
use App\Models\Website;
use Carbon\Carbon;

class SessionRecordingSeeder extends Seeder
{
    /**
     * Seed the database with sample session recordings.
     */
    public function run(): void
    {
        // Get the first website or create a default one
        $website = Website::first();
        
        if (!$website) {
            echo "No websites found. Please create a website first.\n";
            return;
        }

        $pages = [
            ['url' => '/donate', 'title' => 'Donate Now'],
            ['url' => '/causes', 'title' => 'Our Causes'],
            ['url' => '/about', 'title' => 'About Us'],
            ['url' => '/events', 'title' => 'Upcoming Events'],
            ['url' => '/volunteer', 'title' => 'Volunteer Opportunities'],
            ['url' => '/contact', 'title' => 'Contact Us'],
        ];

        $devices = ['desktop', 'mobile', 'tablet'];
        $browsers = ['Chrome', 'Firefox', 'Safari', 'Edge'];
        $countries = [
            ['country' => 'United States', 'code' => 'US', 'state' => 'California', 'city' => 'San Francisco'],
            ['country' => 'United Kingdom', 'code' => 'GB', 'state' => 'England', 'city' => 'London'],
            ['country' => 'Canada', 'code' => 'CA', 'state' => 'Ontario', 'city' => 'Toronto'],
            ['country' => 'Australia', 'code' => 'AU', 'state' => 'New South Wales', 'city' => 'Sydney'],
            ['country' => 'Germany', 'code' => 'DE', 'state' => 'Bavaria', 'city' => 'Munich'],
        ];

        echo "Seeding session recordings for website: {$website->name}\n";

        // Create 50 sample recordings
        for ($i = 1; $i <= 50; $i++) {
            $page = $pages[array_rand($pages)];
            $country = $countries[array_rand($countries)];
            $hasRageClicks = rand(1, 10) > 7; // 30% chance
            $hasErrors = rand(1, 10) > 8; // 20% chance
            $isStarred = rand(1, 10) > 9; // 10% chance
            
            $startedAt = Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 23));
            $durationMs = rand(5000, 300000); // 5s to 5 minutes
            $endedAt = (clone $startedAt)->addMilliseconds($durationMs);

            $recording = SessionRecording::create([
                'website_id' => $website->id,
                'session_id' => 'sess_' . uniqid() . '_' . $i,
                'visitor_id' => 'visitor_' . rand(1000, 9999),
                'user_id' => rand(1, 10) > 7 ? rand(1, 100) : null, // 30% authenticated
                'url' => $website->url . $page['url'],
                'page_title' => $page['title'],
                'duration_ms' => $durationMs,
                'viewport_width' => rand(1, 2) == 1 ? 1920 : 1366,
                'viewport_height' => rand(1, 2) == 1 ? 1080 : 768,
                'device_type' => $devices[array_rand($devices)],
                'browser' => $browsers[array_rand($browsers)],
                'os' => rand(1, 2) == 1 ? 'Windows' : 'macOS',
                'ip_address' => rand(1, 255) . '.' . rand(1, 255) . '.' . rand(1, 255) . '.' . rand(1, 255),
                'country' => $country['country'],
                'country_code' => $country['code'],
                'state' => $country['state'],
                'city' => $country['city'],
                'status' => 'completed',
                'has_rage_clicks' => $hasRageClicks,
                'has_errors' => $hasErrors,
                'event_count' => rand(10, 500),
                'is_starred' => $isStarred,
                'notes' => $isStarred ? 'Important recording with unusual behavior' : null,
                'tags' => $hasRageClicks || $hasErrors ? json_encode(['needs-review']) : null,
                'started_at' => $startedAt,
                'ended_at' => $endedAt,
                'created_at' => $startedAt,
                'updated_at' => $endedAt,
            ]);

            // Create sample rrweb events for this recording
            $this->createSampleEvents($recording, $durationMs);
        }

        echo "Successfully seeded 50 session recordings with events.\n";
    }

    /**
     * Create sample rrweb-compatible events for a recording
     * Based on actual rrweb event structure
     */
    private function createSampleEvents(SessionRecording $recording, int $durationMs): void
    {
        $events = [];
        
        // Event 1: DomContentLoaded
        $events[] = [
            'session_recording_id' => $recording->id,
            'timestamp' => 0,
            'event_type' => 0, // DomContentLoaded
            'data' => json_encode([
                'href' => $recording->url,
                'width' => $recording->viewport_width,
                'height' => $recording->viewport_height
            ]),
            'action' => 'dom_content_loaded',
            'target_element' => null,
            'x' => null,
            'y' => null,
            'scroll_x' => null,
            'scroll_y' => null,
            'created_at' => $recording->started_at,
        ];
        
        // Event 2: Full snapshot (initial page state)
        $events[] = [
            'session_recording_id' => $recording->id,
            'timestamp' => 50,
            'event_type' => 2, // FULL_SNAPSHOT
            'data' => json_encode([
                'node' => [
                    'type' => 0,
                    'childNodes' => [
                        [
                            'type' => 1,
                            'name' => 'html',
                            'publicId' => '',
                            'systemId' => '',
                            'id' => 2
                        ],
                        [
                            'type' => 2,
                            'tagName' => 'html',
                            'attributes' => ['lang' => 'en'],
                            'childNodes' => [
                                [
                                    'type' => 2,
                                    'tagName' => 'head',
                                    'attributes' => [],
                                    'childNodes' => [
                                        [
                                            'type' => 2,
                                            'tagName' => 'title',
                                            'attributes' => [],
                                            'childNodes' => [
                                                [
                                                    'type' => 3,
                                                    'textContent' => $recording->page_title,
                                                    'id' => 5
                                                ]
                                            ],
                                            'id' => 4
                                        ]
                                    ],
                                    'id' => 3
                                ],
                                [
                                    'type' => 2,
                                    'tagName' => 'body',
                                    'attributes' => [],
                                    'childNodes' => [
                                        [
                                            'type' => 2,
                                            'tagName' => 'div',
                                            'attributes' => ['class' => 'container'],
                                            'childNodes' => [
                                                [
                                                    'type' => 2,
                                                    'tagName' => 'h1',
                                                    'attributes' => [],
                                                    'childNodes' => [
                                                        [
                                                            'type' => 3,
                                                            'textContent' => $recording->page_title,
                                                            'id' => 9
                                                        ]
                                                    ],
                                                    'id' => 8
                                                ],
                                                [
                                                    'type' => 2,
                                                    'tagName' => 'button',
                                                    'attributes' => ['class' => 'btn-donate'],
                                                    'childNodes' => [
                                                        [
                                                            'type' => 3,
                                                            'textContent' => 'Donate Now',
                                                            'id' => 11
                                                        ]
                                                    ],
                                                    'id' => 10
                                                ]
                                            ],
                                            'id' => 7
                                        ]
                                    ],
                                    'id' => 6
                                ]
                            ],
                            'id' => 1
                        ]
                    ],
                    'id' => 0
                ],
                'initialOffset' => [
                    'top' => 0,
                    'left' => 0
                ]
            ]),
            'action' => 'full_snapshot',
            'target_element' => null,
            'x' => null,
            'y' => null,
            'scroll_x' => null,
            'scroll_y' => null,
            'created_at' => $recording->started_at,
        ];

        // Event 2: Mouse move
        $events[] = [
            'session_recording_id' => $recording->id,
            'timestamp' => 1000,
            'event_type' => 3, // INCREMENTAL_SNAPSHOT
            'data' => json_encode([
                'source' => 1, // MouseMove
                'positions' => [
                    ['x' => 100, 'y' => 150, 'timeOffset' => 0],
                    ['x' => 120, 'y' => 180, 'timeOffset' => 50],
                    ['x' => 150, 'y' => 200, 'timeOffset' => 100],
                ]
            ]),
            'action' => 'mouse_move',
            'target_element' => null,
            'x' => 150,
            'y' => 200,
            'scroll_x' => null,
            'scroll_y' => null,
            'created_at' => $recording->started_at,
        ];

        // Event 3: Click
        $events[] = [
            'session_recording_id' => $recording->id,
            'timestamp' => 2500,
            'event_type' => 3,
            'data' => json_encode([
                'source' => 2, // MouseInteraction
                'type' => 2, // Click
                'id' => 10, // Button element
                'x' => 200,
                'y' => 300
            ]),
            'action' => 'click',
            'target_element' => '.btn-donate',
            'x' => 200,
            'y' => 300,
            'scroll_x' => null,
            'scroll_y' => null,
            'created_at' => $recording->started_at,
        ];

        // Event 4: Scroll
        $events[] = [
            'session_recording_id' => $recording->id,
            'timestamp' => 4000,
            'event_type' => 3,
            'data' => json_encode([
                'source' => 3, // Scroll
                'id' => 6, // Body element
                'x' => 0,
                'y' => 500
            ]),
            'action' => 'scroll',
            'target_element' => 'body',
            'x' => null,
            'y' => null,
            'scroll_x' => 0,
            'scroll_y' => 500,
            'created_at' => $recording->started_at,
        ];

        // Add more mouse movements throughout the session
        $numEvents = rand(10, 30);
        for ($j = 0; $j < $numEvents; $j++) {
            $timestamp = rand(5000, $durationMs - 1000);
            $events[] = [
                'session_recording_id' => $recording->id,
                'timestamp' => $timestamp,
                'event_type' => 3,
                'data' => json_encode([
                    'source' => 1,
                    'positions' => [
                        ['x' => rand(0, 1920), 'y' => rand(0, 1080), 'timeOffset' => 0],
                    ]
                ]),
                'action' => 'mouse_move',
                'target_element' => null,
                'x' => rand(0, 1920),
                'y' => rand(0, 1080),
                'scroll_x' => null,
                'scroll_y' => null,
                'created_at' => $recording->started_at,
            ];
        }

        // Batch insert events
        SessionEvent::insert($events);
    }
}
