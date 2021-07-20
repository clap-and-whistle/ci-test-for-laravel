<?php
declare(strict_types=1);

namespace App\Http\Controllers\Desk;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

final class MyWorkController extends BaseController
{
    public function indexAction(): View
    {
        logger(__METHOD__, ['id:' . (Auth::guest() ? 'guest' : Auth::id())]);
        return view('desk.my-work.index');
    }

}
