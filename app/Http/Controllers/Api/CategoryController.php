<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category as Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Str;

class CategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->apiResponse(ResultType::Success, Category::all(), 'Categories fetched', 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        //$input = $request->all();
        //$category = Product::create($input);
        $category = new Category;
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->save();

        return $this->apiResponse(ResultType::Success, $category,'Category Created.', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Category $category)
    {
        return $this->apiResponse(ResultType::Success, $category, 'Category fetched', 200);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Category $category): \Illuminate\Http\JsonResponse
    {
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->save();

        return $this->apiResponse(ResultType::Success, $category, 'Category updated.', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return $this->apiResponse(ResultType::Success, null, 'Category deleted', 200);
    }

    public function custom1()
    {
        //$category  = Category::all()->pluck('id');
        //$category  = Category::all()->pluck('id', 'name');
        $category  = Category::all()->pluck('name', 'id');

        return $category;
    }

    public function report1()
    {
        return DB::table('product_categories as pc')
            ->selectRaw('c.name, COUNT(*) as total')
            ->join('categories as c', 'c.id', '=', 'pc.category_id')
            ->join('products as p', 'p.id', '=', 'pc.product_id')
            ->groupBy('c.name')
            ->orderByRaw('COUNT(*) DESC')
            ->get();
    }
}
