<?php

include_once("./GetData.php");
include_once("./APILog.php");
include_once("./config_db.php");

$json = file_get_contents('php://input');

// input log
// APILog::inputLog($json);

$data = json_decode($json, true);
$cmd = $data["cmd"];
if(isset($data["params"])) $params = $data["params"];


switch($cmd) {
    case "getMember":
        $res = GetData::getMember();
        break;
    case "getPet":
        $res = GetData::getPet($params);
        break;
    default:
        $res = array(
            "status" => 999,
            "msg"    => "ERR_FUNCTION_NOT_EXIST",
        );
        break;
}

$result = json_encode($res);

echo $result;


// output log
// APILog::outputLog($result);



// echo $result;

?>