<?php
declare(strict_types=1);

namespace App\Http\Controllers\Uam\UserOperation;

use App\Http\Controllers\Controller;

class LoginCompleteController extends Controller
{
    public function completeAction() {
        logger(__METHOD__);
        return view('uam.user-operation.login-complete.complete');
    }

}
