<?php
declare(strict_types=1);

namespace App\Http\Controllers\UserOperation;

use Bizlogics\UseCase\UserOperation\Login\AuthenticatableInterface;
use Bizlogics\UseCase\UserOperation\Login\LoginUseCase;
use Bizlogics\UseCase\UserOperation\Login\Result;
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
        return view('user-operation.login.input');
    }

    public function execAction(Request $request): LaraRedirector|LaraRedirectResponse
    {
        logger(__METHOD__);
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

            // TODO: 認証済みユーザ向けコンテンツブロックの配置がトップページへ行われたら、リダイレクト先を直す
            // ログイン完了画面へリダイレクト
            return redirect('/uam/login-complete/');

        } catch (Throwable $e) {
            logger(__METHOD__, [
                '予期せぬエラー:'.$e->getMessage(),
                'トレース: ' . $e->getTraceAsString()
            ]);
//            return redirect('/e');
            throw $e;
        }
    }

}
