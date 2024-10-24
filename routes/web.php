<?php

use App\Http\Controllers\ApprovedItem;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\Plan\PCalibration;
use App\Http\Controllers\Plan\PContractService;
use App\Http\Controllers\Plan\PInsidewarehouse;
use App\Http\Controllers\Plan\PMaintenance;
use App\Http\Controllers\Plan\PNoserial;
use App\Http\Controllers\Plan\POutsidewarehouse;
use App\Http\Controllers\Plan\PPotential;
use App\Http\Controllers\Plan\PProjectwork;
use App\Http\Controllers\Plan\PRepair;
use App\Http\Controllers\Plan\PReplacement;
use App\Http\Controllers\Report\FinancialReport;
use App\Http\Controllers\select_plan;
use App\Http\Controllers\select_store;
use Illuminate\Support\Facades\Route;

// Export excel Zone
Route::get('/export/{type}', [ExportController::class, 'export'])->name('export.data');

// Generate-PDF Zone
Route::get('/GeneratePDF/{id}/{type}', [PDFController::class, 'generatePDF'])->name('GeneratePDF');
Route::get('/pdf_generate_product/{id}', [PDFController::class, 'pdf_generate_product'])->name('pdf_generate_product');

// Report Zone
Route::get('/financial_report', [FinancialReport::class, 'financial_report'])->name('financial_report');
Route::get('/financial_report/{name_report}', [FinancialReport::class, 'report'])->name('report');

// login
Route::controller(AuthController::class)->group(function () {
    // Route::get('register', 'register')->name('register');
    // Route::post('register', 'registerSave')->name('register.save');

    Route::get('login', 'login')->name('login');
    Route::post('login', 'loginAction')->name('login.action');

    Route::get('logout', 'logout')->middleware('auth')->name('logout');
});

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });
    Route::get('select_plan', [select_plan::class, 'index'])->name('select_plan');
    Route::get('select_store', [select_store::class, 'index'])->name('select_store');

// Approved Zone
    Route::get('select_approved', [ApprovedItem::class, 'index'])->name('select_approved');
    Route::get('approved_items_plans', [ApprovedItem::class, 'approved_items_plans'])->name('approved_items_plans');
    Route::patch('approved_items_plans/{id}', [ApprovedItem::class, 'approved'])->name('approved_items_plans.approved');
    Route::delete('approved_items_plans/{id}', [ApprovedItem::class, 'destroy'])->name('approved_items_plans.destroy');

    Route::get('approved_items_stores', [ApprovedItem::class, 'approved_items_stores'])->name('approved_items_stores');
    Route::patch('approved_items_stores/{id}', [ApprovedItem::class, 'approved_store'])->name('approved_items_stores.approved');
    Route::delete('approved_items_stores/{id}', [ApprovedItem::class, 'destroy_store'])->name('approved_items_stores.destroy');

    Route::get('passed_approved_items', [ApprovedItem::class, 'passed_approved_items'])->name('passed_approved_items');
    Route::get('no_passed_approved_items', [ApprovedItem::class, 'no_passed_approved_items'])->name('no_passed_approved_items');

//
    Route::controller(PMaintenance::class)->prefix('maintenances')->group(function () {
        Route::get('', 'index')->name('maintenances');
        Route::get('create/{EQUP_LINK_NO}/{PLAN_ID}', 'create')->name('maintenances.create');
        Route::post('store', 'store')->name('maintenances.store');
        Route::get('show/{id}', 'show')->name('maintenances.show');
        Route::put('edit/{id}', 'update')->name('maintenances.update');
        Route::delete('destroy/{id}', 'destroy')->name('maintenances.destroy');
        Route::delete('destroy_equip/{id}', 'destroy_equip')->name('maintenances.destroy_equip');
        Route::patch('approved/{id}', 'approved')->name('maintenances.approved');
        Route::patch('update_equip/{id}', 'updateEquip')->name('maintenances.update_equip');
        Route::patch('update_equip_used/{id}', 'updateEquipUsed')->name('maintenances.update_equip_used');
        Route::get('/get-plan-status/{id}', 'getPlanStatus');
        Route::post('/close-plan/update', 'updateClosePlan')->name('maintenances.close_plan');
    });

    Route::controller(PRepair::class)->prefix('repairs')->group(function () {
        Route::get('', 'index')->name('repairs');
        Route::get('create/{EQUP_LINK_NO}/{PLAN_ID}', 'create')->name('repairs.create');
        Route::post('store', 'store')->name('repairs.store');
        Route::get('show/{id}', 'show')->name('repairs.show');
        Route::put('edit/{id}', 'update')->name('repairs.update');
        Route::delete('destroy/{id}', 'destroy')->name('repairs.destroy');
        Route::delete('destroy_equip/{id}', 'destroy_equip')->name('repairs.destroy_equip');
        Route::patch('approved/{id}', 'approved')->name('repairs.approved');
        Route::patch('update_equip/{id}', 'updateEquip')->name('repairs.update_equip');
        Route::patch('update_equip_used/{id}', 'updateEquipUsed')->name('repairs.update_equip_used');
        Route::get('/get-plan-status/{id}', 'getPlanStatus');
        Route::post('/close-plan/update', 'updateClosePlan')->name('repairs.close_plan');

    });
    Route::controller(PContractService::class)->prefix('contractservices')->group(function () {
        Route::get('', 'index')->name('contractservices');
        Route::get('create/{EQUP_LINK_NO}/{PLAN_ID}', 'create')->name('contractservices.create');
        Route::post('store', 'store')->name('contractservices.store');
        Route::get('show/{id}', 'show')->name('contractservices.show');
        Route::put('edit/{id}', 'update')->name('contractservices.update');
        Route::delete('destroy/{id}', 'destroy')->name('contractservices.destroy');
        Route::delete('destroy_equip/{id}', 'destroy_equip')->name('contractservices.destroy_equip');
        Route::patch('approved/{id}', 'approved')->name('contractservices.approved');
        Route::patch('update_equip/{id}', 'updateEquip')->name('contractservices.update_equip');
        Route::patch('update_equip_used/{id}/{PLAN_ID}', 'updateEquipUsed')->name('contractservices.update_equip_used');
        Route::get('get-plan-status/{id}', 'getPlanStatus');
        Route::post('addRow/{id}', 'addRow')->name('contractservices.addRow');
        Route::post('/close-plan/update', 'updateClosePlan')->name('contractservices.close_plan');
    });
    Route::controller(PCalibration::class)->prefix('calibrations')->group(function () {
        Route::get('', 'index')->name('calibrations');
        Route::get('create/{EQUP_LINK_NO}/{PLAN_ID}', 'create')->name('calibrations.create');
        Route::post('store', 'store')->name('calibrations.store');
        Route::get('show/{id}', 'show')->name('calibrations.show');
        Route::put('edit/{id}', 'update')->name('calibrations.update');
        Route::delete('destroy/{id}', 'destroy')->name('calibrations.destroy');
        Route::delete('destroy_equip/{id}', 'destroy_equip')->name('calibrations.destroy_equip');
        Route::patch('approved/{id}', 'approved')->name('calibrations.approved');
        Route::patch('update_equip/{id}', 'updateEquip')->name('calibrations.update_equip');
        Route::patch('update_equip_used/{id}/{PLAN_ID}', 'updateEquipUsed')->name('calibrations.update_equip_used');
        Route::get('/get-plan-status/{id}', 'getPlanStatus');
        Route::post('addRow/{id}', 'addRow')->name('calibrations.addRow');
        Route::post('/close-plan/update', 'updateClosePlan')->name('calibrations.close_plan');

    });
    Route::controller(PPotential::class)->prefix('potentials')->group(function () {
        Route::get('', 'index')->name('potentials');
        Route::get('create/{EQUP_LINK_NO}/{PLAN_ID}', 'create')->name('potentials.create');
        Route::post('store', 'store')->name('potentials.store');
        Route::get('show/{id}', 'show')->name('potentials.show');
        Route::put('edit/{id}', 'update')->name('potentials.update');
        Route::delete('destroy/{id}', 'destroy')->name('potentials.destroy');
        Route::delete('destroy_equip/{id}', 'destroy_equip')->name('potentials.destroy_equip');
        Route::patch('approved/{id}', 'approved')->name('potentials.approved');
        Route::patch('update_equip/{id}', 'updateEquip')->name('potentials.update_equip');
        Route::patch('update_equip_used/{id}/{PLAN_ID}', 'updateEquipUsed')->name('potentials.update_equip_used');
        Route::get('/get-plan-status/{id}', 'getPlanStatus');
        Route::post('addRow/{id}', 'addRow')->name('potentials.addRow');
        Route::post('/close-plan/update', 'updateClosePlan')->name('potentials.close_plan');

    });
    Route::controller(PReplacement::class)->prefix('replacements')->group(function () {
        Route::get('', 'index')->name('replacements');
        Route::get('create/{EQUP_LINK_NO}/{PLAN_ID}', 'create')->name('replacements.create');
        Route::post('store', 'store')->name('replacements.store');
        Route::get('show/{id}', 'show')->name('replacements.show');
        Route::put('edit/{id}', 'update')->name('replacements.update');
        Route::delete('destroy/{id}', 'destroy')->name('replacements.destroy');
        Route::delete('destroy_equip/{id}', 'destroy_equip')->name('replacements.destroy_equip');
        Route::patch('approved/{id}', 'approved')->name('replacements.approved');
        Route::patch('update_equip/{id}', 'updateEquip')->name('replacements.update_equip');
        Route::patch('update_equip_used/{id}', 'updateEquipUsed')->name('replacements.update_equip_used');
        Route::get('/get-plan-status/{id}', 'getPlanStatus');
        Route::post('/close-plan/update', 'updateClosePlan')->name('replacements.close_plan');

    });
    Route::controller(PNoserial::class)->prefix('noserials')->group(function () {
        Route::get('', 'index')->name('noserials');
        Route::get('create/{EQUP_LINK_NO}/{PLAN_ID}', 'create')->name('noserials.create');
        Route::post('store', 'store')->name('noserials.store');
        Route::get('show/{id}', 'show')->name('noserials.show');
        Route::put('edit/{id}', 'update')->name('noserials.update');
        Route::delete('destroy/{id}', 'destroy')->name('noserials.destroy');
        Route::delete('destroy_equip/{id}', 'destroy_equip')->name('noserials.destroy_equip');
        Route::patch('approved/{id}', 'approved')->name('noserials.approved');
        Route::patch('update_equip/{id}', 'updateEquip')->name('noserials.update_equip');
        Route::patch('update_equip_used/{id}/{PLAN_ID}', 'updateEquipUsed')->name('noserials.update_equip_used');
        Route::get('/get-plan-status/{id}', 'getPlanStatus');
        Route::post('addRow/{id}', 'addRow')->name('noserials.addRow');
        Route::post('/close-plan/update', 'updateClosePlan')->name('noserials.close_plan');

    });
    Route::controller(POutsidewarehouse::class)->prefix('outsidewarehouses')->group(function () {
        Route::get('', 'index')->name('outsidewarehouses');
        Route::get('create/{PRODCT_LINK_NO}/{PLAN_ID}', 'create')->name('outsidewarehouses.create');
        Route::post('store', 'store')->name('outsidewarehouses.store');
        Route::get('show/{id}', 'show')->name('outsidewarehouses.show');
        Route::put('edit/{id}', 'update')->name('outsidewarehouses.update');
        Route::delete('destroy/{id}', 'destroy')->name('outsidewarehouses.destroy');
        Route::delete('destroy_equip/{id}', 'destroy_equip')->name('outsidewarehouses.destroy_equip');
        Route::patch('approved/{id}', 'approved')->name('outsidewarehouses.approved');
        Route::patch('approved_list_product/{id}', 'approved_list_product')->name('outsidewarehouses.approved_list_product');
        Route::patch('update_equip/{id}', 'updateEquip')->name('outsidewarehouses.update_equip');
        Route::patch('update_equip_used/{id}', 'updateEquipUsed')->name('outsidewarehouses.update_equip_used');
        Route::get('/get-plan-status/{id}', 'getPlanStatus');
        Route::post('/close-plan/update', 'updateClosePlan')->name('outsidewarehouses.close_plan');

    });
    Route::controller(PInsidewarehouse::class)->prefix('insidewarehouses')->group(function () {
        Route::get('', 'index')->name('insidewarehouses');
        Route::get('create/{EQUP_LINK_NO}/{PLAN_ID}', 'create')->name('insidewarehouses.create');
        Route::post('store', 'store')->name('insidewarehouses.store');
        Route::get('show/{id}', 'show')->name('insidewarehouses.show');
        Route::put('edit/{id}', 'update')->name('insidewarehouses.update');
        Route::delete('destroy/{id}', 'destroy')->name('insidewarehouses.destroy');
        Route::delete('destroy_equip/{id}', 'destroy_equip')->name('insidewarehouses.destroy_equip');
        Route::patch('approved/{id}', 'approved')->name('insidewarehouses.approved');
        Route::patch('approved_list_product/{id}', 'approved_list_product')->name('insidewarehouses.approved_list_product');
        Route::patch('update_equip/{id}', 'updateEquip')->name('insidewarehouses.update_equip');
        Route::patch('update_equip_used/{id}', 'updateEquipUsed')->name('insidewarehouses.update_equip_used');
        Route::get('/get-plan-status/{id}', 'getPlanStatus');
        Route::post('/close-plan/update', 'updateClosePlan')->name('insidewarehouses.close_plan');

    });
    Route::controller(PProjectwork::class)->prefix('projectworks')->group(function () {
        Route::get('', 'index')->name('projectworks');
        Route::get('create', 'create')->name('projectworks.create');
        Route::post('store', 'store')->name('projectworks.store');
        Route::get('show/{id}', 'show')->name('projectworks.show');
        Route::get('edit/{id}', 'edit')->name('projectworks.edit');
        Route::put('edit/{id}', 'update')->name('projectworks.update');
        Route::delete('destroy/{id}', 'destroy')->name('projectworks.destroy');
    });
});
