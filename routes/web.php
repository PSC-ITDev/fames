<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterListController;
use App\Http\Controllers\EvaluationController;
 
Route::get('/', function () {
    return redirect()->route('login');
});

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');


Route::group(['prefix' => '', 'middleware' => 'auth'], function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/newasset', [AssetController::class, 'newasset'])
        ->name('new-asset');



        //Assets 
        Route::post('/saveasset',[MasterListController::class, 'saveAsset']) ->name('saveasset');
        Route::get('/asset-list', [MasterListController::class, 'assetList'])->name('asset-list');

        //Departments 
        Route::post('/savedepartment',[MasterListController::class, 'saveDepartment']) ->name('savedepartment');
        Route::get('/department-list', [MasterListController::class, 'departmentList'])->name('department-list');
        Route::get('/view-department/{deptid}', [MasterListController::class, 'viewDepartment'])->name('view-department');
        Route::post('/save-hierarchy/{deptid}',[MasterListController::class, 'saveHierarchy']) ->name('savehierarchy');

        //Categorys 
        Route::post('/savecategory',[MasterListController::class, 'saveCategory']) ->name('savecategory');
        Route::get('/category-list', [MasterListController::class, 'categoryList'])->name('category-list');

        //Classifications 
        Route::post('/saveclassification',[MasterListController::class, 'saveClassification']) ->name('saveclassification');
        Route::get('/classification-list', [MasterListController::class, 'classificationList'])->name('classification-list');

        //Locations 
        Route::post('/savelocation',[MasterListController::class, 'saveLocation']) ->name('savelocation');
        Route::get('/location-list', [MasterListController::class, 'locationList'])->name('location-list');

        //Evaluation Details
        // Route::post('/submievaluationdetails',[EvaluationController::class, 'saveEvaluation']) ->name('saveevaluation');

        //Evaluation 
        Route::post('/saveevaluation',[EvaluationController::class, 'saveEvaluation']) ->name('saveevaluation');
        Route::get('/evaluationList', [EvaluationController::class, 'evaluationList'])->name('evaluation-list');
        Route::get('/evaluationdetails/{id}', [EvaluationController::class, 'evaluationDetails'])->name('evaluation-details');
        Route::post('/updateevaluationdetail/{eval_id}',[EvaluationController::class, 'updateEvaluationDetails']) ->name('updateevaluation');

        Route::post('/approve/{eval_id}',[EvaluationController::class, 'approveEvaluation']) ->name('approve-evaluation');
        Route::post('/confirm/{eval_id}',[EvaluationController::class, 'confirmEvaluation']) ->name('confirm-evaluation');
        Route::post('/reject/{eval_id}',[EvaluationController::class, 'rejectEvaluation']) ->name('reject-evaluation');




});


require __DIR__.'/auth.php';
