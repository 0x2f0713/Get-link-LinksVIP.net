<?php
$link = "link=".$_GET['url']."&pass=undefined&hash=&captcha=";
$URL = 'https://linksvip.net/GetLinkFs';
# Hàm mình tham khảo trên GitHub: https://gist.github.com/jrivero/5598138 .
function file_get_contents_curl($url, $retries=5, $post)
{
    $ua = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.82 Safari/537.36';
    if (extension_loaded('curl') === true)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); // The URL to fetch. This can also be set when initializing a session with curl_init().
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); // TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
        #curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // The number of seconds to wait while trying to connect.
        #curl_setopt($ch, CURLOPT_USERAGENT, $ua); // The contents of the "User-Agent: " header to be used in a HTTP request.
        curl_setopt($ch, CURLOPT_FAILONERROR, TRUE); // To fail silently if the HTTP code returned is greater than or equal to 400.
        #curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE); // To follow any "Location: " header that the server sends as part of the HTTP header.
        #curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE); // To automatically set the Referer: field in requests where it follows a Location: redirect.
        #curl_setopt($ch, CURLOPT_TIMEOUT, 10); // The maximum number of seconds to allow cURL functions to execute.
        #curl_setopt($ch, CURLOPT_MAXREDIRS, 5); // The maximum number of redirects
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Host: linksvip.net',
    'Accept: application/json, text/javascript, */*; q=0.01',
    'Origin: https://linksvip.net',
    'X-Requested-With: XMLHttpRequest',
    'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36',
    'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
    'DNT: 1',
    'Referer: https://linksvip.net/',
    'Cookie: __cfduid=d4caae757dd449d8a3fdfaabddad0e48a1512302783; user=huynguyenvn.huynguyen%40gmail.com; pass=da54f18eb2c83e2df54abce273309123; __zlcmid=k0gyT6eoKkbPCg; PHPSESSID=5iunl3bebj0b76ag5je1pvlgt7; __atuvc=63%7C2%2C6%7C3'// Đây là tài khoản Public
    ));  #Tạo HTTP Header tùy chỉnh
        $result = curl_exec($ch);
        curl_close($ch);
    }
    else
    {
        $result = file_get_contents($url);
    }        
    if (empty($result) === true)
    {
        $result = false;
       if ($retries >= 1)
        {
            sleep(1);
            return file_get_contents_curl($url, --$retries);
        }
    }    
    return $result;
}
  $result = preg_replace( '/[^[:print:]\r\n]/', '',file_get_contents_curl($URL, 5, $link));
  $echo = json_decode($result,TRUE);
  if ($echo['trangthai'] == 1) {
      $rep  = array(
      'messages' => array(
          0 => array(
            'text' => 'Link của bạn đã sẵn sàng để download. <3'
          ),
          1 => array(
            'text' => '*Tên file:* '.$echo['filename']
          ),
          2 => array (
              'text' => '*Download:* '.$echo['linkvip']
          ),
      )
  );
  } elseif ($echo['trangthai'] == 0) {
          $rep  = array(
      'messages' => array(
          0 => array(
            'text' => $echo['loi']
          ),
      )
  );
  }

  echo json_encode($rep);
