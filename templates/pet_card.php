<?php

  if(empty($Pet->image)) {
    $Pet->image = "pet_cover.jpg";
  }

?>
<div class="card pet-card">
  <div class="card-img-top" style="background-image: url('<?= $BASE_URL ?>img/pets/<?= $Pet->image ?>')"></div>
  <div class="card-body">
    <p class="card-gender">
      <span class="gender"><?= $Pet->sex ?></span>
    </p>
    <h5 class="card-title">
      <a href="<?= $BASE_URL ?>pet.php?id=<?= $Pet->id ?>"><?= $Pet->name ?></a>
    </h5>
    <a href="<?= $BASE_URL ?>pet.php?id=<?= $Pet->id ?>" class="btn btn-primary card-btn"> <i class="fas fa-plus"></i> INFORMAÇÕES</a>
  </div>
</div>