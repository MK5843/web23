<?php
include 'connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   setcookie('user_id', create_unique_id(), time() + 60*60*24*30);
}

if(isset($_POST['add_to_cart'])){

   $id = create_unique_id();
   $product_id = $_POST['product_id'];
   $product_id = filter_var($product_id, FILTER_SANITIZE_STRING);
   $qty = $_POST['qty'];
   $qty = filter_var($qty, FILTER_SANITIZE_STRING);
   
   $verify_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ? AND product_id = ?");   
   $verify_cart->execute([$user_id, $product_id]);

   $max_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $max_cart_items->execute([$user_id]);

   if($verify_cart->rowCount() > 0){
      $warning_msg[] = 'Already added to cart!';
   }elseif($max_cart_items->rowCount() == 10){
      $warning_msg[] = 'Cart is full!';
   }else{

      $select_price = $conn->prepare("SELECT * FROM `products` WHERE id = ? LIMIT 1");
      $select_price->execute([$product_id]);
      $fetch_price = $select_price->fetch(PDO::FETCH_ASSOC);

      $insert_cart = $conn->prepare("INSERT INTO `cart`(id, user_id, product_id, price, qty) VALUES(?,?,?,?,?)");
      $insert_cart->execute([$id, $user_id, $product_id, $fetch_price['price'], $qty]);
      $success_msg[] = 'Added to cart!';
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
  <style>

.form{
	width: 40%;
	background-color: white;
	padding: 20px;
	display: flex;
	flex-direction: column;
	align-items: flex-end;
	
}
.form h1{
	text-align: center;
	margin-bottom: 20px;
	width: 100%;
}
.form form{
	width: 100%;
	display: flex;
	flex-direction: column;
	align-items: flex-end;
}
.flex-rev {
    display: flex;
    flex-direction: column-reverse;
    margin-bottom: 10px;
		width: 100%;
}

.flex-rev input, .flex-rev textarea {
    border: none;
    background-color: #e6e6e6;
    padding: 12px 10px;
    font-size: 16px;
    resize: none;
    margin-top: 7px;
    margin-bottom: 16px;
    border-radius: 5px;
    color: #243342;
    outline-color: #243342;
    outline-width: thin;
	 -webkit-appearance: none;
}
.flex-rev textarea{
	height: 150px;
}
button{
	-webkit-appearance: none;
	margin-right: 0;
}




@media screen and (max-width: 900px){
	.content{
		padding: 10px 0 0;
		display: block;
	}
	.map{
		display: none;
	}
	.contact{
		width: 100%;
		flex-direction: column-reverse;
		border-radius: 0;
		box-shadow: 0px 0px 0px 0px;
	}
	.other{
		width: 100%;
		padding: 20px 0;
	}
	.form{
		width: 100%;
	}
}

@media screen and (max-height: 660px){
	.content{
		align-items: flex-start;
	}
}


		
		</style>
		
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
        
                        <button class="btn btn-outline-light text-center" type="submit">
                            <i class="bi-cart-fill me-1"></i>
                            
         <a href="shopping_cart.php" style="text-decoration-line: none;"><span class="badge bg-dark text-white ms-1 rounded-pill"><?= $total_cart_items; ?></span></a>
                           
                        </button>
            
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                        <li class="nav-item"><a class="nav-link" aria-current="page" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
						<li class="nav-item"><a class="nav-link active" href="contact.php">Contact us</a></li>
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
<br/>

<div class="content" align="center">
	
		<div class="form" align="left">
			<h1>Get In Touch</h1>
			<form action="">
				<div class="flex-rev">
					<input type="text" placeholder="Connor Gaunt" name="name" id="name" />
					<label for="name">Full Name</label>
				</div>
				<div class="flex-rev">
					<input type="email" placeholder="connor@connorgaunt.com" name="email" id="email" />
					<label for="email">Your Email</label>
				</div>

				<div class="flex-rev">
					<textarea placeholder="I have an idea for a project...." name="message" id="message" /></textarea>
					<label for="message">Email Message</label>
				</div>
				<button class="btn btn-outline-dark text-center">Send Email</button>
			</form>
		</div>
	</div>
</div>
</div>
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
