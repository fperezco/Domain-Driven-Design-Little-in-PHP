<?php
declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\User;

use App\Application\UseCase\User\DeleteUser\DeleteUserCommand;
use App\UI\Http\Rest\Controller\Shared\AbstractCommandController;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Security;

/**
 * @OA\Delete (
 *     path="/api/users/{uuid}",
 *     description="Delete a user by his uuid")
 *     @OA\Response(
 *      response="200",
 *      description="User deleted successfully"
 *     ),
 *     @OA\Response(
 *      response="default",
 *      description="an ""unexpected"" error"
 *     )
 * )
 * @Security(name="Bearer")
 *
 * @OA\Tag(
 *     name="Users",
 * )
 */
class DeleteUserController extends AbstractCommandController
{
    public function __invoke()
    {
        $this->dispatch(new DeleteUserCommand(
            $this->request->attributes->get('uuid')
        ));

        return new Response('', Response::HTTP_OK);
    }
}