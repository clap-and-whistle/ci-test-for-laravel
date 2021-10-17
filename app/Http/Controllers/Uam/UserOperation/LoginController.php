<?php
declare(strict_types=1);

namespace App\Http\Controllers\Uam\UserOperation;

use Bizlogics\Uam\UseCase\UserOperation\Login\LoginUseCase;
use Bizlogics\Uam\UseCase\UserOperation\Login\Result;
use Illuminate\Contracts\Auth\Authenticatable as LaraAuthenticatable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Routing\Redirector as LaraRedirector;
use Illuminate\Http\RedirectResponse as LaraRedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Throwable;

final class LoginController extends BaseController
{
    public const URL_ROUTE_NAME_INPUT_ACTION = "login";

    private LoginUseCase $useCase;

    /**
     * LoginController constructor.
     * @param LoginUseCase $useCase
     */
    public function __construct(LoginUseCase $useCase)
    {
        $this->useCase = $useCase;
    }

    public function inputAction(): View
    {
        logger(__METHOD__);
        return view('uam.user-operation.login.input');
    }

    public function execAction(Request $request): LaraRedirector|LaraRedirectResponse
    {
        logger(__METHOD__, ['id:' . (Auth::guest() ? 'guest' : Auth::id())]);
        $email = $request->get('email');
        $password = $request->get('password');

        try {
            /** @var Result $result */
            $result = $this->useCase->execute($email, $password);
            if (!$result->isSuccess()) {
                return back()->withErrors($result->eMessage());
            }
            // UserAccountBaseインスタンスをキャストするためのアロー関数でクロージャを生成
            $authenticatable = fn(): LaraAuthenticatable => $result->authObj();

            /*
             * セッションを開始
             */
            Auth::login($authenticatable());
            return redirect('/desk/index');

        } catch (Throwable $e) {
            logger(__METHOD__, [
                '予期せぬエラー:'.$e->getMessage(),
                'トレース: ' . $e->getTraceAsString()
            ]);
//            return redirect('/e');
            throw $e;
        }
    }

    public function logoutAction()
    {
        logger(__METHOD__, [
            'id:' . (Auth::guest() ? 'guest' : Auth::id())
        ]);
        Auth::logout();
        return view('uam.user-operation.login.logout');
    }
}
