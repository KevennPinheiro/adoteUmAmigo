<?php

  require_once("globals.php");
  require_once("db.php");
  require_once("models/Pet.php");
  require_once("models/Message.php");
  require_once("dao/UserDAO.php");
  require_once("dao/PetDAO.php");


  $message = new Message($BASE_URL);
  $userDao = new UserDAO($conn, $BASE_URL);
  $petDao = new PetDAO($conn, $BASE_URL);

  // Resgata o tipo do formulário
  $type = filter_input(INPUT_POST, "type");

  // Resgata dados do usuário
  $userData = $userDao->verifyToken();

  $user = new User(); //inserido para verificar o admin


  if($type === "create") {

    // Receber os dados dos inputs do pet
    $name = filter_input(INPUT_POST, "name");
    $description = filter_input(INPUT_POST, "description");
    $pet = filter_input(INPUT_POST, "pet");
    $sex = filter_input(INPUT_POST, "sex");
    $size = filter_input(INPUT_POST, "size");

    $Pet = new Pet();


    // Validação mínima de dados
    if(!empty($name) && !empty($description) && !empty($pet) && !empty($sex) && !empty($size)) {

      $Pet->name = $name;
      $Pet->description = $description;
      $Pet->pet = $pet;
      $Pet->sex = $sex;
      $Pet->size = $size;
      $Pet->users_id = $userData->id;

      // Upload de imagem do pet
      if(isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

        $image = $_FILES["image"];
        $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
        $jpgArray = ["image/jpeg", "image/jpg"];

        // Checando tipo da imagem
        if(in_array($image["type"], $imageTypes)) {

          // Checa se imagem é jpg
          if(in_array($image["type"], $jpgArray)) {
            $imageFile = imagecreatefromjpeg($image["tmp_name"]);
          } else {
            $imageFile = imagecreatefrompng($image["tmp_name"]);
          }

          // Gerando o nome da imagem
          $imageName = $Pet->imageGenerateName();

          imagejpeg($imageFile, "./img/pets/" . $imageName, 100);

          $Pet->image = $imageName;

        } else {

          $message->setMessage("Tipo inválido de imagem, insira png ou jpg!", "error", "back");

        }

      }
      $petDao->create($Pet);

    } else {

      $message->setMessage("Você precisa adicionar as informações!", "error", "back");

    }

  } else if($type === "delete") {

    // Recebe os dados do form
    $id = filter_input(INPUT_POST, "id");

    $Pet = $petDao->findById($id);

    if($Pet) {

      // Verifica se o pet é do usuário
      if($Pet->users_id === $userData->id || $userData->isAdmin === 1){

          $petDao->destroy($Pet->id);

      } else {

        $message->setMessage("Informações inválidas!", "error", "index.php");

      }

    } else {

      $message->setMessage("Informações inválidas!", "error", "index.php");

    }

  } else if($type === "update") { 

    // Receber os dados dos inputs
    $name = filter_input(INPUT_POST, "name");
    $description = filter_input(INPUT_POST, "description");
    $pet = filter_input(INPUT_POST, "pet");
    $sex = filter_input(INPUT_POST, "sex");
    $size = filter_input(INPUT_POST, "size");
    $id = filter_input(INPUT_POST, "id");

    $petData = $petDao->findById($id);

    // Verifica se encontrou o Pet
    if($petData) {

      // Verificar se o pet é do usuário
      if($petData->users_id === $userData->id) {

        // Validação mínima de dados
        if(!empty($name) && !empty($description) && !empty($pet) && !empty($sex) && !empty($size)) {

          // Edição do pet
          $petData->name = $name;
          $petData->description = $description;
          $petData->pet = $pet;
          $petData->sex = $sex;
          $petData->size = $size;
          

          // Upload de imagem do pet
          if(isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

            $image = $_FILES["image"];
            $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
            $jpgArray = ["image/jpeg", "image/jpg"];

            // Checando tipo da imagem
            if(in_array($image["type"], $imageTypes)) {

              // Checa se imagem é jpg
              if(in_array($image["type"], $jpgArray)) {
                $imageFile = imagecreatefromjpeg($image["tmp_name"]);
              } else {
                $imageFile = imagecreatefrompng($image["tmp_name"]);
              }

              // Gerando o nome da imagem
              $Pet = new Pet();

              $imageName = $Pet->imageGenerateName();

              imagejpeg($imageFile, "./img/pets/" . $imageName, 100);

              $petData->image = $imageName;

            } else {

              $message->setMessage("Tipo inválido de imagem, insira png ou jpg!", "error", "back");

            }

          }

          $petDao->update($petData);

        } else {

          $message->setMessage("Você precisa adicionar todas as informações!", "error", "back");

        }

      } else {

        $message->setMessage("Informações inválidas!", "error", "index.php");

      }

    } else {

      $message->setMessage("Informações inválidas!", "error", "index.php");

    }
  
  } else {

    $message->setMessage("Informações inválidas!", "error", "index.php");

  }