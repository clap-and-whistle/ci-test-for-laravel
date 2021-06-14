<?php
declare(strict_types=1);

namespace App\Http\Controllers\UserOperation;

use Bizlogics\Aggregate\UserAggregateRepositoryInterface;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\RedirectResponse as LaraRedirectResponse;
use Illuminate\View\View;

final class LoginController extends BaseController
{
    private UserAggregateRepositoryInterface $userRepos;

    /**
     * LoginController constructor.
     * @param UserAggregateRepositoryInterface $userRepos
     */
    public function __construct(UserAggregateRepositoryInterface $userRepos)
    {
        $this->userRepos = $userRepos;
    }

    public function inputAction(): View
    {
        return view('user-operation.login.input');
    }

    public function execAction(): LaraRedirectResponse
    {
        redirect('/');
    }
}
