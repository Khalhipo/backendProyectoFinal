<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of StatsController
 *
 * @author Bueno
 */
class StatsController {

    private $db = null;

    function __construct($conexion) {
        $this->db = $conexion;
    }

    public function obtenerPesoCorporal() {
        $eval = "select id_usuario, concat(MONTH(fecha),'-',year(fecha)) AS label , ROUND(SUM(peso)/COUNT(peso),1) AS data from pesos WHERE id_usuario = ? GROUP BY MONTH(fecha)";
        $peticion = $this->db->prepare($eval);
        $peticion->execute([IDUSER]);
        $resultado = $peticion->fetchAll(PDO::FETCH_OBJ);
        exit(json_encode($resultado));
    }
    
    public function obtenerCategorias() {
        $eval = "SELECT fecha, categoria FROM ejerciciosstats WHERE id_usuario = ? GROUP BY categoria, fecha ORDER BY fecha";
        $peticion = $this->db->prepare($eval);
        $peticion->execute([IDUSER]);
        $resultado = $peticion->fetchAll(PDO::FETCH_OBJ);
        exit(json_encode($resultado));
    }
    
    public function obtenerVolumen() {
        $categoriaFiltro = null;
        $fechaFiltro = null;
        
        $categoriaFiltro = $_GET['categoria'];
        $fechaFiltro = $_GET['fecha'];
        
        
        if(($categoriaFiltro != "" && $fechaFiltro != "")){
        $eval = "SELECT fecha AS label, categoria, SUM(series*repeticiones) AS data FROM ejerciciosstats WHERE id_usuario = ? 
        AND (MONTH(fecha) IS NULL OR MONTH(fecha) = ?) AND (YEAR(fecha) IS NULL OR YEAR(fecha) = ?) AND (categoria IS NULL OR categoria = ?) GROUP BY fecha, categoria";
        $peticion = $this->db->prepare($eval);
        $peticion->execute([IDUSER, explode('-', $fechaFiltro)[0],explode('-', $fechaFiltro)[1],$categoriaFiltro]);
        $resultado = $peticion->fetchAll(PDO::FETCH_OBJ);  
        
        } else if(($categoriaFiltro == "" && $fechaFiltro == "")) {
        $eval = "SELECT fecha AS label, SUM(series*repeticiones) AS data FROM ejerciciosstats WHERE id_usuario = ? GROUP BY fecha";
        $peticion = $this->db->prepare($eval);
        $peticion->execute([IDUSER]);
        $resultado = $peticion->fetchAll(PDO::FETCH_OBJ);
         
        } else if($categoriaFiltro == "" && $fechaFiltro != "") {
        $eval = "SELECT fecha AS label, categoria, SUM(series*repeticiones) AS data FROM ejerciciosstats WHERE id_usuario = ? 
        AND (MONTH(fecha) IS NULL OR MONTH(fecha) = ?) AND (YEAR(fecha) IS NULL OR YEAR(fecha) = ?) GROUP BY fecha";
        $peticion = $this->db->prepare($eval);
        $peticion->execute([IDUSER, explode('-', $fechaFiltro)[0],explode('-', $fechaFiltro)[1]]);
        $resultado = $peticion->fetchAll(PDO::FETCH_OBJ); 
            
        } else if($categoriaFiltro != "" && $fechaFiltro == "") {
        $eval = "SELECT fecha AS label, categoria, SUM(series*repeticiones) AS data FROM ejerciciosstats WHERE id_usuario = ? 
        AND (categoria IS NULL OR categoria = ?) GROUP BY categoria, fecha";
        $peticion = $this->db->prepare($eval);
        $peticion->execute([IDUSER,$categoriaFiltro]);
        $resultado = $peticion->fetchAll(PDO::FETCH_OBJ); 
        }
       
        exit(json_encode($resultado));
    }
    
    public function obtenerIntensidad() {
        $categoriaFiltro = null;
        $fechaFiltro = null;
        
        $categoriaFiltro = $_GET['categoria'];
        $fechaFiltro = $_GET['fecha'];
        
        
        if(($categoriaFiltro != "" && $fechaFiltro != "")){
        $eval = "SELECT fecha AS label, categoria, SUM(series*repeticiones*peso) AS data FROM ejerciciosstats WHERE id_usuario = ? 
        AND (MONTH(fecha) IS NULL OR MONTH(fecha) = ?) AND (YEAR(fecha) IS NULL OR YEAR(fecha) = ?) AND (categoria IS NULL OR categoria = ?) GROUP BY fecha, categoria";
        $peticion = $this->db->prepare($eval);
        $peticion->execute([IDUSER, explode('-', $fechaFiltro)[0],explode('-', $fechaFiltro)[1],$categoriaFiltro]);
        $resultado = $peticion->fetchAll(PDO::FETCH_OBJ);  
        
        } else if(($categoriaFiltro == "" && $fechaFiltro == "")) {
        $eval = "SELECT fecha AS label, SUM(series*repeticiones*peso) AS data FROM ejerciciosstats WHERE id_usuario = ? GROUP BY fecha";
        $peticion = $this->db->prepare($eval);
        $peticion->execute([IDUSER]);
        $resultado = $peticion->fetchAll(PDO::FETCH_OBJ);
         
        } else if($categoriaFiltro == "" && $fechaFiltro != "") {
        $eval = "SELECT fecha AS label, categoria, SUM(series*repeticiones*peso) AS data FROM ejerciciosstats WHERE id_usuario = ? 
        AND (MONTH(fecha) IS NULL OR MONTH(fecha) = ?) AND (YEAR(fecha) IS NULL OR YEAR(fecha) = ?) GROUP BY fecha";
        $peticion = $this->db->prepare($eval);
        $peticion->execute([IDUSER, explode('-', $fechaFiltro)[0],explode('-', $fechaFiltro)[1]]);
        $resultado = $peticion->fetchAll(PDO::FETCH_OBJ); 
            
        } else if($categoriaFiltro != "" && $fechaFiltro == "") {
        $eval = "SELECT fecha AS label, categoria, SUM(series*repeticiones*peso) AS data FROM ejerciciosstats WHERE id_usuario = ? 
        AND (categoria IS NULL OR categoria = ?) GROUP BY categoria, fecha";
        $peticion = $this->db->prepare($eval);
        $peticion->execute([IDUSER,$categoriaFiltro]);
        $resultado = $peticion->fetchAll(PDO::FETCH_OBJ); 
        }
       
        exit(json_encode($resultado));
    }
    
    public function obtenerEttoCategoria() {
        $eval = "SELECT categoria AS label, count(*) AS data FROM `ejerciciosstats` WHERE id_usuario = ? GROUP BY categoria";
        $peticion = $this->db->prepare($eval);
        $peticion->execute([IDUSER]);
        $resultado = $peticion->fetchAll(PDO::FETCH_OBJ);
        exit(json_encode($resultado));
    }
    
    

}
