<?php

//* Conncet to Database
include 'components/connect.php';

session_start();

//* Cek User yg sudah Login
$user_id = $_SESSION['user_id'] ?? '';

/*
//* Proses Form Registrasi User Sebelumnya
if (isset($_POST['submit'])) {

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_EMAIL);
   $pass = sha1($_POST['pass']); //hash password memakai sh1 sblm di save ke db
   $pass = filter_var($pass, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $cpass = sha1($_POST['cpass']); //confirm password
   $cpass = filter_var($cpass, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

   //* Menyiapkan Query SQL
   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?"); //'?' Placeholder for prevent SQL injection
   //* Menjalankan $select_user 
   //$email di bind ke placholder '?' dalam query SQL
   $select_user->execute([$email,]);
   //* Mengambil Hasil Query $select_user
   $row = $select_user->fetch(PDO::FETCH_ASSOC);
*/

//* Proses Form Registrasi User
if (isset($_POST['submit'])) {
   $name = filter_var($_POST['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
   $pass = $_POST['pass'];
   $cpass = $_POST['cpass'];

   //* Menyiapkan Query SQL
   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = :email");
   //* Menjalankan $select_user 
   $select_user->execute([':email' => $email]);

   //* Cek Email yg sudah terdaftar
   //mengambil bersarkan jmlh baris dari db, 
   //jika jmlh baris > 0 berarti ada user dgn email yg sama sudah terdaftar ke db
   if ($select_user->rowCount() > 0) {
      $message[] = 'Email sudah ada!';
   } else {
      if ($pass != $cpass) { //cek kecocokan password dan konfirmasi password
         $message[] = 'konfirmasi kata sandi tidak cocok!';
      } else {
         //Simpan User baru ke db
         $hash_password = password_hash($pass, PASSWORD_DEFAULT);
         $insert_user = $conn->prepare("INSERT INTO `users`(name, email, password) VALUES(:name, :email, :password)");
         $insert_user->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $hash_password
         ]);
         $message[] = 'berhasil terdaftar, silakan masuk sekarang!';
      }
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Set the viewport to ensure responsiveness on mobile devices -->
   <title>register</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'components/user_header.php'; ?>

   <section class="form-container">

      <form action="" method="post">
         <h3>register now</h3>
         <input type="text" name="name" required placeholder="masukkan username Anda" maxlength="20" class="box">
         <input type="email" name="email" required placeholder="masukkan email Anda" maxlength="50" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="pass" required placeholder="masukkan password Anda" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="cpass" required placeholder="konfirmasi password Anda" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="submit" value="Daftar sekarang" class="btn" name="submit">
         <p>Sudah memiliki akun?</p>
         <a href="user_login.php" class="option-btn">login sekarang</a>
      </form>

   </section>

   <?php include 'components/footer.php'; ?>

   <script src="js/script.js"></script>

</body>

</html>