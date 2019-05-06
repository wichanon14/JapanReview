<?php 
    session_start();

    /*if(!$_SESSION['user-id']){
        header('Location: ./page-home.php');
    }*/
?>
<!DOCTYPE html>
<html>
    <head>
        <?php 
            require_once('header-resource.php');
        ?>
    </head>
    <body>
        <style>
            .font-size-0-7{
                font-size:0.7em;
            }
        </style>
        <script>

            var check_email_exist = false;
            var check_username_exist = false;

            function showCaption(obj){
                $(obj).parent().parent().children('span').removeClass('hide');
            }
            
            function hideCaption(obj){
                if( !$(obj).parent().parent().children('span').hasClass('text-danger') ){
                    $(obj).parent().parent().children('span').addClass('hide');
                }
                
            }

            function checkMatch(obj){
                if($('input[name=password]').val()&&$('input[name=confirm_password]').val()&&$('input[name=password]').val()===$('input[name=confirm_password]').val()){
                    $(obj).parent().parent().children('span').addClass('hide');
                }else{
                    $(obj).parent().parent().children('span').removeClass('hide');
                }
            }

            function signupOnPage(){
                if(check_username_exist){
                    alert('Your username had already exist');
                }else if($('input[name=password]').val()&&$('input[name=confirm_password]').val()&&$('input[name=password]').val()!==$('input[name=confirm_password]').val()){
                    alert('Your password mismatch!');
                }else if(check_email_exist){
                    alert('Your email already exist!');
                }else if(!validateEmail($('input[name=email]').val())){
                    alert('Your email is not formatted correctly.');
                }else if($('input[name=password]').val()===$('input[name=confirm_password]').val()&&!check_email_exist){
                    signup();
                }
            }

            function usernameLabel(obj){
                check_username_exist_func(obj);    
            }

        </script>
        <?php 
            require_once('header.php');
        ?>
        <div class="container" style="margin-top:10em;">
            <div class="row">
                <div class="col-sm-4"></div>
                <div class="text-center col-sm-4">
                    <h1>REGISTRATION</h1>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-sm-4"></div>
                <div class="text-center col-sm-4 mb-3">
                    <input name="username" class="form-control text-center" type="text" maxlength="12" placeholder="Username" 
                    oninput="usernameLabel(this)" onfocusout="hideCaption(this)" />
                </div>
                <span class="mt-2 font-size-0-7 font-weight-bold hide">
                     Username length mustn't over 12 character
                </span>
                
            </div>
            <div class="row">
                <div class="col-sm-4"></div>
                <div class="text-center col-sm-4 mb-3">
                    <input name="password" class="form-control text-center" type="password" maxlength="16" placeholder="Password" 
                    autocomplete="new-password" onfocusin="showCaption(this)" onfocusout="hideCaption(this)" />
                </div>
                <span class="mt-2 font-size-0-7 font-weight-bold hide">
                    Your Password should be contain with (A-Z a-z 0-9)
                </span>
            </div>
            <div class="row">
                <div class="col-sm-4"></div>
                <div class="text-center col-sm-4 mb-3">
                    <input name="confirm_password" class="form-control text-center" type="password" 
                    maxlength="16" placeholder="Confirm Password" oninput="checkMatch(this)"/>
                </div>
                <span class="text-danger mt-2 font-size-0-7 hide font-weight-bold">Password mismatch!</span>
            </div>
            <div class="row">
                <div class="col-sm-4"></div>
                <div class="text-center col-sm-4 mb-3">
                    <input name="email" class="form-control text-center" maxlength="30" type="email" placeholder="Email" 
                    oninput="check_email_exist_func(this)"/>
                </div>
                <span class="text-danger mt-2 font-size-0-7 hide font-weight-bold">This email is already exist</span>
            </div>
            <div class="row">
                <div class="col-sm-4"></div>
                <div class="text-center col-sm-4 mb-3">
                    <input class="form-control btn btn-dark bg-dark" type="button" value="REGISTER" onclick="signupOnPage()"/>
                </div>
            </div>
        </div>
    </body>
</html>