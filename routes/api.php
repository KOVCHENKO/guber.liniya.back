<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/register', 'Auth\LoginController@register');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', 'Auth\LoginController@login');

Route::group(['middleware' => 'jwt.auth'], function () {

});

Route::get('/get_user', 'Common\UserController@getUser');
Route::get('/get_cabinets/{user_id}', 'Common\DesktopController@getCabinets');

Route::prefix('/organizations/')->namespace('Functional')->group(function(){
    Route::get('all', 'OrganizationController@getAll');
    Route::post('create', 'OrganizationController@create');
    Route::post('update/{id}', 'OrganizationController@update');
    Route::get('get_by_id/{id}', 'OrganizationController@getById');
    Route::get('delete/{id}', 'OrganizationController@delete');
    Route::get('bind_problem_type_to_organization/{organization_id}/{problem_id}/{status}', 'OrganizationController@bindProblemTypeToOrganization');
});

Route::prefix('/problem_types/')->namespace('Functional')->group(function(){
    Route::get('all', 'ProblemTypeController@getAll');
    Route::post('create', 'ProblemTypeController@create');
    Route::get('get_by_id/{id}', 'ProblemTypeController@getByid');
    Route::get('all_with_problems/{organization_id}', 'ProblemTypeController@getAllWithQuestions');
});

Route::prefix('/claims/')->namespace('Functional')->group(function(){
    Route::get('all', 'ClaimController@getAll');
    Route::post('create', 'ClaimController@create');
});

Route::post('/problems/create', 'Functional\ProblemController@create');

