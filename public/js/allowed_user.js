var base_url_prefix = base_url+"/user_allow",
totalRecords = 0,
records = [],
displayRecords = [],
filterSearch = "",
recPerPage = 10;

count_users=()=>{
    url = base_url_prefix+"/count_users"
    var params = {search:filterSearch}
    set_ajax(url,params,"null",function(response){
        if(response.result==true){
        var total = Math.ceil(response.data/recPerPage);
        if(total!=0){
            localStorage.setItem('total_page',total)
            apply_pagination();
            }else{
                $("#tableBody").html("<tr><td colspan='3'><h4 class='text-danger text-center'>Data Not Found</h4></td></tr>");
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
                get_users_all();
            }
     });
}



get_users_all=()=>{
    displayRecordsIndex = Math.max(localStorage.getItem('active_page') - 1, 0) * recPerPage;
    var url = base_url_prefix+"/get_users"
    var params = {search:filterSearch,offset:displayRecordsIndex,limit:recPerPage}
    set_ajax(url,params,"tableLoading",function(response){
        $no = 1;
        $element = "";
       response.data.map(d=>{
        $element += '<tr style="text-align:left;vertical-align:left">'
            $element+= '<td>'+$no+'</td><td>'+d.user_email+'</td>'+
                '<td><button onclick="editForm('+d.id+')" data-placement="right"  class="btn btn-secondary btn-rounded btn-icon btn-sm" title="" data-toggle="tooltip" data-original-title="Edit Data"><i class="fas fa-edit m-0"></i></button><button onclick="deleteForm('+d.id+')" title="" data-toggle="tooltip" data-original-title="Delete" class="btn theme-bg2 text-white f-12 btn-rounded btn-icon"><i class="fas fa-trash m-0"></i></button></td>'
        $element += '</tr>'
        $no++
       })
       $("#tableBody").html($element)
    })
}



$("#find").on('click',function(e){
    e.preventDefault();
    filterSearch = $("#search-name").val();
    count_users();
})
$("#reload").on('click',function(e){
    e.preventDefault();
    $("#search-name").val("");
    filterSearch = "";
    count_users();
    
})

$("#formEmail").submit(function(e){
    e.preventDefault();
    var formData = new FormData(this);
    set_ajax(base_url_prefix+"/action",formData,"null",function(response){
       if(response=="success"){
        $.notify("Add Data Success","success");
        $('#formEmail')[0].reset();
        $("input[name=action]").val("add");
        count_users();
       }
    },true);
})


$("#formEmail").on("reset",function(){
    $("input[name=action]").val("add");
})


editForm=(id)=>{
    set_ajax(base_url_prefix+"/detail_users",{id},"null",function(response){
        if(response.result==true){
            var d = response.data[0];
            $("input[name=user_email]").val(d.user_email);
            $("input[name=action]").val("update");
            $("input[name=id]").val(d.id);
            $("#user_role").val(d.role);
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
                count_users();
        })
    } 
});
}

$(document).ready(function(){
    count_users();
})