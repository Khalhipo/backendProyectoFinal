<?php

class MensajesController {

  private $db = null;

  function __construct($conexion) {
    $this->db = $conexion;
  }

  public function leerRecibidos() {
    if(IDUSER){
      //Seleccionamos todos los mensajes recibidos sustituyendo el ID del remitente por su nombre.
      $eval = 'SELECT m.id,m.mensaje,m.fecha,u.nombre as nombreRemitente,m.idRemitente,m.idDestinatario
       FROM mensajes m,users u WHERE m.idDestinatario=? AND u.id=m.idRemitente';
      $peticion = $this->db->prepare($eval);
      $peticion->execute([IDUSER]);
      $resultado = $peticion->fetchAll(PDO::FETCH_OBJ);
      //Marcar mensaje como leido una vez enviados los mensajes al usuario.
      $eval = "UPDATE mensajes SET leido=1 WHERE idDestinatario=? AND leido=0";
      $peticion = $this->db->prepare($eval);
      $peticion->execute([IDUSER]);

      exit(json_encode($resultado));
    }
    else {
      http_response_code(401);
      exit(json_encode(["error" => "Fallo de autorizacion"]));  
    }
  }

  public function leerEnviados() {
    if(IDUSER){
      //Seleccionamos todos los mensajes enviados sustituyendo el ID del destinatario por su nombre.
      $eval = 'SELECT m.id,m.mensaje,m.fecha,u.nombre as nombreDestinatario,m.idRemitente,m.idDestinatario,m.leido
      FROM mensajes m,users u WHERE m.idRemitente=? AND u.id=m.idDestinatario';
      $peticion = $this->db->prepare($eval);
      $peticion->execute([IDUSER]);
      $resultado = $peticion->fetchAll(PDO::FETCH_OBJ);
      exit(json_encode($resultado));
    } else {
      http_response_code(401);
      exit(json_encode(["error" => "Fallo de autorizacion"])); 
    }
  }
  
    public function leerMensajes() {
    if(IDUSER){
      $id_amigo = null;
      if(!empty($_GET["id"])) $id_amigo = $_GET["id"];
      //Seleccionamos todos los mensajes enviados sustituyendo el ID del destinatario por su nombre.
      $eval = "SELECT * FROM mensajes WHERE (idRemitente =? AND idDestinatario =?) OR (idRemitente =? AND idDestinatario =?)";
      $peticion = $this->db->prepare($eval);
      $peticion->execute([IDUSER,$id_amigo,$id_amigo,IDUSER]);
      $resultado = $peticion->fetchAll(PDO::FETCH_OBJ);
      
      //Marcar mensaje como leido una vez enviados los mensajes al usuario.
      foreach($resultado as $amigo){
      $eval = "UPDATE mensajes SET leido=1 WHERE idDestinatario=? AND idRemitente=? AND leido=0";
      $peticion = $this->db->prepare($eval);
      $peticion->execute([IDUSER,$amigo->idRemitente]);
      }
      
      exit(json_encode($resultado));
    } else {
      http_response_code(401);
      exit(json_encode(["error" => "Fallo de autorizacion"])); 
    }
  }

  public function enviarMensaje() {
    if(IDUSER){
      //Comprobamos que se hayan proporcionados todos los datos para enviar un mensaje.
      $mensaje = json_decode(file_get_contents("php://input"));
      if(!isset($mensaje->idDestinatario) || !isset($mensaje->mensaje)) {
        http_response_code(400);
        exit(json_encode(["error" => "No se han enviado todos los parametros"]));
      }
      //Inserta en la tabla el mensaje con los parámetros de entrada
      $eval = 'INSERT INTO mensajes (idRemitente,idDestinatario,mensaje) VALUES (?,?,?)';
      $peticion = $this->db->prepare($eval);
      $peticion->execute([IDUSER,$mensaje->idDestinatario,$mensaje->mensaje]);
      http_response_code(201);
      exit(json_encode("Mensaje enviado correctamente"));
    }
    else{
      http_response_code(401);
      exit(json_encode(["error" => "Fallo de autorizacion"]));
    }
  }

  public function editarMensaje() {
    if(IDUSER){
      $mensaje = json_decode(file_get_contents("php://input"));
      if(!isset($mensaje->id) || !isset($mensaje->mensaje)) {
        http_response_code(400);
        exit(json_encode(["error" => "No se han enviado todos los parametros"]));
      }
      //Función que solo edita los mensajes que el ha enviado.
      $eval = "UPDATE mensajes SET mensaje=?,leido=0 WHERE id=? AND idRemitente=?";
      $peticion = $this->db->prepare($eval);
      $peticion->execute([$mensaje->mensaje,$mensaje->id,IDUSER]);
      
      http_response_code(201);
      //Comprobamos si se ha editado el mensaje e informarnos en la respuesta.
      if($peticion->rowCount()) exit(json_encode("Se ha actualizado el mensaje"));
      else exit(json_encode("El mensaje no se ha actualizado"));
    } else {
      http_response_code(401);
      exit(json_encode(["error" => "Fallo de autorizacion"]));
    }
  }

  public function eliminarMensaje($id) {
    //Comprueba si se ha proporcionado un email válido.
    if(empty($id)) {
      http_response_code(400);
      exit(json_encode(["error" => "Peticion mal formada"]));    
    }
    if(IDUSER) {
      //Función que solo elimina los mensajes que ha recibido.
      $eval = "DELETE FROM mensajes WHERE id=? AND idDestinatario=?";
      $peticion = $this->db->prepare($eval);
      $peticion->execute([$id,IDUSER]);
      http_response_code(200);
      if($peticion->rowCount()) exit(json_encode("Mensaje eliminado correctamente"));
      else exit(json_encode("No se ha eliminado el mensaje"));
    } else {
      http_response_code(401);
      exit(json_encode(["error" => "Fallo de autorizacion"]));            
    }
  }
  
  public function borrarChat() {
      if(IDUSER){
      $id_amigo = null;
      if(!empty($_GET["id"])) $id_amigo = $_GET["id"];
      //Seleccionamos todos los mensajes enviados sustituyendo el ID del destinatario por su nombre.
      $eval = "DELETE FROM mensajes WHERE (idRemitente =? AND idDestinatario =?) OR (idRemitente =? AND idDestinatario =?)";
      $peticion = $this->db->prepare($eval);
      $peticion->execute([IDUSER,$id_amigo,$id_amigo,IDUSER]);
      http_response_code(200);
      if($peticion->rowCount()) exit(json_encode("Chat eliminado correctamente"));
      else exit(json_encode("No se ha eliminado el chat"));
    } else {
      http_response_code(401);
      exit(json_encode(["error" => "Fallo de autorizacion"])); 
    }
  }
}