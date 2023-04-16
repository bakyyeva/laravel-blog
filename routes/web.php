<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SocialMediaController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\Admin\ArticleCommentController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\EmailController;
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
        Route::get('/', [AdminController::class, 'index'])->name('admin.home');

        Route::get('logs-db', [LogController::class, 'index'])->name('dbLogs');
        Route::get('logs-db/{id}', [LogController::class, 'getLog'])->name('dbLogs.getLog')->whereNumber('id');

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

        Route::get('email-themes', [EmailController::class, 'index'])->name('admin.email-themes.index');
        Route::get('email-themes/create', [EmailController::class, 'create'])->name('admin.email-themes.create');
        Route::post('email-themes/create', [EmailController::class, 'store']);
        Route::get('email-themes/edit', [EmailController::class, 'edit'])->name('admin.email-themes.edit');
        Route::post('email-themes/edit', [EmailController::class, 'update']);
        Route::delete('email-themes/delete', [EmailController::class, 'delete'])->name('admin.email-themes.delete');
        Route::post('email-themes/change-status', [EmailController::class, 'changeStatus'])->name('admin.email-themes.changeStatus');

        Route::get('email-themes/assign-list', [EmailController::class, 'assignList'])->name('admin.email-themes.assign-list');
        Route::get('email-themes/assign-list/show-email', [EmailController::class, 'showEmail'])->name('admin.email-themes.assign.show.email');
        Route::delete('email-themes/assign-list/delete', [EmailController::class, 'assignDelete'])->name('admin.email-themes.assign.delete');
        Route::get('email-themes/assign', [EmailController::class, 'assignShow'])->name('admin.email-themes.assign');
        Route::post('email-themes/assign', [EmailController::class, 'assign']);
        Route::get('email-themes/assign/get-theme', [EmailController::class, 'assignGetTheme'])->name('admin.email-theme.assign.getTheme');
    });

    Route::post('articles/favorite', [ArticleController::class, 'favorite'])->name('article.favorite');
    Route::post('article/comment-favorite', [ArticleCommentController::class, 'favorite'])->name('article.comment.favorite');

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
Route::get('/makaleler', [FrontController::class, 'articleList'])->name('front.articleList');
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

Route::get('/parola-sifirla', [LoginController::class, 'showPasswordReset'])->name('passwordReset');
Route::post('/parola-sifirla', [LoginController::class, 'sendPasswordReset']);
Route::get('/parola-sifirla/{token}', [LoginController::class, 'showPasswordResetConfirm'])->name('passwordResetToken');
Route::post('/parola-sifirla/{token}', [LoginController::class, 'passwordReset']);

Route::get('/auth/verify/{token}', [RegisterController::class, 'verify'])->name('verify.token');
Route::get('/auth/{driver}/callback', [RegisterController::class, 'socialVerify'])->name('socialVerify');
Route::get('/auth/{driver}', [RegisterController::class, 'socialLogin'])->name('socialLogin');

Route::get('/iletisim', [LoginController::class, 'contact'])->name('contact');




