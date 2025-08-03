<?php

    class Database{
        private $db_host="localhost";
        private $db_username="root";
        private $db_password="";
        private $db_name="mill";

        private $mysqli="";
        public $result=array();
        private $con=false;

        public $hostname="http://localhost/mill";
        public function __construct(){
            if($this->con==false){
                $this->con=true;
                $this->mysqli=new mysqli($this->db_host,$this->db_username,$this->db_password,$this->db_name);

            if($this->mysqli->connect_error){
                array_push($this->result, $this->mysqli->connect_error);
                return false;
            }else{
                
                return true;
            }
        
            }
        }

        //insert 

        public function insert($table,$param=array()){
            

            if($this->tableExists($table)){
                $tableColumn=implode(",",array_keys($param));
                $values=implode("','",$param);
                $sql="INSERT INTO $table($tableColumn) VALUES('$values')";
                $res=$this->mysqli->query($sql);
                if($this->mysqli->affected_rows==1){
                    array_push($this->result, "Data inserted ");
                    return true;
                }else{
                    array_push($this->result, "Data could not inserted ". $this->mysqli->error);
                    return false;
                }
            }else{
                array_push($this->result, $this->mysqli->error);
            }
        }

        //update

        public function update($table,$param=array(),$where=null){
            if($this->tableExists($table)){
                foreach($param as $key=>$pa){
                    $args[]=$key."='".$pa."'";
                }
                
                $sql="UPDATE $table SET ".implode(", ",$args);
                if($where!=NULL){
                    $sql.=" WHERE $where";
                }
                $res=$this->mysqli->query($sql);
                if($this->mysqli->affected_rows==1){
                    array_push($this->result,$this->mysqli->affected_rows);
                    return true;
                }else{
                    array_push($this->result,$this->mysqli->error);
                    return false;
                }
            }
        }

        //Delete

        public function delete($table,$where=null){
            if($this->tableExists($table)){
                $sql="DELETE FROM $table";
                if($where!=NULL){
                    $sql.=" WHERE $where";
                }
                $res=$this->mysqli->query($sql);
                if($this->mysqli->affected_rows==1){
                    array_push($this->result,$this->mysqli->affected_rows);
                    return true;
                }else{
                    array_push($this->result,$this->mysqli->error);
                    return false;
                }
            }
        }

        //select 

        public function select($table, $rows = '*', $join = null, $where = null, $order = null, $groupby = null){
            if($this->tableExists($table)){ 
                $sql="SELECT $rows FROM $table";
                if($join != null){
                    $sql .= ' JOIN '.$join;
                }
                if($groupby != null){
                    $sql .= ' GROUP BY ('.$groupby.')';
                }
                if($where != null){
                    $sql .= ' WHERE '.$where;
                }
                if($order != null){
                    $sql .= ' ORDER BY '.$order;
                }

                
                $query=$this->mysqli->query($sql);
                if($query){
                    $this->result=$query->fetch_all(MYSQLI_ASSOC);
                    return true;
                }else{
                    array_push($this->result, $this->mysqli->error);
                    return false;
                }

            }
        }


        public function sql($sql){
            // $this->query = $sql; // Pass back the SQL
            $query = $this->mysqli->query($sql);
    
            if($query){
          $sql_array = explode(' ',$sql);
          switch ($sql_array[0]) {
            case "INSERT":
              array_push($this->result,$this->mysqli->insert_id);
              break;
            case "UPDATE":
              array_push($this->result,$this->mysqli->affected_rows);
              break;
            case "DELETE":
              array_push($this->result,$this->mysqli->affected_rows);
              break;
            case "SELECT":
              array_push($this->result,$query->fetch_all(MYSQLI_ASSOC));
              break;
          }
                return true; 
            }else{
                array_push($this->result,$this->mysqli->error);
                return false; 
            }
        }


        private function tableExists($table){
            $sql="SHOW TABLES from $this->db_name like '$table' ";
            $tableInDb=$this->mysqli->query($sql);
            if($tableInDb->num_rows==1){
                return true;
            }else{
                array_push($this->result, $this->mysqli->error);
                return false;
            }
        }

        public function escapeString($data){
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $this->mysqli->real_escape_string($data);
          }
        public function getResult(){
            $val = $this->result;
            $this->result = array();
            return $val;
        }
    }



?>