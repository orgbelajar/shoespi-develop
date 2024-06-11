<?php
include 'components/connect.php';

session_start();

//*Kode sebelumnya
// if(isset($_SESSION['user_id'])){
//    $user_id = $_SESSION['user_id'];
// }else{
//    $user_id = '';
// };

$user_id = $_SESSION['user_id'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Pesanan</title>
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
</head>

<body>
   <?php include 'components/user_header.php'; ?>

   <section class="orders">
      <h1 class="heading">Daftar Pembelian</h1>
      <div class="box-container">

         <?php
         if (empty($user_id)) {
            echo '<p class="empty">silahkan login untuk melihat pesanan anda</p><br><br><br><br><br><br><br><br>';
         } else {
            $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = :user_id"); //ambil data berdasarkan user_id
            $select_orders->execute([':user_id' => $user_id]);
            if ($select_orders->rowCount() > 0) { //cek jumlah pesanan
               while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) { //loop berjalan selama data pesanan masih ada pada user_id tersebut
         ?>
                  <div class="box">
                     <p><span class="label">Tanggal Pembelian</span><span class="titik">:</span><span><?= htmlspecialchars($fetch_orders['placed_on']); ?></span></p>
                     <p><span class="label">Nama Pembeli</span><span class="titik">:</span><span><?= htmlspecialchars($fetch_orders['name']); ?></span></p>
                     <p><span class="label">Email</span><span class="titik">:</span><span><?= htmlspecialchars($fetch_orders['email']); ?></span></p>
                     <p><span class="label">Nomor Hp</span><span class="titik">:</span><span><?= htmlspecialchars($fetch_orders['number']); ?></span></p>
                     <p><span class="label">Alamat</span><span class="titik">:</span><span><?= htmlspecialchars($fetch_orders['address']); ?></span></p>
                     <p><span class="label">Metode Pembayaran</span><span class="titik">:</span><span><?= htmlspecialchars($fetch_orders['method']); ?></span></p>
                     <p><span class="label">Produk</span><span class="titik">:</span><span><?= htmlspecialchars($fetch_orders['total_products']); ?></span></p>
                     <p><span class="label">Total Harga</span><span class="titik">:</span><span class="total-price">IDR <?= number_format($fetch_orders['total_price'], 0, ',', '.'); ?></span></p>
                     <p><span class="label">Status pembayaran</span><span class="titik">:</span><span class="status-payment" style="color:<?= ($fetch_orders['payment_status'] === 'pending') ? 'red' : 'green'; ?>"><?= htmlspecialchars($fetch_orders['payment_status']); ?></span></p>
                  </div>
         <?php
               }
            } else { //Jika tidak ada pesanan 
               echo '<p class="empty">belum ada pesanan!</p><br><br><br><br><br><br>';
            }
         }
         ?>
      </div>
   </section>

   <?php include 'components/footer.php'; ?>

   <script src="js/script.js"></script>
</body>

</html>