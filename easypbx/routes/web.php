<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SmsController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\ApisController;
use App\Http\Controllers\IvrsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FlowsController;
use App\Http\Controllers\FuncsController;
use App\Http\Controllers\LeadsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\Api\FunctionCall;
use App\Http\Controllers\DialerController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TrunksController;
use App\Http\Controllers\CallingController;
use App\Http\Controllers\ScriptsController;
use App\Http\Controllers\SettingController;
use App\Notifications\PushNotification;
use App\Http\Controllers\SurveysController;
use function PHPUnit\Framework\returnValue;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\FirewallController;
use App\Http\Controllers\HotdesksController;
use App\Http\Controllers\PinListsController;
use App\Http\Controllers\BroadcastsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VoiceMailController;
use App\Http\Controllers\CallQueuesController;
use App\Http\Controllers\ExtensionsController;
use App\Http\Controllers\IvrActionsController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\RingGroupsController;
use App\Http\Controllers\TimeGroupsController;
use App\Http\Controllers\VoiceFilesController;
use App\Http\Controllers\CallRecordsController;
use App\Http\Controllers\CampaignSmsController;
use App\Http\Controllers\CustomFormsController;
use App\Http\Controllers\CustomFuncsController;
use App\Http\Controllers\FlowActionsController;
use App\Http\Controllers\SmsProfilesController;
use App\Http\Controllers\TtsProfilesController;
use App\Http\Controllers\ApplicationsController;
use App\Http\Controllers\IpBlackListsController;
use App\Http\Controllers\MailProfilesController;
use App\Http\Controllers\AnnouncementsController;
use App\Http\Controllers\CampaignCallsController;
use App\Http\Controllers\ContactGroupsController;
use App\Http\Controllers\InboundRoutesController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\OrganizationsController;
use App\Http\Controllers\LicenceManagerController;
use App\Http\Controllers\OutboundRoutesController;
use App\Http\Controllers\VoiceRecordsController;
use App\Http\Controllers\TimeConditionsController;
use App\Http\Controllers\DialerCampaignsController;
use App\Http\Controllers\DialerCampaignCallController;
use App\Http\Controllers\TicketsController;
use App\Http\Controllers\PlansController;
use App\Http\Controllers\CallParkingsController;
use App\Http\Controllers\ExtensionGroupsController;
use App\Http\Controllers\Agent\Auth\LoginController;
use Illuminate\Support\Facades\Http;
use App\Models\SmsProfile;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CallQueueExtensionsController;
use App\Http\Controllers\Agent\DashboardController as AgentDashboardController;


Route::get( '/clear', function () {
    $cacheCommands = [
        'event:clear',
        'view:clear',
        'cache:clear',
        'route:clear',
        'config:clear',
        'clear-compiled',
        'optimize:clear',
    ];

    foreach ( $cacheCommands as $command ) {
        Artisan::call( $command );
    }

    return redirect()->back();

} );

Route::group( ['prefix' => 'agent', 'as' => 'agent.'], function () {
    Route::get( '/', [LoginController::class, 'showLoginForm'] );
    Route::get( '/login', [LoginController::class, 'showLoginForm'] )->name( 'login' );

    Route::post( '/login', [LoginController::class, 'login'] )->name( 'login' );
    Route::get( '/dashboard', [AgentDashboardController::class, 'index'] )->name( 'dashboard' );
} );



Route::get( '/', function () {
    return redirect()->route( 'login' );
} );

Auth::routes();
Route::get('reg-information', [RegisterController::class, 'regInfo'])->name('reg.info');

Route::group( [ 'middleware' => 'auth'], function () {
    Route::group( [ 'middleware' => 'permission:su.*'], function () {
        Route::group( [
            'prefix' => 'tenants',
        ], function () {
            Route::get( '/', [OrganizationsController::class, 'index'] )
                ->name( 'organizations.organization.index' );
            Route::get( '/create', [OrganizationsController::class, 'create'] )
                ->name( 'organizations.organization.create' );
    
            Route::get( '/dns/{ip}/{subdomain}', [OrganizationsController::class, 'dns'] );
    
            Route::get( '/show/{organization}', [OrganizationsController::class, 'show'] )
                ->name( 'organizations.organization.show' );
            Route::get( '/{organization}/edit', [OrganizationsController::class, 'edit'] )
                ->name( 'organizations.organization.edit' );
            Route::post( '/', [OrganizationsController::class, 'store'] )
                ->name( 'organizations.organization.store' );
            Route::put( 'organization/{organization}', [OrganizationsController::class, 'update'] )
                ->name( 'organizations.organization.update' );
            Route::delete( '/organization/{organization}', [OrganizationsController::class, 'destroy'] )
                ->name( 'organizations.organization.destroy' );
            Route::put( 'field/{organization}', [OrganizationsController::class, 'updateField'] )
                ->name( 'organizations.organization.updateField' );
            Route::put( '/bulk', [OrganizationsController::class, 'bulkAction'] )
                ->name( 'organizations.organization.bulk' );
            Route::get( '/login/{organization}', [OrganizationsController::class, 'login'] )
                ->name( 'organizations.organization.login' );
        } );
    
        Route::group( [
            'prefix' => 'ip_black_lists',
        ], function () {
            Route::get( '/', [IpBlackListsController::class, 'index'] )
                ->name( 'ip_black_lists.ip_black_list.index' );
            Route::get( '/create', [IpBlackListsController::class, 'create'] )
                ->name( 'ip_black_lists.ip_black_list.create' );
            Route::get( '/show/{ipBlackList}', [IpBlackListsController::class, 'show'] )
                ->name( 'ip_black_lists.ip_black_list.show' );
            Route::get( '/{ipBlackList}/edit', [IpBlackListsController::class, 'edit'] )
                ->name( 'ip_black_lists.ip_black_list.edit' );
            Route::post( '/', [IpBlackListsController::class, 'store'] )
                ->name( 'ip_black_lists.ip_black_list.store' );
            Route::put( 'ip_black_list/{ipBlackList}', [IpBlackListsController::class, 'update'] )
                ->name( 'ip_black_lists.ip_black_list.update' );
            Route::delete( '/ip_black_list/{ipBlackList}', [IpBlackListsController::class, 'destroy'] )
                ->name( 'ip_black_lists.ip_black_list.destroy' );
            Route::put( 'field/{ipBlackList}', [IpBlackListsController::class, 'updateField'] )
                ->name( 'ip_black_lists.ip_black_list.updateField' );
            Route::put( '/bulk', [IpBlackListsController::class, 'bulkAction'] )
                ->name( 'ip_black_lists.ip_black_list.bulk' );
        } );
    
    
        Route::group( [
            'prefix' => 'settings',
        ], function () {
            Route::get( '/firewall', [FirewallController::class, 'index'] )->name( 'settings.firewall.index' );
            Route::post( '/firewall/store', [FirewallController::class, 'store'] )->name( 'settings.firewall.store' );
    
            Route::get( '/switch/{group}', [SettingController::class, 'index'] )->name( 'settings.setting.index' );
            Route::post( '/store', [SettingController::class, 'store'] )->name( 'settings.setting.store' );
    
        } );


        Route::group([
            'prefix' => 'plans',
        ], function () {
            Route::get('/', [PlansController::class, 'index'])
                 ->name('plans.plan.index');
            Route::get('/create', [PlansController::class, 'create'])
                 ->name('plans.plan.create');
            Route::get('/show/{plan}',[PlansController::class, 'show'])
                 ->name('plans.plan.show');
            Route::get('/{plan}/edit',[PlansController::class, 'edit'])
                 ->name('plans.plan.edit');
            Route::post('/', [PlansController::class, 'store'])
                 ->name('plans.plan.store');
            Route::put('plan/{plan}', [PlansController::class, 'update'])
                 ->name('plans.plan.update');
            Route::delete('/plan/{plan}',[PlansController::class, 'destroy'])
                 ->name('plans.plan.destroy');
            Route::put('field/{plan}', [PlansController::class, 'updateField'])
                 ->name('plans.plan.updateField'); 
            Route::put('/bulk', [PlansController::class, 'bulkAction'])
                 ->name('plans.plan.bulk');            
        });
    
    });


    
    Route::group(['middleware' => 'permission:admin.monitoring.*'], function(){
        Route::group( [
            'prefix' => 'monitoring',
        ], function () {
            Route::get( '/active-call', [MonitoringController::class, 'activeCall'] )->name( 'monitoring.active.call' );
            Route::get( '/bridge-calls', [MonitoringController::class, 'callHistories'] )->name( 'monitoring.call.history' );

            Route::get( '/queue-call', [MonitoringController::class, 'queueCall'] )->name( 'monitoring.queue.call' );
            
            Route::get( '/record-preview/{id}', [MonitoringController::class, 'preview'] )
                ->name( 'monitoring.record.preview' );
            Route::get( '/history-record-preview/{id}', [MonitoringController::class, 'history_preview'] )
                ->name( 'monitoring.history.record.preview' );
            Route::get( '/call-log', [MonitoringController::class, 'callLog'] )->name( 'monitoring.log.call' );
            Route::get( '/active-channels', [MonitoringController::class, 'activeChannels'] )->name( 'monitoring.active.sip' );

            Route::get( '/voice-record-logs', [VoiceMailController::class, 'index'] )->name( 'monitoring.voice_mails.index' );
            Route::get( '/voice-mails/preview/{id}', [VoiceMailController::class, 'preview'] )
                ->name( 'monitoring.voice_mails.preview' );

            Route::get( '/sms-histories', [MonitoringController::class, 'smsHistory'] )->name( 'monitoring.sms.histories' );
            Route::get( '/trunk-logs', [MonitoringController::class, 'trunkLog'] )->name( 'monitoring.trunk.log' );
            Route::get( '/surveys', [ReportController::class, 'survey'] )->name( 'monitoring.surveys' );

            Route::get('/active-parking-calls', [MonitoringController::class, 'activeParkingCalls'])->name('monitoring.active.parking.calls');

        } );

        Route::group( [
            'prefix' => 'monitoring/broadcast_history',
        ], function () {
            Route::get( '/', [CampaignCallsController::class, 'index'] )
                ->name( 'broadcast_calls.broadcast_call.index' );
           /*  Route::put( 'field/{campaignCall}', [CampaignCallsController::class, 'updateField'] )
                ->name( 'campaign_calls.campaign_call.updateField' );
            
            Route::put( '/bulk/{campaign}', [CampaignCallsController::class, 'bulkAction'] )
                ->name( 'campaign_calls.campaign_call.bulk' ); */
        } );

        Route::group( [
            'prefix' => 'campaign_sms',
        ], function () {
            Route::get( '/{campaign}', [CampaignSmsController::class, 'index'] )
                ->name( 'campaign_sms.campaign_sms.index' );
            Route::put( 'field/{campaignCall}', [CampaignSmsController::class, 'updateField'] )
                ->name( 'campaign_sms.campaign_sms.updateField' );
            Route::put( '/bulk/{campaign}', [CampaignSmsController::class, 'bulkAction'] )
                ->name( 'campaign_sms.campaign_sms.bulk' );
        } );

    });


    Route::group(['middleware' => 'permission:admin.extension.*'], function(){
        Route::group( [
            'prefix' => 'extensions',
        ], function () {
            Route::get( '/', [ExtensionsController::class, 'index'] )
                ->name( 'extensions.extension.index' );
            Route::get( '/create', [ExtensionsController::class, 'create'] )
                ->name( 'extensions.extension.create' );
            Route::get( '/show/{extension}', [ExtensionsController::class, 'show'] )
                ->name( 'extensions.extension.show' );
            Route::get( '/{extension}/edit', [ExtensionsController::class, 'edit'] )
                ->name( 'extensions.extension.edit' );
            Route::post( '/', [ExtensionsController::class, 'store'] )
                ->name( 'extensions.extension.store' );
            Route::put( 'extension/{extension}', [ExtensionsController::class, 'update'] )
                ->name( 'extensions.extension.update' );
            Route::delete( '/extension/{extension}', [ExtensionsController::class, 'destroy'] )
                ->name( 'extensions.extension.destroy' );
            Route::put( 'field/{extension}', [ExtensionsController::class, 'updateField'] )
                ->name( 'extensions.extension.updateField' );
            Route::put( '/bulk', [ExtensionsController::class, 'bulkAction'] )
                ->name( 'extensions.extension.bulk' );
            Route::post( '/upload', [ExtensionsController::class, 'import'] )
                ->name( 'extensions.extension.import' );

        } );

        Route::group( [
            'prefix' => 'hotdesks',
        ], function () {
            Route::get( '/', [HotdesksController::class, 'index'] )
                ->name( 'hotdesks.hotdesk.index' );
            Route::get( '/create', [HotdesksController::class, 'create'] )
                ->name( 'hotdesks.hotdesk.create' );
            Route::get( '/show/{hotdesk}', [HotdesksController::class, 'show'] )
                ->name( 'hotdesks.hotdesk.show' )->where( 'id', '[0-9]+' );
            Route::get( '/{hotdesk}/edit', [HotdesksController::class, 'edit'] )
                ->name( 'hotdesks.hotdesk.edit' )->where( 'id', '[0-9]+' );
            Route::post( '/', [HotdesksController::class, 'store'] )
                ->name( 'hotdesks.hotdesk.store' );
            Route::put( 'hotdesk/{hotdesk}', [HotdesksController::class, 'update'] )
                ->name( 'hotdesks.hotdesk.update' )->where( 'id', '[0-9]+' );
            Route::delete( '/hotdesk/{hotdesk}', [HotdesksController::class, 'destroy'] )
                ->name( 'hotdesks.hotdesk.destroy' )->where( 'id', '[0-9]+' );
            Route::put( 'field/{hotdesk}', [HotdesksController::class, 'updateField'] )
                ->name( 'hotdesks.hotdesk.updateField' );
            Route::put( '/bulk', [HotdesksController::class, 'bulkAction'] )
                ->name( 'hotdesks.hotdesk.bulk' );
        } );


    });


    Route::group(['middleware' => 'permission:admin.call_center.*'], function(){

        Route::group( [
            'prefix' => 'ring_groups',
        ], function () {
            Route::get( '/', [RingGroupsController::class, 'index'] )
                ->name( 'ring_groups.ring_group.index' );

            Route::get( 'distinations/{function}', [RingGroupsController::class, 'destinations'] )
                ->name( 'ring_groups.ring_group.destinations' );

            Route::get( '/create', [RingGroupsController::class, 'create'] )
                ->name( 'ring_groups.ring_group.create' );
            Route::get( '/show/{ringGroup}', [RingGroupsController::class, 'show'] )
                ->name( 'ring_groups.ring_group.show' )->where( 'id', '[0-9]+' );
            Route::get( '/{ringGroup}/edit', [RingGroupsController::class, 'edit'] )
                ->name( 'ring_groups.ring_group.edit' )->where( 'id', '[0-9]+' );
            Route::post( '/', [RingGroupsController::class, 'store'] )
                ->name( 'ring_groups.ring_group.store' );
            Route::put( 'ring_group/{ringGroup}', [RingGroupsController::class, 'update'] )
                ->name( 'ring_groups.ring_group.update' )->where( 'id', '[0-9]+' );
            Route::delete( '/ring_group/{ringGroup}', [RingGroupsController::class, 'destroy'] )
                ->name( 'ring_groups.ring_group.destroy' )->where( 'id', '[0-9]+' );
            Route::put( 'field/{ringGroup}', [RingGroupsController::class, 'updateField'] )
                ->name( 'ring_groups.ring_group.updateField' );
            Route::put( '/bulk', [RingGroupsController::class, 'bulkAction'] )
                ->name( 'ring_groups.ring_group.bulk' );
        } );

        Route::group( [
            'prefix' => 'call_queues',
        ], function () {
            Route::get( '/', [CallQueuesController::class, 'index'] )
                ->name( 'call_queues.call_queue.index' );

            Route::get( 'distinations/{function}', [CallQueuesController::class, 'destinations'] )
                ->name( 'call_queues.call_queue.destinations' );

            Route::get( '/create', [CallQueuesController::class, 'create'] )
                ->name( 'call_queues.call_queue.create' );
            Route::get( '/show/{callQueue}', [CallQueuesController::class, 'show'] )
                ->name( 'call_queues.call_queue.show' );
            Route::get( '/{callQueue}/edit', [CallQueuesController::class, 'edit'] )
                ->name( 'call_queues.call_queue.edit' );
            Route::post( '/', [CallQueuesController::class, 'store'] )
                ->name( 'call_queues.call_queue.store' );
            Route::put( 'call_queue/{callQueue}', [CallQueuesController::class, 'update'] )
                ->name( 'call_queues.call_queue.update' );
            Route::delete( '/call_queue/{callQueue}', [CallQueuesController::class, 'destroy'] )
                ->name( 'call_queues.call_queue.destroy' );
            Route::put( 'field/{callQueue}', [CallQueuesController::class, 'updateField'] )
                ->name( 'call_queues.call_queue.updateField' );
            Route::put( '/bulk', [CallQueuesController::class, 'bulkAction'] )
                ->name( 'call_queues.call_queue.bulk' );
        } );

        Route::group( [
            'prefix' => 'call_queue_extensions',
        ], function () {

            Route::get( '/create/{queue_id}', [CallQueueExtensionsController::class, 'create'] )
                ->name( 'call_queue_extensions.call_queue_extension.create' );

            Route::get( '/{queue_id}', [CallQueueExtensionsController::class, 'index'] )
                ->name( 'call_queue_extensions.call_queue_extension.index' );

            Route::get( '/show/{callQueueExtension}', [CallQueueExtensionsController::class, 'show'] )
                ->name( 'call_queue_extensions.call_queue_extension.show' );
            Route::get( '/{callQueueExtension}/edit', [CallQueueExtensionsController::class, 'edit'] )
                ->name( 'call_queue_extensions.call_queue_extension.edit' );
            Route::post( '/', [CallQueueExtensionsController::class, 'store'] )
                ->name( 'call_queue_extensions.call_queue_extension.store' );
            Route::put( 'call_queue_extension/{callQueueExtension}', [CallQueueExtensionsController::class, 'update'] )
                ->name( 'call_queue_extensions.call_queue_extension.update' );
            Route::delete( '/call_queue_extension/{callQueueExtension}', [CallQueueExtensionsController::class, 'destroy'] )
                ->name( 'call_queue_extensions.call_queue_extension.destroy' );
            Route::put( 'field/{callQueueExtension}', [CallQueueExtensionsController::class, 'updateField'] )
                ->name( 'call_queue_extensions.call_queue_extension.updateField' );
            Route::put( '/bulk', [CallQueueExtensionsController::class, 'bulkAction'] )
                ->name( 'call_queue_extensions.call_queue_extension.bulk' );
        } );

    });


    Route::group(['middleware' => 'permission:admin.external.*'], function(){
        Route::group( [
            'prefix' => 'trunks',
        ], function () {
            Route::get( '/', [TrunksController::class, 'index'] )
                ->name( 'trunks.trunk.index' );
            Route::get( '/create', [TrunksController::class, 'create'] )
                ->name( 'trunks.trunk.create' );
            Route::get( '/show/{trunk}', [TrunksController::class, 'show'] )
                ->name( 'trunks.trunk.show' );
            Route::get( '/{trunk}/edit', [TrunksController::class, 'edit'] )
                ->name( 'trunks.trunk.edit' );
            Route::post( '/', [TrunksController::class, 'store'] )
                ->name( 'trunks.trunk.store' );
            Route::put( 'trunk/{trunk}', [TrunksController::class, 'update'] )
                ->name( 'trunks.trunk.update' );
            Route::delete( '/trunk/{trunk}', [TrunksController::class, 'destroy'] )
                ->name( 'trunks.trunk.destroy' );
            Route::put( 'field/{trunk}', [TrunksController::class, 'updateField'] )
                ->name( 'trunks.trunk.updateField' );
            Route::put( '/bulk', [TrunksController::class, 'bulkAction'] )
                ->name( 'trunks.trunk.bulk' );
        } );

        Route::group( [
            'prefix' => 'outbound_routes',
        ], function () {
            Route::get( '/', [OutboundRoutesController::class, 'index'] )
                ->name( 'outbound_routes.outbound_route.index' );

            Route::get( 'distinations/{function}', [OutboundRoutesController::class, 'destinations'] )
                ->name( 'outbound_routes.outbound_route.destinations' );

            Route::get( '/create/{api?}', [OutboundRoutesController::class, 'create'] )
                ->name( 'outbound_routes.outbound_route.create' );
            Route::get( '/show/{outboundRoute}', [OutboundRoutesController::class, 'show'] )
                ->name( 'outbound_routes.outbound_route.show' );
            Route::get( '/{outboundRoute}/edit', [OutboundRoutesController::class, 'edit'] )
                ->name( 'outbound_routes.outbound_route.edit' );
            Route::post( '/', [OutboundRoutesController::class, 'store'] )
                ->name( 'outbound_routes.outbound_route.store' );
            Route::put( 'outbound_route/{outboundRoute}', [OutboundRoutesController::class, 'update'] )
                ->name( 'outbound_routes.outbound_route.update' );
            Route::delete( '/outbound_route/{outboundRoute}', [OutboundRoutesController::class, 'destroy'] )
                ->name( 'outbound_routes.outbound_route.destroy' );
            Route::put( 'field/{outboundRoute}', [OutboundRoutesController::class, 'updateField'] )
                ->name( 'outbound_routes.outbound_route.updateField' );
            Route::put( '/bulk', [OutboundRoutesController::class, 'bulkAction'] )
                ->name( 'outbound_routes.outbound_route.bulk' );
        } );

        Route::group( [
            'prefix' => 'inbound_routes',
        ], function () {
            Route::get( '/', [InboundRoutesController::class, 'index'] )
                ->name( 'inbound_routes.inbound_route.index' );

            Route::get( 'distinations/{function}', [InboundRoutesController::class, 'destinations'] )
                ->name( 'inbound_routes.inbound_route.destinations' );

            Route::get( '/create', [InboundRoutesController::class, 'create'] )
                ->name( 'inbound_routes.inbound_route.create' );
            Route::get( '/show/{inboundRoute}', [InboundRoutesController::class, 'show'] )
                ->name( 'inbound_routes.inbound_route.show' );
            Route::get( '/{inboundRoute}/edit', [InboundRoutesController::class, 'edit'] )
                ->name( 'inbound_routes.inbound_route.edit' );
            Route::post( '/', [InboundRoutesController::class, 'store'] )
                ->name( 'inbound_routes.inbound_route.store' );
            Route::put( 'inbound_route/{inboundRoute}', [InboundRoutesController::class, 'update'] )
                ->name( 'inbound_routes.inbound_route.update' );
            Route::delete( '/inbound_route/{inboundRoute}', [InboundRoutesController::class, 'destroy'] )
                ->name( 'inbound_routes.inbound_route.destroy' );
            Route::put( 'field/{inboundRoute}', [InboundRoutesController::class, 'updateField'] )
                ->name( 'inbound_routes.inbound_route.updateField' );
            Route::put( '/bulk', [InboundRoutesController::class, 'bulkAction'] )
                ->name( 'inbound_routes.inbound_route.bulk' );
        } );
    });

    Route::group(['middleware' => 'permission:admin.incoming.*'], function(){
        Route::group( [
            'prefix' => 'ivrs',
        ], function () {
            Route::get( '/', [IvrsController::class, 'index'] )
                ->name( 'ivrs.ivr.index' );

            Route::get( 'distinations/{function}', [IvrsController::class, 'destinations'] )
                ->name( 'ivrs.ivr.destinations' );

            Route::get( '/create', [IvrsController::class, 'create'] )
                ->name( 'ivrs.ivr.create' );
            Route::get( '/show/{ivr}', [IvrsController::class, 'show'] )
                ->name( 'ivrs.ivr.show' );
            Route::get( '/{ivr}/edit', [IvrsController::class, 'edit'] )
                ->name( 'ivrs.ivr.edit' );
            Route::post( '/', [IvrsController::class, 'store'] )
                ->name( 'ivrs.ivr.store' );
            Route::put( 'ivr/{ivr}', [IvrsController::class, 'update'] )
                ->name( 'ivrs.ivr.update' );
            Route::delete( '/ivr/{ivr}', [IvrsController::class, 'destroy'] )
                ->name( 'ivrs.ivr.destroy' );
            Route::put( 'field/{ivr}', [IvrsController::class, 'updateField'] )
                ->name( 'ivrs.ivr.updateField' );
            Route::put( '/bulk', [IvrsController::class, 'bulkAction'] )
                ->name( 'ivrs.ivr.bulk' );
        } );

        Route::group( [
            'prefix' => 'ivr_actions',
        ], function () {
            Route::get( '/{ivr}', [IvrActionsController::class, 'index'] )
                ->name( 'ivr_actions.ivr_action.index' );

            Route::get( 'distinations/{function}/{ivr?}', [IvrActionsController::class, 'destinations'] )
                ->name( 'ivr_actions.ivr_action.destinations' );

            Route::get( 'ivr_digits/{ivr}/{ivr_action?}', [IvrActionsController::class, 'ivr_digits'] )
                ->name( 'ivr_actions.ivr_action.digits' );

            Route::get( '/create/{ivr?}', [IvrActionsController::class, 'create'] )
                ->name( 'ivr_actions.ivr_action.create' );
            Route::get( '/show/{ivrAction}', [IvrActionsController::class, 'show'] )
                ->name( 'ivr_actions.ivr_action.show' );
            Route::get( '/{ivrAction}/edit', [IvrActionsController::class, 'edit'] )
                ->name( 'ivr_actions.ivr_action.edit' );
            Route::post( '/', [IvrActionsController::class, 'store'] )
                ->name( 'ivr_actions.ivr_action.store' );
            Route::put( 'ivr_action/{ivrAction}', [IvrActionsController::class, 'update'] )
                ->name( 'ivr_actions.ivr_action.update' );
            Route::delete( '/ivr_action/{ivrAction}', [IvrActionsController::class, 'destroy'] )
                ->name( 'ivr_actions.ivr_action.destroy' );
            Route::put( 'field/{ivrAction}', [IvrActionsController::class, 'updateField'] )
                ->name( 'ivr_actions.ivr_action.updateField' );
            Route::put( '/bulk', [IvrActionsController::class, 'bulkAction'] )
                ->name( 'ivr_actions.ivr_action.bulk' );
        } );

        Route::group( [
            'prefix' => 'announcements',
        ], function () {
            Route::get( '/', [AnnouncementsController::class, 'index'] )
                ->name( 'announcements.announcement.index' );
            Route::get( '/create', [AnnouncementsController::class, 'create'] )
                ->name( 'announcements.announcement.create' );
            Route::get( '/show/{announcement}', [AnnouncementsController::class, 'show'] )
                ->name( 'announcements.announcement.show' );
            Route::get( '/{announcement}/edit', [AnnouncementsController::class, 'edit'] )
                ->name( 'announcements.announcement.edit' );
            Route::post( '/', [AnnouncementsController::class, 'store'] )
                ->name( 'announcements.announcement.store' );
            Route::put( 'announcement/{announcement}', [AnnouncementsController::class, 'update'] )
                ->name( 'announcements.announcement.update' );
            Route::delete( '/announcement/{announcement}', [AnnouncementsController::class, 'destroy'] )
                ->name( 'announcements.announcement.destroy' );
            Route::put( 'field/{announcement}', [AnnouncementsController::class, 'updateField'] )
                ->name( 'announcements.announcement.updateField' );
            Route::put( '/bulk', [AnnouncementsController::class, 'bulkAction'] )
                ->name( 'announcements.announcement.bulk' );

            Route::get( 'distinations/{function}', [AnnouncementsController::class, 'destinations'] )
                ->name( 'announcements.announcement.destinations' );
        } );

        Route::group( [
            'prefix' => 'time_groups',
        ], function () {
            Route::get( '/', [TimeGroupsController::class, 'index'] )
                ->name( 'time_groups.time_group.index' );
            Route::get( '/create', [TimeGroupsController::class, 'create'] )
                ->name( 'time_groups.time_group.create' );
            Route::get( '/show/{timeGroup}', [TimeGroupsController::class, 'show'] )
                ->name( 'time_groups.time_group.show' );
            Route::get( '/{timeGroup}/edit', [TimeGroupsController::class, 'edit'] )
                ->name( 'time_groups.time_group.edit' );
            Route::post( '/', [TimeGroupsController::class, 'store'] )
                ->name( 'time_groups.time_group.store' );
            Route::put( 'time_group/{timeGroup}', [TimeGroupsController::class, 'update'] )
                ->name( 'time_groups.time_group.update' );
            Route::delete( '/time_group/{timeGroup}', [TimeGroupsController::class, 'destroy'] )
                ->name( 'time_groups.time_group.destroy' );
            Route::put( 'field/{timeGroup}', [TimeGroupsController::class, 'updateField'] )
                ->name( 'time_groups.time_group.updateField' );
            Route::put( '/bulk', [TimeGroupsController::class, 'bulkAction'] )
                ->name( 'time_groups.time_group.bulk' );
        } );

        Route::group( [
            'prefix' => 'time_conditions',
        ], function () {
            Route::get( '/', [TimeConditionsController::class, 'index'] )
                ->name( 'time_conditions.time_condition.index' );

            Route::get( 'distinations/{function}', [TimeConditionsController::class, 'destinations'] )
                ->name( 'time_conditions.time_condition.destinations' );
            Route::get( '/create', [TimeConditionsController::class, 'create'] )
                ->name( 'time_conditions.time_condition.create' );
            Route::get( '/show/{timeCondition}', [TimeConditionsController::class, 'show'] )
                ->name( 'time_conditions.time_condition.show' );
            Route::get( '/{timeCondition}/edit', [TimeConditionsController::class, 'edit'] )
                ->name( 'time_conditions.time_condition.edit' );
            Route::post( '/', [TimeConditionsController::class, 'store'] )
                ->name( 'time_conditions.time_condition.store' );
            Route::put( 'time_condition/{timeCondition}', [TimeConditionsController::class, 'update'] )
                ->name( 'time_conditions.time_condition.update' );
            Route::delete( '/time_condition/{timeCondition}', [TimeConditionsController::class, 'destroy'] )
                ->name( 'time_conditions.time_condition.destroy' );
            Route::put( 'field/{timeCondition}', [TimeConditionsController::class, 'updateField'] )
                ->name( 'time_conditions.time_condition.updateField' );
            Route::put( '/bulk', [TimeConditionsController::class, 'bulkAction'] )
                ->name( 'time_conditions.time_condition.bulk' );
        } );

    });


    Route::group(['middleware' => 'permission:admin.campaign.*'], function(){
        Route::group( [
            'prefix' => 'dialer_campaigns',
        ], function () {
            Route::get( '/', [DialerCampaignsController::class, 'index'] )
                ->name( 'dialer_campaigns.dialer_campaign.index' );
            Route::get( '/create', [DialerCampaignsController::class, 'create'] )
                ->name( 'dialer_campaigns.dialer_campaign.create' );
            Route::get( '/show/{dialerCampaign}', [DialerCampaignsController::class, 'show'] )
                ->name( 'dialer_campaigns.dialer_campaign.show' );
            Route::get( '/{dialerCampaign}/edit', [DialerCampaignsController::class, 'edit'] )
                ->name( 'dialer_campaigns.dialer_campaign.edit' );
            Route::get( '/{dialerCampaign}/clone', [DialerCampaignsController::class, 'clone'] )
                ->name( 'dialer_campaigns.dialer_campaign.clone' );    
            Route::post( '/', [DialerCampaignsController::class, 'store'] )
                ->name( 'dialer_campaigns.dialer_campaign.store' );
            Route::put( 'dialer_campaign/{dialerCampaign}', [DialerCampaignsController::class, 'update'] )
                ->name( 'dialer_campaigns.dialer_campaign.update' );
            Route::delete( '/dialer_campaign/{dialerCampaign}', [DialerCampaignsController::class, 'destroy'] )
                ->name( 'dialer_campaigns.dialer_campaign.destroy' );
            Route::put( 'field/{dialerCampaign}', [DialerCampaignsController::class, 'updateField'] )
                ->name( 'dialer_campaigns.dialer_campaign.updateField' );
            Route::put( '/bulk', [DialerCampaignsController::class, 'bulkAction'] )
                ->name( 'dialer_campaigns.dialer_campaign.bulk' );
            Route::get( '/dialer_campaign/process/{campaignId}', [DialerCampaignsController::class, 'process'] )
                ->name( 'dialer_campaigns.dialer_campaign.process' );
            Route::get( '/dialer_campaign/run/{id}', [DialerCampaignsController::class, 'run'] )
                ->name( 'dialer_campaigns.dialer_campaign.run' );
            Route::get( '/dialer_campaign/get_contact/{id}', [DialerCampaignsController::class, 'getContact'] )
                ->name( 'dialer_campaigns.dialer_campaign.get_contact' );    
            Route::post('/dialer_campaign/update_campaign_call/{id}',[DialerCampaignsController::class, 'updateCampaignCall'] )
                ->name( 'dialer_campaigns.dialer_campaign.update_campaign_call' );    

            Route::post('/dialer_campaign/update_contact',[DialerCampaignsController::class, 'updateContact'] )
                ->name( 'dialer_campaigns.dialer_campaign.update_contact' );    
            
            Route::post('/dialer_campaign/form-data', [DialerCampaignsController::class, 'formData'])->name('dialer_campaigns.dialer_campaign.form_data');
            Route::get( '/dial', [DialerCampaignsController::class, 'dial'] )->name( 'dialer_campaigns.dialer_campaign.dial' );
            Route::get( '/hangup', [DialerCampaignsController::class, 'hangup'] )->name( 'dialer_campaigns.dialer_campaign.hangup' );
            Route::get( '/forward', [DialerCampaignsController::class, 'forward'] )->name( 'dialer_campaigns.dialer_campaign.forward' );
            Route::get('/send-sms', [DialerCampaignsController::class, 'sendSms'])->name('dialer_campaigns.dialer_campaign.send.sms');
        } );

        Route::group( [
            'prefix' => 'dialer_campaign_calls',
        ], function () {
            Route::get( '/', [DialerCampaignCallController::class, 'index'] )
                ->name( 'dialer_campaign_calls.dialer_campaign_call.index' );
            // Route::put( 'field/{campaignCall}', [CampaignCallsController::class, 'updateField'] )
            //     ->name( 'dialer_campaign_calls.dialer_campaign_call.updateField' );
            
            // Route::put( '/bulk/{campaign}', [CampaignCallsController::class, 'bulkAction'] )
            //     ->name( 'campaign_calls.campaign_call.bulk' );
        } );

        Route::group( [
            'prefix' => 'scripts',
        ], function () {
            Route::get( '/', [ScriptsController::class, 'index'] )
                ->name( 'scripts.script.index' );
            Route::get( '/create', [ScriptsController::class, 'create'] )
                ->name( 'scripts.script.create' );
            Route::get( '/show/{script}', [ScriptsController::class, 'show'] )
                ->name( 'scripts.script.show' );
            Route::get( '/{script}/edit', [ScriptsController::class, 'edit'] )
                ->name( 'scripts.script.edit' );
            Route::post( '/', [ScriptsController::class, 'store'] )
                ->name( 'scripts.script.store' );
            Route::put( 'script/{script}', [ScriptsController::class, 'update'] )
                ->name( 'scripts.script.update' );
            Route::delete( '/script/{script}', [ScriptsController::class, 'destroy'] )
                ->name( 'scripts.script.destroy' );
            Route::put( 'field/{script}', [ScriptsController::class, 'updateField'] )
                ->name( 'scripts.script.updateField' );
            Route::put( '/bulk', [ScriptsController::class, 'bulkAction'] )
                ->name( 'scripts.script.bulk' );
        } );
        
        Route::group([
            'prefix' => 'custom_forms',
        ], function () {
            Route::get('/', [CustomFormsController::class, 'index'])
                 ->name('custom_forms.custom_form.index');
            Route::get('/create', [CustomFormsController::class, 'create'])
                 ->name('custom_forms.custom_form.create');
            Route::get('/show/{customForm}',[CustomFormsController::class, 'show'])
                 ->name('custom_forms.custom_form.show');
            Route::get('/{customForm}/edit',[CustomFormsController::class, 'edit'])
                 ->name('custom_forms.custom_form.edit');
            Route::post('/', [CustomFormsController::class, 'store'])
                 ->name('custom_forms.custom_form.store');
            Route::put('custom_form/{customForm}', [CustomFormsController::class, 'update'])
                 ->name('custom_forms.custom_form.update');
            Route::delete('/custom_form/{customForm}',[CustomFormsController::class, 'destroy'])
                 ->name('custom_forms.custom_form.destroy');
            Route::put('field/{customForm}', [CustomFormsController::class, 'updateField'])
                 ->name('custom_forms.custom_form.updateField'); 
            Route::put('/bulk', [CustomFormsController::class, 'bulkAction'])
                 ->name('custom_forms.custom_form.bulk');            
        });

        Route::group( [
            'prefix' => 'contact_groups',
        ], function () {
            Route::get( '/', [ContactGroupsController::class, 'index'] )
                ->name( 'contact_groups.contact_group.index' );
            Route::get( '/create', [ContactGroupsController::class, 'create'] )
                ->name( 'contact_groups.contact_group.create' );
            Route::get( '/show/{contactGroup}', [ContactGroupsController::class, 'show'] )
                ->name( 'contact_groups.contact_group.show' )->where( 'id', '[0-9]+' );
            Route::get( '/{contactGroup}/edit', [ContactGroupsController::class, 'edit'] )
                ->name( 'contact_groups.contact_group.edit' )->where( 'id', '[0-9]+' );
            Route::post( '/', [ContactGroupsController::class, 'store'] )
                ->name( 'contact_groups.contact_group.store' );
            Route::put( 'contact_group/{contactGroup}', [ContactGroupsController::class, 'update'] )
                ->name( 'contact_groups.contact_group.update' )->where( 'id', '[0-9]+' );
            Route::delete( '/contact_group/{contactGroup}', [ContactGroupsController::class, 'destroy'] )
                ->name( 'contact_groups.contact_group.destroy' )->where( 'id', '[0-9]+' );
            Route::put( 'field/{contactGroup}', [ContactGroupsController::class, 'updateField'] )
                ->name( 'contact_groups.contact_group.updateField' );
            Route::put( '/bulk', [ContactGroupsController::class, 'bulkAction'] )
                ->name( 'contact_groups.contact_group.bulk' );
        } );

        Route::group( [
            'prefix' => 'contacts',
        ], function () {
            Route::get( '/', [ContactsController::class, 'index'] )
                ->name( 'contacts.contact.index' );
            Route::get( '/create', [ContactsController::class, 'create'] )
                ->name( 'contacts.contact.create' );
            Route::get( '/show/{contact}', [ContactsController::class, 'show'] )
                ->name( 'contacts.contact.show' )->where( 'id', '[0-9]+' );
            Route::get( '/{contact}/edit', [ContactsController::class, 'edit'] )
                ->name( 'contacts.contact.edit' )->where( 'id', '[0-9]+' );
            Route::post( '/', [ContactsController::class, 'store'] )
                ->name( 'contacts.contact.store' );
            Route::put( 'contact/{contact}', [ContactsController::class, 'update'] )
                ->name( 'contacts.contact.update' )->where( 'id', '[0-9]+' );
            Route::delete( '/contact/{contact}', [ContactsController::class, 'destroy'] )
                ->name( 'contacts.contact.destroy' )->where( 'id', '[0-9]+' );
            Route::put( 'field/{contact}', [ContactsController::class, 'updateField'] )
                ->name( 'contacts.contact.updateField' );
            Route::put( '/bulk', [ContactsController::class, 'bulkAction'] )
                ->name( 'contacts.contact.bulk' );
            Route::post( '/upload', [ContactsController::class, 'upload'] )
                ->name( 'contacts.contact.upload' );

            Route::get('/send-sms', [ContactsController::class, 'sendSms'] )
            ->name( 'contacts.contact.send_sms' );
        } );

        Route::group( [
            'prefix' => 'broadcasts',
        ], function () {
            Route::get( '/', [BroadcastsController::class, 'index'] )
                ->name( 'broadcasts.broadcast.index' );
            Route::get( '/create', [BroadcastsController::class, 'create'] )
                ->name( 'broadcasts.broadcast.create' );
            Route::get( '/show/{campaign}', [BroadcastsController::class, 'show'] )
                ->name( 'broadcasts.broadcast.show' )->where( 'id', '[0-9]+' );
            Route::get( '/{campaign}/edit', [BroadcastsController::class, 'edit'] )
                ->name( 'broadcasts.broadcast.edit' )->where( 'id', '[0-9]+' );
            Route::get( '/{campaign}/clone', [BroadcastsController::class, 'clone'] )
                ->name( 'broadcasts.broadcast.clone' )->where( 'id', '[0-9]+' );    
            Route::post( '/', [BroadcastsController::class, 'store'] )
                ->name( 'broadcasts.broadcast.store' );
            Route::put( 'campaign/{campaign}', [BroadcastsController::class, 'update'] )
                ->name( 'broadcasts.broadcast.update' )->where( 'id', '[0-9]+' );
            Route::delete( '/campaign/{campaign}', [BroadcastsController::class, 'destroy'] )
                ->name( 'broadcasts.broadcast.destroy' )->where( 'id', '[0-9]+' );
            Route::put( 'field/{campaign}', [BroadcastsController::class, 'updateField'] )
                ->name( 'broadcasts.broadcast.updateField' );
            Route::put( '/bulk', [BroadcastsController::class, 'bulkAction'] )
                ->name( 'broadcasts.broadcast.bulk' );
            Route::get( 'distinations/{function}', [BroadcastsController::class, 'destinations'] )
                ->name( 'broadcasts.broadcast.destinations' );
            Route::get( 'stats/{id}', [BroadcastsController::class, 'stats'] )
                ->name( 'broadcasts.broadcast.stats' );
            Route::post( 'stats/{id}', [BroadcastsController::class, 'run'] )
                ->name( 'broadcasts.broadcast.run' );    
        } );

        Route::group( [
            'prefix' => 'sms',
        ], function () {
            Route::get( '/', [SmsController::class, 'index'] )
                ->name( 'sms.sms.index' );
            Route::get( '/create', [SmsController::class, 'create'] )
                ->name( 'sms.sms.create' );
            Route::get( '/show/{sms}', [SmsController::class, 'show'] )
                ->name( 'sms.sms.show' );
            Route::get( '/{sms}/edit', [SmsController::class, 'edit'] )
                ->name( 'sms.sms.edit' );
            Route::post( '/', [SmsController::class, 'store'] )
                ->name( 'sms.sms.store' );
            Route::put( 'sms/{sms}', [SmsController::class, 'update'] )
                ->name( 'sms.sms.update' );
            Route::delete( '/sms/{sms}', [SmsController::class, 'destroy'] )
                ->name( 'sms.sms.destroy' );
            Route::put( 'field/{sms}', [SmsController::class, 'updateField'] )
                ->name( 'sms.sms.updateField' );
            Route::put( '/bulk', [SmsController::class, 'bulkAction'] )
                ->name( 'sms.sms.bulk' );
        } );

        Route::group( [
            'prefix' => 'surveys',
        ], function () {
            Route::get( '/', [SurveysController::class, 'index'] )
                ->name( 'surveys.survey.index' );
            Route::get( '/create', [SurveysController::class, 'create'] )
                ->name( 'surveys.survey.create' );
            Route::get( '/show/{survey}', [SurveysController::class, 'show'] )
                ->name( 'surveys.survey.show' );
            Route::get( '/{survey}/edit', [SurveysController::class, 'edit'] )
                ->name( 'surveys.survey.edit' );
            Route::post( '/', [SurveysController::class, 'store'] )
                ->name( 'surveys.survey.store' );
            Route::put( 'survey/{survey}', [SurveysController::class, 'update'] )
                ->name( 'surveys.survey.update' );
            Route::delete( '/survey/{survey}', [SurveysController::class, 'destroy'] )
                ->name( 'surveys.survey.destroy' );
            Route::put( 'field/{survey}', [SurveysController::class, 'updateField'] )
                ->name( 'surveys.survey.updateField' );
            Route::put( '/bulk', [SurveysController::class, 'bulkAction'] )
                ->name( 'surveys.survey.bulk' );
            Route::get( 'distinations/{function}', [SurveysController::class, 'destinations'] )
                ->name( 'surveys.survey.destinations' );
        } );
        
        Route::group( [
            'prefix' => 'leads',
        ], function () {
            Route::get( '/', [LeadsController::class, 'index'] )
                ->name( 'leads.lead.index' );
            Route::get( '/create', [LeadsController::class, 'create'] )
                ->name( 'leads.lead.create' );
            Route::get( '/show/{lead}', [LeadsController::class, 'show'] )
                ->name( 'leads.lead.show' );
            Route::get( '/{lead}/edit', [LeadsController::class, 'edit'] )
                ->name( 'leads.lead.edit' );
            Route::post( '/', [LeadsController::class, 'store'] )
                ->name( 'leads.lead.store' );
            Route::put( 'lead/{lead}', [LeadsController::class, 'update'] )
                ->name( 'leads.lead.update' );
            Route::delete( '/lead/{lead}', [LeadsController::class, 'destroy'] )
                ->name( 'leads.lead.destroy' );
            Route::put( 'field/{lead}', [LeadsController::class, 'updateField'] )
                ->name( 'leads.lead.updateField' );
            Route::put( '/bulk', [LeadsController::class, 'bulkAction'] )
                ->name( 'leads.lead.bulk' );
        } );

        Route::group( [
            'prefix' => 'calling',
        ], function () {
            Route::get( '/', [CallingController::class, 'index'] )->name( 'calling.index' );
            Route::post( '/call', [CallingController::class, 'calling'] )->name( 'calling.call' );
            Route::post( '/dial', [CallingController::class, 'dialing'] )->name( 'calling.dial' );
            Route::get( 'distinations/{function}', [CallingController::class, 'destinations'] )
                ->name( 'calling.destinations' );
        } );

    });

    Route::group(['middleware' => 'permission:admin.report.*'], function(){
        Route::group( [
            'prefix' => 'report',
        ], function () {
            Route::get( '/queue-stats', [ReportController::class, 'queueStats'] )->name( 'report.queue.stats' );
            Route::get( 'extensions/summery', [ReportController::class, 'extensionSummery'] )->name( 'report.extensions.summery' );
            Route::get( 'trunks/summery', [ReportController::class, 'trunkSummery'] )->name( 'report.trunks.summery' );
            Route::get( 'campaign', [ReportController::class, 'campaign'] )->name( 'report.campaign' );

        } );


    });

    Route::group( [
        'prefix' => 'applications',
        'middleware' => 'permission:admin.application.*'
    ], function () {
        Route::get( '/', [ApplicationsController::class, 'index'] )
            ->name( 'applications.application.index' );

        Route::get( 'distinations/{function}', [ApplicationsController::class, 'destinations'] )
            ->name( 'applications.application.destinations' );

        Route::get( '/create', [ApplicationsController::class, 'create'] )
            ->name( 'applications.application.create' );
        Route::get( '/show/{application}', [ApplicationsController::class, 'show'] )
            ->name( 'applications.application.show' );
        Route::get( '/{application}/edit', [ApplicationsController::class, 'edit'] )
            ->name( 'applications.application.edit' );
        Route::post( '/', [ApplicationsController::class, 'store'] )
            ->name( 'applications.application.store' );
        Route::put( 'application/{application}', [ApplicationsController::class, 'update'] )
            ->name( 'applications.application.update' );
        Route::delete( '/application/{application}', [ApplicationsController::class, 'destroy'] )
            ->name( 'applications.application.destroy' );
        Route::put( 'field/{application}', [ApplicationsController::class, 'updateField'] )
            ->name( 'applications.application.updateField' );
        Route::put( '/bulk', [ApplicationsController::class, 'bulkAction'] )
            ->name( 'applications.application.bulk' );
    } );

    Route::group([
		'prefix' => 'call_parkings',
        'middleware' => 'permission:admin.application.*'
	], function () {
		Route::get('/', [CallParkingsController::class, 'index'])
			 ->name('call_parkings.call_parking.index');
		Route::get('/create', [CallParkingsController::class, 'create'])
			 ->name('call_parkings.call_parking.create');
		Route::get('/show/{callParking}',[CallParkingsController::class, 'show'])
			 ->name('call_parkings.call_parking.show');
		Route::get('/{callParking}/edit',[CallParkingsController::class, 'edit'])
			 ->name('call_parkings.call_parking.edit');
		Route::post('/', [CallParkingsController::class, 'store'])
			 ->name('call_parkings.call_parking.store');
		Route::put('call_parking/{callParking}', [CallParkingsController::class, 'update'])
			 ->name('call_parkings.call_parking.update');
		Route::delete('/call_parking/{callParking}',[CallParkingsController::class, 'destroy'])
			 ->name('call_parkings.call_parking.destroy');
		Route::put('field/{callParking}', [CallParkingsController::class, 'updateField'])
			 ->name('call_parkings.call_parking.updateField'); 
		Route::put('/bulk', [CallParkingsController::class, 'bulkAction'])
			 ->name('call_parkings.call_parking.bulk');  
        Route::get('distinations/{function}', [CallParkingsController::class, 'destinations'] )
             ->name( 'call_parkings.call_parking.destinations' );          
	});

    Route::group( [
        'prefix' => 'custom_funcs',
        'middleware' => 'permission:admin.custom_function.*'
    ], function () {
        Route::get( '/', [CustomFuncsController::class, 'index'] )
            ->name( 'custom_funcs.custom_func.index' );
        Route::get( '/create/{func_lang}', [CustomFuncsController::class, 'create'] )
            ->name( 'custom_funcs.custom_func.create' );
        Route::get( '/show/{customFunc}', [CustomFuncsController::class, 'show'] )
            ->name( 'custom_funcs.custom_func.show' )->where( 'id', '[0-9]+' );
        Route::get( '/{customFunc}/edit', [CustomFuncsController::class, 'edit'] )
            ->name( 'custom_funcs.custom_func.edit' )->where( 'id', '[0-9]+' );
        Route::post( '/', [CustomFuncsController::class, 'store'] )
            ->name( 'custom_funcs.custom_func.store' );
        Route::put( 'custom_func/{customFunc}', [CustomFuncsController::class, 'update'] )
            ->name( 'custom_funcs.custom_func.update' )->where( 'id', '[0-9]+' );
        Route::delete( '/custom_func/{customFunc}', [CustomFuncsController::class, 'destroy'] )
            ->name( 'custom_funcs.custom_func.destroy' )->where( 'id', '[0-9]+' );
        Route::put( 'field/{customFunc}', [CustomFuncsController::class, 'updateField'] )
            ->name( 'custom_funcs.custom_func.updateField' );
        Route::put( '/bulk', [CustomFuncsController::class, 'bulkAction'] )
            ->name( 'custom_funcs.custom_func.bulk' );
    } );

    Route::get( 'dashboard', [DashboardController::class, 'index'] )->name( 'dashboard' )->middleware('permission:admin.dashboard.*');


    Route::group( [
        'prefix' => 'dialer',
    ], function () {
        Route::post( '/login', [DialerController::class, 'login'] )->name( 'dialer.login' );
        Route::get( '/logout', [DialerController::class, 'logout'] )->name( 'dialer.logout' );
        Route::get( '/login', [DialerController::class, 'loginForm'] )->name( 'dialer.login.form' );
        Route::get( '/', [DialerController::class, 'index'] )->name( 'dialer.index' );
        Route::get( '/dial', [DialerController::class, 'dial'] )->name( 'dialer.dial' );
        Route::get( '/hangup', [DialerController::class, 'hangup'] )->name( 'dialer.hangup' );
        Route::get( '/forward', [DialerController::class, 'forward'] )->name( 'dialer.forward' );
        Route::get( '/distinations/{function}', [DialerController::class, 'destinations'] )->name( 'dialer.destinations' );
        // Route::get('/end-call', [DialerController::class, 'endCall'])->name('dialer.end.call');
        
    });
    
    Route::group( ['middleware' => 'permission:admin.*'], function () {
        
        Route::group( [
            'prefix' => 'apis',
        ], function () {
            Route::get( '/', [ApisController::class, 'index'] )
                ->name( 'apis.api.index' );
    
            Route::get( '/api/logs/{api}', [ApisController::class, 'logs'] )
                ->name( 'apis.api.logs' );
    
            Route::get( '/create', [ApisController::class, 'create'] )
                ->name( 'apis.api.create' );
    
            Route::get( '/regenerate/{api}', [ApisController::class, 'regenrate'] )
                ->name( 'apis.api.regenerate' );
    
            Route::get( '/show/{api}', [ApisController::class, 'show'] )
                ->name( 'apis.api.show' );
            Route::get( '/{api}/edit', [ApisController::class, 'edit'] )
                ->name( 'apis.api.edit' );
            Route::post( '/', [ApisController::class, 'store'] )
                ->name( 'apis.api.store' );
            Route::put( 'api/{api}', [ApisController::class, 'update'] )
                ->name( 'apis.api.update' );
            Route::delete( '/api/{api}', [ApisController::class, 'destroy'] )
                ->name( 'apis.api.destroy' );
            Route::put( 'field/{api}', [ApisController::class, 'updateField'] )
                ->name( 'apis.api.updateField' );
            Route::put( '/bulk', [ApisController::class, 'bulkAction'] )
                ->name( 'apis.api.bulk' );
        } );
    
    
        Route::group( [
            'prefix' => 'funcs',
        ], function () {
            Route::get( '/', [FuncsController::class, 'index'] )
                ->name( 'funcs.func.index' );
            Route::get( '/create', [FuncsController::class, 'create'] )
                ->name( 'funcs.func.create' );
            Route::get( '/show/{func}', [FuncsController::class, 'show'] )
                ->name( 'funcs.func.show' )->where( 'id', '[0-9]+' );
            Route::get( '/{func}/edit', [FuncsController::class, 'edit'] )
                ->name( 'funcs.func.edit' )->where( 'id', '[0-9]+' );
            Route::post( '/', [FuncsController::class, 'store'] )
                ->name( 'funcs.func.store' );
            Route::put( 'func/{func}', [FuncsController::class, 'update'] )
                ->name( 'funcs.func.update' )->where( 'id', '[0-9]+' );
            Route::delete( '/func/{func}', [FuncsController::class, 'destroy'] )
                ->name( 'funcs.func.destroy' )->where( 'id', '[0-9]+' );
            Route::put( 'field/{func}', [FuncsController::class, 'updateField'] )
                ->name( 'funcs.func.updateField' );
            Route::put( '/bulk', [FuncsController::class, 'bulkAction'] )
                ->name( 'funcs.func.bulk' );
        } );
    
        

        Route::group( [
            'prefix' => 'pin_lists',
        ], function () {
            Route::get( '/', [PinListsController::class, 'index'] )
                ->name( 'pin_lists.pin_list.index' );
            Route::get( '/create', [PinListsController::class, 'create'] )
                ->name( 'pin_lists.pin_list.create' );
            Route::get( '/show/{pinList}', [PinListsController::class, 'show'] )
                ->name( 'pin_lists.pin_list.show' );
            Route::get( '/{pinList}/edit', [PinListsController::class, 'edit'] )
                ->name( 'pin_lists.pin_list.edit' );
            Route::post( '/', [PinListsController::class, 'store'] )
                ->name( 'pin_lists.pin_list.store' );
            Route::put( 'pin_list/{pinList}', [PinListsController::class, 'update'] )
                ->name( 'pin_lists.pin_list.update' );
            Route::delete( '/pin_list/{pinList}', [PinListsController::class, 'destroy'] )
                ->name( 'pin_lists.pin_list.destroy' );
            Route::put( 'field/{pinList}', [PinListsController::class, 'updateField'] )
                ->name( 'pin_lists.pin_list.updateField' );
            Route::put( '/bulk', [PinListsController::class, 'bulkAction'] )
                ->name( 'pin_lists.pin_list.bulk' );
        } );
    
        Route::get( 'dashboard/dialer', [DashboardController::class, 'dialer'] )->name( 'dashboard.dialer' );
        Route::get( 'dashboard/dialer_connect', [DashboardController::class, 'dialer_connect'] )->name( 'dashboard.dialer.connect' );
        Route::get( 'dashboard/dialer_dial', [DashboardController::class, 'dialer_dial'] )->name( 'dashboard.dialer.dial' );
        
        Route::group( ['prefix' => 'user'], function () {
            Route::get( '/profile', [UserController::class, 'index'] )->name( 'user.profile.index' );
            Route::put( '/profile/update', [UserController::class, 'update'] )->name( 'user.profile.update' );
            Route::get( '/change-password', [UserController::class, 'showChangePasswordForm'] )->name( 'user.change.password' );
            Route::post( '/change-password', [UserController::class, 'changePassword'] );
        } );
    
        Route::group( [
            'prefix' => 'users',
        ], function () {
            Route::get( '/', [UsersController::class, 'index'] )
                ->name( 'users.user.index' );
            Route::get( '/create', [UsersController::class, 'create'] )
                ->name( 'users.user.create' );
            Route::get( '/show/{user}', [UsersController::class, 'show'] )
                ->name( 'users.user.show' );
            Route::get( '/{user}/edit', [UsersController::class, 'edit'] )
                ->name( 'users.user.edit' );
    
            Route::post( '/', [UsersController::class, 'store'] )
                ->name( 'users.user.store' );
    
            Route::put( 'user/{user}', [UsersController::class, 'update'] )
                ->name( 'users.user.update' );
    
            Route::delete( '/user/{user}', [UsersController::class, 'destroy'] )
                ->name( 'users.user.destroy' );
            Route::put( 'field/{user}', [UsersController::class, 'updateField'] )
                ->name( 'users.user.updateField' );
            Route::put( '/bulk', [UsersController::class, 'bulkAction'] )
                ->name( 'users.user.bulk' );
    
            Route::get('show/{id}', [UsersController::class, 'show']);
            
        } );
    
        Route::group( [
            'prefix' => 'voice_files',
        ], function () {
            Route::get( '/', [VoiceFilesController::class, 'index'] )
                ->name( 'voice_files.voice_file.index' );
            Route::get( '/create', [VoiceFilesController::class, 'create'] )
                ->name( 'voice_files.voice_file.create' );
            Route::get( '/show/{voiceFile}', [VoiceFilesController::class, 'show'] )
                ->name( 'voice_files.voice_file.show' )->where( 'id', '[0-9]+' );
            Route::get( '/{voiceFile}/edit', [VoiceFilesController::class, 'edit'] )
                ->name( 'voice_files.voice_file.edit' )->where( 'id', '[0-9]+' );
            Route::post( '/', [VoiceFilesController::class, 'store'] )
                ->name( 'voice_files.voice_file.store' );
            Route::put( 'voice_file/{voiceFile}', [VoiceFilesController::class, 'update'] )
                ->name( 'voice_files.voice_file.update' )->where( 'id', '[0-9]+' );
            Route::delete( '/voice_file/{voiceFile}', [VoiceFilesController::class, 'destroy'] )
                ->name( 'voice_files.voice_file.destroy' )->where( 'id', '[0-9]+' );
            Route::put( 'field/{voiceFile}', [VoiceFilesController::class, 'updateField'] )
                ->name( 'voice_files.voice_file.updateField' );
            Route::put( '/bulk', [VoiceFilesController::class, 'bulkAction'] )
                ->name( 'voice_files.voice_file.bulk' );
    
            Route::get( '/play', [VoiceFilesController::class, 'play'] )
                ->name( 'voice_files.voice.play' );
    
            Route::get( '/record/preview', [VoiceFilesController::class, 'record'] )
                ->name( 'voice_files.voice.record.preview' );
    
            // Route::match( ['put', 'post'], '/voice_preview', [VoiceFilesController::class, 'tts_voice_preview'] )->name( 'voice_files.voice.preview' );
    
        } );
    
        Route::group( [
            'prefix' => 'sms_profiles',
        ], function () {
            Route::get( '/', [SmsProfilesController::class, 'index'] )
                ->name( 'sms_profiles.sms_profile.index' );
            Route::get( '/create', [SmsProfilesController::class, 'create'] )
                ->name( 'sms_profiles.sms_profile.create' );
            Route::get( '/show/{smsProfile}', [SmsProfilesController::class, 'show'] )
                ->name( 'sms_profiles.sms_profile.show' );
            Route::get( '/{smsProfile}/edit', [SmsProfilesController::class, 'edit'] )
                ->name( 'sms_profiles.sms_profile.edit' );
            Route::post( '/', [SmsProfilesController::class, 'store'] )
                ->name( 'sms_profiles.sms_profile.store' );
            Route::put( 'sms_profile/{smsProfile}', [SmsProfilesController::class, 'update'] )
                ->name( 'sms_profiles.sms_profile.update' );
            Route::delete( '/sms_profile/{smsProfile}', [SmsProfilesController::class, 'destroy'] )
                ->name( 'sms_profiles.sms_profile.destroy' );
            Route::put( 'field/{smsProfile}', [SmsProfilesController::class, 'updateField'] )
                ->name( 'sms_profiles.sms_profile.updateField' );
            Route::put( '/bulk', [SmsProfilesController::class, 'bulkAction'] )
                ->name( 'sms_profiles.sms_profile.bulk' );
        } );
    
        Route::group( [
            'prefix' => 'mail_profiles',
        ], function () {
            Route::get( '/', [MailProfilesController::class, 'index'] )
                ->name( 'mail_profiles.mail_profile.index' );
            Route::get( '/create', [MailProfilesController::class, 'create'] )
                ->name( 'mail_profiles.mail_profile.create' );
            Route::get( '/show/{mailProfile}', [MailProfilesController::class, 'show'] )
                ->name( 'mail_profiles.mail_profile.show' );
            Route::get( '/{mailProfile}/edit', [MailProfilesController::class, 'edit'] )
                ->name( 'mail_profiles.mail_profile.edit' );
            Route::post( '/', [MailProfilesController::class, 'store'] )
                ->name( 'mail_profiles.mail_profile.store' );
            Route::put( 'mail_profile/{mailProfile}', [MailProfilesController::class, 'update'] )
                ->name( 'mail_profiles.mail_profile.update' );
            Route::delete( '/mail_profile/{mailProfile}', [MailProfilesController::class, 'destroy'] )
                ->name( 'mail_profiles.mail_profile.destroy' );
            Route::put( 'field/{mailProfile}', [MailProfilesController::class, 'updateField'] )
                ->name( 'mail_profiles.mail_profile.updateField' );
            Route::put( '/bulk', [MailProfilesController::class, 'bulkAction'] )
                ->name( 'mail_profiles.mail_profile.bulk' );
        } );
    
    
    
        Route::group( [
            'prefix' => 'tts_profiles',
        ], function () {
            Route::get( '/', [TtsProfilesController::class, 'index'] )
                ->name( 'tts_profiles.tts_profile.index' );
            Route::get( '/create/{type}', [TtsProfilesController::class, 'create'] )
                ->name( 'tts_profiles.tts_profile.create' );
            Route::get( '/show/{ttsProfile}', [TtsProfilesController::class, 'show'] )
                ->name( 'tts_profiles.tts_profile.show' )->where( 'id', '[0-9]+' );
            Route::get( '/{ttsProfile}/edit', [TtsProfilesController::class, 'edit'] )
                ->name( 'tts_profiles.tts_profile.edit' )->where( 'id', '[0-9]+' );
            Route::post( '/', [TtsProfilesController::class, 'store'] )
                ->name( 'tts_profiles.tts_profile.store' );
            Route::put( 'tts_profile/{ttsProfile}', [TtsProfilesController::class, 'update'] )
                ->name( 'tts_profiles.tts_profile.update' )->where( 'id', '[0-9]+' );
            Route::delete( '/tts_profile/{ttsProfile}', [TtsProfilesController::class, 'destroy'] )
                ->name( 'tts_profiles.tts_profile.destroy' )->where( 'id', '[0-9]+' );
            Route::put( '/field/{ttsProfile}', [TtsProfilesController::class, 'updateField'] )
                ->name( 'tts_profiles.tts_profile.updateField' );
            Route::put( '/bulk', [TtsProfilesController::class, 'bulkAction'] )
                ->name( 'tts_profiles.tts_profile.bulk' );
    
            Route::get( '/tts_histories/{profile}', [TtsProfilesController::class, 'histories'] )
                ->name( 'tts_profiles.tts_profile.histories' );
    
            Route::put( '/bulk-histories', [TtsProfilesController::class, 'bulkActionHistory'] )
                ->name( 'tts_profiles.tts_histories.bulk' );
    
        } );
    
    
        Route::group( [
            'prefix' => 'call_records',
        ], function () {
    
            Route::get( '/{call_id}', [CallRecordsController::class, 'index'] )->name( 'call.records.index' );
            Route::get( '/preview/{id}', [CallRecordsController::class, 'preview'] )
                ->name( 'call.records.preview' );
        } );
    
        Route::group( [
            'prefix' => 'flows',
        ], function () {
            Route::get( '/', [FlowsController::class, 'index'] )
                ->name( 'flows.flow.index' );
            Route::get( '/create', [FlowsController::class, 'create'] )
                ->name( 'flows.flow.create' );
            Route::get( '/show/{flow}', [FlowsController::class, 'show'] )
                ->name( 'flows.flow.show' );
            Route::get( '/{flow}/edit', [FlowsController::class, 'edit'] )
                ->name( 'flows.flow.edit' );
            Route::post( '/', [FlowsController::class, 'store'] )
                ->name( 'flows.flow.store' );
            Route::put( 'flow/{flow}', [FlowsController::class, 'update'] )
                ->name( 'flows.flow.update' );
            Route::delete( '/flow/{flow}', [FlowsController::class, 'destroy'] )
                ->name( 'flows.flow.destroy' );
            Route::put( 'field/{flow}', [FlowsController::class, 'updateField'] )
                ->name( 'flows.flow.updateField' );
            Route::put( '/bulk', [FlowsController::class, 'bulkAction'] )
                ->name( 'flows.flow.bulk' );
        } );
    
        Route::group( [
            'prefix' => 'flow_actions',
        ], function () {
            Route::get( '/', [FlowActionsController::class, 'index'] )
                ->name( 'flow_actions.flow_action.index' );
            Route::get( '/create', [FlowActionsController::class, 'create'] )
                ->name( 'flow_actions.flow_action.create' );
            Route::get( '/show/{flowAction}', [FlowActionsController::class, 'show'] )
                ->name( 'flow_actions.flow_action.show' );
            Route::get( '/{flowAction}/edit', [FlowActionsController::class, 'edit'] )
                ->name( 'flow_actions.flow_action.edit' );
            Route::post( '/', [FlowActionsController::class, 'store'] )
                ->name( 'flow_actions.flow_action.store' );
            Route::put( 'flow_action/{flowAction}', [FlowActionsController::class, 'update'] )
                ->name( 'flow_actions.flow_action.update' );
            Route::delete( '/flow_action/{flowAction}', [FlowActionsController::class, 'destroy'] )
                ->name( 'flow_actions.flow_action.destroy' );
            Route::put( 'field/{flowAction}', [FlowActionsController::class, 'updateField'] )
                ->name( 'flow_actions.flow_action.updateField' );
            Route::put( '/bulk', [FlowActionsController::class, 'bulkAction'] )
                ->name( 'flow_actions.flow_action.bulk' );
        } );
    
        Route::get( 'licence', [LicenceManagerController::class, 'index'] )->name( 'licence' );
        // Route::get( 'render/{view}', [LicenceManagerController::class, 'render'] )->name( 'licence.render' );
        Route::post( 'licence', [LicenceManagerController::class, 'store'] );
        Route::post( 'licence/active', [LicenceManagerController::class, 'licenceActive'] )->name( 'licence.active' );
        Route::get( 'licence/sync', [LicenceManagerController::class, 'syncLicence'] )->name( 'licence.sync' );
    
    
    
        // JUNK ROUTE 
        Route::get('test/popup', function(){
            return view('test.popup');
        })->name('test.popup');
    
        Route::get( 'test', function () {

            auth()->user()->notify(new PushNotification([
                'type' => 0,
                'msg' => 'New notification added into queue'
            ]));


            // FunctionCall::send_to_websocket('campaign_51', ['status' => true]);
            
            // $playload = [
            //     "acode" => "30000230",
            //     "api_key" => "3a04fc1ce65eb911483f90ac6580b26aad94d820",
            //     "senderid" => "8809610935087",
            //     "type" => "text",
            //     "msg" => "This is test message",
            //     "contacts" => "+8801722882656",
            //     "transactionType" =>"T",
            //     "contentID" =>""
            // ];

            // $res = Http::post('https://api.rtcom.xyz/onetomany', $playload);

            // $smsProfile = SmsProfile::where('organization_id', auth()->user()->organization_id)->where('default', 1)->first();
            // return $smsProfile;
            // $data = [
            //     'from' => 'EasyPbx',
            //     'to' => '+8801518307641',
            //     'body' => 'hello are you there',
            //     'sms_profile' => $smsProfile
            // ];
            // $response = FunctionCall::send_sms($data);


            // return $response;
    
        } );
    
 
    } );

    Route::group([
        'prefix' => 'notifications',
    ], function () {
        Route::get('/', [NotificationsController::class, 'index'])
             ->name('notifications.notification.index');

         Route::get('/seen/{ids}', [NotificationsController::class, 'seenNotification'])
             ->name('notifications.notification.seen');


       // Route::get('/create', [NotificationsController::class, 'create'])
       //      ->name('notifications.notification.create');
       // Route::get('/show/{notification}',[NotificationsController::class, 'show'])
       //      ->name('notifications.notification.show');
       // Route::get('/{notification}/edit',[NotificationsController::class, 'edit'])
       //      ->name('notifications.notification.edit');
       // Route::post('/', [NotificationsController::class, 'store'])
       //      ->name('notifications.notification.store');
       // Route::put('notification/{notification}', [NotificationsController::class, 'update'])
       //      ->name('notifications.notification.update');
        Route::delete('/notification/{notification}',[NotificationsController::class, 'destroy'])
             ->name('notifications.notification.destroy');
        Route::put('field/{notification}', [NotificationsController::class, 'updateField'])
             ->name('notifications.notification.updateField');
        Route::put('/bulk', [NotificationsController::class, 'bulkAction'])
             ->name('notifications.notification.bulk');
    });


    Route::group([
        'prefix' => 'voice_records',
    ], function () {
        Route::get('/', [VoiceRecordsController::class, 'index'])
             ->name('voice_records.voice_record.index');
        Route::get('/create', [VoiceRecordsController::class, 'create'])
             ->name('voice_records.voice_record.create');
        Route::get('/show/{voiceRecord}',[VoiceRecordsController::class, 'show'])
             ->name('voice_records.voice_record.show');
        Route::get('/{voiceRecord}/edit',[VoiceRecordsController::class, 'edit'])
             ->name('voice_records.voice_record.edit');
        Route::post('/', [VoiceRecordsController::class, 'store'])
             ->name('voice_records.voice_record.store');
        Route::put('voice_record/{voiceRecord}', [VoiceRecordsController::class, 'update'])
             ->name('voice_records.voice_record.update');
        Route::delete('/voice_record/{voiceRecord}',[VoiceRecordsController::class, 'destroy'])
             ->name('voice_records.voice_record.destroy');
        Route::put('field/{voiceRecord}', [VoiceRecordsController::class, 'updateField'])
             ->name('voice_records.voice_record.updateField'); 
        Route::put('/bulk', [VoiceRecordsController::class, 'bulkAction'])
             ->name('voice_records.voice_record.bulk');            
    });

    
    Route::group([
        'prefix' => 'tickets',
    ], function () {
        Route::get('/', [TicketsController::class, 'index'])
             ->name('tickets.ticket.index');
        Route::get('/create', [TicketsController::class, 'create'])
             ->name('tickets.ticket.create');
        Route::get('/show/{ticket}',[TicketsController::class, 'show'])
             ->name('tickets.ticket.show');
        Route::get('/{ticket}/edit',[TicketsController::class, 'edit'])
             ->name('tickets.ticket.edit');
        Route::post('/', [TicketsController::class, 'store'])
             ->name('tickets.ticket.store');
        Route::put('ticket/{ticket}', [TicketsController::class, 'update'])
             ->name('tickets.ticket.update');
        Route::delete('/ticket/{ticket}',[TicketsController::class, 'destroy'])
             ->name('tickets.ticket.destroy');
        Route::put('field/{ticket}', [TicketsController::class, 'updateField'])
             ->name('tickets.ticket.updateField'); 
        Route::put('/bulk', [TicketsController::class, 'bulkAction'])
             ->name('tickets.ticket.bulk');        
             
        Route::post('/ticket/follow_up/{ticket}', [TicketsController::class, 'followUp'])->name('tickets.follow_up.store');
    });

    
    
});






// Route::get('user/check/{id}', [UsersController::class, 'show']);