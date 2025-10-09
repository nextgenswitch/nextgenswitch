<!-- Modal -->
@push('css')
    <style>
        .dialer_table {
            width: 100%;
            font-size: 1.5em;
        }

        .dialer_table tr td {
            text-align: center;
            height: 50px;
            width: 33%;
        }

        .dialer_table #dialer_input_td {
            border-bottom: 1px solid #fafafa;
        }

        .dialer_table #dialer_input_td input {
            width: 100%;
            border: none;
            font-size: 1.6em;
            text-align: center;
        }

        /* Remove arrows from type number input : Chrome, Safari, Edge, Opera */
        .dialer_table #dialer_input_td input::-webkit-outer-spin-button,
        .dialer_table #dialer_input_td input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Remove arrows from type number input : Firefox */
        .dialer_table #dialer_input_td input[type=number] {
            -moz-appearance: textfield;
        }

        .dialer_table #dialer_input_td input::placeholder {
            /* Chrome, Firefox, Opera, Safari 10.1+ */
            color: #cccccc;
            opacity: 1;
            /* Firefox */
        }

        .dialer_table #dialer_input_td input:-ms-input-placeholder {
            /* Internet Explorer 10-11 */
            color: #cccccc;
        }

        .dialer_table #dialer_input_td input::-ms-input-placeholder {
            /* Microsoft Edge */
            color: #cccccc;
        }

        .dialer_table .dialer_num_tr td {
            -webkit-touch-callout: none;
            /* iOS Safari */
            -webkit-user-select: none;
            /* Safari */
            -khtml-user-select: none;
            /* Konqueror HTML */
            -moz-user-select: none;
            /* Old versions of Firefox */
            -ms-user-select: none;
            /* Internet Explorer/Edge */
            user-select: none;
            /* Non-prefixed version, currently supported by Chrome, Edge, Opera and Firefox */
        }

        .dialer_table .dialer_num_tr td:nth-child(1) {
            border-right: 1px solid #fafafa;
        }

        .dialer_table .dialer_num_tr td:nth-child(3) {
            border-left: 1px solid #fafafa;
        }

        .dialer_table .dialer_num_tr:nth-child(1) td,
        .dialer_table .dialer_num_tr:nth-child(2) td,
        .dialer_table .dialer_num_tr:nth-child(3) td,
        .dialer_table .dialer_num_tr:nth-child(4) td {
            border-bottom: 1px solid #fafafa;
        }

        .dialer_table .dialer_num_tr .dialer_num {
            color: #0B559F;
            cursor: pointer;
        }

        .dialer_table .dialer_num_tr .dialer_num:hover {
            background-color: #fafafa;
        }

        .dialer_table .dialer_del_td img {
            cursor: pointer;
        }

        .modal-header .close {
            padding: 1rem 1rem;
            margin: -1rem -1rem -1rem auto;
            outline: none;
        }

        /* RINGING */
        .calling-info p,
        .calling-table td p {
            padding-top: 0px !important;
            padding-bottom: 0px !important;
            margin-top: 0px !important;
            margin-bottom: 0px !important;
        }

        .calling-info h4,
        .calling-info p {
            margin-bottom: 0.5rem;
        }

        .calling-table td p span {
            font-size: 14px;
            font-weight: normal;
        }

        .calling-table td p i {
            font-size: 16px;
            font-weight: normal;
        }

        .call-end button {
            border-top-left-radius: 0px;
            border-top-right-radius: 0px;
        }

        .call-end button i {
            font-size: 18px;
            font-weight: 450;
        }

        .call-end .btn:hover {
            box-shadow: none !important;
            transform: none !important;
        }

        .call-options td {
            cursor: pointer;
            padding: 10px;
        }

        .call-options td.active {
            background-color: #f0f0f0;
        }

        #dtmf_pad {
            display: none;
            margin-top: 20px;
        }

        .dtmf_table {
            width: 100%;
            font-size: 1.5em;
        }

        .dtmf_table tr td {
            text-align: center;
            height: 50px;
            width: 33%;
            cursor: pointer;
        }

        .dtmf_table .dtmf_num:hover {
            background-color: #fafafa;
        }

        .error-message {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    </style>
@endpush

<div class="modal fade" id="dialer_modal" tabindex="-1" aria-labelledby="dialer_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dialer_modal_label">Dialer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table id="dialer_table" class="dialer_table">
                    <tr>
                        <td id="dialer_input_td" colspan="3"><input type="text" id="dialer_input" placeholder="">
                        </td>
                    </tr>
                    <tr class="dialer_num_tr">
                        <td class="dialer_num" onclick="dialerClick('dial', 1)">1</td>
                        <td class="dialer_num" onclick="dialerClick('dial', 2)">2</td>
                        <td class="dialer_num" onclick="dialerClick('dial', 3)">3</td>
                    </tr>
                    <tr class="dialer_num_tr">
                        <td class="dialer_num" onclick="dialerClick('dial', 4)">4</td>
                        <td class="dialer_num" onclick="dialerClick('dial', 5)">5</td>
                        <td class="dialer_num" onclick="dialerClick('dial', 6)">6</td>
                    </tr>
                    <tr class="dialer_num_tr">
                        <td class="dialer_num" onclick="dialerClick('dial', 7)">7</td>
                        <td class="dialer_num" onclick="dialerClick('dial', 8)">8</td>
                        <td class="dialer_num" onclick="dialerClick('dial', 9)">9</td>
                    </tr>
                    <tr class="dialer_num_tr">
                        <td class="dialer_del_td">
                            <img data-toggle="tooltip" data-placement="top" title="Clear" alt="clear"
                                onclick="dialerClick('clear', 'clear')"
                                src="data:image/svg+xml;base64,PHN2ZyBhcmlhLWhpZGRlbj0idHJ1ZSIgZm9jdXNhYmxlPSJmYWxzZSIgZGF0YS1wcmVmaXg9ImZhcyIgZGF0YS1pY29uPSJlcmFzZXIiIHJvbGU9ImltZyIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB2aWV3Qm94PSIwIDAgNTEyIDUxMiIgY2xhc3M9InN2Zy1pbmxpbmUtLWZhIGZhLWVyYXNlciBmYS13LTE2IGZhLTd4Ij48cGF0aCBmaWxsPSIjYjFiMWIxIiBkPSJNNDk3Ljk0MSAyNzMuOTQxYzE4Ljc0NS0xOC43NDUgMTguNzQ1LTQ5LjEzNyAwLTY3Ljg4MmwtMTYwLTE2MGMtMTguNzQ1LTE4Ljc0NS00OS4xMzYtMTguNzQ2LTY3Ljg4MyAwbC0yNTYgMjU2Yy0xOC43NDUgMTguNzQ1LTE4Ljc0NSA0OS4xMzcgMCA2Ny44ODJsOTYgOTZBNDguMDA0IDQ4LjAwNCAwIDAgMCAxNDQgNDgwaDM1NmM2LjYyNyAwIDEyLTUuMzczIDEyLTEydi00MGMwLTYuNjI3LTUuMzczLTEyLTEyLTEySDM1NS44ODNsMTQyLjA1OC0xNDIuMDU5em0tMzAyLjYyNy02Mi42MjdsMTM3LjM3MyAxMzcuMzczTDI2NS4zNzMgNDE2SDE1MC42MjhsLTgwLTgwIDEyNC42ODYtMTI0LjY4NnoiIGNsYXNzPSIiPjwvcGF0aD48L3N2Zz4="
                                width="22px" title="Clear" />
                        </td>
                        <td class="dialer_num" onclick="dialerClick('dial', 0)">0</td>
                        <td class="dialer_del_td">
                            <img data-toggle="tooltip" data-placement="top" title="Delete" alt="delete"
                                onclick="dialerClick('delete', 'delete')"
                                src="data:image/svg+xml;base64,PHN2ZyBhcmlhLWhpZGRlbj0idHJ1ZSIgZm9jdXNhYmxlPSJmYWxzZSIgZGF0YS1wcmVmaXg9ImZhciIgZGF0YS1pY29uPSJiYWNrc3BhY2UiIHJvbGU9ImltZyIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB2aWV3Qm94PSIwIDAgNjQwIDUxMiIgY2xhc3M9InN2Zy1pbmxpbmUtLWZhIGZhLWJhY2tzcGFjZSBmYS13LTIwIGZhLTd4Ij48cGF0aCBmaWxsPSIjREMxQTU5IiBkPSJNNDY5LjY1IDE4MS42NWwtMTEuMzEtMTEuMzFjLTYuMjUtNi4yNS0xNi4zOC02LjI1LTIyLjYzIDBMMzg0IDIyMi4wNmwtNTEuNzItNTEuNzJjLTYuMjUtNi4yNS0xNi4zOC02LjI1LTIyLjYzIDBsLTExLjMxIDExLjMxYy02LjI1IDYuMjUtNi4yNSAxNi4zOCAwIDIyLjYzTDM1MC4wNiAyNTZsLTUxLjcyIDUxLjcyYy02LjI1IDYuMjUtNi4yNSAxNi4zOCAwIDIyLjYzbDExLjMxIDExLjMxYzYuMjUgNi4yNSAxNi4zOCA2LjI1IDIyLjYzIDBMMzg0IDI4OS45NGw1MS43MiA1MS43MmM2LjI1IDYuMjUgMTYuMzggNi4yNSAyMi42MyAwbDExLjMxLTExLjMxYzYuMjUtNi4yNSA2LjI1LTE2LjM4IDAtMjIuNjNMNDE3Ljk0IDI1Nmw1MS43Mi01MS43MmM2LjI0LTYuMjUgNi4yNC0xNi4zOC0uMDEtMjIuNjN6TTU3NiA2NEgyMDUuMjZDMTg4LjI4IDY0IDE3MiA3MC43NCAxNjAgODIuNzRMOS4zNyAyMzMuMzdjLTEyLjUgMTIuNS0xMi41IDMyLjc2IDAgNDUuMjVMMTYwIDQyOS4yNWMxMiAxMiAyOC4yOCAxOC43NSA0NS4yNSAxOC43NUg1NzZjMzUuMzUgMCA2NC0yOC42NSA2NC02NFYxMjhjMC0zNS4zNS0yOC42NS02NC02NC02NHptMTYgMzIwYzAgOC44Mi03LjE4IDE2LTE2IDE2SDIwNS4yNmMtNC4yNyAwLTguMjktMS42Ni0xMS4zMS00LjY5TDU0LjYzIDI1NmwxMzkuMzEtMTM5LjMxYzMuMDItMy4wMiA3LjA0LTQuNjkgMTEuMzEtNC42OUg1NzZjOC44MiAwIDE2IDcuMTggMTYgMTZ2MjU2eiIgY2xhc3M9IiI+PC9wYXRoPjwvc3ZnPg=="
                                width="25px" title="Delete" />
                        </td>
                    </tr>
                </table>
                <div id="dialer_error" class="error-message"></div>
            </div>
            <div>
                <button id="make_call_btn" class="btn btn-block btn-primary">Call</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="calling_modal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="calling_modal_label">Calling</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="calling-info py-3 text-center">
                    <h4 id="call_name">Unknown</h4>
                    <p id="call_number">(000) 000-0000</p>
                    <p><span id="call_status" class="badge badge-danger">Calling</span></p>
                    <p id="call_duration">00:00</p>
                </div>

                <table class="calling-table call-options table-borderless">
                    <tr>
                        <td id="toggle_dtmf">
                            <p class="text-center"><i class="fa fa-keyboard-o"></i></p>
                            <p class="text-center"><span>Dial pad</span></p>
                        </td>
                        <td id="toggle_volume">
                            <p class="text-center"><i class="fa fa-volume-control-phone"></i></p>
                            <p class="text-center"><span>Volume</span></p>
                        </td>
                        <td id="add_contact">
                            <p class="text-center"><i class="fa fa-user-plus"></i></p>
                            <p class="text-center"><span>Add Contact</span></p>
                        </td>
                    </tr>
                    <tr>
                        <td id="toggle_hold">
                            <p class="text-center"><i class="fa fa-pause"></i></p>
                            <p class="text-center"><span>Hold</span></p>
                        </td>
                        <td id="toggle_headset">
                            <p class="text-center"><i class="fa fa-headphones"></i></p>
                            <p class="text-center"><span>Headset</span></p>
                        </td>
                        <td id="toggle_mute">
                            <p class="text-center"><i class="fa fa-bell-slash"></i></p>
                            <p class="text-center"><span>Mute</span></p>
                        </td>
                    </tr>
                </table>

                <div id="dtmf_pad">
                    <table class="dtmf_table">
                        <tr>
                            <td class="dtmf_num" onclick="sendDTMF(1)">1</td>
                            <td class="dtmf_num" onclick="sendDTMF(2)">2</td>
                            <td class="dtmf_num" onclick="sendDTMF(3)">3</td>
                        </tr>
                        <tr>
                            <td class="dtmf_num" onclick="sendDTMF(4)">4</td>
                            <td class="dtmf_num" onclick="sendDTMF(5)">5</td>
                            <td class="dtmf_num" onclick="sendDTMF(6)">6</td>
                        </tr>
                        <tr>
                            <td class="dtmf_num" onclick="sendDTMF(7)">7</td>
                            <td class="dtmf_num" onclick="sendDTMF(8)">8</td>
                            <td class="dtmf_num" onclick="sendDTMF(9)">9</td>
                        </tr>
                        <tr>
                            <td class="dtmf_num" onclick="sendDTMF('*')">*</td>
                            <td class="dtmf_num" onclick="sendDTMF(0)">0</td>
                            <td class="dtmf_num" onclick="sendDTMF('#')">#</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="call-end">
                <button id="end_call_btn" class="btn-block btn btn-danger">
                    <i class="fa fa-phone"></i>
                    <span>End Call</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Login Modal -->
<div class="modal fade" id="sip_login_modal" tabindex="-1" aria-labelledby="sip_login_modal_label"
    aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sip_login_modal_label">Agent Login</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="sip_login_form">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="sip_username">SIP Username</label>
                        <input type="text" class="form-control" id="sip_username" name="sip_username"
                            placeholder="Enter SIP Username" required>
                    </div>

                    <div class="form-group">
                        <label for="sip_password">SIP Password</label>
                        <input type="password" class="form-control" id="sip_password" name="sip_password"
                            placeholder="Enter SIP Password" required>
                    </div>
                    <div id="login_error" class="error-message"></div>
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('script')
    <script>
        let ws = null;
        let currentCallId = null;
        let dialedNumber = '';
        let callTimer = null;
        let callDuration = 0;
        let isMuted = false;
        let isOnHold = false;
        let isHeadset = false;

        function dialerClick(type, value) {
            let input = $('#dialer_input');
            let input_val = input.val();
            if (type === 'dial') {
                input.val(input_val + value);
            } else if (type === 'delete') {
                input.val(input_val.substring(0, input_val.length - 1));
            } else if (type === 'clear') {
                input.val("");
            }
        }

        function formatDuration(seconds) {
            const mins = Math.floor(seconds / 60);
            const secs = seconds % 60;
            return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }

        function startCallTimer() {
            callDuration = 0;
            $('#call_duration').text(formatDuration(callDuration));
            callTimer = setInterval(() => {
                callDuration++;
                $('#call_duration').text(formatDuration(callDuration));
            }, 1000);
        }

        function stopCallTimer() {
            if (callTimer) {
                clearInterval(callTimer);
                callTimer = null;
            }
        }

        function connectSIP(username, password) {
            ws = new WebSocket("ws://localhost:8775");

            ws.onopen = () => {
                ws.send(JSON.stringify({
                    action: "register",
                    username,
                    password
                }));
            };

            ws.onmessage = (event) => {
                const data = JSON.parse(event.data);
                console.log("SIP Event:", data);

                if (data.event === "registered") {
                    console.log(`SIP Registered as ${data.username}`);
                    $('#sip_login_modal').modal('hide');
                    $('#dialer_modal').modal('show');
                } else if (data.event === "registration_failed") {
                    $('#login_error').text(data.message || 'Registration failed. Please try again.');
                    $('#sip_login_modal').modal('show');
                } else if (data.event === "incoming_call") {
                    currentCallId = data.call_id;
                    dialedNumber = data.from; // For incoming, use caller number
                    $('#call_name').text(data.caller_name || 'Unknown Caller');
                    $('#call_number').text(data.from);
                    $('#call_status').removeClass('badge-danger badge-success').addClass('badge-warning').text(
                        'Incoming');
                    $('#calling_modal').modal('show');
                    // Show accept button (add dynamically if needed)
                    $('#end_call_btn').before(
                        '<button id="accept_call_btn" class="btn-block btn btn-success"><i class="fa fa-phone"></i> <span>Accept</span></button>'
                    );
                } else if (data.event === "outgoing_call") {
                    currentCallId = data.call_id;
                    $('#calling_modal').modal('show');
                } else if (data.event === "call_ringing") {
                    $('#call_status').removeClass('badge-danger badge-success').addClass('badge-warning').text(
                        'Ringing');
                } else if (data.event === "call_answered") {
                    $('#call_status').removeClass('badge-danger badge-warning').addClass('badge-success').text(
                        'In Call');
                    startCallTimer();
                    $('#accept_call_btn').remove(); // Remove accept if present
                } else if (data.event === "call_ended") {
                    $('#calling_modal').modal('hide');
                    stopCallTimer();
                    currentCallId = null;
                } else if (data.event === "dtmf_received") {
                    console.log("DTMF Tone Received:", data.tone);
                    // Optionally play sound or visual feedback
                } else if (data.event === "error") {
                    console.error("SIP Error:", data.message);
                    alert(data.message || 'An error occurred.');
                }
            };

            ws.onclose = () => {
                console.log("SIP Disconnected");
                // Optionally reconnect or show login again
            };

            ws.onerror = (error) => {
                console.error("WebSocket Error:", error);
                $('#login_error').text('Connection error. Please check the server.');
            };
        }

        function makeCall(to) {
            if (ws && ws.readyState === WebSocket.OPEN) {
                dialedNumber = to;
                $('#call_number').text(to);
                $('#call_name').text('Unknown'); // Could fetch name via API if available
                $('#call_status').removeClass('badge-success badge-warning').addClass('badge-danger').text('Calling');
                ws.send(JSON.stringify({
                    action: "make_call",
                    from: $('#sip_username').val(),
                    to
                }));
            } else {
                $('#dialer_error').text('Not connected. Please login again.');
            }
        }

        function answerCall() {
            if (ws && currentCallId) {
                ws.send(JSON.stringify({
                    action: "answer_call",
                    call_id: currentCallId
                }));
            }
        }

        function endCall() {
            if (ws && currentCallId) {
                ws.send(JSON.stringify({
                    action: "end_call",
                    call_id: currentCallId
                }));
            }
            $('#calling_modal').modal('hide');
            stopCallTimer();
            currentCallId = null;
        }

        function sendDTMF(tone) {
            if (ws && currentCallId) {
                ws.send(JSON.stringify({
                    action: "dtmf",
                    call_id: currentCallId,
                    tone
                }));
                // Optional: Play tone sound locally
                console.log(`DTMF sent: ${tone}`);
            }
        }

        function toggleMute() {
            isMuted = !isMuted;
            if (ws && currentCallId) {
                ws.send(JSON.stringify({
                    action: isMuted ? "mute" : "unmute",
                    call_id: currentCallId
                }));
            }
            $('#toggle_mute').toggleClass('active', isMuted);
        }

        function toggleHold() {
            isOnHold = !isOnHold;
            if (ws && currentCallId) {
                ws.send(JSON.stringify({
                    action: isOnHold ? "hold" : "unhold",
                    call_id: currentCallId
                }));
            }
            $('#toggle_hold').toggleClass('active', isOnHold);
        }

        function toggleHeadset() {
            isHeadset = !isHeadset;
            // Implement local audio routing if possible (WebRTC integration needed)
            console.log(`Headset mode: ${isHeadset ? 'On' : 'Off'}`);
            $('#toggle_headset').toggleClass('active', isHeadset);
        }

        // Event Listeners
        $('#sip_login_form').on('submit', function(e) {
            e.preventDefault();
            $('#login_error').text('');
            let username = $('#sip_username').val().trim();
            let password = $('#sip_password').val().trim();
            if (!username || !password) {
                $('#login_error').text('Please enter both username and password');
                return;
            }
            connectSIP(username, password);
        });

        $('#make_call_btn').on('click', function() {
            $('#dialer_error').text('');
            let number = $('#dialer_input').val().trim();
            if (!number) {
                $('#dialer_error').text('Please enter a phone number');
                return;
            }
            $('#dialer_modal').modal('hide');
            makeCall(number);
        });

        $('#end_call_btn').on('click', function() {
            endCall();
        });

        $(document).on('click', '#accept_call_btn', function() {
            answerCall();
        });

        $('#toggle_dtmf').on('click', function() {
            $('#dtmf_pad').toggle();
        });

        $('#toggle_mute').on('click', toggleMute);
        $('#toggle_hold').on('click', toggleHold);
        $('#toggle_headset').on('click', toggleHeadset);

        $('#toggle_volume').on('click', function() {
            console.log('Volume control toggled - implement slider if needed');
        });

        $('#add_contact').on('click', function() {
            console.log('Add contact for number:', dialedNumber);
            // Implement contact addition logic
        });

        // Show login modal on page load or as needed
        $(document).ready(function() {
            $('#sip_login_modal').modal('show');
        });
    </script>
@endpush
