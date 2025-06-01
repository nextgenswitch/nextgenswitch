@push('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>

    <script>
        $(document).ready(function(){
            let pieChartCallStatsInstance;

            $.get("{{ route('dashboard.extension.stats') }}", function(response){
                // console.log(response);
                extensionStats(response.online, response.offline);
            });
            
            
            callStats();
            trunkStats();
            queueStats();
            bridgeStats();

            $('.dropdown-item[data-filter]').on('click', function (e) {
                e.preventDefault();
                // console.log('filter button clicked');
                const filter = $(this).data('filter');
                const selectedText = $(this).text();
                const btnGroup = $(this).closest('.btn-group'); 
                const button = btnGroup.find('.btn.dropdown-toggle');
                button.text(selectedText); 

                const stats = button.data('stats');
                console.log(stats);

                if(stats == 'call-stats'){
                    callStats(filter);
                }
                
                if(stats == 'trunk-stats'){
                    trunkStats(filter);
                }
                
                if(stats == 'queue-stats'){
                    queueStats(filter);
                }
                
            });
            
            
            function callStats(filter = null){
                // Calculate date range based on filter
                const { startDate, endDate } = getDateRange(filter);

                let url = "{{ route('dashboard.call.stats') }}?start_date=" + startDate + "&end_date=" + endDate;
                // console.log(url);
                
                $.get(url, function(response){
                    // console.log(response);
                    // generateCallStatsPie(response.successed, response.failed)

                    generateChart(
                        'pieChartCallStats',
                        'pie',    
                        ['Succeeded', 'Failed'],
                        [response.successed, response.failed],    
                        ['#00C49F', '#1c1c489e']
                    );

                });

            }    

            function trunkStats(filter = null){
                // Calculate date range based on filter
                const { startDate, endDate } = getDateRange(filter);

                let url = "{{ route('dashboard.trunk.stats') }}?start_date=" + startDate + "&end_date=" + endDate;
                // console.log(url);
                
                $.get(url, function(response){
                    // console.log(response);
                
                    generateChart(
                        'doughnutChartTrunkStats',
                        'doughnut',    
                        ['Succeeded', 'Failed'],
                        [response.successed, response.failed],    
                        ['#00C49F', '#1c1c489e']
                    );
                });

            } 

            function queueStats(filter = null){
                // Calculate date range based on filter
                const { startDate, endDate } = getDateRange(filter);

                let url = "{{ route('dashboard.queue.stats') }}?start_date=" + startDate + "&end_date=" + endDate;
                // console.log(url);
                
                $.get(url, function(response){
                    // console.log(response);
                
                    generateChart(
                        'pieChartQueueStats',
                        'pie',    
                        ['Answer', 'Abandoned', 'Timeout'],
                        [response.answer, response.abandoned, response.timeout],    
                        ['#00C49F', '#1c1c489e', '#FFBB28']
                    );
                });

            }

            function bridgeStats(){
                let url = "{{ route('dashboard.bridge.call.stats') }}";
                // console.log(url);
                
                $.get(url, function(response){
                    console.log(response);
                    
                    if ($.isEmptyObject(response) || !response) {
                        console.log("The response is empty.");
                    } else {
                        bridgeCallStatistics(response.date, response.successed, response.failed);
                    }
                
                });
            }

            
            /*
            function generateCallStatsPie(successed = 0, failed = 0){
                if (pieChartCallStatsInstance) {
                    pieChartCallStatsInstance.destroy();
                }

                const pieChartCallStats = document.getElementById('pieChartCallStats').getContext('2d');

                pieChartCallStatsInstance  = new Chart(pieChartCallStats, {
                    type: 'pie',
                    data: {
                        labels: ['Succeeded', 'Failed'],
                        datasets: [{
                            data: [successed, failed],
                            backgroundColor: [
                                '#1c1c489e',   // Primary color
                                '#00C49F',   // Neutral gray
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        aspectRatio: 1.4,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    color: '#333333',
                                    font: {
                                        size: 14,
                                        weight: 'bold'
                                    }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.raw;
                                        return `${label}: ${value}`;
                                    }
                                }
                            }
                        }
                    }
                });
            }
            */


            function generateChart(chartId, chartType, labels, data, colors, options = {}) {
                if (!window.chartInstances) {
                    window.chartInstances = {};
                }
                if (window.chartInstances[chartId] && typeof window.chartInstances[chartId].destroy === 'function') {
                    window.chartInstances[chartId].destroy();
                }
                const chartContext = document.getElementById(chartId).getContext('2d');
                window.chartInstances[chartId] = new Chart(chartContext, {
                    type: chartType,
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: colors,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        aspectRatio: 1.4,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    color: '#333333',
                                    font: {
                                        size: 14,
                                        weight: 'bold'
                                    }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.raw;
                                        return `${label}: ${value}`;
                                    }
                                }
                            }
                        },
                        
                    }
                });
            }



            function bridgeCallStatistics(labels, successed, failed){
                const lineChartCtx = document.getElementById('lineChart').getContext('2d');

                // Create gradient for Effective Calls
                const effectiveCallsGradient = lineChartCtx.createLinearGradient(0, 0, 0, 400);
                effectiveCallsGradient.addColorStop(0, 'rgba(39, 39, 100, 0.8)');
                effectiveCallsGradient.addColorStop(1, 'rgba(39, 39, 100, 0)');

                // Create gradient for Total Calls
                const totalCallsGradient = lineChartCtx.createLinearGradient(0, 0, 0, 400);
                totalCallsGradient.addColorStop(0, 'rgba(108, 117, 125, 0.8)');
                totalCallsGradient.addColorStop(1, 'rgba(108, 117, 125, 0)');

                new Chart(lineChartCtx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Total Successed Calls',
                                data: successed,
                                borderColor: '#272764',
                                backgroundColor: effectiveCallsGradient,
                                fill: true,
                                tension: 0.4,
                                pointBackgroundColor: '#272764',
                                pointBorderColor: '#fff',
                                pointHoverBackgroundColor: '#fff',
                                pointHoverBorderColor: '#272764',
                            },
                            {
                                label: 'Total Failed Calls',
                                data: failed,
                                borderColor: '#6c757d',
                                backgroundColor: totalCallsGradient,
                                fill: true,
                                tension: 0.4,
                                pointBackgroundColor: '#6c757d',
                                pointBorderColor: '#fff',
                                pointHoverBackgroundColor: '#fff',
                                pointHoverBorderColor: '#6c757d',
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    color: '#333333',
                                    font: {
                                        size: 14,
                                        weight: 'bold'
                                    }
                                }
                            },
                            
                            tooltip: {
                                backgroundColor: '#fff',
                                titleColor: '#272764',
                                bodyColor: '#272764',
                                borderColor: '#ddd',
                                borderWidth: 1,
                            },
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false,
                                },
                                ticks: {
                                    color: '#6c757d',
                                },
                            },
                            y: {
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.1)',
                                },
                                ticks: {
                                    color: '#6c757d',
                                },
                            },
                        },
                    },
                });

            }


            
            function extensionStats(online = 0, offline = 0){
                const doughnutChartExtensionStats = document.getElementById('doughnutChartExtensionStats').getContext('2d');

                new Chart(doughnutChartExtensionStats, {
                    type: 'doughnut',
                    data: {
                        labels: ['Online', 'Offline'],
                        datasets: [{
                            data: [online, offline], // Sample percentages or values
                            backgroundColor: [
                                '#00C49F',
                                '#1c1c489e'
                            ],
                            borderWidth: 0,
                            cutout: '60%',
                            circumference: 180,
                            rotation: 270
                        }]
                    },
                    options: {
                        aspectRatio: 1.4,
                        responsive: true,
                        cutout: '70%', // Makes the doughnut visually thinner
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    color: '#333333',
                                    font: {
                                        size: 12,
                                        weight: 'bold'
                                    }
                                }
                            },
                            tooltip: {
                                enabled: true
                            }
                        }
                    }
                });
            }


            function getDateRange(filter) {
                const today = moment();
                let startDate, endDate;

                switch (filter) {
                    case 'yesterday':
                        startDate = today.clone().subtract(1, 'days').startOf('day').format('YYYY-MM-DD');
                        endDate = today.clone().subtract(1, 'days').endOf('day').format('YYYY-MM-DD');
                        break;
                    case 'last_week':
                        startDate = today.clone().subtract(6, 'days').startOf('day').format('YYYY-MM-DD'); // Last 7 days including today
                        endDate = today.clone().endOf('day').format('YYYY-MM-DD');
                        break;
                    default:
                        startDate = today.clone().startOf('day').format('YYYY-MM-DD');
                        endDate = today.clone().endOf('day').format('YYYY-MM-DD');
                }

                return { startDate, endDate };
            }
            
        })
    </script>
@endpush