<header class="app-header">



    <a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a>


    <!-- Navbar Right Menu-->
    <ul class="app-nav">


        <!--Notification Menu-->
        <li class="dropdown">
            
            <a class="app-nav__item show-notification" href="#" id="dailerCallButtonOnNav">

                <i data-feather="phone-outgoing"></i>
            </a>

            <div class="app-notification dropdown-menu dropdown-menu-right shadow-dropdown p-3">
                <form action="" id="phone-call">
                    @csrf
                    <div class="form-group">
                        <label for="to">To</label>
                        <input type="text" name="to" id="to" class="form-control"
                            placeholder="Destination Number">
                    </div>

                    <div class="form-group">
                        <label for="callback">Callback</label>
                        <input type="text" name="callback" id="callback" class="form-control"
                            placeholder="Sender Number">
                    </div>

                    <div class="form-group">
                        <input type="submit" value="Dial" class="btn btn-block btn-primary">
                    </div>
                </form>
            </div>
        </li>


        <li class="dropdown">
            <a class="app-nav__item maximize" href="#">
                <i data-feather="maximize"></i>
            </a>

            <a class="app-nav__item minimize" style="display: none;" href="#">
                <i data-feather="minimize"></i>
            </a>

        </li>




        <!--Notification Menu-->
        <li class="dropdown">

            <a class="app-nav__item show-notification" href="#" data-toggle="dropdown"
                aria-label="Show notifications">

                <i data-feather="bell"></i>

                @if (auth()->check() && auth()->user()->unreadNotifications()->count() > 0)
                    <span class="badge badge-warning"> {{ auth()->user()->unreadNotifications()->count() }} </span>
                @endif

            </a>


            <ul class="app-notification dropdown-menu dropdown-menu-right shadow-dropdown">

                @if (auth()->check())
                    <li class="app-notification__title">{{ __('You have') }}
                        {{ auth()->user()->unreadNotifications()->count() }} {{ __('new') }} <a href="#">
                            {{ __('notifications') }}</a>.</li>
                @endif

                <div class="app-notification__content">

                    @php
                        $notificationMessageTypes = ['fa-exclamation-circle', 'fa-exclamation-triangle', 'fa-check'];
                        $notificationColors = ['secodary', 'danger', 'success'];
                        $pushNotificationIds = [];

                    @endphp

                    @if (auth()->check())


                        @foreach (auth()->user()->unreadNotifications as $pushNotification)
                            @php $notification = $pushNotification->data; @endphp
                            <li>

                                <a class="app-notification__item" href="javascript:;"><span
                                        class="app-notification__icon"><span class="fa-stack fa-lg">
                                            <i
                                                class="fa fa-circle fa-stack-2x text-{{ $notificationColors[$notification['type']] }}"></i>
                                            <i
                                                class="fa {{ $notificationMessageTypes[$notification['type']] }} fa-stack-1x fa-inverse"></i></span></span>
                                    <div>
                                        <p class="app-notification__message">{{ $notification['msg'] }}</p>
                                        <p class="app-notification__meta">
                                            {{ $pushNotification->created_at->diffForHumans() }}</p>
                                    </div>
                                </a>
                            </li>
                        @endforeach

                    @endif


                </div>

                @if (auth()->check() && auth()->user()->unreadNotifications->count() > 0)
                    <li id="notification-params" class="app-notification__footer"><a
                            href="#">{{ __('See all notifications.') }}</a></li>
                @endif
            </ul>
        </li>


        <!-- User Menu-->
        <li class="dropdown">

            <a class="app-nav__item user-profile d-flex justify-content-center align-items-center" href="#"
                data-toggle="dropdown" aria-label="Open Profile Menu">
                <img src="{{ asset('images/profile.png') }}" height="48px" alt="">
                <div class="author">
                    @if (auth()->check())
                        <p class="name">{{ ucwords(auth()->user()->name) }}</p>
                    @endif

                    <span
                        class="title">{{ auth()->check() && auth()->user()->role == 'admin' ? __('Administrator') : __('Client') }}</span>
                </div>
            </a>

            <ul class="shadow-dropdown dropdown-menu settings-menu dropdown-menu-right">
             

                <li>
                    <a class="dropdown-item" href="{{ route('user.profile.index') }}">
                        <i data-feather="user"></i> {{ __('Profile') }}
                    </a>
                </li>

                <li>
                    <a class="dropdown-item" href="{{ route('user.change.password') }}">
                        <i data-feather="lock"></i> {{ __('Change Password') }}
                    </a>
                </li>
                @can('su.license.*')
                <li>
                    <a id="btn-lc-modal" class="dropdown-item" href="#" data-toggle="modal"
                        data-target="#licenseModal">
                        <i data-feather="key"></i> {{ __('Update License') }}
                    </a>
                </li>
                @endcan
                <li>
                    <form method="post" id="logout-form" action="{{ route('logout') }}"> @csrf </form>
                    <a class="dropdown-item"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        href="javascript::void(0)">
                        <i data-feather="log-out"></i> {{ __('Logout') }}
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</header>

<!-- Modal -->
<div class="modal fade" id="licenseModal" tabindex="-1" role="dialog" aria-labelledby="licenseModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="licenseModalLabel">Activate License</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="licence_ajax_content">
                @include('licences.active_form')

                @include('licences.notice')

                @include('licences.create_account_form')
            </div>
        </div>
    </div>
</div>




@push('script')
    <script>
        $(document).ready(function() {

            $(document).on("click", ".licence-toggle-form", function() {

                console.log('clicked licence form')

                $(".active-lc-form").each((index, item) => {
                    $(item).removeClass('active-lc-form');
                })

                if ($(this).hasClass('create-account')) {
                    $(this).closest('form').addClass('d-none');
                    $(this).closest('form').removeClass('active-lc-form');

                    $(".active-licence").closest('form').removeClass('d-none');
                    $(".active-licence").closest('form').addClass('active-lc-form');
                }


                if ($(this).hasClass('active-licence')) {
                    $(this).closest('form').addClass('d-none');
                    $(this).closest('form').removeClass('active-lc-form');

                    $(".create-account").closest('form').removeClass('d-none');
                    $(".create-account").closest('form').addClass('active-lc-form');
                }

                if ($(this).hasClass('reactive-licence')) {
                    $(".active-licence").closest('form').removeClass('d-none');
                    $(".active-licence").closest('form').addClass('active-lc-form');

                    $(this).closest('.notice').addClass('d-none');
                    $(".licence-form-submit-btn").removeClass('d-none');
                }

            });

            $(".licence-form-submit-btn").click(function(e) {
                e.preventDefault();

                var form = $(".active-lc-form");
                var phone = $("#cc").val() + $("#phone").val();

                var actionUrl = form.attr('action');
                $('<input>').attr({
                    type: 'hidden',
                    name: 'phone',
                    id: 'temp-phone',
                    value: phone
                }).appendTo('.active-lc-form');

                $.ajax({
                    type: "POST",
                    url: actionUrl,
                    data: form.serialize(), // serializes the form's elements.
                    success: function(data, message, xhr) {
                        console.log(data, message, xhr.status)

                        $("#temp-phone").remove();

                        if (data.status == 'error') {
                            console.log(data.errors)
                            clearErrorForm(form);

                            $.each(data.errors, (key, item) => {
                                console.log(key, item);

                                var input = form.find('#' + key).addClass('is-invalid');

                                var invalid_feedback = input.closest('.form-group')
                                    .find('.invalid-feedback strong');

                                invalid_feedback.text(item);

                            })
                        }

                        if (data.status == 'success') {
                            clearErrorForm(form);
                            resetForm(form);
                            $(".active-lc-form").closest('form').addClass('d-none');
                            $(".reactive-licence").closest('.notice').removeClass('d-none');
                        }

                    }
                });

            });
          

        function clearErrorForm(activeForm) {
            var is_invalids = activeForm.find('.is-invalid');

            is_invalids.each((index, item) => {
                $(item).removeClass('is-invalid');
            });

            var invalid_feedbacks = activeForm.find('.invalid-feedback strong')
            invalid_feedbacks.each((index, item) => {
                $(item).text('');
            });

        }

        function resetForm(activeForm) {
            activeForm.trigger("reset");
        }

        function showToast(message, success = true) {

            let toast = {
                title: (success) ? "Success" : "Failed",
                message: message,
                status: (success) ? TOAST_STATUS.SUCCESS : TOAST_STATUS.DANGER,
                timeout: 5000
            }

            Toast.create(toast);
        }


          /*   $("#to").keyup(function() {
                this.value = this.value.replace(/[^0-9\.]/g, '');
            });

            $("#callback").keyup(function() {
                this.value = this.value.replace(/[^0-9\.]/g, '');
            });

            $("#phone-call").submit(function(e) {
                e.preventDefault();

                var to = $("#to").val();
                var callback = $("#callback").val();

                if (to == undefined || to.length == 0) {
                    showToast('Please input a valid phone number.', false);
                }

                if (callback == undefined || callback.length == 0) {
                    showToast('Please input a valid callback phone number.', false);
                }

                if (to.length > 0 && callback.length > 0) {
                    var formData = $(this).serialize();
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('calling.dial') }}',
                        data: formData,
                        success: function(response) {
                            console.log(response);
                            // response = JSON.parse(response);

                            if (response['error'] != undefined && response['error'] == true) {

                                showToast(response['error_message'], false);


                            }

                            if (response['call_id'] != undefined) {
                                $("#phone-call").trigger('reset');
                                showToast('Call sent successfully');
                            }
                        },
                        error: function(error) {
                            console.error('Error occurred:', error);
                        }
                    });

                }



            });
 */
        });


    </script>
  
@endpush
