<?php define('RESTRICTED', true); ?>
<?php include 'header.php'; ?>
<?php
if ($_SESSION['login_user_role'] == '2') {
    ?>
    <div class="content-wrapper">
        <div class="container-fluid">
            Sorry, you are not allowed to access this page. Please browse Correct Page ! 
        </div>
    </div>
    <?php
    include 'footer.php';
} else {
    $status = 'pending';
    if (isset($_GET['status'])) {
        $status = $_GET['status'];
    }
    ?>
    <style>
        .alert{
            z-index: 10 !important;
            position: absolute !important;
            //margin-left: -10px;
            width: 82% !important;
            opacity: 0.9 !important;
            margin-top: 200px !important;
            padding: 39px !important;
            margin-bottom: 29px !important;
            border: 1px solid transparent !important;
            border-radius: 0px !important;
            text-align: center;
            font-size: large;
        }

    </style>


    <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-8 col-md-4 .col-xl-4">
                    <h1>Order Management</h1>
                </div>
                <div class="col-sm-4 col-md-6 .col-xl-8" style="vertical-align: center; padding-top:25px">
                    <a onclick="doOrder('new', 0)"><button class="btn btn-primary">Create Order	</button></a>
                </div>
            </div>

            <!-- <div class="alert alert-success"  id="show_error" role="alert" style="display:none;"></div> -->
            <div class="alert alert-success"  id="show_error" role="alert" style="display:none;"></div>

            <ul class="nav nav-tabs" role="tablist" style="padding-left: 10px;">
                <li class="nav-item">
                    <a class="nav-link <?php if ($status == 'pending') echo 'active'; ?>" href="order_manage.php?status=pending">Pending</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  <?php if ($status == 'completed') echo 'active'; ?>" href="order_manage.php?status=completed">Completed</a>
                </li>
            </ul>
            <br/>
            <?php
            $order_list = get_order_list($status);
            //if ($status == 'pending') {
            ?>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="showOrder" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="display:none;">Order Number</th>
                                    <th>Order Number</th>
                                    <th>Contact Person Name</th>
                                    <th>Total Qty (m<sup>3</sup>) </th>
                                    <th>Balance Qty (m<sup>3</sup>) </th>
                                    <?php
                                    if ($status == 'pending')
                                        echo "<th>Create Date</th>";
                                    else
                                        echo "<th>Completed Date</th>";
                                    ?>
                                    <th>Status</th>
                                    <th>Remark</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th style="display:none;">Order Number</th>
                                    <th>Order Number</th>
                                    <th>Contact Person Name</th>
                                    <th>Total Qty (m<sup>3</sup>) </th>
                                    <th>Balance Qty (m<sup>3</sup>) </th>
                                    <?php
                                    if ($status == 'pending')
                                        echo "<th>Create Date</th>";
                                    else
                                        echo "<th>Completed Date</th>";
                                    ?>
                                    <th>Status</th>
                                    <th>Remark</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php
                                foreach ($order_list as $row) {
                                    echo "<tr>";
                                    echo "<td style='display:none;'>" . $row['order_id'] . "</td>";
                                    //echo "<td>" . $row['invoice_number'] . "</td>";
                                    echo "<td>";
                                    ?>
                                <a onclick="ShowOrderDetail('show', <?php echo $row['order_id']; ?>, <?php echo $row['invoice_number']; ?>);" style="cursor:pointer;"><?php echo $row['invoice_number']; ?></a>
                                <?php
                                echo "</td>";

                                echo "<td>" . $row['user_id'] . " - " . $row['user_name'] . "</td>";
                                echo "<td>" . $row['qty'] . "</td>";
                                /*                                 * * GET water_pumped ****** */
                                $qty = $row['qty'] - get_water_pumped($row['order_id']);
                                echo "<td>" . $qty . "</td>";
                                /*                                 * * GET water_pumped ****** */
                                if ($status == 'pending') {
                                    $datetime = $row['create_date'];
                                    $dt = strtotime($datetime);
                                } else {
                                    $datetime = $row['complete_date'];
                                    $dt = strtotime($datetime);
                                }
                                echo "<td>" . date("d-m-Y", $dt) . "</td>";
                                //echo "<td>" . $row['status'] . "</td>";
                                if ($row['status'] == '1')
                                    echo "<td>Completed</td>";
                                else
                                    echo "<td>Pending</td>";
                                echo "<td>" . $row['remark'] . "</td>";
                                echo "<td>";
                                ?>
                                <a onclick="doOrder('edit', <?php echo $row['order_id']; ?>);"><i class='fa fa-pencil-square fa-5' style='font-size:30px; color:#367fa9' title='Edit Order'></i></a>
                                &nbsp;&nbsp;
                                <a onclick="doOrder('delete', <?php echo $row['order_id']; ?>);"><i class='fa fa-trash-o fa-5' style='font-size:30px; color:#367fa9' title='Delete Order'></i></a>
                                <?php
                                echo "</td>";
                                echo "</tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php
            //}else {
            //}
            ?>

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

            <div class="modal clickable" id="PreviewOrder" tabindex="-1" role="dialog" aria-labelledby="PreviewOrder" aria-hidden="true">
                <form action='order_manage.php' method='post' id='OrderForm'>
                    <div class="vertical-alignment-helper">
                        <div class="modal-dialog  modal-lg vertical-align-center">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title"></h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <div class="alert alert-danger" role="alert" id="modal_error" style="display:none;"></div>

                                <div class="modal-body">

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal clickable" id="PreviewDelete" tabindex="-1" role="dialog" aria-labelledby="PreviewDelete" aria-hidden="true">
                <form action='order_manage.php' method='post' id='DeleteForm'>
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
        function doOrder(action, id) {
            if (action == 'edit') {
                $('#PreviewOrder .modal-body').empty();
                $('#PreviewOrder .modal-title').text("Edit Order");
                $('#PreviewOrder .modal-body').load('get_order_info.php?order_id=' + id + '&action=edit', function () {
                    $('.selectpicker').selectpicker();
                    $('#PreviewOrder').modal({show: true});
                });
                //$('#PreviewOrder').modal({show:true});
            } else if (action == 'new') {
                $('#PreviewOrder .modal-body').empty();
                $('#PreviewOrder .modal-title').text("Create Order");
                $('#PreviewOrder .modal-body').load('get_order_info.php?order_id=0&action=new', function () {
                    $('.selectpicker').selectpicker();
                    $('#PreviewOrder').modal({show: true});
                });
            } else if (action == 'delete') {
                $('#PreviewDelete .modal-body').empty();
                //$('#PreviewDelete .modal-title').text("Delete User Confirm");
                //$('#PreviewOrder .modal-body').text("Are you sure to Delete? Click 'Delete' To Confrim");
                $('#PreviewDelete .modal-body').load('get_order_info.php?order_id=' + id + '&action=delete', function () {
                    $('.selectpicker').selectpicker();
                    $('#PreviewDelete').modal({show: true});
                });
            }
        }

        function create_user_show() {
            var isVisible = $("#collapseCreateCustomer").is(":visible");
            //alert(isVisible);
            if (isVisible === false) {
                $('select[name=customer_id]').val("");
                $('.selectpicker').selectpicker('refresh');
                document.getElementById("customer_id").removeAttribute("required");

                document.getElementById("user_id").setAttribute("required", "");
                document.getElementById("new_user_pass").setAttribute("required", "");
                document.getElementById("user_name").setAttribute("required", "");
                document.getElementById("user_phone").setAttribute("required", "");

            } else {
                document.getElementById("customer_id").setAttribute("required", "");

                document.getElementById("user_id").removeAttribute("required");
                document.getElementById("new_user_pass").removeAttribute("required");
                document.getElementById("user_name").removeAttribute("required");
                document.getElementById("user_phone").removeAttribute("required");
            }

        }
        function create_user_verify() {
            $('select[name=customer_id]').val("");
            $('.selectpicker').selectpicker('refresh');
            document.getElementById("customer_id").removeAttribute("required");
        }

        $(document).ready(function ($) {
            $('#showOrder').dataTable({
                aaSorting: [[5, 'desc']]
                        // "columnDefs": [
                        // { "orderable": false, "targets": 0 }
                        // ]
            });
            //$('#dataTable').DataTable( {
            //"ordering": false,
            // "paging": false,
            // "searching": false,
            //"scrollX": true
            //"order": [[ 5, "desc" ]]
            //});

            // $( '#dataTable' ).dataTable( {
            // "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            // // Bold the grade for all 'A' grade browsers
            // if ( aData[4] == "A" )
            // {
            // $('td:eq(4)', nRow).html( '<b>A</b>' );
            // }
            // }
            // } );
            //ChangePasswordUser
            $(".modal").on("hidden.bs.modal", function () {
                $('#PreviewOrder .modal-body').empty();
                //location.reload();
            });

            $("input").keydown(function () {
                $("input").css("background-color", "yellow");
            });

            $("input").keyup(function () {
                $("input").css("background-color", "pink");
            });


            //$("#user_name").keyup(function(){
            jQuery('#OrderForm').keyup(function (e) {
                var check = $("input[id='user_id']").val();
                $.get("get_order_info.php?check=" + check, function (data) {
                    if (data == '1' || data == 1) {
                        //alert(data);
                        //user_name_error
                        $("#user_name_error").show();
                        $('#user_name_error').text("User Name - " + check + " Already Exist");
                    } else {
                        $("#user_name_error").hide();
                    }
                });
            });

            jQuery('#OrderForm').on('submit', function (e) {
                e.preventDefault();
                jQuery.post('get_order_info.php',
                        $('#OrderForm').serialize(),
                        function (data, status, xhr) {
                            $("#modal_error").show();
                            $('#modal_error').text(data.error);

                            if (data.condition == true) {
                                $('#PreviewOrder .modal-body').empty();
                                $('#PreviewOrder').modal('toggle');

                                //$("#show_error").show();
                                //$('#show_error').text(data.error);

                                //$('#SaveSuccessful .modal-title').text(data.error);
                                $('#SaveSuccessful .modal-body').text(data.error);
                                $('#SaveSuccessful').modal({show: true});

                                setTimeout(function () {
                                    location.reload();
                                }, 1000);
                            }
                        }, "json");
            });

            jQuery('#DeleteForm').on('submit', function (e) {
                e.preventDefault();
                jQuery.post('get_order_info.php',
                        $('#DeleteForm').serialize(),
                        function (data, status, xhr) {
                            $('.modal-error').text(data.error);
                            if (data.condition == true) {
                                $('#PreviewDelete .modal-body').empty();
                                location.reload();
                            }
                            //location.reload();
                            // do something here with response;
                        }, "json");
            });


            jQuery('#Create_Customer').on('submit', function (e) {
                e.preventDefault();
                jQuery.post('get_user_info.php',
                        $('#Create_Customer').serialize(),
                        function (data, status, xhr) {
                            $('.modal-error').text(data.error);
                            if (data.condition == true) {
                                //$('#PreviewOrder .modal-body').empty();
                                //location.reload();
                            }
                            //location.reload();
                            // do something here with response;
                        }, "json");
            });

        });
    </script>
<?php } ?>

<?php include 'footer.php'; ?>