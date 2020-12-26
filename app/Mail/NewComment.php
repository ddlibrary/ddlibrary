<?php

namespace App\Mail;

use App\ResourceComment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewComment extends Mailable
{
    use Queueable, SerializesModels;

    Public $comment;

    /**
     * Create a new message instance.
     *
     * @param ResourceComment $comment
     */
    public function __construct(ResourceComment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('comments.email');
    }
}
