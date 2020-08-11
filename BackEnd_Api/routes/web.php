<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/api/register', 'UserController@register');
Route::post('/api/login', 'UserController@login');
Route::put('/api/updateuser/{updateuser}', 'UserController@update');
Route::post('/api/forgetpass', 'UserController@forgetpass');
Route::get('/api/activeframes', 'ActiveFramesController@index');
Route::put('/api/activeframes/{activeframes}', 'ActiveFramesController@update');
Route::post('/api/probability', 'ProbabilityController@calculateProbability');
Route::get('api/listUbication/{Ubication}','UbicationController@indexFromId');
Route::resource('/api/ubication', 'UbicationController');
Route::resource('/api/monthmaster', 'MonthMasterController');
Route::resource('/api/locationmaster', 'LocationMasterController');
Route::resource('/api/genderagemaster', 'GenderAgeMasterController');
Route::resource('/api/weekhourmaster', 'WeekHourMasterController');
Route::resource('/api/datamaster', 'DataMasterController');
Route::resource('/api/IBOCA', 'IBOCAController');
Route::resource('/api/COVID', 'COVIDController');
Route::resource('/api/masterCOVID', 'MasterCOVIDController');
Route::get('/api/loadQuestions', 'InformationQuestionController@LoadQuestions');
Route::post('/api/saveQuestions', 'InformationQuestionController@SaveQuestions');
Route::put('/api/updateQuestions/{updateQuestions}', 'InformationQuestionController@updateQuestions');
Route::get('/api/serachanswers/{serachAnswers}','InformationQuestionController@searchAnswersHead');
Route::get('/api/findAnswers/{findAnswers}','InformationQuestionController@findAnswersDetail');
Route::get('/api/findLocationIBOCA/{findLocation}','InformationQuestionController@findLocationIboca');
Route::get('/api/comments', 'CommentsController@getComments');
Route::put('/api/comments/{comments}', 'CommentsController@modify');
Route::post('/api/comments', 'CommentsController@insert');
