<?php
/**
 * @license
 * Copyright(c) 2002-2019 Jose Ricardo Mantilla Mantilla. All Rights Reserved.
 * Author: Jose Ricardo Mantilla Mantilla <contact@pepiuox.net> / <contact@labemotion.net>
 * Website Author: http://pepiuox.net/ / http://labemotion.net/
 * Author's licenses: http://pepiuox.net/license / http://labemotion.net/license
 * Project Name: EasyCRUD

 * Contributors:
 * Website Contributors:

 * Library list.
 * Bootstrap http://www.getbootstrap.com 
 * Jquery http://www.jquery.com , 
 * Poppers https://popper.js.org/

 * This file is a module or application for make simple CRUD based in PHP.
 * Use of this source code is governed by an PEPIUOX / LAB EMOTION License included 
 * MIT License that can be found in the LICENSE file in the root directory of 
 * this source tree.

 * Distributed under the MIT License 
 * (license terms are at http://opensource.org/licenses/MIT).

 * */
ob_start();

$path = basename($_SERVER['REQUEST_URI']);
$file = basename($path);

$fileName = basename($_SERVER['PHP_SELF']);

if ($file == $fileName) {
    header("Location: index.php?view=select");
}

function protect($string) {
    $protection = htmlspecialchars(trim($string), ENT_QUOTES);
    return $protection;
}

/* Get id table as first column */

function getID($tble) {
    global $link;
    if (!empty(protect($_GET['tbl']))) {
        $tble = protect($_GET['tbl']);
        $query = "SELECT * from " . $tble;
        if ($result = $link->query($query)) {
            /* Get field information for first column */
            $result->field_seek(0);
            $deletemeta = $result->fetch_field();
            return $deletemeta->name;
        }
    }
}

require_once("db.php");
$view = protect($_GET['view']);
include 'easycrud_class.php';
$c = new EasyCRUD();
?>
<!DOCTYPE html>
<html lang = "en">
    <head>
        <meta charset = "UTF-8" name = "viewport" content = "width-device=width, initial-scale=1" />    
        <title><?php
            if (isset($_GET['tbl'])) {
                $titleTbl = str_replace("_", " ", protect($_GET['tbl']));
            }
            if ($view === "select") {
                echo 'Select your table - PHP CRUD';
            } elseif ($view === "list") {
                echo 'List ' . $titleTbl . ' - PHP CRUD';
            } elseif ($view === "add") {
                echo 'Add ' . $titleTbl . ' - PHP CRUD';
            } elseif ($view === "edit") {
                echo 'Edit ' . $titleTbl . ' - PHP CRUD';
            } elseif ($view === "delete") {
                echo 'Delete ' . $titleTbl . ' - PHP CRUD';
            }
            ?></title>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

        <style>
            body{
                font-size: 14px;
            }
        </style>
    </head>
    <body>

        <?php
        /* Select table */
        if ($view === "select") {
            ?>
            <div class="container">
                <div class = "row">	
                    <div class="col-md-6">
                        <h3 id="fttl">Form </h3>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <script>
                                $(function () {
                                    $("#selecttb").change(function () {
                                        var selecttb = $(this).val();
                                        //var path = $(location).attr('href');                        
                                        var url = 'index.php?view=list&tbl=' + selecttb;
                                        $('#fttl').text('Form ' + selecttb);
                                        window.location.replace(url);
                                    });
                                });
                            </script>
                            <label class="control-label" for="selecttb">Select Table</label>
                            <select id="selecttb" name="selecttb" class="form-control">
                                <option value="">Select Table</option>
                                <?php
                                /* Get table names */
                                $tableList = array();
                                $res = $link->query("SHOW TABLES");
                                while ($row = $res->fetch_array()) {
                                    $tableList[] = $row[0];
                                }
                                foreach ($tableList as $tname) {
                                    $remp = str_replace("_", " ", $tname);
                                    echo '<option value="' . $tname . '">' . ucfirst($remp) . '</option>' . "\n";
                                }
                                ?>
                            </select>                               
                        </div>
                    </div>
                </div>
            </div>
            <?php
            /* View data in the selected table */
        } elseif ($view == "list") {
            if (!empty(protect($_GET['tbl']))) {
                $tble = protect($_GET['tbl']);
                ?>
                <div class="container-fluid">
                    <div class="row">
                        <div class="w-100">
                            <?php
                            echo '<h2><a href="index.php?view=select" class="btn btn-primary">Back to select</a> List ' . $titleTbl . '</h2>';
                            ?>
                        </div>
                        <div class="w-100">
                            <?php
                            $tmpfile = $tble . '.php';
                            if (file_exists($tmpfile)) {
                                unlink($tmpfile);
                            }
                            $c->viewList($tble);
                            ?>
                        </div>
                    </div>
                </div>
                <?php
            } else {
                header("Location: index.php?view=select");
            }
            /* Add data in the selected table */
        } elseif ($view == "add") {
            if (!empty(protect($_GET['tbl']))) {
                $tble = protect($_GET['tbl']);
                $idCol = getID($tble);
                ?>
                <div class="container">
                    <div class="row">
                        <div class="w-100">
                            <?php
                            echo '<h2><a href="index.php?view=list" class="btn btn-primary">Back to list</a> Add ' . $titleTbl . '</h2>';
                            ?>
                        </div>
                        <div class="w-100">
                            <?php
                            $scpt = $c->addPost($tble, $idCol);
                            $idCols = $c->addTtl($tble, $idCol);
                            $nvals = $c->addTPost($tble, $idCol);
                            $mpty = $c->ifMpty($tble, $idCol);

                            $tmpfile = $tble . '.php';
                            $myfile = fopen("$tmpfile", "w") or die("Unable to open file!");
                            $start = '<?php' . "\n";

                            fwrite($myfile, $start);
                            fclose($myfile);

                            $actual = file_get_contents($tmpfile);
                            $actual .= '//This is temporal file only for add new row in ' . $tble . "\n";
                            $actual .= "if (isset(\$_POST['addrow'])) { \r\n";
                            $actual .= $scpt . "\r\n";
                            $actual .= "    if (" . $mpty . ") { \r\n";
                            $actual .= '        $query = "INSERT INTO `$tble`(' . $idCols . ') VALUES (' . $nvals . ')";' . "\r\n";
                            $actual .= 'if ($link->query($query) == TRUE) {
               echo "Record added successfully";                                           
            } else {
               echo "Error added record: " . $link->error;
            }
            unlink("' . $tble . '.php");
            ' . "\r\n";
                            $actual .= "    } \r\n";
                            $actual .= "} \r\n";
                            $actual .= "?> \n";

                            file_put_contents($tmpfile, $actual);

                            include $tble . '.php';
                            //get add form
                            $c->addItem($tble, $idCol);
                            ?>
                        </div>
                    </div>
                </div>
                <?php
            } else {
                header("Location: index.php?view=select");
            }
            /* Edid data in the selected table */
        } elseif ($view == "edit") {
            if (!empty(protect($_GET['tbl'])) || !empty(protect($_GET['id']))) {
                $tble = protect($_GET['tbl']);
                $id = protect($_GET['id']);
                $idCol = getID($tble);
                ?>
                <div class="container">
                    <div class="row">
                        <div class="w-100">
                            <?php
                            echo '<h2><a href="index.php?view=list" class="btn btn-primary">Back to list</a> Edit ' . $tble . '</h2>';
                            ?>
                        </div>
                        <div class="w-100">
                            <?php
                            $scpt = $c->addPost($tble, $idCol);
                            $ecols = $c->updateData($tble, $idCol);
                            $mpty = $c->ifMpty($tble, $idCol);

                            $tmpfile = $tble . '.php';
                            $myfile = fopen("$tmpfile", "w") or die("Unable to open file!");
                            $content = '<?php' . "\n";
                            $content .= '//This is temporal file only for add new row' . "\n";

                            fwrite($myfile, $content);
                            fclose($myfile);

                            $actual = file_get_contents($tmpfile);

                            $actual .= "if (isset(\$_POST['editrow'])) { \r\n";
                            $actual .= $scpt . "\r\n";
                            $actual .= "    if (" . $mpty . ") { \r\n";
                            $actual .= '        $query = "UPDATE `$tble` SET ' . $ecols . ' WHERE ' . $idCol . '=`$id` ";' . "\r\n";
                            $actual .= 'if ($link->query($query) == TRUE) {
               echo "Record added successfully";                            
            } else {
               echo "Error added record: " . $link->error;
            }
            unlink("' . $tble . '.php");              
            ' . "\r\n";
                            $actual .= "    } \r\n";
                            $actual .= "} \r\n";
                            $actual .= "?> \n";

                            file_put_contents($tmpfile, $actual);

                            include $tble . '.php';

                            $c->editItem($tble, $id, $idCol);
                            ?>

                        </div>
                    </div>
                </div>
                <?php
            } else {
                header("Location: index.php?view=select");
            }

            /* detele data in the selected table */
        } elseif ($view == "delete") {
            if (!empty(protect($_GET['tbl'])) || !empty(protect($_GET['id']))) {
                $tble = protect($_GET['tbl']);
                $id = protect($_GET['id']);
                $idCol = getID($tble);
                ?>
                <div class="container">
                    <div class="row">
                        <div class="w-100">
                            <?php
                            echo '<h2><a href="index.php?view=list" class="btn btn-primary">Back to list</a> Delete ' . $tble . '</h2>';
                            ?>
                        </div>
                        <div class="w-100">
                            <?php
                            if (isset($_POST["deleterow"])) {
                                if ($c->ifEmpty($tble, $idCol)) {
                                    $query = "DELETE FROM $tble WHERE $idCol = '$id' ";
                                    if ($link->query($query) === TRUE) {
                                        echo "Record deleted successfully";
                                        header("Location: index.php?w=list");
                                    } else {
                                        echo "Error deleting record: " . $link->error;
                                    }
                                }
                            }
                            $c->deleteItem($tble, $id, $idCol);
                            ?>
                        </div>
                    </div>
                </div>
                <?php
            } else {
                header("Location: index.php?view=select");
            }
        }
        ?>

    </body>
</html>
