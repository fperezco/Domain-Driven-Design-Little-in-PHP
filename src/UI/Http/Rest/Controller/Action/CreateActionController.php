<?PHP

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Action;

use App\Application\UseCase\Action\CreateAction\CreateActionCommand;
use App\UI\Http\Rest\Controller\Shared\AbstractCommandController;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Post (
 *     path="/api/actions",
 *     description="Assign a new action to user")
 * @OA\RequestBody (
 *      @OA\JsonContent(
 *        type="object",
 *          @OA\Property(property="useruuid", type="string", description="the user owner uuid"),
 *          @OA\Property(property="title", type="string", description="action title")
 *      )
 *     ),
 * @OA\Response(
 *      response="200",
 *      description="Action is assigned to the provided user"
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
 *     name="Actions",
 * )
 */
class CreateActionController extends AbstractCommandController
{
    public function __invoke(): Response
    {
        $this->dispatch(
            new CreateActionCommand(
                $this->request->request->get('useruuid'),
                $this->request->request->get('title'),
            )
        );
        return new Response('', Response::HTTP_CREATED);
    }

}