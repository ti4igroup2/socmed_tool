var base_url_prefix = base_url+"/fbpage",
totalRecords = 0,
records = [],
displayRecords = [],
filterSearch = "",
recPerPage = 10;

count_fanspage=()=>{
    url = base_url_prefix+"/count_fanspage"
    var params = {search:filterSearch}
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
                get_fanspage();
            }
     });
}


get_fanspage=()=>{
    displayRecordsIndex = Math.max(localStorage.getItem('active_page') - 1, 0) * recPerPage;
    var url = base_url_prefix+"/get_fanspage"
    var params = {search:filterSearch,offset:displayRecordsIndex,limit:recPerPage}
    set_ajax(url,params,"tableLoading",function(response){
        $no = 1;
        $element = "";
       response.data.map(d=>{
        var owned= "";
        if(d.owned=="owned"){
            owned = '<span class="badge badge-success bagde-sm"><i class="fas fa-check"> owned<span>';
        }else{
            owned = "";
        }
        $element += '<tr style="text-align:left;">'
            $element+= '<td>'+$no+'</td><td ><a class="btn btn-link " href="/detail_fbpage/'+d.id+'">'+d.fanpage_id+'</a></td>'+
                '<td>'+d.fanpage_name+' '+owned+'</td><td style="display: ruby;">'+
                ((d.fanpage_status==1)?''+
                '<div class="switch switch-success"><input type="checkbox" onChange="onOff('+d.id+')" id="onoff'+d.id+'" checked><label for="onoff'+d.id+'" class="cr"></label></div>'
                :'<h5 id="status'+d.id+'"><div class="switch switch-success"><input type="checkbox" onChange="onOff('+d.id+')" id="onoff'+d.id+'"><label for="onoff'+d.id+'" class="cr"></label></div>')+
                '</td>'
        $element += '</tr>'
        $no++
       })
       $("#tableBody").html($element)
    })
}


$("#find").on('click',function(e){
    e.preventDefault();
    filterSearch = $("#search-name").val();
    count_fanspage();
})


$("#reload").on('click',function(e){
    e.preventDefault();
    $("#search-name").val("");
    filterSearch = "";
    count_fanspage();
    
})

onOff=(id)=>{
 
    var url = base_url_prefix+'/updateStatus';
    set_ajax(url,{id},"null",function(response){
        if(response.data=="error_token"){
            $.notify("Access Token Error, Re-List Your Fanspage To Active", "error");
            $("#onoff"+id).attr('disabled',"");
        }
    })
}


$(document).ready(function(){
    count_fanspage();

})


