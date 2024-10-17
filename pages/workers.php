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
    <form class="filter-form" action="">
    <div class="categories">
        <div class="skills">
            <p>Skills</p>
            <details>
                <summary>Communication</summary>
                <label><input type="radio" name="skill-radiobtn" value="Actor"> Actor</label>
                <label><input type="radio" name="skill-radiobtn" value="Manager"> Manager</label>
                <label><input type="radio" name="skill-radiobtn" value="Customer"> Customer</label>
                <label><input type="radio" name="skill-radiobtn" value="Consultant"> Consultant service</label>
            </details>
            <details>
                <summary>Creative</summary>
                <label><input type="radio" name="skill-radiobtn" value="Designer"> Designer</label>
                <label><input type="radio" name="skill-radiobtn" value="Artist"> Artist</label>
            </details>
            <details>
                <summary>Labor</summary>
                <label><input type="radio" name="skill-radiobtn" value="Construction"> Construction</label>
                <label><input type="radio" name="skill-radiobtn" value="Mechanic"> Mechanic</label>
            </details>
            <details>
                <summary>Professional</summary>
                <label><input type="radio" name="skill-radiobtn" value="Lawyer"> Lawyer</label>
                <label><input type="radio" name="skill-radiobtn" value="Doctor"> Doctor</label>
            </details>
            <details>
                <summary>Technical</summary>
                <label><input type="radio" name="skill-radiobtn" value="Engineer"> Engineer</label>
                <label><input type="radio" name="skill-radiobtn" value="IT Specialist"> IT Specialist</label>
            </details>
        </div>

        <div class="educ-attainment">
            <p>Educational Attainment</p>
            
                <label><input type="radio" name="education" value="High School Diploma"> High School Diploma</label>
            
                <label><input type="radio" name="education" value="Bachelor's Degree"> Bachelor's Degree</label>
            
                <label><input type="radio" name="education" value="Master's Degree"> Master's Degree</label>
            
                <label><input type="radio" name="education" value="PhD"> PhD</label>
            
        </div>

        <div class="sex">
            <p>Sex</p>
                <label><input type="radio" name="sex" value="Male"> Male</label>
                <label><input type="radio" name="sex" value="Female"> Female</label>
        </div>
    </div>

        <button class="apply-btn">Apply Filter</button>
    </form>
   
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

<script>
    var filter_box = document.querySelector(".filter-box");
    var filter_button = document.querySelector("#filter-button");

    filter_button.addEventListener("click", function(e) {
        e.preventDefault(); // Prevent the default button action
        if (filter_box.style.display === "block") {
            filter_box.style.display = "none"; // Hide the filter box
        } else {
            filter_box.style.display = "block"; // Show the filter box
        }
    });
</script>


</body>
</html>