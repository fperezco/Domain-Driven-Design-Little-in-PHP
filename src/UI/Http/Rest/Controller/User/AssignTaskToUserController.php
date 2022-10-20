<?PHP

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\User;

use App\Application\UseCase\User\AssignTaskToUser\AssignTaskToUserCommand;
use App\Application\UseCase\User\CreateUser\CreateUserCommand;
use App\UI\Http\Rest\Controller\Shared\AbstractCommandController;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Post (
 *     path="/api/users/{useruuid}/tasks",
 *     description="Assign a new task to user")
 * @OA\RequestBody (
 *      @OA\JsonContent(
 *        type="object",
 *          @OA\Property(property="title", type="string"),
 *          @OA\Property(property="priority", type="string"),
 *      )
 *     ),
 * @OA\Response(
 *      response="200",
 *      description="User registered inside the provided Delivery Company"
 *     ),
 * @OA\Response(
 *      response="default",
 *      description="an ""unexpected"" error"
 *     )
 * )
 *
 * @Security(name="Bearer")
 *
 * @OA\Tag(
 *     name="Users",
 * )
 */
class AssignTaskToUserController extends AbstractCommandController
{
    public function __invoke()
    {
        $this->dispatch(
            new AssignTaskToUserCommand(
                $this->request->attributes->get('useruuid'),
                $this->request->request->get('title'),
                $this->request->request->get('priority')
            )
        );
        return new Response('', Response::HTTP_CREATED);
    }

}