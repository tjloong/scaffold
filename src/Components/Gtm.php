<?php

namespace Jiannius\Scaffold\Components;

use Illuminate\View\Component;

class Gtm extends Component
{
    public $config;
    public $noscript;

    /**
     * Create the component instance.
     *
     * @param boolean $noscript
     * @return void
     */
    public function __construct($noscript = false)
    {
        $this->noscript = $noscript;
        $this->config = (object)config('scaffold.web.gtm');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        if ($this->config->id && env('APP_ENV') === 'production') {
            return view('scaffold-component::gtm');
        }
    }
}