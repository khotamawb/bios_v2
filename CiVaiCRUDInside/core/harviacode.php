<?php

class Harviacode
{

    private $host;
    private $user;
    private $password;
    private $database;
    private $sql;

    function __construct()
    {
        $this->connection();
    }

    function connection()
    {
        $subject = file_get_contents('../application/config/database.php');
        $string = str_replace("defined('BASEPATH') OR exit('No direct script access allowed');", "", $subject);

        $con = 'core/connection.php';
        $create = fopen($con, "w") or die("Change your permision folder for application and harviacode folder to 777");
        fwrite($create, $string);
        fclose($create);

        require $con;

        $this->host = $db['default']['hostname'];
        $this->user = $db['default']['username'];
        $this->password = $db['default']['password'];
        $this->database = $db['default']['database'];

        $this->sql = new mysqli($this->host, $this->user, $this->password, $this->database);
        if ($this->sql->connect_error) {
            echo $this->sql->connect_error . ", please check 'application/config/database.php'.";
            die();
        }

        unlink($con);
    }

    function field_list()
    {
        //$dbx=$this->database;
        $tablex = $_GET['table'];
        $query = "SELECT ORDINAL_POSITION,COLUMN_NAME,IS_NULLABLE,COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=? AND TABLE_NAME=?  ORDER BY COLUMN_NAME ASC";
        $stmt = $this->sql->prepare($query) or die("Error code :" . $this->sql->errno . " (not_primary_field)");
        $stmt->bind_param('ss', $this->database, $tablex);
        $stmt->bind_result($ORDINAL_POSITION, $COLUMN_NAME, $IS_NULLABLE, $COLUMN_TYPE);
        $stmt->execute();
        while ($stmt->fetch()) {
            $colums[] = array('ORDINAL_POSITION' => $ORDINAL_POSITION, 'COLUMN_NAME' => $COLUMN_NAME, 'ISNULLABLE' => $IS_NULLABLE, 'COLUMN_TYPE' => $COLUMN_TYPE);
        }
        return $colums;
        $stmt->close();
        $this->sql->close();
    }

    function table_list()
    {
        $query = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA=? ORDER BY TABLE_NAME ASC";
        $stmt = $this->sql->prepare($query) or die("Error code :" . $this->sql->errno . " (not_primary_field)");
        $stmt->bind_param('s', $this->database);
        $stmt->bind_result($table_name);
        $stmt->execute();
        while ($stmt->fetch()) {
            $fields[] = array('table_name' => $table_name);
        }
        return $fields;
        $stmt->close();
        $this->sql->close();
    }

    function insert_menu()
    {
        if (isset($_POST['controller'])) {
            $id = '';
            $title = $_POST['controller'];
            $url = $_POST['controller'];
            $icon = 'fal fa-align-justify';
            $main = 0;
            $aktif = 'y';
            $group = $_POST['modul'];
            $query = "INSERT INTO zx_tbl_menu values(?,?,?,?,?,?,?)";
            $stmt = $this->sql->prepare($query);
            $stmt->bind_param('ssssss', $id, $title, $url, $icon, $main, $aktif, $group);
            $stmt->execute();
            $stmt->close();
            $this->sql->close();
        }
    }

    function menumain()
    {
        $conn = new mysqli($this->host, $this->user, $this->password, $this->database);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $url = $_POST['controller'];
        $sql = "SELECT id_menu FROM zx_tbl_menu where url='" . $url . "'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $title = $_POST['tombol'];
            $url = $_POST['controller'];
            $sqlx = "UPDATE zx_tbl_menu set title='" . $title . "' where url='" . $url . "'";
            $result = $conn->query($sqlx);
        } else {
            $url = $_POST['controller'];
            $main_menu = "0";
            $title = $_POST['tombol'];
            $icon = "fal fa-align-justify";
            $aktif = "y";
            $group = $_POST['modul'];
            $sqlx = "INSERT INTO zx_tbl_menu (`title`,`url`,`icon`,`is_main_menu`,`is_aktif`,`group_menu`) values('" . $title . "', '" . $url . "', '" . $icon . "', '" . $main_menu . "', '" . $aktif . "', '" . $group . "')";
            $result = $conn->query($sqlx);
        }
        $conn->close();
    }

    function primary_field($table)
    {
        $query = "SELECT COLUMN_NAME,COLUMN_KEY FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=? AND TABLE_NAME=? AND COLUMN_KEY = 'PRI'";
        $stmt = $this->sql->prepare($query) or die("Error code :" . $this->sql->errno . " (primary_field)");
        $stmt->bind_param('ss', $this->database, $table);
        $stmt->bind_result($column_name, $column_key);
        $stmt->execute();
        $stmt->fetch();
        return $column_name;
        $stmt->close();
        $this->sql->close();
    }

    function not_primary_field($table)
    {
        $query = "SELECT ORDINAL_POSITION,COLUMN_NAME,COLUMN_KEY,DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=? AND TABLE_NAME=? AND COLUMN_KEY <> 'PRI' ORDER BY ORDINAL_POSITION ASC";
        $stmt = $this->sql->prepare($query) or die("Error code :" . $this->sql->errno . " (not_primary_field)");
        $stmt->bind_param('ss', $this->database, $table);
        $stmt->bind_result($ordinal_position, $column_name, $column_key, $data_type);
        $stmt->execute();
        while ($stmt->fetch()) {
            $fields[] = array('ordinal_position' => $ordinal_position, 'column_name' => $column_name, 'column_key' => $column_key, 'data_type' => $data_type);
        }
        return $fields;
        $stmt->close();
        $this->sql->close();
    }

    function all_field($table)
    {
        $query = "SELECT COLUMN_NAME,COLUMN_KEY,DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=? AND TABLE_NAME=?";
        $stmt = $this->sql->prepare($query) or die("Error code :" . $this->sql->errno . " (not_primary_field)");
        $stmt->bind_param('ss', $this->database, $table);
        $stmt->bind_result($column_name, $column_key, $data_type);
        $stmt->execute();
        while ($stmt->fetch()) {
            $fields[] = array('column_name' => $column_name, 'column_key' => $column_key, 'data_type' => $data_type);
        }
        return $fields;
        $stmt->close();
        $this->sql->close();
    }
}

$hc = new Harviacode();
