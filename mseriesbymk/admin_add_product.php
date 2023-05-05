<?php
include 'connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   setcookie('user_id', create_unique_id(), time() + 60*60*24*30);
}

if(isset($_POST['add'])){

   $id = create_unique_id();
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $ext = pathinfo($image, PATHINFO_EXTENSION);
   $rename = create_unique_id().'.'.$ext;
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_size = $_FILES['image']['size'];
   $image_folder = 'uploaded_files/'.$rename;

   if($image_size > 2000000){
      $warning_msg[] = 'Image size is too large!';
   }else{
      $add_product = $conn->prepare("INSERT INTO `products`(id, name, price, image) VALUES(?,?,?,?)");
      $add_product->execute([$id, $name, $price, $rename]);
      move_uploaded_file($image_tmp_name, $image_folder);
      $success_msg[] = 'Product added!';
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
        
                        <button class="btn btn-outline-light text-center" type="submit">
                            <i class="bi-cart-fill me-1"></i>
                            
         <a href="shopping_cart.php" style="text-decoration-line: none;"><span class="badge bg-dark text-white ms-1 rounded-pill"><?= $total_cart_items; ?></span></a>
                           
                        </button>
            
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                        <li class="nav-item"><a class="nav-link active" aria-current="page" href="admin_add_product.php">Admin Home</a></li>
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
        <!-- Header-->
		<div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="images/c-1.jpg" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="images/c2.jpg" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="images/c3.jpg" class="d-block w-100" alt="...">
    </div>
  </div>
</div>
      <br/>

<section>
 <div class="container">
	  <h2>Add Product</h2>
    <div class="row d-flex justify-content-center align-items-center">
      <div class="col">
        <div class="card shopping-cart" style="border-radius: 15px;">
          <div class="card-body text-black">

            <div class="row">
              <div class="col-lg-6 px-5 py-4">

                <h3 class="mb-5 pt-2 text-left fw-bold text-uppercase">Product Info</h3>
   <form action="" method="POST" enctype="multipart/form-data" class="mb-5">
       <div class="form-outline mb-5">
						  <label class="form-label" for="typeName">Product Name <span style="color: red;">*</span></label>
                    <input type="text" id="typeName" name="name" class="form-control form-control-lg" size="17" placeholder="Enter product name"/>
                    
                  </div>
	   <div class="form-outline mb-5">
						  <label class="form-label" for="typePrice">Product Price <span style="color: red;">*</span></label>
                    <input type="number" id="typePrice" name="price" class="form-control form-control-lg" size="17" placeholder="e.g. 16" minlength="19" maxlength="50"/>
                    
                  </div>
      <div class="form-outline mb-5">
						  <label class="form-label" for="typeImage">Product Image <span style="color: red;">*</span></label>
                    <input type="file" id="typeImage" name="image" accept="uploaded_files/*" class="form-control form-control-lg" size="17"/>
                    
                  </div>
      
      <input type="submit" class="btn btn-dark btn-block btn-lg" name="add" value="Add Product">
   </form>

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