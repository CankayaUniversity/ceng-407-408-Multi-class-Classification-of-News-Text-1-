<?php
    include('../includes/db.php');
    if(isset($_GET['status']))
    {
        $status1=$_GET['status'];
        $select=mysqli_query($con, "select * from user where uID='$status1'");
        while($row=mysqli_fetch_object ($select))
        {
            $status_var=$row->isActive;
            $name_var = $row->uName;
            if($status_var=='0')
            {
                $status_state=1;
                if (!file_exists("../users/" .$name_var)) {
                    mkdir("../users/". $name_var);
                    mkdir("../users/". $name_var. "/classify");

                    $files = ['action.php','upload.php','classify.php'];
                    $yey = "../users/". $name_var. "/classify/";

                    foreach($files as $resFile){
                        //rename($resFile, 'newfolder/'.$resFile);
                        copy($resFile, $yey .$resFile);
                        }
                    echo 'success!';
                }
            }
            else
            {
                $status_state=0;
            }
            $update=mysqli_query($con, "update user set isActive='$status_state' where uID='$status1' ");
            if($update)
            {
                header("Location:members.php");
            }
            else
            {
                echo mysqli_error();
            }
        }
?>
    <?php
    }
    ?>
