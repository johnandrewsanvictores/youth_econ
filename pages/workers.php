<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="../global.css">
    <link rel="stylesheet" href="../public/css/workers.css">


    <title>Workers</title>

</head>
<body>
    
    <?php
    include('../includes/nav.php');
    ?>

    <section class="main_container">

    <div class="head">

    <div class="title">

    <h2>Workers</h2>
    <p id="p_title">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>

    </div>

    
    <div class="function">

    <form id ="searchFilter" action="">

    <div class="search-container">
    <input type="search" id="searchbar" placeholder="Search..">
    <button><i class="fas fa-search"></i></button>
    </div>

    <div class="filter">
    <button id="filter-button">Filter</button>

    <div class="filter-box">
    
    </div>


    </div>

    </form>


    </div>

    </div>

    <div class="body">

    <?php
    include('../includes/worker_card.php');
    include('../includes/worker_card.php');
    include('../includes/worker_card.php');
    include('../includes/worker_card.php');
    include('../includes/worker_card.php');
    include('../includes/worker_card.php');
    ?>



    </div>


    </section>

    <?php
    include('../includes/footer.php');
    ?>



</body>
</html>