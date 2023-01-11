<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Voorbeeld</title>
    <link rel="stylesheet" href="global.css" type="text/css"/>
</head>

<body>

    <?php

    echo '<h1> Dit is een voorbeeld </h1>';
    echo '<p> Dit is de subtext </p>';
    require '../components/dropdown_function.php';

    $links = array("Test 1"=>"test_1.php", "Test 2"=>"test_2.php", "Test 3"=>"test_3.php");

    dropDown($links);

    ?>

    <html>
    <body>

    <video width="320" height="240" controls>
        <source src="test.mp4" type="video/mp4">
        <source src="test.ogg" type="video/ogg">
    </video>

    </body>
    </html>

</body>
</html>