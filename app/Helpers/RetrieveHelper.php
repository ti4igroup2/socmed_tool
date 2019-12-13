<?php
    if (!function_exists('UrlToUsername')) {
        function UrlToUsername($url,$type)
        {
            $removeurl = parse_url($url, PHP_URL_PATH);
            switch(true){
                case (($type=="facebook") || ($type=="twitter") || ($type=="instagram")):
                $username = str_replace('/','',$removeurl);
                return $username;
                break;
                case (($type=="youtube")):
                $uri_segments = explode('/', $removeurl);
                $username = $uri_segments[2];
                return $username;
                break;
                default:
                return "no type selected";
            }
        }
    }

    function get_youtube_video_ID($youtube_video_url) {
        
        // Checks if it matches a pattern and returns the value
        if (preg_match($pattern, $youtube_video_url, $match)) {
          return $match[1];
        }
        
        // if no match return false.
        return false;
      }
    
    if(!function_exists('rupiah')){
        function rupiah($angka){
            return number_format($angka,0,',','.');
        }
    }

    if(!function_exists('json_true')){
        function json_true($data){
            $json_data = array(
                'result' => TRUE,
                'data' => $data
            );
            print json_encode($json_data);
        }
    }
    
    if(!function_exists('json_false')){
        function json_false($message,$form_error,$redirect){
            $data = array(
                'result' => FALSE,
                'message' => $message,
                'form_error' => $form_error,
                'redirect' => $redirect
            );
            print json_encode($data);
        }
    }

    if(!function_exists('dp')){
        function dp($text){
            echo "<pre>".print_r($text,TRUE)."</pre>";
           die();
        }
    }

    if(!function_exists('numbercolor')){
        function numbercolor($number){
            if($number=="" || $number == 0){
                return "<span style='color:black;'>".$number."</span>";
            }else if($number>0){
                return "<span style='color:green;'>".$number."</span>";
            }else if($number<0){
                return "<span style='color:red;'>".$number."</span>";
            }
        }
    }

    if(!function_exists('removeRupiah')){
        function removeRupiah($text){
            $text = str_replace(".", "", $text);
            return $text;
        }
    }

    if(!function_exists('switchNumber')){
        function switchNumber($text){
            if($text<0){
                $text = abs($text);
            }else if($text>0){
                $text = "-".$text;
            }
            return $text;
        }
    }

?>