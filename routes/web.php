<?php

use App\Http\Controllers\SurveyStartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SurveyDimensionController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::get('/survey', [SurveyStartController::class, 'show'])->name('survey.start');
Route::post('/survey', [SurveyStartController::class, 'store'])->name('survey.start.submit');

Route::get('/survey/form', function () {
    return "Survey questions page goes here.";
})->name('survey.form');

Route::get('/survey/dimension/1', [SurveyDimensionController::class, 'decisionMaking'])
    ->name('survey.dimension1');

Route::post('/survey/dimension/1', [SurveyDimensionController::class, 'saveDecisionMaking'])
    ->name('survey.dimension1.save');

Route::get('/survey/dimension/2', [SurveyDimensionController::class, 'teamworkRespect'])
    ->name('survey.dimension2');

Route::post('/survey/dimension/2', [SurveyDimensionController::class, 'saveTeamworkRespect'])
->name('survey.dimension2.save');

Route::get('/survey/dimension/3', [SurveyDimensionController::class, 'learningSkills'])
    ->name('survey.dimension3');

Route::post('/survey/dimension/3', [SurveyDimensionController::class, 'saveLearningSkills'])
    ->name('survey.dimension3.save');


Route::get('/survey/dimension/4', [SurveyDimensionController::class, 'responsibility'])
    ->name('survey.dimension4');

Route::post('/survey/dimension/4', [SurveyDimensionController::class, 'saveResponsibility'])
    ->name('survey.dimension4.save');

Route::get('/survey/dimension/5', [SurveyDimensionController::class, 'flexibleThinking'])
    ->name('survey.dimension5');

Route::post('/survey/dimension/5', [SurveyDimensionController::class, 'saveFlexibleThinking'])
    ->name('survey.dimension5.save');

Route::get('/survey/dimension/6', [SurveyDimensionController::class, 'criticalCreativeThinking'])
    ->name('survey.dimension6');

Route::post('/survey/dimension/6', [SurveyDimensionController::class, 'saveCriticalCreativeThinking'])
    ->name('survey.dimension6.save');


Route::get('/survey/thank-you/{submission}', [SurveyDimensionController::class, 'thankYou'])
    ->name('survey.thankyou');

Route::prefix('admin')->name('admin.')->group(function () {

    // Auth routes
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Protected admin routes
    Route::middleware('admin.session')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/submissions', [AdminDashboardController::class, 'submissions'])->name('submissions');
        Route::get('/submissions/{submission}', [AdminDashboardController::class, 'showSubmission'])->name('submissions.show');
    });
});

