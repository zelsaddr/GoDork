<?php
/**
* -------- GOOGLE DORKER v1--------
* 
* @Author : Izzeldin Addarda [ Security Ghost ]
* @Big Thanks for Teguh Aprianto about the Google CSE Key!
* 
**/
define('CSE_TOKEN', 'partner-pub-2698861478625135:3033704849');
    function Curl ($url, $post = 0, $headers = 0, $proxy = 0){
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HEADER         => true,
        );
        if($proxy){
            $options[CURLOPT_PROXY] = $proxy;
            $options[CURLOPT_PROXYTYPE] = CURLPROXY_SOCKS5;
        }
        if($post){
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = $post;
        }
        if($headers){
            $options[CURLOPT_HTTPHEADER] = $headers;
        }
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch);
        if(!$httpcode) return "Curl Error : ".curl_error($ch); else{
            $header = substr($response, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
            $body = substr($response, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
            curl_close($ch);
            return array($header, $body);
        }
    }
    function get_string($string, $start, $end){
        $str = explode($start, $string);
        $str = explode($end, $str[1]);
        return $str[0];
    }
    function banner (){
        print " _______       ______             _\n";     
        print "(_______)     (______)           | |\n";    
        print " _   ___  ___  _     _ ___   ____| |  _\n"; 
        print "| | (_  |/ _ \| |   | / _ \ / ___) |_/ )\n";
        print "| |___) | |_| | |__/ / |_| | |   |  _ ( \n";
        print " \_____/ \___/|_____/ \___/|_|   |_| \_)\n";
        print " Version : 1.0   | By Mazterin.Com\n\n";
    }
    banner();
    echo "# DORK : "; $dork = fgets(STDIN);
    $i = 0;
    while(true){
        $headers = array();
        $headers[] = 'Referer: https://cse.google.com/cse?cx='.CSE_TOKEN;
        $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.'.rand(0000, 3333).'.169 Safari/537.36';
        $getInfo = Curl("https://cse.google.com/cse.js?hpg=1&cx=".CSE_TOKEN, 0, $headers);
        preg_match('#"cselibVersion": "(.*?)"#si', $getInfo[1], $csiLib); // CSILIB
        preg_match('#"cx": "(.*?)"#si', $getInfo[1], $cx); // CX
        preg_match('#"cse_token": "(.*?)"#si', $getInfo[1], $cseToken); // CSE TOKEN
        preg_match('#"exp": \["(.*?)", "(.*?)"\]#si', $getInfo[1], $exp); // EXP
        preg_match('#"resultSetSize": "(.*?)"#si', $getInfo[1], $rsz); // RSZ
        $url = "https://cse.google.com/cse/element/v1?rsz=".$rsz[1]."&num=10&&start=".$i."&hl=en&source=gcsc&gss=.com&cselibv=".$csiLib[1]."&cx=".$cx[1]."&q=".rawurlencode($dork)."&safe=off&cse_tok=".$cseToken[1]."&exp=".$exp[1].",".$exp[2]."&callback=google.search.cse.api16950";
        $json = Curl($url, 0, $headers);
        preg_match_all('#"clicktrackUrl": "(.*?)"#si', $json[1], $trackUrl);
        if($trackUrl[1] != NULL){
            foreach($trackUrl[1] as $index => $key){
                echo urldecode(get_string($key, '&q=', '&sa'))."\n";
            }
        }else{
            echo "{!} DONE!\n";
            exit();
        }
        $i = $i+10;
    }
?>
