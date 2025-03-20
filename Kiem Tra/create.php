<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $masv = $_POST['masv'];
    $hoten = $_POST['hoten'];
    $gioitinh = $_POST['gioitinh'];
    $ngaysinh = $_POST['ngaysinh'];
    $manganh = $_POST['manganh'];
    
    // Xử lý upload hình
    $hinh = '';
    if(isset($_FILES['hinh']) && $_FILES['hinh']['error'] == 0) {
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        // Tạo tên file ngắn hơn
        $extension = pathinfo($_FILES["hinh"]["name"], PATHINFO_EXTENSION);
        $hinh = $masv . '.' . $extension; // Sử dụng MaSV làm tên file
        $target_file = $target_dir . $hinh;
        
        // Xóa file cũ nếu tồn tại
        if(file_exists($target_file)) {
            unlink($target_file);
        }
        
        // Upload file mới
        if(move_uploaded_file($_FILES["hinh"]["tmp_name"], $target_file)) {
            $hinh = $target_file; // Lưu đường dẫn đầy đủ
        } else {
            $hinh = '';
        }
    }

    try {
        $stmt = $conn->prepare("INSERT INTO sinhvien (MaSV, HoTen, GioiTinh, NgaySinh, Hinh, MaNganh) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$masv, $hoten, $gioitinh, $ngaysinh, $hinh, $manganh]);
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
    <title>Thêm Sinh Viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>THÊM SINH VIÊN</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Mã SV</label>
                <input type="text" class="form-control" name="masv" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Họ Tên</label>
                <input type="text" class="form-control" name="hoten" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Giới Tính</label>
                <select class="form-control" name="gioitinh">
                    <option value="Nam">Nam</option>
                    <option value="Nữ">Nữ</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Ngày Sinh</label>
                <input type="date" class="form-control" name="ngaysinh" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Hình</label>
                <input type="file" class="form-control" name="hinh" accept="image/*">
                <small class="text-muted">Chỉ chấp nhận file hình ảnh</small>
            </div>
            <div class="mb-3">
                <label class="form-label">Mã Ngành</label>
                <input type="text" class="form-control" name="manganh" required>
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
            <a href="index.php" class="btn btn-secondary">Back to List</a>
        </form>
    </div>
</body>
</html> 