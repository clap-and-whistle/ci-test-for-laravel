<?php
declare(strict_types=1);

namespace App\Http\Controllers\Uam\SysAdminOperation;

use Illuminate\Http\RedirectResponse as LaraRedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Routing\Redirector as LaraRedirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends BaseController
{
    public const URL_ROUTE_NAME_INPUT_ACTION = "admin-login";

    public function inputAction(): View
    {
        logger(__METHOD__);
        return view('uam.sys-admin-operation.login.input');
    }

    public function execAction(Request $request): LaraRedirector|LaraRedirectResponse
    {
        logger(__METHOD__, ['id:' . (Auth::guest() ? 'guest' : Auth::id())]);
        $email = $request->get('email');
        $password = $request->get('password');

        try {
            return redirect('/admin/index');
        } catch (Throwable $e) {
            logger(__METHOD__, [
                '予期せぬエラー:'.$e->getMessage(),
                'トレース: ' . $e->getTraceAsString()
            ]);
//            return redirect('/e');
            throw $e;
        }
    }
    public function logoutAction(): View
    {
        logger(__METHOD__, [
            'id:' . (Auth::guest() ? 'guest' : Auth::id())
        ]);
        Auth::logout();
        return view('uam.sys-admin-operation.login.logout');
    }

}
