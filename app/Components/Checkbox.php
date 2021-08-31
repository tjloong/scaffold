<?php

namespace Jiannius\Scaffold\Components;

use Illuminate\View\Component;

class Checkbox extends Component
{
    public $color;
    public $checked;

    /**
     * Create the component instance.
     *
     * @param string $color
     * @param boolean $checked
     * @return void
     */
    public function __construct($color = 'blue', $checked = false)
    {
        $this->color = $color;
        $this->checked = $checked;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('scaffold-component::checkbox');
    }
}