<?php

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Utils\Paginate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/', function(){
    return response()->json([
        'api_version'=>1
    ],200);
});

Route::get('/posts', function(Request $req){
    
    $page = $req->query('page') == null ? 1 : $req->query('page');
    $limit = $req->query('limit') == null ? 20 : $req->query('limit');
    $fl = $req->query('friendly_link') == null ? 5 : $req->query('friendly_link');
    
    $status = Response::HTTP_UNPROCESSABLE_ENTITY;
    if(!is_numeric($page)) {
        return response()->json([
            'message'=>'page is not numeric',
            'status'=>$status
        ],$status);
    }

    $count = Post::count(); 
    $paginate = new Paginate($page, $limit, $count);
    $paginate->setLinkPagesAvailable($paginate->getTotalPages());

    $data = Post::with(['user'=>function($q){
        $q->select('id', 'name');
    }])->offset($paginate->getOffset())
        ->limit($paginate->getResultPerPage())
        ->get();
    
    $status = Response::HTTP_OK;
    return response()->json([
        'posts'=> $data,
        'paginate'=>[
            'friendly_link'=>$paginate->getLinkPagesAvailable($fl),
            'limit_per_page'=>(int) $limit,
            'current_page'=>(int) $page,
            'total_pages'=>$paginate->getTotalPages(),
            'count'=> $count,
        ]
    ],$status);
});

Route::get('/posts/{post_id}', function(int $post_id){
    $status = Response::HTTP_UNPROCESSABLE_ENTITY;
    if(!is_numeric($post_id)) {
        return response()->json([
            'message'=>'page is not numeric',
            'status'=>$status
        ],$status);
    }

    $data = Post::find($post_id);
    $user = $data->user()->select('name','id')->first();
    $status = Response::HTTP_OK;

    return response()->json([
        'post'=> [
            'content'=>$data,
            'user'=>$user,
        ]
    ],$status);
});

Route::get('/users/{user_id}', function(int $user_id){
    $status = Response::HTTP_UNPROCESSABLE_ENTITY;
    if(!is_numeric($user_id)) {
        return response()->json([
            'message'=>'page is not numeric',
            'status'=>$status
        ],$status);
    }

    $data = User::where('id',$user_id)->select('id','name','email')->first();
    $status = Response::HTTP_OK;

    return response()->json([
        'user'=> $data
    ],$status);
});

Route::middleware('auth:sanctum')->get('/users/{user_id}/posts', function(Request $request, int $user_id){
    $page = $request->query('page') == null ? 1 : $request->query('page');
    $limit = $request->query('limit') == null ? 20 : $request->query('limit');
    $fl = $request->query('friendly_link') == null ? 5 : $request->query('friendly_link');
    $status = Response::HTTP_UNPROCESSABLE_ENTITY;
    if(!is_numeric($user_id)) {
        return response()->json([
            'message'=>'page is not numeric',
            'status'=>$status
        ],$status);
    }

    $data = User::where('id',$user_id)->select('id','name','email')->first();
    
    if(!$data || $user_id != $request->user()->id){
        $status = Response::HTTP_BAD_REQUEST;
        return response()->json([
            'message'=>'user don\'t exists or your token is invalid',
            'status'=>$status
        ],$status);
    }
    $count = $data->posts()->count();
    $paginate = new Paginate($page, $limit, $count);
    $paginate->setLinkPagesAvailable($paginate->getTotalPages());
    $posts = $data->posts()
        ->offset($paginate->getOffset())
        ->limit($paginate->getResultPerPage())
        ->get();
    $status = Response::HTTP_OK;
    return response()->json([
        'posts'=> $posts,
        'paginate'=>[
            'friendly_link'=>$paginate->getLinkPagesAvailable($fl),
            'limit_per_page'=>(int) $limit,
            'current_page'=>(int) $page,
            'total_pages'=>$paginate->getTotalPages(),
            'count'=> $count,
        ]
    ],$status);
});

Route::middleware('auth:sanctum')->get('/user/posts', function(Request $req){
    
    $page = $req->query('page') == null ? 1 : $req->query('page');
    $limit = $req->query('limit') == null ? 20 : $req->query('limit');
    $fl = $req->query('friendly_link') == null ? 5 : $req->query('friendly_link');
    
    $status = Response::HTTP_UNPROCESSABLE_ENTITY;
    if(!is_numeric($page)) {
        return response()->json([
            'message'=>'page is not numeric',
            'status'=>$status
        ],$status);
    }
    $data = User::find($req->user()->id)->select('id','name')->first();
    $count = $data->posts()->count(); 
    $paginate = new Paginate($page, $limit, $count);
    $paginate->setLinkPagesAvailable($paginate->getTotalPages());
    
    $posts = $data->posts()->offset($paginate->getOffset())
    ->limit($paginate->getResultPerPage())
    ->get();

    $status = Response::HTTP_OK;
    return response()->json([
        'user'=> $data,
        'posts'=> $posts,
        'friendly_link'=>$paginate->getLinkPagesAvailable($fl),
        'paginate'=>[
            'limit_per_page'=>(int) $limit,
            'current_page'=>(int) $page,
            'total_pages'=>$paginate->getTotalPages(),
            'count'=> $count,
        ]
    ],$status);
});

Route::post('/posts', function(Request $request){
    $body = $request->only(['title','text']);
    return Post::create([
        'title'=>$body['title'],
        'text'=>$body['text'],
        'user_id'=>1
    ]);
});

Route::middleware('auth:sanctum')->get('/is-auth', function(Request $request){
    return response()->json([
        'user'=> $request->user()
    ],200);
});

Route::post('/sign-in', function (Request $request) {
    $body = $request->only(['email', 'password']);
    $device_name = 'desktop';
    
    $user = User::where('email', $body['email'])->first();
    if(!$user){
        return 
        response()->json([
            'message'=>'user email don\'t exists'
        ], 400);
    }

    if (!Hash::check($body['password'], $user->password)) {
        return response()   
        ->json([
            'message'=>'password is wrong'
        ],400);
        /*
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);*/
    }

    $accessToken = $user->createToken($device_name, [
        'server:create'
    ])->plainTextToken;

    return response()->json([
        'access_token'=> $accessToken
    ],200);
});

Route::middleware('auth:sanctum')->delete('/sign-out', function(Request $request){

    
    $user = User::select('id')
        ->where('id',$request->user()->id)
        ->first();
    $tokenId = explode('|',$request->bearerToken())[0];
    
    //delete access token
    $user->tokens()
        ->where('tokenable_id', $user['id'])
        ->where('id', $tokenId)
        ->delete();

    return response()->json([
        'user'=> $request->user()
    ],200);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json([
        'user' => $request->user()
    ],200);
});
