<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Agency\DashboardController as AgencyDashboard;
use App\Http\Controllers\Agency\ClientController;
use App\Http\Controllers\Client\DashboardController as ClientDashboard;
use App\Http\Controllers\Client\PortfolioController;
use App\Http\Controllers\Client\TestimonialController;
use App\Http\Controllers\Client\InboxController;
use App\Http\Controllers\Client\SettingsController;
use App\Http\Controllers\Client\MediaController;
use App\Http\Controllers\Client\ProductController;
use Illuminate\Support\Facades\Route;

// Auth
Route::get('/', [LoginController::class, 'showLogin'])->name('login');
Route::get('/login', [LoginController::class, 'showLogin'])->name('login.show');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Agency routes
Route::prefix('agency')->middleware(['auth.check', 'auth.agency'])->group(function () {
    Route::get('/', [AgencyDashboard::class, 'index'])->name('agency.dashboard');
    Route::get('/clients', [ClientController::class, 'index'])->name('agency.clients.index');
    Route::get('/clients/create', [ClientController::class, 'create'])->name('agency.clients.create');
    Route::post('/clients', [ClientController::class, 'store'])->name('agency.clients.store');
    Route::get('/clients/{client}/edit', [ClientController::class, 'edit'])->name('agency.clients.edit');
    Route::put('/clients/{client}', [ClientController::class, 'update'])->name('agency.clients.update');
    Route::post('/clients/{client}/impersonate', [ClientController::class, 'impersonate'])->name('agency.clients.impersonate');
    Route::post('/stop-impersonate', [ClientController::class, 'stopImpersonate'])->name('agency.stop-impersonate');
});

// Client routes
Route::prefix('client')->middleware(['auth.check', 'auth.client'])->group(function () {
    Route::get('/', [ClientDashboard::class, 'index'])->name('client.dashboard');

    // Portfolio
    Route::get('/portfolio', [PortfolioController::class, 'index'])->name('client.portfolio.index');
    Route::get('/portfolio/create', [PortfolioController::class, 'create'])->name('client.portfolio.create');
    Route::post('/portfolio', [PortfolioController::class, 'store'])->name('client.portfolio.store');
    Route::get('/portfolio/{project}/edit', [PortfolioController::class, 'edit'])->name('client.portfolio.edit');
    Route::put('/portfolio/{project}', [PortfolioController::class, 'update'])->name('client.portfolio.update');
    Route::delete('/portfolio/{project}', [PortfolioController::class, 'destroy'])->name('client.portfolio.destroy');

    // Testimonials
    Route::get('/testimonials', [TestimonialController::class, 'index'])->name('client.testimonials.index');
    Route::get('/testimonials/create', [TestimonialController::class, 'create'])->name('client.testimonials.create');
    Route::post('/testimonials', [TestimonialController::class, 'store'])->name('client.testimonials.store');
    Route::get('/testimonials/{testimonial}/edit', [TestimonialController::class, 'edit'])->name('client.testimonials.edit');
    Route::put('/testimonials/{testimonial}', [TestimonialController::class, 'update'])->name('client.testimonials.update');
    Route::delete('/testimonials/{testimonial}', [TestimonialController::class, 'destroy'])->name('client.testimonials.destroy');

    // Inbox
    Route::get('/inbox', [InboxController::class, 'index'])->name('client.inbox.index');
    Route::get('/inbox/{submission}', [InboxController::class, 'show'])->name('client.inbox.show');
    Route::put('/inbox/{submission}/status', [InboxController::class, 'updateStatus'])->name('client.inbox.status');
    Route::post('/inbox/{submission}/archive', [InboxController::class, 'archive'])->name('client.inbox.archive');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('client.settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('client.settings.update');

    // Media Library
    Route::get('/media', [MediaController::class, 'index'])->name('client.media.index');
    Route::post('/media', [MediaController::class, 'store'])->name('client.media.store');
    Route::put('/media/{medium}', [MediaController::class, 'update'])->name('client.media.update');
    Route::delete('/media/{medium}', [MediaController::class, 'destroy'])->name('client.media.destroy');
    Route::get('/media/json', [MediaController::class, 'json'])->name('client.media.json');

    // Products (Webshop)
    Route::get('/products', [ProductController::class, 'index'])->name('client.products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('client.products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('client.products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('client.products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('client.products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('client.products.destroy');
});
