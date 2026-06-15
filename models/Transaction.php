<?php
require_once 'config/database.php';

class Transaction {
    private $conn;
    private $table_name = "transaksi";

    public $id;
    public $user_id;
    public $sepeda_id;
    public $waktu_mulai;
    public $waktu_selesai;
    public $total_biaya;
    public $status_pembayaran;
    public $status_sewa;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET user_id=:user_id, sepeda_id=:sepeda_id, waktu_mulai=:waktu_mulai, status_pembayaran=:status_pembayaran, status_sewa=:status_sewa";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":sepeda_id", $this->sepeda_id);
        $stmt->bindParam(":waktu_mulai", $this->waktu_mulai);
        $stmt->bindParam(":status_pembayaran", $this->status_pembayaran);
        $stmt->bindParam(":status_sewa", $this->status_sewa);

        if($stmt->execute()){
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    public function endTransaction() {
        $query = "UPDATE " . $this->table_name . " SET waktu_selesai=:waktu_selesai, total_biaya=:total_biaya, status_sewa=:status_sewa WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":waktu_selesai", $this->waktu_selesai);
        $stmt->bindParam(":total_biaya", $this->total_biaya);
        $stmt->bindParam(":status_sewa", $this->status_sewa);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()){
            return true;
        }
        return false;
    }

    public function readByUserId($user_id) {
        $query = "SELECT t.*, s.kode_sepeda, s.merk FROM " . $this->table_name . " t LEFT JOIN sepeda s ON t.sepeda_id = s.id WHERE t.user_id = ? ORDER BY t.id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->execute();
        return $stmt;
    }

    public function readActiveByUserId($user_id) {
        $query = "SELECT t.*, s.kode_sepeda, s.merk, s.tarif_per_jam FROM " . $this->table_name . " t LEFT JOIN sepeda s ON t.sepeda_id = s.id WHERE t.user_id = ? AND t.status_sewa = 'Berjalan' LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->execute();
        return $stmt;
    }

    public function readAll() {
        $query = "SELECT t.*, u.nim, u.name as user_name, s.kode_sepeda FROM " . $this->table_name . " t LEFT JOIN users u ON t.user_id = u.id LEFT JOIN sepeda s ON t.sepeda_id = s.id ORDER BY t.id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readById($id) {
        $query = "SELECT t.*, s.tarif_per_jam FROM " . $this->table_name . " t LEFT JOIN sepeda s ON t.sepeda_id = s.id WHERE t.id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row){
            $this->id = $row['id'];
            $this->user_id = $row['user_id'];
            $this->sepeda_id = $row['sepeda_id'];
            $this->waktu_mulai = $row['waktu_mulai'];
            $this->waktu_selesai = $row['waktu_selesai'];
            $this->total_biaya = $row['total_biaya'];
            $this->status_pembayaran = $row['status_pembayaran'];
            $this->status_sewa = $row['status_sewa'];
            return $row;
        }
        return false;
    }
}
?>
