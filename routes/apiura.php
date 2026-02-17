<?php

use Apiura\Http\Controllers\ApiuraController;
use Apiura\Http\Controllers\ApiuraModuleController;
use Apiura\Http\Controllers\SavedApiFlowController;
use Apiura\Http\Controllers\SavedApiRequestCommentController;
use Apiura\Http\Controllers\SavedApiRequestController;
use Apiura\Http\Middleware\EnsureApiuraAccess;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| APIura Routes
|--------------------------------------------------------------------------
|
| These routes are loaded automatically by the APIura service provider.
|
*/

Route::middleware('web')
    ->prefix('apiura')
    ->middleware(array_filter([
        EnsureApiuraAccess::class,
        'throttle:apiura',
        config('apiura.require_auth') ? 'auth' : null,
    ]))
    ->group(function () {
        // Main page
        Route::get('/', [ApiuraController::class, 'index'])->name('apiura');
        Route::get('/db-schema', [ApiuraController::class, 'dbSchema'])->name('apiura.db-schema');
        Route::get('/export-md', [ApiuraController::class, 'exportMarkdown'])->name('apiura.export-md');
        Route::get('/export-openapi/{mode}', [ApiuraController::class, 'exportOpenApi'])
            ->where('mode', 'with-cases|with-examples|clean')
            ->name('apiura.export-openapi');

        // Telescope integration (optional - works if Telescope is installed)
        Route::get('/telescope', [ApiuraController::class, 'telescopeEntries'])->name('apiura.telescope');
        Route::get('/telescope/{uuid}', [ApiuraController::class, 'telescopeEntry'])->name('apiura.telescope.entry');

        // Saved Requests
        Route::get('/saved-requests', [SavedApiRequestController::class, 'index'])->name('apiura.saved-requests.index');
        Route::post('/saved-requests', [SavedApiRequestController::class, 'store'])->name('apiura.saved-requests.store');
        Route::get('/saved-requests/{savedRequest}', [SavedApiRequestController::class, 'show'])->name('apiura.saved-requests.show');
        Route::put('/saved-requests/{savedRequest}', [SavedApiRequestController::class, 'update'])->name('apiura.saved-requests.update');
        Route::delete('/saved-requests/{savedRequest}', [SavedApiRequestController::class, 'destroy'])->name('apiura.saved-requests.destroy');

        // Saved Request Comments
        Route::get('/saved-requests/{savedRequest}/comments', [SavedApiRequestCommentController::class, 'index'])->name('apiura.comments.index');
        Route::post('/saved-requests/{savedRequest}/comments', [SavedApiRequestCommentController::class, 'store'])->name('apiura.comments.store');
        Route::put('/saved-requests/{savedRequest}/comments/{comment}', [SavedApiRequestCommentController::class, 'update'])->name('apiura.comments.update');
        Route::delete('/saved-requests/{savedRequest}/comments/{comment}', [SavedApiRequestCommentController::class, 'destroy'])->name('apiura.comments.destroy');

        // Modules (static routes first, before {module} param)
        Route::get('/modules', [ApiuraModuleController::class, 'index'])->name('apiura.modules.index');
        Route::post('/modules', [ApiuraModuleController::class, 'store'])->name('apiura.modules.store');
        Route::post('/modules/reorder', [ApiuraModuleController::class, 'reorder'])->name('apiura.modules.reorder');
        Route::post('/modules/move-items', [ApiuraModuleController::class, 'moveItems'])->name('apiura.modules.move-items');
        Route::post('/modules/import-preview', [ApiuraModuleController::class, 'importPreview'])->name('apiura.modules.import-preview');
        Route::post('/modules/import-execute', [ApiuraModuleController::class, 'importExecute'])->name('apiura.modules.import-execute');
        Route::get('/modules/{module}', [ApiuraModuleController::class, 'show'])->name('apiura.modules.show');
        Route::put('/modules/{module}', [ApiuraModuleController::class, 'update'])->name('apiura.modules.update');
        Route::delete('/modules/{module}', [ApiuraModuleController::class, 'destroy'])->name('apiura.modules.destroy');

        // Saved Flows
        Route::get('/flows', [SavedApiFlowController::class, 'index'])->name('apiura.flows.index');
        Route::post('/flows', [SavedApiFlowController::class, 'store'])->name('apiura.flows.store');
        Route::post('/flows/bulk', [SavedApiFlowController::class, 'bulkStore'])->name('apiura.flows.bulk-store');
        Route::post('/flows/bulk-delete', [SavedApiFlowController::class, 'bulkDestroy'])->name('apiura.flows.bulk-destroy');
        Route::get('/flows/{flow}', [SavedApiFlowController::class, 'show'])->name('apiura.flows.show');
        Route::put('/flows/{flow}', [SavedApiFlowController::class, 'update'])->name('apiura.flows.update');
        Route::delete('/flows/{flow}', [SavedApiFlowController::class, 'destroy'])->name('apiura.flows.destroy');
    });
