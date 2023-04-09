<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SocialMediaController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\Admin\ArticleCommentController;
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

Route::prefix('admin')->middleware(["auth", "verified"])->group(function (){

    Route::middleware('is_admin')->group(function (){

        Route::group(['prefix' => 'filemanager'], function () {
            \UniSharp\LaravelFilemanager\Lfm::routes();
        });

        Route::get('/', function () {
            return view('admin.index');
        })->name('admin.home');

        Route::get('articles', [ArticleController::class, 'index'])->name('article.index');
        Route::get('articles/create', [ArticleController::class, 'create'])->name('article.create');
        Route::post('articles/create', [ArticleController::class, 'store']);
        Route::post('articles/change-status', [ArticleController::class, 'changeStatus'])->name('article.change-status');
        Route::post('articles/delete', [ArticleController::class, 'delete'])->name('article.delete');
        Route::get('articles/{id}/edit', [ArticleController::class, 'edit'])->name('article.edit')->whereNumber('id');
        Route::post('articles/{id}/edit', [ArticleController::class, 'update'])->whereNumber('id');

        Route::get('article/pending-approval', [ArticleCommentController::class, 'approvalList'])->name('article.pending-approval');
        Route::get('article/comment-list', [ArticleCommentController::class, 'list'])->name('article.comment.list');
        Route::post('article/pending-approval/change-status', [ArticleCommentController::class, 'changeStatus'])->name('article.pending-approval.change-status');
        Route::delete('article/pending-approval/delete', [ArticleCommentController::class, 'delete'])->name('article.pending-approval.delete');
        Route::post('article/comment-restore', [ArticleCommentController::class, 'restore'])->name('article.comment.restore');

        Route::get('categories', [CategoryController::class, 'index'])->name('category.index');
        Route::get('categories/create', [CategoryController::class, 'create'])->name('category.create');
        Route::post('categories/create', [CategoryController::class, 'store']);
        Route::post('categories/change-status', [CategoryController::class, 'changeStatus'])->name('category.change-status');
        Route::post('categories/change-feature-status', [CategoryController::class, 'changeFeatureStatus'])->name('category.change-feature-status');
        Route::post('categories/delete', [CategoryController::class, 'delete'])->name('category.delete');
        Route::get('categories/{id}/edit', [CategoryController::class, 'edit'])->name('category.edit')->whereNumber('id');
        Route::post('categories/{id}/edit', [CategoryController::class, 'update'])->whereNumber('id');

        Route::get('users', [UserController::class, 'index'])->name('user.index');
        Route::get('users/create', [UserController::class, 'create'])->name('user.create');
        Route::post('users/create', [UserController::class, 'store']);
        Route::get('users/{user:username}/edit', [UserController::class, 'edit'])->name('user.edit');
        Route::post('users/{user:username}/edit', [UserController::class, 'update']);
        Route::post('users/change-status', [UserController::class, 'changeStatus'])->name('user.change-status');
        Route::post('users/change-is-admin', [UserController::class, 'changeIsAdmin'])->name('user.change-is-admin');
        Route::post('users/change-remember-token', [UserController::class, 'changeRememberToken'])->name('user.change-remember-token');
        Route::post('users/delete', [UserController::class, 'delete'])->name('user.delete');
        Route::post('users/restore', [UserController::class, 'restore'])->name('user.restore');

        Route::get('settings', [SettingsController::class, 'show'])->name('settings');
        Route::post('settings', [SettingsController::class, 'update']);
    });

    Route::post('articles/favorite', [ArticleController::class, 'favorite'])->name('article.favorite');
    Route::post('article/comment-favorite', [ArticleCommentController::class, 'favorite'])->name('article.comment.favorite');

    //Missing required parameter for [Route: logs.edit] [URI: logs/{log}/edit] [Missing parameter: log].
//Route::resource("logs", "App\Http\Controllers\Admin\LogController");

    Route::get('logs', [LogController::class, 'index'])->name('log.index');
    Route::get('logs/{id}/edit', [LogController::class, 'edit'])->name('log.edit')->whereNumber('id');
    Route::post('logs/{id}/edit', [LogController::class, 'update'])->whereNumber('id');
    Route::delete('logs/delete', [LogController::class, 'delete'])->name('log.delete');
    Route::get('logs/create', [LogController::class, 'create'])->name('log.create');


    Route::get('comments', [CommentController::class, 'index'])->name('comment.index');
    Route::get('comments/create', [CommentController::class, 'create'])->name('comment.create');
    Route::post('comments/create', [CommentController::class, 'store']);
    Route::delete('comments/delete', [CommentController::class, 'delete'])->name('comment.delete');
    Route::post('comments/change-status', [CommentController::class, 'changeStatus'])->name('comment.change-status');
    Route::get('comments/{id}/edit', [CommentController::class, 'edit'])->name('comment.edit')->whereNumber('id');
    Route::post('comments/{id}/edit', [CommentController::class, 'update'])->whereNumber('id');

    Route::get('social-medias', [SocialMediaController::class, 'index'])->name('social-media.index');
    Route::get('social-medias/create', [SocialMediaController::class, 'create'])->name('social-media.create');
    Route::post('social-medias/create', [SocialMediaController::class, 'store']);
    Route::get('social-medias/{id}/edit', [SocialMediaController::class, 'edit'])->name('social-media.edit')->whereNumber('id');
    Route::post('social-medias/{id}/edit', [SocialMediaController::class, 'update'])->whereNumber('id');
    Route::post('social-medias/change-status', [SocialMediaController::class, 'changeStatus'])->name('social-media.change-status');
    Route::delete('social-medias/delete', [SocialMediaController::class, 'delete'])->name('social-media.delete');


});
Route::get("admin/login", [LoginController::class, "showLogin"])->name("auth.login");
Route::post("admin/login", [LoginController::class, "login"]);



Route::get('/', [FrontController::class, 'home'])->name('home');
Route::get('/kategoriler/{category:slug}', [FrontController::class, 'category'])->name('front.categoryArticles');
Route::get('/yazarlar/{user:username}', [FrontController::class, 'authorArticles'])->name('front.authorArticles');
Route::get('/@{user:username}}/{article:slug}', [FrontController::class, 'articleDetail'])->name('front.articleDetail')->middleware('visitedArticle');
Route::post('{article:id}/makale-yorum', [FrontController::class, 'articleComment'])->name('articleComment');
Route::get('/arama', [FrontController::class, 'search'])->name('front.search');




Route::get("/register", [RegisterController::class, "showRegister"])->name("register");
Route::post("/register", [RegisterController::class, "register"]);

Route::get("/login", [LoginController::class, "showLoginUser"])->name("user.login");
Route::post("/login", [LoginController::class, "login"]);
Route::post("/logout", [LoginController::class, "logout"])->name("logout");

Route::get('/auth/verify/{token}', [RegisterController::class, 'verify'])->name('verify.token');
Route::get('/auth/{driver}/callback', [RegisterController::class, 'socialVerify'])->name('socialVerify');
Route::get('/auth/{driver}', [RegisterController::class, 'socialLogin'])->name('socialLogin');




