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
        'api_version'=>1,
        'app'=>'http://localhost:8080'
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
    }])->orderBy('id', 'DESC')
        ->offset($paginate->getOffset())
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
            'has_next'=>$paginate->hasNext()
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
    if(!$data) {
        $status = Response::HTTP_NOT_FOUND;
        
        return response()->json([
            'message'=>'page is not numeric',
            'status'=>$status
        ],$status);
    }
    $user = $data->user()->select('name','id')->first();
    $status = Response::HTTP_OK;

    return response()->json([
        'post'=> [
            'content'=>$data,
            'user'=>$user,
        ]
    ],$status);
});

Route::get('/posts/{post_id}/user', function(string $post_id){
    $status = Response::HTTP_UNPROCESSABLE_ENTITY;
    if(!is_numeric($post_id)) {
        return response()->json([
            'message'=>'page is not numeric',
            'status'=>$status
        ],$status);
    }

    $post = Post::find($post_id);
    if(!$post){
        $status = Response::HTTP_NOT_FOUND;
        return response()->json([
            'message'=>'post not exists',
            'status'=>$status
        ],$status);
    }
    $status = Response::HTTP_OK;
    $user = $post->user()->first();
    return response()->json([
        'user'=> $user
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
            'has_next'=>$paginate->hasNext()
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
//create post
Route::middleware('auth:sanctum')->post('/posts', function(Request $request){

    $body = $request->only(['title','text']);
    
    $user = User::find($request->user()->id);
    
    $created = $user->posts()->create([
        'title'=>$body['title'],
        'text'=>$body['text']
    ]);
    if(!$created){
        return response()->json([
            'message'=>'post isnot created.',
            'status'=>400
        ],400);
    }

    return response()->json([
        'message'=>'post created.',
        'status'=>200
    ],200);
});

//update post
Route::middleware('auth:sanctum')->put('/posts/{post_id}/update', function(Request $request, $post_id){
    $body = $request->only(['title', 'text']);
    $status = Response::HTTP_UNPROCESSABLE_ENTITY;
    if(!is_numeric($post_id)) {
        return response()->json([
            'message'=>'post_id is not numeric',
            'status'=>$status
        ],$status);
    }
    $user =  User::find($request->user()->id);
    $updated = $user
        ->posts()
        ->find($post_id)
        ->update($body);

    if(!$updated) {
        $status = Response::HTTP_BAD_REQUEST;
        return response()->json([
            'message'=>'fail on update',
            'status'=>$status
        ],$status);
    }

    $status = Response::HTTP_ACCEPTED;
    return response()->json([
        'message'=>'success on update',
        'status'=>$status
    ],$status);
});

//delete post
Route::middleware('auth:sanctum')->delete('/posts/{post_id}/delete', function(Request $request, $post_id){
    $status = Response::HTTP_UNPROCESSABLE_ENTITY;
    if(!is_numeric($post_id)) {
        return response()->json([
            'message'=>'post_id is not numeric',
            'status'=>$status
        ],$status);
    }

    $user = User::find($request->user()->id);
    
    $deleted = $user
        ->posts()
        ->find($post_id)
        ->delete();
    if(!$deleted) {
        $status = Response::HTTP_BAD_REQUEST;
        return response()->json([
            'message'=>'fail on delete',
            'status'=>$status
        ],$status);
    }

    $status = Response::HTTP_ACCEPTED;
    return response()->json([
        'message'=>'success on delete',
        'status'=>$status
    ],$status);
});

Route::middleware('auth:sanctum')->get('/is-auth', function(Request $request){
    $user = array_filter($request->user()->toArray(), function($key){
        if($key == 'id' || $key == 'name' || $key == 'email') {
            return true;
        }
    }, ARRAY_FILTER_USE_KEY);
    
    return response()->json([
        'user'=> $user
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

Route::post('/sign-up', function (Request $request) {
    $body = $request->only(['name','email', 'password']);
    $device_name = 'desktop';
   
    $new_user = User::where('email', $body['email'])->first();
    if($new_user){
        return response()->json([
            'message'=>'user email already exists'
        ], 400);
    }

    $created = User::create([
        'name'=>$body['name'],
        'email'=>$body['email'],
        'password'=>Hash::make($body['password'])
    ]);

    if (!$created) {
        return response()   
        ->json([
            'message'=>'user is not created.'
        ],400);

    }
    
    $user = User::where('email', $created->email)->first();

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
    
    return response()->json([],Response::HTTP_NO_CONTENT);
});
