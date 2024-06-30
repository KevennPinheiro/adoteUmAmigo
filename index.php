<?php
  require_once("templates/header.php");

  require_once("dao/PetDAO.php");

  // DAO do Pet
  $petDao = new PetDAO($conn, $BASE_URL);

  $latestPets = $petDao->getLatestPets();

  $dogPets = $petDao->getPetsByPet("Cachorro");

  $catPets = $petDao->getPetsByPet("Gato");

?>
  <div id="main-container" class="container-fluid">
    <h2 class="section-title">Pets</h2>
    <p class="section-description">Adote, não compre: salve vidas!</p>
    <div class="pets-container">
      <?php foreach($latestPets as $Pet): ?>
        <?php require("templates/pet_card.php"); ?>
      <?php endforeach; ?>
      <?php if(count($latestPets) === 0): ?>
        <p class="empty-list">Ainda não há pets cadastrados!</p>
      <?php endif; ?>
    </div>
    <h2 class="section-title">Cães</h2>
    <div class="pets-container">
      <?php foreach($dogPets as $Pet): ?>
        <?php require("templates/pet_card.php"); ?>
      <?php endforeach; ?>
      <?php if(count($dogPets) === 0): ?>
        <p class="empty-list">Ainda não há cães cadastrados!</p>
      <?php endif; ?>
    </div>
    <h2 class="section-title">Gatos</h2>
    <div class="pets-container">
      <?php foreach($catPets as $Pet): ?>
        <?php require("templates/pet_card.php"); ?>
      <?php endforeach; ?>
      <?php if(count($catPets) === 0): ?>
        <p class="empty-list">Ainda não há gatos cadastrados!</p>
      <?php endif; ?>
    </div>
  </div>
<?php
  require_once("templates/footer.php");
?>