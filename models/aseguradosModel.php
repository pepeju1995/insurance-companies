<?php

include_once 'models/asegurado.php';

class AseguradosModel extends Model{
    function __construct()
    {
        parent::__construct();
    }

    function insert($datos){
        if($datos){
            $query = $this->db->connect();
            $stmt = $query->prepare("INSERT INTO asegurados (nif, nombre, apellido, direccion, localidad, cp, telefono, direccion_rep, localidad_rep, cp_rep, aseguradora) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('sssssiissis', $datos[0], $datos[1], $datos[2], $datos[3], $datos[4], $datos[5], $datos[6],
                $datos[7], $datos[8], $datos[9], $datos[10]);
            
            if($stmt->execute()){
                return true;
            }
            else{
                return false;
            }
        }
    }

    function get(){
        $asegurados = [];
        try {
            $query = $this->db->connect()->query("SELECT * FROM asegurados");
            while($row = $query->fetch_row()){
                $item = new Asegurado();
                $item->datosAsegurado($row);
                array_push($asegurados, $item);
            }
            return $asegurados;
        } catch(mysqli_sql_exception $e) {
            return false;
        }
    }

    function getByAseguradora($aseguradora){
        $asegurados = [];
        try{
            $query = $this->db->connect()->query("SELECT * FROM asegurados WHERE aseguradora = '$aseguradora'");
            while($row = $query->fetch_row()){
                $item = new Asegurado();
                $item->datosAsegurado($row);
                array_push($asegurados, $item);
            }
            return $asegurados;
        } catch(mysqli_sql_exception $e){
                return false;
        }
    }

    function getById($id){
        try{
            $query = $this->db->connect()->query("SELECT * FROM asegurados WHERE nif = '$id'");
            $item = new Asegurado();
            $item->datosAsegurado($query->fetch_row());
            return $item;
        } catch(mysqli_sql_exception $e){
                return false;
        }
    }

    function update($datos){
        $query = $this->db->connect();
        $stmt = $query->prepare("UPDATE asegurados SET direccion=?, localidad=?, cp=?, telefono=?, direccion_rep=?, localidad_rep=?, cp_rep=? WHERE nif=?");
        try{
            $stmt->bind_param('ssiissis', $datos[1], $datos[2], $datos[3], $datos[4], $datos[5], $datos[6],
                $datos[7], $datos[0]);
            if($stmt->execute()){
                return true;
            } else {
                return false;
            }
        } catch(mysqli_sql_exception $e) {
            return false;
        }
    }

    public function drop($id){
        $query = $this->db->connect();
        try{
            $query->query("DELETE FROM averias WHERE asegurado = '$id'");
            $query->query("DELETE FROM asegurados WHERE nif = '$id'");          
            return true;
        } catch(mysqli_sql_exception $e) {
            return false;
        }
    }
}

?>