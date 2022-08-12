<?php 
    session_start();

    require "partials/_connection.php";

    if(isset($_POST['delete'])){
		$wishlistId = $_POST['wishlist-id'];
		$stmt =  $mysqli->prepare("DELETE FROM `wishlist` WHERE wishlist_id = ?");
		$stmt->bind_param("i", $wishlistId);
		$stmt->execute();
		$wishlistResult = $stmt->get_result();
	}

	$user_id = $_SESSION['user_id'];
	$showWishlist = false;

	$stmt =  $mysqli->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $wishlistResult = $stmt->get_result();
    $num = mysqli_num_rows($wishlistResult);

	if($num){
		$showWishlist = true;
		$wishlistRow = mysqli_fetch_all($wishlistResult, MYSQLI_ASSOC);
		// print_r($ordersRow);
	}
?>

<!DOCTYPE html>
<html lang="en">


<!-- molla/wishlist.html  22 Nov 2019 09:55:05 GMT -->
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
        			<h1 class="page-title">Wishlist<span>Shop</span></h1>
        		</div><!-- End .container -->
        	</div><!-- End .page-header -->
            <nav aria-label="breadcrumb" class="breadcrumb-nav">
                <div class="container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Wishlist</li>
                    </ol>
                </div><!-- End .container -->
            </nav><!-- End .breadcrumb-nav -->

            <div class="page-content">
            	<div class="container">
                    <?php if(!$showWishlist): ?>
                        <h4 class="text-center">No items in Wishlist</h4>
                    <?php else: ?>
					<table class="table table-wishlist table-mobile">
						<thead>
							<tr>
								<th>Product</th>
								<th>Price</th>
								<th>Stock Status</th>
								<th></th>
								<th></th>
							</tr>
						</thead>

						<tbody>
                            <?php foreach($wishlistRow as $wishlistItems => $wishlistItem): ?>
							<tr>
								<td class="product-col">
									<div class="product">
										<figure class="product-media">
											<a href="#">
												<img src="<?php echo htmlspecialchars($wishlistItem['wishlist_img'])?>" alt="Product image">
											</a>
										</figure>

										<h3 class="product-title">
											<a href="#"><?php echo htmlspecialchars($wishlistItem['wishlist_name'])?></a>
										</h3><!-- End .product-title -->
									</div><!-- End .product -->
								</td>
								<td class="price-col">$<?php echo htmlspecialchars($wishlistItem['wishlist_price'])?></td>
								
                                    <?php if($wishlistItem['wishlist_stock']): ?>
                                        <td class="stock-col"><span class="in-stock"></span></td>
                                    <?php else: ?>
                                        <td class="stock-col"><span class="out-of-stock">Out of stock</span></td>
                                    <?php endif;?>
                                
                                <?php if($wishlistItem['wishlist_stock']): ?>
								<td class="action-col">
									<button class="btn btn-block btn-outline-primary-2"><i class="icon-cart-plus"></i>Add to Cart</button>
								</td>
                                <?php else: ?>
                                <td class="action-col">
									<button class="btn btn-block btn-outline-primary-2 disabled">Out of Stock</button>
								</td>
                                <?php endif;?>
                                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
									<input type="hidden" name="wishlist-id" value="<?php echo htmlspecialchars($wishlistItem['wishlist_id'])?>">
								    <td class="remove-col"><button name="delete" value="deleted" class="btn-remove"><i class="icon-close"></i></button></td>
                                </form>
							</tr>
                            <?php endforeach; ?>
						</tbody>
					</table><!-- End .table table-wishlist -->
                    <?php endif; ?>
            	</div><!-- End .container -->
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
    <!-- Main JS File -->
    <script src="assets/js/main.js"></script>
</body>


<!-- molla/wishlist.html  22 Nov 2019 09:55:06 GMT -->
</html>