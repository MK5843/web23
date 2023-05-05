<?php

include 'connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   setcookie('user_id', create_unique_id(), time() + 60*60*24*30);
}

if(isset($_POST['place_order'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $address = $_POST['flat'].', '.$_POST['street'].', '.$_POST['city'].', '.$_POST['country'].' - '.$_POST['pin_code'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $address_type = $_POST['address_type'];
   $address_type = filter_var($address_type, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);

   $verify_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $verify_cart->execute([$user_id]);
   
   if(isset($_GET['get_id'])){

      $get_product = $conn->prepare("SELECT * FROM `products` WHERE id = ? LIMIT 1");
      $get_product->execute([$_GET['get_id']]);
      if($get_product->rowCount() > 0){
         while($fetch_p = $get_product->fetch(PDO::FETCH_ASSOC)){
            $insert_order = $conn->prepare("INSERT INTO `orders`(id, user_id, name, number, email, address, address_type, method, product_id, price, qty) VALUES(?,?,?,?,?,?,?,?,?,?,?)");
            $insert_order->execute([create_unique_id(), $user_id, $name, $number, $email, $address, $address_type, $method, $fetch_p['id'], $fetch_p['price'], 1]);
            header('location:orders.php');
         }
      }else{
         $warning_msg[] = 'Something went wrong!';
      }

   }elseif($verify_cart->rowCount() > 0){

      while($f_cart = $verify_cart->fetch(PDO::FETCH_ASSOC)){

         $insert_order = $conn->prepare("INSERT INTO `orders`(id, user_id, name, number, email, address, address_type, method, product_id, price, qty) VALUES(?,?,?,?,?,?,?,?,?,?,?)");
         $insert_order->execute([create_unique_id(), $user_id, $name, $number, $email, $address, $address_type, $method, $f_cart['product_id'], $f_cart['price'], $f_cart['qty']]);

      }

      if($insert_order){
         $delete_cart_id = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
         $delete_cart_id->execute([$user_id]);
         header('location:orders.php');
      }

   }else{
      $warning_msg[] = 'Your cart is empty!';
   }

}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>M Series by MK</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
		<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

   
    </head>
    <body>
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container px-4 px-lg-5">
                <a class="navbar-brand" href="index.php"><img src="images/logo33.png" alt="logo" width="20%" height="20%"/></a>
				<?php
            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
            $total_cart_items = $count_cart_items->rowCount();
         ?>
        
                        <button class="btn btn-outline-light text-center active" type="submit">
                            <i class="bi-cart-fill me-1"></i>
                            
         <a href="shopping_cart.php" style="text-decoration-line: none;"><span class="badge bg-dark text-white ms-1 rounded-pill"><?= $total_cart_items; ?></span></a>
                           
                        </button>
            
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                        <li class="nav-item"><a class="nav-link" aria-current="page" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
						<li class="nav-item"><a class="nav-link" href="contact.php">Contact us</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Help</a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="tnc.php">Terms & Condition</a></li>
                                
                               
                            </ul>
                        </li>
						  <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">User</a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item disabled" href="login.php">Login</a></li>
								 <li><a class="dropdown-item disabled" href="signup.php">Signup</a></li>
                                <li><hr class="dropdown-divider" /></li>
                                <li><a class="dropdown-item" href="orders.php">Orders</a></li>
                             
                            </ul>
                        </li>
                    </ul>
                    
                </div>
            </div>
        </nav>
    <br/>  <br/>
     <section>
  <div class="container-fluid">
	  <h2>Checkout Summary</h2>
    <div class="row d-flex justify-content-center align-items-center">
      <div class="col">
        <div class="card shopping-cart" style="border-radius: 15px;">
          <div class="card-body text-black">

            <div class="row">
              <div class="col-lg-6 px-5 py-4">

                <h3 class="mb-5 pt-2 text-center fw-bold text-uppercase">Cart Items</h3>
<?php
            $grand_total = 0;
            if(isset($_GET['get_id'])){
               $select_get = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
               $select_get->execute([$_GET['get_id']]);
               while($fetch_get = $select_get->fetch(PDO::FETCH_ASSOC)){
         ?>
       <div class="d-flex align-items-center mb-5">
                  <div class="flex-shrink-0">
                    <img src="uploaded_files/<?= $fetch_get['image']; ?>"
                      class="img-fluid" width="30%" alt="">
                  </div>
            
               <h3 class="name"><?= $fetch_get['name']; ?></h3>
               <p class="price"><i class="fas fa-indian-rupee-sign"></i> <?= $fetch_get['price']; ?> x 1</p>
            </div>
         </div>
         <?php
               }
            }else{
               $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
               $select_cart->execute([$user_id]);
               if($select_cart->rowCount() > 0){
                  while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
                     $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
                     $select_products->execute([$fetch_cart['product_id']]);
                     $fetch_product = $select_products->fetch(PDO::FETCH_ASSOC);
                     $sub_total = ($fetch_cart['qty'] * $fetch_product['price']);

                     $grand_total += $sub_total;
            
         ?>
          <div class="d-flex align-items-center mb-5">
                  <div class="flex-shrink-0">
                    <img src="uploaded_files/<?= $fetch_product['image']; ?>"
                      class="img-fluid" style="width: 150px;" alt="">
                  </div>
           
				<div class="flex-grow-1 ms-3">
                    <h5 class="text-primary"><?= $fetch_product['name']; ?></h5>
                    <h6 class="lead fw-normal mb-3">RM <?= $fetch_product['price']; ?> x <?= $fetch_cart['qty']; ?></h6>
            
               
            </div>
         </div>
         <?php
                  }
               }else{
                  echo '<p class="empty">your cart is empty</p>';
               }
            }
         ?>
				 <div class="d-flex justify-content-between p-2 mb-2" style="background-color: #AEE893;">
                  <h5 class="fw-bold mb-0">Total:</h5>
                  <h5 class="fw-bold mb-0">RM <?= $grand_total; ?></h5>
                </div>


   </div>
             
              <div class="col-lg-6 px-5 py-4">

                <h3 class="mb-5 pt-2 text-center fw-bold text-uppercase">Billing details</h3>

                <form action="" method="POST" class="mb-5">

					  <div class="form-outline mb-5">
						  <label class="form-label" for="typeName">Your Name <span style="color: red;">*</span></label>
                    <input type="text" id="typeName" name="name" class="form-control form-control-lg" size="17" placeholder="e.g. john"/>
                    
                  </div>
					
                  <div class="form-outline mb-5">
                    <label class="form-label" for="typeNumber">Your Contact Number <span style="color: red;">*</span></label>
					  <input type="number" id="typeNumber" class="form-control form-control-lg" size="17"
                      placeholder="012 345 6789" name="number" minlength="19" maxlength="50" />
                    
                  </div>

                  <div class="form-outline mb-5">
                   <label class="form-label" for="typeEmail">Your Email <span style="color: red;">*</span></label> 
					  <input type="email" name="email" id="typeEmail" class="form-control form-control-lg" size="17" placeholder="sample@example.com"/>
                    
                  </div>
					<div class="form-outline mb-5">
                   <label class="form-label" for="typeMethod">Payment Method <span style="color: red;">*</span></label> 
                    <select name="method" class="form-select form-select-lg mb-3" id="typeMethod"  required>
					
						<option value="cash on Delivery">Cash on Delivery</option>
						 <option value="credit or debit card">Credit or Debit Card</option>
						
						</select>
                  </div>
					<div class="form-outline mb-5">
                   <label class="form-label" for="typeAddType">Address Type <span style="color: red;">*</span></label> 
                    <select name="address_type" class="form-select form-select-lg mb-3" id="typeAddType"  required>
					
						<option value="home">Home</option>
						 <option value="office">Office</option>
						
						</select>
                  </div>
					<div class="form-outline mb-5">
						  <label class="form-label" for="typeAddress">Address Line 01 <span style="color: red;">*</span></label>
                    <input type="text" id="typeAddress" name="flat" class="form-control form-control-lg" size="17" placeholder="Flat No./ Building No."/>
                    
                  </div>
					<div class="form-outline mb-5">
						  <label class="form-label" for="typeStreet">Address Line 02 <span style="color: red;">*</span></label>
                    <input type="text" id="typeStreet" name="street" class="form-control form-control-lg" size="17" placeholder="Street Name"/>
                    
                  </div>
					<div class="form-outline mb-5">
						  <label class="form-label" for="typeCity">City <span style="color: red;">*</span></label>
                    <input type="text" id="typeCity" name="city" class="form-control form-control-lg" size="17" placeholder="City"/>
                    
                  </div>
					<div class="form-outline mb-5">
						  <label class="form-label" for="typeCountry">Country <span style="color: red;">*</span></label>
                    <input type="text" id="typeCountry" name="country" class="form-control form-control-lg" size="17" placeholder="Country"/>
                    
                  </div>
				  <div class="form-outline mb-5">
                    <label class="form-label" for="typePinCode">Pin Code <span style="color: red;">*</span></label>
					  <input type="number" id="typePinCode" class="form-control form-control-lg" size="17"
                      placeholder="e.g. 12345" name="pin_code" minlength="19" maxlength="50" />
                    
                  </div>
					
					

                  <button type="submit" name="place_order" class="btn btn-dark btn-block btn-lg">Place Order</button>

                  <h5 class="fw-bold mb-5" style="position: absolute; bottom: 0;">
                    <a href="index.php" style="color: black;"><i class="fas fa-angle-left me-2"></i>Back to shopping</a>
                  </h5>

                </form>

              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>
	<br/>
	
        <!-- Footer -->
<footer class="text-center text-lg-start bg-dark text-muted">
  <!-- Section: Social media -->
 <br/>
  <!-- Section: Social media -->

  <!-- Section: Links  -->
  <section class="">
    <div class="container text-center text-md-start mt-5 text-light">
      <!-- Grid row -->
      <div class="row mt-3">
        <!-- Grid column -->
        <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
          <!-- Content -->
          <h6 class="text-uppercase fw-bold mb-4">
            M Series by MK
          </h6>
         <a href="https://www.facebook.com/" class="me-4 link-secondary">
        <i class="fab fa-facebook"></i>
      </a>
      <a href="https://www.instagram.com/" class="me-4 link-secondary">
        <i class="fab fa-instagram"></i>
      </a>
        </div>
        <!-- Grid column -->

       
        <!-- Grid column -->
        <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
          <!-- Links -->
          <h6 class="text-uppercase fw-bold mb-4">Contact</h6>
          
          <p>
            <i class="fas fa-envelope me-3 text-secondary"></i>
            mseries@example.com
          </p>
          <p><i class="fas fa-phone me-3 text-secondary"></i>(+608) 545 8765</p>
       
        </div>
        <!-- Grid column -->
      </div>
      <!-- Grid row -->
    </div>
  </section>
  <!-- Section: Links  -->

  <!-- Copyright -->
  <div class="text-center p-4 border-top" >
    © 2023 Copyright:
    <strong>M Series by MK</strong>
  </div>
  <!-- Copyright -->
</footer>
<!-- Footer -->
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<script src="js/script.js"></script>

<?php include 'alert.php'; ?>
    </body>
</html>
