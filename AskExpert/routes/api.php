<?php

use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\BookController;
use App\Models\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ExpertController;
use App\Http\Controllers\Api\ConsultationController;
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


//['middleware' => 'auth:api'], 

//Route::get('get-main-categories', [CategoriesController::class, 'index']);
Route::group(['middleware' => ['api', 'checkpassword']],function () {

    Route::post('login', [AuthController::class, 'login']);

    Route::post('expert-register', [AuthController::class, 'register_expert']);
    Route::post('user-register', [AuthController::class, 'register_user']);

    Route::group(['middleware' => ['auth.guard:user-api']],function () {

        Route::post('get-main-categories', [CategoriesController::class, 'index']);
        Route::post('get-category-id', [CategoriesController::class, 'get_category_by_id']);
        Route::post('search-category', [CategoriesController::class, 'search_category']);
        
        Route::post('get-questions-category', [ConsultationController::class, 'get_questions_by_category']);
        Route::post('get-all-questions', [ConsultationController::class, 'index']);
        Route::post('ask-question', [ConsultationController::class, 'create_question']);
        Route::post('show-consultation', [ConsultationController::class, 'show_consultation']);
        Route::post('search-consultation', [ConsultationController::class, 'search_consultation']);

        Route::post('get-all-experts', [ExpertController::class, 'index']);
        Route::post('get-experts-category', [ExpertController::class, 'get_experts_by_category']);
        Route::post('show-expert',[ExpertController::class, 'show_expert']);
        Route::post('search-expert', [ExpertController::class, 'search_expert']);

        Route::post('show-appointments', [AppointmentController::class, 'index']);
        Route::post('get-appointment', [AppointmentController::class, 'get_appointment']);

        Route::post('create-book', [BookController::class, 'create_book']);
        Route::post('user-books', [BookController::class, 'user_books']);

        Route::post('logout', [AuthController::class, 'logout']);

        Route::group(['middleware' => ['is.expert']],function () {

            Route::post('give-answer', [ConsultationController::class, 'create_answer']);

            Route::post('create-appointment', [AppointmentController::class, 'create_appointment']);
            Route::post('update-appointment', [AppointmentController::class, 'update_appointment']);
            Route::post('delete-appointment/{id}', [AppointmentController::class, 'delete_appointment']);

            Route::post('expert-books', [BookController::class, 'expert_books']);
        });
    });
});


