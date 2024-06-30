<?php
  require_once("templates/header.php");

  // Verifica se usuário está autenticado
  require_once("models/User.php");
  require_once("dao/UserDAO.php");

  $user = new User();
  $userDao = new UserDao($conn, $BASE_URL);

  $userData = $userDao->verifyToken(true); 
?>
  <div id="main-container" class="container-fluid">
    <div class="offset-md-4 col-md-4 new-pet-container">
      <h1 class="page-title">Adicionar Pet</h1>
      <p class="page-description">Adicione o pet e encontre um lar para ele!</p>
      <form action="<?= $BASE_URL ?>pet_process.php" id="add-pet-form" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="type" value="create">
        <div class="form-group">
          <label for="name">Nome:</label>
          <input type="text" class="form-control" id="name" name="name" placeholder="Digite o nome do pet">
        </div>
        <div class="form-group">
          <label for="image">Foto:</label>
          <input type="file" class="form-control-file" name="image" id="image">
        </div>
        <div class="form-group">
          <label for="pet">Pet:</label>
          <select name="pet" id="pet" class="form-control">
            <option value="">Selecione</option>
            <option value="Cachorro">Cachorro</option>
            <option value="Gato">Gato</option>
          </select>
        </div>  
          <div class="form-group">
          <label for="sex">Sexo:</label>
          <select name="sex" id="sex" class="form-control">
            <option value="">Selecione</option>
            <option value="Macho">Macho</option>
            <option value="Fêmea">Fêmea</option>
          </select>
        </div>
        <div class="form-group">
          <label for="size">Porte:</label>
          <select name="size" id="size" class="form-control">
            <option value="">Selecione</option>
            <option value="Pequeno">Pequeno</option>
            <option value="Médio">Médio</option>
            <option value="Grande">Grande</option>
          </select>
        </div>
        <div class="form-group">
          <label for="description">Descrição:</label>
          <textarea name="description" id="description" rows="5" class="form-control" placeholder="Fale um pouco sobre esse pet..."></textarea>
        </div>
        <input type="submit" class="btn card-btn" value="Adicionar pet">
      </form>
    </div>
  </div>
<?php
  require_once("templates/footer.php");
?>