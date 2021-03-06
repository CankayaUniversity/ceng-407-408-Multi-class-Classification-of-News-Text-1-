<?php

$db = mysqli_connect('localhost', 'root', '', 'mtlbl');

$modelName = "";
	$modelPath = "";
	$modelID = 0;
$modelVec = 0;
$modelEp= 0;
$modelLabel = "";
$modelRatio = 0;
	$update = false;

function format_folder_size($size)
{
 if ($size >= 1073741824)
 {
  $size = number_format($size / 1073741824, 2) . ' GB';
 }
    elseif ($size >= 1048576)
    {
        $size = number_format($size / 1048576, 2) . ' MB';
    }
    elseif ($size >= 1024)
    {
        $size = number_format($size / 1024, 2) . ' KB';
    }
    elseif ($size > 1)
    {
        $size = $size . ' bytes';
    }
    elseif ($size == 1)
    {
        $size = $size . ' byte';
    }
    else
    {
        $size = '0 bytes';
    }
 return $size;
}

function get_folder_size($folder_name)
{
 $total_size = 0;
 $file_data = scandir($folder_name);
 foreach($file_data as $file)
 {
  if($file === '.' or $file === '..')
  {
   continue;
  }
  else
  {
   $path = $folder_name . '/' . $file;
   $total_size = $total_size + filesize($path);
  }
 }
 return format_folder_size($total_size);
}

if(isset($_POST["action"]))
{
 if($_POST["action"] == "fetch")
 {
  $folder = array_filter(glob('*'), 'is_dir');

  $output = '
  <table class="table table-bordered table-striped">
   <tr>
    <th>Model Name</th>
    <th>Total File</th>
    <th>Size</th>
    <!--<th>Update</th>-->
    <th>Delete</th>
    <!--<th>Upload File</th>-->
    <th>View Files</th>
    <!--<th>Classify</th>-->
   </tr>
   ';
  if(count($folder) > 0)
  {
   foreach($folder as $name)
   {
    $output .= '
     <tr>
      <td>'.$name.'</td>
      <td>'.(count(scandir($name)) - 2).'</td>
      <td>'.get_folder_size($name).'</td>
      <!--<td><button type="button" name="update" data-name="'.$name.'" class="update btn btn-warning btn-xs">Update</button></td>-->
      <td><button type="button" name="delete" data-name="'.$name.'" class="delete btn btn-danger btn-xs">Delete</button></td>
      <!--<td><button type="button" name="upload" data-name="'.$name.'" class="upload btn btn-info btn-xs">Upload File</button></td>-->
      <td><button type="button" name="view_files" data-name="'.$name.'" class="view_files btn btn-default ">View Files</button></td>
      <!--<td><button type="button" name="train" data-name="'.$name.'" class="train btn btn-default ">Train</button></td>-->
     </tr>';
   }
  }
  else
  {
   $output .= '
    <tr>
     <td colspan="6">No Queue Found</td>
    </tr>
   ';
  }
  $output .= '</table>';
  echo $output;
 }

 if($_POST["action"] == "create")
 {
  if(!file_exists($_POST["folder_name"]))
  {
   mkdir($_POST["folder_name"], 0777, true);
   echo 'Dataset Created';
  }
  else
  {
   echo 'Dataset Already Created';
  }
 }
 if($_POST["action"] == "change")
 {
  if(!file_exists($_POST["folder_name"]))
  {
   rename($_POST["old_name"], $_POST["folder_name"]);
   echo 'Dataset Name Changed';
  }
  else
  {
   echo 'Dataset already has the same name';
  }
 }

    function disable_ob() {
    // Turn off output buffering
    ini_set('output_buffering', 'off');
    // Turn off PHP output compression
    ini_set('zlib.output_compression', false);
    // Implicitly flush the buffer(s)
    ini_set('implicit_flush', true);
    ob_implicit_flush(true);
    // Clear, and turn off output buffering
    while (ob_get_level() > 0) {
        // Get the curent level
        $level = ob_get_level();
        // End the buffering
        ob_end_clean();
        // If the current level has not changed, abort
        if (ob_get_level() == $level) break;
    }
    // Disable apache output buffering/compression
    if (function_exists('apache_setenv')) {
        apache_setenv('no-gzip', '1');
        apache_setenv('dont-vary', '1');
    }
}





 if($_POST["action"] == "delete")
 {
  $files = scandir($_POST["folder_name"]);
  foreach($files as $file)
  {
   if($file === '.' or $file === '..')
   {
    continue;
   }
   else
   {
    unlink($_POST["folder_name"] . '/' . $file);
   }
  }
  if(rmdir($_POST["folder_name"]))
  {
   echo 'Dataset Deleted';
  }
 }

 if($_POST["action"] == "fetch_files")
 {
  $file_data = scandir($_POST["folder_name"]);
  $output = '
  <table class="table table-bordered table-striped">
   <tr>
<!--   <th>Image</th>-->
    <th>File Name</th>
    <th>Delete</th>
    <th>Classify</th>
   </tr>
  ';

  foreach($file_data as $file)
  {
   if($file === '.' or $file === '..')
   {
    continue;
   }
   else
   {
    $path = $_POST["folder_name"] . '/' . $file;
    $output .= '
    <tr>
     <!--<td><img src="'.$path.'" class="img-thumbnail" height="50" width="50" /></td>-->
     <td contenteditable="true" data-folder_name="'.$_POST["folder_name"].'"  data-file_name = "'.$file.'" class="change_file_name">'.$file.'</td>
     <td><button name="remove_file" class="remove_file btn btn-danger btn-xs" id="'.$path.'">Remove</button></td>
     <td><button name="classify_file" class="classify_file btn btn-default btn-xs" id="'.$path.'">Classify</button></td>
    </tr>
    ';
   }
  }
  $output .='</table>';
  echo $output;
 }

 if($_POST["action"] == "remove_file")
 {
  if(file_exists($_POST["path"]))
  {
   unlink($_POST["path"]);
   echo 'File Deleted';
  }
 }

     if($_POST["action"] == "classify")
 {
  if(file_exists($_POST["path"]))
  {
      $model_name =  $_POST["model_name"];
   $vec_dim =  $_POST["vec_dim"];

      $labels = $_POST["labels"];
      $test_ratio = $_POST["test_ratio"];
    $epoch = $_POST["epoch"];
      $path = $_POST["path"];
      $val = substr($path, strrpos($path, '/') + 1);
//$val = end(explode('/', $path));

      $a = popen("python -u D:\\xampp\\htdocs\\mtlbl\\webpage\\classify.py $model_name $vec_dim $test_ratio $epoch $path $val $labels", "r");

      while (!feof($a)) {
          $buffer = fgets($a);
        echo "$buffer<br>\n";
        ob_flush();
        }
        pclose($a);
   //echo 'Success!';
  }
 }

 if($_POST["action"] == "change_file_name")
 {
  $old_name = $_POST["folder_name"] . '/' . $_POST["old_file_name"];
  $new_name = $_POST["folder_name"] . '/' . $_POST["new_file_name"];
  if(rename($old_name, $new_name))
  {
   echo 'File name changed successfully';
  }
  else
  {
   echo 'There is an error';
  }
 }

     if($_POST["action"] == "classify_files")
 {
  if(file_exists($_POST["path"]))
  {

      $folder_name = $_POST["folder_name"];
       $query= mysqli_query($db, "SELECT * FROM model where modelName = '$folder_name'");
     $row = mysqli_fetch_array($query);
        $modelName = $row['modelName'];
        $modelVec = $row['modelVec'];
        $modelEp = $row['modelEp'];
      $modelLabel = $row['modelLabel'];
      $modelRatio = $row['modelRatio'];
      $datasetName = $row['datasetName'];


    echo json_encode(array($modelName, $modelVec, $modelEp, $modelLabel, $modelRatio, $datasetName));

  /* $folder_name = $_POST["folder_name"];
      $vec_dim =  $_POST["vec_dim"];
      $model_name =  $_POST["model_name"];
      $labels = $_POST["labels"];
      $test_ratio = $_POST["test_ratio"];
    $epoch = $_POST["epoch"];
*/
//disable_ob();

      /*header("Content-type: text/plain");

// tell php to automatically flush after every output
// including lines of output produced by shell commands


$command = "python D:\\xampp\\htdocs\\mtlbl\\webpage\\admin\\datasets\\train.py $folder_name $vec_dim";
system($command);*/


      /*$cmd = "python D:\\xampp\\htdocs\\mtlbl\\webpage\\admin\\datasets\\train.py $folder_name $vec_dim ";*/


      /* $a = popen("python -u D:\\xampp\\htdocs\\mtlbl\\webpage\\admin\\classif.py $folder_name $vec_dim $test_ratio $epoch $model_name $labels", "r");


      while (!feof($a)) {
          $buffer = fgets($a);
        echo "$buffer<br>\n";
        ob_flush();
        }
        pclose($a);*/
//$modelPath = 'models\\' . $folder_name;
      /*mysqli_query($db, "INSERT INTO model (modelName, modelPath, modelVec, modelEp, modelLabel, modelRatio, datasetName) VALUES ('$model_name', '$model_name', '$vec_dim', '$epoch', '$labels', '$test_ratio', '$folder_name')");*/




      #this is test for CLI
      #$output = shell_exec('dir');
      #echo $output;



      /*echo $_POST["folder_name"];
       echo $_POST["vec_dim"];
       echo $_POST["labels"];
       echo $_POST["test_ratio"];
       echo $_POST["epoch"];*/



  # echo 'Train Process Started!';
  }
  else
  {
   echo 'There is an error!';
  }
 }

}
?>
