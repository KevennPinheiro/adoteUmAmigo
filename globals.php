<?php

  session_start();

  $BASE_URL = "http://" . $_SERVER["SERVER_NAME"] . dirname($_SERVER["REQUEST_URI"]."?") . "/";

  $address = (object) [
    'cep' => '',
    'logradouro' => '',
    'bairro' => '',
    'localidade' => '',
    'uf' => ''
];

if (isset ($_POST['cep'])) {
   
   $cep = $_POST['cep'];

   $cep = preg_replace('/[^0-9]/','', $cep);

   if (preg_match('/^[0-9]{5}-?[0-9]{3}$/', $cep)) {
       //chamada a api direto do site
       $url = "https://viacep.com.br/ws/{$cep}/json/";

       $address = json_decode(file_get_contents($url));
   } else {
       $address->cep = 'CEP inválido!';
   }
}