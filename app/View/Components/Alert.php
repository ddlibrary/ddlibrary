<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Alert extends Component
{
    /**
     * The priority of the alert, i.e., "info", or "warning"
     *
     * @var string
     */
    public $level;

    /**
     * The message or an array of messages to present to the user
     *
     * @var mixed
     */
    public $message;

    /**
     * Create a new component instance.
     *
     * @param  mixed  $message
     */
    public function __construct(string $level = 'success', $message = '')
    {
        $this->level = $level;
        $this->message = $message;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.alert');
    }
}
