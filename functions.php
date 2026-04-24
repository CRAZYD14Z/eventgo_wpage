<?php
    use Firebase\JWT\JWT;
    
    $payload = [
        "iat" => time(),
        "exp" => time() + 3600, // Expira en 1 hora
        "cliente_id" => ID_CLIENT     // Este ID determinará la conexión posterior
    ];
    $jwt = JWT::encode($payload, TOKEN_KEY ,'HS256');


    function API($JWT,$URL,$DATA,$METHOD){
        $IdCliente = ID_CLIENT;
        $ch = curl_init($URL);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Retornar el resultado como string
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $METHOD);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $DATA);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $JWT",        // ENVIAMOS EL JWT AQUÍ
            "Content-Type: application/json",
            "Accept: application/json",
            "X-ID-CLIENT: $IdCliente"
        ]);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);    
        if (curl_errno($ch)) {
            die("Error de conexión: " . curl_error($ch));
        }

        curl_close($ch);
        return $response;
    }


    $api_url = URL_API."account/".ID_CLIENT;
    $data='';
    $data = json_decode(API($jwt,$api_url,$data,'GET'), true);
    //print_r($data);
    if ($data['status'] === 'success') {
        foreach ($data['account'] as $account) {

            define('COMPANY_NAME', $account['NombreCompania']);
            define('COMPANY_LOGO', $account['Logo']);
            define('NOSOTROS', $account['Nosotros']);
            define('MISIONVISION', $account['MisionVision']);
            define('COBERTURA', $account['Cobertura']);
            define('URLFace', $account['URLFace']);
            define('URLX', $account['URLX']);
            define('URLInsta', $account['URLInsta']);
            define('URLWhats', $account['URLWhats']);
            define('URLLink', $account['URLLink']);
            define('URLYou', $account['URLYou']);
            define('DIRECCION1', $account['Direccion'])." ".$account['Direccion2'];
            define('DIRECCION2', $account['Ciudad'].", ".$account['Estado']." ".$account['CP']);
            define('PLACE_ID', $account['PlaceID']);
            define('CORREO', $account['Correo']);
        }
    }
    else{

    echo $data['error'];

    }

    //die();

?>