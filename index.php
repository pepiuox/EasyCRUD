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
 * Use of this source code is governed by an PEPIUOX LAB EMOTION License included 
 * MIT License that can be found in the LICENSE file in the root directory of 
 * this source tree.

 * Copyright(c) 2012-2019 Jose Ricardo Mantilla Mantilla.

 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
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
                            echo '<form method="POST">' . "\n";
                            echo '<table class="table table-bordered">' . "\n";
                            echo '<thead class="bg-info">' . "\n";
                            echo '<tr>' . "\n";
                            $sql = "SELECT * FROM $tble";
                            $result = $link->query($sql);
                            $i = 0;
                            while ($i < mysqli_num_fields($result)) {
                                $meta = mysqli_fetch_field($result);
                                $remp = str_replace("_", " ", $meta->name);
                                echo '<th>' . ucfirst($remp) . '</th>' . "\n";
                                $i = $i + 1;
                            }
                            echo '<th><a id="addrow" name="addrow" class="btn btn-primary" href="index.php?view=add&tbl=' . $tble . '">Add new</a></th>' . "\n";
                            echo '</tr>' . "\n";
                            echo '</thead>' . "\n";
                            echo '<tbody>' . "\n";

                            while ($row = mysqli_fetch_row($result)) {
                                echo '<tr>' . "\n";
                                $count = count($row);
                                $y = 0;
                                while ($y < $count) {
                                    $c_row = current($row);
                                    if ($y == 0) {
                                        echo '<td id="' . $c_row . '">' . $c_row . '</td>' . "\n";
                                    } else {
                                        echo '<td>' . $c_row . '</td>' . "\n";
                                    }
                                    next($row);
                                    $y = $y + 1;
                                }

                                $i_row = $row[0];
                                echo '<td><!-- Button -->
                <a id="editrow" name="editrow" class="btn btn-success" href="index.php?view=edit&tbl=' . $tble . '&id=' . $i_row . '">Edit</a>
                <a id="deleterow" name="deleterow" class="btn btn-danger" href="index.php?view=delete&tbl=' . $tble . '&id=' . $i_row . '">Delete</a>   
                </td>';

                                echo '</tr>' . "\n";
                                $i = $i + 1;
                            }
                            echo '</tbody>' . "\n";
                            echo '</table>' . "\n";
                            echo '</form>' . "\n";
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
                ?>
                <div class="container">
                    <div class="row">
                        <div class="w-100">
                            <?php
                            echo '<h2><a href="index.php?view=list" class="btn btn-primary">Back to list</a> Add ' . $tble . '</h2>';
                            ?>
                        </div>
                        <div class="w-100">
                            <?php
                            echo '<form class="form-horizontal">
<fieldset>

<!-- Form Name -->
<legend>' . $tble . '</legend>';
                            $addQuery = 'SELECT * FROM ' . $tble;
                            $addResult = $link->query($addQuery);

                            $idCol = getID($tble);


                            /* Init loop */

                            if (mysqli_num_fields($addResult) > 0) {
                                $addmetas = $addResult->fetch_fields();
                                foreach ($addmetas as $addmeta) {
                                    $remp = str_replace("_", " ", $addmeta->name);
                                    if ($addmeta->name === $idCol) {
                                        continue;
                                    } else {
                                        echo '<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="textinput">' . ucfirst($remp) . '</label>  
  <div class="col-md-4">
  <input id="' . $addmeta->name . '" name="' . $addmeta->name . '" placeholder="' . ucfirst($remp) . '" class="form-control input-md" type="text">
  <small class="form-text text-muted">' . ucfirst($remp) . '</small>  
  </div>
</div>';
                                    }
                                }
                            }
                            /* End loop */
                            echo '<!-- Button -->
<div class="form-group">  
  <div class="col-md-4">
    <button id="addrow" name="addrow" class="btn btn-primary">Save</button>
  </div>
</div>';
                            echo '</fieldset>
</form>';
                        } else {
                            header("Location: index.php?view=select");
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php
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
                            echo '<form class="form-horizontal">
<fieldset>

<!-- Form Name -->
<legend>' . $tble . '</legend>';

                            $editQuery = "SELECT * FROM $tble WHERE ";
                            $editQuery .= $idCol . "=" . $id;

                            $editResult = $link->query($editQuery);

                            if (mysqli_num_fields($editResult) > 0) {
                                $editmetas = $editResult->fetch_fields();
                                $rqu = $editResult->fetch_array();
                                foreach ($editmetas as $editmeta) {
                                    $remp = str_replace("_", " ", $editmeta->name);

                                    if ($editmeta->name === $idCol) {
                                        continue;
                                    } else {
                                        echo '<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="textinput">' . ucfirst($remp) . '</label>  
  <div class="col-md-4">
  <input id="' . $editmeta->name . '" name="' . $editmeta->name . '" value="' . $rqu[$editmeta->name] . '" class="form-control input-md" type="text">
  <small class="form-text text-muted">' . ucfirst($remp) . '</small>  
  </div>
</div>';
                                    }
                                }
                            }
                            echo '<!-- Button -->
<div class="form-group">  
  <div class="col-md-4">
    <button id="editrow" name="editrow" class="btn btn-primary">Edit</button>
  </div>
</div>';
                            echo '</fieldset>
</form>';
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
                            $deletequery = "SELECT * FROM $tble WHERE $idCol = '$id' ";
                            $deleteresult = $link->query($deletequery);
                            echo '<form role="form" id="delete_' . $tble . '" method="POST">
                        <legend>' . $tble . '</legend>' . "\n";
                            $deletemetas = $deleteresult->fetch_fields();
                            $drow = $deleteresult->fetch_array();

                            foreach ($deletemetas as $deletemeta) {
                                $cdta = $drow[$deletemeta->name];
                                if ($deletemeta->name === $idCol) {
                                    continue;
                                } else {
                                    $remp = str_replace("_", " ", $deletemeta->name);
                                    echo '<div class="form-group">
                       <label for="' . $deletemeta->name . '">' . ucfirst($remp) . ':</label>
                       <input type="text" class="form-control" id="' . $deletemeta->name . '" name="' . $deletemeta->name . '" value="' . $cdta . '" readonly>
                  </div>' . "\n";
                                }
                            }
                            echo '<div class="form-group">
             <button type = "submit" id="deleterow" name="deleterow" class="btn btn-primary"><span class = "glyphicon glyphicon-plus"></span> Delete</button>
         </div>' . "\n";
                            echo '</form>' . "\n";
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php
        } else {
            header("Location: index.php?view=select");
        }
        ?>

    </body>
</html>