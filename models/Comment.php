<?php

  class Comment {

    public $id;
    public $comment;
    public $users_id;
    public $pets_id;

  }

  interface CommentDAOInterface {

    public function buildComment($data);
    public function create(Comment $comment);
    public function getPetComment($id);
    public function hasAlreadyCommented($id, $userId);
    public function destroy($id);
  }