<?php

$body = isset($_GET['Body']) ? $_GET['Body'] : "";
$data = parseCommandData($body);

if ($data['device_name'] == "error")
{
    dumpResult($data);
}
else
{
    //TODO: Standardize parameters and formatting of commands
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://vrlabs-qa.verticalresponse.com/iota/adaptors/lightswitch/index.php/2/',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => array(
            translateAction($data['action']) => $data['param']
        ))
    );
    $result =  curl_exec($curl);
    curl_close($curl);

    dumpResult($result);
}

function parseCommandData($string)
{
    $args = explode(" ", $string);

    if (count($args) < 3)
    {
        $result['device_name'] = "error";
        $result['message'] = "Invalid parameters specified for command: " . $args[0];
        return $result;
    }

    // TODO: Work out best order for command data words
    $result['device_name'] = $args[0];
    $result['action'] = $args[1];
    $result['param'] = $args[2];

    return $result;
}

function translateAction($string)
{
    switch ($string)
    {
        case "turn":
            return "toggle_state";
        default:
            return $string;
    }
}

function dumpResult($data)
{
    if ($data['message'] == "error")
    {
        $body = "Error: " . $data['message'];
    }
    else
    {
        $body = $data['message'];
    }

    header('Content-Type: text/xml');
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    echo "<Response>\n";
    echo "    <Sms>$body</Sms>\n";
    echo "</Response>";
}