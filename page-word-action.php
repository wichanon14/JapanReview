<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Add Word To Japanese</title>
        <meta charset="utf-8"> 
        <meta name="viewport" content="width=device-width, initial-scale=1">      
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" href="./css/action.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script src="./js/formatAdjust.js" ></script>
        <script src="./js/action.js" ></script>
        <link href="https://fonts.googleapis.com/css?family=Kanit" rel="stylesheet">
    </head>
    <body>
        <style>
            .font-kanit{
                font-family: 'Kanit', sans-serif;
            }

            .show-mobile {
                display: block
            }

            @media only screen and (min-width: 767px) {
                .show-mobile {
                    display: none;
                }
            
            }
        </style>
        <script>
            var existData;
            getAllWord(null,'existData');
            var searchResult=[];
            function searchFromAPI(obj){
                let request = new XMLHttpRequest();
                request.open("GET", "https://cors.io/?https://jisho.org/api/v1/search/words?keyword="+$(obj).val(), true);
                request.onload = () => {
                    var response = JSON.parse(request.responseText);
                    var defaultLabel = '<li class="insert list-group-item d-flex justify-content-between align-items-center bg-dark text-white">'+
                            '<span class="font-weight-bold">Search Result</span></li>';
                        /*<li class="insert list-group-item d-flex justify-content-between align-items-center font-kanit">
                        response.data[i].japanese[0].word;
                        </li>*/
                    searchResult = response.data;
                    for(var i=0;i<response.data.length;i++){
                        if(response.data[i].japanese[0].word){
                            var addCase = '<span class="badge badge-pill"><i data-id='.concat(i,
                            ' class="text-dark font-large fa fa-plus clickable" onclick="AddWord(',i,')"></i></span>');

                            if(checkExist(response.data[i].japanese[0].word)){
                                defaultLabel += '<li class="insert list-group-item d-flex justify-content-between align-items-center font-kanit">'.concat(
                                response.data[i].japanese[0].word,' (',response.data[i].japanese[0].reading,')</li>');
                            }else{
                                defaultLabel += '<li class="insert list-group-item d-flex justify-content-between align-items-center font-kanit">'.concat(
                                response.data[i].japanese[0].word,' (',response.data[i].japanese[0].reading,')',addCase,'</li>');
                            }

                            
                        }
                        
                    }
                    $('#words').html(defaultLabel);
                    //console.log(response.data[0].japanese[0].word);
                }
                request.send();
            }

            function checkExist(kanji){
                for(var i=0;i<existData.length;i++){
                    if(existData[i].KANJI === kanji){
                        return true
                    }
                }
                return false;
            }

        </script>

        <?php require_once('header.php'); ?>

        <div class="container">
            <div class="row" style="margin-top:7em;">
                <div class="col-sm-3"></div>
                <div class="col-sm-5">
                    <h2>Search Word</h2>
                    <input name="searchForm" class="form-control" type="text"  oninput="searchFromAPI(this)" />
                </div>
                <div class="col-sm-3">
                    <input name="searchbtn" type="button" value="search" class="btn show-mobile" onclick="searchFromAPI($('input[name=searchForm]'))"/>
                </div>
            </div>
            <div class="row" style="margin-top:2em;">
                <div class="col-sm-3"></div>
                <div class="col-sm-5" >
                    <ul class="list-group border scrollbar-custom" id="words" style="height:15em;overflow-y:auto;">
                        <li class="insert list-group-item d-flex justify-content-between align-items-center bg-dark text-white">
                            <span class="font-weight-bold">Search Result</span>
                        </li>
                        <!--li class="insert list-group-item d-flex justify-content-between align-items-center font-kanit">
                            ひらがな
                        </li-->
                    </ul>
                </div>
                <div class="col-sm-3"></div>
            </div>
        </div>
    </body>
    <?php 
        unset($_SESSION['add_result']);
        unset($_SESSION['edit_result']);
    ?>
</html>