<link rel="stylesheet" href="css/footer.css">

<footer class="main-footer">
    <div class="footer-container">
        <div class="footer-grid">
            <!-- Brand Column -->
            <div class="footer-col footer-brand">
                <h3><i class="fas fa-layer-group" style="color: #10b981;"></i> Mastery</h3>
                <p>Nền tảng ôn luyện tiếng Anh toàn diện, giúp bạn chinh phục các chứng chỉ quốc tế một cách hiệu quả và tự tin.</p>
                <div class="social-links">
                    <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-tiktok"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                </div>
            </div>

            <!-- About Column -->
            <div class="footer-col">
                <h4>Về Chúng Tôi</h4>
                <ul class="footer-menu">
                    <li><a href="index.php?#about">Giới thiệu</a></li>
                    <li><a href="#">Điều khoản sử dụng</a></li>
                    <li><a href="#">Chính sách bảo mật</a></li>
                    <li><a href="contact.php">Liên hệ hợp tác</a></li>
                </ul>
            </div>

            <!-- Course Column -->
            <div class="footer-col">
                <h4>Khóa Học Tiêu Biểu</h4>
                <ul class="footer-menu">
                    <?php
                    try {
                        $footerCats = $pdo->query("SELECT * FROM categories LIMIT 6")->fetchAll();
                        $hasMore = count($footerCats) > 5;
                        $displayCats = array_slice($footerCats, 0, 5);

                        foreach ($displayCats as $fc): ?>
                            <li>
                                <a href="level_exams.php?id=<?php echo $fc['id']; ?>"><?php echo htmlspecialchars($fc['name']); ?></a>
                            </li>
                    <?php endforeach;

                        if ($hasMore) {
                            echo '<li><a href="roadmap.php" style="color: #10b981;">Xem tất cả...</a></li>';
                        }
                    } catch (Exception $e) {
                        echo "<li>Loading...</li>";
                    }
                    ?>
                </ul>
            </div>

            <!-- Contact Column -->
            <div class="footer-col">
                <h4>Thông Tin Liên Hệ</h4>
                <div class="footer-contact-item">
                    <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div>Tầng 5, Tòa nhà Tech, 123 Đường Cầu Giấy, Hà Nội</div>
                </div>
                <div class="footer-contact-item">
                    <div class="contact-icon"><i class="fas fa-phone"></i></div>
                    <div>
                        <div style="color:white; font-weight:600;">090 123 4567</div>
                        <small>Hỗ trợ 24/7</small>
                    </div>
                </div>
                <div class="footer-contact-item">
                    <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                    <div>support@englishstudy.vn</div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="copyright">
                &copy; <?php echo date('Y'); ?> <b>English Mastery</b>. All rights reserved.
            </div>
            <div style="display:flex; gap:1rem; font-size: 0.9rem;">
                <a href="#" style="color:#64748b; text-decoration:none;">Privacy Policy</a>
                <a href="#" style="color:#64748b; text-decoration:none;">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>
<script src="js/main.js"></script>