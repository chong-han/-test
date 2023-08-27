<?php
// 填写你的数据库连接信息
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vlink";

// 创建数据库连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接是否成功
if ($conn->connect_error) {
    die("数据库连接失败: " . $conn->connect_error);
}

// 查询图像数据
$sql = "SELECT image, type FROM users_photo WHERE user_id  = ?"; // 假设有一个 "id" 列
$user_id  = 'a00001'; // 根据你的数据更改 id
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$stmt->bind_result($imageData, $imageType);
$stmt->fetch();
$stmt->close();

// 关闭数据库连接
$conn->close();

// 设置 HTTP 标头
header("Content-type: $imageType");
echo $imageData;
