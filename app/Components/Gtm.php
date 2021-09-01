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
        $route = request()->route()->getName() ?? request()->path();

        if (!$this->config->id || env('APP_ENV') !== 'production' || $this->isRouteExcluded($route)) return;

        return view('scaffold-component::gtm');
    }

    /**
     * Check current route is excluded from rendering
     * 
     * @param string $route
     * @return boolean
     */
    public function isRouteExcluded($route)
    {
        return in_array($route, $this->config->exclude_routes);
    }
}