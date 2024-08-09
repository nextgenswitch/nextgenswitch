<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="app-brand d-flex justify-content-center align-items-center">
        <div class="brand-img">
            <i data-feather="command"></i>
        </div>
        <span class="brand-label">{{ env('APP_NAME') }}</span>
    </div>

    
    
    <ul class="app-menu">
        @can('admin.dashboard.*')
        <li>
            <a class="app-menu__item  d-flex align-items-center"
                href="{{ route('dashboard') }}">
                <i data-feather="home"></i>
                <span class="app-menu__label">{{ __('Dashboard') }}</span>
            </a>
        </li>
        @endcan
     
        @can('admin.monitoring.*')
        <li id="menu-monitoring" class="treeview">

            <a class="app-menu__item" href="#" data-toggle="treeview">
                <i data-feather="activity"></i>
                <span class="app-menu__label">{{ __('Monitoring') }}</span>
                <i class="treeview-indicator fa fa-angle-right"></i>
            </a>
           
            <ul class="treeview-menu">

                <li>
                    <a class="treeview-item" href="{{ route('monitoring.active.call') }}"><i
                            class="icon fa fa-angle-double-right"></i>
                        {{ __('Active Calls') }}</a>
                </li>

                <li>
                    <a class="treeview-item" href="{{ route('monitoring.call.history') }}"><i
                            class="icon fa fa-angle-double-right"></i>
                        {{ __('Call History') }}</a>
                </li>



                <li><a class="treeview-item" href="{{ route('monitoring.queue.call') }}"><i
                            class="icon fa fa-angle-double-right"></i> {{ __('Queue Calls') }}</a></li>

                <li><a class="treeview-item" href="{{ route('monitoring.log.call') }}"><i
                            class="icon fa fa-angle-double-right"></i> {{ __('Call Logs') }}</a></li>
                
                <li><a class="treeview-item" href="{{ route('monitoring.trunk.log') }}"><i
                                class="icon fa fa-angle-double-right"></i> {{ __('Trunk Logs') }}</a></li>

                <li><a class="treeview-item" href="{{ route('monitoring.sms.histories') }}"><i
                            class="icon fa fa-angle-double-right"></i> {{ __('SMS Logs') }}</a></li>
                
                <li><a class="treeview-item" href="{{ route('campaign_calls.campaign_call.index') }}"><i
                            class="icon fa fa-angle-double-right"></i> {{ __('Broadcast Logs') }}</a></li>

                <li><a class="treeview-item" href="{{ route('monitoring.active.sip') }}"><i
                            class="icon fa fa-angle-double-right"></i> {{ __('Active Channels') }}</a></li>

                <li><a class="treeview-item" href="{{ route('monitoring.voice_mails.index') }}"><i
                            class="icon fa fa-angle-double-right"></i> {{ __('Voice Mails') }}</a></li>

            </ul>
        </li>
        @endcan


        @can('admin.extension.*')
        <li class="treeview" id="menu-extensions">
            <a class="app-menu__item" href="#" data-toggle="treeview">
                <i data-feather="slack"></i>
                <span class="app-menu__label">{{ __('Extension') }}</span>
                <i class="treeview-indicator fa fa-angle-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a class="treeview-item" href="{{ route('extensions.extension.index') }}"><i
                            class="icon fa fa-angle-double-right"></i>
                        {{ __('Extension') }}</a>
                </li>

                <li><a class="treeview-item" href="{{ route('hotdesks.hotdesk.index') }}"><i
                            class="icon fa fa-angle-double-right"></i> {{ __('Hotdesk') }}</a>
                </li>

            </ul>
        </li>
        @endcan

        @can('admin.call_center.*')
        <li class="treeview " id="menu-callcenter">
            <a class="app-menu__item" href="#" data-toggle="treeview">
                <i data-feather="phone-call"></i>
                <span class="app-menu__label">{{ __('Call Center') }}</span>
                <i class="treeview-indicator fa fa-angle-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a class="treeview-item" href="{{ route('ring_groups.ring_group.index') }}"><i
                            class="icon fa fa-angle-double-right"></i>
                        {{ __('Ring Group') }}</a>
                </li>

                <li><a class="treeview-item" href="{{ route('call_queues.call_queue.index') }}"><i
                            class="icon fa fa-angle-double-right"></i> {{ __('Call Queue') }}</a>
                </li>

                

            </ul>
        </li>
        @endcan

        @can('admin.external.*')
        <li class="treeview " id="menu-external">
            <a class="app-menu__item" href="#" data-toggle="treeview">
                <i data-feather="repeat"></i>
                <span class="app-menu__label">{{ __('External') }}</span>
                <i class="treeview-indicator fa fa-angle-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a class="treeview-item" href="{{ route('inbound_routes.inbound_route.index') }}"><i
                            class="icon fa fa-angle-double-right"></i>
                        {{ __('Inbound Routes') }}</a>
                </li>
                <li><a class="treeview-item" href="{{ route('outbound_routes.outbound_route.index') }}"><i
                            class="icon fa fa-angle-double-right"></i> {{ __('Outbound Routes') }}</a>
                </li>

                <li><a class="treeview-item" href="{{ route('trunks.trunk.index') }}">
                        <i class="icon fa fa-angle-double-right"></i> {{ __('Trunks') }}</a>
                </li>
            </ul>
        </li>
        @endcan

        @can('admin.incoming.*')
        <li class="treeview " id="menu-incoming">
            <a class="app-menu__item" href="#" data-toggle="treeview">
                <i data-feather="phone-incoming"></i>
                <span class="app-menu__label">{{ __('Incoming') }}</span>
                <i class="treeview-indicator fa fa-angle-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a class="treeview-item" href="{{ route('ivrs.ivr.index') }}"><i
                            class="icon fa fa-angle-double-right"></i>
                        {{ __('IVR') }}</a>
                </li>

                <li><a class="treeview-item" href="{{ route('announcements.announcement.index') }}"><i
                            class="icon fa fa-angle-double-right"></i> {{ __('Announcements') }}</a>
                </li>

                <li><a class="treeview-item" href="{{ route('time_groups.time_group.index') }}"><i
                            class="icon fa fa-angle-double-right"></i> {{ __('Time Groups') }}</a>
                </li>

                <li><a class="treeview-item" href="{{ route('time_conditions.time_condition.index') }}"><i
                            class="icon fa fa-angle-double-right"></i> {{ __('Time Conditions') }}</a>
                </li>

            </ul>
        </li>
        @endcan

        @can('admin.campaign.*')
        <li class="treeview " id="menu-campaign">
            <a class="app-menu__item" href="#" data-toggle="treeview">
                <i data-feather="grid"></i>
                <span class="app-menu__label">{{ __('Campaigns') }}</span>
                <i class="treeview-indicator fa fa-angle-right"></i>
            </a>
            <ul class="treeview-menu">

                <li><a class="treeview-item" href="{{ route('dialer_campaigns.dialer_campaign.index') }}"><i
                    class="icon fa fa-angle-double-right"></i>
                {{ __('Campaigns') }}</a></li>

                <li><a class="treeview-item" href="{{ route('scripts.script.index') }}"><i
                    class="icon fa fa-angle-double-right"></i>
                {{ __('Scripts') }}</a></li>

                <li><a class="treeview-item" href="{{ route('custom_forms.custom_form.index') }}"><i
                    class="icon fa fa-angle-double-right"></i>
                {{ __('Custom Forms') }}</a></li>


                <li><a class="treeview-item" href="{{ route('broadcasts.broadcast.index') }}"><i
                            class="icon fa fa-angle-double-right"></i>
                        {{ __('Broadcasts') }}</a></li>

                
                        <li><a class="treeview-item" href="{{ route('surveys.survey.index') }}"><i
                            class="icon fa fa-angle-double-right"></i>
                        {{ __('Surveys') }}</a></li>


                <li><a class="treeview-item" href="{{ route('sms.sms.index') }}"><i
                            class="icon fa fa-angle-double-right"></i>
                        {{ __('SMS Templates') }}</a></li>

                <li><a class="treeview-item" href="{{ route('contacts.contact.index') }}"><i
                            class="icon fa fa-angle-double-right"></i>
                        {{ __('Contacts') }}</a></li>
                <li><a class="treeview-item" href="{{ route('contact_groups.contact_group.index') }}"><i
                            class="icon fa fa-angle-double-right"></i> {{ __('Contact Groups') }}</a></li>
                
                <li><a class="treeview-item" href="{{ route('leads.lead.index') }}"><i
                            class="icon fa fa-angle-double-right"></i> {{ __('Leads') }}</a></li>

                <li><a class="treeview-item" href="{{ route('calling.index') }}"><i
                            class="icon fa fa-angle-double-right"></i> {{ __('Send Call') }}</a>
                </li>



            </ul>
        </li>
        @endcan

        <li  class="treeview " id="menu-application">

            <a class="app-menu__item" href="#" data-toggle="treeview">
                <i data-feather="package"></i>
                <span class="app-menu__label">{{ __('Applications') }}</span>
                <i class="treeview-indicator fa fa-angle-right"></i>
            </a>

            <ul class="treeview-menu">


                @can('admin.*')
                <li><a class="treeview-item" href="{{ route('pin_lists.pin_list.index') }}"><i
                            class="icon fa fa-angle-double-right"></i> {{ __('Pin List') }}</a></li>
                @endcan

                <li><a class="treeview-item" href="{{ route('custom_funcs.custom_func.index') }}"><i
                            class="icon fa fa-angle-double-right"></i> {{ __('Custom Functions') }}</a></li>

                <li><a class="treeview-item" href="{{ route('applications.application.index') }}"><i
                            class="icon fa fa-angle-double-right"></i> {{ __('Applications') }}</a></li>

                @can('admin.*')
                <li><a class="treeview-item" href="{{ route('apis.api.index') }}"><i
                            class="icon fa fa-angle-double-right"></i> {{ __('API') }}</a></li>
                @endcan
                


            </ul>
        </li>

        @can('admin.report.*')
        <li class="treeview " id="menu-reports">

            <a class="app-menu__item" href="#" data-toggle="treeview">
                <i data-feather="bar-chart-2"></i>
                <span class="app-menu__label">{{ __('Reports') }}</span>
                <i class="treeview-indicator fa fa-angle-right"></i>
            </a>

            <ul class="treeview-menu">

                <li><a class="treeview-item" href="{{ route('report.extensions.summery') }}"><i
                            class="icon fa fa-angle-double-right"></i> {{ __('Extension Summery') }}</a></li>
                
                            <li><a class="treeview-item" href="{{ route('report.trunks.summery') }}"><i
                            class="icon fa fa-angle-double-right"></i> {{ __('Trunk Summery') }}</a></li>

                            <li><a class="treeview-item" href="{{ route('report.campaign', 0) }}"><i
                                class="icon fa fa-angle-double-right"></i> {{ __('Campaign Reports') }}</a></li>
                <li><a class="treeview-item" href="{{ route('report.surveys', 0) }}"><i
                                class="icon fa fa-angle-double-right"></i> {{ __('Survey Reports') }}</a></li>

            </ul>
        </li>
        @endcan

        @if (config('licence.multi_tenant') )
            
            @can('su.*')
            <li class="treeview" id="menu-multitenant">
                <a class="app-menu__item" href="#"  data-toggle="treeview">
                <i data-feather="share-2"></i>
                <span class="app-menu__label">{{ __('Multi-Tenant') }}</span>
                <i class="treeview-indicator fa fa-angle-right"></i>
                </a>

                <ul class="treeview-menu">
                    <li><a class="treeview-item" href="{{ route('organizations.organization.index') }}">
                    <i class="icon fa fa-angle-double-right"></i> {{ __('Tenants') }}</a></li>

                    <li><a class="treeview-item" href="{{ route('plans.plan.index') }}">
                    <i class="icon fa fa-angle-double-right"></i> {{ __('Plans') }}</a></li>
                </ul>
            </li>
            @endcan

        @endif

        @can('admin.*')
        <li>
            <a class="app-menu__item  d-flex align-items-center"
                href="{{ route('users.user.index') }}">
                <i data-feather="users"></i>
                <span class="app-menu__label">{{ __('Users') }}</span>
            </a>
        </li>
        @endcan
        
        @can('admin.*')
        <li class="treeview">
            <a class="app-menu__item" href="#" data-toggle="treeview">
                <i data-feather="settings"></i>
                <span class="app-menu__label">{{ __('Settings') }}</span>
                <i class="treeview-indicator fa fa-angle-right"></i>
            </a>

            <ul class="treeview-menu">

            <li><a class="treeview-item" href="{{ route('voice_files.voice_file.index') }}">
                <i class="icon fa fa-angle-double-right"></i> {{ __('Voice Files') }}</a></li>
            
                @can('su.*')
                <li><a class="treeview-item" href="{{ route('settings.setting.index', 'switch') }}"><i
                            class="icon fa fa-angle-double-right"></i>
                        {{ __('Swtich Settings') }}</a></li>
                @endcan


                <li><a class="treeview-item" href=""><i class="icon fa fa-angle-double-right"></i>
                        {{ __('Portal Settings') }}</a></li>

                
                @can('su.*')
                <li><a class="treeview-item" href="{{ route('settings.firewall.index', 'firewall') }}"><i
                            class="icon fa fa-angle-double-right"></i>
                        {{ __('Firewall Settings') }}</a></li>
                @endcan

                <li><a class="treeview-item" href="{{  route('sms_profiles.sms_profile.index') }}"><i class="icon fa fa-angle-double-right"></i>
                            {{ __('Sms Profiles') }}</a></li>

                <li><a class="treeview-item" href="{{  route('mail_profiles.mail_profile.index') }}"><i class="icon fa fa-angle-double-right"></i>
                            {{ __('Email Profiles') }}</a></li>
                <li><a class="treeview-item" href="{{ route('tts_profiles.tts_profile.index') }}"><i
                       class="icon fa fa-angle-double-right"></i>
                             {{ __('TTS Profile') }}</a></li>            

            </ul>
        </li>
        @endcan


    </ul>

 
   
</aside>
