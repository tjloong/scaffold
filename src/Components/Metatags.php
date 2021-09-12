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
        $this->config = (object)config('scaffold.web.metatags');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('scaffold::components.metatags', [
            'disabled' => $this->isRouteExcluded(),
            'noindex' => $this->isNoIndex(),
            'hreflang' => $this->getHreflang(),
            'canonical' => $this->getCanonical(),
        ]);
    }

    /**
     * Check current route is excluded from rendering
     * 
     * @return boolean
     */
    public function isRouteExcluded()
    {
        return collect($this->config->exclude_paths)->contains(function ($path) {
            return request()->is($path);
        });
    }

    /**
     * Determine whether current route should disable index
     * 
     * @return boolean
     */
    public function isNoIndex()
    {
        return $this->config->noindex === true
            || collect($this->config->noindex_paths)->contains(function ($path) {
                return request()->is($path);
            });
    }

    /**
     * Get the hreflang attributes for current route
     * 
     * @return string
     */
    public function getHreflang()
    {
        return collect($this->config->hreflang)->first(function ($value, $key) {
            return request()->is($key);
        });
    }

    /**
     * Get the canonical attributes for current route
     * 
     * @return string
     */
    public function getCanonical()
    {
        return collect($this->config->canonical)->first(function ($value, $key) {
            return request()->is($key);
        });
    }
}