
<body>
   

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Latest News</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="./assets/css/style.css">
  <link rel="stylesheet" href="./assets/css/spapp.css">
  <script src="./assets/js/jquery.min.js"></script>
  <script src="./assets/js/jquery.spapp.min.js"></script>
  <script src="./assets/js/script.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="#view_home">Latest News</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item active">
          <a class="nav-link" href="#view_home">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#view_profile">Profile</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#view_login">Login</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#view_signup">Sign Up</a>
        </li>
      </ul>
    </div>
  </nav>

  <section id="pageContent">
    <main id="spapp" role="main">
      <section id="view_home" data-load="home.html">
        <h1>Page 2</h1>
      </section>
      <section id="view_login" data-load="login.html">
        <h1>Page 4</h1>
      </section>
      <section id="view_profile" data-load="profile.html">
        <h1>Page 5</h1>
      </section>
      <section id="view_signup" data-load="signup.html">
        <h1>Page 6</h1>
      </section>
      <section id="view_article" data-load="article.html">
        <h1>Page 7</h1>
      </section>
    </main>
  </section>

  <footer class="footer text-center py-4">
    <span>© 2024 Latest News. All rights reserved.</span>
  </footer>




</body>


    <script>
        window.location.hash = "#view_home";
    </script>
</body>
</html>
