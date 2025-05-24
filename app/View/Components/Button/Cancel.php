<?php

namespace App\View\Components\Button;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Cancel extends Component
{
    public $icon;

    public function __construct($icon = null)
    {
        $this->icon = $icon;
    }

    public function render(): View|Closure|string
    {
        return view('components.button.cancel');
    }
}
