<?php
session_start();
require('../library.php');

if (isset($_SESSION['form'])) {
	$form = $_SESSION['form'];
} else {
	header('Location: index.php');
	exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$db = dbconnect();
	$stmt = $db->prepare('insert into members (name, email, password, picture) VALUES (?, ?, ?, ?)');
	if (!$stmt) {
		die($db->error);
	}
	$password = password_hash($form['password'], PASSWORD_DEFAULT);
	$stmt->bind_param('ssss', $form['name'], $form['email'], $password, $form['image']);
	$success = $stmt->execute();
	if (!$success) {
		die($db->error);
	}
	unset($_SESSION['form']);
	header('Location: thanks.php');
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Register</title>

	<link rel="stylesheet" href="../style.css" />
</head>

<body>
	<div id="wrap">
		<div id="head">
			<h1>Register</h1>
		</div>

		<div id="content" class="check-contents">
			<form action="" method="post">
				<dl class="check-flex">
					<dt class="check-title">Name</dt>
					<dd><?php echo h($form['name']); ?></dd>
				</dl>
				<dl class="check-flex">
					<dt class="check-title">Email</dt>
					<dd><?php echo h($form['email']); ?></dd>
				</dl>
				<dl class="check-flex">
					<dt class="check-title">Password</dt>
					<dd>
						【表示されません】
					</dd>
				</dl>
				<dl class="check-flex">
					<dt class="check-title">Image</dt>
					<dd>
						<?php if ($form['image']) : ?>
							<img src="../member_picture/<?php echo h($form['image']); ?>" width="100" alt="" />
						<?php endif; ?>
					</dd>
				</dl>
				<div class="check-flex02">
					<a href="index.php?action=rewrite" class="check-link">Rewrite</a>
					<input type="submit" value="Register" class="check-button"/>
				</div>
			</form>
		</div>

	</div>
</body>

</html>
