<?php

namespace Jiannius\Scaffold\Components;

use Illuminate\View\Component;

class Metatags extends Component
{
    public $config;

    /**
     * Create the component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->config = (object)config('scaffold.view.metatags');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        $route = request()->route()->getName() ?? request()->path();

        return view('scaffold-component::metatags', [
            'disabled' => $this->isRouteExcluded($route),
            'noindex' => $this->isNoIndex($route),
            'hreflang' => $this->getHreflang($route),
            'canonical' => $this->getCanonical($route),
        ]);
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

    /**
     * Determine whether current route should disable index
     * 
     * @param string $route
     * @return boolean
     */
    public function isNoIndex($route)
    {
        return $this->config->noindex === true || in_array($route, $this->config->noindex_routes);
    }

    /**
     * Get the hreflang attributes for current route
     * 
     * @param string $route
     * @return string
     */
    public function getHreflang($route)
    {
        return $this->config->hreflang[$route] ?? false;
    }

    /**
     * Get the canonical attributes for current route
     * 
     * @param string $route
     * @return string
     */
    public function getCanonical($route)
    {
        return $this->config->canonical[$route] ?? false;
    }
}