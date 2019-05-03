<!DOCTYPE html>
<html>
    <head>
        <title>Home Page</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
            integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" href="./css/action.css">
        <link rel="stylesheet" href="./css/AnimateField.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
        </script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
        </script>
        <script src="./js/formatAdjust.js"></script>
        <script src="./js/action.js"></script>
    </head>
    <body class="bg-dark">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            
            <a class="navbar-brand mt-minus-2" href="#">
                <img src="./img/logo.png" width="100" height="47.4" class="d-inline-block align-top" alt="">
            </a>
            <button class="navbar-toggler mt-minus-2" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>        
            <div class="collapse navbar-collapse bg-dark " id="navbarNav">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link" href="#">Learning Journey</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Practice Journey</a>
                    </li>
                </ul>
            </div>

            <!-- Sign In Module -->
            <div class="nav-item dropdown my-2 my-lg-0">
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
                            <input class="form-control" type="input" />
                        </div>
                    </div>
                    <div class="row mt-3"></div>
                    <div class="row">
                        <div class="col-sm-4 mt-1 ml-4 mr-1 text-light">
                            <span>Password :</span>
                        </div>
                        <div class="col-sm-6">
                            <input class="form-control" type="password" />
                        </div>
                    </div>
                    <div class="row mt-3" style="margin-left:63%;">
                        <div class="col-sm-5">
                            <button class="btn btn-outline-light" type="button">Sign In</button>
                        </div>
                    </div>
                </div>
            </div>

        </nav>
        <div class="container">
            <img src="./img/logo.png"/>
        </div>
    </body>
</html>
