<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Website;

class AdminRegistrationNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $newUser;
    public $website;

    /**
     * Create a new message instance.
     */
    public function __construct(User $newUser, Website $website)
    {
        $this->newUser = $newUser;
        $this->website = $website;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        // Apply website-specific email settings
        if ($this->website) {
            \App\Services\WebsiteMailService::applyForWebsite($this->website);
        }

        $subject = 'New Parent/Guardian Registration - Approval Required | ' . $this->website->name;

        $message = $this->subject($subject)
                    ->view('emails.admin-registration-notification')
                    ->with([
                        'newUser' => $this->newUser,
                        'website' => $this->website,
                        'userRole' => ucfirst($this->newUser->role),
                        'registrationDate' => $this->newUser->created_at->format('M d, Y \a\t g:i A'),
                        'approvalLink' => url('/admins/student')
                    ]);

        // Apply from address from website settings if available
        if ($this->website && $this->website->emailSettings && $this->website->emailSettings->from_address) {
            $message->from(
                $this->website->emailSettings->from_address,
                $this->website->emailSettings->from_name ?? $this->website->name
            );
        } else if ($this->website) {
            $message->from(config('mail.from.address', 'noreply@' . $this->website->domain), $this->website->name);
        }

        // Apply reply-to if configured
        if (config('mail.reply_to.address')) {
            $message->replyTo(config('mail.reply_to.address'), config('mail.reply_to.name'));
        }

        return $message;
    }
}
