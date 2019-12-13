var base_url_prefix = base_url+"/socmed",
totalRecords = 0,
records = [],
displayRecords = [],
filterSearch = "",
statusSearch = "",
recPerPage = 15;

count_sosmed=()=>{
    url = base_url_prefix+"/count_sosmed"
    var params = {search:filterSearch,status:statusSearch}
    set_ajax(url,params,"null",function(response){
        if(response.result==true){
        var total = Math.ceil(response.data/recPerPage);
        localStorage.setItem('total_page',total)
        apply_pagination();
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
                get_sosmed_all();
            }
     });
}

get_sosmed_all=()=>{
    displayRecordsIndex = Math.max(localStorage.getItem('active_page') - 1, 0) * recPerPage;
    var url = base_url_prefix+"/get_sosmed"
    var params = {search:filterSearch,status:statusSearch,offset:displayRecordsIndex,limit:recPerPage}
    set_ajax(url,params,"tableLoading",function(response){
        $no = 1;
        $element = "";
       response.data.map(d=>{
           var lastCount="";
          if(d.last_counts){
            lastCount = d.last_counts.socmed_total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
          }else{
            lastCount = "0";
          }
        $element += '<tr>'
            $element+= '<td>'+$no+'</td><td><a href=socmed/report/'+d.id+'>'+d.socmed_name+'</a></td>'
            +'<td><span>Type : '+d.socmed_type+'<br>Group : '+d.socmed_group+'<br><p class="small">'+d.socmed_url+'</p></span></td><td>'+lastCount+'</td><td>'+
                ((d.socmed_status==1)?'<span class="badge bg-green">ON<span>':'<span class="badge bg-red">OFF<span>')+
                '</td><td width="100px"><div style="font-size:10px; margin:1px;"><button onclick="editForm('+d.id+')" class="btn btn-default btn-xs"><i class="fa fa-pencil"></i></button><button onclick="deleteForm('+d.id+')" class="btn btn-warning btn-xs"><i class="fa fa-trash"></i></button><button onclick="retrieveById('+d.id+')" class="btn btn-success btn-xs"><i class="fa fa-refresh"></i></button></div></td>'
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
        $('#socemdfrm')[0].reset();
        $("input[name=action]").val("add");
        count_sosmed();
       }
    },true);
})

editForm=(id)=>{
    set_ajax(base_url_prefix+"/detail_socmed",{id},"null",function(response){
        if(response.result==true){
            var d = response.data[0];
            $("input[name=socmed_name]").val(d.socmed_name);
            $("input[name=socmed_url]").val(d.socmed_url);
            $("select[name=socmed_type]").val(d.socmed_type);
            $("input[name=socmed_group]").val(d.socmed_group);
            $('input:radio[name="socmed_status"][value="'+d.socmed_status+'"]').attr('checked',true);
            $("input[name=action]").val("update");
            $("input[name=id]").val(d.id);
        }
    })
}

deleteForm=(id)=>{
    if (confirm("Are you sure want to delete?")) {
        var params = {_method: 'delete'}
        set_ajax(base_url_prefix+"/delete/"+id,params,"null",function(response){
            count_sosmed();
        })
    }
    return false;
}

$(document).ready(function(){
    count_sosmed();
    // get_sosmed_all();
})