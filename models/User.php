<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $nim;
    public $name;
    public $email;
    public $phone;
    public $password;
    public $role;
    public $saldo;
    public $foto_profil;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET nim=:nim, name=:name, email=:email, phone=:phone, password=:password, role=:role, saldo=0";
        $stmt = $this->conn->prepare($query);

        $this->nim=htmlspecialchars(strip_tags($this->nim));
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->phone=htmlspecialchars(strip_tags($this->phone));
        $this->password=password_hash($this->password, PASSWORD_BCRYPT);
        
        $role = $this->role ? $this->role : 'user';

        $stmt->bindParam(":nim", $this->nim);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":role", $role);

        if($stmt->execute()){
            return true;
        }
        return false;
    }

    public function readByNim($nim) {
        $query = "SELECT id, nim, name, email, phone, password, role, saldo, foto_profil FROM " . $this->table_name . " WHERE nim = ? OR email = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $nim);
        $stmt->bindParam(2, $nim);
        $stmt->execute();
        return $stmt;
    }
    
    public function readAll() {
        $query = "SELECT id, nim, name, email, phone, role, saldo, foto_profil FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readById($id) {
        $query = "SELECT id, nim, name, email, phone, role, saldo, foto_profil FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->id = $row['id'];
            $this->nim = $row['nim'];
            $this->name = $row['name'];
            $this->email = $row['email'];
            $this->phone = $row['phone'];
            $this->role = $row['role'];
            $this->saldo = $row['saldo'];
            $this->foto_profil = $row['foto_profil'];
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET name=:name, email=:email, phone=:phone WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->phone=htmlspecialchars(strip_tags($this->phone));
        $this->id=htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()){
            return true;
        }
        return false;
    }

    public function updateSaldo($id, $amount) {
        $query = "UPDATE " . $this->table_name . " SET saldo = saldo + :amount WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':amount', $amount, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deductSaldo($id, $amount) {
        $query = "UPDATE " . $this->table_name . " SET saldo = saldo - :amount WHERE id = :id AND saldo >= :check";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':amount', $amount, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':check', $amount, PDO::PARAM_INT);
        return $stmt->execute() && $stmt->rowCount() > 0;
    }

    public function getSaldo($id) {
        $query = "SELECT saldo FROM " . $this->table_name . " WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['saldo'] : 0;
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

    public function updateProfilePhoto($id, $photoPath) {
        $query = "UPDATE " . $this->table_name . " SET foto_profil = :foto_profil WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':foto_profil', $photoPath);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>
//