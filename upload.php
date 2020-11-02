<?php
$errors = [];
$mimeAllowed = [
    'png' => 'image/png',
    'jpe' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'jpg' => 'image/jpeg',
    'gif' => 'image/gif',
];

if (isset($_FILES) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_FILES['images']['name'] as $index=>$name) {
        if (!in_array($_FILES['images']['type'][$index], $mimeAllowed)) {
            $errors['type'] = "Sorry mate, the file number " . $index . " has a wrong extension";
        }
        if ($_FILES['images']['size'][$index] > 1000000) {
            $errors['size'] = "Sorry mate, the file number " . $index . " is too big";
        }
        if (empty($errors)) {
            $extension = pathinfo($name, PATHINFO_EXTENSION);
            $filename = uniqid() . '.' .$extension;
            $uploadDir = 'uploads/';
            $uploadFile = $uploadDir . basename($filename);
            move_uploaded_file($_FILES['images']['tmp_name'][$index], $uploadFile);
        }
    }
}

if (isset($_GET['delete']) && file_exists($_GET['delete'])) {
    unlink ($_GET['delete']);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bibi</title>
</head>
<body>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="MAX_FILE_SIZE" value="1000000">
        <input type="file" name="images[]" multiple="multiple">
        <?php foreach ($errors as $error){ ?>
            <p><?=$error;?></p>
       <?php }?>
        <button type="submit">Upload</button>
    </form>
    <div style="display: flex; flex-wrap: wrap">
        <?php $iterator = new FilesystemIterator('uploads/');?>
        <?php foreach ($iterator as $image) { ?>
        <div style="border: solid midnightblue 2px">
            <img src="<?=$image;?>" style="max-width: 200px">
            <figure style="text-align: center"><?=$image->getFilename ();?> <br>
                <a href="?delete=<?=$image;?>">Delete Now!</a></figure>
        </div>
        <?php }?>
    </div>
</body>
</html>


