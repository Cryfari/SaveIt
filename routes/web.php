<?php

/** @var \Laravel\Lumen\Routing\Router $router */
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
Route::options(
    '/{any:.*}',
    [
        'middleware' => ['CorsMiddleware'],
        function (){
            return response(['status' => 'success']);
        }
    ]
);


$router->post('/register', 'UserController@register');
$router->post('/login', 'UserController@login');
$router->post('/reset-password', 'UserController@resetPassword');
$router->post('/upload', 'UserController@upload');
$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->get('/', function () use ($router) {
        return $router->app->version();
    });
    // $router->get('/user', 'UserController@getAuthenticatedUser');
    // $router->post('/logout', 'UserController@logout');
    // $router->post('/change-password', 'UserController@changePassword');
    $router->post('/add-document', 'DocController@addDocument');
    $router->get('/get-documents', 'DocController@getDocuments');
    //get a document
    $router->get('/get-document/{id}', 'DocController@getaDocument');
    $router->post('/delete-document', 'DocController@deleteDocument');
});




