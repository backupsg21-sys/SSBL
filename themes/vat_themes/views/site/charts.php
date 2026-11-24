<script src="https://cdn.jsdelivr.net/npm/highcharts@10.3.3/highcharts.js"></script>
<script src="https://cdn.jsdelivr.net/npm/highcharts@10.3.3/modules/exporting.js"></script>

<div style="width: 100%">
    <div style="width: 50%; float: left; clear: right">
        <div id="container-vat-payable-vs-vat-paid"
             style="min-width: 400px; height: 400px; margin: 0 auto"></div>
    </div>
    <div style="width: 50%; float: right">
        <div id="container-vat-amount"
             style="min-width: 400px; height: 400px; margin: 0 auto"></div>
    </div>
    <div style="width: 50%; float: left">
        <div id="container-purchase-total"
             style="min-width: 400px; height: 400px; margin: 0 auto"></div>
    </div>
    <div style="width: 50%; float: right">
        <div id="container-sales-total"
             style="min-width: 400px; height: 400px; margin: 0 auto"></div>
    </div>
</div>


<script>
    $(document).ready(function () {
        if (typeof Highcharts === 'undefined') {
            return;
        }
        /*  column chart*/
        var vatPayable = <?php echo $vatPayable; ?>;
        var month = Object.keys(vatPayable);
        var totalVatPayable = Object.values(vatPayable);

        var vatPaid = <?php echo $vatPaidAmount; ?>;
        var month = Object.keys(vatPaid);
        var totalVatPaid = Object.values(vatPaid);

        var purchase = <?php echo $totalPurchaseAmount; ?>;
        var month = Object.keys(purchase);
        var totalPurchase = Object.values(purchase);

        var sales = <?php echo $totalSalesAmount; ?>;
        var month = Object.keys(sales);
        var totalSales = Object.values(sales);

        Highcharts.chart('container-vat-payable-vs-vat-paid', {
            chart: {
                type: 'column'
            },
            title: {
                text: "VAT payable vs VAT Paid"
            },
            // subtitle: {
            //     text: 'Source: WorldClimate.com'
            // },
            xAxis: {
                categories: month,
                crosshair: true
            },
            credits: {
                enabled: false
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Amount'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.f} TK</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            colors: [
                '#FF5733',
                '#C70039 ',
            ],
            plotOptions: {
                column: {
                    // colorByPoint: true,
                    pointPadding: 0.2,
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true
                    },
                }
            },
            series: [{
                name: 'VAT Payable',
                data: totalVatPayable
            }, {
                name: 'VAT Paid',
                data: totalVatPaid
            },]
        });
        /* end column chart*/


        //line chart
        Highcharts.chart('container-vat-amount', {
            chart: {
                type: 'areaspline'
            },
            title: {text: 'Total VAT Month Wise'},
            xAxis: {
                categories: month,
            },
            yAxis: {
                title: {text: 'Amount'}
            },
            legend: {
            },
            credits: {
                enabled: false
            },
            plotOptions: {
                series: {
                    label: {connectorAllowed: false},
                },
                line: {
                    dataLabels: {
                        enabled: true
                    },
                    enableMouseTracking: false
                },

            },
            series: [{
                color: {
                    linearGradient: {
                        x1: 0,
                        x2: 0,
                        y1: 0,
                        y2: 1
                    },
                    stops: [
                        [0, '#ffcd38'],
                        [1, '#fffa7e']
                    ]
                },
                name: 'VAT Amount',
                colorByPoint: false,
                data: totalVatPayable,
            },],

            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 500
                    },
                    chartOptions: {
                        legend: {
                            layout: 'horizontal',
                            align: 'center',
                            verticalAlign: 'bottom'
                        }
                    }
                }]
            }

        });
        /* line chart end */

        /* Line charts start */
        Highcharts.chart('container-purchase-total', {
            chart: {
                type: 'line'
            },
            title: {
                text: 'Monthly Purchase'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: month,
            },
            yAxis: {
                title: {
                    text: 'Amount'
                }
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true
                    },
                    enableMouseTracking: false
                }
            },
            series: [{
                name: 'Purchase',
                data: totalPurchase,
            }]
        });
        /* Line charts end */

        /* Line charts start */
        Highcharts.chart('container-sales-total', {
            chart: {
                type: 'line'
            },
            title: {
                text: 'Monthly Sales'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: month,
            },
            yAxis: {
                title: {
                    text: 'Amount'
                }
            },
            colors: [
                '#FF5733',
            ],
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true
                    },
                    enableMouseTracking: false
                }
            },
            series: [{
                name: 'Sales',
                data: totalSales,
            }]
        });
        /* Line charts end */
    });
</script>