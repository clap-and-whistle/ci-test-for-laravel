<?php
declare(strict_types=1);

namespace App\Http\Controllers\Desk;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

final class MyWorkController extends Controller
{
    public const URL_ROUTE_NAME = "desk";

    public function indexAction(): View
    {
        logger(__METHOD__, ['id:' . (Auth::guest() ? 'guest' : Auth::id())]);
        return view('desk.my-work.index');
    }

}
