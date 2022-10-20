<?php
declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\DeliveryCompany;


use App\Application\UseCase\DeliveryCompany\GetDeliveryCompanies\GetDeliveryCompaniesQuery;
use App\UI\Http\Rest\Controller\Shared\AbstractQueryController;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;


/**
 *
 * @OA\Get (
 *     path="/api/deliverycompanies", description="Get a list of delivery companies",
 * )
 * @OA\Response(
 *      response="200",
 *      description="Delivery companies list response",
 *      @OA\JsonContent(type="object",
 *          @OA\Property(property="results", type="array",
 *             @OA\Items(type="object",description="An array delivery companies",
 *                @OA\Property(property="uuid", type="string"),
 *                @OA\Property(property="name", type="string"),
 *                @OA\Property(property="tasksInTodo", type="int"),
 *                @OA\Property(property="vipLevel", type="string"),
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
 *     name="DeliveryCompany",
 * )
 */
class GetDeliveryCompaniesController extends AbstractQueryController
{
    public function __invoke(): Response
    {
        $results = $this->dispatch(new GetDeliveryCompaniesQuery());
        return $this->getNormalizeJsonResponse($results);
    }
}