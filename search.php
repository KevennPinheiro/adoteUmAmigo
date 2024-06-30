<?php
  require_once("templates/header.php");

  require_once("dao/PetDAO.php");

  // DAO dos Pets
  $petDao = new PetDAO($conn, $BASE_URL);

  // Resgata busca do usuário
  $q = filter_input(INPUT_GET, "q");

  $pets = $petDao->findByName($q);

?>
  <div id="main-container" class="container-fluid">
    <h2 class="section-title" id="search-title">Você está buscando por: <span id="search-result"><?= $q ?></span></h2>
    <p class="section-description">Resultados de busca retornados com base na sua pesquisa.</p>
    <div class="pets-container">
      <?php foreach($pets as $Pet): ?>
        <?php require("templates/pet_card.php"); ?>
      <?php endforeach; ?>
      <?php if(count($pets) === 0): ?>
        <p class="empty-list">Não há pets para esta busca, <a href="<?= $BASE_URL ?>" class="back-link">voltar</a>.</p>
      <?php endif; ?>
    </div>
  </div>
<?php
  require_once("templates/footer.php");
?>