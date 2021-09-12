<?php

namespace App\Http\Controllers;

use App\Models\Page;

class PageController extends Controller
{
    /**
     * Show page
     * 
     * @return View
     */
    public function show()
    {
        $path = request()->path ?? 'home';
        $path = str_replace('/', '.', $path);

        if ($path === 'home') return view('web.home');
        else if (view()->exists('web.' . $path)) return view('web.' . $path);
        else if ($page = Page::where('slug', $path)->firstOrFail()) return view('web.page', compact('page'));

        abort(404);
    }
}
