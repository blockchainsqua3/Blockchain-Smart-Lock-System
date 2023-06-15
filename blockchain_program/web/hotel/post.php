<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>BlockSecure</title>
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800" rel="stylesheet" type="text/css" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
        <?php
        // 連接 MySQL 資料庫
        $connection = mysqli_connect("127.0.0.1", "root", "123456789", "maxdb");

        // 檢查連線是否成功
        if (mysqli_connect_errno()) {
        die("Database connection failed: " .
            mysqli_connect_error() .
            " (" . mysqli_connect_errno() . ")"
        );
        }

        // 處理表單提交
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // 取得使用者輸入的日期和地址
        $start_date = $_POST["start_date"];
        $end_date = $_POST["end_date"];

        // 計算日期差距
        $datetime1 = date_create($start_date);
        $datetime2 = date_create($end_date);
        $interval = date_diff($datetime1, $datetime2);
        $days = $interval->format('%a');

        // 檢查是否有重複日期
        $duplicate_dates = array();
        for ($i = 0; $i < $days; $i++) {
            $date = date('Y-m-d', strtotime($start_date . ' + ' . $i . ' day'));

            $query = "SELECT COUNT(*) FROM Test_table11 WHERE date_column = '$date'";
            $result = mysqli_query($connection, $query);
            $count = mysqli_fetch_array($result)[0];

            if ($count > 0) {
            array_push($duplicate_dates, $date);
            }
        }

        // 如果有重複日期，則不儲存任何資料
        // 如果有重複日期，則顯示提醒並提示修改
            if (!empty($duplicate_dates)) {
                $duplicate_dates_str = implode(", ", $duplicate_dates);
                echo "<script>
                var confirmMsg = '以下日期已存在資料庫中：$duplicate_dates_str\\n請確認是否要修改？';
                if (confirm(confirmMsg)) {
                    window.location.href = window.location.href;
                }
                else {
                    window.history.back();
                }
                </script>";
                exit();
            }
            

        // 如果沒有重複日期，則逐一儲存經過日期的資料
        else {
            for ($i = 0; $i < $days; $i++) {
            $date = date('Y-m-d', strtotime($start_date . ' + ' . $i . ' day'));
            $address = $_POST["address"];

            // 檢查以太訪地址格式是否正確
            if (!preg_match("/^(0x)?[0-9a-fA-F]{40}$/", $address)) {
                echo "<script>alert('以太坊地址格式錯誤，請重新輸入。');</script>";
            }

            // 如果都沒問題，則將日期與地址存入資料庫
            else {
                $query = "INSERT INTO Test_table11 (date_column, address) VALUES ('$date', '$address')";
                mysqli_query($connection, $query);
                echo "<script>alert('成功新增日期 $date 的資料。');</script>";
            }
            }

            #echo "所有資料儲存完成。";
        }
        }

        // 關閉資料庫連線
        mysqli_close($connection);
        ?>
    </head>
    <body>
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light" id="mainNav">
            <div class="container px-4 px-lg-5">
                <a class="navbar-brand" href="index.php">區塊鏈智能鎖</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    Menu
                    <i class="fas fa-bars"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ms-auto py-4 py-lg-0">
                        <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="../login-/index.php">Login</a></li>
                        <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="transfer.php">解鎖記錄</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Page Header-->
        <header class="masthead" style="background-image: url('assets/img/test.jpg')">
            <div class="container position-relative px-4 px-lg-5">
                <div class="row gx-4 gx-lg-5 justify-content-center">
                    <div class="col-md-10 col-lg-8 col-xl-7">
                        <div class="post-heading">
                            <h1>區塊鏈住房系統</h1>
                            <h2 class="subheading">最佳的住房保障</h2>
                            <span class="meta">
                                
                                <a href="#!">To travel is to live.</a>
                                
                                
                                <pre>               - Hans Christian Andersen</pre>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Post Content-->
        <article class="mb-4">
            <div class="container px-4 px-lg-5">
                <div class="row gx-4 gx-lg-5 justify-content-center">
                    <div class="col-md-10 col-lg-8 col-xl-7">
                        
                        <p><h1>新增訂房</h1>
<form method="post">
    <label for="start_date">入住日期：</label>
    <input type="date" id="start_date" name="start_date" required><br>

    <label for="end_date">退房日期：</label>
    <input type="date" id="end_date" name="end_date" required><br>

    <label for="address">以太坊地址：</label>
    <input type="text" id="address" name="address" required><br>

    <input type="submit" value="送出">
  </form><br><br>
                            <h1>刪除訂房</h1>
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    <table>
        <tr>
            <th></th>
            <th>Date</th>
            <th>Address</th>
        </tr>
    <?php
        // 連接 MySQL 資料庫
        $conn = mysqli_connect("127.0.0.1", "root", "123456789", "maxdb");
        // 檢查連線是否成功
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    // 從 Test_table11 資料表讀取 date_column 和 address 欄位，並按日期由遠到近排序
    $sql = "SELECT date_column, address FROM Test_table11 ORDER BY date_column DESC";
    $result = mysqli_query($conn, $sql);

    // 如果有資料，則顯示在網頁上
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr><td><input type='checkbox' name='delete[]' value='" . $row["date_column"] . "," . $row["address"] . "'>&nbsp;&nbsp;&nbsp;</td><td>" . $row["date_column"]. "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>" . $row["address"]. "&nbsp;</td></tr>";
        }
    } else {
        echo "0 results";
    }

    // 關閉資料庫連接
    mysqli_close($conn);
?>
</table>
<br>
<input type="submit" name="submit" value="刪除所選資料">
</form>
<?php
    // 如果有提交刪除表單，則執行刪除操作
    if(isset($_POST["submit"])) {
        $conn = mysqli_connect("127.0.0.1", "root", "123456789", "maxdb");
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        foreach ($_POST["delete"] as $value) {
            $arr = explode(",", $value);
            $date = $arr[0];
            $address = $arr[1];
            $sql = "DELETE FROM Test_table11 WHERE date_column='$date' AND address='$address'";
            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('刪除成功');</script>";
                echo "<script>window.location.href='post.php';</script>";
            } else {
                echo "Error deleting record: " . mysqli_error($conn);
            }
        }
        mysqli_close($conn);
    }
?></p>
                        
                            
                            
                        </p>
                    </div>
                </div>
            </div>
        </article>
        <!-- Footer-->
        <footer class="border-top">
            <div class="container px-4 px-lg-5">
                <div class="row gx-4 gx-lg-5 justify-content-center">
                    <div class="col-md-10 col-lg-8 col-xl-7">
                        <ul class="list-inline text-center">
                            <li class="list-inline-item">
                                
                            </li>
                            <li class="list-inline-item">
                                
                            </li>
                            <li class="list-inline-item">
                                
                            </li>
                        </ul>
                        <div class="small text-center text-muted fst-italic">Copyright &copy; BlockSecure 2023</div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>
