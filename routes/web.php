<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContactListController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\SenderController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\EmailStatusController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Campaigns
    Route::resource('campaigns', CampaignController::class);
    Route::post('/campaigns/{id}/send', [CampaignController::class, 'send'])->name('campaigns.send');

    // Contact Lists
    Route::resource('contact-lists', ContactListController::class);

    // Contacts
    Route::resource('contacts', ContactController::class);

    // Templates
    Route::resource('templates', TemplateController::class);
    Route::post('/templates/{id}/duplicate', [TemplateController::class, 'duplicate'])->name('templates.duplicate');

    // Senders
    Route::resource('senders', SenderController::class);
    Route::post('/senders/{id}/set-default', [SenderController::class, 'setDefault'])->name('senders.set-default');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Images
    Route::get('/image-library', [ImageController::class, 'index'])->name('images.index');
    Route::post('/image-library/upload', [ImageController::class, 'upload'])->name('images.upload');
    Route::delete('/image-library/delete', [ImageController::class, 'destroy'])->name('images.destroy');

    // Email Status
    Route::get('/email-status', [EmailStatusController::class, 'index'])->name('email-status.index');
    Route::get('/email-status/contact/{contactId}', [EmailStatusController::class, 'showContact'])->name('email-status.contact');
    Route::get('/email-status/campaign/{campaignId}', [EmailStatusController::class, 'showCampaign'])->name('email-status.campaign');
});

require __DIR__.'/auth.php';
