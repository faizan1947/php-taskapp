<?php
if (!defined('APP_ROOT')) {
    http_response_code(403);
    exit('Forbidden');
}
?>
</main>
<footer class="footer">
    <p>PHP TaskApp — Running on <?= php_uname('n') ?> | PHP <?= PHP_VERSION ?></p>
</footer>
</body>
</html>