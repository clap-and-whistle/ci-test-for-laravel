<?php
declare(strict_types=1);

namespace App\Http\Controllers\Open;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;

final class HomeController extends BaseController
{
    public function indexAction(): View
    {
        return view('open.home.index');
    }

    public function errorAction(): view
    {
        return view('open.home.error');
    }
}
