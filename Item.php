<?php
class Item {
    private $conn;
    private $table_name = "items";

    public $id;
    public $name;
    public $image;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Metode untuk membuat item
    function create() {
        $query = "INSERT INTO " . $this->table_name . " SET name=?, image=?";
        $stmt = $this->conn->prepare($query);
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->image = htmlspecialchars(strip_tags($this->image));
        $stmt->bind_param("ss", $this->name, $this->image);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Metode untuk membaca semua item
    function read() {
        $query = "SELECT id, name, image FROM " . $this->table_name;
        $result = $this->conn->query($query);
        return $result;
    }

    // Metode untuk membaca satu item berdasarkan ID
    function readOne() {
        $query = "SELECT id, name, image FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        return $stmt;
    }

    // Metode untuk mengupdate item
    function update() {
        $query = "UPDATE " . $this->table_name . " SET name=?, image=? WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->image = htmlspecialchars(strip_tags($this->image));
        $stmt->bind_param("ssi", $this->name, $this->image, $this->id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Metode untuk menghapus item
    function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
