var start = moment().subtract(20, 'days');
var end = moment();
var tables = new Array();
var url0  = "http://10.0.0.229/bigchip/readTable.php";
var url1 = "http://10.0.0.229/bigchip/readTableAccountTotal.php";
var url2 = "http://10.0.0.229/bigchip/readTableAccount.php";

function dataTableUpdate(url, table, start,end){
    if(url==url0){
        tables[0] = $('#table0').DataTable({
            "processing" : true,
            "ajax" : { 
                "url" :url0,
                data : {"startDate": start.toString(), "endDate": end.toString()},
                dataSrc:""
            },
            "columns" : [
                {"data" : "LocationName"},
                {"data" : "GLCategoryName"},
                {"data" : "GLAccountName"},
                {"data" : "AverageValue"},
                {"data" : "HighValue"},
                {"data" : "TotalValue"}
            ]
        });
    }else if(url==url1){
        tables[1] = $('#table1').DataTable({
            "processing" : true,
            "ajax" : { 
                "url" :url1,
                data : {"startDate": start.toString(), "endDate": end.toString()},
                dataSrc:""
            },
            "columns" : [
                {"data" : "GLAccountName"},
                {"data" : "TotalValue"}
            ]
        });
    }else{
        tables[2] = $('#table2').DataTable({
            "processing" : true,
            "ajax" : { 
                "url" :url2,
                data : {"startDate": start.toString(), "endDate": end.toString()},
                dataSrc:""
            },
            "columns" : [
                {"data" : "GLAccountName"},
            ]
        });
    }
}


$(document).ready(function() {

    dataTableUpdate(url0, tables[0], start,end);   
    dataTableUpdate(url1, tables[1], start,end);
    dataTableUpdate(url2, tables[2], start,end);   

    function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        tables[0].destroy();
        tables[1].destroy();
        dataTableUpdate(url0, tables[0], start,end);   
        dataTableUpdate(url1, tables[1], start,end);   
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

    $('a.toggle-vis').on( 'click', function (e) {
        e.preventDefault();
        var column = tables[0].column( $(this).attr('data-column') );
        column.visible( ! column.visible() );
    } );        
});