<?php
require_once 'config.php';
$page_title = 'Đăng nhập';
require_once 'header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $masv = isset($_POST['masv']) ? trim($_POST['masv']) : '';
    
    if (empty($masv)) {
        $error = 'Vui lòng nhập mã sinh viên';
    } else {
        try {
            $stmt = $conn->prepare("SELECT * FROM sinhvien WHERE MaSV = ?");
            $stmt->execute([$masv]);
            $student = $stmt->fetch();
            
            if ($student) {
                // Lưu thông tin sinh viên vào session
                $_SESSION['masv'] = $student['MaSV'];
                $_SESSION['hoten'] = $student['HoTen'];
                
                // Chuyển hướng về trang chủ
                header("Location: index.php");
                exit();
            } else {
                $error = 'Mã sinh viên không tồn tại';
            }
        } catch(PDOException $e) {
            $error = "Lỗi: " . $e->getMessage();
        }
    }
}
?>

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h4 class="card-title mb-0">ĐĂNG NHẬP</h4>
                </div>
                <div class="card-body">
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

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="masv" class="form-label">Mã SV</label>
                            <input type="text" class="form-control" id="masv" name="masv" 
                                   value="<?php echo isset($_POST['masv']) ? htmlspecialchars($_POST['masv']) : ''; ?>"
                                   required>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt"></i> Đăng Nhập
                            </button>
                            <a href="index.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html> 