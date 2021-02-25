<?php

use \Firebase\JWT\JWT;

class UserController {

  private $db = null;

  function __construct($conexion) {
    $this->db = $conexion;
  }

  public function listarUser() {
      //Comprueba si el usuario esta registrado.
      if(IDUSER) {      
      $busqueda = null;
      if(!empty($_GET["busqueda"])) $busqueda = $_GET["busqueda"];

      $eval = "SELECT * FROM users WHERE";
      $eval .= $busqueda ? " email LIKE '%".$busqueda."%'" : null;

      $peticion = $this->db->prepare($eval);
      $peticion->execute();
      $resultado = $peticion->fetchAll(PDO::FETCH_OBJ);
      exit(json_encode($resultado));
    } else {
      http_response_code(401);
      exit(json_encode(["error" => "Fallo de autorizacion"]));       
    }
  }
  
  //Listar Amigos
    public function listarAmigos() {
      //Comprueba si el usuario esta registrado.
      if(IDUSER) {      
      $eval = "SELECT A2.id, A2.nombre, A2.email, A2.imgSrc FROM users A1 INNER JOIN amigos B ON A1.id = B.id_usuario INNER JOIN users A2 ON B.id_amigo = A2.id WHERE A1.id =" . IDUSER;
      $peticion = $this->db->prepare($eval);
      $peticion->execute();
      $resultado = $peticion->fetchAll(PDO::FETCH_OBJ);
      exit(json_encode($resultado));
    } else {
      http_response_code(401);
      exit(json_encode(["error" => "Fallo de autorizacion"]));       
    }
  }

  public function leerPerfil() {
    if(IDUSER) {
      $eval = "SELECT nombre,email,sexo,altura,peso,imgSrc FROM users WHERE id=?";
      $peticion = $this->db->prepare($eval);
      $peticion->execute([IDUSER]);
      $resultado = $peticion->fetchObject();
      exit(json_encode($resultado));
    } else {
      http_response_code(401);
      exit(json_encode(["error" => "Fallo de autorizacion"]));       
    }
  }

  public function hacerLogin() {
    //Se obtienen los datos recibidos en la peticion.
    $user = json_decode(file_get_contents("php://input"));

    if(!isset($user->email) || !isset($user->password)) {
      http_response_code(400);
      exit(json_encode(["error" => "No se han enviado todos los parametros"]));
    }
  
    //Primero busca si existe el usuario, si existe que obtener el id y la password.
    $peticion = $this->db->prepare("SELECT id,password FROM users WHERE email = ?");
    $peticion->execute([$user->email]);
    $resultado = $peticion->fetchObject();
  
    if($resultado) {
  
      //Si existe un usuario con ese email comprobamos que la contraseña sea correcta.
      if(password_verify($user->password, $resultado->password)) {
  
        //Preparamos el token.
        $iat = time();
        $exp = $iat + 3600*24*2;
        $token = array(
          "id" => $resultado->id,
          "iat" => $iat,
          "exp" => $exp
        );
  
        //Calculamos el token JWT y lo devolvemos.
        $jwt = JWT::encode($token, CJWT);
        http_response_code(200);
        exit(json_encode($jwt));
  
      } else {
        http_response_code(401);
        exit(json_encode(["error" => "Password incorrecta"]));
      }
  
    } else {
      http_response_code(404);
      exit(json_encode(["error" => "No existe el usuario"]));  
    }
  }

  public function subirAvatar() {
    if(is_null(IDUSER)){
      http_response_code(401);
      exit(json_encode(["error" => "Fallo de autorizacion"]));
    }
    if(isset($_FILES['imagen'])) {
      $imagen = $_FILES['imagen'];
      $mime = $imagen['type'];
      $size = $imagen['size'];
      $rutaTemp = $imagen['tmp_name'];
  
      //Comprobamos que la imagen sea JPEG o PNG y que el tamaño sea menor que 400KB.
      if( !(strpos($mime, "jpeg") || strpos($mime, "png")) || ($size > 400000) ) {
        http_response_code(400);
        exit(json_encode(["error" => "La imagen tiene que ser JPG o PNG y no puede ocupar mas de 400KB"]));
      } else {
  
        //Comprueba cual es la extensión del archivo.
        $ext = strpos($mime, "jpeg") ? ".jpg":".png";
        $nombreFoto = "p-".IDUSER."-".time().$ext;
        $ruta = ROOT."images/".$nombreFoto;
  
        //Comprobamos que el usuario no tenga mas fotos de perfil subidas al servidor.
        //En caso de que exista una imagen anterior la elimina.
        $imgFind = ROOT."images/p-".IDUSER."-*";
        $imgFile = glob($imgFind);
        foreach($imgFile as $fichero) unlink($fichero);
        
        //Si se guarda la imagen correctamente actualiza la ruta en la tabla usuarios
        if(move_uploaded_file($rutaTemp,$ruta)) {
  
          //Prepara el contenido del campo imgSrc
          $imgSRC = "http://localhost/".basename(ROOT)."/images/".$nombreFoto;
  
          $eval = "UPDATE users SET imgSrc=? WHERE id=?";
          $peticion = $this->db->prepare($eval);
          $peticion->execute([$imgSRC,IDUSER]);
  
          http_response_code(201);
          exit(json_encode("Imagen actualizada correctamente"));
        } else {
          http_response_code(500);
          exit(json_encode(["error" => "Ha habido un error con la subida"]));      
        }
      }
    }  else {
      http_response_code(400);
      exit(json_encode(["error" => "No se han enviado todos los parametros"]));
    }
  }
  
  public function addFriend() {
      $user = json_decode(file_get_contents("php://input"));
      $id_amigo = $user->id;
      if(is_null(IDUSER)){
      http_response_code(401);
      exit(json_encode(["error" => "Fallo de autorizacion"]));
      }
      $eval = "SELECT * FROM amigos WHERE id_usuario=? AND id_amigo=?";
      $peticion = $this->db->prepare($eval);
      $peticion->execute([IDUSER,$id_amigo]);
      $resultado = $peticion->fetchObject();
      if($resultado){
       http_response_code(409);
       exit(json_encode(["error" => "Ya eres amigo de ese usuario"]));
      } else if(IDUSER == $id_amigo){
       http_response_code(409);
       exit(json_encode(["error" => "No puedes añadirte a ti mismo a amigos"])); 
      }
      
      if(isset($user->id)){
          $id_amigo = $user->id;
          $eval = "INSERT INTO amigos (id_usuario,id_amigo) VALUES(?,?)";
          $peticion = $this->db->prepare($eval);
          $peticion->execute([IDUSER,$id_amigo]);
          $eval = "INSERT INTO amigos (id_usuario,id_amigo) VALUES(?,?)";
          $peticion = $this->db->prepare($eval);
          $peticion->execute([$id_amigo,IDUSER]);
      } else {
      http_response_code(400);
      exit(json_encode(["error" => "No se han enviado todos los parametros"]));
    }  
  }

  public function registrarUser() {
    //Guardamos los parametros de la petición.
    $user = json_decode(file_get_contents("php://input"));

    //Comprobamos que los datos sean consistentes.
    if(!isset($user->email) || !isset($user->password)) {
      http_response_code(400);
      exit(json_encode(["error" => "No se han enviado todos los parametros"]));

    }
    if(!isset($user->nombre)) $user->nombre = null;
    if(!isset($user->sexo)) $user->sexo = null;
    if(!isset($user->peso)) $user->peso = null;
    if(!isset($user->altura)) $user->altura = null;

    //Comprueba que no exista otro usuario con el mismo email.
    $peticion = $this->db->prepare("SELECT id FROM users WHERE email=?");
    $peticion->execute([$user->email]);
    $resultado = $peticion->fetchObject();
    if(!$resultado) {
      $password = password_hash($user->password, PASSWORD_BCRYPT);
      $eval = "INSERT INTO users (nombre,password,email,sexo,peso,altura) VALUES (?,?,?,?,?,?)";
      $peticion = $this->db->prepare($eval);
      $peticion->execute([
        $user->nombre,$password,$user->email,$user->sexo,$user->peso,$user->altura
      ]);
      
      //Preparamos el token.
      $id = $this->db->lastInsertId();
      $iat = time();
      $exp = $iat + 3600*24*2;
      $token = array(
        "id" => $id,
        "iat" => $iat,
        "exp" => $exp
      );

      //Calculamos el token JWT y lo devolvemos.
      $jwt = JWT::encode($token, CJWT);
      http_response_code(201);
      echo json_encode($jwt);
    } else {
      http_response_code(409);
      echo json_encode(["error" => "Ya existe este usuario"]);
    }
  }

  public function editarUser() {
    if(IDUSER) {
      //Cogemos los valores de la peticion.
      $user = json_decode(file_get_contents("php://input"));
      
      //Comprobamos si existe otro usuario con ese correo electronico.
      if(isset($user->email)) {
        $peticion = $this->db->prepare("SELECT id FROM users WHERE email=?");
        $peticion->execute([$user->email]);
        $resultado = $peticion->fetchObject();
        
        //Comprobamos si hay algun resultado, sino continuamos editando.
        if($resultado) {
          //Si el id del usuario con este email es distinto del usuario que ha hecho LOGIN.
          if($resultado->id != IDUSER) {
            http_response_code(409);
            exit(json_encode(["error" => "Ya existe un usuario con este email"]));              
          }
        } 
      }

      //Obtenemos los datos guardados en el servidor relacionados con el usuario
      $peticion = $this->db->prepare("SELECT nombre,email,sexo, peso, altura FROM users WHERE id=?");
      $peticion->execute([IDUSER]);
      $resultado = $peticion->fetchObject();

      //Combinamos los datos de la petición y de los que había en la base de datos.
      $nNombre = isset($user->nombre) ? $user->nombre : $resultado->nombre;
      $nEmail = isset($user->email) ? $user->email : $resultado->email;
      $nSexo = isset($user->sexo) ? $user->sexo : $resultado->sexo;
      $nPeso = isset($user->peso) ? $user->peso : $resultado->peso;
      $nAltura = isset($user->altura) ? $user->altura : $resultado->altura;
      

      //Si hemos recibido el dato de modificar la password.
      if(isset($user->password) && (strlen($user->password))){

        //Encriptamos la contraseña.
        $nPassword = password_hash($user->password, PASSWORD_BCRYPT);
        //Preparamos la petición.
        $eval = "UPDATE users SET nombre=?,sexo=?,password=?,email=?,peso=?,altura=? WHERE id=?";
        $peticion = $this->db->prepare($eval);
        $peticion->execute([$nNombre,$nSexo,$nPassword,$nEmail,$nPeso,$nAltura,IDUSER]);
      } else {
        $eval = "UPDATE users SET nombre=?,sexo=?,email=?,peso=?,altura=? WHERE id=?";
        $peticion = $this->db->prepare($eval);
        $peticion->execute([$nNombre,$nSexo,$nEmail,$nPeso,$nAltura,IDUSER]);        
      }
      http_response_code(201);
      exit(json_encode("Usuario actualizado correctamente"));
    } else {
      http_response_code(401);
      exit(json_encode(["error" => "Fallo de autorizacion"]));         
    }
  }

  public function eliminarUser() {
    if(IDUSER) {
        
      //Buscamos si el usuario tenía imagenes y la eliminamos.
      $imgSrc = ROOT."images/p-".IDUSER."-*";
      $imgFile = glob($imgSrc);
      foreach($imgFile as $fichero) unlink($fichero);

      //Preparamos la peticion de eliminar usuario de la base de datos.
      $eval = "DELETE FROM users WHERE id=?";
      $peticion = $this->db->prepare($eval);
      $peticion->execute([IDUSER]);
      http_response_code(200);
      exit(json_encode("Usuario eliminado correctamente"));
    } else {
      http_response_code(401);
      exit(json_encode(["error" => "Fallo de autorizacion"]));            
    }
  } 
    public function eliminarAmigo() {
    if(IDUSER) {
      $id_amigo = null;
      if(!empty($_GET["id"])) $id_amigo = $_GET["id"];
      //Preparamos la peticion de eliminar usuario de la base de datos.
      $eval = "DELETE FROM amigos WHERE id_usuario=? AND id_amigo=?";
      $peticion = $this->db->prepare($eval);
      $peticion->execute([IDUSER,$id_amigo]);
      $eval = "DELETE FROM amigos WHERE id_usuario=? AND id_amigo=?";
      $peticion = $this->db->prepare($eval);
      $peticion->execute([$id_amigo,IDUSER]);
      http_response_code(200);
      exit(json_encode("Amigo eliminado correctamente"));
    } else {
      http_response_code(401);
      exit(json_encode(["error" => "Fallo de autorizacion"]));            
    }
  } 
}