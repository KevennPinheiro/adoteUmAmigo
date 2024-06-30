<?php

    require_once("models/User.php");

    $userModel = new User();

    $fullName = $userModel->getFullName($comment->user);

    
    if($comment->user->image == "") {
      $comment->user->image = "user.png";
    }

?>
<div class="col-md-12 comment">
  <div class="row">
    <div class="col-md-1">
      <div class="profile-image-container comment-image" style="background-image: url('<?= $BASE_URL ?>img/users/<?= $comment->user->image ?>')"></div>
    </div>
    <div class="col-md-9 author-details-container">
      <h4 class="author-name">
        <a href="<?= $BASE_URL ?>profile.php?id=<?= $comment->user->id ?>"><?= $fullName ?></a>
      </h4>
    </div>
    <div class="col-md-12">
      <p class="comment-title">Comentário:</p>
      <p><?= $comment->comment ?></p>
    </div>
  </div>
</div>