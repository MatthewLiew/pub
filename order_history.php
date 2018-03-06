<?php define('RESTRICTED', true); ?>
<?php include 'header.php'; ?>
<?php
$status = 'pending';
if (isset($_GET['status'])) {
    $status = $_GET['status'];
}

$customer_id = $_SESSION['login_id'];
$result = get_user_by_id($customer_id);
?>
<link href="vendor/font-awesome-new/css/fontawesome-all.css" rel="stylesheet" type="text/css">
<link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

<link href="css/bootstrap-datetimepicker.css" rel="stylesheet">
<script src="js/bootstrap-datepicker.js"></script>

<link href="vendor/datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet">
<!-- <script src="vendor/datetimepicker/js/bootstrap-datetimepicker.min.js"></script> -->
<div class="content-wrapper">
    <div class="container-fluid">
        <?php
        if(isset($_POST['submit'])){
            //echo "                                                ";
            //echo $_POST['booking_date'] . " -  ";
            //echo "                                                ";
            //echo $_POST['booking_time'] . "-" . $_POST['order_id'] . "-" . $customer_id;
            
            $datetime = $_POST['booking_date'];
            $dt = strtotime($datetime);
            
            save_booking_time(date("Y-m-d", $dt), $_POST['booking_time'], $_POST['order_id'], $_POST['invoice_number'], $customer_id);
            //exit;
        }
        
        ?>
        <!-- 
        <input id="start_date" value="01 January, 2018" />
        <input id="end_date" value="31 December, 2018" />
        <input id="datetimepicker" value"">
        <a onclick="CheckToday()">CheckToday</a>-->

        <div class="row">
            <div class="col-12">
                <h1>Order History</h1>
                <h3><?php echo "Customer - " . $result['user_id'] . " - " . $result['user_name'] ?></h3>
            </div>
        </div>
        <ul class="nav nav-tabs" role="tablist" style="padding-left: 10px;">
            <li class="nav-item">
                <a class="nav-link <?php if ($status == 'pending') echo 'active'; ?>" href="order_history.php?status=pending">Pending</a>
            </li>
            <li class="nav-item">
                <a class="nav-link  <?php if ($status == 'completed') echo 'active'; ?>" href="order_history.php?status=completed">Completed</a>
            </li>
        </ul>
        <br/>
        <?php
        check_booking_expired();
        $order_list = get_order_list_by_customer($customer_id, $status);
        ?>
        <div class="card mb-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Order Number</th>
                                <th>Total Qty (m<sup>3</sup>) </th>
                                <th>Balance Qty (m<sup>3</sup>) </th>
                                <th>Date</th>
                                <th>Status</th>
                                <?php
                                if ($status == "pending")
                                    echo "<th>Booking</th>";
                                ?>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Order Number</th>                                    
                                <th>Total Qty (m<sup>3</sup>) </th>
                                <th>Balance Qty (m<sup>3</sup>) </th>
                                <th>Date</th>
                                <th>Status</th>
                                <?php
                                if ($status == "pending")
                                    echo "<th>Booking</th>";
                                ?>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php
                            foreach ($order_list as $row) {
                                echo "<tr>";
                                //echo "<td>" . $row['invoice_number'] . "</td>";
                                echo "<td>";
                                ?>
                                <a onclick="ShowOrderDetail('show', <?php echo $row['order_id']; ?>, <?php echo $row['invoice_number']; ?>);" style="cursor:pointer;"><?php echo $row['invoice_number']; ?></a>
                                <?php
                                echo "</td>";
                                //echo "<td>"  . $row['user_id'] . " - " . $row['user_name'] . "</td>";
                                echo "<td>" . $row['qty'] . "</td>";

                                $qty = $row['qty'] - get_water_pumped($row['order_id']);
                                echo "<td>" . $qty . "</td>";

                                $datetime = $row['complete_date'];
                                $dt = strtotime($datetime);
                                //echo "<td>" . date("Y-m-d", $dt) . "</td>";
                                //echo "<td>" . $row['status'] . "</td>";
                                if ($row['status'] == '1') {
                                    $datetime = $row['complete_date'];
                                    $dt = strtotime($datetime);
                                    echo "<td>" . date("d-m-Y", $dt) . "</td>";
                                    echo "<td>Completed</td>";
                                } else {
                                    $datetime = $row['create_date'];
                                    $dt = strtotime($datetime);
                                    echo "<td>" . date("d-m-Y", $dt) . "</td>";
                                    echo "<td>Pending</td>";

                                    echo "<td>";
                                    $booking_or_not = check_booking_or_not($row['order_id']);
                                        
                                    if($booking_or_not[0]==true){
                                        $booking_date = $booking_or_not[1];
                                        $booking_date = explode(" ", $booking_date);
                                        $dt = strtotime($booking_date[0]);
                                        echo date("d-m-Y", $dt);
                                        echo "<br/>";
                                        $booking_time = explode(":", $booking_date[1]);
                                        //echo $booking_time[0];
                                        if($booking_time[0]=="09"){
                                            echo "09:00 to 10:00 AM";
                                        }elseif($booking_time[0]=="10"){
                                            echo "10:00 to 11:00 AM";
                                        }elseif($booking_time[0]=="11"){
                                            echo "11:00 to 12:00 PM";
                                        }elseif($booking_time[0]=="12"){
                                            echo "12:00 to 01:00 PM";
                                        }elseif($booking_time[0]=="13"){
                                            echo "01:00 to 02:00 PM";
                                        }elseif($booking_time[0]=="14"){
                                            echo "02:00 to 03:00 PM";
                                        }elseif($booking_time[0]=="15"){
                                            echo "03:00 to 04:00 PM";
                                        }elseif($booking_time[0]=="16"){
                                            echo "04:00 to 05:00 PM";
                                        }elseif($booking_time[0]=="17"){
                                            echo "05:00 to 06:00 PM";
                                        }
                                        ?>
                                        <br/>
                                        <a style="cursor: pointer" onclick="doBooking('book', <?php echo $row['order_id']; ?>, <?php echo $row['invoice_number']; ?>);"><i class="fas fa-calendar-alt" style='font-size:30px; color:#367fa9; cursor: pointer' title='Change Booking Time'></i> Change Booking </a>
                                        <?php
                                        //echo $booking_or_not[1];
                                    }else{
                                        ?>
                                        <a style="cursor: pointer" onclick="doBooking('book', <?php echo $row['order_id']; ?>, <?php echo $row['invoice_number']; ?>);"><i class="fas fa-calendar-alt" style='font-size:30px; color:#367fa9; cursor: pointer' title='<?php echo $booking_or_not[0];  ?>'></i><?php echo $booking_or_not[1]; ?></a>
                                        <?php
                                        
                                        //echo $booking_or_not[1];
                                    }
                                    /*if($booking_or_not==0){
                                        echo "Book Now";
                                    }else{
                                        $dt = strtotime($booking_or_not);
                                        echo date("d-m-Y H:m:i", $dt);
                                    }*/
                                    
                                    
                                echo "</td>";
                            }
                            echo "</tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
        ?>
        
        <div class="modal clickable" id="DetailOrder" tabindex="-1" role="dialog" aria-labelledby="DetailOrder" aria-hidden="true">
            <div class="vertical-alignment-helper">
                <div class="modal-dialog  modal-lg vertical-align-center">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title"></h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal clickable" id="PreviewBooking" tabindex="-1" role="dialog" aria-labelledby="PreviewBooking" aria-hidden="true">
            <form action='order_history.php' method='post' id='OrderHistory'>
                <div class="vertical-alignment-helper">
                    <div class="modal-dialog  modal-lg vertical-align-center">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title"></h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>

                            <div class="modal-body">

                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" name="submit" class="btn btn-primary">Book</button>
                                <!-- <button type="submit" class="btn btn-primary" data-dismiss="modal" name="submit">Save</button>-->
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="modal clickable" id="SaveSuccessful" tabindex="-1" role="dialog" aria-labelledby="SaveSuccessful" aria-hidden="true">
            <div class="vertical-alignment-helper">
                <div class="modal-dialog  modal-lg vertical-align-center">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <!--
                        <div class="modal-header">
                                <h4 class="modal-title"></h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        -->
                        <div class="modal-body"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal clickable" id="PreviewDelete" tabindex="-1" role="dialog" aria-labelledby="PreviewDelete" aria-hidden="true">
            <form action='order_history.php' method='post' id='DeleteForm'>
                <div class="vertical-alignment-helper">
                    <div class="modal-dialog vertical-align-center">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Delete Order</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>                            

                            <div class="modal-body">

                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Delete</button>
                                <!-- <button type="submit" class="btn btn-primary" data-dismiss="modal" name="submit">Save</button>-->
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>


    </div>
</div>
<script type="text/javascript">
    function ShowOrderDetail(action, id, invoice_number) {
        //alert("action - " + action + " order id - " + id + " invo - " + invoice_number);
        if (action == 'show') {
            $('#DetailOrder .modal-body').empty();
            $('#DetailOrder .modal-title').text("Order Detail");
            //
            //$('#DetailOrder .modal-body').load('get_order_info.php?order_id=' + id + '&action=show', function () {
            $('#DetailOrder .modal-body').load('get_order_info.php?order_id=' + id + '&invoice_number='+ invoice_number +'&action=show', function () {
                //$('.selectpicker').selectpicker();
                $('#DetailOrder').modal({show: true});
            });
            //$('#PreviewOrder').modal({show:true});
        }
    }

    function doBooking(action, id, invoice_number) {
        if (action == 'book') {
            $('#PreviewBooking .modal-body').empty();
            $('#PreviewBooking .modal-title').text("Booking TimeSlot");
            $('#PreviewBooking .modal-body').load('get_booking_info.php?order_id=' + id + '&invoice_number=' + invoice_number + '&action=edit', function () {
                $('.selectpicker').selectpicker();
                $('#PreviewBooking').modal({show: true});
            });
            //$('#PreviewBooking').modal({show:true});
        }
    }
    /** Days to be disabled as an array */
    
    /*
    var disableddates = ["2-3-2018", "2-11-2018", "2-25-2018", "2-20-2018"];

    function DisableSpecificDates(date) {

        var m = date.getMonth();
        var d = date.getDate();
        var y = date.getFullYear();

        // First convert the date in to the mm-dd-yyyy format 
        // Take note that we will increment the month count by 1 
        var currentdate = (m + 1) + '-' + d + '-' + y;



        // We will now check if the date belongs to disableddates array 
        for (var i = 0; i < disableddates.length; i++) {

            // Now check if the current date is in disabled dates array. 
            if ($.inArray(currentdate, disableddates) != -1) {
                return [false];
            }
        }

        // In case the date is not present in disabled array, we will now check if it is a weekend. 
        // We will use the noWeekends function
        var weekenddate = $.datepicker.noWeekends(date);
        return weekenddate;
    }

    var gon = {};
    gon["holiday"] = "2018-01-01,2018-02-12,2018-02-16,2018-02-17,2018-03-30,2018-05-01,2018-05-29,2018-06-15,2018-08-09,2018-08-22,2018-11-06,2018-12-25".split(",");

    // 2 helper functions - moment.js is 35K minified so overkill in my opinion
    function pad(num) {
        return ("0" + num).slice(-2);
    }
    function formatDate(date) {
        var d = new Date(date), dArr = [d.getFullYear(), pad(d.getMonth() + 1), pad(d.getDate())];
        return dArr.join('-');
    }
    
    function CheckToday(){
        var d = new Date();
        var n = d.getDate();
        for (i = 0; i < 10; i++) {
            if(CheckOfforNot(d)==true){
                i = 10;
                var dd = d.getDate();
                var mm = d.getMonth()+1; //January is 0!
                var yyyy = d.getFullYear();

                if(dd<10) {
                    dd = '0'+dd
                }
                if(mm<10) {
                    mm = '0'+mm
                }
                today = dd + '-' + mm + '-' + yyyy;
                return today;
            }else{
                var next_date = d.setDate(d.getDate() + 1);
                CheckOfforNot(d);
            }
        }
    }
    
    function CheckOfforNot(d){
        if (d.getDay() == 6 || d.getDay() == 0 || gon.holiday.indexOf(formatDate(d)) != -1) {
            return false;
        }else{
            return true;
        }
    }
    /*
    function CheckNextDay(d){
    }
    function calculateDays(first, last) {
        var aDay = 24 * 60 * 60 * 1000,
                daysDiff = parseInt((last.getTime() - first.getTime()) / aDay, 10) + 1;

        if (daysDiff > 0) {
            for (var i = first.getTime(), lst = last.getTime(); i <= lst; i += aDay) {
                var d = new Date(i);
                if (d.getDay() == 6 || d.getDay() == 0 || gon.holiday.indexOf(formatDate(d)) != -1) {
                    daysDiff--;
                }
            }
        }
        return daysDiff;
    }
    */
   
    $(function () {
        //document.getElementById('datetimepicker').value = CheckToday();
        /*
        var days = calculateDays(new Date($('#start_date').val()),
                new Date($('#end_date').val()));
        if (days <= 0) {
            alert("Please enter an end date after the begin date");
        } else {
            alert(days + " working days found");
        }*/

        jQuery('#OrderHistory #booking_date').change(function (e) {
            var check = $("input[id='booking_date']").val();

            $.get("get_booking_info.php?booking_date=" + check, function (data) {
                console.log('Date chosen: ' + data);
                $('#booking_time').val(data);

                /*
                 $('#mySelect')
                 .append($("<option></option>")
                 .attr("value",key)
                 .text(value)); 
                 */
                /*
                 if(data=='1' || data==1){
                 //alert(data);
                 //user_name_error
                 $("#user_name_error").show();
                 $('#user_name_error').text("User Name - " + check + " Already Exist");
                 }else{
                 $("#user_name_error").hide();
                 }*/
            });

            //alert(check);
        });


        //var array = ["28-12"]
        var currDate = new Date();
        var datesEnabled = ['2018-1-1', '2018-2-16', '2018-2-17', '2018-3-30', '2018-5-1', '2018-5-29', '2018-6-15', '2018-8-9', '2018-8-22', '2018-11-6', '2018-12-25'];
        $("#datepicker").datepicker({
            beforeShowDay: function (date) {
                var allDates = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();
                if (datesEnabled.indexOf(allDates) == -1)
                    return true;
                else
                    return false;
            },
            //});
            daysOfWeekDisabled: [0, 6],
            autoclose: true,
            startDate: currDate,
            minDate: 0
        });

        $("#datetimepicker").datepicker({
            beforeShowDay: function (date) {
                var allDates = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();
                if (datesEnabled.indexOf(allDates) == -1)
                    return true;
                else
                    return false;
            },
            //});
            daysOfWeekDisabled: [0, 6],
            startDate: currDate,
            autoclose: true,
            minDate: 0
        });

        $('.day').datepicker().on('changeDate', function (ev) {
            alert("bbb");
        });
    });
    /*
     $(document).ready(function ($) {
     
     $('#datepicker').datepicker({
     beforeShowDay: $.datepicker.noWeekends
     });
     });*/
</script>
<style>
    td.day.disabled {
        opacity: 0.2;
    }
</style>
<?php include 'footer.php'; ?>     