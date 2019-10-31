var base_url_prefix = base_url+"/group",
totalRecords = 0,
records = [],
displayRecords = [],
filterSearch = "",
statusSearch = "",
orderByCol = "id",
orderByAct = "desc",
recPerPage = 10;
$no = 1;

count_group=()=>{
    url = base_url_prefix+"/count_group"
    var params = {search:filterSearch,status:statusSearch}
    set_ajax(url,params,"null",function(response){
        if(response.result==true){
        var total = Math.ceil(response.data/recPerPage);
        if(total!=0){
            localStorage.setItem('total_page',total)
            apply_pagination();
            }else{
                $("#tableBody").html("<tr><td colspan='4'><h4 class='text-danger text-center'>Data Not Found</h4></td></tr>");
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
                get_group_all();
            }
     });
}



get_group_all=()=>{
    displayRecordsIndex = Math.max(localStorage.getItem('active_page') - 1, 0) * recPerPage;
    var url = base_url_prefix+"/get_group"
    var params = {search:filterSearch,status:statusSearch,offset:displayRecordsIndex,limit:recPerPage,orderByCol,orderByAct}
    set_ajax(url,params,"tableLoading",function(response){
        $element = "";
       response.data.map(d=>{
        $element += '<tr>'
            $element+= '<td><input class="form-control form-control-sm" min="1" id="order'+d.id+'" onChange="updateOrder('+d.id+')" type="number" value="'+d.group_order+'" ></td><td>'+d.group_name+'</td>'+
                '<td class="text-left">Type : '+d.group_type+'<br><div class="row">&nbsp;&nbsp;&nbsp;&nbsp;Status : '+((d.group_status==1)?'<h6><span class="badge badge-success">ON<span></h6>':'<h6><span class="badge badge-danger">OFF<span></h6>')+'</div></td>'+
                '<td align="left"><button onclick="editForm('+d.id+')" data-placement="right"  class="btn btn-secondary btn-rounded btn-icon btn-sm" title="" data-toggle="tooltip" data-original-title="Edit Data"><i class="fas fa-edit m-0"></i></button><button onclick="deleteForm('+d.id+')" title="" data-toggle="tooltip" data-original-title="Delete" class="btn theme-bg2 text-white f-12 btn-rounded btn-icon"><i class="fas fa-trash m-0"></i></button></td>'
        $element += '</tr>'
        $no++
       })
       $("#tableBody").html($element)
    })
}

$(".sortstatus").hover(function() {
    $(this).css('cursor','pointer');
}, function() {
    $(this).css('cursor','auto');
});

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
    count_group();
}


$("#find").on('click',function(e){
    e.preventDefault();
    filterSearch = $("#search-name").val();
    statusSearch = $("#status :selected").val();
    count_group();
})


showOperation=()=>{
    $("#tabledata").slideUp("fast");
    $("#operation").slideDown( "slow" );   
    $("#filterForm").hide();
}
hideOperation=()=>{

    $("#filterForm").fadeIn('slow');
    $('#formGroup')[0].reset();
    $("#operation").slideUp("fast");
    $("#tabledata").slideDown( "slow" );
}



updateOrder=(id)=>{
    var order = $("#order"+id).val();
    if(order<0 || order >20){
        $("#order"+id).val(order)
    }else{
    set_ajax(base_url_prefix+"/updateOrder",{id,order},"null",function(response){
        if(response.result==true){
            count_group();
        }
    })
}

}

$("#reload").on('click',function(e){
    e.preventDefault();
    $("#search-name").val("");
    $("#status").val("");
    filterSearch = "";
    statusSearch = "";
    count_group();
})

$("#formGroup").submit(function(e){
    e.preventDefault();
    var formData = new FormData(this);
    set_ajax(base_url_prefix+"/action",formData,"null",function(response){
       if(response=="success"){
        $.notify("Add Data Success","success");
        $('#formGroup')[0].reset();
        $("input[name=action]").val("add");
        count_group();
        hideOperation();
       }
    },true);
})

$("#formGroup").on("reset",function(){
    $("input[name=action]").val("add");
})

editForm=(id)=>{
    set_ajax(base_url_prefix+"/detail_group",{id},"null",function(response){
        if(response.result==true){
            showOperation();
            var d = response.data[0];
            $("input[name=group_name]").val(d.group_name);
            $("input[name=action]").val("update");
            $("input[name=id]").val(d.id);
            $('input:radio[name="group_status"][value="'+d.group_status+'"]').attr('checked',true);
            $("#group_type").val(d.group_type);
        }
    })
}

deleteForm=(id)=>{
    if (confirm("Are you sure want to delete?")) {
        var params = {_method: 'delete'}
        set_ajax(base_url_prefix+"/delete/"+id,params,"null",function(response){
            count_group();
        })
    }
    return false;
}

$(document).ready(function(){

    $(".sortstatus").addClass("fa fa-sort-up");
    count_group();
})