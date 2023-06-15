<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <link
      href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap"
      rel="stylesheet"
    />
    <link rel="icon" type="image/x-icon" href="favicon.ico" />
    <link rel="stylesheet" href="fonts/icomoon/style.css" />

    <link rel="stylesheet" href="css/owl.carousel.min.css" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css" />

    <!-- Style -->
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/lstyle.css"/>
    
    <title>BlockSecure</title>
  </head>
  <body>
  <nav class="navbar navbar-expand-lg navbar-light" id="mainNav">
            <div class="container px-4 px-lg-5">
                <a class="navbar-brand" href="http://127.0.0.1/b/index.php">區塊鏈智能鎖</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    Menu
                    <i class="fas fa-bars"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ms-auto py-4 py-lg-0">                        
                        <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="../hotel/index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="../login-/index.php">Login</a></li>
                        <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="../hotel/transfer.php">解鎖記錄</a></li>
                      </ul>
                </div>
            </div>
        </nav>
    <?php
        // 檢查是否有提交表單
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // 取得輸入的帳號密碼
            $username = $_POST["username"];
            $password = $_POST["password"];
            
            // 連接 MySQL 資料庫
            $conn = mysqli_connect("127.0.0.1", "root", "123456789", "maxdb");
            if (!$conn) {
                die("資料庫連接失敗：" . mysqli_connect_error());
            }
            
            // 查詢資料庫中是否有對應的使用者帳號密碼
            $sql = "SELECT * FROM user_accounts WHERE username='$username' AND password='$password'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                // 登入成功，跳轉到 welcome.php
                header("Location: ../hotel/post.php");
                exit(); // 結束程式碼執行，確保跳轉正常進行
            } else {
                // 登入失敗

                echo "<script>alert('登入失敗，請檢查帳號密碼是否正確。');</script>";
                //echo "<h2>登入失敗，請檢查帳號密碼是否正確。</h2>";
            }
            
            // 關閉資料庫連接
            mysqli_close($conn);
        }
    ?>
    <div class="d-lg-flex half">
      <div
        class="bg order-1 order-md-2"
        style="background-image: url('images/test3.jpg')"
      ></div>
      <div class="contents order-2 order-md-1">
        <div class="container">
          <div class="row align-items-center justify-content-center">
            <div class="col-md-7">
              <h3>區塊鏈智能鎖</h3>
              <p class="mb-4">
                飯店人員登入
              </p>
              <form action="#" method="post">
                <div class="form-group first">
                  <label for="username">Username</label>
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Your Username"
                    name="username"
                  />
                </div>
                <div class="form-group last mb-3">
                  <label for="password">Password</label>
                  <input
                    type="password"
                    class="form-control"
                    placeholder="Your Password"
                    name="password"
                  />
                </div>

                <div class="d-flex mb-5 align-items-center">
                  <label class="control control--checkbox mb-0"
                    ><span class="caption">Remember me</span>
                    <input type="checkbox" checked="checked" />
                    <div class="control__indicator"></div>
                  </label>
                  <span class="ml-auto"
                    ><a href="#" class="forgot-pass"></a></span
                  >
                </div>

                <input
                  type="submit"
                  value="Log In"
                  class="btn btn-block btn-primary"
                />
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
  </body>
</html>
