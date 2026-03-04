<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <?php require('inc/link.php'); ?>
    <title><?php echo $setting_r['site_title'] ?> - BOOKING STATUS</title>


</head>

<body class="bg-light">

    <?php require('inc/header.php'); ?>

    <div class="container">
        <div class="row">

            <div class="col-12 my-5 mb-3 px-4">
                <h2 class="fw-bold">PAYMENT STATUS</h2>
            </div>
            <?php
            if (isset($_GET['payment_id'])) {
                echo <<<data
                    <div class="col-12 px-4">
                        <p class="fw-bold alert alert-success">
                            <i class="bi bi-check-circle-fill"></i>
                            Payment done! Booking successful..
                            <br><br>
                            <a href="bookings.php">Go to Bookings</a>
                        </p>

                    </div>
                data;
            } else {
                echo <<<data
    <div class="col-12 px-4">
        <p class="fw-bold alert alert-danger">
            <i class="bi bi-exclamation-triangle-fill"></i>
            Payment failed! $booking_fetch[trans_resp_msg]
            <br><br>
            <a href="bookings.php">Go to Bookings</a>
        </p>

    </div>
data;
            }
            ?>
        </div>
    </div>
    <!-- Footer -->
    <?php require('inc/footer.php'); ?>

</body>

</html>