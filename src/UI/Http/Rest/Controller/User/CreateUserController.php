<?PHP

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\User;

use App\Application\UseCase\User\CreateUser\CreateUserCommand;
use App\UI\Http\Rest\Controller\Shared\AbstractCommandController;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Post (
 *     path="/api/users",
 *     description="Register a new user inside a delivery company")
 * @OA\RequestBody (
 *      @OA\JsonContent(
 *        type="object",
 *          @OA\Property(property="deliverycompanyuuid", type="string"),
 *          @OA\Property(property="useruuid", type="string"),
 *          @OA\Property(property="firstname", type="string"),
 *          @OA\Property(property="lastname", type="string"),
 *          @OA\Property(property="email", type="string"),
 *          @OA\Property(property="username", type="string"),
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
class CreateUserController extends AbstractCommandController
{
    public function __invoke()
    {
        $this->dispatch(
            new CreateUserCommand(
                $this->request->request->get('deliverycompanyuuid'),
                $this->request->request->get('useruuid'),
                $this->request->request->get('firstname'),
                $this->request->request->get('lastname'),
                $this->request->request->get('email'),
                $this->request->request->get('username')
            )
        );
        return new Response('', Response::HTTP_CREATED);
    }

}