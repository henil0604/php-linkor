<?php

session_start();

require './api/db.php';
require './api/redirectLogger.php';

function custom_exit()
{
    unset($_SESSION['gwtoken']);
    exit();
}

function redirect($url, $statusCode = 303)
{
    header('location: ' . $url, true, $statusCode);
    custom_exit();
}

if (empty($_SESSION['gwtoken'])) {
    $_SESSION['gwtoken'] = bin2hex(random_bytes(98));
}

if (isset($_GET['r'])) {

    $url_id = $_GET['r'];

    $check = [];
    $check['sql'] = "SELECT * FROM `links` WHERE `url_id`='$url_id'";
    $check['result'] = mysqli_query($conn, $check['sql']);

    if ($check['result'] == false) {
        echo 'Something went Wrong';
        custom_exit();
    }

    if (mysqli_num_rows($check['result']) == 0) {
        echo 'No Redirects Found';
        custom_exit();
    }

    if (mysqli_num_rows($check['result']) > 1) {
        echo 'Multiple Redirects Found';
        custom_exit();
    }

    $row = mysqli_fetch_assoc($check['result']);

    redirect_log($row['url_id'], "./logs/redirects.txt");
    redirect($row['original_url']);
    custom_exit();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/0.0.0-insiders.4a070ac/tailwind.min.css" rel="stylesheet">

    <link rel="stylesheet" href="./assets/css/global.css">

    <title>Linkor</title>
</head>

<body class="bg-indigo-700 flex items-center justify-center p-5">

    <div class="bg-gray-100 p-12 rounded flex items-center justify-center flex-col transition">


        <h1 class="font-bold text-3xl mb-5">Linkor - The Redirector</h1>


        <?php

        if (!empty($_SESSION['responseData'])) {

            $responseData = $_SESSION['responseData'];
            // echo json_encode($responseData);

            if (!empty($responseData['status'])) {

        ?>

                <div class="py-3 px-5 bg-blue-600 flex items-center justify-center flex-col min-w-full rounded text-white text-lg my-4">

                    <?php echo $responseData['message']; ?>

                    <br />

                    <?php

                    if (!empty($responseData['responseUrl'])) {


                    ?>
                        <input class="rounded bg-gray-100 outline-none px-4 py-2 text-lg min-w-full w-96 mt-3 text-black hover:bg-gray-200 transition cursor-pointer" value="<?php echo $responseData['responseUrl']; ?>" readonly onclick="copy(this.value)">
                        Click on the link To Copy to Clipboard
                    <?php
                    }

                    ?>

                </div>

        <?php

                unset($_SESSION['responseData']);
            }
        };
        ?>


        <form class="flex items-center justify-center flex-col" action="api/create.php" method="GET">

            <input type="url" name="url" class="rounded outline-none px-4 py-2 text-lg min-w-full w-96" placeholder="Enter The URL" required>

            <button type="submit" class="bg-red-600 text-white px-5 py-2 rounded mt-4 hover:bg-red-700 transition">Create</button>

        </form>

    </div>


    <script>
        function copy(text) {
            if (window.clipboardData && window.clipboardData.setData) {
                // Internet Explorer-specific code path to prevent textarea being shown while dialog is visible.
                return window.clipboardData.setData("Text", text);

                alert("Copied to Clipboard");
            } else if (document.queryCommandSupported && document.queryCommandSupported("copy")) {
                var textarea = document.createElement("textarea");
                textarea.textContent = text;
                textarea.style.position = "fixed"; // Prevent scrolling to bottom of page in Microsoft Edge.
                document.body.appendChild(textarea);
                textarea.select();
                try {
                    return document.execCommand("copy");
                } catch (ex) {
                    console.warn("Copy to clipboard failed.", ex);
                    return false;
                } finally {
                    alert("Copied to Clipboard");
                    document.body.removeChild(textarea);
                }
            }
        }
    </script>


</body>

</html>
