<?php
require_once 'config.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

try {
    // Lấy thông tin chi tiết sinh viên
    $stmt = $conn->prepare("SELECT * FROM sinhvien WHERE MaSV = ?");
    $stmt->execute([$id]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        header("Location: index.php");
        exit();
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông Tin Chi Tiết Sinh Viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>THÔNG TIN CHI TIẾT</h2>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <?php if(!empty($student['Hinh'])): ?>
                            <img src="<?php echo htmlspecialchars($student['Hinh']); ?>" class="img-fluid rounded">
                        <?php else: ?>
                            <p>Không có hình</p>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-8">
                        <table class="table">
                            <tr>
                                <th>Mã SV:</th>
                                <td><?php echo htmlspecialchars($student['MaSV']); ?></td>
                            </tr>
                            <tr>
                                <th>Họ Tên:</th>
                                <td><?php echo htmlspecialchars($student['HoTen']); ?></td>
                            </tr>
                            <tr>
                                <th>Giới Tính:</th>
                                <td><?php echo htmlspecialchars($student['GioiTinh']); ?></td>
                            </tr>
                            <tr>
                                <th>Ngày Sinh:</th>
                                <td><?php echo htmlspecialchars($student['NgaySinh']); ?></td>
                            </tr>
                            <tr>
                                <th>Mã Ngành:</th>
                                <td><?php echo htmlspecialchars($student['MaNganh']); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-3">
            <a href="index.php" class="btn btn-secondary">Back to List</a>
            <a href="edit.php?id=<?php echo htmlspecialchars($student['MaSV']); ?>" class="btn btn-warning">Edit</a>
        </div>
    </div>
</body>
</html> 