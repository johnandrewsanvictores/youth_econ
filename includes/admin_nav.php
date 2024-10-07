<header>
    <h5>Admin Panel</h5>

    <input type="checkbox" id="check">
    <label for="check" class="checkbtn">
        <i class="fas fa-bars"></i>
    </label>

    <nav role="navigation" class="primary-navigation">
        <div class="user-container user-mobile">
            <img src="../images/logo.jpg" alt="">
            <div class="user-info-mobile">Admin &dtrif;
                <ul class="dropdown">
                    <li><a href="#">Change Password</a></li>
                    <li><a href="#">Log Out</a></li>
                </ul>
            </div>

            <div class="line"></div>
        </div>
        <ul>
            <li id="active-nav"><a href="#">Dashboard</a></li>
            <li><a href="#"><span class="mul-navlinks">Business &dtrif;</span></a>
                <ul class="dropdown">
                    <li><a href="business.php">List of Business</a></li>
                    <li><a href="#">Product Gallery</a></li>
                </ul>
            </li>
            <li><a href="#">Worker</a></li>
            <li><a href="#">Events</a></li>
        </ul>
    </nav>

    <div class="user-container">
        <img src="../images/logo.jpg" alt="">
        <div class="user-info">Admin &dtrif;
            <ul class="dropdown">
                <li><a href="#">Change Password</a></li>
                <li><a href="#">Log Out</a></li>
            </ul>
        </div>
    </div>
</header>



<style>
    header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1em 2em;
        background-color: var(--primary);
        color: var(--font-white);
        font-size: var(--body);
    }

    #active-nav {
        color: var(--tertiary);
        padding-bottom: 5px;
        border-bottom: 2px solid var(--tertiary);
    }

    .user-container {
        display: flex;
        gap: 0.5em;
        align-items: center;
        -webkit-user-select: none;
        /* Safari */
        -ms-user-select: none;
        /* IE 10 and IE 11 */
        user-select: none;
        /* Standard syntax */
    }

    .user-container div {
        cursor: pointer;
        position: relative;
    }

    .user-container .clicked {
        visibility: visible;
        opacity: 1;
        text-align: left;
        margin-left: 30px;
        padding-top: 20px;
        box-shadow: 0px 3px 5px -1px #ccc;
        z-index: 999;
        top: 30px;
        color: var(--font-dark);
    }

    .user-container div ul {
        position: absolute;
        padding-left: 0;
        right: 0;
        background: white;
        min-width: 10em;
        color: var(--font-dark);
        visibility: hidden;
        opacity: 0;
        display: none;

        border-radius: 5px;
        padding: 1.5em 1em;
        box-shadow: 0px 3px 5px -1px #ccc;
        display: flex;
        flex-direction: column;
        gap: 0.5em;
        align-items: flex-end;
        margin-top: 2px;
    }

    .user-container img {
        width: 3em;
        aspect-ratio: 1/1;
        border-radius: 50%;
        border: 2px solid var(--tertiary);
    }

    nav.primary-navigation {
        display: block;
    }

    nav.primary-navigation ul li {
        list-style: none;
        margin: 0 auto;
        display: inline-block;
        padding: 0 20px;
        position: relative;
        text-decoration: none;
        text-align: center;
        font-family: arvo;
    }

    nav.primary-navigation ul li a:hover {
        color: var(--tertiary);
    }

    nav.primary-navigation ul li:hover {
        cursor: pointer;
    }

    nav.primary-navigation ul li ul {
        visibility: hidden;
        opacity: 0;
        display: none;
        position: absolute;
        padding-left: 0;
        left: 0;
        background: white;
    }

    nav.primary-navigation ul li:hover>ul,
    nav.primary-navigation ul li ul:hover {
        visibility: visible;
        opacity: 1;
        display: block;
        min-width: 250px;
        text-align: left;
        margin-left: 30px;
        padding-top: 20px;
        box-shadow: 0px 3px 5px -1px #ccc;
    }

    nav.primary-navigation ul li ul li {
        clear: both;
        width: 100%;
        text-align: left;
        margin-bottom: 20px;
        border-style: none;
        color: var(--font-dark);
    }

    nav.primary-navigation ul li ul li a:hover {
        padding-left: 10px;
        border-left: 2px solid #3ca0e7;
        transition: all 0.3s ease;
    }

    .user-container a {
        text-decoration: none;
    }

    .user-container a:hover {
        color: var(--tertiary);
    }

    nav.primary-navigation ul li ul li a {
        transition: all 0.5s ease;
    }



    .checkbtn {
        font-size: 30px;
        color: white;
        cursor: pointer;
        display: none;
    }

    #check {
        display: none;
    }


    .user-mobile {
        visibility: hidden;
        display: none;
    }



    @media (max-width: 948px) {
        .user-container {
            visibility: hidden;
            display: none;
        }

        .user-mobile {
            visibility: visible;
            display: flex;
            justify-content: center;
            flex-direction: column;
            gap: 0.5em;
            align-items: center;
            font-size: var(--body);
        }

        .user-mobile .line {
            margin-top: 10px;
            width: 90%;
            height: 2px;
            background-color: var(--primary);
        }

        .user-mobile .dropdown {
            min-width: 12em;
            padding: 1em;
            font-size: var(--small);
            left: -30px;

        }

        .user-mobile .dropdown li {
            padding: 0;
        }

        /* .user-mobile .dropdown {
            display: block;
            opacity: 1;
            visibility: visible;
            left: 0;

        } */

        header {
            position: relative;
            height: 80px;
        }

        nav {
            background: var(--dark-primary);
            position: fixed;
            left: -100%;
            top: 80px;
            width: 100vw;
            height: 100%;
            transition: all 0.3s ease-out;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-s;
            padding: 1.5em;
            font-size: var(--h6-desktop);
        }

        nav>ul {
            margin-top: 3em;
            gap: 2em;
            display: flex;
            flex-direction: column;
        }

        nav ul li {
            margin: 0 auto;
            text-decoration: none;
            text-align: center;
            font-family: var(--font-body);
            display: relative;
        }

        nav.primary-navigation ul li ul {
            visibility: visible;
            opacity: 1;
            display: flex;
            flex-direction: column;
            position: relative;
            margin-top: 10px;
            background: transparent;
            gap: 2em;
        }

        .mul-navlinks {
            color: var(--font-light-grey);
            display: none;
        }

        nav.primary-navigation ul li ul li {
            clear: both;
            width: 100%;
            text-align: center;
            border-style: none;
            color: var(--font-white);
            margin-bottom: 0;
        }

        .checkbtn {
            display: block;
        }

        #check:checked~nav {
            left: 0;
        }
    }
</style>

<script>
    var user_info = document.querySelector('.user-container .user-info');

    user_info.addEventListener('click', () => {
        user_info.querySelector('.dropdown').classList.toggle('clicked');
    })

    var user_info_mobile = document.querySelector('.user-container .user-info-mobile');

    user_info_mobile.addEventListener('click', () => {
        user_info_mobile.querySelector('.dropdown').classList.toggle('clicked');
    })
</script>