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

    public function getPet($params) {
        
        $account = $params["account"];
        // $phone = $params["phone"];
        if(!isset($account)) {
            return self::setResult(-2, "", "ERR_NO_ACCOUNT");
        }

        $memId = self::sql_getMemberID($account);
        if(!$memId) return self::setResult(-1, "", "ERR_CONNECT");
        
        $result = self::sql_get_Pet($memId);
        if(!$result) return self::setResult(-1, "", "ERR_CONNECT");
        
        return self::setResult(0, $result);
    }

    private function sql_get_member() {
        $sql = "select * ";
        $sql.= "from member ";

        $res = Config_db::connectDB($sql);

        if($res == false) return $res;

        $ary = array();
        while($row = mysqli_fetch_assoc($res)) {
            $ary[] = $row;
        }

        return $ary;
    }

    private function sql_getMemberID($account) {
        $sql = "select id ";
        $sql.= "from member ";
        $sql.= "where account = '".$account."' ";

        $res = Config_db::connectDB($sql);

        if($res == false) return $res;

        $row = mysqli_fetch_assoc($res);
        return  $row["id"];
    }

    private function sql_get_Pet($memId) {
        $sql = "select * ";
        $sql.= "from pets ";
        $sql.= "where host = '".$memId."' ";

        $res = Config_db::connectDB($sql);
        
        
        // if($res["num_rows"]== 0) return array();
        if($res == false) return $res;

        $ary = array();
        while($row = mysqli_fetch_assoc($res)) {
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