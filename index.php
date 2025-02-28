<?php

    include_once 'Database.php';
    $DB=new Database();
    $DB->select("mill_tbl","*",null,"MONTHNAME(mill_date) = MONTHNAME(CURDATE())","mill_date ASC");
    $current_month=date('F');
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
                        <a href="update-mill.php" class="btn bg-primary nav-link text-light ms-2">Update Credentials</a>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#routineModal">Routine</button>
                    </div>
                </div>
                <div class="mill-contents">
                    <div class="total-mill mt-2 mb-3 d-flex flex-column">
                        <div class="total-mill-header d-flex align-items-center mb-3">
                            <h1>Total Mill : </h1>
                            <h1><span class="badge bg-success">
                            <?php 
                                $DB->select("mill_tbl","SUM(mill_count) as mill",null,"MONTHNAME(mill_date) = MONTHNAME(CURDATE())");  
                                $total_mill=0;                  
                                $total_mill_count=$DB->getResult();
                               
                                if(!empty($total_mill_count[0]['mill'])){
                                    foreach($total_mill_count as $mill_cnt){
                                       $total_mill=$mill_cnt['mill'];
                                    }
                                }
                                
                                echo $total_mill;
                            ?>
                        </span></h1>
                        </div>
                        <h5><span class="badge bg-info">Mill Rate : 
                            <?php 
                                $DB->select("bazar_tbl","SUM(amount) as total_bazar_amount",null,"MONTHNAME(amt_paid_date) = MONTHNAME(CURDATE())");
                                $bazar_amount=$DB->getResult();
                                if($total_mill>0){
                                    $mill_rate=round($bazar_amount[0]['total_bazar_amount']/$total_mill, 1);
                                    echo $mill_rate;
                                }else{
                                    echo 0;
                                }
                            ?>
                        </span></h5>
                        <h3><span class="badge bg-warning">
                            <script>
                                    const days=[
                                        'Sunday',
                                        'Monday',
                                        'Tuesday',
                                        'Wednesday',
                                        'Thursday',
                                        'Friday',
                                        'Saturday'
                                    ];
                                    var td=new Date();
                                    document.write(days[td.getDay()] +" , "+td.getDate()+"-"+td.getMonth()+"-"+td.getFullYear());
                            </script>
                            </span>
                        </h3>
                    </div>
                    <?php
                        $DB->select("mill_tbl","*",null,"MONTHNAME(mill_date) = MONTHNAME(CURDATE())");
                        if(count($DB->getResult())>0){
                    ?>
                    <div class="mill-table">
                        <table class="table table-hover table-info table-borderd table-stripped">
                            <thead>
                                <tr class="text-center">
                                    <th>Date</th>
                                    <?php  
                                        $total_members=array();
                                        $specific_mill_members=array();
                                        $specific_mill_members_mill=array();
                                        $b=0;
                                        $DB->select("members","*",null,null,"member_name ASC");
                                        $all_members=$DB->getResult();
                                        foreach($all_members as $member){
                                            array_push($total_members,$member['member_name']);
                                            echo "<th class='text-capitalize'>".$member['member_name']."</th>";
                                        }
                                    ?>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <?php
                                        $DB->select("mill_tbl","DISTINCT(mill_date)",null,"MONTHNAME(mill_date) = MONTHNAME(CURDATE())","mill_date ASC");
                                        $all_dates=$DB->getResult();
                                        foreach($all_dates as $date){
                                    ?>
                                        <tr class="text-center">
                                            <td ><?php echo $date['mill_date']; ?></td>
                                            
                                                <?php
                                                    $unique_mill_date=$date['mill_date'];
                                                    $DB->sql("SELECT * from mill_tbl where mill_date='$unique_mill_date' AND MONTHNAME(mill_date)=MONTHNAME(CURDATE()) ORDER BY member_name ASC");
                                                    $date_wise_mill_record=$DB->getResult();
                                                        foreach($date_wise_mill_record as $mills){
                                                            foreach($mills as $mill){
                                                            array_push($specific_mill_members, $mill['member_name']);    
                                                            array_push($specific_mill_members_mill, $mill['mill_count']);    
                                                        }
                                                    }
                                                    for($a=0; $a<count($total_members); $a++){
                                                        if($b<count($specific_mill_members)){
                                                            if($total_members[$a]==$specific_mill_members[$b]){
                                                                echo "<td>". $specific_mill_members_mill[$b] ."</td>";
                                                                $b++;
                                                            }else{
                                                                echo "<td>0</td>";
                                                            }
                                                        }else{
                                                            echo "<td>0</td>";
                                                        }
                                                    }
                                                ?>
                                                <td><a class="btn btn-primary btn-sm" href="edit-mill.php?mdate=<?php echo $date['mill_date'] ?>">Edit?</a></td>
                                            </tr>
                                    <?php
                                    $specific_mill_members=[];
                                    $specific_mill_members_mill=[];
                                    $a=0;
                                    $b=0;
                                     }
                                    ?>
                                    <tr class="text-center bg-success">
                                        <td><strong>Total</strong></td>
                                        <?php 
                                        $total_mill_arr=[];
                                            foreach($total_members as $tm){
                                                $DB->select("mill_tbl","SUM(mill_count) as p_w_mill,member_name",null,"member_name='$tm' AND MONTHNAME(mill_date) = MONTHNAME(CURDATE())");
                                                foreach($DB->getResult() as $getMill){
                                                    if(empty($getMill['p_w_mill'])){
                                                        array_push($total_mill_arr,"0");
                                                    }else{
                                                        array_push($total_mill_arr,$getMill['p_w_mill']);
                                                    }
                                                }
                                            }
                                            
                                            for($x=0; $x<count($total_members); $x++){
                                                $m_name=$total_members[$x];
                                                $DB->select("mill_tbl","*",null,"member_name='$m_name' AND MONTHNAME(mill_date) = MONTHNAME(CURDATE())");
                                                if(count($DB->getResult())>0){
                                                    $DB->select("mill_tbl","SUM(mill_count) as total_mill",null,"member_name='$m_name' AND MONTHNAME(mill_date) = MONTHNAME(CURDATE())");
                                                    echo '<td><strong><span class="badge bg-success">'. $DB->getResult()[0]['total_mill'].'</span></strong></td>';
                                                }else{
                                                    echo '<td><strong><span class="badge bg-success">0</span></strong></td>';
                                                }
                                            }
                                        ?>
                                        <td></td>
                                    </tr>
                            </tbody>
                        </table>
                    </div>
                    <?php
                        }else{
                            echo '<div class="w-100 text-center"><h2><span class="badge bg-danger">Add members to view details</span></h2></div>';
                        }
                        /*  */
                    ?>
                    <?php  
                        /*  */
                        $DB->select("bazar_tbl","*",null,"MONTHNAME(amt_paid_date) = MONTHNAME(CURDATE())");
                        if(count($DB->getResult())>0){
                    ?>
                    <div class="money w-100 mt-5 ">
                        <div class="deopsit-money">
                            
                                <h1 class="mb-3 header__style">Bazar Amount</h1>
                                <a href="generate-bazar.php?month=<?php echo $current_month ?>" class="btn btn-sm btn-success mb-3">Generate Bazar PDF</a>
                            
                        <table class="table table-info table-borderd table-hover table-stripped">
                            <thead>
                                <tr class="text-center">
                                    <th>Date</th>
                                    <?php  
                                        $specific_bazar_members=[];
                                        $specific_bazar_members_amount=[];
                                        $b=0;
                                        foreach($all_members as $member){
                                            echo "<th class='text-capitalize'>".$member['member_name']."</th>";
                                        }
                                        
                                    ?>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                        $DB->select("bazar_tbl","DISTINCT(amt_paid_date)",null,"MONTHNAME(amt_paid_date) = MONTHNAME(CURDATE())","amt_paid_date ASC");
                                        $bazar_dates=$DB->getResult();
                                        foreach($bazar_dates as $bdate){
                                    ?>
                                <tr class="text-center">
                                    <td><?php echo $bdate['amt_paid_date']; ?></td>
                                    <?php
                                            $unique_bazar_date=$bdate['amt_paid_date'];
                                            $DB->sql("SELECT * from bazar_tbl where amt_paid_date='$unique_bazar_date' AND MONTHNAME(amt_paid_date)=MONTHNAME(CURDATE()) ORDER BY member_name ASC");
                                            $date_wise_bazar_record=$DB->getResult();
                                                foreach($date_wise_bazar_record as $bazar){
                                                    foreach($bazar as $baz){
                                                    array_push($specific_bazar_members, $baz['member_name']);    
                                                    array_push($specific_bazar_members_amount, $baz['amount']);    
                                                }
                                            }
                                            for($a=0; $a<count($total_members); $a++){
                                                if($b<count($specific_bazar_members)){
                                                    if($total_members[$a]==$specific_bazar_members[$b]){
                                                        echo "<td>". $specific_bazar_members_amount[$b] ."</td>";
                                                        $b++;
                                                    }else{
                                                        echo "<td>0</td>";
                                                    }
                                                }else{
                                                    echo "<td>0</td>";
                                                }
                                            }
                                                ?>
                                                <td><a class="btn btn-primary btn-sm" href="edit-bazar.php?bdate=<?php echo $bdate['amt_paid_date']; ?>">Edit?</a></td>
                                          
                                </tr>
                                <?php
                                    $specific_bazar_members=[];
                                    $specific_bazar_members_amount=[];
                                    $a=0;
                                    $b=0;
                                     }
                                    ?>
                                    <tr class="text-center bg-success">
                                        <td><strong>Total</strong></td>
                                        <?php 
                                        $person_wise_bazar_arr=[];
                                            foreach($total_members as $tm){
                                                $DB->select("bazar_tbl","SUM(amount) as p_w_amount,member_name",null,"member_name='$tm' AND MONTHNAME(amt_paid_date) = MONTHNAME(CURDATE())");
                                                foreach($DB->getResult() as $getBazar){
                                                    if(empty($getBazar['p_w_amount'])){
                                                        array_push($person_wise_bazar_arr,"0");
                                                    }else{
                                                        array_push($person_wise_bazar_arr,$getBazar['p_w_amount']);
                                                    }
                                                }
                                            }
                                           foreach($person_wise_bazar_arr as $single_person_bazar){
                                            echo '<td><strong><span class="badge bg-primary">'. $single_person_bazar.'</span></strong></td>';
                                           }
                                        ?>
                                        <td><?php echo $bazar_amount[0]['total_bazar_amount']; ?></td>
                                    </tr>
                                    <tr class="text-center ">
                                        <td><strong>Expense</strong></td>
                                        <?php 
                                        $total_expense=0;
                                           for($a=0; $a<count($total_members); $a++){
                                            $total_expense+=round($total_mill_arr[$a] * $mill_rate, 1);
                                                echo "<td><strong><span class='badge bg-secondary'>".round($total_mill_arr[$a] * $mill_rate, 1)."</span></strong></td>";
                                            } 
                                        ?>
                                        <td><?php echo $total_expense; ?></td>
                                    </tr>
                                    <tr class="text-center ">
                                        <td><strong>Due/Pay</strong></td>
                                        <?php 
                                        $give=0;
                                        $take=0;
                                           for($a=0; $a<count($total_members); $a++){
                                                $result=round($person_wise_bazar_arr[$a]-($total_mill_arr[$a] * $mill_rate), 1);
                                                if($result>=0){
                                                    $take+=$result;
                                                    echo "<td><strong><span class='badge bg-success'>".$result."</span></strong></td>";
                                                }else{
                                                    $give+=$result;
                                                    echo "<td><strong><span class='badge bg-danger'>".$result."</span></strong></td>";
                                                }
                                           } 
                                        ?>
                                        <td class="d-flex justify-content-between">
                                            <div class="give">
                                                <span class="badge bg-warning">Give</span><br>
                                                <span class="badge bg-danger"><?php echo $give; ?></span>
                                            </div>
                                            |
                                            <div class="take">
                                                <span class="badge bg-warning">Take</span><br>
                                                <span class="badge bg-success"><?php echo $take; ?></span>
                                            </div>
                                        </td>
                                    </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                    
                    <?php
                        }else{
                            echo '<div class="w-100 text-center"><h2><span class="badge bg-danger">Add Bazar to view details</span></h2></div>';
                        }
                        /*  */
                    ?>
                </div>
            </div>
        </div>
    </div>
             
    <!-- mill routine -->
    <div class="modal fade" id="routineModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width:800px">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title text-dark" id="exampleModalLabel">Flat - 11 | Monthly Expense</h5>
        </div>
        <div class="modal-body meal_table">
            <table class="table table-bordered table-stripped table-hover text-dark">
                <thead>
                    <tr>
                        <th>Week Day</th>
                        <th>Day</th>
                        <th>Night</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
        </div>
        </div>
    </div>
</div>
    <!--  -->
    <script src="js/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="js/actions.js"></script>
</body>
</html>