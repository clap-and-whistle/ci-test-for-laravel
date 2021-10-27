<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Bizlogics\Uam\Aggregate\AdminUserAggregateRepositoryInterface;
use Illuminate\Http\RedirectResponse as LaraRedirectResponse;
use Illuminate\Routing\Redirector as LaraRedirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

final class SystemConsoleController extends Controller
{
    public const URL_ROUTE_NAME = "admin";

    private AdminUserAggregateRepositoryInterface $adminRepos;

    /**
     * @param AdminUserAggregateRepositoryInterface $adminRepos
     */
    public function __construct(AdminUserAggregateRepositoryInterface $adminRepos)
    {
        $this->adminRepos = $adminRepos;
    }

    public function indexAction(): View|LaraRedirector|LaraRedirectResponse
    {
        $guard = Auth::guard('admin');
        if ($guard->guest()) {
            logger(__METHOD__, ['$guard->guest()', $guard->guest(), 'ログインセッション無し']);
            return redirect('/e');
        }

        logger(__METHOD__, ['$guard->id()'=>$guard->id()]);
        $adminUser = $this->adminRepos->findById($guard->id());
        if (is_null($adminUser)) {
            logger(__METHOD__, ['User $user', $adminUser, 'Admin集約インスタンスの構築に失敗']);
            return redirect('/e');
        }

        return view('admin.system-console.index', ['email'=>$adminUser->email()]);
    }

}
