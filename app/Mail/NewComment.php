<?php

namespace App\Mail;

use App\Models\ResourceComment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewComment extends Mailable
{
    use Queueable, SerializesModels;

    public $comment;

    /**
     * Create a new message instance.
     */
    public function __construct(ResourceComment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Build the message.
     */
    public function build(): static
    {
        return $this->view('comments.email');
    }
}
