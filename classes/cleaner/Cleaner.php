<?php
class Cleaner {
    private $user_id;
    private $name;
    private $experience;
    private $price;

    public function __construct($user_id, $name, $experience, $price) {
        $this->user_id = $user_id;
        $this->name = $name;
        $this->experience = $experience;
        $this->price = $price;
    }

    public function getUserId() { return $this->user_id; }
    public function getName() { return $this->name; }
    public function getExperience() { return $this->experience; }
    public function getPrice() { return $this->price; }

    public function setName($name) { $this->name = $name; }
    public function setExperience($experience) { $this->experience = $experience; }
    public function setPrice($price) { $this->price = $price; }
}
?>