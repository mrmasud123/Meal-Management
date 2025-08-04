<?php

    include_once 'Database.php';
    $DB=new Database();
    $DB->select("mill_tbl","*",null,"MONTHNAME(mill_date) = MONTHNAME(CURDATE())","mill_date ASC");
    $current_month=date('F');
    $DB->select("members","*", null, null, "member_name ASC");
    $all_members=$DB->getResult();
    $DB->select("seat_types","*");
    $seat_types=$DB->getResult();
    $DB->sql("SELECT COUNT(seat_type) as dining from members where seat_type='dining'");
    $dining_flag= $DB->getResult();
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
            <div class="col-12 ">
                <div class="mill-header header__style py-3 text-center d-flex align-items-center flex-column">
                    <h1>Bachelor Flat - 11</h1>
                    <div class="mt-5 mill-btns w-50 d-flex align-items-center justify-content-around">
                        <a href="index.php" class="btn btn-sm btn-success">Home</a>
                        <a href="update-mill.php" class="btn-sm btn bg-primary nav-link text-light ms-2">Update Credentials</a>
                        <a href="flat_credentials.php" class="btn btn-sm btn-warning">Add/Update Utilites</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card" style="color:black">
                <div class="card-header">
                    <h5>Header</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="form-group d-flex gap-2">
                            <input <?php echo $dining_flag[0][0]['dining'] != 0? 'checked':""; ?> type="checkbox" id="dining" />
                            <label for="dining" class="btn btn-primary mb-0">
                                Dining
                            </label>
                        </div>
                        
                        <?php
                        $total_flat_rent=0;
                        foreach($all_members as $member){
                            $total_flat_rent+= $member['seat_rent'];
                        ?>
                            <div class="form-group row align-items-center mt-2 mb-2">
                                <label for="member_name" class="col-md-3 col-form-label">
                                    <?php echo htmlspecialchars($member['member_name']); ?>
                                </label>
                                <div class="col-md-5">
                                    <select name="seat_type" id="seat_type" class="form-control">
                                            <?php 
                                                foreach($seat_types as $seat){
                                                    $selected= $member['seat_type'] == $seat['slug'] ? 'selected':'';
                                                    echo "<option value='".$seat['slug']."' $selected>".$seat['seat_type']."</option>";
                                                }
                                            ?>
                                        </select>
                                </div>
                                <div class="col-md-4">
                                    <input id="seat_rent" name="seat_rent" value="<?php echo $member['seat_rent'] ?>" class="form-control" type="number" placeholder="Enter Seat Rent">
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                        <table style="width: 100%;">
                            <tr>
                                <td style="text-align: right;"><h4><span class="badge bg-success"><?php echo $total_flat_rent ?></span></h4></td>
                            </tr>
                        </table>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
