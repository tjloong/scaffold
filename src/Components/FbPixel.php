<?php

namespace Jiannius\Scaffold\Components;

use Illuminate\View\Component;

class FbPixel extends Component
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
        $this->config = (object)config('scaffold.web.fbpixel');
        $this->noscript = $noscript;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        if ($this->config->id && env('APP_ENV') === 'production') {
            return view('scaffold-component::fb-pixel');
        }
    }
}