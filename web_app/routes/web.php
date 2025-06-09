<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventDetailController;
use App\Http\Controllers\AddEventController;
use App\Http\Controllers\EditEventController;
use App\Http\Controllers\DeleteEventController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CategoryDetailController;
use App\Http\Controllers\AddCategoryController;
use App\Http\Controllers\EditCategoryController;
use App\Http\Controllers\DeleteCategoryController;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/event/{eventId}', [EventDetailController::class, 'eventDetailView'])->name('event-detail.view');
Route::get('/category/{categoryId}', [CategoryDetailController::class, 'categoryDetailView'])->name('category-detail.view');

/* Event creation routes */
Route::get('/newEvent', [AddEventController::class, 'newEventView'])->name('new-event.view')->middleware('auth');
Route::post('/newEvent', [AddEventController::class, 'newEventSubmit'])->name('new-event.submit')->middleware('auth');
Route::get('/newEvent/confirm', [AddEventController::class, 'newEventConfirmView'])->name('new-event-confirm.view')->middleware('auth');
Route::post('/newEvent/confirm', [AddEventController::class, 'newEventConfirmSubmit'])->name('new-event-confirm.submit')->middleware('auth');
Route::get('/newEvent/cancel', [AddEventController::class, 'newEventCancel'])->name('new-event.cancel')->middleware('auth');

/* Event editing routes */
Route::get('/editEvent/cancel', [EditEventController::class, 'editEventCancel'])->name('edit-event.cancel')->middleware('auth');
Route::get('/editEvent/{eventId}', [EditEventController::class, 'editEventView'])->name('edit-event.view')->middleware('auth');
Route::post('/editEvent/{eventId}', [EditEventController::class, 'editEventSubmit'])->name('edit-event.submit')->middleware('auth');
Route::get('/editEvent/confirm/{eventId}', [EditEventController::class, 'editEventConfirmView'])->name('edit-event-confirm.view')->middleware('auth');
Route::post('/editEvent/confirm/{eventId}', [EditEventController::class, 'editEventConfirmSubmit'])->name('edit-event-confirm.submit')->middleware('auth');

/* Event deletion routes */
Route::get('/deleteEvent/{eventId}', [DeleteEventController::class, 'deleteEventView'])->name('delete-event.view')->middleware('auth');
Route::post('/deleteEvent/{eventId}', [DeleteEventController::class, 'deleteEventSubmit'])->name('delete-event.submit')->middleware('auth');

/* Category creation routes */
Route::get('/newCategory', [AddCategoryController::class, 'newCategoryView'])->name('new-category.view')->middleware('auth');
Route::post('/newCategory', [AddCategoryController::class, 'newCategorySubmit'])->name('new-category.submit')->middleware('auth');

/* Category editing routes */
Route::get('/editCategory/{categoryId}', [EditCategoryController::class, 'editCategoryView'])->name('edit-category.view')->middleware('auth');
Route::post('/editCategory/{categoryId}', [EditCategoryController::class, 'editCategorySubmit'])->name('edit-category.submit')->middleware('auth');

/* Category deletion routes */
Route::get('/deleteCategory/{categoryId}', [DeleteCategoryController::class, 'deleteCategoryView'])->name('delete-category.view')->middleware('auth');
Route::post('/deleteCategory/{categoryId}', [DeleteCategoryController::class, 'deleteCategorySubmit'])->name('delete-category.submit')->middleware('auth');

/* Category list routes */
Route::get('/categories', [CategoryDetailController::class, 'categoryList'])->name('category-list.view')->middleware('auth');

Auth::routes(['register' => false]);

Route::get('/search', [SearchController::class, 'searchView'])->name('search');
Route::get('/search/advanced', [SearchController::class, 'searchAdvancedView'])->name('search-advanced.view');
