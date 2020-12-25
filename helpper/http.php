<?php



    //  get curl
     function get($url, array $queryString = []){
    //check for is set querystring
    if (isset($queryString) && !empty($queryString)) {
        //set operate in the end url Address
        $url = rtrim($url, '/') . '?';
        $queryString =urldecode( http_build_query($queryString));
        $url = $url . $queryString;


    }

    //start curl
    $ch = curl_init();

    //set option
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //execute curl
    $resCurl = curl_exec($ch);

    if (curl_error($ch)) {
        echo  $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        die(curl_error($ch));
    }

    curl_close($ch);
    echo $resCurl;

//        var_dump($http_status);
}
    //post curl
     function post($url, array $parameters = [])
    {
        $url = (empty($parameters)) ?: rtrim($url, "/");
        try {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            (!empty($parameters)) ? curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters) : null;
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);

            $resCurl = curl_exec($ch);

            $errorCurl = curl_error($ch);
            if (!empty($errorCurl)){
                die($errorCurl);
            }

            curl_close($ch);
            $response = json_decode($resCurl, true);
            return $response;

        }catch (Exception $e){
            die($e->getMessage() . $e->getCode());
        }

    }
