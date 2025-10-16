<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dialer</title>

    <!-- Design deps (unchanged) -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet" />

    <!-- Functionality deps -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://webrtc.github.io/adapter/adapter-latest.js"></script>

    <link rel="stylesheet" href="{{ asset('css/web_dialer.css?v=2') }}" />
    
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
        <source src="{{ asset('sounds/incoming_call.mp3') }}" type="audio/mpeg">
    </audio>

    <!-- Bootstrap JS (unchanged) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    @include('dialer.web.script')

</body>
</html>
