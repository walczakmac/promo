<?php

namespace App\UserInterface\Controller;

use App\Application\Handler;
use App\Application\Validator\ValidationException;
use App\Domain\Product;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ProductController
{
    /**
     * @var Handler\CreateProduct
     */
    private $createProductHandler;

    /**
     * @var Handler\ListProducts
     */
    private $listProductsHandler;

    public function __construct(Handler\CreateProduct $createProductHandler, Handler\ListProducts $listProductsHandler)
    {
        $this->createProductHandler = $createProductHandler;
        $this->listProductsHandler = $listProductsHandler;
    }

    public function create(Request $request) : Response
    {
        $this->deserializeRequestData($request);
        try {
            $this->createProductHandler->handle($request->request->all());
        } catch (ValidationException $e) {
            return new Response(
                json_encode($e->getErrors()),
                Response::HTTP_BAD_REQUEST,
                ['Content-Type' => 'application/json']
            );
        }

        return new Response(null, Response::HTTP_CREATED);
    }

    public function index(Request $request) : Response
    {
        $this->deserializeRequestData($request);
        $products = array_map(function(Product $product) {
            return $product->toArray();
        }, $this->listProductsHandler->handle($request->request->all()));

        return new Response(json_encode($products), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    private function deserializeRequestData(Request $request) : void
    {
        if ($request->headers->get('Content-Type') !== 'application/json') {
            throw new BadRequestHttpException('Invalid content type provided. Only application/json is accepted.');
        }

        $data = json_decode((string) $request->getContent(), true);
        if (!is_array($data)) {
            throw new BadRequestHttpException('Invalid JSON payload provided.');
        }

        $request->request->replace($data);
    }
}
