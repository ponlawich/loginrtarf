<?php

    $username = $_POST['username'];
    $password = $_POST['password'];
   
    $gg = array('username' => $username, 'password' => $password);
    $url = "https://itdev.rtarf.mi.th/welfare/index.php/authentication_2";
    $curlAD = curl_init();

    curl_setopt($curlAD,CURLOPT_URL,$url);
    curl_setopt($curlAD, CURLOPT_POST, true);
    curl_setopt($curlAD, CURLOPT_POSTFIELDS, $gg);
    curl_setopt($curlAD,CURLOPT_RETURNTRANSFER,true);
    //curl_setopt ($curlAD, CURLOPT_CAINFO, FCPATH."assets/ca/cacert.pem");
    $output = curl_exec($curlAD); 
    $curlErr = curl_error($curlAD);

    if ($curlErr) {
        $data['status']  = false;
        $data['errno']   = curl_errno($curlAD);
        $data['error']   = curl_error($curlAD);
    } else {
        $data['status']     = true;
        $data['http_code']  = curl_getinfo($curlAD, CURLINFO_HTTP_CODE);
        $data['response']   = $output;
    }

    curl_close($curlAD);

    var_dump($data['status']);




?>