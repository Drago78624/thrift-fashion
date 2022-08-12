<?php 
    session_start();

    require "partials/_connection.php";

    if(isset($_POST['delete'])){
		$cartId = $_POST['cart-id'];
		$stmt =  $mysqli->prepare("DELETE FROM `cart` WHERE cart_id = ?");
		$stmt->bind_param("i", $cartId);
		$stmt->execute();
		$cartResult = $stmt->get_result();
	}

	$user_id = $_SESSION['user_id'];
	$showCart = false;

	$stmt =  $mysqli->prepare("SELECT * FROM `cart` WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $cartResult = $stmt->get_result();
    $num = mysqli_num_rows($cartResult);

	if($num){
		$showCart = true;
		$cartRow = mysqli_fetch_all($cartResult, MYSQLI_ASSOC);
		// print_r($ordersRow);
        $stmt =  $mysqli->prepare("SELECT SUM(cart_price) FROM cart WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $totalResult = $stmt->get_result();
        $total = mysqli_fetch_assoc($totalResult);
        // print_r($total["SUM(cart_price)"]);
	}
?>

<!DOCTYPE html>
<html lang="en">


<!-- molla/cart.html  22 Nov 2019 09:55:06 GMT -->
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Molla - Bootstrap eCommerce Template</title>
    <meta name="keywords" content="HTML5 Template">
    <meta name="description" content="Molla - Bootstrap eCommerce Template">
    <meta name="author" content="p-themes">
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/icons/favicon-16x16.png">
    <link rel="manifest" href="assets/images/icons/site.html">
    <link rel="mask-icon" href="assets/images/icons/safari-pinned-tab.svg" color="#666666">
    <link rel="shortcut icon" href="assets/images/icons/favicon.ico">
    <meta name="apple-mobile-web-app-title" content="Molla">
    <meta name="application-name" content="Molla">
    <meta name="msapplication-TileColor" content="#007bff;">
    <meta name="msapplication-config" content="assets/images/icons/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
    <!-- Plugins CSS File -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Main CSS File -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/skins/skin-demo-4.css">
    <link rel="stylesheet" href="assets/css/demos/demo-4.css">
</head>

<body>
    <div class="page-wrapper">
       <?php require "partials/_header.php" ?>

        <main class="main">
        	<div class="page-header text-center" style="background-image: url('assets/images/page-header-bg.jpg')">
        		<div class="container">
        			<h1 class="page-title">Shopping Cart<span>Shop</span></h1>
        		</div><!-- End .container -->
        	</div><!-- End .page-header -->
            <nav aria-label="breadcrumb" class="breadcrumb-nav">
                <div class="container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Shopping Cart</li>
                    </ol>
                </div><!-- End .container -->
            </nav><!-- End .breadcrumb-nav -->

            <div class="page-content">
            	<div class="cart">
	                <div class="container">
	                	<div class="row">
	                		<div class="col-lg-9">
                            <?php if(!$showCart): ?>
                                <h4 class="text-center">No items in Cart</h4>
                            <?php else: ?>
	                			<table class="table table-cart table-mobile">
									<thead>
										<tr>
											<th>Product</th>
											<th>Price</th>
											<th>Quantity</th>
											<th>Total</th>
											<th></th>
										</tr>
									</thead>

									<tbody>
                                    <?php foreach($cartRow as $cartItems => $cartItem): ?>
										<tr>
											<td class="product-col">
												<div class="product">
													<figure class="product-media">
														<a href="#">
															<img src="<?php echo htmlspecialchars($cartItem['cart_img'])?>" alt="Product image">
														</a>
													</figure>

													<h3 class="product-title">
														<a href="#"><?php echo htmlspecialchars($cartItem['cart_name'])?></a>
													</h3><!-- End .product-title -->
												</div><!-- End .product -->
											</td>
											<td class="price-col"><?php echo htmlspecialchars($cartItem['cart_price'])?></td>
											<td class="quantity-col">
                                                <div class="cart-product-quantity">
                                                    <input type="number" class="form-control" value="1" min="1" max="10" step="1" data-decimals="0" required>
                                                </div><!-- End .cart-product-quantity -->
                                            </td>
											<td class="total-col">$99</td>
                                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
                                                <input type="hidden" name="cart-id" value="<?php echo htmlspecialchars($cartItem['cart_id'])?>">
											<td class="remove-col"><button name="delete" value="deleted" class="btn-remove"><i class="icon-close"></i></button></td>
                                            </form>
										</tr>
                                    <?php endforeach; ?>
									</tbody>
								</table><!-- End .table table-wishlist -->
                            <?php endif; ?>
	                		</div><!-- End .col-lg-9 -->
	                		<aside class="col-lg-3">
	                			<div class="summary summary-cart">
	                				<h3 class="summary-title">Cart Total</h3><!-- End .summary-title -->

	                				<table class="table table-summary">
	                					<tbody>
	                						<tr class="summary-subtotal">
	                							<td>Subtotal:</td>
	                							<td>$<?php echo htmlspecialchars($total["SUM(cart_price)"])?></td>
	                						</tr><!-- End .summary-subtotal -->
	                						<tr class="summary-shipping">
	                							<td>Shipping:</td>
	                							<td>&nbsp;</td>
	                						</tr>

	                						<tr class="summary-shipping-row">
	                							<td>
														<div class="custom-control-label" for="free-shipping">Shipping</div>
	                							</td>
	                							<td>$20.00</td>
	                						</tr><!-- End .summary-shipping-row -->

	                						<tr class="summary-total">
	                							<td>Total:</td>
	                							<td>$<?php echo htmlspecialchars($total["SUM(cart_price)"]) + 20.00?></td>
	                						</tr><!-- End .summary-total -->
	                					</tbody>
	                				</table><!-- End .table table-summary -->

	                				<a href="checkout.php" class="btn btn-outline-primary-2 btn-order btn-block">PROCEED TO CHECKOUT</a>
	                			</div><!-- End .summary -->

		            			<a href="category.php" class="btn btn-outline-dark-2 btn-block mb-3"><span>CONTINUE SHOPPING</span><i class="icon-refresh"></i></a>
	                		</aside><!-- End .col-lg-3 -->
	                	</div><!-- End .row -->
	                </div><!-- End .container -->
                </div><!-- End .cart -->
            </div><!-- End .page-content -->
        </main><!-- End .main -->

        <?php require "partials/_footer.php" ?>
    </div><!-- End .page-wrapper -->
    <button id="scroll-top" title="Back to Top"><i class="icon-arrow-up"></i></button>

    <?php require "partials/_mobile-menu.php" ?>

    <!-- Plugins JS File -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/jquery.hoverIntent.min.js"></script>
    <script src="assets/js/jquery.waypoints.min.js"></script>
    <script src="assets/js/superfish.min.js"></script>
    <script src="assets/js/owl.carousel.min.js"></script>
    <script src="assets/js/bootstrap-input-spinner.js"></script>
    <!-- Main JS File -->
    <script src="assets/js/main.js"></script>
</body>


<!-- molla/cart.html  22 Nov 2019 09:55:06 GMT -->
</html>