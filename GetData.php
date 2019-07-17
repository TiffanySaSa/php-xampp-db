<?php

include_once('./config_db.php');
class GetData {
    public function __construct() {
    }

    public function getMember($params) {
        $index = $params["index"];
        $size = $params["size"];
        $status = ($params["status"]!== "all") ? $params["status"] : false;
        $res = self::sql_get_member(($index*$size) +1, $size, $status);
        $count = self::sql_count_member();

        if($res===false) return self::setResult(-1, "", "ERR_CONNECT");

        $result = array();
        $result["row"] = $res;
        $result["total"] = $count;

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
        $weight = $params["weight"];
        $name = $params["name"];
        $sex = $params["sex"];
        
        $id = self::sql_add_member($name, $sex, $weight);

        if($id>0)  return self::setResult(0, array("id" => $id));
    }

    public function editMember($params) {
        $id = $params["id"];
        $weight = $params["weight"];
        $name = $params["name"];
        $sex = $params["sex"];
        
        $result = self::sql_edit_member($id, $name, $sex, $weight);

        if($result== true)  return self::setResult(0, array("id" => $id));
    }

    public function addPets($params) {
        $name = $params["name"];
        $year = $params["year"];
        $sex = $params["sex"];
        $host = $params["host"];

        $id = self::sql_add_pets($name, $year, $sex, $host);

        if($id>0)  return self::setResult(0, array("id" => $id));
    }

    // 啟/停用
    public function enabledStatus($params) {
        $id = $params["id"];
        $enabled = $params["enabled"];
        
        $result = self::sql_enabled_member($id, $enabled);
        
        if($result== true)  return self::setResult(0, array("id" => $id));
    }

    public function deleteMember($params) {
        $id = $params["id"];

        self::sql_delete_member($id);
        // self::sql_delete_pets($id);

        return self::setResult(0, '');
    }

    private function sql_add_pets($name, $year, $sex, $host) {
        $sql = "insert into pets (`name`, `year`, `sex`, `host`) ";
        $sql.= "values ( '$name' , '$year', '$sex', '$host' )";

        $res = Config_db::writeDB($sql, "insert");
        if($res == false) return $res;
        
        return $res;
    }

    private function sql_edit_member($id, $name, $sex, $weight) {
        $sql = "update rabbits set ";
        $sql.= "name='$name' ";
        $sql.= ",sex='$sex' ";
        $sql.= ",weight=$weight ";
        $sql.= " where id=$id";

        $res = Config_db::writeDB($sql);
        if($res == false) return $res;
        
        return $res;
    }

    private function sql_enabled_member($id, $enabled) {
        $sql = "update rabbits set ";
        $sql.= "active='$enabled' ";
        $sql.= " where id=$id";

        $res = Config_db::writeDB($sql);
        if($res == false) return $res;
        
        return $res;
    }

    private function sql_delete_member($id) {
        $sql = "delete from rabbits ";
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

    private function sql_add_member($name, $sex, $weight) {
        $sql = "insert into rabbits (`name`, `sex`, `weight`) ";
        $sql.= "values ('$name', '$sex', '$weight' )";

        $res = Config_db::writeDB($sql, "insert");
        if($res == false) return $res;
        
        return $res;
    }

    private function sql_get_member($index, $size, $status = false) {
        $sql = "select * ";
        $sql.= "from rabbits ";
        if($status!= false) $sql.= "where active = '".$status."' ";
        $sql.= "limit $index, $size";
        // echo $sql."<br>";
        $res = Config_db::connectDB($sql);

        if($res == false) return $res;

        $ary = array();
        while($row = mysqli_fetch_assoc($res)) {
            $ary[] = $row;
        }

        return $ary;
    }

    private function sql_count_member() {
        $sql = "select COUNT(*) ";
        $sql.= "from rabbits ";
        
        $res = Config_db::connectDB($sql);

        if($res == false) return $res;

        $result = mysqli_fetch_row($res);

        return $result[0];
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