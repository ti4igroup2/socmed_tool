
var base_url_prefix = base_url+"/alexa/report";
var tabel=[];
var tabel2=[];
var tabel3=[];
var action = $("#filter :selected").val();
var params = {};
set_chart=()=>{
    $('#lineChart').remove();
    $('.chart').append('<canvas id="lineChart" style="height:200px"></canvas>');
set_ajax(base_url_prefix+"/getFilterReport/"+$('#param_id').val()+"/"+action,params,"nuu",function(response){
    tabel=[];
    tabel2=[];
    tabel3=[];
    for(var i in response.data){
        tabel.push({
            "label" : response.data[i].tgl,
            "values" : response.data[i].total
        })
        tabel2.push({
            "label" : response.data[i].tgl,
            "values" : response.data[i].total2
        })
    }

    var chart = AmCharts.makeChart("Statistics-line", {
        "type": "serial",
        "theme": "light",
        "marginTop": 10,
        "marginRight": 0,
        "dataProvider": tabel,
        "graphs": [{
            "id": "g1",
            "balloonText": "[[category]]<br><b><span style='font-size:14px;'>[[value]]</span></b>",
            "bullet": "round",
            "bulletSize": 5,
            "lineColor": "#1dc4e9",
            "lineThickness": 5,
            "valueField": "values"
        }],
        "valueAxes": [{
          "reversed":true
        }],
        "chartCursor": {
            // "categoryBalloonDateFormat": "YYYY",
            "cursorAlpha": 0,
            "valueLineEnabled": true,
            "valueLineBalloonEnabled": true,
            // "valueLineAlpha": 0.5,
            "fullWidth": true
        },
        // "dataDateFormat": "YYYY",
        "categoryField": "label",
        "categoryAxis": {
            // "minPeriod": "YYYY",
            "parseDates": true,
            "minorGridAlpha": 0.1,
            "gridColor": '#fff',
            "minorGridEnabled": true
        },
    });

    var chart = AmCharts.makeChart("Statistics-line2", {
        "type": "serial",
        "theme": "light",
        "marginTop": 10,
        "marginRight": 0,
        "dataProvider": tabel2,
        "graphs": [{
            "id": "g1",
            "balloonText": "[[category]]<br><b><span style='font-size:14px;'>[[value]]</span></b>",
            "bullet": "round",
            "bulletSize": 5,
            "lineColor": "#1dc4e9",
            "lineThickness": 5,
            "valueField": "values"
        }],
        "valueAxes": [{
            "reversed":true
          }],
        "chartCursor": {
            // "categoryBalloonDateFormat": "YYYY",
            "cursorAlpha": 0,
            "valueLineEnabled": true,
            "valueLineBalloonEnabled": true,
            // "valueLineAlpha": 0.5,
            "fullWidth": true
        },
        // "dataDateFormat": "YYYY",
        "categoryField": "label",
        "categoryAxis": {
            // "minPeriod": "YYYY",
            "parseDates": true,
            "minorGridAlpha": 0.1,
            "gridColor": '#fff',
            "minorGridEnabled": true
        },
    });

    
    
})}

$("#filter").on("change",function(){
    var selected = $("#filter :selected").val();
    if(selected==="range"){
        $("#start_range").val("");
        $("#end_range").val("");
        $("#date_range").show();
    }else{
        $("#date_range").hide();
    action = $("#filter :selected").val();
    set_chart();
    }
})

$("#end_range").on("change",function(e){
    e.preventDefault();
    action = "range";
    params = {start:$("#start_range").val(),end:$("#end_range").val()}
    set_chart();
})

$(document).ready(function(){
    set_chart();
    $('#start_range').bootstrapMaterialDatePicker({
        weekStart: 0,
        time: false,
        maxDate: new Date(),
    }).on('change', function(e, date) {
        $('#end_range').bootstrapMaterialDatePicker({
            time: false,
            maxDate: new Date(),
            minDate: new Date($("#start_range").val())
        });
    });


})