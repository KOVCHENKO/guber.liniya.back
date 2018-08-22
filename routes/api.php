<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', 'Auth\LoginController@register');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', 'Auth\LoginController@login');

Route::get('/get_user', 'Common\UserController@getUser');
Route::get('/get_cabinets/{user_id}', 'Common\DesktopController@getCabinets');

Route::group(['middleware' => 'jwt.auth'], function () {

    Route::prefix('/organizations/')->namespace('Functional')->middleware('role:admin')->group(function(){
        Route::get('all', 'OrganizationController@getAll');
        Route::post('create', 'OrganizationController@create');
        Route::post('update/{id}', 'OrganizationController@update');
        Route::get('get_by_id/{id}', 'OrganizationController@getById');
        Route::get('delete/{id}', 'OrganizationController@delete');
        Route::get('bind_problem_type_to_organization/{organization_id}/{problem_id}/{status}', 'OrganizationController@bindProblemTypeToOrganization');
    });

    Route::prefix('/problem_types/')->namespace('Functional')->middleware('role:admin')->group(function(){
        Route::post('create', 'ProblemTypeController@create');
        Route::get('get_by_id/{id}', 'ProblemTypeController@getByid');
        Route::get('all_with_problems/{organization_id}', 'ProblemTypeController@getAllWithQuestions');
    });
    Route::get('/problem_types/all', 'Functional\ProblemTypeController@getAll')
        ->middleware('role:admin,dispatcher');


    Route::prefix('/claims/')->namespace('Functional')->group(function(){
        Route::get('all', 'ClaimController@getAll')->middleware('role:dispatcher');
        Route::post('create', 'ClaimController@create')->middleware('role:dispatcher');
    });

    Route::prefix('/specialists/')->namespace('Functional')->middleware('role:specialist')->group(function() {
       Route::get('get_specialists_of_organization/{organization_id}', 'SpecialistController@getSpecialistsOfOrganization');
       Route::post('create_specialist/{organization_id}', 'SpecialistController@createSpecialist');
    });

    Route::post('/problems/create', 'Functional\ProblemController@create')->middleware('role:admin');

});

Route::get('/claims/export', 'Analytics\ClaimExportController@export');
