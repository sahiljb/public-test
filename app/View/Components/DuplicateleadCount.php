<?php

namespace App\View\Components;

use App\Models\DuplicateLead;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DuplicateleadCount extends Component
{
    /**
     * Create a new component instance.
     */

    public $count;
    public function __construct()
    {
        $this->count = DuplicateLead::count();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.duplicatelead-count');
    }
}
