<?php
    $month=$_GET['month'];
    include_once 'Database.php';
    $DB=new Database();
    $total_members=array();
    $members_email=array();
    $DB->select("members","*",null,null,"member_name ASC");
    $all_members=$DB->getResult();
    foreach($all_members as $member){
        array_push($total_members,$member['member_name']);
        array_push($members_email,$member['member_email']);
    }

    $DB->select("mill_tbl","SUM(mill_count) as mill",null,"MONTHNAME(mill_date)='$month'");  

    $total_mill=0;                  
    $total_mill_count=$DB->getResult();
                               
    if(!empty($total_mill_count[0]['mill'])){
        foreach($total_mill_count as $mill_cnt){
            $total_mill=$mill_cnt['mill'];
        }
    }

    $DB->select("bazar_tbl","SUM(amount) as total_bazar_amount",null,"MONTHNAME(amt_paid_date)='$month'");
    $mill_rate=0;
    $bazar_amount=$DB->getResult();
    if($total_mill>0){
        $mill_rate=round($bazar_amount[0]['total_bazar_amount']/$total_mill, 1);               
    }else{
        $mill_rate=0;
    }

    $total_mill_arr=[];
    foreach($total_members as $tm){
    $DB->select("mill_tbl","SUM(mill_count) as p_w_mill,member_name",null,"member_name='$tm' AND MONTHNAME(mill_date)='$month'");
    foreach($DB->getResult() as $getMill){
    if(empty($getMill['p_w_mill'])){
        array_push($total_mill_arr,"0");
    }else{
        array_push($total_mill_arr,$getMill['p_w_mill']);
    }
}}


$person_wise_bazar_arr=[];
foreach($total_members as $tm){
$DB->select("bazar_tbl","SUM(amount) as p_w_amount,member_name",null,"member_name='$tm' AND MONTHNAME(amt_paid_date)='$month'");
foreach($DB->getResult() as $getBazar){
if(empty($getBazar['p_w_amount'])){
    array_push($person_wise_bazar_arr,"0");
    }else{
    array_push($person_wise_bazar_arr,$getBazar['p_w_amount']);
        }
    }
}
$person_wise_expense=array();
$total_expense=0;
for($a=0; $a<count($total_members); $a++){
    $total_expense+=round($total_mill_arr[$a] * $mill_rate, 1);
} 
$remaining_money=array();
for($a=0; $a<count($total_members); $a++){
    $result=round($person_wise_bazar_arr[$a]-($total_mill_arr[$a] * $mill_rate), 1);
    array_push($remaining_money, $result);
} 

for($index=0; $index<count($total_members); $index++){

    $to = $members_email[$index];
    $subject = $month.' Mill Record';
    $message = "Flat - 11 , Mill Record.
                \nName : ". $total_members[$index] ."\nYour Total Mill : ". $total_mill_arr[$index].
                "\nYour Bazar Amount : " . $person_wise_bazar_arr[$index].
                "\nFlat Total Mill : " . $total_mill .
                "\nMill Rate : ". $mill_rate.
                "\nRemaining : ". $remaining_money[$index] 
                ;
    $headers = 'From: mrmasud151821@gmail.com' . "\r\n" .
               'Reply-To: mrmasud151821@gmail.com' . "\r\n" .
               'X-Mailer: PHP/' . phpversion();

    if (mail($to, $subject, $message, $headers)) {
        echo '<h5><span class="badge bg-success">Email sent successfully to '. $total_members[$index] . '</span></h5><br>';
    } else {
        echo '<h5><span class="badge bg-danger">Email not sent successfully to '. $total_members[$index] . '</span></h5><br>';
    }
}

?>