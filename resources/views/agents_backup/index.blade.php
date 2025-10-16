<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SIP Web Dialer</title>

    <!-- Design deps (unchanged) -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet" />

    <!-- Functionality deps -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://webrtc.github.io/adapter/adapter-latest.js"></script>

    <style>
        :root {
            --primary-color: #5E6A9C;
            --secondary-color: #868E96;
            --background-color: #F4F6F9;
            --panel-bg-color: #ffffff;
            --text-color: #343A40;
            --border-color: #e9ecef;
            --green-status: #16C79A;
            --red-status: #E57373;
            --yellow-status: #FFB74D;
            --blue-status: #64B5F6;
        }

        button:focus {
            outline: none;
            box-shadow: none
        }

        body {
            font-family: "Lato", sans-serif;
            background: var(--background-color);
            color: var(--text-color);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 15px
        }

        #login-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, .6);
            backdrop-filter: blur(5px);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1050;
            opacity: 1;
            transition: opacity .3s
        }

        #login-overlay.hidden {
            opacity: 0;
            pointer-events: none
        }

        .login-container {
            background: var(--panel-bg-color);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, .2);
            width: 100%;
            max-width: 400px;
            text-align: center
        }

.login-progress {
            background: var(--panel-bg-color);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, .2);
            width: 100%;
            max-width: 400px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        .login-progress .spinner-border {
            width: 3rem;
            height: 3rem;
        }

        .login-container h2 {
            margin-bottom: 25px;
            color: var(--text-color);
            font-weight: 600
        }

        .login-container .form-control {
            height: 50px;
            border-radius: 8px;
            border: 2px solid var(--border-color)
        }

        .login-container .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: none
        }

        .login-container .btn-login {
            background: var(--primary-color);
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-size: 1.1rem;
            font-weight: 500
        }

        .dialer-container {
            max-width: 1000px;
            width: 100%;
            background: var(--panel-bg-color);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
            overflow: hidden;
            position: relative; /* for drawer overlay */
        }

        .dialer-header {
            background: var(--primary-color);
            color: #fff;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center
        }

        .agent-info .agent-name {
            font-weight: 700;
            font-size: 1.1rem
        }

        .agent-status {
            display: flex;
            align-items: center;
            font-size: .9rem
        }

        .status-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 8px;
            transition: background-color .3s
        }

        .status-indicator.available {
            background: var(--green-status)
        }

        .status-indicator.busy {
            background: var(--yellow-status)
        }

        .status-indicator.offline {
            background: var(--red-status)
        }

        .btn-header-action {
            background: transparent;
            border: none;
            color: #fff;
            font-size: 1.2rem;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            line-height: 1;
            transition: .2s
        }

        .btn-header-action:hover {
            background: rgba(255, 255, 255, .2)
        }

        .btn-dnd.active {
            background: var(--red-status);
            color: #fff
        }

        .btn-break.active {
            background: var(--yellow-status);
            color: #fff
        }

        .logout-btn {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, .5);
            color: #fff;
            padding: 8px 16px;
            border-radius: 25px;
            font-size: .9rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            transition: .2s
        }

        .logout-btn i {
            margin-right: 8px
        }

        .logout-btn:hover {
            background: #fff;
            color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, .1)
        }

        .incoming-call-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, .6);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000
        }

        .incoming-call-modal {
            background: var(--panel-bg-color);
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0, 0, 0, .2);
            animation: pulse 1.5s infinite
        }

        .incoming-call-modal h3 {
            margin-bottom: 10px;
            color: var(--secondary-color)
        }

        .incoming-caller-id {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 30px
        }

        .incoming-call-actions .btn {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            font-size: 1.8rem;
            margin: 0 15px;
            color: #fff;
            border: none
        }

        .btn-accept {
            background: var(--green-status)
        }

        .btn-decline {
            background: var(--red-status)
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(23, 162, 184, .7)
            }

            70% {
                box-shadow: 0 0 0 20px rgba(23, 162, 184, 0)
            }

            100% {
                box-shadow: 0 0 0 0 rgba(23, 162, 184, 0)
            }
        }

        .dialer-body {
            display: flex
        }

        .dialer-left-panel {
            flex: 1;
            padding: 25px;
            border-right: 1px solid var(--border-color)
        }

        .call-status-area {
            text-align: center;
            padding: 20px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            margin-bottom: 25px;
            min-height: 150px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            transition: .3s
        }

        .call-status-area.ringing {
            border-color: var(--blue-status);
            box-shadow: 0 0 10px rgba(23, 162, 184, .3)
        }

        .call-status-area.connected {
            border-color: var(--green-status);
            box-shadow: 0 0 10px rgba(40, 167, 69, .3)
        }

        .call-status-area.on-hold {
            border-color: var(--yellow-status);
            box-shadow: 0 0 10px rgba(255, 193, 7, .3)
        }

        .caller-id {
            font-size: 1.5rem;
            font-weight: 700
        }

        .call-timer {
            font-size: 1.2rem;
            color: var(--secondary-color)
        }

        .call-state {
            font-size: 1rem;
            font-weight: 700;
            text-transform: uppercase
        }

        .call-state.ringing {
            color: var(--blue-status)
        }

        .call-state.connected {
            color: var(--green-status)
        }

        .call-state.on-hold {
            color: var(--yellow-status)
        }

        .dtmf-sequence-display {
            min-height: 28px;
            font-size: 1.5rem;
            color: var(--secondary-color);
            letter-spacing: 4px;
            font-weight: 700;
            margin: 5px 0;
            word-break: break-all
        }

        .in-call-actions {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 15px
        }

        .in-call-actions .btn-call-action {
            background: #f6f8fa;
            border: none;
            color: #555;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 1.1rem;
            transition: .2s
        }

        .in-call-actions .btn-call-action:hover {
            background: #eef1f6;
            transform: translateY(-2px)
        }

        .in-call-actions .btn-call-action.active {
            background: var(--primary-color);
            color: #fff
        }

        .number-input-container {
            position: relative;
            margin-bottom: 20px
        }

        #phone-number-input {
            width: 100%;
            height: 60px;
            padding: 10px 45px 10px 20px;
            font-size: 2rem;
            text-align: center;
            letter-spacing: 2px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: #fcfdff
        }

        #phone-number-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 5px rgba(0, 123, 255, .3)
        }

        #phone-number-input:disabled {
            background: #f0f2f5;
            cursor: not-allowed
        }

        .backspace-btn {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            font-size: 1.5rem;
            color: var(--secondary-color);
            cursor: pointer
        }

        .backspace-btn:hover {
            color: var(--red-status)
        }

        .dialpad {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px
        }

        .dialpad-btn {
            height: 70px;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            background: var(--panel-bg-color);
            font-size: 1.8rem;
            font-weight: 700;
            transition: .2s
        }

        .dialpad-btn:hover {
            background: #f0f0f0;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, .05)
        }

        .dialpad-btn:active {
            transform: translateY(0);
            box-shadow: none
        }

        .dialpad-btn .sub-text {
            font-size: .8rem;
            color: var(--secondary-color);
            font-weight: 400
        }

        .call-actions {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
            height: 80px
        }

        .btn-main-action {
            width: 70px;
            height: 70px;
            border: none;
            border-radius: 50%;
            font-size: 1.8rem;
            transition: .2s;
            display: flex;
            align-items: center;
            justify-content: center
        }

        .btn-call {
            background: var(--green-status);
            color: #fff
        }

        .btn-call:hover {
            background: #218838
        }

        .btn-hangup {
            background: var(--red-status);
            color: #fff
        }

        .btn-hangup:hover {
            background: #c82333
        }

        .dialer-right-panel {
            flex: 0 0 350px;
            background: #fbfdff
        }

        .nav-tabs {
            border-bottom: 1px solid var(--border-color)
        }

        .nav-tabs .nav-link {
            border: none;
            border-bottom: 3px solid transparent;
            color: var(--secondary-color);
            font-weight: 700
        }

        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
            background: transparent
        }

        .tab-content {
            padding: 20px;
            height: calc(100vh - 120px);
            max-height: 550px;
            overflow-y: auto
        }

        .search-bar {
            margin-bottom: 20px;
            width: 100%;
        }

        .log-item,
        .contact-item {
            display: flex;
            align-items: center;
            padding: 12px 5px;
            border-bottom: 1px solid var(--border-color);
            transition: background-color .2s
        }

        .log-item:hover,
        .contact-item:hover {
            background: #eef1f6;
            cursor: pointer
        }

        .log-item .call-icon,
        .contact-item .contact-icon {
            font-size: 1.2rem;
            margin-right: 15px;
            width: 30px;
            text-align: center
        }

        .call-icon.incoming {
            color: var(--green-status)
        }

        .call-icon.outgoing {
            color: var(--primary-color)
        }

        .call-icon.missed {
            color: var(--red-status)
        }

        .item-details {
            flex: 1;
            line-height: 1.3
        }

        .item-name {
            font-weight: 700
        }

        .item-meta {
            font-size: .85rem;
            color: var(--secondary-color)
        }

        .call-button {
            background: transparent;
            border: none;
            color: var(--green-status);
            font-size: 1.5rem;
            opacity: 0;
            transform: scale(.8);
            transition: .2s
        }

        .contact-item:hover .call-button {
            opacity: 1;
            transform: scale(1)
        }

        @media (max-width:992px) {
            .dialer-body {
                flex-direction: column
            }

            .dialer-left-panel {
                border-right: none;
                border-bottom: 1px solid var(--border-color)
            }

            .dialer-right-panel {
                flex-basis: auto
            }

            .tab-content {
                max-height: 300px
            }
        }

        @media (max-width:768px) {
            body {
                padding: 0;
                align-items: flex-start
            }

            .dialer-container {
                border-radius: 0;
                min-height: 100vh
            }
        }

        /* hide technical sections */
        #remote-audio audio {
            display: block;
            margin-top: 6px;
            width: 100%
        }

        /* ====== NEW: Customer Drawer styling ====== */
        .drawer-scrim {
            position: absolute;
            inset: 0;
            background: rgba(52, 58, 64, 0.12);
            opacity: 0;
            pointer-events: none;
            transition: opacity .25s ease;
            z-index: 20;
        }

        .drawer-scrim.open {
            opacity: 1;
            pointer-events: auto
        }

        .customer-drawer {
            position: absolute;
            top: 72px;
            right: 0;
            bottom: 0;
            width: 360px;
            max-width: 85vw;
            background: #fbfdff;
            border-left: 1px solid var(--border-color);
            box-shadow: -10px 0 30px rgba(0, 0, 0, .08);
            transform: translateX(100%);
            transition: transform .28s ease;
            z-index: 21;
            display: flex;
            flex-direction: column
        }

        .customer-drawer.open {
            transform: translateX(0)
        }

        .drawer-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 16px;
            border-bottom: 1px solid var(--border-color);
            background: var(--panel-bg-color)
        }

        .drawer-title {
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
            font-size: 1.05rem
        }

        .drawer-close {
            border: none;
            background: transparent;
            color: var(--secondary-color);
            width: 36px;
            height: 36px;
            border-radius: 50%
        }

        .drawer-close:hover {
            background: #eef1f6;
            color: #222
        }

        .drawer-body {
            padding: 16px 16px 20px;
            overflow: auto
        }

        .profile-card {
            display: flex;
            align-items: center;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            background: #fff;
            padding: 12px 12px;
            margin-bottom: 14px
        }

        .profile-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e9edf7;
            color: #445;
            font-weight: 700;
            margin-right: 12px
        }

        .profile-name {
            font-weight: 700;
            margin: 0
        }

        .profile-sub {
            margin: 2px 0 0;
            color: var(--secondary-color);
            font-size: .9rem
        }

        .badge-tag {
            background: #eef1f6;
            color: #465;
            border: 1px solid var(--border-color);
            border-radius: 999px;
            padding: 2px 8px;
            font-size: .75rem;
            margin-left: 6px
        }

        .info-list {
            border: 1px solid var(--border-color);
            border-radius: 10px;
            background: #fff;
            margin-bottom: 14px
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 12px;
            border-bottom: 1px solid var(--border-color)
        }

        .info-row:last-child {
            border-bottom: none
        }

        .info-label {
            color: var(--secondary-color);
            font-size: .9rem
        }

        .info-value {
            font-weight: 700
        }

        .timeline {
            border: 1px solid var(--border-color);
            border-radius: 10px;
            background: #fff
        }

        .timeline h6 {
            padding: 10px 12px;
            border-bottom: 1px solid var(--border-color);
            margin: 0;
            color: #445
        }

        .tl-item {
            display: flex;
            align-items: flex-start;
            padding: 10px 12px;
            border-bottom: 1px solid var(--border-color)
        }

        .tl-item:last-child {
            border-bottom: none
        }

        .tl-icn {
            width: 26px;
            text-align: center;
            margin-right: 8px;
            color: var(--primary-color)
        }

        .tl-meta {
            font-size: .85rem;
            color: var(--secondary-color)
        }

        .call-icon.incoming { color: var(--green-status) }
        .call-icon.outgoing { color: var(--primary-color) }
        .call-icon.missed   { color: var(--red-status) }

        mark { background: #ffe9a8; padding: 0 .15em; border-radius: 3px; }

        /* Contact avatar (initials) */
        .contact-avatar {
          width: 36px;
          height: 36px;
          border-radius: 50%;
          display: inline-flex;
          align-items: center;
          justify-content: center;
          font-weight: 700;
          background: #e9edf7;
          color: #445;
          margin-right: 12px;
          flex: 0 0 36px;
        }

        /* Tighten contact row a bit on mobile */
        @media (max-width: 480px){
          .item-meta { font-size: 0.8rem; }
        }

        .in-call-actions .btn-call-action[disabled] {
          opacity: .45;
          cursor: not-allowed;
          pointer-events: none;
        }


        /* Use the same color classes the history list uses */
        .tl-icn .call-icon.incoming { color: var(--green-status); }
        .tl-icn .call-icon.outgoing { color: var(--primary-color); }
        .tl-icn .call-icon.missed   { color: var(--red-status); }

        /* You already show it for .contact-item; do the same for .log-item */
      .log-item .call-button {
        background: transparent;
        border: none;
        color: var(--green-status);
        font-size: 1.5rem;
        opacity: 0;
        transform: scale(.8);
        transition: .2s;
      }
      .log-item:hover .call-button {
        opacity: 1;
        transform: scale(1);
      }


    </style>
</head>

<body>

    <!-- Login Overlay -->
    <div id="login-overlay">
        <div class="login-container" id="login-container">
            <h2>Login</h2>
            <form id="login-form">
                <div class="alert alert-danger d-none" role="alert" id="login-alert">Invalid Username or Password.
                </div>
                <div class="form-group"><input type="text" class="form-control" id="agent-id" placeholder="Username"
                        required value="1000"></div>
                <div class="form-group"><input type="password" class="form-control" id="agent-password"
                        placeholder="Password" required value="123456"></div>
                <button type="submit" class="btn btn-primary btn-block btn-login">Login</button>
            </form>
        </div>
        <div class="login-progress d-none" id="webrtc-progress">
            <div class="spinner-border text-primary mb-3" role="status">
                <span class="sr-only">Connecting...</span>
            </div>
            <h4 class="text-primary" id="webrtc-progress-message">Preparing media connection...</h4>
            <p class="text-muted" id="webrtc-progress-subtext">We are finalizing your audio channel.</p>
        </div>
    </div>

    <!-- Main Dialer -->
    <div class="dialer-container">
        <div class="dialer-header">
            <div class="agent-info">
                <div class="agent-name">John Doe</div>
                <div class="agent-status">
                    <span id="status-indicator" class="status-indicator available"></span>
                    <span id="status-text">Available</span>
                </div>
            </div>
            <div class="header-actions d-flex align-items-center">
                <button id="break-btn" class="btn-header-action btn-break mr-2" title="Take a Break"><i
                        class="fas fa-coffee"></i></button>
                <button id="dnd-btn" class="btn-header-action btn-dnd mr-3" title="Do Not Disturb"><i
                        class="fas fa-bell-slash"></i></button>
                <button id="logout-btn" class="logout-btn"><i
                        class="fas fa-sign-out-alt"></i><span>Logout</span></button>
            </div>
        </div>

        <div class="dialer-body">
            <div class="dialer-left-panel">
                <div class="call-status-area" id="call-status-area">
                    <div id="call-info" class="d-none">
                        <div class="caller-id" id="caller-id-display"></div>
                        <div class="dtmf-sequence-display" id="dtmf-sequence-display"></div>
                        <div class="call-timer" id="call-timer-display">00:00</div>
                        <div class="call-state" id="call-state-display"></div>
                        <div class="in-call-actions mt-3">
                            <button class="btn btn-call-action" id="mute-btn" title="Mute"><i
                                    class="fas fa-microphone-slash"></i></button>
                            <button class="btn btn-call-action" id="hold-btn" title="Hold" disabled><i
                                    class="fas fa-pause"></i></button>
                            <button class="btn btn-call-action" id="transfer-btn" title="Transfer" disabled><i
                                    class="fas fa-exchange-alt"></i></button>
                            <button class="btn btn-call-action" id="conf-btn" title="Conference" disabled><i
                                    class="fas fa-users"></i></button>
                        </div>
                    </div>
                    <div id="welcome-message">
                        <h4 class="text-secondary">Ready to make a call</h4>
                    </div>
                </div>

                <div class="number-input-container">
                    <input type="text" id="phone-number-input" class="form-control" placeholder="Enter number..." />
                    <button class="backspace-btn" id="backspace-btn"><i class="fas fa-backspace"></i></button>
                </div>

                <div class="dialpad" id="dialpad">
                    <button class="btn dialpad-btn" data-value="1">1</button>
                    <button class="btn dialpad-btn" data-value="2">2 <span class="sub-text">ABC</span></button>
                    <button class="btn dialpad-btn" data-value="3">3 <span class="sub-text">DEF</span></button>
                    <button class="btn dialpad-btn" data-value="4">4 <span class="sub-text">GHI</span></button>
                    <button class="btn dialpad-btn" data-value="5">5 <span class="sub-text">JKL</span></button>
                    <button class="btn dialpad-btn" data-value="6">6 <span class="sub-text">MNO</span></button>
                    <button class="btn dialpad-btn" data-value="7">7 <span class="sub-text">PQRS</span></button>
                    <button class="btn dialpad-btn" data-value="8">8 <span class="sub-text">TUV</span></button>
                    <button class="btn dialpad-btn" data-value="9">9 <span class="sub-text">WXYZ</span></button>
                    <button class="btn dialpad-btn" data-value="*">*</button>
                    <button class="btn dialpad-btn" data-value="0">0</button>
                    <button class="btn dialpad-btn" data-value="#">#</button>
                </div>

                <div class="call-actions">
                    <button class="btn btn-main-action btn-call" id="call-btn" title="Call"><i
                            class="fas fa-phone"></i></button>
                    <button class="btn btn-main-action btn-hangup d-none" id="hangup-btn" title="Hang Up"><i
                            class="fas fa-phone-slash"></i></button>
                </div>

                <!-- Hidden but needed for remote audio playback -->
                <div id="remote-audio" class="mt-2"></div>
            </div>

            <div class="dialer-right-panel">
                <ul class="nav nav-tabs nav-fill" id="myTab" role="tablist">
                    <li class="nav-item"><a class="nav-link active" id="history-tab" data-toggle="tab"
                            href="#history" role="tab">History
                            <span id="history-refresh" class="ml-2" title="Refresh History" style="cursor: pointer;">
                                <i class="fas fa-sync-alt"></i> 
                            </span>
                            </a>
                          </li>
                    <li class="nav-item"><a class="nav-link" id="contacts-tab" data-toggle="tab" href="#contacts"
                            role="tab">Contacts
                            <span id="contacts-refresh" class="ml-2" title="Refresh History" style="cursor: pointer;">
                                <i class="fas fa-sync-alt"></i> 
                            </span>
                          </a></li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="history" role="tabpanel">
                      <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="search-bar">
                              <div class="input-group">
                                <input id="history-search" type="text" class="form-control" placeholder="Search history…" aria-label="Search history">
                                <div class="input-group-append">
                                  <button id="history-clear" class="btn btn-outline-secondary" type="button" title="Clear search">&times;</button>
                                </div>
                              </div>
                            </div>

                        
                      </div>

                      <div id="history-empty" class="text-center text-muted d-none" style="padding: 24px;">
                        <i class="fas fa-history mb-2" style="font-size: 1.6rem;"></i>
                        <div>No recent calls yet.</div>
                      </div>

                      <div id="history-loading" class="text-center text-secondary d-none" style="padding: 24px;">
                        <i class="fas fa-circle-notch fa-spin"></i> Loading call history…
                      </div>

                      <div id="history-error" class="alert alert-warning d-none" role="alert"></div>
                      <div id="history-nomatch" class="text-center text-muted d-none" style="padding: 24px;">
                            <i class="fas fa-search mb-2" style="font-size: 1.6rem;"></i>
                            <div>No calls match your search.</div>
                          </div>


                      <div class="log-list" id="history-list"></div>
                    </div>

                    <div class="tab-pane fade" id="contacts" role="tabpanel" aria-labelledby="contacts-tab">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                          <div class="search-bar w-100">
                            <div class="input-group">
                              <input id="contacts-search" type="text" class="form-control" placeholder="Search contacts…" aria-label="Search contacts">
                              <div class="input-group-append">
                                <button id="contacts-clear" class="btn btn-outline-secondary" type="button" title="Clear search">&times;</button>
                              </div>
                            </div>
                          </div>
                          <!-- <button id="contacts-refresh" class="btn btn-link ml-2" title="Refresh Contacts">
                            <i class="fas fa-sync-alt"></i>
                          </button> -->
                        </div>

                        <div id="contacts-empty" class="text-center text-muted d-none" style="padding: 24px;">
                          <i class="fas fa-address-book mb-2" style="font-size: 1.6rem;"></i>
                          <div>No contacts yet.</div>
                        </div>

                        <div id="contacts-loading" class="text-center text-secondary d-none" style="padding: 24px;">
                          <i class="fas fa-circle-notch fa-spin"></i> Loading contacts…
                        </div>

                        <div id="contacts-error" class="alert alert-warning d-none" role="alert"></div>
                        <div id="contacts-nomatch" class="text-center text-muted d-none" style="padding: 24px;">
                          <i class="fas fa-search mb-2" style="font-size: 1.6rem;"></i>
                          <div>No contacts match your search.</div>
                        </div>

                        <div id="contacts-list" class="contact-list"></div>
                      </div>

                </div>
            </div>

            <!-- ===== Drawer overlay & panel ===== -->
            <div id="drawer-scrim" class="drawer-scrim"></div>
            <aside id="customer-drawer" class="customer-drawer" aria-hidden="true">
                <div class="drawer-header">
                    <h5 class="drawer-title"><i class="fas fa-id-badge mr-2"></i>Customer Info</h5>
                    <button id="drawer-close" class="drawer-close" title="Close"><i class="fas fa-times"></i></button>
                </div>
                <div class="drawer-body">
                    <div class="profile-card">
                        <div id="cd-avatar" class="profile-avatar">U</div>
                        <div>
                            <p id="cd-name" class="profile-name">Unknown</p>
                            <p id="cd-sub" class="profile-sub">—</p>
                            <div id="cd-tags" class="mt-1"></div>
                        </div>
                    </div>

                    <div class="info-list">
                        <div class="info-row"><span class="info-label">Phone</span><span id="cd-phone"
                                class="info-value">—</span></div>
                        <div class="info-row"><span class="info-label">Email</span><span id="cd-email"
                                class="info-value">—</span></div>
                        <div class="info-row"><span class="info-label">Company</span><span id="cd-company"
                                class="info-value">—</span></div>
                        
                        <div class="info-row"><span class="info-label">Open Tickets</span><span id="cd-tickets"
                                class="info-value">0</span></div>
                    </div>

                    <div class="timeline">
                        <h6>Recent Interactions</h6>
                        <div id="cd-timeline"></div>
                    </div>
                </div>
            </aside>
            <!-- ===== /Drawer ===== -->

        </div>
    </div>

    <!-- Incoming Call Modal -->
    <div class="incoming-call-overlay" id="incoming-call-overlay">
        <div class="incoming-call-modal">
            <h3>Incoming Call</h3>
            <div class="incoming-caller-id" id="incoming-caller-id">(000) 000-0000</div>
            <div class="incoming-call-actions">
                <button class="btn btn-decline" id="decline-btn" title="Decline"><i
                        class="fas fa-phone-slash"></i></button>
                <button class="btn btn-accept" id="accept-btn" title="Accept"><i class="fas fa-phone"></i></button>
            </div>
        </div>
    </div>

    <!-- Ring & media assets -->
    <audio id="incoming-ring" preload="auto">
        <source src="https://janussg.nextgenswitch.com/incoming_call.mp3" type="audio/mpeg">
    </audio>

    <!-- Bootstrap JS (unchanged) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>
(() => {
  'use strict';

  /* =========================================================
   * Utilities (tiny, focused helpers – no behavior changes)
   * ======================================================= */
  const DEBUG = true;
  const _ts   = () => new Date().toISOString();
  const log   = {
    info: (...a) => DEBUG && console.log(`[${_ts()}]`, ...a),
    warn: (...a) => DEBUG && console.warn(`[${_ts()}]`, ...a),
    err:  (...a) => DEBUG && console.error(`[${_ts()}]`, ...a),
    grp:  (label, obj) => { if (!DEBUG) return; console.groupCollapsed(`[${_ts()}] ${label}`); try { console.log(obj); } finally { console.groupEnd(); } }
  };

  const escHtml = (s) =>
    String(s).replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));

  const debounce = (fn, ms = 250) => {
    let t = null;
    return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), ms); };
  };

  const fmtTime = (seconds) =>
    String(Math.floor(seconds / 60)).padStart(2, '0') + ':' + String(seconds % 60).padStart(2, '0');

  const highlight = (text, query) => {
    const safe = escHtml(text ?? '');
    const q = (query || '').trim();
    if (!q) return safe;
    const re = new RegExp(q.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'ig');
    return safe.replace(re, m => `<mark>${escHtml(m)}</mark>`);
  };

  const fetchJson = async (url, { signal } = {}) => {
    const res = await fetch(url, { headers: { 'Accept': 'application/json' }, signal });
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    return res.json();
  };

  /* =========================================================
   * CONFIG
   * ======================================================= */
  const WS_BASE          = 'wss://' + window.location.hostname + '/websocket/';
  const DEFAULT_REGISTRAR= 'sip:sg.nextgenswitch.com:8345';
  const DEFAULT_EXPIRES  = 60;
  const OFFER_PATH       = (typeof window !== 'undefined' && window.OFFER_PATH) ? window.OFFER_PATH : '/offer';

  /* =========================================================
   * WebRTC Session (unchanged behavior)
   * ======================================================= */
  class WebrtcSession extends EventTarget {
    constructor({ WsUrl = null } = {}) {
      super();
      this.WsUrl = WsUrl || Math.random().toString(36).substring(2, 15);
      this.pc = null;
      this.stream = null;
      this.lastSignal = null;
      this.lastStatusText = null;
    }
    setStatus(text){ const message = typeof text === 'string' ? text : ''; this.lastStatusText = message; this.emit('status', { message, status: message }); }
    emit(name, detail = {}){ const payload = { WsUrl: this.WsUrl, ...detail }; this.dispatchEvent(new CustomEvent(name, { detail: payload })); if (name) document.dispatchEvent(new CustomEvent(`webrtc${name[0].toUpperCase()}${name.slice(1)}`, { detail: payload })); }
    async connect(){
      this.setStatus('Requesting mic...');
      try {
        this.stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        this.setStatus('Mic captured, creating peer connection...');

        this.pc = new (window.RTCPeerConnection || RTCPeerConnection)();
        const pc = this.pc;

        this.stream.getTracks().forEach(track => pc.addTrack(track, this.stream));
        pc.ontrack = e => {
          const audio = document.createElement('audio');
          audio.autoplay = true; audio.controls = false; audio.srcObject = e.streams[0];
          (document.getElementById('remote-audio') || document.body).appendChild(audio);
        };
        pc.oniceconnectionstatechange = () => {
          if (!this.pc) return;
          const st = pc.iceConnectionState;
          if ((st === 'connected' || st === 'completed') && this.lastSignal !== 'connected') {
            this.lastSignal = 'connected'; this.setStatus(this.lastSignal); this.emit('connected', { state: st });
          }
          if ((st === 'disconnected' || st === 'failed' || st === 'closed') && this.lastSignal !== 'disconnected') {
            this.lastSignal = 'disconnected'; this.setStatus('WebRTC Disconnected'); this.emit('disconnected', { state: st });
          }
        };

        const offer = await pc.createOffer();
        await pc.setLocalDescription(offer);

        const ans = await (await fetch(OFFER_PATH, {
          method: 'POST', headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ sdp: pc.localDescription.sdp, type: pc.localDescription.type, WsUrl: this.WsUrl })
        })).json();

        if (!ans.sdp || !ans.type) throw new Error('Invalid answer from server');
        await pc.setRemoteDescription(new RTCSessionDescription(ans));
        this.setStatus('Starting ICE');
      } catch (err) {
        this.setStatus('Error: ' + err.message);
        this.lastSignal = 'disconnected';
        this.emit('disconnected', { state: 'error', message: err.message });
        this.cleanup();
        throw err;
      }
    }
    disconnect(reason = 'WebRTC Disconnected'){ this.lastSignal = 'disconnected'; this.cleanup(); this.setStatus(reason); this.emit('disconnected', { state: 'manual', reason }); }
    async waitForConnected({ timeoutMs = 60000 } = {}){
      if (this.lastSignal === 'connected') return;
      if (!this.pc) throw new Error('WebRTC session is not active');
      if (this.lastSignal === 'disconnected') throw new Error('WebRTC session already disconnected');
      await new Promise((resolve, reject) => {
        let timeoutId = null, settled = false;
        const done = () => { if (timeoutId) clearTimeout(timeoutId); this.removeEventListener('connected', onOk); this.removeEventListener('disconnected', onBad); };
        const onOk  = () => { if (settled) return; settled = true; done(); resolve(); };
        const onBad = (e)=> { if (settled) return; settled = true; done(); reject(new Error('WebRTC disconnected before establishing media connection' + (e?.detail?.message ? ': ' + e.detail.message : ''))); };
        this.addEventListener('connected', onOk);
        this.addEventListener('disconnected', onBad);
        if (timeoutMs > 0) timeoutId = setTimeout(() => { if (settled) return; settled = true; done(); reject(new Error('Timed out waiting for WebRTC connection')); }, timeoutMs);
      });
    }
    cleanup(){
      try { if (this.pc){ this.pc.oniceconnectionstatechange = null; this.pc.ontrack = null; this.pc.close(); } } catch {}
      this.pc = null;
      try { if (this.stream){ this.stream.getTracks().forEach(t => { try { t.stop(); } catch {} }); } } catch {}
      this.stream = null;
    }
  }

  /* =========================================================
   * SIP over WS + WebRTC Dialer (unchanged behavior)
   * ======================================================= */
  class WebrtcDialer extends EventTarget {
    constructor(wsUrl){
      super();
      this.baseUrl = wsUrl;
      this.clientId = this._id();
      this.wsUrl = `${this.baseUrl}?;client_id=${this.clientId};channel=1`;
      const existingCallId = (typeof window !== 'undefined' && window.GLOBAL_CALL_ID) ? String(window.GLOBAL_CALL_ID) : null;
      this.callChannelId = existingCallId && existingCallId.length ? existingCallId : this._id();
      this.wsStatus = $('#ws-status');
      this.ws = null;
      this.registered = false;
      this.webrtcSession = null;
      this.activeCall = null;
      this.pendingIncoming = null;
      this.registerOptions = { reg_url: 'sip:yourdomain:5060', user: 'username', pass: 'password', expire: 60 };
      this._wantWebrtc = false;
      this._webrtcConnectPromise = null;
      this._webrtcReconnectTimer = null;
      this._updateGlobalMediaMetadata();
    }
    emit(name, detail = {}){ const payload = { wsUrl: this.wsUrl, ...detail, clientId: this.clientId }; this.dispatchEvent(new CustomEvent(name, { detail: payload })); document.dispatchEvent(new CustomEvent(`sipUa${name[0].toUpperCase()}${name.slice(1)}`, { detail: payload })); }
    _id(){ return Math.random().toString(36).substring(2, 15); }
    _getCallChannelId(){ if (!this.callChannelId) return this._setCallChannelId(null); return this.callChannelId; }
    _setCallChannelId(callId){ const normalized = (callId && String(callId).trim()) || null; this.callChannelId = normalized || this._id(); this._updateGlobalMediaMetadata(); return this.callChannelId; }
    _buildMediaWsUrl(){ const id = this._getCallChannelId(); return (this.baseUrl && id) ? `${this.baseUrl}?;client_id=${id};channel=2` : null; }
    _updateGlobalMediaMetadata(){ if (typeof window === 'undefined') return; window.GLOBAL_CALL_ID = this._getCallChannelId(); const mediaUrl = this._buildMediaWsUrl(); if (mediaUrl) window.GLOBAL_MEDIA_WS_URL = mediaUrl; }
    _shouldMaintainWebrtc(){ return this._wantWebrtc && this.ws && this.ws.readyState === WebSocket.OPEN; }
    _clearWebrtcReconnectTimer(){ if (this._webrtcReconnectTimer){ clearTimeout(this._webrtcReconnectTimer); this._webrtcReconnectTimer = null; } }
    _scheduleWebrtcReconnect(delayMs = 2000){
      if (!this._shouldMaintainWebrtc() || this._webrtcReconnectTimer) return;
      this._webrtcReconnectTimer = setTimeout(() => {
        this._webrtcReconnectTimer = null;
        if (!this._shouldMaintainWebrtc()) return;
        this.ensureWebrtcSession().catch(() => this._scheduleWebrtcReconnect(Math.min(delayMs * 2, 30000)));
      }, delayMs);
    }
    async ensureWebrtcSession({ force = false } = {}){
      this._wantWebrtc = true;
      if (!this.ws || this.ws.readyState !== WebSocket.OPEN) throw new Error('WebSocket signaling not connected');
      if (!force && this.webrtcSession && this.webrtcSession.lastSignal === 'connected') return this.webrtcSession;
      if (this._webrtcConnectPromise) return this._webrtcConnectPromise;
      let session = this.webrtcSession;
      if (!session || force || session.lastSignal === 'disconnected') session = this._createWebrtcSession({ WsUrl: this._buildMediaWsUrl() });
      this._webrtcConnectPromise = (async () => {
        try { await session.connect(); await session.waitForConnected({ timeoutMs: 60000 }); return session; }
        finally { this._webrtcConnectPromise = null; }
      })();
      return this._webrtcConnectPromise;
    }
    async webrtcConnect({ WsUrl = null, force = false } = {}){
      if (WsUrl && typeof WsUrl === 'string') {
        const match = WsUrl.match(/client_id=([^;]+)/);
        if (match && match[1] && match[1] !== this._getCallChannelId()){ this._setCallChannelId(match[1]); force = true; }
      }
      return this.ensureWebrtcSession({ force });
    }
    _createWebrtcSession({ WsUrl = null } = {}){
      this.disconnectWebrtc('Reinitializing WebRTC connection', { keepDesired: true });
      this._webrtcConnectPromise = null;
      const session = new WebrtcSession({ WsUrl: WsUrl || this._buildMediaWsUrl() });
      const forwardStatus     = (e) => this.emit('webrtcStatus', { ...(e?.detail ?? {}), session });
      const handleConnected   = (e) => { if (this.webrtcSession !== session) return; this._clearWebrtcReconnectTimer(); this._updateGlobalMediaMetadata(); this.emit('webrtcConnected', { ...(e?.detail ?? {}), session, active: true, message: (e?.detail?.message || 'WebRTC connected') }); };
      const handleDisconnected= (e) => {
        session.removeEventListener('status', forwardStatus);
        session.removeEventListener('connected', handleConnected);
        session.removeEventListener('disconnected', handleDisconnected);
        const wasActive = this.webrtcSession === session;
        if (wasActive) this.webrtcSession = null;
        this.emit('webrtcDisconnected', { ...(e?.detail ?? {}), session, active: wasActive, message: (e?.detail?.message || e?.detail?.reason || e?.detail?.state || 'WebRTC disconnected') });
        if (wasActive && this._shouldMaintainWebrtc()) this._scheduleWebrtcReconnect();
      };
      session.addEventListener('status', forwardStatus);
      session.addEventListener('connected', handleConnected);
      session.addEventListener('disconnected', handleDisconnected);
      this.webrtcSession = session;
      return session;
    }
    disconnectWebrtc(reason = 'WebRTC Disconnected', { keepDesired = false } = {}){
      if (!keepDesired) this._wantWebrtc = false;
      this._clearWebrtcReconnectTimer();
      try{ this.webrtcSession?.disconnect(reason); }catch{}
      this.webrtcSession = null;
      if (!keepDesired) this._webrtcConnectPromise = null;
    }
    _updateWsStatus(text){ if (!text) return; (this.wsStatus && typeof this.wsStatus.text === 'function') ? this.wsStatus.text(text) : $('#ws-status').text(text); }
    _clearRegisterTimer(){ if (this._registerTimer){ clearTimeout(this._registerTimer); this._registerTimer = null; } this._registerNextAt = null; }
    _scheduleRegisterRenew(expireSeconds){
      this._clearRegisterTimer();
      const seconds = Number(expireSeconds || this.registerOptions?.expire);
      if (!Number.isFinite(seconds) || seconds <= 0) return;
      const renewSeconds = Math.max(15, Math.floor(seconds * 0.85));
      this._registerTimer = setTimeout(() => { if (this.ws && this.ws.readyState === WebSocket.OPEN) this.register(); }, renewSeconds * 1000);
      this._registerNextAt = Date.now() + renewSeconds * 1000;
    }
    setRegisterOptions(options = {}){
      if (!options || typeof options !== 'object') return this.registerOptions;
      const normalizeSipUri = (uri) => {
        if (uri === undefined || uri === null) return uri;
        let value = String(uri).trim(); if (!value) return value;
        if (!/^sip:/i.test(value)) value = `sip:${value}`;
        const rest = value.slice(4);
        const atIndex = rest.lastIndexOf('@');
        const userPart = atIndex >= 0 ? rest.slice(0, atIndex + 1) : '';
        let hostPort = atIndex >= 0 ? rest.slice(atIndex + 1) : rest;
        let params = ''; const semiIndex = hostPort.indexOf(';');
        if (semiIndex !== -1) { params = hostPort.slice(semiIndex); hostPort = hostPort.slice(0, semiIndex); }
        let hasPort = false;
        if (hostPort.startsWith('[')){ const i = hostPort.indexOf(']'); if (i !== -1) hasPort = i < hostPort.length - 1 && hostPort.charAt(i + 1) === ':'; }
        else if (hostPort.includes(':')) hasPort = true;
        if (!hasPort && hostPort) hostPort = `${hostPort}:5060`;
        return `sip:${userPart}${hostPort}${params}`;
      };
      const next = { ...this.registerOptions };
      if (options.reg_url !== undefined && options.reg_url !== null) {
        const normalizedUrl = normalizeSipUri(options.reg_url);
        next.reg_url = normalizedUrl || (options.reg_url === '' ? '' : options.reg_url);
      }
      if (options.user) next.user = options.user;
      if (typeof options.pass === 'string') next.pass = options.pass;
      if (options.expire !== undefined) {
        const expire = Number(options.expire);
        if (Number.isFinite(expire) && expire > 0) next.expire = expire;
      }
      this.registerOptions = next;
      return next;
    }
    connect(){
      this._clearRegisterTimer();
      if (this.ws && this.ws.readyState === WebSocket.OPEN) { this._updateWsStatus('WebSocket: Already connected'); return; }
      this.ws = new WebSocket(this.wsUrl);
      this._updateWsStatus('WebSocket: Connecting...');
      this.ws.onopen   = () => { this._updateWsStatus('WebSocket: Connected'); this.emit('wsConnected', { readyState: this.ws.readyState, message: 'WebSocket connected' }); this.register(); };
      this.ws.onclose  = (e) => { this._updateWsStatus('WebSocket: Disconnected'); this._clearRegisterTimer(); this.registered = false; this.ws = null; this.disconnectWebrtc('WebRTC disconnected with signaling closed'); this.emit('wsClosed', { code: e?.code ?? null, reason: e?.reason ?? null, message: 'WebSocket disconnected', clientClosed: false }); };
      this.ws.onerror  = (e) => { this._updateWsStatus('WebSocket: Error'); this.emit('wsError', { message: 'WebSocket error', error: e }); };
      this.ws.onmessage= (msg)=> this.handleWsMessage(msg);
    }
    disconnect(reason = 'Client disconnected'){
      this._clearRegisterTimer();
      try{ this.ws?.close(); }catch{}
      this.ws = null; this.registered = false; this._updateWsStatus('WebSocket: Disconnected');
      this.emit('wsClosed', { message: reason, clientClosed: true });
      this.disconnectWebrtc('WebRTC disconnected by client');
    }
    isConnected(){ return this.ws && this.ws.readyState === WebSocket.OPEN; }
    isRegistered(){ return this.registered; }
    register(options = {}){
      const merged = this.setRegisterOptions(options);
      if (!this.ws || this.ws.readyState !== WebSocket.OPEN) { this._clearRegisterTimer(); if (this.ws && this.ws.readyState === WebSocket.CONNECTING) return; this.connect(); return; }
      if (!merged || !merged.reg_url || !merged.user || typeof merged.pass !== 'string') { console.error('Missing register options', merged); return; }
      const payload = { action: 'REGISTER', reg_url: merged.reg_url, user: merged.user, pass: merged.pass, channel_id: this.clientId, media_client_id: this._getCallChannelId() };
      this._wantWebrtc = false; this._clearWebrtcReconnectTimer(); this.registered = false;
      if (Number.isFinite(merged.expire) && merged.expire > 0) payload.expire = Math.floor(merged.expire);
      this._clearRegisterTimer();
      try { this.ws.send(JSON.stringify(payload)); } catch (err) { console.error('WebSocket register send failed:', err); return; }
      this.emit('registerSent', { message: 'REGISTER sent', options: { ...merged }, raw: payload });
    }
    async call(dest){
      if (!dest) { console.error('Missing call destination'); return; }
      if (!this.ws || this.ws.readyState !== WebSocket.OPEN) { console.error('WebSocket not open, cannot call'); return; }
      const callId = this._getCallChannelId();
      let session;
      try { session = await this.ensureWebrtcSession(); await session.waitForConnected({ timeoutMs: 60000 }); }
      catch (err) { console.error('Unable to establish WebRTC before dialing:', err); throw err; }
      const mediaWsUrl = this._buildMediaWsUrl();
      const callMsg = { channel_id: callId, action: 'CALL', dest };
      this.ws.send(JSON.stringify(callMsg));
      this.activeCall = { channelId: callId, direction: 'outgoing', destination: dest, session, mediaWsUrl };
      this.emit('callDialSent', { channelId: callId, destination: dest, mediaWsUrl, raw: callMsg });
    }
    async acceptIncoming(callInfo = null){
      if (!this.ws || this.ws.readyState !== WebSocket.OPEN) throw new Error('WebSocket not connected');
      const incoming = callInfo || this.pendingIncoming; if (!incoming) throw new Error('No incoming call metadata available');
      let channelId = incoming.callId || this._getCallChannelId();
      const existing = this._getCallChannelId(); const needsNew = !!channelId && channelId !== existing;
      if (needsNew) this._setCallChannelId(channelId); else channelId = existing;
      let session; try { session = await this.ensureWebrtcSession({ force: needsNew }); await session.waitForConnected({ timeoutMs: 60000 }); }
      catch (err) { console.error('Unable to establish WebRTC before accepting call:', err); throw err; }
      const acceptMsg = { channel_id: channelId, action: 'ACCEPT' }; this.ws.send(JSON.stringify(acceptMsg));
      const mediaWsUrl = this._buildMediaWsUrl();
      this.activeCall = { channelId, direction: 'incoming', session, mediaWsUrl, from: incoming.from ?? incoming.caller ?? null };
      this.emit('callAcceptSent', { channelId, raw: acceptMsg, incoming, mediaWsUrl });
      this.pendingIncoming = null;
      return { channelId, session, mediaWsUrl };
    }
    hangup(){
      if (!this.ws || this.ws.readyState !== WebSocket.OPEN) { console.error('WebSocket not open, cannot hangup'); return; }
      const channelId = this.activeCall?.channelId ?? this.clientId;
      this.ws.send(JSON.stringify({ channel_id: channelId, action: 'HANGUP' }));
      this.activeCall = null;
      this.emit('callHangupSent', { channelId });
    }
    sendDtmf(digits, { channelId = null, toneDuration = null, toneGap = null } = {}){
      if (digits === undefined || digits === null) return false;
      if (!this.ws || this.ws.readyState !== WebSocket.OPEN) return false;
      const sanitized = (Array.isArray(digits) ? digits.join('') : String(digits)).replace(/\s+/g, '');
      if (!sanitized) return false;
      const allowed = new Set('0123456789*#ABCD'.split(''));
      const normalizedDigits = sanitized.toUpperCase().split('').filter(ch => allowed.has(ch)).join('');
      if (!normalizedDigits) return false;
      const activeChannel = channelId || this.activeCall?.channelId || this.pendingIncoming?.callId || this.clientId;
      if (!activeChannel) return false;

      const msg = { action: 'DTMF', channel_id: activeChannel, digits: normalizedDigits };
      const d = Number(toneDuration), g = Number(toneGap);
      if (Number.isFinite(d) && d > 0) msg.duration = Math.floor(d);
      if (Number.isFinite(g) && g >= 0) msg.gap = Math.floor(g);

      try { this.ws.send(JSON.stringify(msg)); } catch (err) { this.emit('dtmfFailed', { channelId: activeChannel, digits: normalizedDigits, error: err, raw: msg }); return false; }
      this.emit('dtmfSent', { channelId: activeChannel, digits: normalizedDigits, raw: msg });
      return true;
    }
    handleWsMessage(msg){
      try {
        const data = JSON.parse(msg.data);
        if (data.event === 'REGISTER'){
          this.registered = data.status === 'SUCCESS';
          const serverCallId = data.call_id ?? data.callId ?? null;
          if (serverCallId) this._setCallChannelId(serverCallId); else this._updateGlobalMediaMetadata();

          const expire = Number(data.expire ?? this.registerOptions?.expire ?? NaN);
          const hasExpire = Number.isFinite(expire) && expire > 0;
          if (hasExpire) this.registerOptions.expire = expire;

          let message = 'REGISTER: Unknown status';
          if (data.status === 'SUCCESS')  message = `REGISTER SUCCESS: code=${data.code || ''} reason=${data.reason || ''}${hasExpire ? ` (expires in ${Math.round(expire)}s)` : ''}`;
          if (data.status === 'FAILED')   message = data.error !== undefined ? `REGISTER FAILED: error=${data.error}` : `REGISTER FAILED: code=${data.code || ''} reason=${data.reason || ''}`;

          const detail = { event: data.event, status: data.status, code: data.code ?? null, reason: data.reason ?? null, error: data.error ?? null, registered: this.registered, expire: hasExpire ? Math.round(expire) : null, callId: this._getCallChannelId(), mediaWsUrl: this._buildMediaWsUrl(), message, raw: data };
          this.emit('register', detail);

          if (data.status === 'SUCCESS'){
            if (hasExpire) this._scheduleRegisterRenew(expire); else this._clearRegisterTimer();
            if (this.ws && this.ws.readyState === WebSocket.OPEN) { this._wantWebrtc = true; this.ensureWebrtcSession().catch(() => this._scheduleWebrtcReconnect()); }
            this.emit('registerSuccess', detail);
          } else if (data.status === 'FAILED'){
            this._clearRegisterTimer(); this._wantWebrtc = false; this.disconnectWebrtc('WebRTC disconnected due to register failure'); this.emit('registerFailed', detail);
          } else {
            this._clearRegisterTimer(); this._wantWebrtc = false; this.emit('registerUnknown', detail);
          }
          return;
        }

        if (data.event === 'CALL'){
          let message = `Call status: ${data.status || 'UNKNOWN'}`;
          if (data.status === 'PROGRESS')    message = `Call ringing... code ${data.code || ''} reason ${data.reason || ''}`;
          else if (data.status === 'ESTABLISHED') message = 'Call answered';
          else if (data.status === 'FAILED')  message = `Call failed: ${data.reason || 'Unknown reason'}`;
          else if (data.status === 'HANGUP')  message = 'Call hung up';

          const callDetail = {
            event: data.event, status: data.status, code: data.code ?? null, reason: data.reason ?? null,
            destination: data.destination ?? null, callId: data.call_id ?? data.callId ?? null,
            channelId: data.channel_id ?? data.channelId ?? null, from: data.from ?? data.from ?? null,
            registered: this.registered, message, raw: data, mediaWsUrl: this._buildMediaWsUrl(),
            wsUrl: (data.ws_url !== undefined || data.wsUrl !== undefined) ? (data.ws_url ?? data.wsUrl) : undefined
          };

          if (callDetail.callId) this._setCallChannelId(callDetail.callId);
          this.emit('call', callDetail);

          switch (data.status){
            case 'PROGRESS':    this.emit('callProgress', callDetail); break;
            case 'ESTABLISHED':
              this.emit('callEstablished', callDetail);
              if (callDetail.channelId){
                this.activeCall = { ...(this.activeCall || {}), channelId: callDetail.channelId, session: this.webrtcSession, direction: this.activeCall?.direction || 'outgoing', mediaWsUrl: this._buildMediaWsUrl(), raw: callDetail };
              }
              break;
            case 'INCOMING':    this.emit('callIncoming', callDetail); this.pendingIncoming = callDetail; break;
            case 'FAILED':      this.emit('callFailed', callDetail); this.activeCall = null; this.pendingIncoming = null; break;
            case 'HANGUP':      this.emit('callHangup', callDetail); this.activeCall = null; this.pendingIncoming = null; break;
            default:            this.emit('callUnknown', callDetail);
          }
        }
      } catch (e) {
        console.log('WebSocket message (raw):', msg.data);
      }
    }
  }

  /* =========================================================
   * Drawer (Customer Info)
   * ======================================================= */
  const drawer = {
    el: null, scrim: null, refs: {},
    ensure(){
      if (this.el) return;
      this.el    = document.getElementById('customer-drawer');
      this.scrim = document.getElementById('drawer-scrim');
      this.refs  = {
        avatar:  document.getElementById('cd-avatar'),
        name:    document.getElementById('cd-name'),
        sub:     document.getElementById('cd-sub'),
        tags:    document.getElementById('cd-tags'),
        phone:   document.getElementById('cd-phone'),
        email:   document.getElementById('cd-email'),
        company: document.getElementById('cd-company'),
        tickets: document.getElementById('cd-tickets'),
        timeline:document.getElementById('cd-timeline'),
        closeBtn:document.getElementById('drawer-close')
      };
      this.scrim?.addEventListener('click', () => this.close(), { passive: true });
      this.refs.closeBtn?.addEventListener('click', () => this.close());
      document.addEventListener('keydown', (e) => { if (e.key === 'Escape') this.close(); });
    },
    populate(data){
      this.ensure();
      const callIconFor = (ev) => {
        const dir  = String(ev.direction||'').toLowerCase();
        const qual = String(ev.qualifier||'').toLowerCase();
        if (qual === 'missed')  return { fa: 'fa-phone-slash', cls: 'missed' };
        if (dir === 'incoming') return { fa: 'fa-reply',       cls: 'incoming' };
        if (dir === 'outgoing') return { fa: 'fa-share',       cls: 'outgoing' };
        return { fa: 'fa-comment-dots', cls: '' };
      };

      this.refs.avatar.textContent = (data.avatar || 'U').slice(0,2);
      this.refs.name.textContent   = data.name || 'Unknown';
      this.refs.sub.textContent    = (data.company || '') ? `${data.company} • ${data.email || '—'}` : (data.email || '—');
      this.refs.tags.innerHTML     = (data.tags || []).map(t => `<span class="badge-tag">${t}</span>`).join('');
      this.refs.phone.textContent  = data.phone  || '—';
      this.refs.email.textContent  = data.email  || '—';
      this.refs.company.textContent= data.company|| '—';
      this.refs.tickets.textContent= (data.tickets ?? 0);

      this.refs.timeline.innerHTML = (data.timeline || []).map(ev => {
        const { fa, cls } = callIconFor(ev);
        return `
          <div class="tl-item">
            <div class="tl-icn"><i class="fas ${fa} call-icon ${cls}"></i></div>
            <div>
              <div>${ev.note || ev.title || ''}</div>
              <div class="tl-meta">${ev.when || ''}</div>
            </div>
          </div>`;
      }).join('');
    },
    async openForNumber(num){
      const customerInfo = await fetchJson('/dialer/customer/lookup/' + encodeURIComponent(num));
      this.populate(customerInfo);
      this.open();
    },
    open(){ this.ensure(); this.el.classList.add('open'); this.scrim.classList.add('open'); this.el.setAttribute('aria-hidden','false'); },
    close(){ this.ensure(); this.el.classList.remove('open'); this.scrim.classList.remove('open'); this.el.setAttribute('aria-hidden','true'); }
  };

  /* =========================================================
   * App Wiring
   * ======================================================= */
  window.addEventListener('error', (e) => { log.err('window.error', e.message, 'at', e.filename+':'+e.lineno+':'+e.colno, e.error); });
  window.addEventListener('unhandledrejection', (e) => { log.err('unhandledrejection', e.reason); });

  $(function(){
    /* ---- Cache jQuery elements once ---- */
    const $loginOverlay     = $('#login-overlay');
    const $loginForm        = $('#login-form');
    const $loginAlert       = $('#login-alert');
    const $logoutBtn        = $('#logout-btn');
    const $dndBtn           = $('#dnd-btn');
    const $breakBtn         = $('#break-btn');

    const $phoneInput       = $('#phone-number-input');
    const $dialpad          = $('#dialpad');
    const $backspaceBtn     = $('#backspace-btn');
    const $callBtn          = $('#call-btn');
    const $hangupBtn        = $('#hangup-btn');
    const $callStatusArea   = $('#call-status-area');
    const $callInfo         = $('#call-info');
    const $welcome          = $('#welcome-message');
    const $welcomeH4        = $welcome.find('h4');

    const defaultWelcomeText= $welcomeH4.text().trim() || 'Ready to make a call';
    const welcomeBaseClasses= ($welcomeH4.attr('class') || '').split(/\s+/).filter(Boolean);
    const defaultWelcomeColorClass = welcomeBaseClasses.find(cls => cls.startsWith('text-')) || 'text-secondary';
    const staticWelcomeClasses     = welcomeBaseClasses.filter(cls => !cls.startsWith('text-'));
    const applyWelcomeClass = (colorClass) => {
      const classes = [...staticWelcomeClasses];
      if (colorClass && colorClass.length) classes.push(colorClass);
      $welcomeH4.attr('class', classes.join(' ') || null);
    };
    const setWelcomeMessage  = (text, colorClass = defaultWelcomeColorClass) => { applyWelcomeClass(colorClass); $welcomeH4.text(text || defaultWelcomeText); };
    const showWelcomeMessage = (text, colorClass) => { $welcome.removeClass('d-none'); setWelcomeMessage(text, colorClass); };
    const resetWelcomeMessage= () => setWelcomeMessage(defaultWelcomeText, defaultWelcomeColorClass);

    const $callerId    = $('#caller-id-display');
    const $callTimer   = $('#call-timer-display');
    const $callState   = $('#call-state-display');
    const $holdBtn     = $('#hold-btn');
    const $muteBtn     = $('#mute-btn');
    const $dtmfSeq     = $('#dtmf-sequence-display');

    const $loginContainer  = $('#login-container');
    const $webrtcProgress  = $('#webrtc-progress');
    const $webrtcMsg       = $('#webrtc-progress-message');
    const webrtcMsgDefault = $webrtcMsg.length ? ($webrtcMsg.text().trim() || 'Preparing media connection...') : 'Preparing media connection...';

    const setWebrtcProgressMessage = (text) => { if ($webrtcMsg.length) $webrtcMsg.text(text || webrtcMsgDefault); };
    const showLoginForm    = () => { $loginOverlay.removeClass('hidden'); $loginContainer.removeClass('d-none'); $webrtcProgress.addClass('d-none'); setWebrtcProgressMessage(webrtcMsgDefault); };
    const showWebrtcConnecting = (message = null) => { $loginContainer.addClass('d-none'); $webrtcProgress.removeClass('d-none'); setWebrtcProgressMessage(message || webrtcMsgDefault); $loginOverlay.removeClass('hidden'); };
    const hideLoginOverlay = () => { $loginOverlay.addClass('hidden'); $loginContainer.removeClass('d-none'); $webrtcProgress.addClass('d-none'); setWebrtcProgressMessage(webrtcMsgDefault); };

    const $incomingOverlay = $('#incoming-call-overlay');
    const $incomingCaller  = $('#incoming-caller-id');
    const $acceptBtn       = $('#accept-btn');
    const $declineBtn      = $('#decline-btn');
    const ringAudio        = document.getElementById('incoming-ring');

    let sipClient = null, isCallActive = false, isOnHold = false, isMuted = false, callTimer = null, elapsed = 0;

    const ensureClient = () => { if (!sipClient){ sipClient = new WebrtcDialer(WS_BASE); attachDialerListeners(sipClient); } return sipClient; };
    const setHeaderStatus = (txt, cls) => { $('#status-text').text(txt); $('#status-indicator').attr('class', 'status-indicator ' + cls); };

    const startTimer = () => { clearInterval(callTimer); elapsed = 0; $callTimer.text(fmtTime(elapsed)); callTimer = setInterval(() => { if (!isOnHold){ elapsed++; $callTimer.text(fmtTime(elapsed)); } }, 1000); };
    const stopTimer  = () => { clearInterval(callTimer); callTimer = null; };

    const showIncoming = (fromNumber) => {
      try { ringAudio.currentTime = 0; ringAudio.play().catch(() => {}); } catch {}
      $incomingCaller.text(fromNumber || 'Unknown');
      $acceptBtn.prop('disabled', false).removeClass('disabled').html('<i class="fas fa-phone"></i>');
      $incomingOverlay.css('display', 'flex');
    };
    const hideIncoming = () => { $incomingOverlay.css('display', 'none'); try { ringAudio.pause(); ringAudio.currentTime = 0; } catch {} };

    function updateCallState(state, number){
      $welcome.addClass('d-none');
      $callInfo.removeClass('d-none');
      $callerId.text(number || $callerId.text());
      $callState.text(state);

      $callStatusArea.removeClass('ringing connected on-hold');
      $callState.removeClass('ringing connected on-hold');

      if (state === 'Trying' || state === 'Ringing') { $callStatusArea.addClass('ringing'); $callState.addClass('ringing'); }
      if (state === 'Connected') { $callStatusArea.addClass('connected'); $callState.addClass('connected'); startTimer(); drawer.openForNumber($callerId.text()); }
      if (state === 'On Hold')   { $callStatusArea.addClass('on-hold');   $callState.addClass('on-hold'); }

      isCallActive = (state !== 'Idle');
      $phoneInput.prop('disabled', isCallActive);
      if (isCallActive){ $callBtn.addClass('d-none'); $hangupBtn.removeClass('d-none'); }
    }
    function resetToIdle(){
      stopTimer();
      $callInfo.addClass('d-none'); $welcome.removeClass('d-none');
      $callStatusArea.removeClass('ringing connected on-hold');
      $callState.text(''); $callerId.text('');
      $phoneInput.val('').prop('disabled', false);
      isCallActive = false; isOnHold = false; isMuted = false;
      $holdBtn.removeClass('active'); $muteBtn.removeClass('active'); $dtmfSeq.text('');
      $hangupBtn.addClass('d-none'); $callBtn.removeClass('d-none');
      drawer.close();
    }

    /* ---------- Events: Inputs & Actions ---------- */
    $dialpad.on('click', '.dialpad-btn', function(){
      const v = String($(this).data('value') ?? '');
      if (!v) return;
      if (isCallActive) {
        const ok = sipClient?.sendDtmf(v);
        if (ok) $dtmfSeq.text($dtmfSeq.text() + v);
      } else {
        $phoneInput.val($phoneInput.val() + v);
      }
    });
    $backspaceBtn.on('click', () => { if (!isCallActive) $phoneInput.val($phoneInput.val().slice(0, -1)); });

    $dndBtn.on('click', function(){ const active = $(this).toggleClass('active').hasClass('active'); setHeaderStatus(active ? 'Do Not Disturb' : 'Available', active ? 'offline' : 'available'); });
    $breakBtn.on('click', function(){ const active = $(this).toggleClass('active').hasClass('active'); setHeaderStatus(active ? 'On Break' : 'Available', active ? 'busy' : 'available'); });

    $loginForm.on('submit', function(e){
      e.preventDefault();
      const user = $('#agent-id').val().trim();
      const pass = $('#agent-password').val();
      if (!user || !pass) { $loginAlert.text('Missing Agent ID or Password.').removeClass('d-none'); return; }
      const client = ensureClient();
      client.setRegisterOptions({ reg_url: DEFAULT_REGISTRAR, user, pass, expire: DEFAULT_EXPIRES });
      client.isConnected() ? client.register() : client.connect();
    });

    $logoutBtn.on('click', () => { sipClient?.disconnect('User disconnected'); setHeaderStatus('Available', 'available'); showLoginForm(); });

    $callBtn.on('click', async () => {
      const dest = $phoneInput.val().trim();
      if (!dest) { $phoneInput.focus(); return; }
      const client = ensureClient();
      if (!client.isConnected()) { client.connect(); return; }
      $dtmfSeq.text(''); elapsed = 0; $callTimer.text(fmtTime(elapsed)); $callerId.text(dest); updateCallState('Trying', dest);
      try { await client.call(dest); } catch (err) { log.err(err?.message || 'Call could not be started'); resetToIdle(); }
    });

    $hangupBtn.on('click', () => { sipClient?.hangup(); resetToIdle(); });

    $holdBtn.on('click', () => {
      if (!sipClient || !sipClient.activeCall) return;
      isOnHold = !isOnHold; $holdBtn.toggleClass('active', isOnHold);
      const channelId = sipClient.activeCall.channelId;
      if (sipClient.ws && sipClient.ws.readyState === WebSocket.OPEN) sipClient.ws.send(JSON.stringify({ action: isOnHold ? 'HOLD' : 'UNHOLD', channel_id: channelId }));
      updateCallState(isOnHold ? 'On Hold' : 'Connected', $callerId.text());
    });

    $muteBtn.on('click', () => {
      isMuted = !isMuted; $muteBtn.toggleClass('active', isMuted);
      const track = sipClient?.webrtcSession?.stream?.getAudioTracks?.()[0];
      if (track) track.enabled = !isMuted;
    });

    $acceptBtn.on('click', async () => {
      if (!sipClient) return;
      $acceptBtn.prop('disabled', true).addClass('disabled').html('<i class="fas fa-circle-notch fa-spin"></i>');
      try {
        await sipClient.acceptIncoming();
        hideIncoming();
        const from = sipClient.activeCall?.from || $incomingCaller.text();
        $callerId.text(from || 'Unknown');
        updateCallState('Connected', from);
      } catch (err) {
        log.err(err?.message || 'Unable to accept call');
        $acceptBtn.prop('disabled', false).removeClass('disabled').html('<i class="fas fa-phone"></i>');
      }
    });
    $declineBtn.on('click', () => {
      if (sipClient?.pendingIncoming?.callId && sipClient.ws && sipClient.ws.readyState === WebSocket.OPEN) {
        sipClient.ws.send(JSON.stringify({ action: 'HANGUP', channel_id: sipClient.pendingIncoming.callId }));
      }
      hideIncoming(); drawer.close();
    });

    /* ---------- Dialer client listeners ---------- */
    function attachDialerListeners(client){
      if (client._listenersAttached) return;
      client.addEventListener('wsConnected',   () => { setHeaderStatus('Online', 'available'); $loginAlert.addClass('d-none'); });
      client.addEventListener('wsClosed',      () => { setHeaderStatus('Offline', 'offline'); showLoginForm(); resetToIdle(); });
      client.addEventListener('wsError',       () => { setHeaderStatus('Offline', 'offline'); showLoginForm(); $loginAlert.text('WebSocket error').removeClass('d-none'); });

      client.addEventListener('webrtcStatus',      ({ detail }) => { const note = (detail?.message || detail?.status || '').trim(); if (!note) return; if ($loginContainer.length && !$loginContainer.hasClass('d-none')) return; showWebrtcConnecting(note); });
      client.addEventListener('webrtcConnected',   () => { hideLoginOverlay(); resetWelcomeMessage(); });
      client.addEventListener('webrtcDisconnected',({ detail }) => { const note = (detail?.message || 'WebRTC disconnected').trim(); showWebrtcConnecting(note); });

      client.addEventListener('register', ({ detail }) => {
        if (detail?.registered){
          $('.agent-name').text(client.registerOptions.user || 'Agent');
          $loginAlert.addClass('d-none');
          const webrtcUp = !!(client.webrtcSession && client.webrtcSession.lastSignal === 'connected');
          if (!webrtcUp) showWebrtcConnecting(detail?.message || 'Register successful. Preparing media...'); else hideLoginOverlay();

          const agent = $('#agent-id').val().trim();
          const q = $('#history-search').val() || '';
          if (agent) loadCallHistory(agent, q);
        } else if (detail?.status === 'FAILED'){
          showLoginForm(); $loginAlert.text(detail?.error || detail?.reason || 'Register failed').removeClass('d-none');
        }
      });

      client.addEventListener('callDialSent',   ({ detail }) => updateCallState('Trying', detail?.destination || $callerId.text()));
      client.addEventListener('callProgress',   ({ detail }) => updateCallState('Ringing', detail.destination || $callerId.text()));
      client.addEventListener('callEstablished',({ detail }) => updateCallState('Connected', $callerId.text() || detail.destination || detail.from));
      client.addEventListener('callFailed',     () => { resetToIdle(); const agent = $('#agent-id').val().trim(); const q = $('#history-search').val() || ''; if (agent) setTimeout(() => loadCallHistory(agent, q), 600); });
      client.addEventListener('callHangup',     () => { resetToIdle(); hideIncoming(); const agent = $('#agent-id').val().trim(); const q = $('#history-search').val() || ''; if (agent) setTimeout(() => loadCallHistory(agent, q), 600); });
      client.addEventListener('callIncoming',   ({ detail }) => showIncoming(detail.from || 'Unknown'));
      client._listenersAttached = true;
    }

    /* ---------- History UI ---------- */
    const hx = {
      list:    $('#history-list'),
      empty:   $('#history-empty'),
      loading: $('#history-loading'),
      error:   $('#history-error'),
      refresh: $('#history-refresh'),
      nomatch: $('#history-nomatch'),
      search:  $('#history-search'),
      clear:   $('#history-clear'),
    };
    let historyAbort = null;

    const historyIconClasses = (item) => {
      const missed = String(item.qualifier || '').toLowerCase() === 'missed';
      const dirIn  = String(item.direction || '').toLowerCase() === 'incoming';
      if (missed) return { icon: 'fa-phone-slash', cls: 'missed' };
      if (dirIn)  return { icon: 'fa-reply',       cls: 'incoming' };
      return       { icon: 'fa-share',            cls: 'outgoing' };
    };

    const renderHistoryItem = (item, q='') => {
      const { icon, cls } = historyIconClasses(item);
      const title = item.who || '(unknown)';
      const when  = item.when || '';
      const right = (item.duration && item.duration.trim() !== '') ? item.duration : (item.qualifier || '');
      const statusCss  = item.statusCss || '';
      const statusText = item.status || '';
      const num = ((String(title).match(/\(([^)]+)\)/) || [])[1]) || '';
      return `
        <div class="log-item" data-call-id="${escHtml(item.id || '')}" data-who="${escHtml(title)}">
          <div class="call-icon ${cls}"><i class="fas ${icon}"></i></div>
          <div class="item-details">
            <div class="item-name">${highlight(title, q)}</div>
            <div class="item-meta">
              <span>${highlight(when, q)}</span>
              ${right ? ` • <span>${highlight(right, q)}</span>` : ''}
              ${statusText ? ` • <span class="${escHtml(statusCss)}">${highlight(statusText, q)}</span>` : ''}
            </div>
          </div>
          <button class="call-button" data-number="${escHtml(num)}" title="Call back"><i class="fas fa-phone-square-alt"></i></button>
        </div>`;
    };

    const renderHistory = (list, q='') => {
      hx.loading.addClass('d-none'); hx.error.addClass('d-none').text('');
      hx.empty.addClass('d-none');   hx.nomatch.addClass('d-none'); hx.list.empty();
      if (!Array.isArray(list) || list.length === 0){ (q && q.trim()) ? hx.nomatch.removeClass('d-none') : hx.empty.removeClass('d-none'); return; }
      hx.list.html(list.map(it => renderHistoryItem(it, q)).join(''));
    };

    async function loadCallHistory(agentId, q=''){
      if (!agentId) return;
      historyAbort?.abort?.();
      historyAbort = new AbortController();

      hx.loading.removeClass('d-none');
      hx.empty.addClass('d-none');
      hx.error.addClass('d-none').text('');
      hx.nomatch.addClass('d-none');
      hx.list.empty();

      const url = `/dialer/call/history/${encodeURIComponent(agentId)}${q ? `?q=${encodeURIComponent(q)}` : ''}`;
      try {
        const data = await fetchJson(url, { signal: historyAbort.signal });
        renderHistory(Array.isArray(data?.history) ? data.history : [], q);
      } catch (err) {
        if (err?.name === 'AbortError') return;
        hx.loading.addClass('d-none'); hx.error.removeClass('d-none').text(`Failed to load call history: ${err.message || err}`);
      } finally {
        historyAbort = null;
      }
    }

    const onHistorySearch = debounce(() => {
      const q = hx.search?.val() || '';
      const agent = $('#agent-id').val().trim();
      if (agent) loadCallHistory(agent, q);
    }, 220);

    hx.search?.on('input', onHistorySearch).on('keydown', (e) => {
      if (e.key === 'Escape'){ hx.search.val(''); const agent = $('#agent-id').val().trim(); if (agent) loadCallHistory(agent, ''); hx.search.blur(); }
    });
    hx.clear?.on('click', () => { hx.search.val(''); const agent = $('#agent-id').val().trim(); if (agent) loadCallHistory(agent, ''); hx.search.focus(); });
    hx.refresh.on('click', () => { const agent = $('#agent-id').val().trim(); const q = hx.search?.val() || ''; if (agent) loadCallHistory(agent, q); });

    $(document).on('click', '#history-list .log-item', function(e){
      if ($(e.target).closest('.call-button').length) return;
      const who = $(this).data('who') || '';
      const num = (String(who).match(/\(([^)]+)\)/) || [])[1] || '';
      if (num){ 
        // $('#phone-number-input').val(num); 
        drawer.openForNumber(String(num)); 
      }
    });
    $(document).on('click', '#history-list .call-button', function(){
      const num = $(this).data('number'); if (!num) return;
      $('#phone-number-input').val(num);
      // $("#call-btn").click();
    });

    /* ---------- Contacts UI ---------- */
    const cx = {
      list:    $('#contacts-list'),
      empty:   $('#contacts-empty'),
      loading: $('#contacts-loading'),
      error:   $('#contacts-error'),
      refresh: $('#contacts-refresh'),
      nomatch: $('#contacts-nomatch'),
      search:  $('#contacts-search'),
      clear:   $('#contacts-clear'),
    };
    let contactsAbort = null;

    const safeStr = (v) => (v === null || v === undefined) ? '' : String(v);
    const contactDisplayPhone = (p) => (safeStr(p) || '—');
    const contactInitials = (s) => {
      const t = safeStr(s).trim(); if (!t) return 'U';
      if (/^[A-Z]{1,3}$/.test(t)) return t.slice(0,2);
      const parts = t.split(/\s+/).filter(Boolean);
      const first = parts[0]?.[0] || '', last = parts.length > 1 ? parts[parts.length-1][0] : '';
      return (first + last).toUpperCase().slice(0,2) || t.slice(0,2).toUpperCase();
    };

    const renderContactItem = (item, q='') => {
      const id   = item.id ?? '';
      const name = safeStr(item.name) || 'Unknown';
      const phone= contactDisplayPhone(item.phone);
      const email= safeStr(item.email) || '—';
      const initials = contactInitials(item.avatar || name);
      const dialNumber = safeStr(item.phone || '').trim();
      return `
        <div class="contact-item" data-id="${escHtml(id)}" data-number="${escHtml(dialNumber)}" data-name="${escHtml(name)}">
          <div class="contact-icon"><div class="contact-avatar">${escHtml(initials)}</div></div>
          <div class="item-details">
            <div class="item-name">${highlight(name, q)}</div>
            <div class="item-meta"><span>${highlight(phone, q)}</span>${email && email !== '—' ? ` • <span>${highlight(email, q)}</span>` : ''}</div>
          </div>
          ${dialNumber ? `<button class="call-button" data-number="${escHtml(dialNumber)}" title="Call"><i class="fas fa-phone-square-alt"></i></button>` : ``}
        </div>`;
    };

    const renderContacts = (list, q='') => {
      cx.loading.addClass('d-none'); cx.error.addClass('d-none').text('');
      cx.empty.addClass('d-none');   cx.nomatch.addClass('d-none'); cx.list.empty();
      if (!Array.isArray(list) || list.length === 0){ (q && q.trim()) ? cx.nomatch.removeClass('d-none') : cx.empty.removeClass('d-none'); return; }
      cx.list.html(list.map(c => renderContactItem(c, q)).join(''));
    };

    async function loadContacts(q=''){
      contactsAbort?.abort?.();
      contactsAbort = new AbortController();

      cx.loading.removeClass('d-none'); cx.empty.addClass('d-none'); cx.error.addClass('d-none').text(''); cx.nomatch.addClass('d-none'); cx.list.empty();

      const url = `/dialer/contacts${q ? `?q=${encodeURIComponent(q)}` : ''}`;
      try {
        const data = await fetchJson(url, { signal: contactsAbort.signal });
        renderContacts(Array.isArray(data?.results) ? data.results : [], q);
      } catch (err) {
        if (err?.name === 'AbortError') return;
        cx.loading.addClass('d-none'); cx.error.removeClass('d-none').text(`Failed to load contacts: ${err.message || err}`);
      } finally {
        contactsAbort = null;
      }
    }

    const onContactsSearch = debounce(() => { loadContacts(cx.search?.val() || ''); }, 220);
    cx.search?.on('input', onContactsSearch).on('keydown', (e) => {
      if (e.key === 'Escape'){ cx.search.val(''); loadContacts(''); cx.search.blur(); }
    });
    cx.clear?.on('click', () => { cx.search.val(''); loadContacts(''); cx.search.focus(); });
    cx.refresh.on('click', () => { loadContacts(cx.search?.val() || ''); });

    $(document).on('click', '#contacts-list .contact-item', function(e){
      if ($(e.target).closest('.call-button').length) return;
      const number = $(this).data('number');
      if (number) drawer.openForNumber(String(number));
    });

    $('a[data-toggle="tab"][href="#contacts"]').on('shown.bs.tab', () => loadContacts(cx.search?.val() || ''));
    if ($('#contacts').hasClass('show') || $('#contacts-tab').hasClass('active')) loadContacts(cx.search?.val() || '');

    /* ---- Initial UI ---- */
    showLoginForm();
  });
})();
</script>

</body>
</html>
