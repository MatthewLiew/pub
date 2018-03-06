            <!-- /.container-fluid-->
            <!-- /.content-wrapper-->
            <?php 
                if(basename($_SERVER['PHP_SELF'])!='login.php'){
            ?>
            <footer class="sticky-footer">
                <div class="container">
                    <div class="text-center">
                        <small><a onclick="">Copyright © Vogomo 2018</a></small>
                    </div>
                </div>
            </footer>
            <!-- Scroll to Top Button-->
            <a class="scroll-to-top rounded" href="#page-top">
                <i class="fa fa-angle-up"></i>
            </a>
            <!-- Logout Modal-->
            <div class="modal fade" id="LogoutModal" tabindex="-1" role="dialog" aria-labelledby="LogoutModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="LogoutModalLabel">Ready to Leave?</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">Select "Logout" below to end your current session.</div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                            <a class="btn btn-primary" href="logout.php">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                }
            ?>
            <script type="text/javascript">
                $(document).ready(function() {	
                    $('li.active').removeClass('active');
                    $('a[href="' + location.pathname + '"]').closest('li').addClass('active'); 
                    
                    /*
                    $.each($('#navbar').find('li'), function() {
                        $(this).toggleClass('active', 
                            window.location.pathname.indexOf($(this).find('a').attr('href')) > -1);
                    }); 
                    $(".nav-link a").on("click", function(){
                        alert("here");
                        $(".nav-item").find(".active").removeClass("active");
                        $(this).parent().addClass("active");
                    });
                    */
                   //nav-item
                    $('a').click(function(){ 
                        $('li a').removeClass("active"); 
                        $(this).addClass("active"); 
                    });
                }); 
            </script>
        </div>
    </body>
</html>
