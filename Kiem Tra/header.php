<?php
// Bắt đầu session nếu chưa được bắt đầu
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Quản lý sinh viên'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .navbar {
            padding: 0;
            background-color: #2b2b2b !important;
        }
        .navbar-nav .nav-link {
            padding: 1rem;
            color: white !important;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .navbar-nav .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
        }
        .navbar-nav .nav-link.active {
            background-color: rgba(255,255,255,0.2);
        }
        .nav-link i {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand navbar-dark">
        <div class="container-fluid">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" 
                       href="index.php">
                       <i class="fas fa-home"></i> Quản lý Sinh viên
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" 
                       href="index.php">
                       <i class="fas fa-user"></i> Sinh Viên
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'hocphan.php' ? 'active' : ''; ?>" 
                       href="hocphan.php">
                       <i class="fas fa-book"></i> Học Phần
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dangnhap.php' ? 'active' : ''; ?>" 
                       href="dangnhap.php">
                       <i class="fas fa-book"></i> Đăng Nhập
                    </a>
                </li>
                
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if (isset($page_title)): ?>
            <h1><?php echo $page_title; ?></h1>
        <?php endif; ?>
    </div>
</body>
</html> 