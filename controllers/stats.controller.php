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
    
        public function obtenerVolumen() {
        $eval = "SELECT fecha AS label, SUM(series*repeticiones) AS data FROM ejerciciosstats WHERE id_usuario = ? GROUP BY fecha";
        $peticion = $this->db->prepare($eval);
        $peticion->execute([IDUSER]);
        $resultado = $peticion->fetchAll(PDO::FETCH_OBJ);
        exit(json_encode($resultado));
    }
    
        public function obtenerIntensidad() {
        $eval = "SELECT fecha AS label, SUM(series*repeticiones*peso) AS data FROM ejerciciosstats WHERE id_usuario = ? GROUP BY fecha";
        $peticion = $this->db->prepare($eval);
        $peticion->execute([IDUSER]);
        $resultado = $peticion->fetchAll(PDO::FETCH_OBJ);
        exit(json_encode($resultado));
    }
    
    

}
