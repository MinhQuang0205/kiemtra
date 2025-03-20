<?php
require_once 'config.php';

// Initialize messages
$error_message = '';
$success_message = '';

// Handle course registration
if (isset($_POST['register']) && isset($_POST['mahp'])) {
    try {
        // Check if already registered
        $check_sql = "SELECT * FROM dangky WHERE MaHp = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->execute([$_POST['mahp']]);
        
        if ($check_stmt->rowCount() > 0) {
            $error_message = "Học phần này đã được đăng ký!";
        } else {
            // Register the course
            $register_sql = "INSERT INTO dangky (MaHp, NgayDK) VALUES (?, NOW())";
            $register_stmt = $conn->prepare($register_sql);
            
            if ($register_stmt->execute([$_POST['mahp']])) {
                $success_message = "Đăng ký học phần thành công!";
            } else {
                $error_message = "Lỗi khi đăng ký học phần";
            }
        }
    } catch(PDOException $e) {
        $error_message = "Lỗi: " . $e->getMessage();
    }
}

// Get all courses
try {
    $stmt = $conn->query("SELECT * FROM hocphan");
    $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}

require_once 'header.php';
?>

<div class="container">
    <h1 class="my-4">Danh sách học phần</h1>

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
                            <th>Mã Học Phần</th>
                            <th>Tên Học Phần</th>
                            <th>Số Tín Chỉ</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($subjects as $subject): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($subject['MaHp']); ?></td>
                                <td><?php echo htmlspecialchars($subject['TenHP']); ?></td>
                                <td><?php echo htmlspecialchars($subject['SoTinChi']); ?></td>
                                <td>
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="mahp" value="<?php echo htmlspecialchars($subject['MaHp']); ?>">
                                        <button type="submit" name="register" class="btn btn-primary btn-sm">
                                            <i class="fas fa-plus"></i> Đăng ký
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="danhsachdangky.php" class="btn btn-info">
            <i class="fas fa-list"></i> Xem danh sách đăng ký
        </a>
    </div>
</div>

</body>
</html> 