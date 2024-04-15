
<?php
require __DIR__ . '/vendor/autoload.php';

$clientId = "5581328e-d3b4-4d82-84c0-46d4532753a8";
$clientSecret = "zKzy2KdyAHEgKlB7LUoctY59Ib3vqTKHqbawfYC2IycJDXY3vUszLy5Tyl298l1O";
$redirectUri = "https://yan.ru";
$account_domain = "{{alnaserhussein}}.amocrm.ru";
$apiClient = new AmoCRMApiClient($clientId, $clientSecret, $redirectUri);
$apiClient->setAccountBaseDomain($account_domain);
$rawToken = json_decode(file_get_contents('response.json'), 1); 
$token = new AccessToken($rawToken);
$apiClient->setAccessToken($token);



$leadsService = $apiClient->leads();
$lead = new LeadModel();
$lead->setName('Название сделки')->setPrice(54321);
$leadsCollection = new LeadsCollection();
$leadsCollection->add($lead);
$leadsCollection = $leadsService->add($leadsCollection);


