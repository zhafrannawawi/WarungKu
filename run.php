<?php
echo "Menjalankan WarungKu di http://localhost:8000 ...\n";
passthru('php -S localhost:8000 -t public public/index.php');
