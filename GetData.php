<?php

include_once('./config_db.php');
class GetData {
    public function __construct() {
    }

    public function getMember() {
        $result = self::sql_get_member();

        if(!$result) return self::setResult(-1, "", "ERR_CONNECT");

        return self::setResult(0, $result);
    }

    private function sql_get_member() {
        $sql = "select * ";
        $sql.= "from member ";

        $res = Config_db::connectDB($sql);

        if($res == false) return $res;

        $ary = array();
        while($row = mysqli_fetch_row($res)) {
            $ary[] = $row;
        }

        return $ary;
    }

    private function setResult($status, $data, $msg=false) {
        return array(
            "status" => $status,
            "data"   => $data,
            "msg"    => $msg,
        );
    }
}
?>