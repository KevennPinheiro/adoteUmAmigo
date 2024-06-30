<?php
  require_once("templates/header.php");

  // Verifica se usuário está autenticado
  require_once("models/User.php");
  require_once("dao/UserDAO.php");
  require_once("dao/PetDAO.php");

  $user = new User();
  $userDao = new UserDao($conn, $BASE_URL);

  $userData = $userDao->verifyToken(true);

  $petDao = new PetDAO($conn, $BASE_URL);

  $id = filter_input(INPUT_GET, "id");

  if(empty($id)) {

    $message->setMessage("O pet não foi encontrado!", "error", "index.php");

  } else {

    $Pet = $petDao->findById($id);

    // Verifica se o pet existe
    if(!$Pet) {

      $message->setMessage("O pet não foi encontrado!", "error", "index.php");

    }

  }

  // Checar se o pet tem foto
  if($Pet->image == "") {
    $Pet->image = "pet_cover.jpg";
  }

?>
  <div id="main-container" class="container-fluid">
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-6 offset-md-1">
          <h1><?= $Pet->name ?></h1>
          <p class="page-description">Altere os dados do pet no formulário abaixo:</p>
          <form id="edit-pet-form" action="<?= $BASE_URL ?>pet_process.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="type" value="update">
            <input type="hidden" name="id" value="<?= $Pet->id ?>">
            <div class="form-group">
              <label for="name">Nome:</label>
              <input type="text" class="form-control" id="name" name="name" placeholder="Digite o nome do Pet" value="<?= $Pet->name ?>">
            </div>
            <div class="form-group">
              <label for="image">Foto:</label>
              <input type="file" class="form-control-file" name="image" id="image">
            </div>
            <div class="form-group">
              <label for="pet">Tipo:</label>
              <select name="pet" id="pet" class="form-control">
                <option value="">Selecione</option>
                <option value="Cachorro" <?= $Pet->pet === "Cachorro" ? "selected" : "" ?>>Cachorro</option>
                <option value="Gato" <?= $Pet->pet === "Gato" ? "selected" : "" ?>>Gato</option>
              </select>
            </div>
            <div class="form-group">
              <label for="sex">Sexo:</label>
              <select name="sex" id="sex" class="form-control">
                <option value="">Selecione</option>
                <option value="Macho" <?= $Pet->sex === "Macho" ? "selected" : "" ?>>Macho</option>
                <option value="Fêmea" <?= $Pet->sex === "Fêmea" ? "selected" : "" ?>>Fêmea</option>
              </select>
            </div>
            <div class="form-group">
              <label for="size">Porte:</label>
              <select name="size" id="size" class="form-control">
                <option value="">Selecione</option>
                <option value="Pequeno" <?= $Pet->size === "Pequeno" ? "selected" : "" ?>>Pequeno</option>
                <option value="Médio" <?= $Pet->size === "Médio" ? "selected" : "" ?>>Médio</option>
                <option value="Grande" <?= $Pet->size === "Grande" ? "selected" : "" ?>>Grande</option>
              </select>
            </div>
            <div class="form-group">
              <label for="description">Descrição:</label>
              <textarea name="description" id="description" rows="5" class="form-control" placeholder="Fale um pouco sobre esse pet..."><?= $Pet->description ?></textarea>
            </div>
            <input type="submit" class="btn card-btn" value="Editar">
          </form>
        </div>
        <div class="col-md-3">
          <div class="pet-image-container" style="background-image: url('<?= $BASE_URL ?>img/pets/<?= $Pet->image ?>')"></div>
        </div>
      </div>
    </div>
  </div>
<?php
  require_once("templates/footer.php");
?>
