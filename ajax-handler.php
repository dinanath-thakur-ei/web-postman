<?php

require_once 'config.php';
require_once 'curl.php';

$postData = $_POST;
$response = array('status' => '');

$errors = array();

switch ($postData['method']) {

    case 'getList':
        $microserviceName = $postData['microservice'];

        $microserviceConfig = executeCurl(API_BASE_URL . 'Mindspark/creator/GetMicroserviceDetails', ['microservice' => $microserviceName])['data'];

        $tasks = [];
        foreach ($microserviceConfig['taskFlow'] as $key => $task) {
            foreach ($task as $key => $value) {
                $tasks[] = $value['taskName'];
            }
        }
        $finalData['tasks'] = array_unique($tasks);
        sort($finalData['tasks']);

        $contexts = [];
        $apiList  = [];
        foreach ($microserviceConfig['microservice'] as $key => $api) {
            if (!empty($api['context'])) {
                $contexts = array_merge($contexts, $api['context']);
            }
            $apiList[] = $key;
        }

        $finalData['apiList'] = array_unique($apiList);
        sort($finalData['apiList']);
        $finalData['contexts'] = array_unique($contexts);
        sort($finalData['contexts']);

        $finalData['objects'] = array_keys($microserviceConfig['persistence']);
        sort($finalData['objects']);
        $response = array('status' => 'succcess', 'message' => 'microservice details', 'data' => $finalData);

        break;

    case 'createApi':

        $response = executeCurl(API_BASE_URL . 'Mindspark/creator/CreateFramework', ['postData' => json_encode($postData)]);

        break;

    case 'getApiDetails':
        $microserviceName = $postData['microservice'];
        $apiName          = $postData['apiName'];

        $microserviceConfig = executeCurl(API_BASE_URL . 'Mindspark/creator/GetMicroserviceDetails', ['microservice' => $microserviceName])['data'];

        if (isset($microserviceConfig['microservice'][$apiName])) {
            $apiDetails = $microserviceConfig['microservice'][$apiName];
            $response   = array('status' => 'succcess', 'message' => 'Api details found', 'data' => $apiDetails);
        } else {
            $response = array('status' => 'error', 'message' => 'Api name not found');
        }
        break;

    default:
        $response = array('status' => 'error', 'message' => 'Parameter not passed.');
        break;
}
header('Content-Type: application/json');

if ($response['status'] === 'error') {
    header('HTTP/1.1 500 Internal Server error');
}

echo json_encode($response);
