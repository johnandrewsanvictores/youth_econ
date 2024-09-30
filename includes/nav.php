<nav>
  <input type="checkbox" id="check">
  <label for="check" class="checkbtn">
    <i class="fas fa-bars"></i>
  </label>
  <a href="home.php" id="logo"><img src="../public/images/logo.jpg" alt="sk logo"></a>
  <ul>
    <li><a class="active" href="home.php">Home</a></li>
    <li><a href="about.php">About</a></li>
    <li><a href="workers.php">Worker</a></li>
    <li><a href="business.php">Business</a></li>
    <li><a href="gallery.php">Gallery</a></li>
  </ul>
</nav>

<style>
  * {
    padding: 0;
    margin: 0;
    text-decoration: none;
    list-style: none;
    box-sizing: border-box;
  }

  body {
    font-family: "Montserrat", sans-serif;
  }

  nav {
    background: var(--bg-white);
    height: 80px;
    width: 100%;
    position: relative;
    display: flex;
    justify-content: center;
    position: fixed;
  }

  #logo {
    width: 3.5em;
    height: 3.5em;
    position: absolute;
    top: 50%;
    left: 10px;
    transform: translateY(-50%);
  }

  #logo>img {
    width: 3.5em;
    height: 3.5em;
    border-radius: 50%;
  }

  nav ul {
    /* float: right; */
    margin-right: 20px;
  }

  nav ul li {
    display: inline-block;
    line-height: 80px;
    margin: 0 5px;
  }

  nav ul li a {
    color: var(--font-dark);
    font-size: 17px;
    padding: 7px 13px;
    border-radius: 3px;
    text-transform: uppercase;
  }

  nav ul li a.active,
  nav ul li a:hover {
    background: var(--tertiary);
    transition: .5s;
  }

  .checkbtn {
    font-size: 30px;
    color: white;
    float: right;
    line-height: 80px;
    margin-right: 40px;
    cursor: pointer;
    display: none;
  }

  #check {
    display: none;
  }

  @media (max-width: 952px) {
    label.logo {
      font-size: 30px;
      padding-left: 50px;
    }

    nav ul li a {
      font-size: 16px;
    }
  }

  @media (max-width: 858px) {
    .checkbtn {
      display: block;
    }

    nav {
      background: var(--primary);
      justify-content: flex-end;
    }


    ul {
      position: fixed;
      width: 100%;
      height: 100vh;
      background: var(--dark-primary);
      top: 80px;
      left: -100%;
      text-align: center;
      transition: all .5s;
    }

    nav ul li {
      display: block;
      margin: 50px 0;
      line-height: 30px;
    }

    nav ul li a {
      font-size: 20px;
      color: var(--font-white);
    }

    nav ul li a:hover,
    nav ul li a.active {
      background: none;
      color: var(--tertiary);
    }

    #check:checked~ul {
      left: 0;
    }
  }
</style>