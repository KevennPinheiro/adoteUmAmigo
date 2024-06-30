<?php

  class Pet {

    public $id;
    public $name;
    public $description;
    public $image;
    public $pet;
    public $sex;
    public $users_id;

    public function imageGenerateName() {
      return bin2hex(random_bytes(60)) . ".jpg";
    }

  }

  interface PetDAOInterface {

    public function buildPet($data);
    public function findAll();
    public function getLatestPets();
    public function getPetsByPet($pet);
    public function getPetsByUserId($id);
    public function findById($id);
    public function findByName($name);
    public function create(Pet $Pet);
    public function update(Pet $Pet);
    public function destroy($id);

  }