<?php
declare(strict_types=1);

namespace App\Http\Controllers\Desk;

use App\Http\Controllers\Controller;
use Bizlogics\Uam\Aggregate\UserAggregateRepositoryInterface;
use Illuminate\Routing\Redirector as LaraRedirector;
use Illuminate\Http\RedirectResponse as LaraRedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

final class MyWorkController extends Controller
{
    public const URL_ROUTE_NAME = "desk";

    /** @var UserAggregateRepositoryInterface  */
    private UserAggregateRepositoryInterface $userRepos;

    public function __construct(UserAggregateRepositoryInterface $userAggregateRepository)
    {
        $this->userRepos = $userAggregateRepository;
    }

    public function indexAction(): View|LaraRedirector|LaraRedirectResponse
    {
        if (Auth::guest()) {
            logger(__METHOD__, ['Auth::guest()', Auth::guest(), 'ログインセッション無し']);
            return redirect('/e');
        }

        $user = $this->userRepos->findById(Auth::id());
        if (is_null($user)) {
            logger(__METHOD__, ['User $user', $user, 'User集約インスタンスの構築に失敗']);
            return redirect('/e');
        }

        return view('desk.my-work.index', ['email'=>$user->email()]);
    }

}
