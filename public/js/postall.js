var base_url_prefix = base_url+"/post",
fpid = $("#fpid").val(),
totalRecords = 0,
records = [],
displayRecords = [],
filterSearch = "",
fanspage_search = "",
orderByCol = "created_time",
orderByAct = "desc",
dateSearch = "",
creatorSearch = "",
recPerPage = 15;

count_post=()=>{
    url = base_url_prefix+"/count_post"
    var params = {search:filterSearch,creatorSearch,fanspage_search,dateSearch,fpid}
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
                get_post();
            }
     });
}


get_post=()=>{
    displayRecordsIndex = Math.max(localStorage.getItem('active_page') - 1, 0) * recPerPage;
    var url = base_url_prefix+"/get_post"
    var params = {search:filterSearch,dateSearch,offset:displayRecordsIndex,creatorSearch,fanspage_search,limit:recPerPage,fpid,orderByCol,orderByAct}
    set_ajax(url,params,"tableLoading",function(response){
        $no = 1;
        $element = "";
        // console.log(response.data);
       response.data.map(d=>{
        $element += '<tr>'
            $element+= '<td>'+$no+'</td><td><a style="margin:0;padding:0;" href="https://facebook.com/'+d.fanpage_id+'_'+d.id+'" target="_blank"><img src="'+d.full_picture+'" height="100" width="150"></a></td><td style="word-wrap: break-word;white-space:normal;max-width:25%;">'+d.message+'</td>'+
            '<td>like : '+toRupiah(d.likes)+'<br>comment : '+toRupiah(d.comments)+'<br>share : '+toRupiah(d.shares)+'<br>'+
            '<td>wow : '+toRupiah(d.wow)+'<br>haha : '+toRupiah(d.haha)+'<br>sad : '+toRupiah(d.sad)+'<br>angry : '+toRupiah(d.angry)+'</td></td>'+
                '<td>'+d.admin_creator.substr(0,15)+'</td><td>'+d.datepost+'</td>'
        $element += '</tr>'
        $no++
       })
       $("#tableBody").html($element)
    })
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
    count_post();
}


$("#find").on('click',function(e){
    e.preventDefault();
    filterSearch = $("#search-name").val();
    dateSearch = $("#filter-date").val();
    creatorSearch = $("#creator :selected").val();
    fanspage_search = $("#fanspage :selected").val();
    let params = new URLSearchParams(location.search.slice(1));
    params.delete('message');
    count_post();
})

$("#reload").on('click',function(e){
    e.preventDefault();
    $("#search-name").val("");
    $("#filter-date").val("");
    $("#fanspage").val("").trigger("change");
    $("#creator").val("").trigger("change");
    filterSearch = "";
    dateSearch = "";
    creatorSearch = "";
    fanspage_search = "";
    count_post();
    
})

get_fanspage=()=>{
    var url = base_url_prefix+"/get_fanspage"
    set_ajax(url,{},"null",function(response){
        
            $("#fanspage").html("");
            $("#fanspage").append("<option value=''>ALL</option>")
            response.data.map(d=>{
                $("#fanspage").append("<option value='"+d.fanpage_id+"'>"+d.fanpage_name+"</option>")
            })
        
    });
}

get_creator=()=>{
    var url = base_url_prefix+"/get_creator"
    set_ajax(url,{},"null",function(response){
            $("#creator").html("");
            $("#creator").append("<option value=''>ALL</option>")
            response.data.map(d=>{
                $("#creator").append("<option value='"+d.admin_creator+"'>"+d.admin_creator+"</option>")
            })
        
    });
}





$(document).ready(function(){
    // console.log(fpid);
    var message = location.search.split('message=')[1] ? location.search.split('message=')[1] : '';
    var creator = location.search.split('creator=')[1] ? location.search.split('creator=')[1] : '';
    // console.log(message);
    filterSearch = decodeURIComponent(message);
    creatorSearch = decodeURIComponent(creator);
    count_post();
    get_fanspage();
    get_creator();
    $('#filter-date').bootstrapMaterialDatePicker({
        weekStart: 0,
        time: false,
        maxDate: new Date(),
    })
})


