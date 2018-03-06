<?php
include 'config/function.php';
date_default_timezone_set('asia/singapore');
session_start();
$order_id = $_GET['order_id'];
$yesterday = date('Y-m-d', strtotime("-1 days"));
$today = date('d-m-Y');
$current_hour = date('H');
if($current_hour>=17){
    $current_hour = 8;
    $today = date('d-m-Y', strtotime("+1 days"));
}

if(isset($_GET['booking_date'])){
    $booking_date = $_GET['booking_date'];
    $time_array = array(
        9 => "09:00 to 10:00 AM",
        10 => "10:00 to 11:00 AM",
        11 => "11:00 to 12:00 PM",
        12 => "12:00 to 01:00 PM",
        13 => "01:00 to 02:00 PM",
        14 => "02:00 to 03:00 PM",
        15 => "03:00 to 04:00 PM",
        16 => "04:00 to 05:00 PM",
        17 => "05:00 to 06:00 PM"
    );
    
    for($i=9; $i<=17; $i++){
        if($current_hour<$i){
            echo '<option value="'. $i .'"> ' . $time_array[$i] . '</option>';
        }
    }	
}
else if(isset($_GET['order_id'])) {
    ?>

    <div class="form-group">
        <div class="form-row">
            <div class="col-md-4"> 
                <label for="UserIDLabel">Choose Date</label>
            </div>
            <div class="col-md-8">
                <div class='input-group date' id='datetimepicker'>
                    <input type="hidden" id="order_id" name="order_id" value="<?php echo $_GET['order_id']; ?>">
                    <input type="hidden" id="invoice_number" name="invoice_number" value="<?php echo $_GET['invoice_number']; ?>">
                    <input type='text' class="form-control" name="booking_date" id="booking_date" value="<?php echo $today; ?>" class="readonly" required />
                    <span class="input-group-addon">
                      <!-- <span class="glyphicon glyphicon-calendar"></span> -->
                        <i class="fas fa-calendar-alt" style='font-size:20px; color:#367fa9'></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="form-row">
            <div class="col-md-4">
                <label for="UserIDLabel">Choose Time</label>
            </div>
            <div class="col-md-8">               
                <?php
                //echo date("H:m i");
                ?>
                <select class="form-control" data-size="5" id="booking_time" name="booking_time" required>
                    <!-- <option>Select Time</option>-->
                    <?php
                    /*
                    $time_array = array(
                        9 => "09 AM",
                        array(9, "09 AM"),
                        array(10, "10 AM"),
                        array(11, "11 AM"),
                        array(12, "12 PM"),
                        array(13, "01 PM"),
                        array(14, "02 PM"),
                        array(15, "03 PM"),
                        array(16, "04 PM"),
                        array(17, "05 PM"),
                    );
                    $time_array = array(
                        9 => "09 AM",
                        10 => "10 AM",
                        11 => "11 AM",
                        12 => "12 PM",
                        13 => "01 PM",
                        14 => "02 PM",
                        15 => "03 PM",
                        16 => "04 PM",
                        17 => "05 PM"
                    );*/
                    
                    $time_array = array(
                        9 => "09:00 to 10:00 AM",
                        10 => "10:00 to 11:00 AM",
                        11 => "11:00 to 12:00 PM",
                        12 => "12:00 to 01:00 PM",
                        13 => "01:00 to 02:00 PM",
                        14 => "02:00 to 03:00 PM",
                        15 => "03:00 to 04:00 PM",
                        16 => "04:00 to 05:00 PM",
                        17 => "05:00 to 06:00 PM"
                    );
                    
                    print_r($time_array);
                    
                    for($i=9; $i<=17; $i++){
                        if($current_hour<$i){
                            echo '<option value="'. $i .'"> ' . $time_array[$i] . '</option>';
                        }
                    }					
                    ?>
                    
                    <!--
                    <option value="9">9 AM</option>
                    <option value="10">10 AM</option>
                    <option value="11">11 AM</option>
                    <option value="12">12 PM</option>
                    <option value="13">1 PM</option>
                    <option value="14">2 PM</option>
                    <option value="15">3 PM</option>
                    <option value="16">4 PM</option>
                    <option value="17">5 PM</option>
                    -->
                    
                </select>
                <!--- <input class="form-control" id="order_date" name="order_date" data-error="this.setCustomValidity('Please Enter Valid Qty')" type="time" aria-describedby="Water Qty" placeholder="Water Qty" value="<?php echo date("H:m i"); ?>" required> -->
                <!--
                <input class="form-control" id="order_number" name="order_number" type="hidden" value="<?php echo $result['invoice_number']; ?>">
                <input class="form-control" value="<?php echo $result['invoice_number']; ?>" id="order_number" name="order_number" type="text" data-error="Ener Invoice Number" aria-describedby="Invoice Number" placeholder="Ener Invoice Number" value="" required>-->
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(".readonly").keydown(function(e){
            e.preventDefault();
        });

        var gon = {};
        gon["holiday"] = "2018-01-01,2018-02-16,2018-02-17,2018-03-30,2018-05-01,2018-05-29,2018-06-15,2018-08-09,2018-08-22,2018-11-06,2018-12-25".split(",");

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
            //alert(d.getHours());
            for (i = 0; i < 10; i++) {
                if(CheckOfforNot(d)==true){
                    i = 10;
                    if(d.getHours() >= 17){
                        d.setDate(d.getDate() + 1);
                    }
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
    
        $(function () {
            document.getElementById('booking_date').value = CheckToday();
            //var array = ["28-12"]
            var d = new Date();
            var currDate = new Date();
            var datesEnabled = ['2018-1-1', '2018-2-16', '2018-2-17', '2018-3-30', '2018-5-1', '2018-5-29', '2018-6-15', '2018-8-9', '2018-8-22', '2018-11-6', '2018-12-25'];

            $("#datetimepicker").datepicker({
                beforeShowDay: function (date) {
                    var allDates = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();
                    if (datesEnabled.indexOf(allDates) == -1)
                        return true;
                    else
                        return false;
                },
                //});
                format: 'dd-mm-yyyy',
                daysOfWeekDisabled: [0, 6],
                //startDate: currDate,
                startDate: CheckToday(),
                ignoreReadonly: true,
                minDate: 0,
                autoclose: true,
                onSelect: function(dateText) {
                    //alert("hhhh");
                    display("Selected date: " + dateText + "; input's current value: " + this.value);
                }
                /*
                 * changeDate: function(date){
                    alert("here");
                }
               
                onSelect: function(dateText, inst) {
                    //var date = $(this).val();
                    //var time = $('#time').val();
                    alert('on select triggered');
                    //$("#start").val(date + time.toString(' HH:mm').toString());
                    //console.log(date + time.toString(' HH:mm').toString());
                }*/
            });
            
           /*
            $('.table-condensed').datepicker().on('changeDate', function (ev) {
                alert("bbb");
            });
            */
            
            /*
           jQuery('.table-condensed').on('click', function (e) {
			alert("here");
		});
                
                $('.day').click(function(event){
    alert("here");
});
            
            e = {
                date, //date the picker changed to. Type: moment object (clone)
                oldDate //previous date. Type: moment object (clone) or false in the event of a null
              }
            $('#datetimepicker').datepicker()
            .on('dp.change', function(e){
              if(e.date){
                  alert("hhh");
                console.log('Date chosen: ' + e.date.format() );
              }
            });
            */
            
            
        });
    </script>
    <style>
        .dropdown-menu{
            width: 33%;
        }
        .table-condensed{
            width: 100%;
            text-align: center;
            cursor: pointer;
        }
        .table-condensed>tbody>tr>td, .table-condensed>tbody>tr>th, .table-condensed>tfoot>tr>td, .table-condensed>tfoot>tr>th, .table-condensed>thead>tr>td, .table-condensed>thead>tr>th{
            text-align: center;
        }
        td.active.day{
            background-color: #367fa9;
            background-color: #367ea9;
            background-image: -moz-linear-gradient(to bottom,#08c,#04c);
            background-image: -ms-linear-gradient(to bottom,#08c,#04c);
            background-image: -webkit-gradient(linear,0 0,0 100%,from(#08c),to(#04c));
            background-image: -webkit-linear-gradient(to bottom,#08c,#04c);
            background-image: -o-linear-gradient(to bottom,#08c,#04c);
            background-image: linear-gradient(to bottom,#08c,#04c);
            background-repeat: repeat-x;
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#08c', endColorstr='#49a7df', GradientType=0);
            border-color: #04c #04c #367ea9;
            border-color: rgba(0,0,0,.1) rgba(0,0,0,.1) rgba(0,0,0,.25);
            filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
            color: #fff;
            text-shadow: 0 -1px 0 rgba(0,0,0,.25);
        }
    </style>
    <?php
}
?>