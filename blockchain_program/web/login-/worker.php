<!DOCTYPE html>
<html>
<head>
    <title>顯示日期</title>
</head>
<body>
    <h1>顯示日期</h1>
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    <table>
        <tr>
            <th></th>
            <th>Date</th>
            <th>Address</th>
        </tr>
    <?php
        // 連接 MySQL 資料庫
        $conn = mysqli_connect("127.0.0.1", "root", "20011005", "maxdb");
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
            echo "<tr><td><input type='checkbox' name='delete[]' value='" . $row["date_column"] . "," . $row["address"] . "'></td><td>" . $row["date_column"]. "</td><td>" . $row["address"]. "</td></tr>";
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
        $conn = mysqli_connect("127.0.0.1", "root", "20011005", "maxdb");
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
                echo "<script>window.location.href='worker.php';</script>";
            } else {
                echo "Error deleting record: " . mysqli_error($conn);
            }
        }
        mysqli_close($conn);
    }
?>
</body>
</html>