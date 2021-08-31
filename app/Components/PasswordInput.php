<?php

namespace Jiannius\Scaffold\Components;

use Illuminate\View\Component;

class PasswordInput extends Component
{
    /**
     * Create the component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('scaffold-component::password-input');
    }
}