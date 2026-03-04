<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <?php require('inc/link.php'); ?>
    <title><?php echo $setting_r['site_title']?> - ABOUT</title>

    <style>
        .box {
            border-top-color: var(--teal) !important;
        }

        .autoplay-progress {
            position: absolute;
            right: 16px;
            bottom: 16px;
            z-index: 10;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: var(--swiper-theme-color);
        }

        .autoplay-progress svg {
            --progress: 0;
            position: absolute;
            left: 0;
            top: 0px;
            z-index: 10;
            width: 100%;
            height: 100%;
            stroke-width: 4px;
            stroke: var(--swiper-theme-color);
            fill: none;
            stroke-dashoffset: calc(125.6px * (1 - var(--progress)));
            stroke-dasharray: 125.6;
            transform: rotate(-90deg);
        }
    </style>
</head>

<body class="bg-light">

    <?php require('inc/header.php'); ?>

    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">ABOUT US</h2>
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-3">
            Lorem ipsum dolor sit amet consectetur adipisicing elit.
            Exercitationem laboriosam <br>in animi optio
            neque illum qui esse eaque id minima.
        </p>
    </div>

    <div class="container">
        <div class="row justify-content-between align-items-center">
            <div class="col-lg-6 col-md-5 mb-4 order-lg-1 order-md-1 order-2">
                <h3 class="mb-3">Lorem ipsum dolor sit.</h3>
                <p>
                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    Qui, atque aut? Fugit, sed? Corrupti, aliquam vero!
                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    Qui, atque aut? Fugit, sed? Corrupti, aliquam vero!

                </p>
            </div>
            <div class="col-lg-5 col-md-5 mb-4 order-lg-2 order-md-2 order-1">
                <img src="images/about/about.jpg" class="w-100">
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                    <img src="images/about/hotel.svg" width="70px">
                    <h4 class="mt-3">100+ ROOMS</h4>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                    <img src="images/about/customers.svg" width="70px">
                    <h4 class="mt-3">200+ CUSTOMERS</h4>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                    <img src="images/about/rating.svg" width="70px">
                    <h4 class="mt-3">100+ REVIEWS </h4>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                    <img src="images/about/staff.svg" width="70px">
                    <h4 class="mt-3">100+ STAFFS</h4>
                </div>
            </div>
        </div>
    </div>
    <h3 class="my-5 fw-bold h-font text-center">MANAGENT TEAM</h3>

    <div class="container px-4">
        <div class="swiper mySwiper">
            <div class="swiper-wrapper md-5">
                <?php 
                $about_r=selectAll('team_details');
                $path=ABOUT_IMG_PATH;
                while($row=mysqli_fetch_assoc($about_r)){
                    echo<<<data
                    <div class="swiper-slide bg-white text-center overflow-hidden rounded">
                        <img src="$path$row[picture]" class="w-100">
                        <h5 class="mt-2">$row[name]</h5>
                    </div>

                    data;
                }
                ?>
                

            </div>
            <div class="autoplay-progress">
                <svg viewBox="0 0 0 0">
                    <circle cx="24" cy="24" r="20"></circle>
                </svg>
                <span></span>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <?php require('inc/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        const progressCircle = document.querySelector(".autoplay-progress svg");
        const progressContent = document.querySelector(".autoplay-progress span");
        var swiper = new Swiper(".mySwiper", {
            slidesPerView: 4,
            spaceBetween: 40,
            centeredSlides: true,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev"
            },
            on: {
                autoplayTimeLeft(s, time, progress) {
                    progressCircle.style.setProperty("--progress", 1 - progress);
                    progressContent.textContent = `${Math.ceil(time / 1000)}s`;
                }
            },
            breakpoints: {
                320: {
                    slidesPerView: 1,
                },
                640: {
                    slidesPerView: 1,
                },
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            }
        });
    </script>
</body>

</html>