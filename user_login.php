<?php

include 'components/connect.php';

session_start();

$user_id = $_SESSION['user_id'] ?? '';

if (isset($_POST['submit'])) {
   $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
   $pass = $_POST['pass'];

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = :email");
   $select_user->execute([':email' => $email]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if (!$row) {
      // Kondisi ketika username tidak terdaftar
      $message[] = 'Username tidak terdaftar';
   } elseif (password_verify($pass, $row['password'])) {
      // Kondisi ketika password cocok
      $_SESSION['user_id'] = $row['id'];
      header('Location: home.php');
      //memastikan bahwa tidak ada bagian kode selanjutnya yang dieksekusi setelah pengalihan.
      exit;
   } else {
      // Kondisi ketika password tidak cocok
      $message[] = 'Password salah!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'components/user_header.php'; ?>

   <section class="form-container">

      <form action="" method="post">
         <h3>login sekarang</h3>
         <input type="email" name="email" required placeholder="masukkan email Anda" maxlength="50" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="pass" required placeholder="masukkan password Anda" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="submit" value="login now" class="btn" name="submit">
         <p>tidak memiliki akun?</p>
         <a href="user_register.php" class="option-btn">Daftar sekarang</a>
      </form>

   </section>
   <?php include 'components/footer.php'; ?>

   <script src="js/script.js"></script>

</body>

</html>