<?php

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

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/attachments/{path}', function (Request $request, $path) {
    if (!auth()->check()) {
        return abort(404);
    }

    $filepath = storage_path('app/attachments/' . $path);

    if (!File::exists($filepath)) {
        abort(404);
    }

    $file = File::get($filepath);
    $type = File::mimeType($filepath);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});

Route::group(['middleware' => 'auth', 'prefix' => 'profile'], function () {
    Route::get('', 'ProfileController@index')->name('profile.index');
    Route::post('', 'ProfileController@update')->name('profile.update');
    Route::post('/payment', 'ProfileController@payment')->name('profile.payment');
    Route::post('/kyc', 'ProfileController@kyc')->name('profile.kyc');
});


Route::group(['namespace' => 'Admin', 'middleware' => ['auth', 'acl'], 'acl' => ['isAdmin'], 'prefix' => 'admin'], function () {
    Route::get('/dashboard', 'DashboardController@index')->name('admin.dashboard');
    Route::get('/settings', 'SettingsController@index')->name('admin.settings');
    Route::patch('/settings', 'SettingsController@update')->name('admin.settings.update');
    Route::group(['prefix' => 'plans'], function () {
        Route::get('', 'PlansController@index')->name('admin.plans.index');
        Route::get('/create', 'PlansController@create')->name('admin.plans.create');
        Route::get('/edit/{id}', 'PlansController@edit')->name('admin.plans.edit');
        Route::post('', 'PlansController@store')->name('admin.plans.store');
        Route::patch('/{id}', 'PlansController@update')->name('admin.plans.update');
        Route::delete('/{id}', 'PlansController@destroy')->name('admin.plans.destroy');
    });

    Route::group(['prefix' => 'approvals'], function () {
        Route::get('/members', 'ApprovalsController@members')->name('admin.approvals.members');
        Route::get('/members/{id}', 'ApprovalsController@showmember')->name('admin.approvals.showmember');
        Route::patch('/members/{id}', 'ApprovalsController@updatemember')->name('admin.approvals.updatemember');
        Route::delete('/members/{id}', 'ApprovalsController@deletemember')->name('admin.approvals.deletemember');

        Route::get('/withdrawals', 'ApprovalsController@withdrawals')->name('admin.approvals.withdrawals');
        Route::get('/withdrawals/{id}/payment', 'ApprovalsController@withdrawalpayment')->name('admin.approvals.withdrawalpayment');
        Route::patch('/withdrawals/{id}', 'ApprovalsController@updatewithdrawal')->name('admin.approvals.updatewithdrawal');

        Route::get('/closings', 'ApprovalsController@closings')->name('admin.approvals.closings');
        Route::get('/closings/{id}', 'ApprovalsController@editclosing')->name('admin.approvals.editclosing');
        Route::patch('/closings/{id}', 'ApprovalsController@updateclosing')->name('admin.approvals.updateclosing');

        Route::get('/kyc', 'ApprovalsController@kyc')->name('admin.approvals.kyc');
        Route::patch('/kyc/{id}', 'ApprovalsController@updatekyc')->name('admin.approvals.updatekyc');

        Route::get('/plans', 'ApprovalsController@plans')->name('admin.approvals.plans');
        Route::patch('/plans/{id}', 'ApprovalsController@updateplan')->name('admin.approvals.updateplan');
    });

    Route::group(['prefix' => 'members'], function () {
        Route::get('/', 'MembersController@list')->name('admin.members.list');
        Route::get('/hierarchy', 'MembersController@hierarchy')->name('admin.members.hierarchy');
        Route::get('/tabular', 'MembersController@tabular')->name('admin.members.tabular');
        Route::get('/{id}', 'MembersController@show')->name('admin.members.show');
        Route::patch('/{id}', 'MembersController@update')->name('admin.members.update');
        Route::post('/resetsecuritypin/{id}', 'MembersController@resetSecurityPin')->name('admin.members.resetsecuritypin');
    });

    Route::group(['prefix' => 'payouts'], function () {
        Route::get('/payouts', 'PayoutsController@index')->name('admin.payouts.index');
        Route::get('/payouts/create', 'PayoutsController@create')->name('admin.payouts.create');
        Route::get('/payouts/{id}', 'PayoutsController@show')->name('admin.payouts.show');
        Route::post('/payouts', 'PayoutsController@store')->name('admin.payouts.store');
    });

    Route::group(['prefix' => 'mailbox'], function () {
        Route::get('/', 'MailboxController@inbox')->name('admin.mailbox.inbox');
        Route::get('/delete/{id}', 'MailboxController@delete')->name('admin.mailbox.delete');
        Route::get('/mail/{id}', 'MailboxController@show')->name('admin.mailbox.show');
        Route::get('/outbox', 'MailboxController@outbox')->name('admin.mailbox.outbox');
        Route::get('/compose', 'MailboxController@compose')->name('admin.mailbox.compose');
        Route::post('/send', 'MailboxController@send')->name('admin.mailbox.send');

        Route::get('/announcements', 'MailboxController@announcements')->name('admin.mailbox.announcements');
    });
});

Route::group(['namespace' => 'Member', 'middleware' => ['auth', 'acl', 'pendingMember'], 'acl' => ['isMember', 'isSys', 'isAdmin'], 'prefix' => 'member'], function () {
    Route::get('/dashboard', 'DashboardController@index')->name('member.dashboard');
    Route::group(['prefix' => 'downlines'], function () {
        Route::get('/list', 'DownlinesController@list')->name('member.downlines.list');
        Route::get('/hierarchy', 'DownlinesController@hierarchy')->name('member.downlines.hierarchy');
        Route::get('/tabular', 'DownlinesController@tabular')->name('member.downlines.tabular');

        Route::get('/create', 'DownlinesController@create')->name('member.downlines.create');
        Route::post('', 'DownlinesController@store')->name('member.downlines.store');
        Route::post('/{id}/payment', 'DownlinesController@payment')->name('member.downlines.payment');
        Route::get('/{id}', 'DownlinesController@show')->name('member.downlines.show');
        Route::delete('/{id}', 'DownlinesController@destroy')->name('member.downlines.destroy');
    });

    Route::group(['prefix' => 'wallets'], function () {
        Route::get('/transactions', 'WalletsController@transactions')->name('member.wallets.transactions');
        Route::get('/withdrawals', 'WalletsController@withdrawals')->name('member.wallets.withdrawals');
        Route::post('/withdrawals', 'WalletsController@withdraw')->name('member.wallets.withdraw');
        Route::get('/transfers', 'WalletsController@transfers')->name('member.wallets.transfers');
        Route::post('/transferout', 'WalletsController@transferout')->name('member.wallets.transferout');
    });

    Route::group(['prefix' => 'mailbox'], function () {
        Route::get('/', 'MailboxController@inbox')->name('member.mailbox.inbox');
        Route::get('/delete/{id}', 'MailboxController@delete')->name('member.mailbox.delete');
        Route::get('/mail/{id}', 'MailboxController@show')->name('member.mailbox.show');
        Route::get('/outbox', 'MailboxController@outbox')->name('member.mailbox.outbox');
        Route::get('/compose', 'MailboxController@compose')->name('member.mailbox.compose');
        Route::post('/send', 'MailboxController@send')->name('member.mailbox.send');

        Route::get('/announcements', 'MailboxController@announcements')->name('member.mailbox.announcements');
    });

    Route::group(['prefix' => 'upgrade'], function () {
        Route::get('/', 'UpgradeController@index')->name('member.upgrade');
        Route::post('/', 'UpgradeController@update')->name('member.upgrade.update');
    });

    Route::group(['prefix' => 'payouts'], function () {
        Route::get('/payouts', 'PayoutsController@index')->name('member.payouts.index');
        Route::get('/payouts/{id}', 'PayoutsController@show')->name('member.payouts.show');
    });

    Route::post('/closing/close', 'ClosingController@close')->name('member.closing.close');
    Route::get('/closing/cancel', 'ClosingController@cancel')->name('member.closing.cancel');
});

//Please do not remove this if you want adminlte:route and adminlte:link commands to works correctly.
#adminlte_routes
