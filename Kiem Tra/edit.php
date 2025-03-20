<?php
require_once 'config.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

// Lấy thông tin sinh viên
$stmt = $conn->prepare("SELECT * FROM sinhvien WHERE MaSV = ?");
$stmt->execute([$id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hoten = $_POST['hoten'];
    $gioitinh = $_POST['gioitinh'];
    $ngaysinh = $_POST['ngaysinh'];
    $manganh = $_POST['manganh'];
    
    // Xử lý upload hình mới
    $hinh = $student['Hinh'];
    if(isset($_FILES['hinh']) && $_FILES['hinh']['error'] == 0) {
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $hinh = time() . '_' . basename($_FILES["hinh"]["name"]);
        $target_file = $target_dir . $hinh;
        move_uploaded_file($_FILES["hinh"]["tmp_name"], $target_file);
        $hinh = $target_file;
    }

    try {
        $stmt = $conn->prepare("UPDATE sinhvien SET HoTen = ?, GioiTinh = ?, NgaySinh = ?, Hinh = ?, MaNganh = ? WHERE MaSV = ?");
        $stmt->execute([$hoten, $gioitinh, $ngaysinh, $hinh, $manganh, $id]);
        header("Location: index.php");
        exit();
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Thông Tin Sinh Viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>HIỆU CHỈNH THÔNG TIN SINH VIÊN</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Mã SV</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($student['MaSV']); ?>" disabled>
            </div>
            <div class="mb-3">
                <label class="form-label">Họ Tên</label>
                <input type="text" class="form-control" name="hoten" value="<?php echo htmlspecialchars($student['HoTen']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Giới Tính</label>
                <select class="form-control" name="gioitinh">
                    <option value="Nam" <?php echo $student['GioiTinh'] == 'Nam' ? 'selected' : ''; ?>>Nam</option>
                    <option value="Nữ" <?php echo $student['GioiTinh'] == 'Nữ' ? 'selected' : ''; ?>>Nữ</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Ngày Sinh</label>
                <input type="date" class="form-control" name="ngaysinh" value="<?php echo htmlspecialchars($student['NgaySinh']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Hình Hiện Tại</label>
                <?php if($student['Hinh']): ?>
                    <img src="<?php echo htmlspecialchars($student['Hinh']); ?>" style="width: 100px; height: 100px; object-fit: cover;">
                <?php endif; ?>
                <input type="file" class="form-control mt-2" name="hinh">
            </div>
            <div class="mb-3">
                <label class="form-label">Mã Ngành</label>
                <input type="text" class="form-control" name="manganh" value="<?php echo htmlspecialchars($student['MaNganh']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="index.php" class="btn btn-secondary">Back to List</a>
        </form>
    </div>
</body>
</html> 