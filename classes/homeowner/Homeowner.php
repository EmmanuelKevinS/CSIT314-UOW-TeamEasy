<?php
class Homeowner {
    private $user_id;
    private $name;
    private $phone;
    private $address;

    public function __construct($user_id, $name, $phone, $address) {
        $this->user_id = $user_id;
        $this->name = $name;
        $this->phone = $phone;
        $this->address = $address;
    }

    public function getUserId() { return $this->user_id; }
    public function getName() { return $this->name; }
    public function getPhone() { return $this->phone; }
    public function getAddress() { return $this->address; }
}
?>