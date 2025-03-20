<?php
require_once 'config.php';
$page_title = 'Đăng Kí Học Phần';
require_once 'header.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['masv'])) {
    header("Location: dangnhap.php");
    exit();
}

$masv = $_SESSION['masv'];
$error = '';
$success = '';

// Xử lý xóa đăng ký
if (isset($_POST['action']) && $_POST['action'] == 'delete' && isset($_POST['mahp'])) {
    try {
        $stmt = $conn->prepare("DELETE FROM dangky WHERE MaSV = ? AND MaHp = ?");
        $stmt->execute([$masv, $_POST['mahp']]);
        $success = "Đã xóa đăng ký học phần thành công!";
    } catch(PDOException $e) {
        $error = "Lỗi khi xóa đăng ký: " . $e->getMessage();
    }
}

// Xử lý xóa tất cả đăng ký
if (isset($_POST['action']) && $_POST['action'] == 'delete_all') {
    try {
        $stmt = $conn->prepare("DELETE FROM dangky WHERE MaSV = ?");
        $stmt->execute([$masv]);
        $success = "Đã xóa tất cả đăng ký học phần!";
    } catch(PDOException $e) {
        $error = "Lỗi khi xóa đăng ký: " . $e->getMessage();
    }
}

// Lấy danh sách học phần đã đăng ký
try {
    $stmt = $conn->prepare("
        SELECT d.*, h.TenHP, h.SoTinChi 
        FROM dangky d 
        JOIN hocphan h ON d.MaHp = h.MaHp 
        WHERE d.MaSV = ?
    ");
    $stmt->execute([$masv]);
    $registrations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Lỗi: " . $e->getMessage();
}
?>

<div class="container">
    <h1>ĐĂNG KÍ HỌC PHẦN</h1>

    <?php if ($error): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?php echo $success; ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header bg-dark text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Danh sách học phần đã đăng ký</h5>
                <?php if (!empty($registrations)): ?>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="delete_all">
                        <button type="submit" class="btn btn-danger btn-sm" 
                                onclick="return confirm('Bạn có chắc muốn xóa tất cả đăng ký không?')">
                            <i class="fas fa-trash"></i> Xóa tất cả đăng ký
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body">
            <?php if (!empty($registrations)): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Mã HP</th>
                                <th>Tên Học Phần</th>
                                <th>Số Tín Chỉ</th>
                                <th>Ngày ĐK</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($registrations as $reg): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($reg['MaHp']); ?></td>
                                    <td><?php echo htmlspecialchars($reg['TenHP']); ?></td>
                                    <td><?php echo htmlspecialchars($reg['SoTinChi']); ?></td>
                                    <td><?php echo htmlspecialchars($reg['NgayDK']); ?></td>
                                    <td>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="mahp" value="<?php echo htmlspecialchars($reg['MaHp']); ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" 
                                                    onclick="return confirm('Bạn có chắc muốn xóa đăng ký này không?')">
                                                <i class="fas fa-trash"></i> Xóa
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Bạn chưa đăng ký học phần nào.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-3">
        <a href="hocphan.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Đăng ký thêm học phần
        </a>
    </div>
</div>

</body>
</html> 