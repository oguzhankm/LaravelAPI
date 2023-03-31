<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductWithCategories;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class ProductController extends ApiController

{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     *
     * @OA\Get(
     *     path="/api/products",
     *     tags={"product"},
     *     summary="List all products",
     *     operationId="index",
     *     @OA\Parameter(
     *     name="limit",
     *     in="query",
     *     description="How many items to return at one time",
     *     required=false,
     *     @OA\Schema (type="integer", format="int32")
     *     ),
     * @OA\Response(
     *     response=200,
     *     description="A paged array of products",
     *      @OA\JsonContent(
     *     type="array",
     *     @OA\Items(ref="#/components/schemas/Product")
     *      )
     *     ),
     *      @OA\Response(
     *     response=401,
     *     description="Unauthorized",
     *     @OA\JsonContent()
     *     ),
     *      @OA\Response(
     *     response="default",
     *     description="Unexpected Error",
     *     @OA\JsonContent()
     *     )
     * )
     *
     */
    public function index(Request $request)
    {

//        $product = new Product();
//        $product->name = 'Product 1';
        //return Product::all();
        //return response(Product::paginate(10), 200);
        $offset = $request->has('offset') ? $request->query('offset') : 0;
        $limit = $request->has('limit') ? $request->query('limit') : 10;
        $qb = Product::query()->with('categories');
        if ($request->has('q'))
            $qb->where('name', 'like', '%' . $request->query('q') . '%');

        if ($request->has('sortBy'))
            $qb->orderBy($request->query('sortBy'), $request->query('sort', 'DESC'));

        $data = $qb->offset($offset)->limit($limit)->get();

        $data = $data->makeHidden('slug');

        return response($data, 200);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     *@OA\Post(
     *     path="/api/products",
     *     tags={"product"},
     *     summary="Create a Product",
     *     operationId="store",
     *     @OA\RequestBody (
     *     description="Store a product",
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *
     * @OA\Response(
     *     response=201,
     *     description="Product created response",
     *      @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     ),
     *      @OA\Response(
     *     response=401,
     *     description="Unauthorized",
     *     @OA\JsonContent()
     *     ),
     *      @OA\Response(
     *     response="default",
     *     description="Unexpected Error",
     *     @OA\JsonContent()
     *     ),
     * )
     *
     *
     *
     */
    public function store(Request $request)
    {
        //$input = $request->all();
        //$product = Product::create($input);
        $product = new Product;
        $product->name = $request->name;
        $product->slug = \Str::slug($request->name);
        $product->price = $request->price;
        $product->save();

        return $this->apiResponse(ResultType::Success, $product, 'Product Created', 201);

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\JsonResponse
     *
     *@OA\Get(
     *     path="/api/products/{productId}",
     *     tags={"product"},
     *     summary="Info for a specific product",
     *     operationId="show",
     *     @OA\Parameter(
     *     name="productId",
     *     in="path",
     *     description="The id colomn of the product to retrieve",
     *     required=true,
     *     @OA\Schema (type="integer", format="int32")
     *     ),
     * @OA\Response(
     *     response=200,
     *     description="Product detail response",
     *      @OA\JsonContent (ref="#/components/schemas/ApiResponse")
     *     ),
     *      @OA\Response(
     *     response=401,
     *     description="Unauthorized",
     *     @OA\JsonContent()
     *     ),
     *      @OA\Response(
     *     response="default",
     *     description="Unexpected Error",
     *     @OA\JsonContent()
     *     )
     * )
     */
    public function show($id): \Illuminate\Http\JsonResponse
    {
        try {
            $product = Product::query()->findOrFail($id);
            return $this->apiResponse(ResultType::Success, $product, 'Product Found', 200);
        } catch (ModelNotFoundException $exception) {
            return $this->apiResponse(ResultType::Error, null, 'Product Not Found', 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\JsonResponse
     *
     *
     *
     *@OA\Put(
     *     path="/api/products/{productId}",
     *     tags={"product"},
     *     summary="Update a Product",
     *     operationId="update",
     *     @OA\Parameter(
     *     name="productId",
     *     in="path",
     *     description="The id colomn of the product to update",
     *     required=true,
     *     @OA\Schema (type="integer", format="int32")
     *     ),
     *     @OA\RequestBody (
     *     description="Update a product",
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     * @OA\Response(
     *     response=200,
     *     description="Product updated response",
     *      @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     ),
     *      @OA\Response(
     *     response=401,
     *     description="Unauthorized",
     *     @OA\JsonContent()
     *     ),
     *      @OA\Response(
     *     response="default",
     *     description="Unexpected Error",
     *     @OA\JsonContent()
     *     )
     * )
     */
    public function update(Request $request, Product $product)
    {
//        $input = $request->all();
//        $product->update($input);


        $product->name = $request->name;
        $product->slug = \Str::slug($request->name);
        $product->price = $request->price;
        $product->save();

        return $this->apiResponse(ResultType::Success, $product, 'Product updated', 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\JsonResponse
     *
     *     @OA\Delete(
     *     path="/api/products/{productId}",
     *     tags={"product"},
     *     summary="Deletes a Product",
     *     operationId="destroy",
     *     @OA\Parameter(
     *     name="productId",
     *     in="path",
     *     description="The id colomn of the product to delete",
     *     required=true,
     *     @OA\Schema (type="integer", format="int32")
     *     ),
     *
     *      @OA\Response(
     *     response=200,
     *     description="Product delete response",
     *      @OA\JsonContent (ref="#/components/schemas/ApiResponse")
     *     ),
     *      @OA\Response(
     *     response=401,
     *     description="Unauthorized",
     *     @OA\JsonContent()
     *     ),
     *      @OA\Response(
     *     response="default",
     *     description="Unexpected Error",
     *     @OA\JsonContent()
     *     )
     * )
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return $this->apiResponse(ResultType::Success, null, 'Product deleted', 200);


    }

    public function custom1()
    {
        //$product = Product::select('id', 'name')->orderBy('created_at', 'desc')->take(10)->get();
//        $product = Product::selectRaw('id as product_id, name as product_name')
//            ->orderBy('created_at', 'desc')
//            ->take(10)
//            ->get();
//        return $product;


        // Model sınıfı işlemlerinde eğer tanımlama olmazsa mutlaka query metodunu kullan
        $products = Product::query()
            ->selectRaw('id as product_id, name as product_name')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        return $products;
    }

    public function custom2()
    {
        // Model sınıfı işlemlerinde eğer tanımlama olmazsa mutlaka query metodunu kullan
        $products = Product::query()
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        $mapped = $products->map(function ($product) {
            return [
                '_id' => $product['id'],
                'product_name' => $product['name'],
                'product_price' => $product['price'] * 1.03
            ];
        });

        return $mapped->all();

    }

    public function custom3()
    {
        $products = Product::query()->paginate(10);

        return ProductResource::collection($products);

    }

    public function listWithCategories()
    {
        $products = Product::query()->with('categories')->paginate(10);

        return ProductWithCategories::collection($products);

    }
}
