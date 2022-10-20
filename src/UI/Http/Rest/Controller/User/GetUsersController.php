<?php
declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\User;

use App\Application\UseCase\User\GetUsers\GetUsersQuery;
use App\UI\Http\Rest\Controller\Shared\AbstractQueryController;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;


/**
 *
 * @OA\Get (
 *     path="/api/users", description="Get a list of users",
 * )
 * @OA\Response(
 *      response="200",
 *      description="Users list response",
 *      @OA\JsonContent(type="object",
 *          @OA\Property(property="results", type="array",
 *             @OA\Items(type="object",description="An array users",
 *                @OA\Property(property="deliveryCompanyUuid", type="string"),
 *                @OA\Property(property="uuid", type="string"),
 *                @OA\Property(property="firstname", type="string"),
 *                @OA\Property(property="lastname", type="string"),
 *                @OA\Property(property="username", type="string"),
 *                @OA\Property(property="email", type="string"),
 *          ),
 *     ),
 * ),
 * @OA\Response(
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
class GetUsersController extends AbstractQueryController
{
    public function __invoke()
    {
        $results = $this->dispatch(new GetUsersQuery());
        return $this->getNormalizeJsonResponse($results);
    }
}