<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// echo('<pre>'.print_r($_REQUEST, 1).'</pre>');
$config = include('config.php');
$filename = $config['data_path'].'/share.json';
$id = null;
$output = null;
if (array_key_exists('id', $_REQUEST)) {
    $id = trim($_REQUEST['id']);
    if ($id !== '') {
        $stored = file_exists($filename) ? json_decode(file_get_contents($filename), 1) : [];
        if (array_key_exists('shared', $_REQUEST)) {
            $stored[$id] = $_REQUEST['shared'];
        } else {
            if (array_key_exists($id, $stored)) {
                $shared = $stored[$id];
                if (preg_match('#^http[s]?://.+#', $shared) === 1) {
                    $output = "<a href=\"$shared\">$shared</a>";
                } else {
                    $output = htmlspecialchars($shared);
                }
                unset($stored[$id]);
            }
        }
        file_put_contents($filename, json_encode($stored));
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>share</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <?php if (is_null($output)) : ?>
        <form method="post">
        <p>id <input name="id"<?= isset($id) ? ' value="'.$id.'"' : ''?>></p>
        <p>shared text / url:<br>
        <textarea name="shared"></textarea></br>
        <input type="submit" value="&raquo;">
        </form>
        <?php else : ?>
        <p><?= $output ?></p>
        <?php endif; ?>
    </body>
</html>
