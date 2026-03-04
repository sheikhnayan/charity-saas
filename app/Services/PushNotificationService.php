<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserNotificationToken;
use App\Models\PushNotification;
use App\Models\NotificationPreference;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PushNotificationService
{
    protected $fcmServerKey;
    protected $projectId;
    protected $serviceAccountPath;

    public function __construct()
    {
        // FCM Server Key - should be in .env file
        $this->fcmServerKey = env('FCM_SERVER_KEY', 'YOUR_FCM_SERVER_KEY');
        $this->projectId = env('FIREBASE_PROJECT_ID', 'charity-390ca');
        $this->serviceAccountPath = base_path('firebase-service-account.json');
    }

    /**
     * Send push notification to a user
     */
    public function sendToUser(int $userId, string $title, string $body, array $data = [], string $type = 'general'): bool
    {
        try {
            $user = User::find($userId);
            if (!$user) {
                Log::warning("User not found: {$userId}");
                return false;
            }
            // Check user preferences
            if (!$this->shouldSendNotification($userId, $type)) {
                Log::info("Notification skipped due to user preferences: User {$userId}, Type {$type}");
                return false;
            }

            // Get all active tokens for the user
            $tokens = UserNotificationToken::where('user_id', $userId)
                ->active()
                ->get();

            // dd($tokens);

            if ($tokens->isEmpty()) {
                Log::info("No active tokens for user: {$userId}");
                return false;
            }

            // Log notification to database
            $notification = PushNotification::create([
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
                'body' => $body,
                'data' => $data,
                'status' => 'pending'
            ]);

            // Send to all devices
            $sentCount = 0;
            $errors = [];

            foreach ($tokens as $tokenRecord) {
                try {
                    $result = $this->sendFCMNotification(
                        $tokenRecord->token,
                        $title,
                        $body,
                        array_merge($data, [
                            'type' => $type,
                            'notification_id' => $notification->id
                        ])
                    );

                    if ($result) {
                        $sentCount++;
                        $tokenRecord->update(['last_used_at' => now()]);
                    } else {
                        $errors[] = "Failed to send to token ending in: " . substr($tokenRecord->token, -10);
                    }

                } catch (\Exception $e) {
                    Log::error("FCM send error: " . $e->getMessage());
                    $errors[] = $e->getMessage();
                    
                    // If token is invalid, deactivate it
                    if (str_contains($e->getMessage(), 'invalid') || str_contains($e->getMessage(), 'not registered')) {
                        $tokenRecord->update(['is_active' => false]);
                    }
                }
            }

            // Update notification status
            if ($sentCount > 0) {
                $notification->markAsSent();
                return true;
            } else {
                $notification->markAsFailed(implode('; ', $errors));
                return false;
            }

        } catch (\Exception $e) {
            Log::error("Push notification error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send push notification to multiple users
     */
    public function sendToUsers(array $userIds, string $title, string $body, array $data = [], string $type = 'general'): array
    {
        $results = [];
        
        foreach ($userIds as $userId) {
            $results[$userId] = $this->sendToUser($userId, $title, $body, $data, $type);
        }

        return $results;
    }

    /**
     * Send notification via Firebase Cloud Messaging HTTP v1 API
     */
    protected function sendFCMNotification(string $token, string $title, string $body, array $data = []): bool
    {
        try {
            $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";
            
            // Convert all data values to strings (v1 API requirement)
            $stringData = [];
            foreach ($data as $key => $value) {
                $stringData[$key] = (string) $value;
            }
            
            $message = [
                'message' => [
                    'token' => $token,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'webpush' => [
                        'notification' => [
                            'icon' => url('/images/icon-192x192.png'),
                            'badge' => url('/images/icon-72x72.png'),
                            'requireInteraction' => true,
                        ],
                        'fcm_options' => [
                            'link' => $data['url'] ?? url('/'),
                        ],
                    ],
                    'data' => $stringData,
                ]
            ];

            $accessToken = $this->getAccessToken();
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->post($url, $message);

            // dd($response->body());

            if ($response->successful()) {
                Log::info("FCM v1 notification sent successfully", [
                    'token_suffix' => substr($token, -10),
                    'title' => $title
                ]);
                return true;
            }

            Log::error("FCM v1 API Error", [
                'status' => $response->status(),
                'body' => $response->body(),
                'token_suffix' => substr($token, -10)
            ]);
            return false;

        } catch (\Exception $e) {
            Log::error("FCM v1 Request Exception", [
                'message' => $e->getMessage(),
                'token_suffix' => substr($token, -10)
            ]);
            return false;
        }
    }

    /**
     * Get OAuth 2.0 access token from Service Account
     */
    protected function getAccessToken(): string
    {
        try {
            // Check if service account file exists
            if (!file_exists($this->serviceAccountPath)) {
                throw new \Exception("Service account file not found: {$this->serviceAccountPath}");
            }

            $serviceAccount = json_decode(file_get_contents($this->serviceAccountPath), true);
            
            // Create JWT
            $now = time();
            $jwtHeader = base64_encode(json_encode([
                'alg' => 'RS256',
                'typ' => 'JWT'
            ]));
            
            $jwtClaim = base64_encode(json_encode([
                'iss' => $serviceAccount['client_email'],
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                'aud' => 'https://oauth2.googleapis.com/token',
                'iat' => $now,
                'exp' => $now + 3600
            ]));
            
            $jwtSignature = '';
            $dataToSign = $jwtHeader . '.' . $jwtClaim;
            
            openssl_sign(
                $dataToSign,
                $jwtSignature,
                $serviceAccount['private_key'],
                OPENSSL_ALGO_SHA256
            );
            
            $jwt = $dataToSign . '.' . base64_encode($jwtSignature);
            
            // Exchange JWT for access token
            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt
            ]);
            
            if ($response->successful()) {
                $result = $response->json();
                return $result['access_token'];
            }
            
            throw new \Exception("Failed to get access token: " . $response->body());
            
        } catch (\Exception $e) {
            Log::error("OAuth token generation failed: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Check if notification should be sent based on user preferences
     */
    protected function shouldSendNotification(int $userId, string $type): bool
    {
        $preferences = NotificationPreference::firstOrCreate(
            ['user_id' => $userId],
            NotificationPreference::defaults()
        );

        // Check if notification type is enabled
        if (!$preferences->isEnabled($type)) {
            return false;
        }

        // Check quiet hours
        if ($preferences->isInQuietHours()) {
            return false;
        }

        // Check frequency (for future implementation of batching)
        // For now, we only support 'instant'

        return true;
    }

    /**
     * Register or update FCM token for a user
     */
    public function registerToken(int $userId, string $token, string $deviceType = 'web', ?string $browser = null): UserNotificationToken
    {
        $tokenHash = UserNotificationToken::hashToken($token);
        $websiteId = $this->getCurrentWebsiteId();

        return UserNotificationToken::updateOrCreate(
            ['token_hash' => $tokenHash],
            [
                'user_id' => $userId,
                'website_id' => $websiteId,
                'token' => $token,
                'device_type' => $deviceType,
                'browser' => $browser,
                'is_active' => true,
                'last_used_at' => now()
            ]
        );
    }

    /**
     * Get user's registered tokens/devices
     */
    public function getUserTokens(int $userId, ?int $websiteId = null)
    {
        // If website_id is explicitly null, get ALL devices for the user
        // Otherwise filter by website_id
        $query = UserNotificationToken::where('user_id', $userId)
            ->where('is_active', true);
        
        if ($websiteId !== null) {
            $websiteId = $websiteId ?? $this->getCurrentWebsiteId();
            $query->where('website_id', $websiteId);
        }
        
        return $query->orderBy('last_used_at', 'desc')->get();
    }

    /**
     * Delete/unregister a token
     */
    public function deleteToken(string $token): bool
    {
        $tokenHash = UserNotificationToken::hashToken($token);
        
        return UserNotificationToken::where('token_hash', $tokenHash)
            ->delete() > 0;
    }

    /**
     * Get user's notification history
     */
    public function getUserNotifications(int $userId, int $limit = 50)
    {
        return PushNotification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(int $notificationId): bool
    {
        $notification = PushNotification::find($notificationId);
        
        if ($notification) {
            $notification->markAsRead();
            return true;
        }

        return false;
    }

    /**
     * Get unread notification count for user
     */
    public function getUnreadCount(int $userId): int
    {
        return PushNotification::where('user_id', $userId)
            ->unread()
            ->count();
    }

    // ============================================
    // SPECIFIC NOTIFICATION TYPES
    // ============================================

    /**
     * Send donation notification
     */
    public function sendDonationNotification(int $userId, float $amount, string $donorName, int $donationId): bool
    {
        $title = '💰 New Donation Received!';
        $body = "{$donorName} donated $" . number_format($amount, 2);
        $data = [
            'donation_id' => $donationId,
            'amount' => $amount,
            'donor_name' => $donorName,
            'url' => url("/admin/transactions")
        ];

        return $this->sendToUser($userId, $title, $body, $data, 'donation');
    }

    /**
     * Send auction outbid notification
     */
    public function sendAuctionOutbidNotification(int $userId, string $auctionTitle, float $newBid, int $auctionId): bool
    {
        $title = '🔨 You\'ve been outbid!';
        $body = "Someone bid $" . number_format($newBid, 2) . " on \"{$auctionTitle}\"";
        $data = [
            'auction_id' => $auctionId,
            'new_bid' => $newBid,
            'auction_title' => $auctionTitle,
            'url' => url("/auction/{$auctionId}"),
            'requireInteraction' => 'true'
        ];

        return $this->sendToUser($userId, $title, $body, $data, 'auction_outbid');
    }

    /**
     * Send auction won notification
     */
    public function sendAuctionWonNotification(int $userId, string $auctionTitle, float $winningBid, int $auctionId): bool
    {
        $title = '🎉 You won the auction!';
        $body = "Congratulations! You won \"{$auctionTitle}\" for $" . number_format($winningBid, 2);
        $data = [
            'auction_id' => $auctionId,
            'winning_bid' => $winningBid,
            'auction_title' => $auctionTitle,
            'url' => url("/auction/{$auctionId}")
        ];

        return $this->sendToUser($userId, $title, $body, $data, 'auction_won');
    }

    /**
     * Send goal reached notification
     */
    public function sendGoalReachedNotification(int $userId, string $campaignName, float $goalAmount, int $campaignId = null): bool
    {
        $title = '🎯 Campaign Goal Reached!';
        $body = "\"{$campaignName}\" has reached its goal of $" . number_format($goalAmount, 2) . "!";
        $data = [
            'campaign_id' => $campaignId,
            'goal_amount' => $goalAmount,
            'campaign_name' => $campaignName,
            'url' => $campaignId ? url("/campaign/{$campaignId}") : url("/admin")
        ];

        return $this->sendToUser($userId, $title, $body, $data, 'goal_reached');
    }

    /**
     * Send campaign update notification
     */
    public function sendCampaignUpdateNotification(int $userId, string $campaignName, string $updateMessage, int $campaignId = null): bool
    {
        $title = '📢 Campaign Update';
        $body = "{$campaignName}: {$updateMessage}";
        $data = [
            'campaign_id' => $campaignId,
            'campaign_name' => $campaignName,
            'url' => $campaignId ? url("/campaign/{$campaignId}") : url("/admin")
        ];

        return $this->sendToUser($userId, $title, $body, $data, 'campaign_update');
    }

    /**
     * Send investment milestone notification
     */
    public function sendInvestmentMilestoneNotification(int $userId, string $milestoneName, float $totalRaised, int $investmentId = null): bool
    {
        $title = '📈 Investment Milestone Reached!';
        $body = "{$milestoneName}: $" . number_format($totalRaised, 2) . " raised!";
        $data = [
            'investment_id' => $investmentId,
            'milestone' => $milestoneName,
            'total_raised' => $totalRaised,
            'url' => url("/admin/investments")
        ];

        return $this->sendToUser($userId, $title, $body, $data, 'investment_milestone');
    }

    /**
     * Send ticket purchase notification
     */
    public function sendTicketPurchaseNotification(int $userId, string $eventName, int $quantity, int $ticketId = null): bool
    {
        $title = '🎫 Ticket Purchase Confirmed';
        $body = "You purchased {$quantity} ticket(s) for \"{$eventName}\"";
        $data = [
            'ticket_id' => $ticketId,
            'event_name' => $eventName,
            'quantity' => $quantity,
            'url' => url("/admin/tickets")
        ];

        return $this->sendToUser($userId, $title, $body, $data, 'ticket_purchased');
    }

    // ============================================
    // PREFERENCE MANAGEMENT
    // ============================================

    /**
     * Get current website ID
     */
    protected function getCurrentWebsiteId(): ?int
    {
        // Try to get from session or default website
        return session('website_id') ?? \App\Models\Website::first()?->id;
    }

    /**
     * Get user notification preferences
     */
    public function getPreferences(int $userId, ?int $websiteId = null)
    {
        $websiteId = $websiteId ?? $this->getCurrentWebsiteId();
        
        $preference = NotificationPreference::firstOrCreate(
            ['user_id' => $userId, 'website_id' => $websiteId],
            array_merge(NotificationPreference::defaults(), ['website_id' => $websiteId])
        );

        return $preference;
    }

    /**
     * Update user notification preferences
     */
    public function updatePreferences(int $userId, array $settings, ?int $websiteId = null)
    {
        $websiteId = $websiteId ?? $this->getCurrentWebsiteId();
        
        $preference = NotificationPreference::firstOrCreate(
            ['user_id' => $userId, 'website_id' => $websiteId],
            array_merge(NotificationPreference::defaults(), ['website_id' => $websiteId])
        );

        $preference->update($settings);

        return $preference;
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead(int $userId): int
    {
        return PushNotification::where('user_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }
}
