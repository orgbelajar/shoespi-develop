<?php
include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];

   //Cek apakah pesan selamat datang sudah ditampilkan
   if (!isset($_SESSION['welcome_message_shown'])) {
      //Ambil nama pengguna dari database
      $select_user = $conn->prepare("SELECT name FROM `users` WHERE id = :id");
      $select_user->execute([':id' => $user_id]);
      $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);

      if ($fetch_user) {
         $message[] = 'Selamat datang, ' . htmlspecialchars($fetch_user['name']) . '!';
      }
      $_SESSION['welcome_message_shown'] = true; //Tandai bahwa pesan sudah ditampilkan
   }
} else {
   $user_id = '';
   unset($_SESSION['welcome_message_shown']); //Hapus tanda jika user logout
}

include 'components/wishlist_cart.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>
   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
</head>

<body>
   <?php include 'components/user_header.php'; ?>

   <div class="home-bg">
      <section class="home">
         <div class="swiper home-slider">
            <div class="swiper-wrapper">

               <div class="swiper-slide slide">
                  <div class="image">
                     <img src="images/home-img-1.png" alt="">
                  </div>
                  <div class="content">
                     <span>upto 50% off</span>
                     <h3>Smartphones Terbaru</h3>
                     <a href="shop.php" class="btn">Beli Sekarang</a>
                  </div>
               </div>

               <div class="swiper-slide slide">
                  <div class="image">
                     <img src="images/home-img-2.png" alt="">
                  </div>
                  <div class="content">
                     <span>diskon hingga 50%</span>
                     <h3>jam tangan terbaru</h3>
                     <a href="shop.php" class="btn">Beli Sekarang</a>
                  </div>
               </div>

               <div class="swiper-slide slide">
                  <div class="image">
                     <img src="images/home-img-3.png" alt="">
                  </div>
                  <div class="content">
                     <span>diskon hingga 50%</span>
                     <h3>Headsets Terbaru</h3>
                     <a href="shop.php" class="btn">Beli Sekarang</a>
                  </div>
               </div>

            </div>
            <div class="swiper-pagination"></div>
         </div>
      </section>
   </div>

   <section class="category">
      <h1 class="heading">BERBAGAI SEPATU LOKAL UNGGULAN</h1>
      <div class="swiper category-slider">
         <div class="swiper-wrapper">

            <a href="category.php?category=nineteen" class="swiper-slide slide">
               <img src="images/gga.png" alt="">
               <!-- <h3>laptop</h3> -->
            </a>

            <a href="category.php?category=NAH" class="swiper-slide slide">
               <img src="images/ggb.png" alt="">
               <!-- <h3>tv</h3> -->
            </a>

            <a href="category.php?category=aero" class="swiper-slide slide">
               <img src="images/ggc.png" alt="">
               <!-- <h3>camera</h3> -->
            </a>

            <a href="category.php?category=geoff" class="swiper-slide slide">
               <img src="images/ggd.png" alt="">
               <!-- <h3>mouse</h3> -->
            </a>

            <a href="category.php?category=ventela" class="swiper-slide slide">
               <img src="images/gge.png" alt="">
               <!-- <h3>fridge</h3> -->
            </a>

            <a href="category.php?category=bumi" class="swiper-slide slide">
               <img src="images/ggf1.png" alt="">
               <!-- <h3>washing machine</h3> -->
            </a>

            <a href="category.php?category=league" class="swiper-slide slide">
               <img src="images/ggg1.png" alt="">
               <!-- <h3>smartphone</h3> -->
            </a>

            <a href="category.php?category=" class="swiper-slide slide">
               <img src="images/ggg1.png" alt="">
               <!-- <h3>smartphone</h3> -->
            </a>

         </div>
         <div class="swiper-pagination"></div>
      </div>
   </section>

   <section class="home-products">
      <h1 class="heading">Produk Terbaru</h1>
      <div class="swiper products-slider">
         <div class="swiper-wrapper">

            <?php
            $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 6");
            $select_products->execute();
            if ($select_products->rowCount() > 0) {
               while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
            ?>
                  <form action="" method="post" class="swiper-slide slide">
                     <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
                     <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
                     <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
                     <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
                     <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
                     <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a>
                     <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
                     <div class="name"><?= $fetch_product['name']; ?></div>
                     <div class="flex">
                        <div class="price"><span>IDR </span><?= number_format($fetch_product['price'], 0, ',', '.'); ?><span></span></div>
                        <input type="hidden" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
                     </div>
                     <input type="submit" value="Tambahkan Ke keranjang" class="btn" name="add_to_cart">
                  </form>
            <?php
               }
            } else {
               echo '<p class="empty">belum ada produk yang ditambahkan!</p>';
            }
            ?>

         </div>
         <div class="swiper-pagination"></div>
      </div>
   </section>

   <?php include 'components/footer.php'; ?>

   <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
   <script src="js/script.js"></script>

   <script>
      var swiper = new Swiper(".home-slider", {
         loop: true,
         spaceBetween: 20,
         pagination: {
            el: ".swiper-pagination",
            clickable: true,
         },
      });

      var swiper = new Swiper(".category-slider", {
         loop: true,
         spaceBetween: 10,
         pagination: {
            el: ".swiper-pagination",
            clickable: true,
            dynamicBullets: true,
         },
         breakpoints: {
            0: {
               slidesPerView: 2,
            },
            650: {
               slidesPerView: 3,
            },
            768: {
               slidesPerView: 4,
            },
            1024: {
               slidesPerView: 4,
            },
         },
      });

      var swiper = new Swiper(".products-slider", {
         loop: true,
         spaceBetween: 18,
         pagination: {
            el: ".swiper-pagination",
            clickable: true,
         },
         breakpoints: {
            550: {
               slidesPerView: 2,
            },
            768: {
               slidesPerView: 2,
            },
            1024: {
               slidesPerView: 3,
            },
         },
      });
   </script>

</body>

</html>