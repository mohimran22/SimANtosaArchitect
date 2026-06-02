<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CollapseCard extends Component
{
    public $title;
    public $target;

    public function __construct(
        $title = '',
        $target = ''
    ) {
        $this->title = $title;
        $this->target = $target;
    }

    public function render()
    {
        return view('components.collapse-card');
    }
}