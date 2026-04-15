<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Modules\AccommodationController;
use App\Http\Controllers\Modules\CateringController;
use App\Http\Controllers\Modules\CommunicationController;
use App\Http\Controllers\Modules\ExhibitorController;
use App\Http\Controllers\Modules\MobilityController;
use App\Http\Controllers\Modules\ProcurementController;
use App\Http\Controllers\Modules\SocialController;
use App\Http\Controllers\Modules\TechnologyController;
use App\Http\Controllers\Modules\TvProductionController;
use App\Http\Controllers\Modules\VenueController;
use App\Http\Controllers\Organization\BillingController;
use App\Http\Controllers\Organization\OrgSettingsController;
use App\Http\Controllers\Organization\OrgUserController;
use App\Http\Controllers\Reports\CarbonReportController;
use App\Http\Controllers\Reports\ChecklistController;
use App\Http\Controllers\Reports\SummaryReportController;
use App\Http\Controllers\Reports\UZ62ReportController;
use App\Http\Controllers\ScoreController;
use Illuminate\Support\Facades\Route;

// Public
Route::get('/', fn () => view('welcome'))->name('home');
Route::get('/pricing', fn () => view('pricing'))->name('pricing');

// Auth (guests only)
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

Route::post('/logout', [LoginController::class, 'destroy'])->name('logout')->middleware('auth');

// Authenticated + tenant-scoped
Route::middleware(['auth', 'tenant'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Organization
    Route::prefix('organization')->name('organization.')->group(function () {
        Route::get('/settings', [OrgSettingsController::class, 'edit'])->name('settings');
        Route::put('/settings', [OrgSettingsController::class, 'update'])->name('settings.update');
        Route::get('/users', [OrgUserController::class, 'index'])->name('users');
        Route::post('/users/invite', [OrgUserController::class, 'invite'])->name('users.invite');
        Route::delete('/users/{user}', [OrgUserController::class, 'destroy'])->name('users.destroy');
        Route::get('/billing', [BillingController::class, 'index'])->name('billing');
    });

    // Events
    Route::prefix('events')->name('events.')->group(function () {
        Route::get('/', [EventController::class, 'index'])->name('index');
        Route::get('/create', [EventController::class, 'create'])->name('create');
        Route::post('/', [EventController::class, 'store'])->name('store');
        Route::get('/{event}', [EventController::class, 'show'])->name('show');
        Route::get('/{event}/edit', [EventController::class, 'edit'])->name('edit');
        Route::put('/{event}', [EventController::class, 'update'])->name('update');
        Route::delete('/{event}', [EventController::class, 'destroy'])->name('destroy');

        // Modules
        Route::prefix('/{event}/modules')->name('modules.')->group(function () {
            Route::get('/mobility', [MobilityController::class, 'edit'])->name('mobility');
            Route::put('/mobility', [MobilityController::class, 'update'])->name('mobility.update');

            Route::get('/accommodation', [AccommodationController::class, 'index'])->name('accommodation');
            Route::post('/accommodation', [AccommodationController::class, 'store'])->name('accommodation.store');
            Route::put('/accommodation/{accommodation}', [AccommodationController::class, 'update'])->name('accommodation.update');
            Route::delete('/accommodation/{accommodation}', [AccommodationController::class, 'destroy'])->name('accommodation.destroy');

            Route::get('/venue', [VenueController::class, 'edit'])->name('venue');
            Route::put('/venue', [VenueController::class, 'update'])->name('venue.update');

            Route::get('/procurement', [ProcurementController::class, 'edit'])->name('procurement');
            Route::put('/procurement', [ProcurementController::class, 'update'])->name('procurement.update');

            Route::get('/exhibitors', [ExhibitorController::class, 'edit'])->name('exhibitors');
            Route::put('/exhibitors', [ExhibitorController::class, 'update'])->name('exhibitors.update');

            Route::get('/catering', [CateringController::class, 'edit'])->name('catering');
            Route::put('/catering', [CateringController::class, 'update'])->name('catering.update');

            Route::get('/communication', [CommunicationController::class, 'edit'])->name('communication');
            Route::put('/communication', [CommunicationController::class, 'update'])->name('communication.update');

            Route::get('/social', [SocialController::class, 'edit'])->name('social');
            Route::put('/social', [SocialController::class, 'update'])->name('social.update');

            Route::get('/technology', [TechnologyController::class, 'edit'])->name('technology');
            Route::put('/technology', [TechnologyController::class, 'update'])->name('technology.update');

            Route::get('/tv-production', [TvProductionController::class, 'edit'])->name('tv_production');
            Route::put('/tv-production', [TvProductionController::class, 'update'])->name('tv_production.update');
        });

        // Reports
        Route::prefix('/{event}/reports')->name('reports.')->group(function () {
            Route::get('/carbon', [CarbonReportController::class, 'show'])->name('carbon');
            Route::get('/carbon/pdf', [CarbonReportController::class, 'pdf'])->name('carbon.pdf');
            Route::get('/uz62', [UZ62ReportController::class, 'show'])->name('uz62');
            Route::get('/uz62/pdf', [UZ62ReportController::class, 'pdf'])->name('uz62.pdf');
            Route::get('/checklist/pdf', [ChecklistController::class, 'pdf'])->name('checklist.pdf');
            Route::get('/summary', [SummaryReportController::class, 'show'])->name('summary');
        });

        // Documents
        Route::post('/{event}/documents', [DocumentController::class, 'store'])->name('documents.store');
        Route::delete('/{event}/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');

        // Score recalculation
        Route::post('/{event}/recalculate', [ScoreController::class, 'recalculate'])->name('recalculate');
    });

    // Analytics
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
    Route::get('/analytics/export', [AnalyticsController::class, 'export'])->name('analytics.export');
});
