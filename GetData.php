<?php

include_once('./config_db.php');
class GetData {
    public function __construct() {
    }

    public function getMember() {
        $result = self::sql_get_member();

        if($result===false) return self::setResult(-1, "", "ERR_CONNECT");

        return self::setResult(0, $result);
    }

    public function getPet($params) {
        
        $account = $params["account"];
        
        if(!isset($account)) {
            return self::setResult(-2, "", "ERR_NO_ACCOUNT");
        }

        $memId = self::sql_getMemberID($account);
        if($memId===false) return self::setResult(-1, "", "ERR_CONNECT");
        
        $result = self::sql_get_Pet($memId);
        if($result===false) return self::setResult(-1, "", "ERR_CONNECT");
        
        return self::setResult(0, $result);
    }

    public function addMember($params) {
        $account = $params["account"];
        $password = $params["password"];
        $name = $params["name"];
        $phone = $params["phone"];
        
        $id = self::sql_add_member($account, $password, $name, $phone);

        if($id>0)  return self::setResult(0, array("id" => $id));
    }

    public function addPets($params) {
        $name = $params["name"];
        $year = $params["year"];
        $sex = $params["sex"];
        $host = $params["host"];

        $id = self::sql_add_pets($name, $year, $sex, $host);

        if($id>0)  return self::setResult(0, array("id" => $id));
    }

    public function deleteMember($params) {
        $id = $params["id"];

        self::sql_delete_member($id);
        self::sql_delete_pets($id);

        return self::setResult(0, );
    }

    private function sql_add_pets($name, $year, $sex, $host) {
        $sql = "insert into pets (`name`, `year`, `sex`, `host`) ";
        $sql.= "values ( '$name' , '$year', '$sex', '$host' )";

        $res = Config_db::writeDB($sql);
        if($res == false) return $res;
        
        return $res;
    }

    private function sql_delete_member($id) {
        $sql = "delete from member ";
        $sql.= "where `id` = '$id'";

        $res = Config_db::writeDB($sql);
        if($res == false) return $res;
        
        return $res;
    }

    private function sql_delete_pets($id) {
        $sql = "delete from pets ";
        $sql.= "where `id` = '$id'";

        $res = Config_db::writeDB($sql);
        if($res == false) return $res;
        
        return $res;
    }

    private function sql_add_member($account, $password, $name, $phone) {
        $sql = "insert into member (`account`, `password`, `name`, `phone`) ";
        $sql.= "values ( '$account' , '$password', '$name', '$phone' )";

        $res = Config_db::writeDB($sql);
        if($res == false) return $res;
        
        return $res;
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
        // echo $sql;
        $res = Config_db::connectDB($sql);
    
        if($res == false) return $res;

        $ary = array();
        while($row = mysqli_fetch_assoc($res)) {
            $ary[] = $row;
        }

        return $ary;
    }

    private function setResult($status, $data=false, $msg=false) {
        return array(
            "status" => $status,
            "data"   => $data,
            "msg"    => $msg,
        );
    }
}
?>