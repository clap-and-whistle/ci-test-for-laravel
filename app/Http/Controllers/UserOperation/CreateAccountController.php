<?php
declare(strict_types=1);

namespace App\Http\Controllers\UserOperation;

use App\Infrastructure\AggregateRepository\Exception\RegistrationProcessFailedException;
use Bizlogics\UseCase\UserOperation\CreateAccount\CreateAccountUseCase;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;
use Exception;

final class CreateAccountController extends BaseController
{
    /** @var CreateAccountUseCase */
    private $useCase;

    public function __construct(CreateAccountUseCase $useCase)
    {
        $this->useCase = $useCase;
    }

    public function newAction(): View
    {
        try {
            $result = $this->useCase->execute();
        } catch (RegistrationProcessFailedException $e) {
            logger(__METHOD__ . ': catch ' . RegistrationProcessFailedException::class);
            throw new Exception('DB登録処理時にエラー発生');
        } catch (Exception $e) {
            logger(__METHOD__ . ': 予期せぬエラー: ' . $e->getMessage());
            logger(__METHOD__ . ': Trace: ' . $e->getTraceAsString());
            throw $e;
        }
        return view('user-operation.create-account.new');
    }
}
