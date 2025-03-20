<?php
require_once 'config.php';

$page_title = 'Danh sách Sinh viên';

try {
    $stmt = $conn->prepare("SELECT * FROM sinhvien");
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
    $students = array();
}

require_once 'header.php';
?>

<div class="container">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="create.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm Sinh viên
                </a>
            </div>
            
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Mã SV</th>
                            <th>Họ Tên</th>
                            <th>Giới Tính</th>
                            <th>Ngày Sinh</th>
                            <th>Hình</th>
                            <th>Mã Ngành</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($students)): ?>
                            <?php foreach($students as $student): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($student['MaSV'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($student['HoTen'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($student['GioiTinh'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($student['NgaySinh'] ?? ''); ?></td>
                                <td>
                                    <?php if (!empty($student['Hinh'])): ?>
                                        <img src="<?php echo htmlspecialchars($student['Hinh']); ?>" 
                                             alt="Ảnh sinh viên" 
                                             class="img-thumbnail" 
                                             style="max-width: 50px;">
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($student['MaNganh'] ?? ''); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="edit.php?id=<?php echo htmlspecialchars($student['MaSV'] ?? ''); ?>" 
                                           class="btn btn-warning">
                                           <i class="fas fa-edit"></i> Sửa
                                        </a>
                                        <a href="detail.php?id=<?php echo htmlspecialchars($student['MaSV'] ?? ''); ?>" 
                                           class="btn btn-info">
                                           <i class="fas fa-info-circle"></i> Chi tiết
                                        </a>
                                        <a href="delete.php?id=<?php echo htmlspecialchars($student['MaSV'] ?? ''); ?>" 
                                           class="btn btn-danger">
                                           <i class="fas fa-trash"></i> Xóa
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">Không có dữ liệu sinh viên</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html> 