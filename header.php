<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? "Data Mining"; ?></title>
    <script src="./js/jquery-3.7.1.min.js"></script>
    <link rel="icon" href="./assets/icons/icon-2.svg" type="image/svg">
    <?php 
    if (isset($css)) {
        foreach ($css as $url) {
            echo "<link rel=\"stylesheet\" href=\"$url\">";
        }
    }
    ?>
    <!-- Sweet Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>
