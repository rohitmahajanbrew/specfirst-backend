<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otpData;

    /**
     * Create a new message instance.
     */
    public function __construct($otpData)
    {
        $this->otpData = $otpData;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = 'Your OTP Code - ' . ucfirst($this->otpData['purpose']);
        
        return $this->subject($subject)
                    ->view('emails.otp')
                    ->with('otpData', $this->otpData);
    }
}
