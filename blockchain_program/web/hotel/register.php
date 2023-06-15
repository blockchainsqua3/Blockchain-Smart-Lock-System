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
  $date = $_POST["date"];
  $address = $_POST["address"];

  // 檢查日期是否重複
  $query = "SELECT COUNT(*) FROM Test_table11 WHERE date_column = '$date'";
  $result = mysqli_query($connection, $query);
  $count = mysqli_fetch_array($result)[0];
  if ($count > 0) {
    echo "日期重複，請重新輸入。";
  }

  // 檢查以太訪地址格式是否正確
  else if (!preg_match("/^(0x)?[0-9a-fA-F]{40}$/", $address)) {
    echo "以太坊地址格式錯誤，請重新輸入。";
  }

  // 如果都沒問題，則將日期與地址存入資料庫
  else {
    $query = "INSERT INTO Test_table11 (date_column, address) VALUES ('$date', '$address')";
    mysqli_query($connection, $query);
    echo "成功新增一筆資料。";
  }
}

// 關閉資料庫連線
mysqli_close($connection);
?>

<!-- HTML 表單 -->
<html>
<head>
    <title>存入日期</title>
</head>
<body>
    <h1>存入日期</h1>
<form method="post">
  <label for="date">日期：</label>
  <input type="date" id="date" name="date" required><br>

  <label for="address">以太坊地址：</label>
  <input type="text" id="address" name="address" required><br>

  <input type="submit" value="送出">
</form>
</body>
