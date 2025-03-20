<?php
require_once 'config.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

// Lấy thông tin sinh viên
try {
    $stmt = $conn->prepare("SELECT * FROM sinhvien WHERE MaSV = ?");
    $stmt->execute([$id]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        header("Location: index.php");
        exit();
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}

// Xử lý khi form submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Xóa file hình nếu có
        if (!empty($student['Hinh']) && file_exists($student['Hinh'])) {
            unlink($student['Hinh']);
        }
        
        // Xóa record trong database
        $stmt = $conn->prepare("DELETE FROM sinhvien WHERE MaSV = ?");
        $stmt->execute([$id]);
        
        header("Location: index.php");
        exit();
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xóa Sinh Viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>XÓA THÔNG TIN</h2>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Are you sure you want to delete this?</h5>
                <div class="row">
                    <div class="col-md-4">
                        <?php if(!empty($student['Hinh'])): ?>
                            <img src="<?php echo htmlspecialchars($student['Hinh']); ?>" class="img-fluid rounded">
                        <?php endif; ?>
                    </div>
                    <div class="col-md-8">
                        <dl class="row">
                            <dt class="col-sm-3">Mã SV:</dt>
                            <dd class="col-sm-9"><?php echo htmlspecialchars($student['MaSV']); ?></dd>

                            <dt class="col-sm-3">Họ Tên:</dt>
                            <dd class="col-sm-9"><?php echo htmlspecialchars($student['HoTen']); ?></dd>

                            <dt class="col-sm-3">Giới Tính:</dt>
                            <dd class="col-sm-9"><?php echo htmlspecialchars($student['GioiTinh']); ?></dd>

                            <dt class="col-sm-3">Ngày Sinh:</dt>
                            <dd class="col-sm-9"><?php echo htmlspecialchars($student['NgaySinh']); ?></dd>

                            <dt class="col-sm-3">Mã Ngành:</dt>
                            <dd class="col-sm-9"><?php echo htmlspecialchars($student['MaNganh']); ?></dd>
                        </dl>
                    </div>
                </div>
                
                <form method="POST" class="mt-3">
                    <button type="submit" class="btn btn-danger">Delete</button>
                    <a href="index.php" class="btn btn-secondary">Back to List</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html> 