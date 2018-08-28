var start = moment().subtract(20, 'days');
var end = moment();
var charts =  new Array();
var url0= "http://10.0.0.229/bigchip/readChart.php?startDate="+ start.toString()+"&endDate="+end.toString();
var url1= "http://10.0.0.229/bigchip/readChartAccount.php?startDate="+ start.toString()+"&endDate="+end.toString();
var myChart;

function chartUpdate(url, chart, start, end){
    if(url == url0){
        charts[0] = c3.generate({
            data: {
                x:'GLAccountName',
                url: url0,
                mimeType: 'json',
                keys: {
                    value:['GLAccountName','AverageValue','HighValue', 'TotalValue']
                },
                names: {
                    AverageValue: 'Average',
                    HighValue: 'Highest',
                    TotalValue:'Total'
                },
                type: 'bar'
            },
            color: {
                pattern: ['#1f77b4', '#1777e0', '#1927e9']
            },
            axis: {
                x: {
                    type: 'category' // this needed to load string x value
                }
            },
            bar: {
                width: {
                    ratio: 0.7 // this makes bar width 50% of length between ticks
                }
                // or
                //width: 100 // this makes bar width 100px
            },
            bindto: '#chart'        
        });
    }else if(url == url1){
        charts[1] = c3.generate({
            data: {
                x:'GLAccountName',
                url: url1,
                mimeType: 'json',
                keys: {
                    value:['GLAccountName','TotalValue'],
                },
                names: {
                    GLAccountName: 'Account',
                    TotalValue:'Total'
                },
                type: 'pie'
            },
            bindto: '#chart1'        
        });

    }
    
    
}

$(document).ready(function() {
    chartUpdate(url0, charts[0],start, end);
    chartUpdate(url1, charts[1],start, end);
    function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        charts[0].destroy();
        charts[1].destroy();
        chartUpdate(url0, charts[0],start, end);
        chartUpdate(url1, charts[1],start, end);
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);
    cb(start, end);    

} );        













/*
var chart = c3.generate({
    data: {
        xs:{
            'TotalEntries':'Gl_Account'
        },
        url: 'http://localhost/graphs/read.php',
        mimeType: 'json',
        keys: {
            value:[ 'TotalEntries', 'Gl_Account']
        },
        type: 'bar'
    },
    axis: {
        x: {
            type: 'category' // this needed to load string x value
        }
    },
    bar: {
        width: {
            ratio: 0.7 // this makes bar width 50% of length between ticks
        }
        // or
        //width: 100 // this makes bar width 100px
    }

});



*/





/*


chart = c3.generate({
        data: {
            x:'GLAccountName',
            url: 'http://localhost/graphs/readPage2.php?startDate='+ start.toString()+'&endDate='+end.toString(),
            mimeType: 'json',
            keys: {
                value:['GLAccountName','AverageValue','HighValue', 'TotalValue']
            },
            names: {
                AverageValue: 'Average',
                HighValue: 'Highest',
                TotalValue:'Total'
            },
            type: 'bar'
        },
        color: {
            pattern: ['#1f77b4', '#1777e0', '#1927e9']
        },
        axis: {
            x: {
                type: 'category' // this needed to load string x value
            }
        },
        bar: {
            width: {
                ratio: 0.7 // this makes bar width 50% of length between ticks
            }
            // or
            //width: 100 // this makes bar width 100px
        }
    
    });



*/