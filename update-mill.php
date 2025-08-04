<?php 
    include_once 'Database.php';
    $DB=new Database();
    $DB->select("mill_tbl","SUM(mill_count) as mill",null,"MONTHNAME(mill_date) = MONTHNAME(CURDATE())");                
    $currentMonth = date('F');
    $currentYear = date('Y');
    $total_mill_count=$DB->getResult();
    $DB->select("members","*");
    $all_members=$DB->getResult();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Mill</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-12 d-flex flex-column align-items-center">
                <div class="mill-header header__style text-center d-flex align-items-center flex-column">
                    <h1>Bachelor Flat - 11</h1>
                    <div class="mt-5 mill-btns w-50 d-flex align-items-center justify-content-around">
                        <a href="index.php" class="btn btn-sm btn-success">Home</a>
                        <a href="update-mill.php" class="btn-sm btn bg-primary nav-link text-light ms-2">Update Credentials</a>
                        <a href="flat_credentials.php" class="btn btn-sm btn-warning">Add/Update Utilites</a>
                    </div>
                </div>
                <div class="mill-updatation-container w-100 d-flex">
                    <!--  -->
                    <div class="insertion-container w-100 p-3">
                    <form action="">
                            <div class="form-group choose_action">
                                <label for="updateType">Choose Entry Type</label>
                                <select id="updateType" class="form-control mt-2" name="updateType">
                                    <option value="" disabled selected>Choose Type</option>
                                    <option value="mill" <?php echo count($all_members) ==0 ? "disabled":"" ?>>Mill</option>
                                    <option value="bazar" <?php echo $total_mill_count[0]['mill']==null? "disabled":"" ?>>Bazar</option>
                                    <option value="member">Member</option>
                                    <option value="expense" <?php echo count($all_members) ==0 ? "disabled":"" ?>>Monthly Expense</option>
                                    <option value="meal_routine">Meal Routine</option>
                                </select>
                            </div>
                        </form>
                        <div class="p-3 mt-2 updation-container">
                            <div class="rounded p-4 mill-update-form m-1 millForm">
                                <h2 class="mt-2 mb-2  ">Mill Update</h2>
                                <form action="" id="millFormSubmit">
                                    <a href="#" class="btn-sm btn btn-warning m-2">
                                        <input id="selectAll" name="allMember" type="checkbox">
                                        <label for="selectAll">Select all</label>
                                    </a>
                                <div class="form-group">
                                
                                        <ul>
                                        <?php foreach($all_members as $member){ ?>
                                                <li class="btn btn-primary list-item m-2">
                                                    <input data-miller-id="<?php echo $member['member_id'] ?>" class="mill_member_check" id="member<?php echo $member['member_id']; ?>" name="member<?php echo $member['member_id']; ?>" type="checkbox">
                                                    <label class="text-capitalize" for="member<?php echo $member['member_id']; ?>"><?php echo $member['member_name']; ?></label>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                    <div class="form-group mt-2 mb-2">
                                        <label for="mill_number" class="">Insert Mill Number</label>
                                        <input id="mill_number" name="mill_no" class="form-control" type="number" placeholder="Mill Number">
                                    </div>
                                    <div class="form-group">
                                        <label for="millDate" class=" ">Choose Date</label>
                                        <input id="millDate" name="mill_date" class="form-control" type="date">
                                    </div>
                                    <button class="mt-2 btn btn-warning">Add</button>
                                </form>
                            </div>
                            <div class="rounded p-4 mill-update-form m-1 meal_routine">
                                
                            </div>
                            <div class="rounded p-4 bazar-update-form m-1 bazarForm">
                                <h2 class=" ">Person Wise Bazar Amount</h2>
                                <form action="" id="bazarAmtForm">
                                    <div class="form-group">
                                        <ul>
                                            <?php foreach($all_members as $member){ ?>
                                                <li class="btn btn-primary list-item m-2">
                                                    <input data-bazar-id="<?php echo $member['member_id'] ?>" class="bazar_member_checked" id="bazar_member<?php echo $member['member_id']; ?>" name="bazar_member<?php echo $member['member_id']; ?>" type="checkbox">
                                                    <label class="text-capitalize" for="bazar_member<?php echo $member['member_id']; ?>"><?php echo $member['member_name']; ?></label>
                                                </li>
                                            <?php } ?>
                                                                                
                                        </ul>
                                    </div>
                                    <div class="form-group mt-2 mb-2">
                                        <label for="bazarAmt" class=" ">Insert Amount</label>
                                        <input class="form-control" name="bazar_amt" type="number" id="bazarAmt" placeholder="Amount">
                                    </div>
                                    <div class="form-group">
                                        <label for="bazarDate" class=" ">Choose Date</label>
                                        <input id="bazarDate" name="bazar_date" class="form-control" type="date">
                                    </div>
                                    <button class="mt-2 btn btn-warning">Add</button>
                                </form>
                            </div>
                            <div class="rounded p-4 member-update-form m-1 memberForm">
                                <h2>Add New Member</h2>
                                <form action="" id="addMemberForm">
                                    <div class="form-group mt-2 mb-2">
                                        <label for="memberName" class=" ">Enter Member Name</label>
                                        <input id="memberName" name="member_name" class="form-control" type="text" placeholder="Enter Name">
                                    </div>
                                    <div class="form-group mt-2 mb-2">
                                        <label for="memberEmail" class=" ">Enter Member E-mail</label>
                                        <input id="memberEmail" name="member_email" class="form-control" type="email" placeholder="Enter Email">
                                    </div>
                                    <div class="form-group mt-2 mb-2">
                                        <label for="seat_type">Choose Seat Type</label>
                                        <select name="seat_type" id="seat_type" class="form-control">
                                            <option value="" selected>Select Seat Type</option>
                                            <option value="room_a" >Room A (Attatch washroom)</option>
                                            <option value="room_b" >Room B (Attatch balcony)</option>
                                            <option value="room_c" >Room C (No Attatch washroom or balcony)</option>
                                            <option value="dining" >Dining</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="mt-2 btn btn-warning">Add</button>
                                </form>
                            </div>
                            <div class="rounded p-4 monthly-expense-form m-1 expenseForm">
                                <div class="d-flex align-items-center justify-content-between">
                                <h5>Add Monthly Expense</h5>
                                </div>
                                <?php 
                                    $is_disabled='';
                                    foreach($all_members as $member){
                                        if($member['seat_rent'] == null){
                                            echo "<h3><span class='badge bg-danger'>".$member['member_name']."'s seat rent not found</span></h3>";
                                            $is_disabled='disabled ';
                                        }
                                    }
                                ?>
                                    <form action="" id="monthlyExpenseForm">
                                            <div class="form-group mt-2 mb-2">
                                                <label for="flat_rent" class=" ">ফ্ল্যাট ভাড়া</label>
                                                <input id="flat_rent" name="flat_rent" class="form-control" type="number">
                                            </div>
                                            <div class="form-group mt-2 mb-2">
                                                <label for="service_charge" class=" ">সার্ভিস চার্জ</label>
                                                <input id="service_charge" name="service_charge" class="form-control" type="number">
                                            </div>
                                            <div class="form-group mt-2 mb-2">
                                                <label for="garbage_charge" class=" ">ময়লা বিল</label>
                                                <input id="garbage_charge" name="garbage_charge" class="form-control" type="number">
                                            </div>
                                            <div class="form-group mt-2 mb-2">
                                                <label for="electricity_bill" class=" ">বিদ্যূৎ বিল</label>
                                                <input id="electricity_bill" name="electricity_bill" class="form-control" type="number">
                                            </div>
                                            <div class="form-group mt-2 mb-2">
                                                <label for="gas_bill" class=" ">গ্যাস বিল</label>
                                                <input id="gas_bill" name="gas_bill" class="form-control" type="number">
                                            </div>
                                            <div class="form-group mt-2 mb-2">
                                                <label for="gas_bill" class=" ">খালা বেতন</label>
                                                <input id="gas_bill" name="khala_salary" class="form-control" type="number">
                                            </div>
                                            <button <?php echo $is_disabled; ?> type="submit" class="mt-2 btn btn-warning calclate_expense_btn" >হিসাব করুন</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <!--  -->
                    <div class="updatation-container w-100 p-3">
                        <div class="prev-mill-history">
                            <h2 class="header__style">Previous Mill</h2>

                            
                            <?php 
                            /* $DB->select("mill_tbl","DISTINCT(MONTHNAME(mill_date)) as mill_month",null,null);
                                $attr="btn-primary";
                                foreach($DB->getResult() as $prev_mill){
                                    if($prev_mill['mill_month']==$currentMonth){
                                        $attr="btn-warning";
                                    }
                                    echo "<a href='mill_history.php?month=".$prev_mill['mill_month']."' class='btn ".$attr." btn-sm m-1'>".$prev_mill['mill_month']."</a>";
                                } */
                                $DB->select("mill_tbl", "DISTINCT(MONTHNAME(mill_date)) as mill_month, YEAR(mill_date) as mill_year", null, null);
                                foreach($DB->getResult() as $prev_mill){
                                    $monthYear = $prev_mill['mill_month'] . " " . $prev_mill['mill_year'];
                                    
                                    $attr = ($prev_mill['mill_month'] == $currentMonth && $prev_mill['mill_year'] == $currentYear) 
                                        ? "btn-warning" 
                                        : "btn-primary";
                                    
                                    echo "<a href='mill_history.php?month=" . $prev_mill['mill_month'] . "&year=" . $prev_mill['mill_year'] . "' class='btn " . $attr . " btn-sm m-1'>" . $monthYear . "</a>";
                                }
                                ?>

                        </div>
                        <div class="member-update">
                        <h2 class="header__style">Update Member</h2>
                            <div class="form-group mt-2 mb-3">
                                <select class="form-control mt-2" name="member" id="member">
                                    <option value="" selected disabled>Choose Member</option>
                                    <?php 
                                        $DB->select("members","*",null,null,"member_name ASC");
                                        $all_members=$DB->getResult();
                                        foreach($all_members as $member){
                                            echo "<option value=". $member['member_id'] .">". $member['member_name'] ."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        <div class="record-form"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--  -->
    <!-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"> -->
    <div id="mill_expense_modal">
        <div class="modal-dialog" role="document" style="max-width:800px">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark" id="exampleModalLabel">Flat - 11 | Monthly Expense</h5>
            </div>
            <div class="modal-body">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary modal_close_btn" data-dismiss="modal">Close</button>
                <a href="make-pdf.php" type="button" class="btn btn-primary">Generate PDF</a>
            </div>
            </div>
        </div>
    </div>

    <!--  -->
    <script src="js/jquery.min.js"></script>
    <script>
        $('#myModal').on('shown.bs.modal', function () {
        $('#myInput').trigger('focus')
        })
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="js/actions.js"></script>

</body>
</html>

