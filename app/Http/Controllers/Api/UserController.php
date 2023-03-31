<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     *
     * @OA\Get(
     *     path="/api/users",
     *     tags={"User"},
     *     summary="List all users",
     *
     *     @OA\Parameter(
     *     name="limit",
     *     in="query",
     *     description="How many items to return at one time",
     *     required=false,
     *     @OA\Schema (type="integer", format="int32")
     *     ),
     * @OA\Response(
     *     response=200,
     *     description="A paged array of users",
     *      @OA\JsonContent(
     *     type="array",
     *     @OA\Items(ref="#/components/schemas/User")
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
     */
    public function index(Request $request): Response
    {


        $offset = $request->has('offset') ? $request->query('offset') : 0;
        $limit = $request->has('limit') ? $request->query('limit') : 10;
        $qb = User::query();
        if ($request->has('q'))
            $qb->where('name', 'like', '%' . $request->query('q') . '%');

        if ($request->has('sortBy'))
            $qb->orderBy($request->query('sortBy'), $request->query('sort', 'DESC'));

        $data = $qb->offset($offset)->limit($limit)->get();

        return response($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|Response
     *@OA\Post(
     *     path="/api/users",
     *     tags={"User"},
     *     summary="Create a User",
     *
     *     @OA\RequestBody (
     *     description="Store a user",
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/User")
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
     */
    public function store(UserStoreRequest $request)
    {
        //$input = $request->all();
        //$user = Product::create($input);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        return response([
            'data' => $user,
            'message' => 'User Created.'
        ], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *     path="/api/users/{userId}",
     *     tags={"User"},
     *     summary="Info for a specific user",
     *
     *     @OA\Parameter(
     *     name="userId",
     *     in="path",
     *     description="The id colomn of the user to retrieve",
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
    public function show(User $user, int $id): \Illuminate\Http\JsonResponse
    {

        try {
            $user = User::query()->findOrFail($id);
            return response()->json(['user' => $user], 200);

        }catch (\Exception $e){
            return response()->json([
                'message' => 'UserController.show\'da Bi şeyler yanlış gitti',
                'error' => $e->getMessage()
            ], 400);
           }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|Response
     *
     * @OA\Put(
     *     path="/api/users/{userId}",
     *     tags={"User"},
     *     summary="Update a User",
     *
     *     @OA\Parameter(
     *     name="userId",
     *     in="path",
     *     description="The id colomn of the user to update",
     *     required=true,
     *     @OA\Schema (type="integer", format="int32")
     *     ),
     *     @OA\RequestBody (
     *     description="Update a user",
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/User")
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
    public function update(Request $request, User $user)
    {
        try {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();

            return response([
                'data' => $user,
                'message' => 'User updated.'
            ], 200);
        }catch (\Exception $e){
            return response()->json([
                'message' => 'UserController.store\'da Bi şeyler yanlış gitti',
                'error' => $e->getMessage()
            ], 400);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return Response
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response([
            'message' => 'User deleted'
        ], 200);
    }

    public function custom1()
    {
//      $user2 = User::all()->find(2);
//      UserResource::withoutWrapping();
//      return new UserResource($user2);

        $users = User::all();
//      return UserResource::collection($users);

        //return new UserCollection($users);


        return UserResource::collection($users)->additional([
            'meta' => [
                'total_users' => $users->count(),
                'custom' => 'value'
            ]
        ]);
    }
}
