<?php
require_once 'models/Transaction.php';
require_once 'models/Bike.php';

class RentalController {
    public function startRental() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['bike_id'])) {
            $bike_id = $_POST['bike_id'];
            $user_id = $_SESSION['user_id'];
            
            // Ensure no active rental
            $trx = new Transaction();
            $active = $trx->readActiveByUserId($user_id)->fetch(PDO::FETCH_ASSOC);
            if ($active) {
                return "Anda masih memiliki penyewaan yang sedang berjalan.";
            }

            // Check if bike is available
            $bike = new Bike();
            if (!$bike->readById($bike_id) || $bike->status != 'Tersedia') {
                return "Sepeda tidak tersedia.";
            }

            // Start Transaction
            $trx->user_id = $user_id;
            $trx->sepeda_id = $bike_id;
            $trx->waktu_mulai = date('Y-m-d H:i:s');
            $trx->status_pembayaran = 'Belum Bayar'; // initial state
            $trx->status_sewa = 'Berjalan';
            
            if ($trx->create()) {
                // Change bike status
                $bike->updateStatus($bike_id, 'Dipinjam');

                // Redirect to payment simulation or dashboard
                // In this flow, they scan -> start -> dashboard -> then end -> payment
                // Or scan -> pay -> start. Let's do: Scan -> Review -> Pay (simulate) -> Start
                header("Location: index.php?page=payment&trx_id=" . $trx->id);
                exit;
            } else {
                return "Gagal memulai penyewaan.";
            }
        }
        return null;
    }

    public function endRental() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['transaction_id'])) {
            $trx_id = $_POST['transaction_id'];
            $trx = new Transaction();
            $transaction = $trx->readById($trx_id);

            if ($transaction && $transaction['status_sewa'] == 'Berjalan' && $transaction['user_id'] == $_SESSION['user_id']) {
                $waktu_selesai = date('Y-m-d H:i:s');
                $mulai = strtotime($transaction['waktu_mulai']);
                $selesai = strtotime($waktu_selesai);
                
                $diff_hours = ceil(($selesai - $mulai) / 3600); // round up to nearest hour
                if ($diff_hours < 1) $diff_hours = 1; // minimum 1 hour charge
                
                $total_biaya = $diff_hours * $transaction['tarif_per_jam'];

                $trx->id = $trx_id;
                $trx->waktu_selesai = $waktu_selesai;
                $trx->total_biaya = $total_biaya;
                $trx->status_sewa = 'Selesai';
                
                if ($trx->endTransaction()) {
                    // Redirect to payment page
                    header("Location: index.php?page=payment&trx_id=" . $trx_id);
                    exit;
                }
            }
            return "Gagal mengakhiri penyewaan.";
        }
        return null;
    }
    
    public function pay() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['transaction_id'])) {
            $trx_id = $_POST['transaction_id'];
            $trx = new Transaction();
            $transaction = $trx->readById($trx_id);

            if ($transaction && $transaction['user_id'] == $_SESSION['user_id']) {
                $db = new Database();
                $conn = $db->getConnection();
                
                // Update transaction status
                $query = "UPDATE transaksi SET status_pembayaran = 'Lunas' WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->execute([$trx_id]);
                
                // Update bike status to Tersedia
                $bike = new Bike();
                $bike->updateStatus($transaction['sepeda_id'], 'Tersedia');
                
                header("Location: index.php?page=history&msg=payment_success");
                exit;
            }
        }
    }
}
?>
