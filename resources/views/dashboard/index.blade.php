@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'dashboard'
])



@push('scripts')
    <script type="text/javascript">

        $(document).ready(function () {
             //Chart vistorias aprovadas e não aprovadas
            $.getJSON('/dashboardCharts', function (data) {
                var jsonChartAprovadasMes = [];
                var jsonChartAprovadasCount = [];
                var jsonChartCountNaoEnviadas = [];
                $.each(data.aprovadas, function (index, value) {
                    jsonChartAprovadasMes.push(value.mes);
                    jsonChartAprovadasCount.push(value.count);
                });
                $.each(data.naoenviadas, function (index, value) {
                    jsonChartCountNaoEnviadas.push(value.count);
                });
                chartsJsSendNotApproved(jsonChartAprovadasMes, jsonChartAprovadasCount, jsonChartCountNaoEnviadas);
            });

            //Chart aprovadas multiplas
            $.getJSON('/dashboardChartsMult', function (data) {
                var jsonChartMultSend = 0;
                var jsonChartMultCreate = 0;
                var jsonChartMultPendSend = 0;
                var jsonChartMultApproved = 0;
                var status = [];
                $.each(data, function (index, value) {
                    status.push(index);
                    if (index == 'Enviado')
                        $.each(value, function (index, valueMult) {
                            jsonChartMultSend += valueMult.count;
                        });
                    if (index == 'cadastro')
                        $.each(value, function (index, valueMult) {
                            jsonChartMultCreate += valueMult.count;
                        });
                    if (index == 'em aprovacao')
                        $.each(value, function (index, valueMult) {
                            jsonChartMultPendSend += valueMult.count;
                        });
                    if (index == 'Aprovado')
                        $.each(value, function (index, valueMult) {
                            jsonChartMultApproved += valueMult.count;
                        });
                });
                chartsJsSendMult(status, jsonChartMultSend, jsonChartMultCreate, jsonChartMultPendSend, jsonChartMultApproved)
            });

            //Chart aprovadas multiplas
            $.getJSON('/dashboardChartsMultType', function (dataType) {
                var jsonChartAprovadasMes = [];
                var jsonChartAprovadasCount = [];
                var jsonChartAprovadasType = [];

                var jsonChartCountNaoEnviadas = [];
                var jsonChartTypeNaoEnviadas = [];

                $.each(dataType.aprovadas, function (index, value) {
                    jsonChartAprovadasMes.push(value.mes);
                    jsonChartAprovadasType.push(value.type);
                    jsonChartAprovadasCount.push(value.count);
                });
                $.each(dataType.naoenviadas, function (index, value) {


                    jsonChartCountNaoEnviadas.push(value.count);
                    jsonChartTypeNaoEnviadas.push(value.type);
                });


                chartJsMultType(jsonChartAprovadasMes, jsonChartAprovadasCount,jsonChartAprovadasType, jsonChartCountNaoEnviadas,jsonChartTypeNaoEnviadas);
            });

            //Sintentic tabel
            $('.total-vistorias-types').DataTable({
                processing: false,
                serverSide: true,
                ajax: {
                    url: "/dashboardSinteticTableVistorias",
                    type: "GET"
                },
                language: {
                    "lengthMenu": "registros _MENU_ por página",
                    "zeroRecords": "Nenhum registro encontrado",
                    "info": "Página _PAGE_ de _PAGES_",
                    "infoEmpty": "No records available",
                    "infoFiltered": "(Total de  _MAX_ total records)",
                    "search": "Pesquisar:",
                    "paginate": {
                        "first": "Primeiro",
                        "last": "Last",
                        "next": "Próximo",
                        "previous": "Anterior"
                    },
                },
                columns: [
                    {
                        data: 'tipo',
                        name: 'tipo'
                    },
                    {
                        data: 'total',
                        name: 'total'
                    },
                    {
                        data: 'nao_enviadas',
                        name: 'nao_enviadas'
                    },
                    {
                        data: 'nao_retornadas',
                        name: 'nao_retornadas'
                    }
                ]
            });

        });

        function chartsJsSendNotApproved(jsonChartAprovadasMes, jsonChartAprovadasCount, jsonChartCountNaoEnviadas) {

            chartColor = "#FFFFFF";

            ctx = document.getElementById('chartHours').getContext("2d");
            values = document.getElementById('jsonCharts');
            myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: jsonChartAprovadasMes,
                    datasets: [{
                        borderColor: "#f17e5d",
                        label: 'Não Enviadas',
                        backgroundColor: "#f17e5d",
                        pointRadius: 0,
                        pointHoverRadius: 0,
                        borderWidth: 3,
                        data: jsonChartCountNaoEnviadas
                    },
                        {
                            borderColor: "#6bd098",

                            label: 'Enviadas',
                            backgroundColor: "#6bd098",
                            pointRadius: 0,
                            pointHoverRadius: 0,
                            borderWidth: 3,
                            data: jsonChartAprovadasCount
                        }
                    ]
                },
                options: {
                    legend: {
                        display: true
                    },

                    tooltips: {
                        enabled: true
                    },

                    scales: {
                        yAxes: [{

                            ticks: {
                                fontColor: "#9f9f9f",
                                beginAtZero: false,
                                maxTicksLimit: 5,
                                //padding: 20
                            },
                            gridLines: {
                                drawBorder: false,
                                zeroLineColor: "#ccc",
                                color: 'rgba(255,255,255,0.05)'
                            }

                        }],

                        xAxes: [{
                            barPercentage: 1.6,
                            gridLines: {
                                drawBorder: false,
                                color: 'rgba(255,255,255,0.1)',
                                zeroLineColor: "transparent",
                                display: false,
                            },
                            ticks: {
                                padding: 20,
                                fontColor: "#9f9f9f"
                            }
                        }]
                    },
                }
            });
        }

        function chartsJsSendMult(status, jsonChartMultSend, jsonChartMultCreate, jsonChartMultPendSend, jsonChartMultApproved) {
            ctx = document.getElementById('chartEmail').getContext("2d");

            myChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: status,
                    datasets: [{
                        label: "Vistorias Multiplas",
                        pointRadius: 0,
                        pointHoverRadius: 0,
                        backgroundColor: [
                            '#e3e3e3',
                            '#4acccd',
                            '#fcc468',
                            '#ef8157'
                        ],
                        borderWidth: 0,
                        data: [jsonChartMultSend, jsonChartMultCreate, jsonChartMultApproved, jsonChartMultPendSend]
                    }]
                },

                options: {

                    legend: {
                        display: true
                    },

                    pieceLabel: {
                        render: 'percentage',
                        fontColor: ['white'],
                        precision: 2
                    },

                    tooltips: {
                        enabled: true
                    },

                    scales: {
                        yAxes: [{

                            ticks: {
                                display: false
                            },
                            gridLines: {
                                drawBorder: false,
                                zeroLineColor: "transparent",
                                color: 'rgba(255,255,255,0.05)'
                            }

                        }],

                        xAxes: [{
                            barPercentage: 1.6,
                            gridLines: {
                                drawBorder: false,
                                color: 'rgba(255,255,255,0.1)',
                                zeroLineColor: "transparent"
                            },
                            ticks: {
                                display: false,
                            }
                        }]
                    },
                }
            });
        }

        function chartJsMultType(jsonChartAprovadasMes, jsonChartAprovadasCount,jsonChartAprovadasType, jsonChartCountNaoEnviadas,jsonChartTypeNaoEnviadas){
            var speedCanvas = document.getElementById("speedChart");
            var dataFirst = {
                data: jsonChartAprovadasCount,
                fill: false,
                label:'Enviadas',
                borderColor: '#fbc658',
                backgroundColor: 'transparent',
                pointBorderColor: '#fbc658',
                pointRadius: 4,
                pointHoverRadius: 4,
                pointBorderWidth: 8,
            };

            var dataSecond = {
                data:jsonChartCountNaoEnviadas,
                fill: false,
                label:'Não Enviadas',
                borderColor: '#51CACF',
                backgroundColor: 'transparent',
                pointBorderColor: '#51CACF',
                pointRadius: 4,
                pointHoverRadius: 4,
                pointBorderWidth: 8
            };

            var speedData = {
                labels: jsonChartAprovadasMes,
                datasets: [dataFirst, dataSecond]
            };

            var chartOptions = {
                legend: {
                    display: true,
                    position: 'top'
                }
            };

            var lineChart = new Chart(speedCanvas, {
                type: 'line',
                hover: false,
                data: speedData,
                options: chartOptions
            });
        }

    </script>
@endpush
