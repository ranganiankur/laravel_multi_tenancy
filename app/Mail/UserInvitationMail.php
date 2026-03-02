<?php
 
namespace App\Mail;
 
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;
 
class UserInvitationMail extends Mailable
{
    use Queueable, SerializesModels;
 
    public $invitation;
    public $inviter;
    public $frontendUrl;
 
    public function __construct($invitation, $inviter,$frontendUrl)
    {
        $this->invitation = $invitation;
        $this->inviter = $inviter;
        $this->frontendUrl = $frontendUrl;
    }
 
    public function build()
    {
        return $this->subject('You Are Invited!')
                    ->view('emails.user_invitation');
    }
}
 
 