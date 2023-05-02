<?php
    error_reporting(0);

    function clean($str) {
        //Remove all non-numeric characters including spaces
        $str = preg_replace('/[^\d]/',  "", $str);

        //Put all phone numbers starting with 234 on a new line
        $str = preg_replace('/234/',  "\n234", $str);

        //Prepend all with a + sign
        $str = trim(preg_replace('/234/', "+234", $str));

        $n = preg_match_all('/\d{14,}/', $str, $m, PREG_OFFSET_CAPTURE);
        for($i = 0; $i < $n; $i++) {
            $str = preg_replace('/' . $m[0][$i][0] . '/', substr($m[0][$i][0], 0, 13), $str);
        }

        return $str;
    }

    if(isset($_POST['submit'])) {
        $file_count = count($_FILES['file']['name']);
        $data_file_details = [];
        $base_path = 'upload/';
        $permitted_chars = '01234567890abcdefghijklmopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        if(empty($_FILES['file']['name'][0])) die('No file selected');

        //For efficient & continuous writing
        $f = fopen('contacts.txt', 'a');

        for($i = 0; $i < $file_count; $i++) {
            $pathinfo = pathinfo($_FILES['file']['name'][$i]);
            $p = clean(file_get_contents($_FILES['file']['tmp_name'][$i]));

            if(!file_exists($base_path . $pathinfo['filename'] . '.clean.' . $pathinfo['extension']))
                $file_name = $base_path . $pathinfo['filename'] . '.clean.' . $pathinfo['extension'];
            else{
                $file_name = $base_path . $pathinfo['filename'] . '-' . 
                substr(str_shuffle($permitted_chars), 0, 7) . '.clean.' . $pathinfo['extension'];
            }
                
            file_put_contents($file_name, $p);
            fwrite($f, $p . PHP_EOL);

            array_push($data_file_details, array(
                'filename' => basename($file_name),
                'contact_count' => preg_match_all('/[+]/', $p)
            ));
        }

        fclose($f);
    }

?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="fonts/icomoon/style.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;700&display=swap" rel="stylesheet">

    <title>WhatsApp Automation Extension</title>
    <style>
        :root {
            --color-WhatsApp: #128c7e
        }

        body {
            font-family: 'Source Sans Pro', 'Trebuchet MS', Tahoma, Helvetica, sans-serif ;
        }

        :link:not(.btn) {
            color: var(--color-WhatsApp);
        }

        .bg-WhatsApp {
            background: var(--color-WhatsApp);
        }

        .jumbotron {
            background-image: url(img/WhatsApp_bg.jpeg);
            background-blend-mode: multiply;
        }
    </style>
  </head>
  <body>
    <div class="container">
        <div class="text-white rounded p-5 mt-3 bg-WhatsApp jumbotron">
            <h1><span class="icon icon-whatsapp"></span> WhatsApp Automation <small style="font-size: 24px">Extension</small></h1>
            <p>Extracts the Nigerian(only) phone numbers from TextScanner's scan result, saves you the stress of manually editing.</p>
        </div>
        <div class="card">
            <div class="card-body">

                <?php if($_SERVER["REQUEST_METHOD"] == 'GET') { ?>

                    <h4 class="text-center mb-4">CONVERT</h4>

                    <div class="d-flex row mb-4">
                        <div class="col-md-5 text-center">
                            <img src="img/144530.png" height="550">
                        </div>
                        <div class="col-md-2 align-self-center">
                            <h4 class="text-center mb-4 mt-4">to</h4>
                        </div>
                        <div class="col-md-5 text-center">
                            <img src="img/163915.jpg"  height="550">
                        </div>
                    </div>

                <?php } ?>

                <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group mt-3">
                    <div class="input-group">
                        <input type="file" name="file[]" multiple class="form-control" id="formFile" accept="text/plain">
                        <input type="submit" class="btn bg-WhatsApp text-white" value="Clean" name="submit">
                    </div>
                    <small class="form-text text-muted">Multiple files permitted (*.txt)</small>
                </div>
                </form>
                <?php if(isset($_POST['submit'])) { ?>
                    <div class="table-responsive mt-3">
                        <table class="table">
                            <thead>
                                <th>#</th>
                                <th>File</th>
                                <th>Contacts</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                <?php for($i = 0; $i < count($data_file_details); $i++){ ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= $data_file_details[$i]['filename'] ?></td>
                                        <td><?= $data_file_details[$i]['contact_count'] ?></td>
                                        <td><a href="download.php?f=<?= $data_file_details[$i]['filename'] ?>">Download</a></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>
            </div>
            <div class="card-footer text-muted text-center">
                Developed by akineni &copy; <?= date('Y'); ?>
            </div>
        </div>
        <div class="text-center text-muted mt-3 small" style="margin-bottom: 120px;">   
                    Contact <a href="https://bit.ly/akineni">akineni</a> for your Website Design and Development. 
                    Tel: <a href="tel: +2349068857142">09068857142</a>
            </div>
    <div>
  </body>
</html>