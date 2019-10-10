var base_url_prefix = base_url+"/socmed/report";
var tabel=[];
var tabel2=[];
var action = $("#filter :selected").val();
var params = {};
set_chart=()=>{
    $('#lineChart').remove();
    $('.chart').append('<canvas id="lineChart" style="height:250px"></canvas>');
set_ajax(base_url_prefix+"/getFilterReport/"+$('#param_id').val()+"/"+action,params,"nuu",function(response){
    tabel=[];
    tabel2=[];
    for(var i in response.data){
        tabel.push({
            "label" : response.data[i].tgl,
            "values" : response.data[i].total
        })
        // tabel.push(response.data[i].tgl);
        // tabel2.push(response.data[i].total);
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
    // $('#end_range').bootstrapMaterialDatePicker({
    //     weekStart: 0,
    //     time: false
    // });

})