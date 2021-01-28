<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// echo('<pre>'.print_r($_REQUEST, 1).'</pre>');
$config = include('config.php');
$filename = $config['data_path'].'/share.json';
$id = null;
$shared = null;
$is_link = false;
$output = null;
if (array_key_exists('id', $_REQUEST)) {
    $id = trim($_REQUEST['id']);
    if ($id !== '') {
        $stored = file_exists($filename) ? json_decode(file_get_contents($filename), 1) : [];

        if (array_key_exists('shared', $_REQUEST)) {
            $shared = $_REQUEST['shared'];
            $stored[$id] = $shared;
        } elseif (array_key_exists($id, $stored)) {
            $shared = $stored[$id];
            unset($stored[$id]);
        }

        file_put_contents($filename, json_encode($stored));

        if (isset($shared)) {
            if (preg_match('#^http[s]?://.+#', $shared) === 1) {
                $output = "<a href=\"$shared\">$shared</a>";
                $is_link = true;
            } else {
                $output = htmlspecialchars($shared);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=2.0">
        <title>share</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <?php if (is_null($shared)) : ?>
        <form method="post">
        <p>id <input name="id"<?= isset($id) ? ' value="'.$id.'"' : ''?>></p>
        <p>shared text / url:<br>
        <textarea name="shared"></textarea></br>
        <input type="submit" value="&raquo;">
        </form>
        <?php else : ?>
        <p><?= $output ?></p>
        <?php endif; ?>
        <?php if ($is_link) : ?>
        <div id="qrcode"></div>
        <script type="text/javascript" src="js/qrcode.js"></script>
        <script type="text/javascript">
        window.onload = function() {
            console.log(document.getElementById('qrcode'));
            new QRCode(document.getElementById('qrcode'), '<?= $shared ?>');
        }
        </script>
        <?php endif; ?>
    </body>
</html>
