<?php
function h($value) {
	return htmlspecialchars($value, ENT_QUOTES);
}

/* DBへの接続 */
function dbconnect() {

    $user=getenv('DB_USERNAME');
    $password=getenv('DB_PASSWORD');
    $db=getenv('DB_NAME');
    $server=getenv('DB_HOSTNAME');
    $conn = new mysqli($server, $user, $password, $db);

    if (!$conn) {
		die($conn->error);
	}
    return $conn;
}
?>
