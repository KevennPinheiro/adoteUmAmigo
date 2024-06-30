<?php

  require_once("models/Comment.php");
  require_once("models/Message.php");

  require_once("dao/UserDAO.php");

  class CommentDao implements CommentDAOInterface {

    private $conn;
    private $url;
    private $message;

    public function __construct(PDO $conn, $url) {
      $this->conn = $conn;
      $this->url = $url;
      $this->message = new Message($url);
    }

    public function buildComment($data) {

      $commentObject = new Comment();

      $commentObject->id = $data["id"];
      $commentObject->comment = $data["comment"];
      $commentObject->users_id = $data["users_id"];
      $commentObject->pets_id = $data["pets_id"];

      return $commentObject;

    }

    public function create(Comment $comment) {

      $stmt = $this->conn->prepare("INSERT INTO comments (
        comment, pets_id, users_id
      ) VALUES (
        :comment, :pets_id, :users_id
      )");

      $stmt->bindParam(":comment", $comment->comment);
      $stmt->bindParam(":pets_id", $comment->pets_id);
      $stmt->bindParam(":users_id", $comment->users_id);

      $stmt->execute();

      // Mensagem de sucesso por adicionar Pet
      $this->message->setMessage("Comentário adicionado com sucesso!", "success", "index.php");

    }

    public function getPetComment($id) {

      $comments = [];

      $stmt = $this->conn->prepare("SELECT * FROM comments WHERE pets_id = :pets_id");

      $stmt->bindParam(":pets_id", $id);

      $stmt->execute();

      if($stmt->rowCount() > 0) {

        $commentsData = $stmt->fetchAll();

        $userDao = new UserDao($this->conn, $this->url);

        foreach($commentsData as $comment) {

          $commentObject = $this->buildComment($comment);

          // Chamar dados do usuário
          $user = $userDao->findById($commentObject->users_id);

          $commentObject->user = $user;

          $comments[] = $commentObject;
        }

      }

      return $comments;

    }

    public function hasAlreadyCommented($id, $userId) {

      $stmt = $this->conn->prepare("SELECT * FROM comments WHERE pets_id = :pets_id AND users_id = :users_id");

      $stmt->bindParam(":pets_id", $id);
      $stmt->bindParam(":users_id", $userId);

      $stmt->execute();

      if($stmt->rowCount() > 0) {
        return true;
      } else {
        return false;
      }

    }

    public function destroy($id) {
      $stmt = $this->conn->prepare("DELETE FROM comments WHERE id = :id");
      $stmt->bindParam(":id", $id);
      $stmt->execute();
    }

  }