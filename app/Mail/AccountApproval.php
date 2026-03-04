<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Website;

class AccountApproval extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $website;
    public $loginUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Website $website)
    {
        $this->user = $user;
        $this->website = $website;
        $this->loginUrl = 'http://' . $website->domain . '/login';
    }

    /**
     * Build the message.
     */
    public function build()
    {
        // Apply website-specific email settings
        \App\Services\WebsiteMailService::applyForWebsite($this->website);

        $message = $this->subject('Your Account Has Been Approved')
                    ->view('emails.account-approval')
                    ->with([
                        'userName' => $this->user->name,
                        'userEmail' => $this->user->email,
                        'websiteName' => $this->website->name,
                        'websiteDomain' => $this->website->domain,
                        'loginUrl' => $this->loginUrl,
                    ]);

        // Apply from address from website settings if available
        if ($this->website && $this->website->emailSettings && $this->website->emailSettings->from_address) {
            $message->from(
                $this->website->emailSettings->from_address,
                $this->website->emailSettings->from_name ?? $this->website->name
            );
        }

        return $message;
    }
}
