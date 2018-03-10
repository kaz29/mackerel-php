<?php
require_once 'boot.php';

use Mackerel\Client;
use GuzzleHttp\Client as HttpClient;

(new Client(
    new HttpClient(
        [
            'timeout' => 10,
            'connect_timeout' => 10,
        ]
    ),
    'APIKEY'
))->postServiceMetric(
    'SERVICENAME',
    [
        [
            'name' => 'metric.name',
            'time' => time(),
            'value' => 100,
        ],
    ]
);
