<?php
require_once __DIR__ . '/../config/database.php';

class Bike {
    private $conn;
    private $table_name = "sepeda";

    public $id;
    public $kode_sepeda;
    public $merk;
    public $tarif_per_jam;
    public $status;
    public $image;
    public $qr_code_url;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function readAll() {
        $query = "SELECT id, kode_sepeda, merk, tarif_per_jam, status, image, qr_code_url FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readAvailable() {
        $query = "SELECT id, kode_sepeda, merk, tarif_per_jam, status, image, qr_code_url FROM " . $this->table_name . " WHERE status = 'Tersedia' ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readById($id) {
        $query = "SELECT id, kode_sepeda, merk, tarif_per_jam, status, image, qr_code_url FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row) {
            $this->kode_sepeda = $row['kode_sepeda'];
            $this->merk = $row['merk'];
            $this->tarif_per_jam = $row['tarif_per_jam'];
            $this->status = $row['status'];
            $this->image = $row['image'];
            $this->qr_code_url = $row['qr_code_url'];
            return true;
        }
        return false;
    }
    
    public function readByKode($kode) {
        $query = "SELECT id, kode_sepeda, merk, tarif_per_jam, status, image, qr_code_url FROM " . $this->table_name . " WHERE kode_sepeda = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $kode);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row) {
            $this->id = $row['id'];
            $this->kode_sepeda = $row['kode_sepeda'];
            $this->merk = $row['merk'];
            $this->tarif_per_jam = $row['tarif_per_jam'];
            $this->status = $row['status'];
            $this->image = $row['image'];
            $this->qr_code_url = $row['qr_code_url'];
            return true;
        }
        return false;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET kode_sepeda=:kode, merk=:merk, tarif_per_jam=:tarif, status=:status, image=:image";
        $stmt = $this->conn->prepare($query);

        $this->kode_sepeda=htmlspecialchars(strip_tags($this->kode_sepeda));
        $this->merk=htmlspecialchars(strip_tags($this->merk));
        $this->tarif_per_jam=htmlspecialchars(strip_tags($this->tarif_per_jam));
        $this->status=htmlspecialchars(strip_tags($this->status));
        if (empty($this->image)) $this->image = 'default_bike.png';
        $this->image=htmlspecialchars(strip_tags($this->image));

        $stmt->bindParam(":kode", $this->kode_sepeda);
        $stmt->bindParam(":merk", $this->merk);
        $stmt->bindParam(":tarif", $this->tarif_per_jam);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":image", $this->image);

        if($stmt->execute()){
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET kode_sepeda=:kode, merk=:merk, tarif_per_jam=:tarif, status=:status, image=:image WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->kode_sepeda=htmlspecialchars(strip_tags($this->kode_sepeda));
        $this->merk=htmlspecialchars(strip_tags($this->merk));
        $this->tarif_per_jam=htmlspecialchars(strip_tags($this->tarif_per_jam));
        $this->status=htmlspecialchars(strip_tags($this->status));
        $this->image=htmlspecialchars(strip_tags($this->image));
        $this->id=htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":kode", $this->kode_sepeda);
        $stmt->bindParam(":merk", $this->merk);
        $stmt->bindParam(":tarif", $this->tarif_per_jam);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":image", $this->image);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()){
            return true;
        }
        return false;
    }

    public function updateStatus($id, $new_status) {
        $query = "UPDATE " . $this->table_name . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $new_status);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        if($stmt->execute()){
            return true;
        }
        return false;
    }
}
?>
//