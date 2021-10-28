<?php
declare(strict_types=1);

namespace App\Http\Controllers\Uam\SysAdminOperation;

use Bizlogics\Uam\UseCase\SysAdminOperation\Login\LoginUseCase;
use Illuminate\Contracts\Auth\Authenticatable as LaraAuthenticatable;
use Illuminate\Http\RedirectResponse as LaraRedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Routing\Redirector as LaraRedirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Throwable;

class LoginController extends BaseController
{
    public const URL_ROUTE_NAME_INPUT_ACTION = "admin-login";

    private LoginUseCase $useCase;

    /**
     * @param LoginUseCase $useCase
     */
    public function __construct(LoginUseCase $useCase)
    {
        $this->useCase = $useCase;
    }


    public function inputAction(): View
    {
        logger(__METHOD__);
        return view('uam.sys-admin-operation.login.input');
    }

    public function execAction(Request $request): LaraRedirector|LaraRedirectResponse
    {
        $guard = Auth::guard('admin');
        logger(__METHOD__, ['id:' . ($guard->guest() ? 'guest' : $guard->id())]);
        $email = $request->get('email');
        $password = $request->get('password');

        try {
            $result = $this->useCase->execute($email, $password);
            if (!$result->isSuccess()) {
                logger(__METHOD__, [$result->eMessage()]);
                return back()->withErrors($result->eMessage());
            }
            // AdminAccountBaseインスタンスをキャストするためのアロー関数でクロージャを生成
            $authenticatable = fn(): LaraAuthenticatable => $result->authObj();

            /*
             * セッションを開始
             */
            $guard->login($authenticatable());
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
        $guard = Auth::guard('admin');
        logger(__METHOD__, [
            'id:' . ($guard->guest() ? 'guest' : $guard->id())
        ]);
        $guard->logout();
        return view('uam.sys-admin-operation.login.logout');
    }

}
