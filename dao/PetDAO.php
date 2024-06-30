<?php

  require_once("models/Pet.php");
  require_once("models/Message.php");
  require_once("dao/CommentDAO.php");

  class PetDAO implements PetDAOInterface {

    private $conn;
    private $url;
    private $message;

    public function __construct(PDO $conn, $url) {
      $this->conn = $conn;
      $this->url = $url;
      $this->message = new Message($url);
    }

    public function buildPet($data) {

      $Pet = new Pet();

      $Pet->id = $data["id"];
      $Pet->name = $data["name"];
      $Pet->description = $data["description"];
      $Pet->image = $data["image"];
      $Pet->pet = $data["pet"];
      $Pet->sex = $data["sex"];
      $Pet->size = $data["size"];
      $Pet->users_id = $data["users_id"];

      return $Pet;

    }

    public function findAll() {

    }

    public function getLatestPets() {

      $Pets = [];

      $stmt = $this->conn->query("SELECT * FROM pets ORDER BY id DESC");

      $stmt->execute();

      if($stmt->rowCount() > 0) {

        $petsArray = $stmt->fetchAll();

        foreach($petsArray as $Pet) {
          $Pets[] = $this->buildPet($Pet);
        }

      }

      return $Pets;

    }

    public function getPetsByPet($pet) {

      $Pets = [];

      $stmt = $this->conn->prepare("SELECT * FROM pets
                                    WHERE pet = :pet
                                    ORDER BY id DESC");

      $stmt->bindParam(":pet", $pet);

      $stmt->execute();

      if($stmt->rowCount() > 0) {

        $petsArray = $stmt->fetchAll();

        foreach($petsArray as $Pet) {
          $Pets[] = $this->buildPet($Pet);
        }

      }

      return $Pets;

    }

    public function getPetsByUserId($id) {

      $Pets = [];

      $stmt = $this->conn->prepare("SELECT * FROM pets
                                    WHERE users_id = :users_id");

      $stmt->bindParam(":users_id", $id);

      $stmt->execute();

      if($stmt->rowCount() > 0) {

        $petsArray = $stmt->fetchAll();

        foreach($petsArray as $Pet) {
          $Pets[] = $this->buildPet($Pet);
        }

      }

      return $Pets;

    }

    public function findById($id) {

      $Pet = [];

      $stmt = $this->conn->prepare("SELECT * FROM pets
                                    WHERE id = :id");

      $stmt->bindParam(":id", $id);

      $stmt->execute();

      if($stmt->rowCount() > 0) {

        $PetData = $stmt->fetch();

        $Pet = $this->buildPet($PetData);

        return $Pet;

      } else {

        return false;

      }

    }

    public function findByName($name) {

      $Pets = [];

      $stmt = $this->conn->prepare("SELECT * FROM pets 
      WHERE name LIKE :name");

      $stmt->bindValue(":name", '%'.$name.'%');

      $stmt->execute();

      if($stmt->rowCount() > 0) {

        $petsArray = $stmt->fetchAll();

        foreach($petsArray as $Pet) {
          $Pets[] = $this->buildPet($Pet);
        }

      }

      return $Pets;

    }

    public function create(Pet $Pet) {

      $stmt = $this->conn->prepare("INSERT INTO pets (
        name, description, image, sex, pet, size, users_id
      ) VALUES (
        :name, :description, :image, :sex, :pet, :size, :users_id
      )");

      $stmt->bindParam(":name", $Pet->name);
      $stmt->bindParam(":description", $Pet->description);
      $stmt->bindParam(":image", $Pet->image);
      $stmt->bindParam(":sex", $Pet->sex);
      $stmt->bindParam(":pet", $Pet->pet);
      $stmt->bindParam(":size", $Pet->size);
      $stmt->bindParam(":users_id", $Pet->users_id);

      $stmt->execute();

      // Mensagem de sucesso por adicionar Pet
      $this->message->setMessage("Pet adicionado com sucesso!", "success", "index.php");

    }

    public function update(Pet $Pet) {

      $stmt = $this->conn->prepare("UPDATE pets SET
        name = :name,
        description = :description,
        image = :image,
        pet = :pet,
        sex = :sex,
        size = :size
        WHERE id = :id      
      ");

      $stmt->bindParam(":name", $Pet->name);
      $stmt->bindParam(":description", $Pet->description);
      $stmt->bindParam(":image", $Pet->image);
      $stmt->bindParam(":pet", $Pet->pet);
      $stmt->bindParam(":sex", $Pet->sex);
      $stmt->bindParam(":size", $Pet->size);
      $stmt->bindParam(":id", $Pet->id);

      $stmt->execute();

      // Mensagem de sucesso por editar cadastro do Pet
      $this->message->setMessage("cadastro do Pet atualizado com sucesso!", "success", "dashboard.php");

    }

    public function destroy($id) {
      // Verifica se há comentários associados ao pet
      $commentDao = new CommentDao($this->conn, $this->url);
      $comments = $commentDao->getPetComment($id);
      
      if (!empty($comments)) {
          // Exclui os comentários associados ao pet
          foreach ($comments as $comment) {
              $commentDao->destroy($comment->id);
          }
      }
      
      // Agora excluimos o pet
      $stmt = $this->conn->prepare("DELETE FROM pets WHERE id = :id");
      $stmt->bindParam(":id", $id);
      $stmt->execute();
  
      // Mensagem de sucesso por remover pet
      $this->message->setMessage("Pet removido com sucesso!", "success", "dashboard.php");
  }

}