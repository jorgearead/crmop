<?php
/* Esta clase funciona para realizar la conexión a la base de datos desde la funcion constructor.*/
class DBConnection
{

    public $conn;

    public function __construct()
    {
        $this->conn = null;
        //Produccion
        /* $host = "mysql:host=localhost";
        $DB   = "dbname=artpromo_articulos_promocionales";
        $user = "artpromo_articulosPromo";
        $pass = "hPjrL9]Qo+3X"; */
       
        //Desarrollo
        $host = "mysql:host=localhost";
        $DB   = "dbname=crm";
        $user = "root";
        $pass = "";
        try {
            $this->conn = new PDO("$host;$DB", $user, $pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function Execute($sql)
    { //Ejecutar, esta funcion realiza una consulta retornando un verdadero y falso.
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function Query($sql)
    { //Consultar, esta funcion realiza una consulta retornando un objeto con el cual se puede interactuar.
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll();
            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function GetLastInsertID()
    {
        try {
            $id = $this->conn->lastInsertId();
            return $id;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function BeginTransaction()
    {
        try {
            $this->conn->beginTransaction();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function Rollback()
    {
        try {
            $this->conn->rollBack();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function Commit()
    {
        try {
            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function Close()
    {
        try {
            $this->conn = null;
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    //Funciones basicas en base de datos
    public function insert($array)//Esta funcion inserta nuevas filas en una tabla.
    {
        $campos = '';
        $datos = '';
        foreach ($array as $nombre => $valor) {
            $campos .= " $nombre,";
            $datos  .= " '$valor',";
        }
        $campos = preg_replace('/(,{1})$/', '', $campos);
        $datos = preg_replace('/(,{1})$/', '', $datos);
        $SQL = "INSERT INTO %s (%s) VALUES (%s)";
        $SQL = sprintf($SQL, $this->TABLA, $campos, $datos);
        $bool = $this->CONN->Execute($SQL);
        return $bool;
    }

    public function update($array, $ID)//Esta funcion actualizar un registro de una tabla.
    {
        $valores = '';
        foreach ($array as $nombre => $valor) {
            $valores .= " $nombre = '$valor',";
        }
        $valores = preg_replace('/(,{1})$/', '', $valores);
        $SQL = "UPDATE %s SET %s WHERE %s = %d";
        $SQL = sprintf($SQL, $this->TABLA, $valores, $this->PRKEY, $ID);
        $bool = $this->CONN->Execute($SQL);
        return $bool;
    }

    public function delete($ID)//Esta funcion elimina algun registro de la tabla.
    {
        $SQL = "DELETE FROM %s WHERE %s = %d;";
        $SQL = sprintf($SQL, $this->TABLA, $this->PRKEY, $ID);
        $bool    = $this->CONN->Execute($SQL);
        return $bool;
    }
    //Funciones basicas en base de datos
}
