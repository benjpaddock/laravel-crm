<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\DataGrid\SavedFilterController;

Route::group(['middleware' => ['web', 'admin_locale']], function () {
    Route::get('/', 'Webkul\Admin\Http\Controllers\Controller@redirectToLogin')->name('krayin.home');

    Route::get('create/{id}', 'Webkul\Admin\Http\Controllers\DataGrid\SavedFilterController@create')->name('admin.datagrid.saved_filters.destroy');

    Route::prefix(config('app.admin_path'))->group(function () {
        // Admin Routes
        Route::group(['middleware' => ['user']], function () {
            Route::delete('logout', 'Webkul\Admin\Http\Controllers\User\SessionController@destroy')->name('admin.session.destroy');

            // Dashboard Route
            Route::get('dashboard', 'Webkul\Admin\Http\Controllers\Admin\DashboardController@index')->name('admin.dashboard.index');

            Route::get('template', 'Webkul\Admin\Http\Controllers\Admin\DashboardController@template')->name('admin.dashboard.template');

            // API routes
            Route::group([
                'prefix'    => 'api',
            ], function () {
                Route::group([
                    'prefix'    => 'dashboard',
                ], function () {
                    Route::get('/', 'Webkul\Admin\Http\Controllers\Admin\DashboardController@getCardData')->name('admin.api.dashboard.card.index');

                    Route::get('/cards', 'Webkul\Admin\Http\Controllers\Admin\DashboardController@getCards')->name('admin.api.dashboard.cards.index');

                    Route::post('/cards', 'Webkul\Admin\Http\Controllers\Admin\DashboardController@updateCards')->name('admin.api.dashboard.cards.update');
                });
            });

            Route::group([
                'prefix'    => 'mail',
                'namespace' => 'Webkul\Admin\Http\Controllers\Mail',
            ], function () {
                Route::post('create', 'EmailController@store')->name('admin.mail.store');

                Route::put('edit/{id?}', 'EmailController@update')->name('admin.mail.update');

                Route::get('attachment-download/{id?}', 'EmailController@download')->name('admin.mail.attachment_download');

                Route::get('{route?}', 'EmailController@index')->name('admin.mail.index');

                Route::get('{route?}/{id?}', 'EmailController@view')->name('admin.mail.view');

                Route::delete('{id?}', 'EmailController@destroy')->name('admin.mail.delete');

                Route::put('mass-update', 'EmailController@massUpdate')->name('admin.mail.mass_update');

                Route::put('mass-destroy', 'EmailController@massDestroy')->name('admin.mail.mass_delete');
            });

            // Settings Routes
            Route::group([
                'prefix'    => 'settings',
                'namespace' => 'Webkul\Admin\Http\Controllers\Settings',
            ], function () {

                Route::get('', 'SettingController@index')->name('admin.settings.index');

                // Attributes Routes
                Route::prefix('attributes')->group(function () {
                    Route::get('', 'AttributeController@index')->name('admin.settings.attributes.index');

                    Route::get('create', 'AttributeController@create')->name('admin.settings.attributes.create');

                    Route::post('create', 'AttributeController@store')->name('admin.settings.attributes.store');

                    Route::get('edit/{id}', 'AttributeController@edit')->name('admin.settings.attributes.edit');

                    Route::put('edit/{id}', 'AttributeController@update')->name('admin.settings.attributes.update');

                    Route::get('lookup/{lookup?}', 'AttributeController@lookup')->name('admin.settings.attributes.lookup');

                    Route::get('lookup-entity/{lookup?}', 'AttributeController@lookupEntity')->name('admin.settings.attributes.lookup_entity');

                    Route::delete('{id}', 'AttributeController@destroy')->name('admin.settings.attributes.delete');

                    Route::put('mass-update', 'AttributeController@massUpdate')->name('admin.settings.attributes.mass_update');

                    Route::put('mass-destroy', 'AttributeController@massDestroy')->name('admin.settings.attributes.mass_delete');

                    Route::get('download', 'AttributeController@download')->name('admin.settings.attributes.download');
                });

                // Email Templates Routes
                Route::prefix('email-templates')->group(function () {
                    Route::get('', 'EmailTemplateController@index')->name('admin.settings.email_templates.index');

                    Route::get('create', 'EmailTemplateController@create')->name('admin.settings.email_templates.create');

                    Route::post('create', 'EmailTemplateController@store')->name('admin.settings.email_templates.store');

                    Route::get('edit/{id?}', 'EmailTemplateController@edit')->name('admin.settings.email_templates.edit');

                    Route::put('edit/{id}', 'EmailTemplateController@update')->name('admin.settings.email_templates.update');

                    Route::delete('{id}', 'EmailTemplateController@destroy')->name('admin.settings.email_templates.delete');
                });

                // Warehouses Locations Routes
                Route::prefix('locations')->group(function () {
                    Route::get('search', 'LocationController@search')->name('admin.settings.locations.search');

                    Route::post('create', 'LocationController@store')->name('admin.settings.locations.store');

                    Route::put('edit/{id}', 'LocationController@update')->name('admin.settings.locations.update');

                    Route::delete('{id}', 'LocationController@destroy')->name('admin.settings.locations.delete');
                });
            });

            Route::controller(SavedFilterController::class)->prefix('datagrid/saved-filters')->group(function () {
                Route::post('', 'store')->name('admin.datagrid.saved_filters.store');

                Route::get('', 'get')->name('admin.datagrid.saved_filters.index');

                Route::put('{id}', 'update')->name('admin.datagrid.saved_filters.update');

                Route::delete('{id}', 'destroy')->name('admin.datagrid.saved_filters.destroy');
            });

            // Configuration Routes
            Route::group([
                'prefix'    => 'configuration',
                'namespace' => 'Webkul\Admin\Http\Controllers\Configuration',
            ], function () {
                Route::get('{slug?}', 'ConfigurationController@index')->name('admin.configuration.index');

                Route::post('{slug?}', 'ConfigurationController@store')->name('admin.configuration.index.store');

                Route::get('{slug}/{path}', 'ConfigurationController@download')->name('admin.configuration.download');
            });
        });
    });
});
