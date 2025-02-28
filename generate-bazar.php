<?php 



include_once 'Database.php';
$month = $_GET['month'];
$DB = new Database();
date_default_timezone_set('Asia/Dhaka');
require_once 'vendor/autoload.php';

$total_members = [];
$total_mill_arr=[];

$html = ""; // Initialize HTML content
$DB->select("mill_tbl", "*", null, "MONTHNAME(mill_date) = '$month'", "mill_date ASC");

// Building the HTML table
$html .= '<h3>'.$month.' Bazar Record</h3><table border="1" style="border-collapse:collapse" cellpadding=5>
<thead>
    <tr>
        <th>Date</th>';

$DB->select("mill_tbl", "SUM(mill_count) as mill", null, "MONTHNAME(mill_date) = '$month'");  
$total_mill_count = $DB->getResult();

$total_mill = !empty($total_mill_count[0]['mill']) ? $total_mill_count[0]['mill'] : 0;

$DB->select("bazar_tbl", "SUM(amount) as total_bazar_amount", null, "MONTHNAME(amt_paid_date) = '$month'");
$bazar_amount = $DB->getResult();
$mill_rate = $total_mill > 0 ? round($bazar_amount[0]['total_bazar_amount'] / $total_mill, 1) : 0;

$specific_bazar_members = [];
$total_members = [];
$specific_bazar_members_amount = [];
$b = 0;

$DB->select("members", "*", null, null, "member_name ASC");
$all_members = $DB->getResult();

foreach ($all_members as $member) {
    array_push($total_members, $member['member_name']);
    $html .= "<th class='text-capitalize'>" . $member['member_name'] . "</th>";
}
$html .= "<th>Total</th></tr></thead><tbody>";

$DB->select("bazar_tbl", "DISTINCT(amt_paid_date)", null, "MONTHNAME(amt_paid_date) = '$month'", "amt_paid_date ASC");
$bazar_dates = $DB->getResult();

foreach ($bazar_dates as $bdate) {
    $html .= "<tr class='text-center'>
        <td>" . $bdate['amt_paid_date'] . "</td>";

    $unique_bazar_date = $bdate['amt_paid_date'];
    $DB->sql("SELECT * from bazar_tbl where amt_paid_date='$unique_bazar_date' AND MONTHNAME(amt_paid_date)='$month' ORDER BY member_name ASC");
    $date_wise_bazar_record = $DB->getResult();

    foreach ($date_wise_bazar_record as $bazar) {
        foreach ($bazar as $baz) {
            array_push($specific_bazar_members, $baz['member_name']);    
            array_push($specific_bazar_members_amount, $baz['amount']);    
        }
    }

    for ($a = 0; $a < count($total_members); $a++) {
        if ($b < count($specific_bazar_members)) {
            if ($total_members[$a] == $specific_bazar_members[$b]) {
                $html .= "<td>" . $specific_bazar_members_amount[$b] . "</td>";
                $b++;
            } else {
                $html .= "<td>0</td>";
            }
        } else {
            $html .= "<td>0</td>";
        }
    }

    $html .= "<td></td>";
    $specific_bazar_members = [];
    $specific_bazar_members_amount = [];
    $a = 0;
    $b = 0;
}
$grandTotalMill=0;
$html.="<tr style='background-color: green'>
            <td style='color:white'><strong>Mill</strong></td>";
            foreach ($total_members as $tm) {
                $DB->select("mill_tbl", "SUM(mill_count) as p_w_mill, member_name", null, "member_name='$tm' AND MONTHNAME(mill_date) = '$month'");
                foreach ($DB->getResult() as $mill) {
                    $html.="<td style='color:white'> <strong>". $mill['p_w_mill'] ."</strong> </td>";
                    $grandTotalMill += $mill['p_w_mill']; 
                }
            }

            $html.="<td style='color:white'><strong>". $grandTotalMill ."</strong></td>";

$html.="</tr>";


$html .= "<tr rowspan='2'>
    <td><strong>Total</strong></td>";
    
$person_wise_bazar_arr = [];
foreach ($total_members as $tm) {
    $DB->select("bazar_tbl", "SUM(amount) as p_w_amount, member_name", null, "member_name='$tm' AND MONTHNAME(amt_paid_date) = '$month'");
    foreach ($DB->getResult() as $getBazar) {
        array_push($person_wise_bazar_arr, empty($getBazar['p_w_amount']) ? "0" : $getBazar['p_w_amount']);
    }
}
foreach ($person_wise_bazar_arr as $single_person_bazar) {
    $html .= '<td><strong><span>' . $single_person_bazar . '</span></strong></td>';
}

$html .= "<td >" . $bazar_amount[0]['total_bazar_amount'] . "</td></tr>
<tr>
    <td><strong>Expense</strong></td>";

$total_mill_arr = [];
foreach ($total_members as $tm) {
    $DB->select("mill_tbl", "SUM(mill_count) as p_w_mill, member_name", null, "member_name='$tm' AND MONTHNAME(mill_date) = '$month'");
    foreach ($DB->getResult() as $getMill) {
        array_push($total_mill_arr, empty($getMill['p_w_mill']) ? "0" : $getMill['p_w_mill']);
    }
}

$total_expense = 0;
for ($a = 0; $a < count($total_members); $a++) {
    $expense_value = round($total_mill_arr[$a] * $mill_rate, 1);
    $total_expense += $expense_value;
    $html .= "<td><strong><span>" . $expense_value . "</span></strong></td>";
} 

$html .= "<td>" . $total_expense . "</td></tr> <tr><td><strong>Mill Rate</strong></td> <td colspan='8' align='center'><strong>". $mill_rate ."</strong></td> </tr>
<tr >
    <td><strong>Due/Pay</strong></td>";

$give = 0;
$take = 0;
for ($a = 0; $a < count($total_members); $a++) {
    $result = round($person_wise_bazar_arr[$a] - ($total_mill_arr[$a] * $mill_rate), 1);
    if ($result >= 0) {
        $take += $result;
        $html .= "<td style='text-align:center'><strong style='color:green'>" . $result . "</strong></td>";
    } else {
        $give += $result;
        $html .= "<td style='text-align:center'><strong style='color:red'>" . $result . "</strong></td>";
    }
} 

$html .= "<td style='text-align:center'>
    <div style='background-color:red;color:white;'>
        <span>Give</span><br>
        <span >" . $give . "</span>
    </div>
    
    <div style='background-color:green;color:white;'>
        <span>Take</span><br>
        <span>" . $take . "</span>
    </div>
</td>
</tr>
</tbody>
</table><br/><code style='font-size:10px'><strong>Created at :</strong> " . date('H:i:s, l d F, Y', $timestamp) . "</code>";

// Create the PDF
$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A6']); 
$mpdf->WriteHTML($html);
$fileName =  $month.'-bazar.pdf';
$mpdf->Output($fileName, 'I');
?>