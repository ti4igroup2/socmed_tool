var base_url_prefix = base_url+"/alexa",
totalRecords = 0,
records = [],
displayRecords = [],
filterSearch = "",
statusSearch = "",
orderByCol = "id",
orderByAct = "desc",
groupSearch = "",
recPerPage = 15;
$no = 1;

count_alexa=()=>{
    url = base_url_prefix+"/count_alexa"
    var params = {search:filterSearch,status:statusSearch,group:groupSearch}
    set_ajax(url,params,"null",function(response){
        if(response.result==true){
        var total = Math.ceil(response.data/recPerPage);
        if(total!=0){
            localStorage.setItem('total_page',total)
            apply_pagination();
            }else{
                $("#tableBody").html("<tr><td colspan='6'><h4 class='text-danger text-center'>Data Not Found</h4></td></tr>");
                if($('#pagination').data("twbs-pagination")){
                    $('.pagination').twbsPagination('destroy');
                }
            }
        }
    })
}

apply_pagination=()=>{
    if($('#pagination').data("twbs-pagination")){
                $('.pagination').twbsPagination('destroy');
            }
    $('#pagination').twbsPagination({
            totalPages: localStorage.getItem('total_page'),
            visiblePages: 5,
            onPageClick:  function  (event, page) {
                localStorage.setItem('active_page',page)
                $no = ((localStorage.getItem('active_page')-1)*recPerPage+1);
                get_alexa_all();
            }
     });
}

get_alexa_all=()=>{
    displayRecordsIndex = Math.max(localStorage.getItem('active_page') - 1, 0) * recPerPage;
    var url = base_url_prefix+"/get_alexa"
    var params = {search:filterSearch,group:groupSearch,status:statusSearch,offset:displayRecordsIndex,limit:recPerPage,orderByAct,orderByCol}
    set_ajax(url,params,"tableLoading",function(response){
        $element = "";
       response.data.map(d=>{
           var lastCount_rank=""; 
           var  lastCount_localRank="";
           var locale_code = "";
          if(d.last_counts){
            lastCount_rank = d.last_counts.alexa_rank.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            lastCount_localRank = d.last_counts.alexa_local_rank.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            locale_code = d.last_counts.alexa_locale_code;
          }else{
            lastCount_rank="0";
            locale_code = ""; 
            lastCount_localRank="0";
          }
        $element += '<tr>'
            $element+= '<td>'+$no+'</td><td><a class="btn btn-link" href=alexa/report/'+d.id+'>'+d.alexa_name+'</a></td>'
            +'<td><span>Group : '+d.group_master.group_name+'<br><p class="small">'+d.alexa_url+'</p></span></td><td>'
            +lastCount_rank+'</td><td>'+lastCount_localRank+'</td>'+
            '</td><td align="center"><button onclick="editForm('+d.id+')" data-placement="right"  class="btn btn-secondary btn-rounded btn-icon btn-sm" title="" data-toggle="tooltip" data-original-title="Edit Data"><i class="fas fa-edit m-0"></i></button><button onclick="deleteForm('+d.id+')" title="" data-toggle="tooltip" data-original-title="Delete" class="btn theme-bg2 text-white f-12 btn-rounded btn-icon"><i class="fas fa-trash m-0"></i></button><button onclick="retrieveById('+d.id+')" title="" data-toggle="tooltip" data-original-title="Refresh" class="btn btn-primary btn-rounded btn-icon"><i class="fas fa-sync m-0"></i></button></td>'
        $element += '</tr>'
        $no++
       })
       $("#tableBody").html($element)
    })
}

showOperation=()=>{
    $("#tabledata").slideUp("fast");
    $("#operation").slideDown( "slow" );   
    $("#filterForm").hide();
}
hideOperation=()=>{

    $("#filterForm").fadeIn('slow');
    $('#alexafrm')[0].reset();
    $("#operation").slideUp("fast");
    $("#tabledata").slideDown( "slow" );
}



retrieveById=(id)=>{
    set_ajax(base_url_prefix+"/retrieveAlexaById",{id},"nuul",function(response){
        if(response.result==true){
            count_alexa();
            $.notify("alexa Has Been Refreshed","success");
        }
    })
}

$("#find").on('click',function(e){
    e.preventDefault();
    groupSearch = $("#fgroupid :selected").val();
    filterSearch = $("#search-name").val();
    statusSearch = $("#status :selected").val();
    count_alexa();
})
$("#reload").on('click',function(e){
    e.preventDefault();
    $("#search-name").val("");
    $("#status").val("");
    filterSearch = "";
    statusSearch = "";
    count_alexa();
    
})

$("#alexafrm").submit(function(e){
    e.preventDefault();
    var formData = new FormData(this);
    set_ajax(base_url_prefix+"/action",formData,"null",function(response){
       if(response=="success"){
        $.notify("Add Data Success","success");
        $('#alexafrm')[0].reset();
        $("input[name=action]").val("add");
        count_alexa();
        hideOperation();       }
    },true);
})

editForm=(id)=>{
    showOperation();
    set_ajax(base_url_prefix+"/detail_alexa",{id},"null",function(response){
        if(response.result==true){
            var d = response.data[0];
            $("input[name=alexa_name]").val(d.alexa_name);
            $("input[name=alexa_url]").val(d.alexa_url);
            $("select[name=alexa_groupid]").val(d.group_id);
       
            $("input[name=action]").val("update");
            $("input[name=id]").val(d.id);
        }
    })
}

$("#alexafrm").on("reset",function(){
    $("input[name=action]").val("add");
})

deleteForm=(id)=>{
    swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this file!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
        var params = {_method: 'delete'}
        set_ajax(base_url_prefix+"/delete/"+id,params,"null",function(response){
            count_alexa();
        })
    } 
});
}

sorting=async(kolom,element)=>{
    orderByCol = kolom;
    $(".sortstatus").removeClass('sortstatus');
    $('.fa-sort-up').removeClass('fa-sort-up');
    $('.fa-sort-down').removeClass('fa-sort-down');
    ((orderByAct=="asc") ? orderByAct = "desc" : orderByAct = "asc");
    if(orderByAct=="desc"){
        $("#"+element).removeClass('sortstatus fa fa-sort-up');
        $("#"+element).addClass('sortstatus fa fa-sort-down');
        // $(".sortstatus").removeClass("fa fa-sort-down");
        // $(".sortstatus").addClass("fa fa-sort-up");
    }else{
        $("#"+element).removeClass('sortstatus fa fa-sort-down');
        $("#"+element).addClass('sortstatus fa fa-sort-up');
        // $(".sortstatus").removeClass("fa fa-sort-up");
        // $(".sortstatus").addClass("fa fa-sort-down");
    }
    count_alexa();
}

getGroup=()=>{
    set_ajax(base_url+'/group/get_groupByAlexa',{},"null",function(response){
        if(response.result==true){
            // console.log(response.data);
            $("#fgroupid").html("<option value=''>ALL</option>");
            $("#alexa_groupid").html("");
            response.data.map(d=>{
                $("#fgroupid").append("<option value='"+d.id+"'>"+d.group_name+"</option>");
                $("#alexa_groupid").append("<option value='"+d.id+"'>"+d.group_name+"</option>");
            })
        }
    })
}


$(".sortstatus").hover(function() {
    $(this).css('cursor','pointer');
}, function() {
    $(this).css('cursor','auto');
});

$(document).ready(function(){
    localStorage.setItem('active_page',1);
    count_alexa();
    getGroup();
    // get_alexa_all();
})