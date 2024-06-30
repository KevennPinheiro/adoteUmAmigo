<?php
  require_once("templates/header.php");
  require_once("models/Pet.php");
  require_once("dao/PetDAO.php");
  require_once("dao/CommentDAO.php");
  require_once("models/User.php");
  require_once("dao/UserDAO.php");
 
 
  // Pegar o id do pet
  $id = filter_input(INPUT_GET, "id");
 
  $petDao = new PetDAO($conn, $BASE_URL);
  $userDao = new UserDAO($conn, $BASE_URL);
  $commentDao = new CommentDAO($conn, $BASE_URL);
 
 
 
  if(empty($id)) {
 
    $message->setMessage("O pet não foi encontrado!", "error", "index.php");
 
  } else {
 
    $Pet = $petDao->findById($id);
    $petOwner = $userDao->findById($Pet->users_id);
 
    // Verifica se o pet existe
    if(!$Pet) {
 
      $message->setMessage("O pet não foi encontrado!", "error", "index.php");
 
    }
 
  }
 
  // Checar se o pet tem foto
  if($Pet->image == "") {
    $Pet->image = "pet_cover.jpg";
  }
 
  // Checar se o pet é do usuário
  $userOwnsPet = false;
 
  $petOwnerWhatsApp = "";
  $petOwnerLocation = $petOwner->cidade;
  $petOwnerUf = $petOwner->estado;
 
  if(!empty($userData)) {
    $petOwnerWhatsApp = $petOwner->whatsapp;
    
    if($userData->id === $Pet->users_id) {
      $userOwnsPet = true;
      
    }
 
    // Resgatar os comentários do pet
    $alreadyCommented = $commentDao->hasAlreadyCommented($id, $userData->id);
 
  }
 
  // Resgatar os comentários do pet
  $petComments = $commentDao->getPetComment($Pet->id);
 
?>
<div id="main-container" class="container-fluid">
  <div class="row">
    <div class="offset-md-1 col-md-6 pet-container">
      <h1 class="page-title"><?= $Pet->name ?></h1>
      <p class="pet-details">
        <span><?= $Pet->pet ?></span>
        <span class="pipe"></span>
        <span><?= $Pet->sex ?></span>
        <span class="pipe"></span>
        <span><?= $Pet->size ?></span>
        <span class="pipe"></span>
        <b><?= $petOwnerLocation ?>-<?= $petOwnerUf ?></b>
      </p>
      <?php if (($userData !== null) && ( $userData->isAdmin === 1)): ?>
       <form action="<?= $BASE_URL ?>pet_process.php" method="POST">
            <input type="hidden" name="type" value="delete">
            <input type="hidden" name="id" value="<?= $Pet->id ?>">
            <button type="submit" class="delete-btn">
                <i class="fas fa-times"></i> Deletar
            </button>
       </form>
      <?php endif; ?>        
      <p><?= $Pet->description ?></p> 
    </div>
    <div class="col-md-4">
      <div class="pet-image-container" style="background-image: url('<?= $BASE_URL ?>img/pets/<?= $Pet->image ?>')"></div>
      <?php if (!empty($userData)): ?>
        <span id="desc-contact">Para adotar entre em contato com o protetor: </span>
        <i class="fab fa-whatsapp" id="whats"></i>
        <span id="number-contact"><?= $petOwnerWhatsApp ?></span>
      <?php else: ?>
        <span id="desc-contact">Faça o <a href="<?= $BASE_URL ?>auth.php">LOGIN</a> para entrar em contato com o protetor</span>
      <?php endif; ?>
    </div>
    <div class="offset-md-1 col-md-10" id="comments-container">
      <h3 id="comments-title">Comentários:</h3>
      <!-- Verifica se habilita o comentario para o usuário ou não -->
      <?php if(!empty($userData) && !$userOwnsPet && !$alreadyCommented): ?>
      <div class="col-md-12" id="comment-form-container">
        <h4>Envie seu comentário:</h4>
        <p class="page-description">Preencha o formulário com o comentário sobre o pet</p>
        <form action="<?= $BASE_URL ?>comment_process.php" id="comment-form" method="POST">
          <input type="hidden" name="type" value="create">
          <input type="hidden" name="pets_id" value="<?= $Pet->id ?>">
          <div class="form-group">
            <label for="comment">Seu comentário:</label>
            <textarea name="comment" id="comment" rows="3" class="form-control" placeholder="Nos conte o que você quer saber sobre o Pet"></textarea>
          </div>
          <input type="submit" class="btn card-btn" value="Enviar comentário">
        </form>
      </div>
      <?php endif; ?>
      <!-- Comentários -->
      <?php foreach($petComments as $comment): ?>
        <?php require("templates/user_comment.php"); ?>
      <?php endforeach; ?>
      <?php if(count($petComments) == 0): ?>
        <p class="empty-list">Não há comentários para este pet ainda...</p>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php
  require_once("templates/footer.php");
?>