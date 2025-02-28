<?php

    include_once 'Database.php';
    $DB=new Database();
    $bdate=$_GET['bdate'];
    $DB->select("bazar_tbl","*",null,"amt_paid_date='$bdate'","member_name ASC");
    $bazar_members_arr=$DB->getResult();
    $DB->select("members","member_name",null,null,"member_name ASC");
    $total_members=[];
    $available_mill_members=[];
    $all_members=$DB->getResult();
    foreach($all_members as $member){
        array_push($total_members,$member['member_name']);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mill Project</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
            <div class="mill-header bg-info py-3 text-center d-flex align-items-center flex-column">
                    <h1>Bachelor Flat - 11</h1>
                    <div class="mt-5 mill-btns w-50 d-flex align-items-center justify-content-around">
                        <a href="update-mill.php" class="btn bg-primary nav-link text-light ms-2">Update Mill</a>
                        <a href="index.php" class="btn bg-primary nav-link text-light ms-2">Home?</a>
                    </div>
                </div>
            </div>
            <div class="mill_update_container mt-5">
                <h2 class="mt-4 mb-4">Mill Update</h2>
                <form action="" id="bazarUpdateForm">
                <?php
                    foreach($total_members as $tm){
                        $DB->select("bazar_tbl","*",null,"amt_paid_date='$bdate' && member_name='$tm'");
                        $bdata=$DB->getResult();
                        if(count($bdata)>0){
                            ?>
                                <div class="form-group mt-3">
                                    <input name="bazar_check_name" type="text" readonly value="<?php echo $bdata[0]['member_name'] ?>" class="form-control">
                                    <input name="bazar_check_date" type="text" readonly value="<?php echo $bdata[0]['amt_paid_date'] ?>" class="form-control">
                                    <input name="bazar_check_count" data-memberName="<?php echo $tm; ?>"  data-bazarDate="<?php echo $bdata[0]['amt_paid_date']; ?>" type="number" value="<?php echo $bdata[0]['amount'] ?>" class="form-control bazar_check_cnt">
                                </div>
                <?php
                    }else{
                ?>
                                <div class="form-group mt-3">
                                    <input name="bazar_check_name" type="text" readonly value="<?php echo $tm; ?>" class="form-control">
                                    <input name="bazar_check_date"  type="text" readonly value="<?php echo $bdate; ?>" class="form-control">
                                    <input name="bazar_check_count" data-memberName="<?php echo $tm; ?>" data-bazarDate="<?php echo $bdate; ?>" type="number" value="0" class="form-control bazar_check_cnt">
                                </div>
                <?php
                    }
                        }
                ?>
                     </form>
            </div>
        </div>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/actions.js"></script>
</body>
</html>