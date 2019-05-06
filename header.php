<?php 
    // get url in php
    session_start();
    $url = $_SERVER[REQUEST_URI];

?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <script>
        function hideSignIn(obj){
            console.log($(obj).attr('aria-expanded'));
            if($(obj).attr('aria-expanded')==="true"){
                setTimeout(function(){
                    $('#AccountAction').removeClass('hide');
                },500);
            }else{
                $('#AccountAction').addClass('hide');
            }
        } 
        
    </script>
    <a class="navbar-brand mt-minus-2" href="./page-home.php" >
        <img src="./img/logo.png" width="80" height="37.92" class="d-inline-block align-top" alt="">
    </a>
    <button class="navbar-toggler mt-minus-2" type="button" data-toggle="collapse" 
    data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" 
    aria-label="Toggle navigation" onclick="hideSignIn(this)">
        <span class="navbar-toggler-icon"></span>
    </button>        
    <div class="collapse navbar-collapse bg-dark " id="navbarNav" style="margin-left:-1em;margin-right:-1em;">
        <ul class="navbar-nav mr-auto bg-dark ml-4 mr-4" >
            <li class="nav-item 
            <?php echo (strpos($url,"home"))?'active':''; ?>
            ">
                <a class="nav-link" href="./page-home.php">Home </a>
            </li>
            <li class="nav-item 
            <?php echo (strpos($url,"group"))?'active':''; ?>
            ">
                <a class="nav-link" href="./page-word-group.php">Learning Journey</a>
            </li>
            <li class="nav-item
            <?php echo (strpos($url,"review"))?'active':''; ?>
            ">
                <a class="nav-link" href="./page-word-review.php">Practice Journey</a>
            </li>
        </ul>
    </div>

    <?php if(!$_SESSION['user-id']) : ?>
        <!-- Sign In Module -->
        <div id="AccountAction" class="nav-item dropdown my-2 my-lg-0" >
            <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Sign In
            </a>
            <div class="dropdown-menu bg-dark" aria-labelledby="navbarDropdownMenuLink" style="width:21em;margin-left:-15em;">
                <div class="row mt-3"></div>
                <div class="row">
                    <div class="col-sm-4 mt-1 ml-4 mr-1 text-light">
                        <span>Username :</span>
                    </div>
                    <div class="col-sm-6">
                        <input name="username_sign_in" class="form-control" type="input" />
                    </div>
                </div>
                <div class="row mt-3"></div>
                <div class="row">
                    <div class="col-sm-4 mt-1 ml-4 mr-1 text-light">
                        <span>Password :</span>
                    </div>
                    <div class="col-sm-6">
                        <input name="password_sign_in" class="form-control" type="password" />
                    </div>
                </div>
                <div class="row mt-3" style="margin-left:10em;">
                    <a href="./page-member-signup.php"><button class="btn btn-outline-light" type="button" >Sign Up</button></a>
                    <button class="btn btn-outline-light ml-3" type="button" onclick="signin()">Sign In</button>
                </div>
            </div>
        </div>
    <?php else: ?>
    <div id="AccountAction" class="btn-group " >
        <button type="button" class="btn btn-secondary bg-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?php echo $_SESSION['username']; ?>
        </button>
        <div class="dropdown-menu dropdown-menu-right">
            <a href="./page-member-signout.php"><button class="dropdown-item" type="button">Sign Out</button></a>
        </div>
    </div>
    <?php endif ?>
</nav>