<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\User;

use App\Application\UseCase\User\GetUsersWithDCNames\GetUsersWithDCNamesQuery;
use App\UI\Http\Rest\Controller\Shared\AbstractQueryController;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;


/**
 *
 * @OA\Get (
 *     path="/api/usersdcnames", description="Get a list of users with Delivery Companies names included",
 * )
 * @OA\Response(
 *      response="200",
 *      description="Users list response",
 *      @OA\JsonContent(type="object",
 *          @OA\Property(property="results", type="array",
 *             @OA\Items(type="object",description="An array users",
 *                @OA\Property(property="deliveryCompany", type="string"),
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
class GetUsersWithDCNamesController extends AbstractQueryController
{
    public function __invoke()
    {
        $results = $this->dispatch(new GetUsersWithDCNamesQuery());
        return $this->getNormalizeJsonResponse($results);
    }
}