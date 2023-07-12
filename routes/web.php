<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    DashboardController,
    AccesoController
};

use App\Http\Controllers\Admin\{
    MenuController,
    ModuloController,
    PerfilController,
    UsuarioController,
    PermisoController,
    PermanentesController,
    PermanentesLWController,
    CausaController,
    ComunidadController,
    DonacionController,
    DonadorController,
    RegimenFiscalController
};

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

Route::get('/articulos/api/{id}', [SLTiendasAPIController::class, 'show'])
    ->middleware('guest');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/rutas', function () {
        # rutas/index
        return view('rutas.index')
            ->with('oRutas', Route::getRoutes());
    })->name('rutas');

    Route::get('/l1', function () {
        # rutas/index
        return view('auth.login1');
    })->name('l1');

    Route::get('/l2', function () {
        # rutas/index
        return view('auth.login2');
    })->name('l2');

    Route::get('/l3', function () {
        # rutas/index
        return view('auth.login3');
    })->name('l3');
    Route::get('/l4', function () {
        # rutas/index
        return view('auth.login4');
    })->name('l4');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/{id}/modulos', [DashboardController::class, 'opcionesModulo'])->name('dashboard.opciones-modulo')
        ->where('id', '[0-9]+');

    # admin.*
    Route::prefix('admin')->name('admin.')->group(function () {
        # admin.accesos.*
        Route::resource('accesos', AccesoController::class)->only(['index', 'store']);

        # admin.usuarios.*
        Route::get('usuarios/{id}/cambiar-contrasena', [
            UsuarioController::class, 'cambiarContrasena'
        ])->name('usuarios.cambiar-contrasena');

        Route::post('usuarios/{id}/cambiar-contrasena', [
            UsuarioController::class, 'guardarCambioContrasena'
        ])->name('usuarios.guardar-cambio-contrasena');

        Route::resource('usuarios', UsuarioController::class);
        # admin.modulos.*
        Route::resource('modulos', ModuloController::class);
        # admin.perfiles.*
        Route::resource('perfiles', PerfilController::class)->parameters(['perfiles' => 'oPerfil']);
        # admin.permisos.detalles-puesto
        Route::get('permisos/detalles-puesto/{oPuesto}', [PermisoController::class, 'obtenerDetallesPuesto'])->name('permisos.detalles-puesto');
        # admin.permisos.*
        Route::resource('permisos', PermisoController::class)->except(['create', 'store', 'destroy'])->parameters(['permisos' => 'oPuesto']);
        # admin.menus.*
        Route::match(['get', 'post'], '/menus/filtro', [MenuController::class, 'index'])->name('menus.filtro');
        Route::get('/menus/limpiar/', [MenuController::class, 'limpiar'])->name('menus.limpiar');
        Route::get('/menus/{id}/pagina/', [MenuController::class, 'pagina'])->name('menus.pagina');
        Route::get('/menus/imprimir/', [MenuController::class, 'imprimir'])->name('menus.imprimir');
        Route::get('/menus/xls/', [MenuController::class, 'xls'])->name('menus.xls');
        Route::resource('menus', MenuController::class);
        # admin.permanentes.*
        Route::match(['get', 'post'], '/permanentes/filtro', [PermanentesController::class, 'index'])->name('permanentes.filtro');
        Route::get('/permanentes/limpiar/', [PermanentesController::class, 'limpiar'])->name('permanentes.limpiar');
        Route::get('/permanentes/{id}/pagina/', [PermanentesController::class, 'pagina'])->name('permanentes.pagina');
        Route::get('/permanentes/imprimir/', [PermanentesController::class, 'imprimir'])->name('permanentes.imprimir');
        Route::get('/permanentes/xls/', [PermanentesController::class, 'xls'])->name('permanentes.xls');
        Route::resource('permanentes', PermanentesController::class);
        # admin.permanentes.*
        Route::match(['get', 'post'], '/permanenteslw/filtro', [PermanentesLWController::class, 'index'])->name('permanenteslw.filtro');
        Route::get('/permanenteslw/limpiar/', [PermanentesLWController::class, 'limpiar'])->name('permanenteslw.limpiar');
        Route::get('/permanenteslw/{id}/pagina/', [PermanentesLWController::class, 'pagina'])->name('permanenteslw.pagina');
        Route::get('/permanenteslw/imprimir/', [PermanentesLWController::class, 'imprimir'])->name('permanenteslw.imprimir');
        Route::get('/permanenteslw/xls/', [PermanentesLWController::class, 'xls'])->name('permanenteslw.xls');
        Route::resource('permanenteslw', PermanentesLWController::class);
        # admin.causas.*
        Route::match(['get', 'post'], '/causas/filtro', [CausaController::class, 'index'])->name('causas.filtro');
        Route::get('/causas/limpiar/', [CausaController::class, 'limpiar'])->name('causas.limpiar');
        Route::get('/causas/{id}/pagina/', [CausaController::class, 'pagina'])->name('causas.pagina');
        Route::get('/causas/imprimir/', [CausaController::class, 'imprimir'])->name('causas.imprimir');
        Route::get('/causas/xls/', [CausaController::class, 'xls'])->name('causas.xls');
        Route::resource('causas', CausaController::class);
        # admin.comunidades.*
        Route::match(['get', 'post'], '/comunidades/filtro', [ComunidadController::class, 'index'])->name('comunidades.filtro');
        Route::get('/comunidades/limpiar/', [ComunidadController::class, 'limpiar'])->name('comunidades.limpiar');
        Route::get('/comunidades/{id}/pagina/', [ComunidadController::class, 'pagina'])->name('comunidades.pagina');
        Route::get('/comunidades/imprimir/', [ComunidadController::class, 'imprimir'])->name('comunidades.imprimir');
        Route::get('/comunidades/xls/', [ComunidadController::class, 'xls'])->name('comunidades.xls');
        Route::resource('comunidades', ComunidadController::class);
        # admin.regimenes-fiscales.*
        Route::resource('regimenes-fiscales', RegimenFiscalController::class);
        # admin.donadores.*
        Route::resource('donadores', DonadorController::class);

        # admin.donaciones.*

        Route::match(['get', 'post'], '/donaciones/filtro', [DonacionController::class, 'index'])->name('donaciones.filtro');
        Route::get('/donaciones/limpiar/', [DonacionController::class, 'limpiar'])->name('donaciones.limpiar');
        Route::get('/donaciones/{id}/pagina/', [DonacionController::class, 'pagina'])->name('donaciones.pagina');
        Route::get('/donaciones/imprimir/', [DonacionController::class, 'imprimir'])->name('donaciones.imprimir');
        Route::get('/donaciones/xls/', [DonacionController::class, 'xls'])->name('donaciones.xls');
        Route::resource('donaciones', DonacionController::class);
    });
});

require __DIR__ . '/auth.php';
