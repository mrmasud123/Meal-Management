<?php
date_default_timezone_set('Asia/Dhaka');
require_once 'vendor/autoload.php';
require_once 'Database.php';

$DB = new Database();
$DB->select('monthly_expenses', "*");
$data = $DB->getResult();
$DB->select('credential',"*");
$credential=$DB->getResult();
echo "";
$timestamp = time();
$total=0;
$html = "<style>td{text-align:center;}</style>
<h3 style='width:100%;text-align:center'>Flat-11 Monthly Expenses</h3>
<b>Flat Rent</b> : <span>".$credential[0]['flat_rent']."</span><br>
<b>Service Charge</b> : <span>".$credential[0]['service_charge']."</span><br>
<b>Electricity Bill</b> : <span>".$credential[0]['electricity_bill']."</span><br>
<b>Garbage Charge</b> : <span>".$credential[0]['garbage_charge']."</span><br>
<b>Gas Bill</b> : <span>".$credential[0]['gas_bill']."</span><br>
<b>Khala Salary</b> : <span>".$credential[0]['khala_salary']."</span><br>

<table border='1' style='border-collapse:collapse' cellpadding=5 style='margin-top:30px'>
    <thead>
        <tr>
            <th>Member</th>
            <th>Seat Rent</th>
            <th>Service Charge</th>
            <th>Garbage Charge</th>
            <th>Electricity Bill</th>
            <th>Gas Bill</th>
            <th>Khala Salary</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>";

foreach ($data as $expense) {
    $total+=$expense['flat_rent']+$expense['service_charge']+$expense['garbage_charge'] +$expense['electricity_bill']+$expense['gas_bill']+$expense['khala_salary'];
    $html .= "<tr>
                <td>" . $expense['member'] . "</td>
                <td>" . $expense['flat_rent'] . "</td>
                <td>" . $expense['service_charge'] . "</td>
                <td>" . $expense['garbage_charge'] . "</td>
                <td>" . $expense['electricity_bill'] . "</td>
                <td>" . $expense['gas_bill'] . "</td>
                <td>" . $expense['khala_salary'] . "</td>
                <td>" . $expense['total_amt'] . "</td>
            </tr>";
}


$html .= "<tr><td colspan='8' align='right'><span style='font-size:19px;background-color:green;color:white'>Receive Amount : ". $total ."</span></td></tr></tbody></table><br/><br/><code style='font-size:10px'><strong>Created at :</strong> " . date('H:i:s, l d F, Y', $timestamp) . "</code>";

$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A6']); 
$mpdf->WriteHTML($html);
$fileName = date('F') . '-expenses.pdf';
$mpdf->Output($fileName, 'I');
exit;
?>
