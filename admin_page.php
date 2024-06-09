<?php
@include 'config.php';

function rupiah($angka)
{

    $hasil_rupiah = "Rp " . number_format($angka, 2, ',', '.');
    return $hasil_rupiah;
}

if (isset($_POST['add_product'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_FILES['product_image']['name'];
    $product_image_tmp_name = $_FILES['product_image']['tmp_name'];
    $product_image_folder = 'uploaded_img/' . $product_image;

    if (empty($product_name) || empty($product_price) || empty($product_image)) {
        $message[] = 'please fill out all';
    } else {
        $insert = "INSERT INTO products (name, price, image) VALUES('$product_name', '$product_price', '$product_image')";
        $upload = mysqli_query($conn, $insert);
        if ($upload) {
            move_uploaded_file($product_image_tmp_name, $product_image_folder);
            $message[] = 'new product added successfully';
        } else {
            $message[] = 'could not add the product';
        }
    }
};
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM products WHERE id = $id");
    header('location:admin_page.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- custom css file link -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php if (isset($message)) {
        foreach ($message as $message) {
            echo '<span class="message">' . $message . '</span>';
        }
    } ?>
    <div class="container">
        <div class="admin-product-form-container">
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
                <h3>add a new product</h3>
                <input type="text" name="product_name" id="" placeholder="enter product name" class="box">
                <input type="number" name="product_price" id="" placeholder="enter product price" class="box">
                <input type="file" accept="image/png, image/jpeg, image/jpg" name="product_image" id="" class="box">
                <input type="submit" value="add product" class="btn" name="add_product">
            </form>
        </div>
        <?php
        $select = mysqli_query($conn, "SELECT * FROM `products`") ?>
        <div class="product-display">
            <table class="product-display-table">
                <thead>
                    <tr>
                        <th>product image</th>
                        <th>product name</th>
                        <th>product price</th>
                        <th colspan="2">action</th>
                    </tr>
                </thead>
                <?php
                while ($row = mysqli_fetch_assoc($select)) {
                ?>
                    <tr>
                        <td><img src="uploaded_img/<?php echo $row['image'] ?>" height="100" alt=""></td>
                        <td><?php echo $row['name'] ?></td>
                        <td><?php
                            echo rupiah($row['price']) ?></td>
                        <td>
                            <a href="admin_update.php?edit=<?php echo $row['id'] ?>" class="btn"><i class="fa-regular fa-pen-to-square"></i>&nbsp;edit</a>
                            <a href="admin_page.php?delete=<?php echo $row['id'] ?>" class="btn delete"><i class="fa-solid fa-trash-can"></i>&nbsp;delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="js/script.js"></script>
</body>

</html>