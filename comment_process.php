<?php

  require_once("globals.php");
  require_once("db.php");
  require_once("models/Pet.php");
  require_once("models/Comment.php");
  require_once("models/Message.php");
  require_once("dao/UserDAO.php");
  require_once("dao/PetDAO.php");
  require_once("dao/CommentDAO.php");

  $message = new Message($BASE_URL);
  $userDao = new UserDAO($conn, $BASE_URL);
  $petDao = new PetDAO($conn, $BASE_URL);
  $commentDao = new CommentDAO($conn, $BASE_URL);

  // Recebendo o tipo do formulário
  $type = filter_input(INPUT_POST, "type");

  // Resgata dados do usuário
  $userData = $userDao->verifyToken();

  if($type === "create") {

    // Recebendo dados do post
    $comment = filter_input(INPUT_POST, "comment");
    $pets_id = filter_input(INPUT_POST, "pets_id");
    $users_id = $userData->id;

    $commentObject = new Comment();

    $petData = $petDao->findById($pets_id);

    // Validando se o pet existe
    if($petData) {

      // Verificar dados mínimos
      if(!empty($comment) && !empty($pets_id)) {

        $commentObject->comment = $comment;
        $commentObject->pets_id = $pets_id;
        $commentObject->users_id = $users_id;

        $commentDao->create($commentObject);

      } else {

        $message->setMessage("Você precisa inserir o comentário!", "error", "back");

      }

    } else {

      $message->setMessage("Informações inválidas!", "error", "index.php");

    }

  } else {

    $message->setMessage("Informações inválidas!", "error", "index.php");

  }