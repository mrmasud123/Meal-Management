<?php  

    include_once 'Database.php';
    $DB=new Database();

    if(isset($_POST['addMember'])){
        $member_name=$DB->escapeString($_POST['member_name']);
        $member_email=$DB->escapeString($_POST['member_email']);
        $member_seat_type=$DB->escapeString($_POST['seat_type']);
        if(empty($member_name) || empty($member_email)){
            echo json_encode(array('error'=>"Name or email empty"));
            exit;
        }else if(preg_match("/[^a-z ]/i", $member_name)){
            echo json_encode(array('error'=>"Name contains special characters."));
            exit;
        }else if(empty($member_seat_type)){
            echo json_encode(array('error'=>"Select seat type."));
            exit;
        }
        else{
            $DB->select("members","member_name",null, "member_name='$member_name'");
            if(count($DB->getResult())>0){
                echo json_encode(array('error'=>'Try different name'));
                exit;
            }else{
                $params=[
                    'member_name'=>$member_name,
                    'member_email'=>$member_email,
                    'seat_type'=>$member_seat_type
                ];
                $DB->insert("members",$params);
                if($DB->getResult()){
                    echo json_encode(array('success'=>1));
                    exit;
                }
            }
        }
    }

    //Bazar insert
    if(isset($_POST['bazar'])){
        $bazar_amt=$DB->escapeString($_POST['bazar_amt']);
        $bazar_members=explode(",", $_POST['bazar_members']);
        $total_member=count($bazar_members);
        $bazar_date=$_POST['bazar_date'];
        if(empty($_POST['bazar_members'])){
            echo json_encode(array('error'=>"Select Member First"));
            exit;
        }else if($bazar_amt < 0){
            echo json_encode(array('error'=>"Invalid Amount"));
            exit;
        }else if(empty($bazar_date)){
            echo json_encode(array('error'=>"Invalid bazar date"));
            exit;
        }else{
            $DB->select("bazar_tbl","DISTINCT(amt_paid_date)",null,null,"amt_paid_date ASC");
            $all_dates=$DB->getResult();
            $available_dates=[];
            foreach($all_dates as $mill_dates){
                array_push($available_dates,$mill_dates['amt_paid_date']);
            }
            if(in_array($bazar_date,$available_dates)){
                echo json_encode(array('error'=>"Record already inserted"));
                exit;
            }else{
             
                $init=0;
                while($init<$total_member){
                    $bm_id=$bazar_members[$init];
                    $DB->select("members","member_name",null,"member_id=$bm_id");
                    $name=$DB->getResult()[0]['member_name'];
                    $params=[
                        'member_name'=>$name,
                        'amt_paid_date'=>$bazar_date,
                        'member_id'=>$bm_id,
                        'amount'=>$bazar_amt
                    ];
                    $DB->insert("bazar_tbl",$params);
                    $init++;
                }
                echo json_encode(array('success'=>1));
                exit;
                   
            }
        }
        
    }
    //mill insert
    if(isset($_POST['mill'])){
        $mill_no=$DB->escapeString($_POST['mill_no']);
        $mill_members=explode(",", $_POST['mill_members']);
        $total_member=count($mill_members);
        $mill_date=$_POST['mill_date'];
        if(empty($_POST['mill_members'])){
            echo json_encode(array('error'=>"Select Member First"));
            exit;
        }else if($mill_no < 0){
            echo json_encode(array('error'=>"Invalid Mill Count"));
            exit;
        }else if(empty($mill_date)){
            echo json_encode(array('error'=>"Invalid mill date"));
            exit;
        }else{
            // echo $mill_date;
            $DB->select("mill_tbl","DISTINCT(mill_date)",null,null,"mill_date ASC");
            $all_dates=$DB->getResult();
            $available_dates=[];
            foreach($all_dates as $mill_dates){
                array_push($available_dates,$mill_dates['mill_date']);
            }
            if(in_array($mill_date,$available_dates)){
                echo json_encode(array('error'=>"Record already inserted"));
                exit;
            }else{
                $init=0;
            while($init<$total_member){
                $bm_id=$mill_members[$init];
                $DB->select("members","member_name",null,"member_id=$bm_id");
                $name=$DB->getResult()[0]['member_name'];
                $params=[
                    'member_name'=>$name,
                    'mill_count'=>$mill_no,
                    'member_id'=>$bm_id,
                    'mill_date'=>$mill_date
                ];
                $DB->insert("mill_tbl",$params);
                $init++;
            }
            echo json_encode(array('success'=>1));
            exit;
            }
        }
        
    }


     //member update
     if(isset($_POST['member_update'])){
        $member_id=$_POST['member_id'];
        // $update_Type=$_POST['mbmType'];
        $DB->select("members","*",null,"member_id=$member_id");
        $member_details=$DB->getResult();
        $output="";
        if(count($member_details)>0){
        foreach($member_details as $m_detail){
            $output.='<div class="record-form_container">
                <h2>Record Form</h2>
                    <span class="badge bg-success">Member/'. $m_detail['member_name'] .'</span>
                    <form action="" id="memberUpdateForm" data-memberId='. $m_detail['member_id'] .'>
                        <div class="form-group mt-2 mb-2 w-100 d-flex">
                            <input value="'. $m_detail['member_name'] .'" type="text" name="mill_member_name" class="form-control m-2">
                            <input value="'. $m_detail['member_email'] .'" type="text" name="mill_member_email" class="form-control m-2">
                            
                        </div>
                        <div class="form-group w-100 text-end pe-1">
                        <button type="submit" class="btn btn-sm btn-warning" >Update?</button>
                        </div>
                        </form>
                        <button class="btn btn-sm btn-danger deleteMember" data-memberId='. $m_detail['member_id'] .' >Delete?</button>
                    </div>
                ';
        }
    }else{
        $output.="<span class='badge bg-danger'>No record found</span>";
    }
        echo $output;
    }

    //delete member
    if(isset($_POST['deleteMember'])){
        $id=$_POST['MemId'];
        $DB->delete("members", "member_id=$id");
        $DB->delete("mill_tbl","member_id=$id");
        $DB->delete("bazar_tbl","member_id=$id");
        if($DB->getResult()){
            echo json_encode(array('success'=>1));
        }
    }

    //Update member
    if(isset($_POST['updateMember'])){
        $mill_member_name=$DB->escapeString($_POST['mill_member_name']);
        $mill_member_email=$DB->escapeString($_POST['mill_member_email']);
        $mill_member_id=$_POST['m_id'];
        if(empty($mill_member_name) || empty($mill_member_email)){
            echo json_encode(array('error'=>"Fields can not be empty"));
            exit;
        }else{
            $params=[
                'member_name'=>$mill_member_name,
                'member_email'=>$mill_member_email
            ];
            $DB->update('members',$params,"member_id=$mill_member_id");
            $DB->update('mill_tbl',['member_name'=>$mill_member_name], "member_id=$mill_member_id");
            $DB->update('bazar_tbl',['member_name'=>$mill_member_name], "member_id=$mill_member_id");
            if($DB->getResult()){
                echo json_encode(array('success'=>1));
                exit;
            }
        }
    }


    ///Mill , bazar updation

    if(isset($_POST['update_mill'])){
        // print_r($_POST);
        $mdate=$_POST['mdate'];
        $mname=$_POST['mname'];
        $mill_cnt=$_POST['millCount'];
        if($mill_cnt<0){
            echo json_encode(array('error'=>"Invalid Mill Count"));
            exit;
        }else{
            $DB->select('mill_tbl',"*",null,"mill_date='$mdate' && member_name='$mname'");
            if(count($DB->getResult())>0){

                $DB->update("mill_tbl",['mill_count'=>$mill_cnt],"member_name='$mname' && mill_date='$mdate'");
                if($DB->getResult()){
                    echo json_encode(array('success'=>1));
                    exit;
                }
            }else{
                $DB->select("members","member_id",null,"member_name='$mname'");
                $member_id=$DB->getResult();
                
                $params=[
                    'member_name'=>$mname,
                    'mill_count'=>$mill_cnt,
                    'member_id'=>$member_id[0]['member_id'],
                    'mill_date'=>$mdate
                ];
                $DB->insert("mill_tbl",$params);
                if($DB->getResult()){
                    echo json_encode(array('success'=>1));
                    exit;
                }
                
            }
        }
    }

    //Bazar update

    if(isset($_POST['update_bazar'])){
        // print_r($_POST);
        $bdate=$_POST['bdate'];
        $mname=$_POST['mname'];
        $bazar_cnt=$_POST['bazarCount'];
        if($bazar_cnt<0){
            echo json_encode(array('error'=>"Invalid Bazar Amount"));
            exit;
        }else{
            $DB->select('bazar_tbl',"*",null,"amt_paid_date='$bdate' && member_name='$mname'");
            if(count($DB->getResult())>0){

                $DB->update("bazar_tbl",['amount'=>$bazar_cnt],"member_name='$mname' && amt_paid_date='$bdate'");
                if($DB->getResult()){
                    echo json_encode(array('success'=>1));
                    exit;
                }
            }else{
                $DB->select("members","member_id",null,"member_name='$mname'");
                $member_id=$DB->getResult();
                
                $params=[
                    'member_name'=>$mname,
                    'amt_paid_date'=>$bdate,
                    'member_id'=>$member_id[0]['member_id'],
                    'amount'=>$bazar_cnt,
                ];
                $DB->insert("bazar_tbl",$params);
                if($DB->getResult()){
                    echo json_encode(array('success'=>1));
                    exit;
                }
                
            }
        }
    }

    //Flat Credentials
    if(isset($_POST['calculate_flat_credentials'])){
        
        if($_POST['flat_rent']<0){
            echo json_encode(array('error'=>"Invalid Flat Rent"));
            exit;
        }else if($_POST['service_charge']<0){
            echo json_encode(array('error'=>"Invalid Service Charge"));
            exit;
        }else if($_POST['garbage_charge']<0){
            echo json_encode(array('error'=>"Invalid Garbage Charge"));
            exit;
        }else if($_POST['electricity_bill']<0){
            echo json_encode(array('error'=>"Invalid Electricity Bill"));
            exit;
        }else if($_POST['gas_bill']<0){
            echo json_encode(array('error'=>"Invalid Gas Bill"));
            exit;
        }else if($_POST['khala_salary']<0){
            echo json_encode(array('error'=>"Invalid Khala Salary"));
            exit;
        }else{
            $DB->select('monthly_expenses',"*");
            if(count($DB->getResult()) > 0){
                $DB->sql("TRUNCATE TABLE monthly_expenses");
                $DB->sql("TRUNCATE TABLE credential");
            }
            $credential_params=[
                'flat_rent'=>$_POST['flat_rent'],
                'service_charge'=>$_POST['service_charge'],
                'garbage_charge'=>$_POST['garbage_charge'],
                'electricity_bill'=>$_POST['electricity_bill'],
                'gas_bill'=>$_POST['gas_bill'],
                'khala_salary'=>$_POST['khala_salary']
            ];
            $flag="bg-light";
            $sub_total=$_POST['flat_rent']+$_POST['service_charge']+$_POST['garbage_charge']+$_POST['electricity_bill']+$_POST['gas_bill']+$_POST['khala_salary'];
            $output="<h5 class='text-dark'>সর্বমোট : ". $sub_total ."</h5>";
            $expense=0;
            $total=0;
            $DB->select("members","*");
            $all_members=$DB->getResult();
            $total_members=count($all_members);
            $dining_space_rent=$_POST['flat_rent']*0.13;
            $other_per_person_rent=($_POST['flat_rent']-$dining_space_rent)/($total_members-1);
            $per_person_service_chrg=round($_POST['service_charge']/$total_members);
            $per_person_garbage_chrg=round($_POST['garbage_charge']/$total_members);
            $per_person_electricity_bill=round($_POST['electricity_bill']/$total_members);
            $per_person_gas_bill=round($_POST['gas_bill']/$total_members);
            $per_person_khala_salary=round($_POST['khala_salary']/$total_members);
            $output.='<table class="table table-bordered table-hover table-stripped">
                <thead>
                    <tr>
                        <th>সদস্য</th>
                        <th>সিট ভাড়া</th>
                        <th>সার্ভিস চার্জ</th>
                        <th>ময়লা বিল</th>
                        <th>বিদ্যূৎ বিল</th>
                        <th>গ্যাস বিল</th>
                        <th>খালার বেতন</th>
                        <th>সর্বমোট</th>
                    </tr>
                </thead>
                <tbody>';
                foreach($all_members as $members){
                    if($members['seat_type']=="dining"){
                        $seat_rate=$dining_space_rent;
                        $flag="bg-warning";
                    }else{
                        $seat_rate=$other_per_person_rent;
                        $flag="bg-light";
                    }
                    $expense=$seat_rate+$per_person_electricity_bill+$per_person_garbage_chrg+$per_person_service_chrg+$per_person_gas_bill+$per_person_khala_salary;
                    $total+=$expense;
                    $params=[
                        'member'=>$members['member_name'],
                        'flat_rent'=>$seat_rate,
                        'service_charge'=>$per_person_service_chrg,
                        'garbage_charge'=>$per_person_garbage_chrg,
                        'electricity_bill'=>$per_person_electricity_bill,
                        'gas_bill'=>$per_person_gas_bill,
                        'khala_salary'=>$per_person_khala_salary,
                        'total_amt'=>round($expense)
                    ];
                    $DB->insert('monthly_expenses',$params);
                    
                    $output.='<tr class="'.$flag.'">
                        <td class="text-capitalize">'.$members['member_name'].'</td>
                        <td>'. round($seat_rate ) .'</td>
                        <td>'. round($per_person_service_chrg ) .'</td>
                        <td>'. round($per_person_garbage_chrg ) .'</td>
                        <td>'. round($per_person_electricity_bill ) .'</td>
                        <td>'. round($per_person_gas_bill ) .'</td>
                        <td>'. round($per_person_khala_salary ) .'</td>
                        <td align="center"><span class="badge bg-info">'. round($expense ) .'</span></td>
                    </tr>';
                }
                $DB->insert('credential',$credential_params);

               $output.='<tr><td colspan="8" align="right"><span class="badge bg-success">'. $total .'</span></td></tr>
               </tbody></table>';

            echo json_encode(array('success'=>1,'data'=>$output));
        }
    }

    //Meal check
    if(isset($_POST['checkMealRoutine'])){
        $DB->select('meal_routine',"*");
        $meal_routine=$DB->getResult();
        echo json_encode(array('data'=>$meal_routine));
        exit;
    }

    //Meal Routine
    if(isset($_POST['meal_routine'])){
        
        $DB->select("meal_routine","*");
        if(count($DB->getResult())>0){
            $DB->sql('TRUNCATE TABLE meal_routine');
        }

        $meal_routine = [
            trim($_POST['weekday1day']),
            trim($_POST['weekday1night']),
            $_POST['weekday1'],
            trim($_POST['weekday2day']),
            trim($_POST['weekday2night']),
            $_POST['weekday2'],
            trim($_POST['weekday3day']),
            trim($_POST['weekday3night']),
            $_POST['weekday3'],
            trim($_POST['weekday4day']),
            trim($_POST['weekday4night']),
            $_POST['weekday4'],
            trim($_POST['weekday5day']),
            trim($_POST['weekday5night']),
            $_POST['weekday5'],
            trim($_POST['weekday6day']),
            trim($_POST['weekday6night']),
            $_POST['weekday6'],
            trim($_POST['weekday7day']),
            trim($_POST['weekday7night']),
            $_POST['weekday7'],
        ];

        if(in_array("",$meal_routine,true)){
            exit;
        }else{
            for ($i = 0; $i < 7; $i++) {
                $day_item = $meal_routine[$i * 3];       
                $night_item = $meal_routine[$i * 3 + 1]; 
                $day = $meal_routine[$i * 3 + 2];       
                
                $DB->insert('meal_routine', [
                    'day_item' => $day_item,
                    'night_item' => $night_item,
                    'day' => $day
                ]);
            }
            echo "1";
        }
        
    }

   
   
    
?>