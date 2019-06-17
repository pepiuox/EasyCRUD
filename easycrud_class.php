<?php

/**
 * @license
 * Copyright(c) 2002-2019 Jose Ricardo Mantilla Mantilla. All Rights Reserved.
 * Author: Jose Ricardo Mantilla Mantilla <contact@pepiuox.net> / <contact@labemotion.net>
 * Website Author: http://pepiuox.net/ / http://labemotion.net/
 * Author's licenses: http://pepiuox.net/license / http://labemotion.net/license
 * Project Name: EasyCRUD
 * 
 */
class EasyCRUD {

// view list 
    function viewList($tble) {
        global $link;
        echo '<form method="POST">' . "\n";
        echo '<table class="table table-bordered">' . "\n";
        echo '<thead class="bg-success">' . "\n";
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
    }

//add item
    function addItem($tble, $idCol) {
        global $link;
        echo '<form class="form-horizontal">
<fieldset>

<!-- Form Name -->
<legend>' . $tble . '</legend>';
        $addQuery = 'SELECT * FROM ' . $tble;
        $addResult = $link->query($addQuery);

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
    }

// editItem
    function editItem($tble, $id, $idCol) {
        global $link;
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
    }

//deleteItem
    function deleteItem($tble, $id, $idCol) {
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

//addpost
    function addPost($tble, $ncol) {
        global $link;
        $query = "SELECT * FROM " . $tble;
        $result = $link->query($query);
        $r = 0;
        $varnames = array();
        while ($r < mysqli_num_fields($result)) {
            $info = mysqli_fetch_field($result);
            if ($info->name != $ncol) {
                if ($info->type === '10') {
                    $varnames[] = '$' . $info->name . ' = date("Y-m-d", strtotime($_POST["' . $info->name . '"])); ' . "\r\n";
                } else {
                    $varnames[] = '$' . $info->name . ' = $_POST["' . $info->name . '"]; ' . "\r\n";
                }
            }
            $r = $r + 1;
//return $varnames;
        }
        return implode("", $varnames);
    }

//addttl
    function addTtl($tble, $ncol) {
        global $link;
        $query = "SELECT * FROM " . $tble;
        $result = $link->query($query);
        $checkd = array();
        $r = 0;
        while ($r < mysqli_num_fields($result)) {
            $info = mysqli_fetch_field($result);
            if ($info->name != $ncol) {
                $checkd[] = '`' . $info->name . '`';
            }

            $r = $r + 1;
        }
        return implode(" , ", $checkd);
    }

//addtpost
    function addTPost($tble, $ncol) {
        global $link;
        $query = "SELECT * FROM " . $tble;
        $result = $link->query($query);
        $checkd = array();
        $r = 0;
        while ($r < mysqli_num_fields($result)) {
            $info = mysqli_fetch_field($result);
            if ($info->name != $ncol) {
                $checkd[] = "'$" . $info->name . "'";
            }
            $r = $r + 1;
        }
        return implode(" , ", $checkd);
    }

//ifempty
    function ifEmpty($tble, $ncol) {
        global $link;
        $query = "SELECT * FROM " . $tble;
        $result = $link->query($query);
        $checkd = array();
        $r = 0;
        while ($r < mysqli_num_fields($result)) {
            $info = mysqli_fetch_field($result);
            if ($info->name != $ncol) {
                $checkd[] = '!empty($_POST["' . $info->name . '"])';
            }

            $r = $r + 1;
        }
        return implode(" && ", $checkd);
    }

//ifmpty
    function ifMpty($tble, $ncol) {
        global $link;
        $query = "SELECT * FROM " . $tble;
        $result = $link->query($query);
        $checkd = array();
        $r = 0;
        while ($r < mysqli_num_fields($result)) {
            $info = mysqli_fetch_field($result);
            if ($info->name != $ncol) {
                $checkd[] = '!empty($' . $info->name . ')';
            }

            $r = $r + 1;
        }
        return implode(" && ", $checkd);
    }

//updatedata
    function updateData($tble, $ncol) {
        global $link;
        $query = "SELECT * FROM " . $tble;
        $result = $link->query($query);
        $varnames = array();
        $r = 0;
        while ($r < mysqli_num_fields($result)) {
            $name = mysqli_fetch_field($result);

            if ($name->name != $ncol) {
                $varnames[] = $name->name . " = '$" . $name->name . "'";
            }
            $r = $r + 1;
        }
        return implode(", ", $varnames);
    }

    function supdateData($tble) {
        global $link;
        $query = "SELECT * FROM " . $tble;
        $result = $link->query($query);
        $varnames = array();
        $r = 0;
        while ($r < mysqli_num_fields($result)) {
            $name = mysqli_fetch_field($result);
            $varnames[] = $name->name . ': $' . $name->name;
            $r = $r + 1;
        }
        echo implode(", ", $varnames);
    }

    function supdateD($tble) {
        global $link;
        $query = "SELECT * FROM " . $tble;
        $result = $link->query($query);
        $varnames = array();
        $r = 0;
        while ($r < mysqli_num_fields($result)) {
            $name = mysqli_fetch_field($result);
            $varnames[] = $name->name . ':' . $name->name;
            $r = $r + 1;
        }
        echo implode(", ", $varnames);
    }

    function addReq($tble, $ncol) {
        global $link;
        $query = "SELECT * FROM " . $tble;
        $result = $link->query($query);
        $r = 0;
        $varnames = '';
        while ($r < mysqli_num_fields($result)) {
            $info = mysqli_fetch_field($result);
            if ($info->name != $ncol) {
                $varnames = '$' . $info->name . ' = mysqli_real_escape_string($link,$_REQUEST["' . $info->name . '"]); ' . "\n\r";
            }
            $r = $r + 1;
            return $varnames;
        }
    }

    function addReqch($tble, $ncol) {
        global $link;
        $query = "SELECT * FROM " . $tble;
        $result = $link->query($query);
        $checkd = array();
        $r = 0;
        while ($r < mysqli_num_fields($result)) {
            $info = mysqli_fetch_field($result);
            if ($info->name != $ncol) {
                $checkd[] = "' " . $info->name . " : $" . $info->name . " '";
            }

            $r = $r + 1;
        }
        return implode(" , ", $checkd);
    }

    function addvTtl($tble, $ncol) {
        global $link;
        $query = "SELECT * FROM " . $tble;
        $result = $link->query($query);
        $checkd = array();
        $r = 0;
        while ($r < mysqli_num_fields($result)) {
            $info = mysqli_fetch_field($result);
            if ($info->name != $ncol) {
                $checkd[] = "'$" . $info->name . "'";
            }

            $r = $r + 1;
        }
        return implode(" , ", $checkd);
    }

    function sValues($tble, $ncol) {
        global $link;
        $query = "SELECT * FROM " . $tble;
        $result = $link->query($query);

        $r = 0;
        while ($r < mysqli_num_fields($result)) {
            $info = mysqli_fetch_field($result);
            if ($info->name != $ncol) {
                $checkd = 'var ' . $info->name . ' = $("#' . $info->name . '").val();' . "\n";
                echo $checkd;
            }
            $r = $r + 1;
        }
    }

}
