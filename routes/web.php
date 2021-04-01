<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\AblySesionsController;

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

Route::get('/',[HomeController::class, 'screen'])->name('screen');


Auth::routes();

Route::get('/auth/facebook', [SocialAuthController::class, 'getFacebook']);
Route::get('/auth/facebook/callback', [SocialAuthController::class,'cbFacebook']);
Route::get('/auth/google', [SocialAuthController::class,'getGoogle']);
Route::get('/auth/google/callback', [SocialAuthController::class, 'cbGoogle']);

Route::get('/play-machine-index', [GameController::class, 'playMachineIndex'])->name('playmachine.index');

Route::get('/play-carlos', function () {
    return view('playmachine-carlos');
});


Route::post('/play-without-auth', [GameController::class, 'playWithoutAuth'])->name('play.without.auth');
Route::post('/validate-move-screen', [GameController::class,'validateMove'])->name('validate.move.screen');
Route::post('/move-machine-screen', [GameController::class, 'moveMachine'])->name('move.machine.screen');
Route::post('/game-end-machine-screen', [GameController::class,'endingGameMachineScreen'])->name('game.end.machine.screen');


Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/perfil', [PerfilController::class,'index'])->name('index_perfil');
    Route::post('/changepassword', [PerfilController::class,'changePassword'])->name('change_password');
    Route::post('/updateperfil', [PerfilController::class,'updatePerfil'])->name('update_perfil');
    Route::post('/deleteaccount', [PerfilController::class,'deleteAccount'])->name('delete_account');

    Route::get('/playmachine', [GameController::class,'playMachine'])->name('play_machine');
    Route::post('/movemachine', [GameController::class,'moveMachine'])->name('move_machine');
    Route::post('/getfirstmove', [GameController::class,'getFirstMove'])->name('get_first_move');
    Route::post('/gameendmachine', [GameController::class,'endingGameMachine'])->name('gameendmachine');
    Route::post('/surrendermachine', [GameController::class,'surrenderMachine'])->name('surrendermachine');
    Route::post('/empatemachine', [GameController::class,'empateGameMachine'])->name('empatemachine');

    Route::get('/authhallwait', [AblySesionsController::class, 'authAblyNormal'])->name('authhallwait');
    Route::get('/playgame', [AblySesionsController::class, 'playInitial'])->name('playgame');
    Route::get('/auth-ably', [AblySesionsController::class, 'authAbly'])->name('authably');
    Route::get('/get-channel', [AblySesionsController::class, 'getNameChannel'])->name('getchannel');
    Route::get('/find-player', [AblySesionsController::class, 'findPlayer']);

    Route::post('/validatemove', [GameController::class, 'validateMove'])->name('validatemove');
    Route::post('/gameend', [GameController::class, 'endingGame'])->name('gameend');
    Route::post('/surrender', [GameController::class, 'surrender'])->name('surrender');
    Route::post('/empate', [GameController::class, 'empateGame'])->name('empate');
    Route::post('/playagain', [GameController::class, 'playAgain'])->name('playagain');
    Route::post('/get-game', [GameController::class, 'getGame'])->name('getgame');
    Route::post('/winbyleave', [GameController::class, 'winByLeave'])->name('winbyleave');
    Route::post('/checkgame', [AblySesionsController::class, 'checkGame'])->name('checkgame');
    Route::post('/creategamechallenge', [GameController::class, 'createGameChallenge'])->name('creategamechallenge');

    Route::prefix('panel')->group(function () {
        Route::group(['middleware' => 'administrador'], function () {
            Route::get('/', 'Panel\GeneralController@indexPanel')->name('panel');

            // Route::get('/', 'Panel\GeneralController@indexPanel')->name('panel');

            Route::get('/cabezotes', 'Panel\CabezoteController@list')->name('cabezotes');
            Route::get('/cabezotes/all', 'Panel\CabezoteController@alljson')->name('cabezotes_all');
            Route::post('/cabezotes/order', 'Panel\CabezoteController@order')->name('cabezotes_order');
            Route::get('/cabezotes/add', 'Panel\CabezoteController@add')->name('cabezotes_add');
            Route::post('/cabezotes/create', 'Panel\CabezoteController@create')->name('cabezotes_create');
            Route::get('/cabezotes/edit/{id}', 'Panel\CabezoteController@edit')->name('cabezotes_edit')->where('id', '[0-9]+');
            Route::post('/cabezotes/update', 'Panel\CabezoteController@update')->name('cabezotes_update');
            Route::post('/cabezotes/delete', 'Panel\CabezoteController@delete')->name('cabezotes_delete');

            Route::get('/distribuciones', 'Panel\DistribucionesController@list')->name('distri');
            Route::get('/distribuciones/all', 'Panel\DistribucionesController@alljson')->name('distri_all');
            Route::get('/distribuciones/add', 'Panel\DistribucionesController@add')->name('distri_add');
            Route::post('/distribuciones/create', 'Panel\DistribucionesController@create')->name('distri_create');
            Route::get('/distribuciones/edit/{id}', 'Panel\DistribucionesController@edit')->name('distri_edit')->where('id', '[0-9]+');
            Route::post('/distribuciones/update', 'Panel\DistribucionesController@update')->name('distri_update');
            Route::post('/distribuciones/delete', 'Panel\DistribucionesController@delete')->name('distri_delete');

            Route::get('/usuarios', 'Panel\UserController@list')->name('usuarios');
            Route::get('/usuarios/all', 'Panel\UserController@alljson')->name('usuarios_all');
            Route::get('/usuarios/show', 'Panel\UserController@show')->name('usuarios_show');

            Route::get('/editindex', 'HomeController@indexEdit')->name('index_edit');
            Route::post('/editindex/update', 'HomeController@saveEditorial')->name('save_editorial');
            Route::post('/editindex/default', 'HomeController@defaultEditorial')->name('default_editorial');
            Route::post('/editindex/reset', 'HomeController@resetEditorial')->name('reset_editorial');

            Route::get('/boletin', 'BoletinController@list')->name('boletin');
            Route::get('/boletin/all', 'BoletinController@alljson')->name('boletin_all');

            Route::get('/analytics', 'CpanelController@indexAnalytics')->name('analytics');
            Route::get('/analytics/iframe', 'CpanelController@iframeAnalytics')->name('analytics_iframe');
        });
    });
});



/***********************************/
// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');
//
// require __DIR__.'/auth.php';
