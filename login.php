<?php
session_start();
require('library.php');

$error = [];
$email = '';
$password = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
  if ($email === '' || $password === '') {
    $error['login'] = 'blank';
  } else {
    $db = dbconnect();
    $stmt = $db->prepare('select id, name, password from members where email=? limit 1');
    if (!$stmt) {
      die($db->error);
    }

    $stmt->bind_param('s', $email);
    $success = $stmt->execute();
    if (!$success) {
      die($db->error);
    }

    $stmt->bind_result($id, $name, $hash);
    $stmt->fetch();
    if (password_verify($password, $hash)) {
      session_regenerate_id();
      $_SESSION['id'] = $id;
      $_SESSION['name'] = $name;
      header('Location: index.php');
      exit();
    } else {
      $error['login'] = 'failed';
    }
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="https://fonts.googleapis.com/css?family=Caveat" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=M+PLUS+Rounded+1c" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="style.css" />
<title>Login</title>
</head>

<body>
<div id="wrap">
  <div id="head">
    <h1>Login</h1>
  </div>
  <div id="content" class="login-contents">
    <form action="" method="post">
      <dl class="login-flex">
        <dt class="login-title">Email</dt>
        <dd>
          <input type="text" name="email" size="35" maxlength="255" value="<?php echo h($email); ?>" class="login-box"/>
          <?php if (isset($error['login']) && $error['login'] === 'blank'): ?>
            <p class="error">* メールアドレスとパスワードをご記入ください</p>
          <?php endif; ?>
          <?php if (isset($error['login']) && $error['login'] === 'failed'): ?>
            <p class="error">* ログインに失敗しました。正しくご記入ください。</p>
          <?php endif; ?>
        </dd>
      </dl>
      <dl class="login-flex">
        <dt class="login-title">Password</dt>
        <dd>
          <input type="password" name="password" size="35" maxlength="255" value="<?php echo h($password); ?>" class="login-box"/>
        </dd>
      </dl>
      <div id="lead" class="login-bottom">
        <p><a href="join/" class="login-link">Register</a></p>
        <input type="submit" value="Login" class="login-button"/>
      </div>
    </form>

  </div>
</div>
</body>
</html>
