var base_url_prefix = base_url+"/socmed",
totalRecords = 0,
records = [],
displayRecords = [],
filterSearch = "",
statusSearch = "",
groupSearch = "",
orderByCol = "id",
orderByAct = "desc",
recPerPage = 15;
$no = 1;

count_sosmed=()=>{
    url = base_url_prefix+"/count_sosmed"
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
                get_sosmed_all();
            }
     });
}

get_sosmed_all=()=>{
    displayRecordsIndex = Math.max(localStorage.getItem('active_page') - 1, 0) * recPerPage;
    var url = base_url_prefix+"/get_sosmed"
    var params = {search:filterSearch,group:groupSearch,status:statusSearch,offset:displayRecordsIndex,limit:recPerPage,orderByCol,orderByAct}
    set_ajax(url,params,"tableLoading",function(response){
        $element = "";
        console.log(response.data);
       response.data.map(d=>{
           var lastCount="";
          if(d.last_counts){
            lastCount = d.last_counts.socmed_total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
          }else{
            lastCount = "0";
          }

        $element += '<tr>'
            $element+= '<td>'+$no+'</td><td><a class="btn btn-link" href=socmed/report/'+d.id+'>'+d.socmed_name+'</a></td>'
            +'<td><span>Type : '+d.socmed_type+'<br>Group : '+d.group_master.group_name+'<br>ID : '+d.channelid+' <a target="_blank" class="text-black" href="'+d.socmed_url+'"><i class="fa fa-external-link"></i></a></span></td><td>'+lastCount+'</td><td>'+
                ((d.socmed_status==1)?'<h5><span class="badge badge-success">ON<span></h5>':'<h5><span class="badge badge-danger">OFF<span></h5>')+
                '</td><td><button onclick="editForm('+d.id+')" data-placement="right"  class="btn btn-secondary btn-rounded btn-icon btn-sm" title="" data-toggle="tooltip" data-original-title="Edit Data"><i class="fas fa-edit m-0"></i></button><button onclick="deleteForm('+d.id+')" title="" data-toggle="tooltip" data-original-title="Delete" class="btn theme-bg2 text-white f-12 btn-rounded btn-icon"><i class="fas fa-trash m-0"></i></button><button onclick="retrieveById('+d.id+')" title="" data-toggle="tooltip" data-original-title="Refresh" class="btn btn-primary btn-rounded btn-icon"><i class="fas fa-sync m-0"></i></button></td>'
        $element += '</tr>'
        $no++
       })
       $("#tableBody").html($element)
    })
}


retrieveById=(id)=>{
    set_ajax(base_url_prefix+"/retrieveById",{id},"nuul",function(response){
        if(response.result==true){
            count_sosmed();
            $.notify("Sosmed Has Been Refreshed","success");
        }
    })
}


$("#find").on('click',function(e){
    e.preventDefault();
    groupSearch = $("#fgroupid :selected").val();
    filterSearch = $("#search-name").val();
    statusSearch = $("#status :selected").val();
    count_sosmed();
})


$("#reload").on('click',function(e){
    e.preventDefault();
    $("#search-name").val("");
    $("#status").val("");
    filterSearch = "";
    statusSearch = "";
    count_sosmed();
    
})

$("#socemdfrm").submit(function(e){
    e.preventDefault();
    var formData = new FormData(this);
    set_ajax(base_url_prefix+"/action",formData,"null",function(response){
       if(response=="success"){
        $.notify("Add Data Success","success");
        $("input[name=action]").val("add");
        count_sosmed();
        hideOperation();
       }
    },true);
})

editForm=(id)=>{
    showOperation();
    set_ajax(base_url_prefix+"/detail_socmed",{id},"null",function(response){
        if(response.result==true){
            var d = response.data[0];
            $("select[name=socmed_groupid]").val(d.group_id);
            $("input[name=socmed_url]").val(d.socmed_url);
            $("select[name=socmed_type]").val(d.socmed_type);
            $("input[name=socmed_name]").val(d.socmed_name);
            $('input:radio[name="socmed_status"][value="'+d.socmed_status+'"]').attr('checked',true);
            $("input[name=action]").val("update");
            $("input[name=id]").val(d.id);
        }
    })
}

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
                count_sosmed();
            })
        } 
    });
}

showOperation=()=>{
    $("#tabledata").slideUp("fast");
    $("#operation").slideDown( "slow" );   
    $("#filterForm").hide();
}
hideOperation=()=>{

    $("#filterForm").fadeIn('slow');
    $('#socemdfrm')[0].reset();
    $("#operation").slideUp("fast");
    $("#tabledata").slideDown( "slow" );
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
    count_sosmed();
}

getGroup=()=>{
    set_ajax(base_url+'/group/get_groupBySocmed',{},"null",function(response){
        if(response.result==true){
            // console.log(response.data);
            $("#fgroupid").html("<option value=''>ALL</option>");
            $("#socmed_groupid").html("");
            response.data.map(d=>{
                $("#fgroupid").append("<option value='"+d.id+"'>"+d.group_name+"</option>");
                $("#socmed_groupid").append("<option value='"+d.id+"'>"+d.group_name+"</option>");
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
    count_sosmed();
    getGroup();
    // get_sosmed_all();
})