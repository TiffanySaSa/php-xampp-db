<?php
class Config_db {

    function __construct($str) {
        // $this->init();
    }

    public function init() {
        $servername = 'localhost';
        $username = 'root';
        $password = '';
        $dbname = 'test';
        
        $link = mysqli_connect($servername, $username, $password);
        mysqli_set_charset($link, "utf8");
        mysqli_select_db($link, $dbname);
        
        if(!$link) {
            die("Connection failed: ". mysqli_connect_error());
        }

        return $link;
    }

    public function connectDB($sql) {
        $link =  self::init();
        $query = mysqli_query($link, $sql);
        if($query) {
            return $query;
        }else{
            echo "Error:".$sql."<br>".mysqli_error($link);
            return false;
        }
        mysqli_close($link);
    }
}



?>