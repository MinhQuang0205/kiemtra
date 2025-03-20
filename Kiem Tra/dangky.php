<?php
require_once 'config.php';
require_once 'header.php';

$page_title = 'Danh sách đăng ký học phần';
$error_message = '';
$success_message = '';

// Delete all registrations
if (isset($_POST['delete_all'])) {
    try {
        $delete_sql = "DELETE FROM dangky";
        if ($conn->exec($delete_sql) !== false) {
            $success_message = "Đã xóa tất cả đăng ký thành công";
        }
    } catch(PDOException $e) {
        $error_message = "Lỗi khi xóa: " . $e->getMessage();
    }
}

// Delete single registration
if (isset($_GET['delete_id'])) {
    try {
        $delete_sql = "DELETE FROM dangky WHERE MaHp = ?";
        $stmt = $conn->prepare($delete_sql);
        if ($stmt->execute([$_GET['delete_id']])) {
            $success_message = "Đã xóa đăng ký thành công";
        }
    } catch(PDOException $e) {
        $error_message = "Lỗi khi xóa: " . $e->getMessage();
    }
}

// Get registered courses
try {
    $sql = "SELECT d.MaHp, h.TenHP, h.SoTinChi, d.NgayDK 
            FROM dangky d 
            JOIN hocphan h ON d.MaHp = h.MaHp 
            ORDER BY d.NgayDK DESC";
    $stmt = $conn->query($sql);
    $registrations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate total credits
    $total_credits = 0;
    foreach ($registrations as $reg) {
        $total_credits += $reg['SoTinChi'];
    }
} catch(PDOException $e) {
    $error_message = "Lỗi: " . $e->getMessage();
    $registrations = [];
    $total_credits = 0;
}
?>

<div class="container">
    <h1 class="my-4">Danh sách đăng ký học phần</h1>

    <?php if ($error_message): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i> <?php echo $error_message; ?>
        </div>
    <?php endif; ?>
    
    <?php if ($success_message): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Mã HP</th>
                            <th>Tên Học Phần</th>
                            <th>Số Tín Chỉ</th>
                            <th>Ngày Đăng Ký</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($registrations)): ?>
                            <?php foreach ($registrations as $reg): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($reg['MaHp']); ?></td>
                                    <td><?php echo htmlspecialchars($reg['TenHP']); ?></td>
                                    <td><?php echo htmlspecialchars($reg['SoTinChi']); ?></td>
                                    <td><?php echo htmlspecialchars($reg['NgayDK']); ?></td>
                                    <td>
                                        <a href="?delete_id=<?php echo urlencode($reg['MaHp']); ?>" 
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Bạn có chắc muốn xóa đăng ký này?');">
                                            <i class="fas fa-trash"></i> Xóa
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">Chưa có học phần nào được đăng ký</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    <?php if (!empty($registrations)): ?>
                        <tfoot>
                            <tr>
                                <td colspan="2" class="text-end"><strong>Tổng số tín chỉ:</strong></td>
                                <td><strong><?php echo $total_credits; ?></strong></td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between mt-3">
        <?php if (!empty($registrations)): ?>
            <form method="post" onsubmit="return confirm('Bạn có chắc muốn xóa tất cả đăng ký?');">
                <button type="submit" name="delete_all" class="btn btn-danger">
                    <i class="fas fa-trash-alt"></i> Xóa tất cả đăng ký
                </button>
            </form>
        <?php endif; ?>
        <a href="hocphan.php" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Quay lại đăng ký
        </a>
    </div>
</div>

</body>
</html> 