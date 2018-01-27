<?php

namespace AppBundle\Controller;

use AppBundle\Manager\ProductManager;
use AppBundle\Model\Request\ProductEditModel;
use AppBundle\Model\Request\ProductListModel;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProductController
 *
 * @package AppBundle\Controller
 *
 * @Route("/product")
 */
class ProductController extends FOSRestController
{
    /** @var ProductManager */
    protected $manager;

    /**
     * ProductController constructor.
     *
     * @param ProductManager $manager
     */
    public function __construct(ProductManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Rest\Get("s")
     * @Rest\QueryParam(name="limit", allowBlank=false, requirements="^\d+")
     * @Rest\QueryParam(name="offset", requirements="^\d+")
     * @Rest\View
     *
     * @Operation(
     *     description="List products",
     *     tags={"Product"},
     *
     *     @SWG\Parameter(name="limit", in="query", type="integer", required=true, default=50, description="Limit results"),
     *     @SWG\Parameter(name="offset", in="query", type="integer", required=true, default=0, description="Offset results, pagination purpose"),
     *
     *     @SWG\Response(response="200", description="Successfull"),
     *     @SWG\Response(response="400", description="Wrong input parameters")
     *  )
     *
     * @param ProductListModel $requestModel
     *
     * @return \AppBundle\Entity\Product[]|array
     */
    public function listAction(ProductListModel $requestModel)
    {
        return $this->manager->getList($requestModel);
    }

    /**
     * @Rest\Post("")
     * @Rest\View(statusCode=201)
     *
     * @Operation(
     *     description="Creates product object",
     *     tags={"Product"},
     *     @SWG\Parameter(name="name", in="formData", type="string", description="Product name", required=true),
     *     @SWG\Parameter(name="type", in="formData", type="integer", enum={1,2}, description="Product type", required=true),
     *     @SWG\Parameter(name="color", in="formData", type="string", description="Color, required when type is 1"),
     *     @SWG\Parameter(name="texture", in="formData", type="string", description="Texture, required when type is 1"),
     *     @SWG\Parameter(name="width", in="formData", type="integer", description="Width, required when type is 2"),
     *     @SWG\Parameter(name="height", in="formData", type="integer", description="Height, required when type is 2"),
     *
     *     @SWG\Response(response="201", description="Created successfully"),
     *     @SWG\Response(response="400", description="Bad request, maybe validation errors")
     * )
     *
     * @param ProductEditModel $requestModel
     *
     * @return \AppBundle\Entity\Product
     */
    public function createAction(ProductEditModel $requestModel)
    {
        return $this->manager->create($requestModel);
    }
}
