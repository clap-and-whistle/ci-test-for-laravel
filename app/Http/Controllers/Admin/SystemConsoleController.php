<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse as LaraRedirectResponse;
use Illuminate\Routing\Redirector as LaraRedirector;
use Illuminate\View\View;

class SystemConsoleController extends Controller
{
    public const URL_ROUTE_NAME = "admin";

    public function indexAction(): View|LaraRedirector|LaraRedirectResponse
    {
        return view('admin.system-console.index');
    }

}
