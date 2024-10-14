<?php
include 'db.php';

// Fetch site content dynamically
$headerContent = getSectionContent('header');

$services = getAllServices();
$footerContent = getSectionContent('footer');

function getSectionContent($section)
{
    global $conn;
    $query = "SELECT telephone_number, image FROM site_content WHERE section = '$section'";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

function getAllServices()
{
    global $conn;
    $query = "SELECT * FROM services";
    return mysqli_query($conn, $query);
}

function getHeaderContent()
{
    global $conn;
    $query = "SELECT telephone_number, image, logo_position, nav_position FROM site_content WHERE section = 'header'";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

$header = getHeaderContent();

function getAboutusSection()
{
    global $conn;
    $query = "SELECT * FROM site_content WHERE section = 'aboutus'";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}
$aboutContent = getAboutusSection();

// Fetch social links
function getSocialLinks()
{
    global $conn;
    $query = "SELECT * FROM social_links WHERE id = 1";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

$socialLinks = getSocialLinks();

/// Assuming you have a connection to your database
$query = "SELECT * FROM site_content WHERE section = 'hero'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $hero = mysqli_fetch_assoc($result);
} else {
    // Fallback settings if nothing is found
    $hero = [
        'hero_title' => 'Professional Handyman Services',
        'hero_subtitle' => '',
        'hero_secondary_text' => '',
        'hero_background_type' => 'color',
        'hero_background_color' => '#f5f5f5',
        'hero_background_image' => '',
        'hero_text_align' => 'left',
        'show_quote_button' => 0,
        'hero_title_font_size' => '36px',
        'hero_title_font_family' => 'Arial, sans-serif',
        'hero_title_color' => '#333333',
        'hero_subtitle_font_size' => '24px',
        'hero_subtitle_font_family' => 'Arial, sans-serif',
        'hero_subtitle_color' => '#666666',
        'hero_height' => '500px',
        'button_bg_color' => '#ff0000',
        'button_hover_bg_color' => '#cc0000',
        'button_border_color' => '#ff0000',
        'button_text_color' => '#ffffff',
        'button_text_hover_color' => '#ffffff',
    ];
}

function getContactDetails()
{
    global $conn;
    $query = "SELECT * FROM admin WHERE id = 1";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}
$contactDetails = getContactDetails();


// Function to update views count
function updateViewsCount()
{
    global $conn;

    // Get visitor's IP address
    $ip = $_SERVER['REMOTE_ADDR'];

    // Check if the IP already exists in the views table
    $query = "SELECT * FROM views WHERE ip = '$ip'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 0) {
        // If the IP is not found, it's a unique visitor
        // Increment the count for all visitors
        $query = "UPDATE views SET count = count + 1";
        mysqli_query($conn, $query);

        // Insert the new IP into the views table
        $country = 'Unknown'; // Replace with actual logic to get the country if needed
        $insertQuery = "INSERT INTO views (count, ip, country) VALUES (1, '$ip', '$country')";
        mysqli_query($conn, $insertQuery);
    }
}

// Call the function to update views count
updateViewsCount();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Handyman Services</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
        }

        header {
            background-color: #34495E;
            color: white;
            padding: 15px 20px;
        }

        /* Base styles for the header */
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            position: relative;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
            margin: 0;
            padding: 0;
        }

        nav ul li {
            display: inline;
        }

        nav ul li a {
            text-decoration: none;
            color: white;
            padding: 10px;
            border-radius: 4px;
            transition: background-color 0.3s;
            text-transform: uppercase;
            font-weight: 600;
        }

        nav ul li a:hover {
            background-color: #2C3E50;
        }

        .logo img {
            max-height: 60px;
            width: auto;
        }
        section{
            margin-top:40px;
           
        }
        .footer-navigation{
            display: flex;
            justify-content: center;
        }
        .footer-navigation ul{
           display: flex;
        }
        .footer-navigation ul li{
           list-style-type: none;
           margin:20px;
           color: #ddd;
        }
        .footer-navigation ul li a{          
           color: #ddd;
           text-decoration: none;
        }
        /* Responsive navigation for mobile screens */
        @media (max-width: 768px) {
            .header-container {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }

            nav ul {
                display: flex;
                flex-flow: wrap;
                background-color: #34495E;
                width: 100%;
                padding: 20px;
            }

            nav ul li {
                width: 40%;
                text-align: center;
            }

        }

        @media (max-width: 400px) {

            nav {
                width: 100%;
                margin: 0;
            }

            nav ul {
                display: flex;
                flex-flow: wrap;
                background-color: #34495E;
                width: 100%;
                padding: 2px;
            }

            nav ul li {
                width: 45%;
                text-align: center;
            }

        }


        .contact {
            text-align: center;
        }
        .contact-us{
            text-align: center;
        }

        .contact-number a {
            text-decoration: none;
            font-size: 22px;
            color: #ecf0f1;
            margin: 0;
            letter-spacing: 0.1em;
        }

        .contact-number {
            font-size: 24px;
            font-weight: bold;
            color: #f39c12;
            margin: 5px 0;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
            padding: 5px 10px;
            border: 3px solid #f9f9f9;
            border-radius: 15px;
            background-color: #e67e22;
        }

        .contact-number:hover {
            color: #e67e22;
            cursor: pointer;
            background-color: #e76c00;

        }

        .hero {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: <?php echo ($hero['hero_background_type'] == 'image' && !empty($hero['hero_background_image'])) ? "url('" . $hero['hero_background_image'] . "')" : $hero['hero_background_color']; ?>;
            background-size: cover;
            background-position: center;
            height: <?php echo htmlspecialchars($hero['hero_height']); ?>vh;
        }

        .hero-content-section {
            width: 100%;
            margin: 0;
            padding: 20px;
            text-align: <?php echo htmlspecialchars($hero['hero_text_align']); ?>;
            background-color: rgb(0, 64, 255, 0.2);
            padding: 20px;
        }

        .hero a {
            text-decoration: none;
            width: 200px;
            margin: auto;
            background-color: <?php echo htmlspecialchars($hero['button_bg_color']); ?>;
            padding: 18px 24px;
            display: block;
            border-color: <?php echo htmlspecialchars($hero['button_border_color']); ?>;
            color: <?php echo htmlspecialchars($hero['button_text_color']); ?>;
            text-align: center;
            font-size: 20px;
            font-weight: 700;
        }

        .hero h1 {
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
            font-size: <?php echo htmlspecialchars($hero['hero_title_font_size']); ?>px;
            color: <?php echo htmlspecialchars($hero['hero_title_color']); ?>;
            margin-bottom: 5px;


        }

        .hero h2 {
            color: <?php echo htmlspecialchars($hero['hero_subtitle_color']); ?>;
            font-size: <?php echo htmlspecialchars($hero['hero_subtitle_font_size']); ?>px;
            margin-bottom: 5px;

        }

        .hero a:hover {
            background-color: <?php echo htmlspecialchars($hero['button_hover_bg_color']); ?>;
            color: <?php echo htmlspecialchars($hero['button_text_hover_color']); ?>;
        }


        /* Services Section */
        #services {
            padding: 50px 20px;
            background-color: #f9f9f9;
            text-align: center;
        }

        #services h2 {
            font-size: 2.5rem;
            margin-bottom: 40px;
            color: #333;
        }

        .carousel-container {
            position: relative;
            max-width: 100%;
            overflow: hidden;
        }

        .carousel-wrapper {
            overflow: hidden;
        }

        .services-carousel {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }

        .service-item {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            width: 300px;
            margin: 10px;
            text-align: left;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            min-width: 300px;
        }

        .service-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
        }

        .service-item h3 {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 15px;
        }

        .service-item p {
            font-size: 1rem;
            color: #666;
            line-height: 1.6;
        }

        /* Carousel Controls */
        .prev-btn,
        .next-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0, 0, 0, 0.1);
            border: none;
            color: #333;
            font-size: 2rem;
            padding: 10px;
            cursor: pointer;
            z-index: 2;
        }

        .prev-btn {
            left: 0;
        }

        .next-btn {
            right: 0;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .services-carousel {
                flex-wrap: nowrap;
            }
        }

        @media (max-width: 992px) {
            .service-item {
                width: 25%;
            }
        }

        @media (max-width: 768px) {
            .service-item {
                width: 33.33%;
            }
        }

        @media (max-width: 576px) {
            .service-item {
                width: 100%;
            }
        }



        /* About Us Parallax */
        .about-us-parallax {
            background-attachment: fixed;
            background-size: cover;
            background-position: center;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
        }

        .content {
            max-width: 50%;
            margin: auto;
            background: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 10px;
        }

        .content p {
            font-size: <?php echo htmlspecialchars($aboutContent['aboutus_description_font_size']); ?>px;
            font-family: <?php echo htmlspecialchars($aboutContent['aboutus_description_font_family']) ?>;
            text-align: <?php echo htmlspecialchars($aboutContent['aboutus_description_align']) ?>;
        }

        .content h2 {
            font-size: <?php echo htmlspecialchars($aboutContent['aboutus_title_font_size']); ?>px;
            font-family: <?php echo htmlspecialchars($aboutContent['aboutus_title_font_family']) ?>;
            text-align: <?php echo htmlspecialchars($aboutContent['aboutus_title_align']) ?>;
        }
        .social-links{
            display: flex;
            justify-content: center;
        }
        .social-links a{
            text-decoration: none;
            color: #ddd;
        }

        /* Media Queries for Responsiveness */


        @media (max-width: 768px) {
            .content {
                max-width: 95%;
                margin: auto;
                background: rgba(0, 0, 0, 0.5);
                padding: 0;
                border-radius: 0;
            }

            .content h2 {
                font-size: 30px;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .hero h2 {
                font-size: 1.5rem;
            }

            .service-item {
                width: 90%;
            }
        }

        @media (max-width: 576px) {
            .hero h1 {
                font-size: 1.8rem;
            }

            .hero h2 {
                font-size: 1.2rem;
            }


            .contact-number {
                font-size: 20px;
                padding: 5px 10px;
                border: 1px solid #f9f9f9;
                border-radius: 15px;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="header-container">
            <!-- Logo -->
            <div class="logo">
                <img id="logo-image" src="<?php echo $header['image']; ?>" alt="Logo">
            </div>
            <!-- Navigation -->
            <nav aria-label="Main navigation" id="nav-menu">
                <ul>
                    <li><a href="#home">Home</a></li>
                    <li><a href="#services">Services</a></li>
                    <li><a href="#get-quote">Get a Quote</a></li>
                    <li><a href="#contact-us">Contact Us</a></li>
                </ul>
            </nav>
            <!-- Telephone Number -->
            <div class="contact">
                <p class="contact-number">
                    <a href="tel:<?php echo $contactDetails['contact_telephone']; ?>">
                        <i class="fas fa-phone-alt"></i>
                        <strong><?php echo $contactDetails['contact_telephone']; ?></strong>
                    </a>
                </p>
            </div>

        </div>
    </header>

    <!-- Hero Section -->
    <section id="home" class="hero">

        <div class="hero-content-section">
            <!-- Hero Title -->
            <h1>
                <?php echo htmlspecialchars($hero['hero_title']); ?>
            </h1>

            <!-- Subtitle (if available) -->
            <?php if (!empty($hero['hero_subtitle'])): ?>
                <h2>
                    <?php echo htmlspecialchars($hero['hero_subtitle']); ?>
                </h2>
            <?php endif; ?>

            <!-- Secondary Text (if available) -->
            <?php if (!empty($hero['hero_secondary_text'])): ?>
                <p><?php echo htmlspecialchars($hero['hero_secondary_text']); ?></p>
            <?php endif; ?>

            <!-- Quote Request Button (if enabled) -->
            <?php if ($hero['show_quote_button']): ?>
                <a href="#get-quote" class="btn">Request a Quote</a>
            <?php endif; ?>
        </div>


    </section>



    <!-- Services Section -->
    <section id="services">
        <h2>Our Services</h2>
        <div class="carousel-container">
            <button class="prev-btn" onclick="moveCarousel(-1)">&#10094;</button>
            <div class="carousel-wrapper">
                <div class="services-carousel">
                    <?php while ($service = mysqli_fetch_assoc($services)): ?>
                        <div class="service-item">
                            <!-- <img src="<?php echo $service['image']; ?>" alt="Service Image" class="service-icon"> -->
                            <h3><?php echo $service['title']; ?></h3>
                            <p><?php echo $service['description']; ?></p>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <button class="next-btn" onclick="moveCarousel(1)">&#10095;</button>
        </div>
    </section>


    <section id="about-us" class="about-us-parallax" style="background-image: url('<?php echo $aboutContent['image']; ?>');">
        <div class="content">
            <h2><?php echo htmlspecialchars($aboutContent['aboutus_title'], ENT_QUOTES, 'UTF-8'); ?></h2>
            <p><?php echo htmlspecialchars($aboutContent['aboutus_description_p1'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p><?php echo htmlspecialchars($aboutContent['aboutus_description_p2'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p><?php echo htmlspecialchars($aboutContent['aboutus_description_p3'], ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
    </section>

    <!-- Get a Quote Section -->
    <section id="get-quote" style="background-color: #f4f4f4; padding: 50px; border-radius: 10px; max-width: 600px; margin: auto;">
        <h2 style="text-align: center; font-size: 28px; color: #333; font-family: 'Arial', sans-serif; margin-bottom: 20px;">
            Request a Quote
        </h2>
        <form id="quote-form" action="getquote.php" method="POST" style="display: flex; flex-direction: column; gap: 15px;">
            <input type="text" name="name" placeholder="Your Name" required
                style="padding: 15px; font-size: 16px; border: 1px solid #ccc; border-radius: 5px; outline: none; width: 100%; box-sizing: border-box;">
            <input type="email" name="email" placeholder="Your Email" required
                style="padding: 15px; font-size: 16px; border: 1px solid #ccc; border-radius: 5px; outline: none; width: 100%; box-sizing: border-box;">
            <input type="tel" name="telephone" placeholder="Your Telephone" required
                style="padding: 15px; font-size: 16px; border: 1px solid #ccc; border-radius: 5px; outline: none; width: 100%; box-sizing: border-box;">
            <textarea name="details" placeholder="Service Details" required
                style="padding: 15px; font-size: 16px; border: 1px solid #ccc; border-radius: 5px; outline: none; width: 100%; box-sizing: border-box; resize: none; height: 150px;"></textarea>
            <button type="submit"
                style="padding: 15px; font-size: 18px; color: #fff; background-color: #007bff; border: none; border-radius: 5px; cursor: pointer; transition: background-color 0.3s;">
                Submit
            </button>
        </form>

    </section>

    <script>
        $(document).ready(function() {
            $('#quote-form').on('submit', function(event) {
                event.preventDefault(); // Prevent the default form submission

                $.ajax({
                    url: 'getquote.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#response-message').html('<p style="color: green;">' + response + '</p>');
                        $('#quote-form')[0].reset(); // Reset the form after successful submission
                    },
                    error: function(xhr, status, error) {
                        $('#response-message').html('<p style="color: red;">An error occurred. Please try again.</p>');
                    }
                });
            });
        });
    </script>


    <style>
        .contact-us {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: #f39c12;
        }

        .contact-us p {
            font-size: 34px;
        }

        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 20px;
            height: 300px;
            font-family: 'Arial', sans-serif;
        }

        footer a {
            text-decoration: none;
        }
    </style>

    <!-- Contact Us Section -->
    <section class="contact-us" id="contact-us">

        <h2>Contact Us</h2>
        <!-- <img src="images/contact.jpg" alt="Contact Us"> -->
        <p>Reach us at <?php echo $contactDetails['contact_telephone']; ?></br>
        Email us via <?php echo $contactDetails['contact_email']; ?></p>
    </section>


    <!-- Footer Section -->
    <footer>
        <?php
        // Get the domain name dynamically
        $siteName = $_SERVER['SERVER_NAME'];
        $currentYear = date('Y');
        ?>
        <p>© <?php echo $currentYear; ?> <?php echo $siteName; ?> All Rights Reserved.</p>
       
        <div class="footer-navigation">
                <ul>
                    <li><a href="#home">Home</a></li>
                    <li><a href="#services">Services</a></li>
                    <li><a href="#get-quote">Get a Quote</a></li>
                    <li><a href="#contact-us">Contact Us</a></li>
                </ul>
        </div>
        <div class="social-links">
        <p>Follow us on:
            <a href="<?php echo $socialLinks['facebook']; ?>">Facebook</a> |
            <a href="<?php echo $socialLinks['twitter']; ?>">Twitter</a> |
            <a href="<?php echo $socialLinks['instagram']; ?>">Instagram</a>
        </p>
        </div>

    </footer>
    <script>
        let currentIndex = 0;

        function moveCarousel(direction) {
            const carousel = document.querySelector('.services-carousel');
            const serviceItems = document.querySelectorAll('.service-item');
            const totalItems = serviceItems.length;
            const visibleItems = getVisibleItems(); // Determine how many items should be shown

            currentIndex += direction;

            // Ensure currentIndex is within bounds
            if (currentIndex < 0) {
                currentIndex = 0;
            } else if (currentIndex > totalItems - visibleItems) {
                currentIndex = totalItems - visibleItems;
            }

            const itemWidth = serviceItems[0].offsetWidth + 20; // 20px for margin
            carousel.style.transform = `translateX(-${currentIndex * itemWidth}px)`;
        }

        function getVisibleItems() {
            const screenWidth = window.innerWidth;
            if (screenWidth >= 1200) {
                return 5;
            } else if (screenWidth >= 992) {
                return 4;
            } else if (screenWidth >= 768) {
                return 3;
            } else {
                return 1;
            }
        }

        window.addEventListener('resize', () => {
            moveCarousel(0); // Reset carousel position on resize
        });
    </script>



</body>

</html>