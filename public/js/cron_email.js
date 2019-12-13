var base_url_prefix = base_url+"/cron_email",
totalRecords = 0,
records = [],
displayRecords = [],
filterSearch = "",
recPerPage = 10;

count_cron=()=>{
    url = base_url_prefix+"/count_cron"
    var params = {search:filterSearch}
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
                get_cron();
            }
     });
}


get_cron=()=>{
    displayRecordsIndex = Math.max(localStorage.getItem('active_page') - 1, 0) * recPerPage;
    var url = base_url_prefix+"/get_cron"
    var params = {search:filterSearch,offset:displayRecordsIndex,limit:recPerPage}
    set_ajax(url,params,"tableLoading",function(response){
        $no = 1;
        $element = "";
       response.data.map(d=>{
            var cron_time = d.email_cron.split(/[,]/g);
        $element += '<tr>'
            $element+= '<td>'+$no+'</td><td>'+d.email_subject+'</td>'+
                '<td><span>Setting : '+cron_time[0]+'<br>At : '+cron_time[1]+'</span></td>'+'<td>'+d.email_recipient.substring(0,50)+'...</td><td>'+
                ((d.email_status==1)?'<h5><span class="badge badge-success">ON<span></h5>':'<h5><span class="badge badge-danger">OFF<span></h5>')+
                '</td>'+
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
    count_cron();
})


$("#reload").on('click',function(e){
    e.preventDefault();
    $("#search-name").val("");
    filterSearch = "";
    count_cron();
    
})

showOperation=()=>{
    $("#tabledata").slideUp("fast");
    $("#operation").slideDown( "slow" );   
    $("#filterForm").hide();
}


hideOperation=()=>{

    $("#filterForm").fadeIn('slow');
    $('#formCron')[0].reset();
    $("#operation").slideUp("fast");
    $("#tabledata").slideDown( "slow" );
}


$("#formCron").submit(function(e){
    e.preventDefault();
    var formData = new FormData(this);
    set_ajax(base_url_prefix+"/action",formData,"null",function(response){
       if(response=="success"){
        $.notify("Add Data Success","success");
        $('#formCron')[0].reset();
        $("input[name=action]").val("add");
        count_cron();
        hideOperation();
       }
    },true);
})

$("#formCron").on("reset",function(){
    $("input[name=action]").val("add");
})


editForm=(id)=>{
    set_ajax(base_url_prefix+"/detail_cron",{id},"null",function(response){
        if(response.result==true){
            showOperation();
            var d = response.data[0];
            $("input[name=action]").val("update");
            $("input[name=id]").val(d.id);
            $("#email_subject").val(d.email_subject);
            var lists = d.email_recipient.replace(/[,]/g,'\n');
            $("#email_recipient").val(lists);
            var groups = d.email_grouplist.split(/[,]/g);
            console.log(groups);
            $("#email_grouplist1").val(groups).trigger("change");;
           var detail = d.email_cron.split(/[,]/g);
           $("#email_cron1").val(detail[0]);
           $("#email_cron2").val(detail[1]);
           $('input:radio[name="email_status"][value="'+d.email_status+'"]').attr('checked',true);
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
                count_cron();
            })
        } 
    });

}



$("#email_grouplist1").closest("form").on("reset",function(ev){
    var targetJQForm = $(ev.target);
    setTimeout((function(){
        this.find("select").trigger("change");
    }).bind(targetJQForm),0);
    });

$(document).ready(function(){
    count_cron();
    $('#email_grouplist1').select2({
        allowClear: true,
        placeholder: "Select Group Target "
    });
    console.log($("#email_cron2").val());
    $('textarea').each(function(){
        $(this).val($(this).val().trim());
    }
    );
})

$("#mailreport").on("click",function(e){
    e.preventDefault();
    $("#emailto").val("");
})

$("#mailreportnow").on("click",function(e){
    e.preventDefault();
    swal({
        title: "Are you sure?",
        text: "Send Report To All MailList Now ",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            set_ajax(base_url+"/api/mail",{user:""},"null",function(response){
                if(response.result==true){
                    $.notify("Email Has Ben Sent","success");
                }
            })
        } 
    });

   
})

$("#send").on("click",function(e){
    e.preventDefault();
    $users = $("#emailto").val();
    if($users===""){
        $.notify("User Email Cannot Be Null","error");
    }else{
        set_ajax(base_url+"/api/mail",{user:$users},"null",function(response){
            if(response.result==true){
                $.notify("Email Has Ben Sent","success");
            }
        })
    }
})
