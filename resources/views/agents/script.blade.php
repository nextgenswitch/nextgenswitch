<script>
    document.addEventListener('DOMContentLoaded', () => {
        let ws_opened = false;
        let socket = null;

        // --- 1. Dial Pad and Backspace Logic ---
        const dialInput = document.getElementById('dial-input');
        const dialPadButtons = document.querySelectorAll('.dial-pad-btn');
        const backspaceBtn = document.getElementById('backspace-btn');

        if (dialInput && dialPadButtons.length > 0) {
            dialPadButtons.forEach(button => {
                button.addEventListener('click', () => {
                    dialInput.value += button.textContent.trim();
                });
            });
        }

        if (backspaceBtn && dialInput) {
            backspaceBtn.addEventListener('click', () => {
                dialInput.value = dialInput.value.slice(0, -1);
            });
        }

        // --- 2. Queue Toggles Logic ---
        const queueToggles = document.querySelectorAll('input[type="checkbox"][data-status-target]');

        queueToggles.forEach(toggle => {
            // Initialize text on page load
            const statusElement = document.getElementById(toggle.dataset.statusTarget);
            if (statusElement) {
                statusElement.textContent = toggle.checked ? 'Joined' : 'Not Joined';
            }

            // Add listener for changes
            toggle.addEventListener('change', (event) => {
                const targetElement = document.getElementById(event.target.dataset
                    .statusTarget);
                if (targetElement) {
                    targetElement.textContent = event.target.checked ? 'Joined' : 'Not Joined';
                }
            });
        });

        // --- 3. Transfer Confirmation Modal Logic ---
        const transferBtn = document.getElementById('transfer-button');
        const transferModal = document.getElementById('transfer-modal');
        const modalContent = document.getElementById('modal-content');
        const cancelTransferBtn = document.getElementById('cancel-transfer-btn');
        const confirmTransferBtn = document.getElementById('confirm-transfer-btn');
        const transferNumberDisplay = document.getElementById('transfer-number-display');

        const openModal = () => {
            const numberToTransfer = dialInput.value;
            if (numberToTransfer.trim() !== '') {
                transferNumberDisplay.textContent = numberToTransfer;
                transferModal.classList.remove('hidden');
                // Trigger animations
                setTimeout(() => {
                    transferModal.style.opacity = '1';
                    modalContent.style.opacity = '1';
                    modalContent.style.transform = 'scale(1)';
                }, 10);
            } else {
                // Visually indicate that a number is required
                dialInput.focus();
                dialInput.classList.add('ring-2', 'ring-red-500');
                setTimeout(() => dialInput.classList.remove('ring-2', 'ring-red-500'), 1500);
            }
        };

        const closeModal = () => {
            // Trigger animations
            transferModal.style.opacity = '0';
            modalContent.style.opacity = '0';
            modalContent.style.transform = 'scale(0.95)';
            setTimeout(() => {
                transferModal.classList.add('hidden');
            }, 300); // Wait for animation to finish
        };

        if (transferBtn && transferModal && dialInput && transferNumberDisplay) {
            transferBtn.addEventListener('click', openModal);
        }

        if (cancelTransferBtn) {
            cancelTransferBtn.addEventListener('click', closeModal);
        }

        if (confirmTransferBtn) {
            confirmTransferBtn.addEventListener('click', () => {
                console.log(`Confirmed: Transferring call to ${transferNumberDisplay.textContent}`);
                // You would add real transfer logic here
                closeModal();
                dialInput.value = ''; // Clear input after transfer
            });
        }

        // Also hide modal if clicking on the background overlay
        if (transferModal) {
            transferModal.addEventListener('click', (event) => {
                if (event.target === transferModal) {
                    closeModal();
                }
            });
        }

        // Close modal with Escape key
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !transferModal.classList.contains('hidden')) {
                closeModal();
            }
        });

        let call_info = @json($call_info);
        console.log("Initial Call Info:", call_info);

        let agent_name = document.getElementById('agent-name');
        let agent_phone = document.getElementById('agent-phone');
        let agent_call_duration = document.getElementById('agent-call-duration');
        let timerInterval = null;

        let customer_name = document.getElementById('customer-name');
        let customer_phone = document.getElementById('customer-phone');
        let customer_email = document.getElementById('customer-email');
        let customer_company = document.getElementById('customer-company');
        let customer_open_tickets = document.getElementById('customer-open-tickets');
        let customer_avatar = document.getElementById('customer-avatar');
        let no_recent_interactions = document.getElementById('no-recent-interactions');
        let recent_interactions = document.getElementById('recent-interactions');

        function timer(initialSeconds) {
            let seconds = initialSeconds;
            timerInterval = setInterval(() => {
                seconds++;
                const mins = String(Math.floor(seconds / 60)).padStart(2, '0');
                const secs = String(seconds % 60).padStart(2, '0');
                agent_call_duration.textContent = `${mins}:${secs}`;
            }, 1000);
        }

        function resetUI() {
            agent_name.textContent = 'No Call Available';
            agent_phone.textContent = '-';
            agent_call_duration.textContent = '00:00';
            if (timerInterval) {
                clearInterval(timerInterval);
            }

            customer_avatar.textContent = '-';
            customer_name.textContent = '-';
            customer_phone.textContent = '-';
            customer_email.textContent = '-';
            customer_company.textContent = '-';
            customer_open_tickets.textContent = '0';
            recent_interactions.innerHTML = '';
            no_recent_interactions.classList.remove('hidden');
            
        }

        function generateTimeline(timeline) {
            return timeline.map(event => {
                let svg;

                if (event.type === 'call') {
                    if (event.qualifier === 'Answered' || event.status === 'Established') {
                        if (event.direction === 'Incoming') {
                            svg = `
                            <svg class="text-[#10B981] flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                                width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M12 5v14" />
                                <path d="m19 12-7 7-7-7" />
                            </svg>`;
                        } else if (event.direction === 'Outgoing') {
                            svg = `
                            <svg class="text-[#6366F1] flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                                width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M12 19V5" />
                                <path d="m5 12 7-7 7 7" />
                            </svg>`;
                        }
                    } else if (event.status === 'Disconnected' && event.qualifier !== 'Answered') {
                        svg = `
                        <svg class="text-[#EF4444] flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                            width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M12 5v14" />
                            <path d="m19 12-7 7-7-7" />
                            <path d="m15.54 15.54-4.08-4.08" />
                        </svg>`;
                    }
                }

                return `
                <li class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full flex-shrink-0 flex items-center justify-center">
                        ${svg}
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">${event.note}</p>
                        <p class="caption-text mt-0.5">${event.when}</p>
                    </div>
                </li>`;
            }).join('');
        }

        function populateCallInfo(data) {
            agent_name.textContent = data.caller_name || 'Unknown';
            agent_phone.textContent = data.caller_id || '';

            if (timerInterval) {
                clearInterval(timerInterval);
            }
            timer(data.duration || 0);

            // Update customer info
            customer_avatar.textContent = data.customer.avatar;
            customer_name.textContent = data.customer.name || 'Unknown';
            customer_phone.textContent = data.customer.phone || '-';
            customer_email.textContent = data.customer.email || '-';
            customer_company.textContent = data.customer.company || '-';
            customer_open_tickets.textContent = data.customer.open_tickets || '0';

            
            const timelineHTML = generateTimeline(data.customer.timeline);

            recent_interactions.innerHTML = timelineHTML;
            no_recent_interactions.classList.add('hidden');
        }


        function connectWebsocket() {
            if (ws_opened) {
                console.log('websock allready connected');
                return;
            }
            var client_id = "{{ $agent_id }}";
            console.log("websocket connecting");
            var url = '{{ url('/websocket') }}/?;client_id=' + client_id;
            url = url.replace("http", 'ws')
            url = url.replace("https", 'wss')
            console.log(url);
            const ws = new WebSocket(url)
            ws.onopen = () => {
                console.log('ws opened on browser')
                ws_opened = true
                socket = ws

            }

            ws.onmessage = (message) => {
                const data = JSON.parse(message.data);
                console.log(`message received`, data);
                if (data.event == 'call_bridged') {
                    populateCallInfo(data.data);

                } else if (data.event == 'call_terminated') {
                    resetUI();
                }
            }

            ws.onclose = function(e) {
                console.log('websock connection closed');
                ws_opened = false;
                connectWebsocket();
            };
        }

        connectWebsocket();

        if(call_info && Object.keys(call_info).length > 0){
            // Populate initial call info if available
            populateCallInfo(call_info);
        } else {
            resetUI();
        }



    });
</script>
