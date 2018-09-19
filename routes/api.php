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

    Route::prefix('/organizations/')->namespace('Functional')->group(function(){
        Route::get('all', 'OrganizationController@getAll')->middleware('role:admin,dispatcher,editor,supervisor');
        Route::post('create', 'OrganizationController@create')->middleware('role:admin');
        Route::post('update/{id}', 'OrganizationController@update')->middleware('role:admin');
        Route::get('get_by_id/{id}', 'OrganizationController@getById')->middleware('role:admin');
        Route::get('delete/{id}', 'OrganizationController@delete')->middleware('role:admin');
        Route::get('bind_problem_type_to_organization/{organization_id}/{problem_id}/{status}', 'OrganizationController@bindProblemTypeToOrganization')
            ->middleware('role:admin');
        Route::get('all_claims_of_organization/{organization_id}', 'OrganizationController@getClaimsToOrganization')
            ->middleware('role:specialist');
        Route::get('all_children_organization/{organization_id}', 'OrganizationController@getChildOrganization')
            ->middleware('role:specialist');
    });

    Route::prefix('/problem_types/')->namespace('Functional')->middleware('role:admin')->group(function(){
        Route::post('create', 'ProblemTypeController@create');
        Route::get('get_by_id/{id}', 'ProblemTypeController@getByid');
        Route::get('all_with_problems/{organization_id}', 'ProblemTypeController@getAllWithQuestions');
        Route::post('edit_problem_type/{problem_type_id}', 'ProblemTypeController@editProblemType');
        Route::get('delete/{problem_type_id}', 'ProblemTypeController@delete');
    });
    Route::get('/problem_types/all', 'Functional\ProblemTypeController@getAll')
        ->middleware('role:admin,dispatcher,editor,supervisor');


    Route::prefix('/claims/')->namespace('Functional')->group(function(){
        Route::get('all/{page}/{dispatch_status}', 'ClaimController@getAll')->middleware('role:dispatcher,editor,supervisor');
        Route::post('search', 'ClaimController@search')->middleware('role:dispatcher');
        Route::post('create', 'ClaimController@create')->middleware('role:dispatcher,editor,supervisor');
        Route::get('update_status/{id}/{status}', 'ClaimController@updateStatus')->middleware('role:specialist');
        Route::get('change_organization/{id}/{id_old_organization}/{id_new_organization}', 'ClaimController@changeOrganization')
            ->middleware('role:specialist');
        Route::post('update/{dispatch_status_to_update}', 'ClaimController@update')->middleware('role:dispatcher,editor,supervisor');
        Route::post('get_previous_by_phone', 'ClaimController@getPreviousByPhone')->middleware('role:dispatcher,editor,supervisor');
        Route::get('get_executed_claims', 'ClaimController@getExecutedClaims')->middleware('role:dispatcher,editor,supervisor,communicator');
        Route::get('change_close_status/{claim_id}/{close_status}', 'ClaimController@changeCloseStatus')->middleware('role:communicator');
        Route::get('reassign_rejected_claim/{organization_id}/{claim_id}', 'ClaimController@reassignRejectedClaim')->middleware('role:dispatcher,editor,supervisor');
    });

    Route::prefix('/specialists/')->namespace('Functional')->middleware('role:specialist,admin')->group(function() {
       Route::get('get_specialists_of_organization/{organization_id}', 'SpecialistController@getSpecialistsOfOrganization');
       Route::post('create_specialist/{organization_id}', 'SpecialistController@createSpecialist');
    });

    Route::post('/problems/create', 'Functional\ProblemController@create')->middleware('role:admin');
    Route::get('/problems/delete/{id}', 'Functional\ProblemController@delete')->middleware('role:admin');
    Route::get('/problems/get_by_id/{id}', 'Functional\ProblemController@getById')->middleware('role:admin');
    Route::post('/problems/update/{id}', 'Functional\ProblemController@update')->middleware('role:admin');
    Route::get('/problems/get_organizations_of_problem/{problem_id}', 'Functional\ProblemController@getOrganizationsOfProblem')->middleware('role:dispatcher,supervisor,editor');
    Route::post('/comments/create', 'Functional\CommentController@create')->middleware('role:specialist');

    Route::prefix('/calls')->namespace('Functional')->middleware('role:dispatcher,editor,supervisor')->group(function() {
        Route::get('/all/{page}', 'CallController@getAll');
        Route::get('/mark_call_as_faulty/{call_id}', 'CallController@markCallAsFaulty');
    });

});

Route::post('/calls/get_call', 'Functional\CallController@receive');

Route::get('/claims/export', 'Analytics\ClaimExportController@export');

Route::post('/file/upload/{claim_id}', 'Util\UploadController@uploadSingleFile');
Route::get('/file/download', 'Util\UploadController@downloadFile');
