<?php
  require_once("templates/header.php");

  // Verifica se usuário está autenticado
  require_once("models/User.php");
  require_once("dao/UserDAO.php");
  require_once("dao/PetDAO.php");

  $user = new User();
  $userDao = new UserDao($conn, $BASE_URL);
  $petDao = new PetDAO($conn, $BASE_URL);

  $userData = $userDao->verifyToken(true);

  $userPets = $petDao->getPetsByUserId($userData->id);

?>
  <div id="main-container" class="container-fluid">
    <h2 class="section-title">Dashboard</h2>
    <p class="section-description">Adicione ou atualize as informações dos pets que você enviou</p>
    <div class="col-md-12" id="add-pet-container">
      <a href="<?= $BASE_URL ?>newpet.php" class="btn card-btn">
        <i class="fas fa-plus"></i> Adicionar Pet
      </a>
    </div>
    <div class="col-md-12" id="pets-dashboard">
      <table class="table">
        <thead>
          <th scope="col">#</th>
          <th scope="col">Nome</th>
          <th scope="col">Sexo</th>
          <th scope="col">Porte</th>
          <th scope="col" class="actions-column">Ações</th>
        </thead>
        <tbody>
          <?php foreach($userPets as $Pet): ?>
          <tr>
            <td scope="row"><?= $Pet->id ?></td>
            <td><a href="<?= $BASE_URL ?>pet.php?id=<?= $Pet->id ?>" class="table-pet-title"><?= $Pet->name ?></a></td>
            <td><?= $Pet->sex ?></td>
            <td><?= $Pet->size ?></td>
            <td class="actions-column">
              <a href="<?= $BASE_URL ?>editpet.php?id=<?= $Pet->id ?>" class="edit-btn">
                <i class="far fa-edit"></i> Editar
              </a>
              <form action="<?= $BASE_URL ?>pet_process.php" method="POST">
                <input type="hidden" name="type" value="delete">
                <input type="hidden" name="id" value="<?= $Pet->id ?>">
                <button type="submit" class="delete-btn">
                  <i class="fas fa-times"></i> Deletar
                </button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
<?php
  require_once("templates/footer.php");
?>