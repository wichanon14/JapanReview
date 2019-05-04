<?php 
    session_start();
?>
<!DOCTYPE html>
<html lang="jp">

<head>
    <title>Grouping Word</title>
    <?php require_once('header-resource.php'); ?>
</head>

<body class="scrollbar-custom">
    <script>
        var words = [];
        var groupSelect = "";
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
                        ' class="text-dark font-large fa fa-plus clickable" onclick="addGroup(',i,')"></i></span>');
                         
                        if(checkExist(words,response.data[i].japanese[0].word)){
                            defaultLabel += '<li class="insert list-group-item d-flex justify-content-between align-items-center font-kanit">'.concat(
                            response.data[i].japanese[0].word,' (',response.data[i].japanese[0].reading,')</li>');
                        }else{
                            defaultLabel += '<li class="insert list-group-item d-flex justify-content-between align-items-center font-kanit">'.concat(
                            response.data[i].japanese[0].word,' (',response.data[i].japanese[0].reading,')',addCase,'</li>');
                        }

                        
                    }
                    
                }
                $('#result-words-search').html(defaultLabel);
                //console.log(response.data[0].japanese[0].word);
            }
            request.send();
        }

        function onSelectGroup(obj){

            var user_id=1;
            var group_id = $(obj).attr('data-id');
            $('#save-btn').attr('group-id',group_id);
            $('#save-btn').attr('user-id',user_id);
            getGroup('words',user_id,group_id,'number-word');
            //$('#words').html(addGroupFormat(words));
            $('#list-group > a').removeClass('active');
            $(obj).addClass('active');
            $('#group-manage-addword').removeClass('hide');
            $('#group-manage-addword').addClass('show');
            $('.Label > #group-name ').text($(obj).text());
            $('.edit-Label > input ').val($(obj).text());
            groupSelect = $(obj).attr('data-id');

        }

        // Adding Group
        function onAddGroup(obj){

            var user_id=1;
            var group_id = 0;
            $('#save-btn').attr('group-id',group_id);
            $('#save-btn').attr('user-id',user_id);
            words=[];
            getGroup('words',user_id,group_id,'number-word');
            $('#list-group > a').removeClass('active');
            
            $('#group-manage-addword').removeClass('hide');
            $('#group-manage-addword').addClass('show');
            $('.Label > #group-name ').text('Group Name1');
            $('.edit-Label > input ').val('Group Name1');
            groupSelect = $(obj).attr('data-id');
        }

        //Save group after edit or added word.
        function saveGroupOnPage(obj){
            var group_id = $('#save-btn').attr('group-id');
            var user_id = $('#save-btn').attr('user-id');
        
            if(groupSelect+"" === "0"){    
                $('#group-manage-addword').removeClass('show');
                $('#group-manage-addword').addClass('hide');
            }

            saveGroup('result-save',user_id,group_id,'group-name');
            getGroupList('list-group',user_id,'');
            
        }

        function onSearchGroupList(id,user_id,keyword){
            getGroupList(id,user_id,keyword);
            
        }

        // check new search word with exist word 
        function checkExist(data,kanji){
            for(var i=0;i<data.length;i++){
                if(data[i].KANJI === kanji){
                    return true
                }
            }
            return false;
        }

        //Add word to group
        function addGroup(id){
            AddToGroup(id,'number-word');
        }

        getGroupList('list-group',1,'');
    </script>
    
    <?php require_once('header.php') ?>

    <div class="container" style="margin-top:5em;">
        <div class="row">
            <div class="col-sm-5" style="margin-right:3em;">
                <div class="mt-3">
                    <h2>
                        List Group
                        <i class="fa fa-plus clickable font-large text-primary" onclick="onAddGroup(this)"></i>
                    </h2>
                </div>
                <div class="row ml-1 mb-2">
                    <input class="form-control col-sm-11" type="text" placeholder="Search Group" oninput="onSearchGroupList('list-group',1,this.value)"/>

                </div>
                <div id="list-group" class="list-group scrollbar-custom" style="height:197px;overflow-y:auto;">
                    <a href="#" class="list-group-item list-group-item-action">
                        Cras justo odio
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">Dapibus ac facilisis in</a>
                    <a href="#" class="list-group-item list-group-item-action">Morbi leo risus</a>
                </div>
            </div>
            <div id="group-manage-addword" class="col-sm-6 mt-3 hide" >
                <div class="row  ml-1">
                    <h2>Word Grouping</h2>
                    <div class="text-danger ml-3 mt-2 hide font-weight-bold" id="add_status">
                        <span >You had alread added.</span>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-8" style="margin-right:1%;">
                        <input class="form-control " type="text" id="search" name="searchForm" placeholder="Search Word" oninput="searchFromAPI(this)"/>
                    </div>
                    <div class="col-sm-8 mt-3" >
                        <ul class="list-group border scrollbar-custom" id="result-words-search" style="height:10em;overflow-y:auto;">
                            <li class="insert list-group-item d-flex justify-content-between align-items-center bg-dark text-white">
                                <span class="font-weight-bold">Search Result</span>
                            </li>
                            <!--li class="insert list-group-item d-flex justify-content-between align-items-center font-kanit">
                                ひらがな
                            </li-->
                        </ul>
                    </div>
                </div>
                
                <div class="row ml-1" style="margin-top:50px;">
                    <div>
                        <h2>
                            <div class="Label">
                                <span id="group-name">
                                    Group Name
                                </span>
                                <span id="number-word" class="badge badge-dark badge-pill" style="font-size:0.4em;">14</span>
                                <i class="fa fa-pencil clickable font-large text-primary" onclick="editWord('Label')"></i>
                                <i class="fa fa-save clickable font-large text-primary" id="save-btn" onclick="saveGroupOnPage(this)"></i>
                            </div>
                            <div class="hide edit-Label">
                                <input type="text" value="Group Name" />
                                <i class="fa fa-check clickable" onclick="save('Label','group-name')"></i>
                            </div>

                        </h2>
                    </div>
                    
                    <div class="col-sm-4 mt-2 mb-2 hide font-weight-bold" id="result-save">
                        
                    </div>
                    <div>

                    </div>
                </div>
                <div class="row col-sm-8 scrollbar-custom" style="height:250px;overflow-y:auto;">
                    <ul class="list-group" style="width:20em;" id="words">

                    </ul>
                </div>
            </div>

        </div>


    </div>
</body>

</html>