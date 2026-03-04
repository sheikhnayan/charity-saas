<?php

namespace App\Services;

use App\Models\Website;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;

class WebsiteMailService
{
    /**
     * Apply website-specific mail configuration to runtime
     */
    public static function applyForWebsite(Website $website): void
    {
        $settings = $website->emailSettings;
        if (!$settings || !$settings->is_active) {
            // Fallback from global .env configuration; still ensure from fields
            Config::set('mail.from.address', Config::get('mail.from.address', 'noreply@' . $website->domain));
            Config::set('mail.from.name', Config::get('mail.from.name', $website->name));
            return;
        }

        $mail = $settings->getMailConfig();

        // Base mailer (we stick to smtp)
        Config::set('mail.default', $mail['mailer'] ?: 'smtp');

        // Apply SMTP credentials
        if (!empty($mail['host'])) Config::set('mail.mailers.smtp.host', $mail['host']);
        if (!empty($mail['port'])) Config::set('mail.mailers.smtp.port', $mail['port']);
        Config::set('mail.mailers.smtp.encryption', $mail['encryption']);
        Config::set('mail.mailers.smtp.username', $mail['username']);
        Config::set('mail.mailers.smtp.password', $mail['password']);

        // From address/name
        if (!empty($mail['from']['address'])) {
            Config::set('mail.from.address', $mail['from']['address']);
        } else {
            Config::set('mail.from.address', 'noreply@' . $website->domain);
        }
        Config::set('mail.from.name', !empty($mail['from']['name']) ? $mail['from']['name'] : $website->name);

        // Some mail drivers respect replyTo via message object; store in config for retrieval
        if (!empty($mail['reply_to']['address'])) {
            Config::set('mail.reply_to.address', $mail['reply_to']['address']);
            Config::set('mail.reply_to.name', $mail['reply_to']['name'] ?? null);
        } else {
            Config::set('mail.reply_to.address', null);
            Config::set('mail.reply_to.name', null);
        }
    }

    /**
     * Detect website for a user based on website_id or request domain
     * Returns Website instance or null to fallback to config('mail')
     */
    public static function detectWebsiteForUser(?User $user): ?Website
    {
        // If user has a website_id, use that
        if ($user && $user->website_id) {
            return Website::find($user->website_id);
        }

        // Try to detect from request domain
        try {
            $domain = Request::getHost();
            return Website::where('domain', $domain)->first();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Send mail with website-specific configuration for a user
     */
    public static function sendForUser(
        $user,
        $view,
        array $data = [],
        ?\Closure $callback = null
    ): void
    {
        $website = self::detectWebsiteForUser($user);
        
        if ($website) {
            self::applyForWebsite($website);
        }

        Mail::send($view, $data, $callback);
    }

    /**
     * Send mail with website-specific configuration for a website ID
     */
    public static function sendForWebsite(
        $websiteId,
        $view,
        array $data = [],
        ?\Closure $callback = null
    ): void
    {
        $website = Website::find($websiteId);
        
        if ($website) {
            self::applyForWebsite($website);
        }

        Mail::send($view, $data, $callback);
    }

    /**
     * Send mail with website-specific configuration
     * Detects website from user or website_id parameter
     */
    public static function send(
        $view,
        array $data = [],
        $userOrWebsiteId = null,
        ?\Closure $callback = null
    ): void
    {
        // Determine website context
        if ($userOrWebsiteId instanceof User) {
            $website = self::detectWebsiteForUser($userOrWebsiteId);
        } elseif (is_int($userOrWebsiteId) || is_string($userOrWebsiteId)) {
            $website = Website::find($userOrWebsiteId);
        } else {
            $website = null;
        }

        if ($website) {
            self::applyForWebsite($website);
        }

        Mail::send($view, $data, $callback);
    }
}
