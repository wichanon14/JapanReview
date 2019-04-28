<?php 
    session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Grouping Word</title>
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

<body>
    <script>
        var words = [];
        var groupSelectID = "";

        $(document).ready(function () {

            $('#search').on('keyup', function (e) {
                if (e.keyCode === 13) {
                    e.preventDefault();

                    $('#add_status').removeClass("show");
                    $('#add_status').addClass("hide");

                    var optionObj = getWordOptionObj(this.value);

                    var check_exist = words.filter(function (val) {
                        return $(optionObj).attr('data-id') === val.data_id;
                    })



                    if (!check_exist.length) {
                        if ($(optionObj).attr('data-id')) {
                            var data = {
                                data_id: $(optionObj).attr('data-id'),
                                word: $(optionObj).val()
                            }

                            words.splice(0, 0, data);

                            $('#words').html(addGroupFormat(words));
                        }

                    } else {
                        $('#add_status').removeClass("hide");
                        $('#add_status').addClass("show");

                        setTimeout(function(){
                            $('#add_status').removeClass("show");
                            $('#add_status').addClass("hide");
                        },3000);
                    }

                    this.value = "";
                }
            });

        })

        function getWordOptionObj(value) {
            var Alloption = $('#word-datalist > option');

            var obj = Alloption.filter(function (index, elem) {
                return elem.value === value;
            });

            return $(obj)[0];
        }

        function onSelectGroup(obj){

            var user_id=1;
            var group_id = $(obj).attr('data-id');
            $('#save-btn').attr('group-id',group_id);
            $('#save-btn').attr('user-id',user_id);
            getGroup('words',user_id,group_id);
            //$('#words').html(addGroupFormat(words));
            $('#list-group > a').removeClass('active');
            $(obj).addClass('active');
            $('#group-manage-addword').removeClass('hide');
            $('#group-manage-addword').addClass('show');
            $('.Label > span ').text($(obj).text());
            $('.edit-Label > input ').val($(obj).text());
            groupSelect = $(obj).attr('data-id');

        }

        function saveGroupOnPage(obj){
            var group_id = $('#save-btn').attr('group-id');
            var user_id = $('#save-btn').attr('user-id');
        
            saveGroup('result-save',user_id,group_id);
            getGroupList('list-group',1);
        }

        getAllWord();
        getGroupList('list-group',1);
    </script>
    <div class="container">
        <div></div>
        <div class="row">
            <div class="col-sm-5" style="margin-right:3em;">
                <div class="mt-3">
                    <h2>
                        List Group
                    </h2>
                </div>
                <div id="list-group" class="list-group scrollbar-custom" style="height:197px;overflow-y:auto;">
                    <a href="#" class="list-group-item list-group-item-action">
                        Cras justo odio
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">Dapibus ac facilisis in</a>
                    <a href="#" class="list-group-item list-group-item-action">Morbi leo risus</a>
                </div>
            </div>
            <div id="group-manage-addword" class="col-sm-6 mt-3 ml-3 hide" >
                <div class="row">
                    <h2>Word Grouping</h2>
                    <div class="text-danger ml-3 mt-2 hide font-weight-bold" id="add_status">
                        <span >You had alread added.</span>
                    </div>
                </div>
                
                <div class="row">
                    <input class="form-control col-sm-8" type="text" id="search" list="word-datalist" />

                </div>
                <div class="row" style="margin-top:150px;">
                    <div>
                        <h2>
                            <div class="Label">
                                <span>
                                    Group Name
                                </span>
                                <i class="fa fa-pencil clickable font-large" onclick="editWord('Label')"></i>
                                <i class="fa fa-save clickable font-large" id="save-btn" onclick="saveGroupOnPage(this)"></i>
                            </div>
                            <div class="hide edit-Label">
                                <input type="text" value="Group Name" />
                                <i class="fa fa-check clickable" onclick="save('Label')"></i>
                            </div>

                        </h2>
                    </div>
                    <div class="col-sm-3 mt-2 mb-2 hide font-weight-bold" id="result-save">
                        
                    </div>
                    <div>

                    </div>
                </div>
                <div class="row col-sm-8 scrollbar-custom" style="height:300px;overflow-y:auto;">
                    <ul class="list-group" style="width:20em;" id="words">

                    </ul>
                </div>
            </div>

        </div>


    </div>
</body>

</html>