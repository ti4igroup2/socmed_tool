var base_url_prefix = base_url+"/dashboard";

function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
}

getPopularPost=()=>{
  url = base_url_prefix+"/getPopularPost"
  
  set_ajax(url,{},"tableLoading",function(response){
    $element = "";
    response.data.map(d=>{
      $element += '<div class="col-md-4 shadow p-3 mb-5 bg-white rounded" style="white-space: initial !important;height:250px;overflow:hidden;float:left;margin:auto;"><a style="text-decoration:none;margin:0;padding:0;"'+
      'href="'+base_url+'/post?message='+d.message.substr(0,40)+'" class="overlay-content" ><img src="'+d.full_picture+'" height="150" width="100%"></a><br><p style="">'+d.message.substr(0,40)+'</p>'+
      '<span class="overlay-img-content"><h6 class="overlay-img-detail"><i class="fab fa-facebook"></i> '+d.fanpage.fanpage_name+'</h6></span>'+
      '</div>'
    })
    $("#mostPopular").html($element)
   })
}

getPopularCreator=()=>{
  url = base_url_prefix+"/getPopularCreator"
  
  set_ajax(url,{},"tableLoading",function(response){
    $element = "";
    $element += "<ul style='list-style: decimal; width:100%;margin-left: -5%;'>"
    response.data.map(d=>{
      $element += '<li style="margin:10%;"><h5 style="margin-bottom:0px;">'+d.admin_creator+'</h5><br><a href="'+base_url+'/post?creator='+d.admin_creator+'" class="btn btn-link">'+d.total+' Posts This Month</a></li>'
    })
    $element += '</ul>'
    $("#mostCreator").html($element)
   })
}


getFacebookRank=()=>{
    url = base_url_prefix+"/getRank/1"
    set_ajax(url,{},"tableLoading",function(response){
        $element = "";
      
       response.data.map(d=>{
           var lastCount="";
          if(d.last_counts){
            lastCount = d.last_counts.socmed_total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
          }else{
            lastCount = "0";
          }
        $element += '<div class="media friendlist-box align-items-center justify-content-center m-b-20">'+
                    '<div class="m-r-10 photo-table">'+
                    '<img class="rounded-circle" style="width:40px;" src="'+base_url+'/assets/img/fb.png" alt="'+d.socmed_name+'">'+
                    '</div><div class="media-body">'+
                    '<h6 class="m-0 d-inline"><a class="btn btn-link" href="'+base_url+'/socmed/report/'+d.id+'">'+d.socmed_name+'</a></h6>'+
                    '<span class="float-right d-flex  align-items-center"><i class="'+d.summary+'"></i>'+formatNumber(d.last_counts.socmed_total)+'</span>'+
                    '</div></div>'
       })
       $("#facebookrank").html($element)
    })
}

getTwitterRank=()=>{
  url = base_url_prefix+"/getRank/3"
  set_ajax(url,{},"tableLoading",function(response){
      $element = "";
      
     response.data.map(d=>{
         var lastCount="";
        if(d.last_counts){
          lastCount = d.last_counts.socmed_total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }else{
          lastCount = "0";
        }
      $element += '<div class="media friendlist-box align-items-center justify-content-center m-b-20">'+
                  '<div class="m-r-10 photo-table">'+
                  '<img class="rounded-circle" style="width:40px;" src="'+base_url+'/assets/img/twitter.png" alt="'+d.socmed_name+'">'+
                  '</div><div class="media-body">'+
                  '<h6 class="m-0 d-inline"><a class="btn btn-link" href="'+base_url+'/socmed/report/'+d.id+'">'+d.socmed_name+'</a></h6>'+
                  '<span class="float-right d-flex  align-items-center"><i class="'+d.summary+'"></i>'+formatNumber(d.last_counts.socmed_total)+'</span>'+
                  '</div></div>'
     })
     $("#twitterrank").html($element)
  })
}

getInstagramRank=()=>{
  url = base_url_prefix+"/getRank/6"
  set_ajax(url,{},"tableLoading",function(response){
      $element = "";
      
     response.data.map(d=>{
         var lastCount="";
        if(d.last_counts){
          lastCount = d.last_counts.socmed_total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }else{
          lastCount = "0";
        }
      $element += '<div class="media friendlist-box align-items-center justify-content-center m-b-20">'+
                  '<div class="m-r-10 photo-table">'+
                  '<img class="rounded-circle" style="width:40px;" src="'+base_url+'/assets/img/instagram.jpeg" alt="'+d.socmed_name+'">'+
                  '</div><div class="media-body">'+
                  '<h6 class="m-0 d-inline"><a class="btn btn-link" href="'+base_url+'/socmed/report/'+d.id+'">'+d.socmed_name+'</a></h6>'+
                  '<span class="float-right d-flex  align-items-center"><i class="'+d.summary+'"></i>'+formatNumber(d.last_counts.socmed_total)+'</span>'+
                  '</div></div>'
     })
     $("#instagramrank").html($element)
  })
}

getYoutubeRank=()=>{
  url = base_url_prefix+"/getRank/2"
  set_ajax(url,{},"tableLoading",function(response){
      $element = "";
      
     response.data.map(d=>{
         var lastCount="";
        if(d.last_counts){
          lastCount = d.last_counts.socmed_total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }else{
          lastCount = "0";
        }
      $element += '<div class="media friendlist-box align-items-center justify-content-center m-b-20">'+
                  '<div class="m-r-10 photo-table">'+
                  '<img class="rounded-circle" style="width:40px;" src="'+base_url+'/assets/img/youtube.png" alt="'+d.socmed_name+'">'+
                  '</div><div class="media-body">'+
                  '<h6 class="m-0 d-inline"><a class="btn btn-link" href="'+base_url+'/socmed/report/'+d.id+'">'+d.socmed_name+'</a></h6>'+
                  '<span class="float-right d-flex  align-items-center"><i class="'+d.summary+'"></i>'+formatNumber(d.last_counts.socmed_total)+'</span>'+
                  '</div></div>'
     })
     $("#youtuberank").html($element)
  })
}

getAlexaRank=()=>{
  url = base_url_prefix+"/getAlexaRank/7/alexa_local_rank"
  set_ajax(url,{},"tableLoading",function(response){
      $element = "";
      
     response.data.map(d=>{
         var lastCount="";
        if(d.last_counts){
          lastCount = d.last_counts.alexa_local_rank.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }else{
          lastCount = "0";
        }
      $element += '<div class="media friendlist-box align-items-center justify-content-center m-b-20">'+
                  '<div class="m-r-10 photo-table">'+
                  '<img class="rounded-circle" style="width:40px;" src="'+base_url+'/assets/img/url.png" alt="'+d.alexa_name+'">'+
                  '</div><div class="media-body table-responsive"><table class="table table-hover" style="margin-bottom:0px !important;"><tr>'+
                  '<td width="60%" style="padding:0px"><a class="btn btn-link" href="'+base_url+'/alexa/report/'+d.id+'">'+d.alexa_name+'</a></td>'+
                  '<td style="text-align:left;padding:0px">Local&nbsp;&nbsp;&nbsp;<i class="'+d.summary+'"></i>'+formatNumber(d.last_counts.alexa_local_rank)+'</td>'+
                  '<td style="text-align:left;padding:0px">Global&nbsp;&nbsp;&nbsp;<i class="'+d.summary_global+'"></i>'+formatNumber(d.last_counts.alexa_rank)+'</td>'+
                  '</tr></table></div></div>'
     })
     $("#alexarank").html($element)
  })
}

$(document).ready(function(){
    getPopularPost();
    getPopularCreator();
    getFacebookRank();
    getTwitterRank();
    getInstagramRank();
    getYoutubeRank();
    getAlexaRank();
})