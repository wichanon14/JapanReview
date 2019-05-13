<?php 
    session_start();

    if(!$_SESSION['user-id']){
        header('Location: ./page-home.php');
    }
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
        var user_id = '<?php echo $_SESSION['user-id']; ?>';
        var resultGateway;
        getAllWord(null,'existData');
        var searchResult=[];
        function searchFromAPI(obj){
            showLoading();
            resultGateway=true;
            let request = new XMLHttpRequest();
            request.open("GET", "https://cors.io/?https://jisho.org/api/v1/search/words?keyword="+$(obj).val()+" ", true);
            request.onload = () => {
                
                var response = JSON.parse(request.responseText);
                
                var defaultLabel = '<li class="insert list-group-item d-flex justify-content-between align-items-center bg-dark text-white">'+
                        '<span class="font-weight-bold">Search Result</span></li>';
                    /*<li class="insert list-group-item d-flex justify-content-between align-items-center font-kanit">
                    response.data[i].japanese[0].word;
                    </li>*/
                searchResult = response.data;
                for(var i=0;i<searchResult.length;i++){
                    searchResult[i].KANJI = searchResult[i].japanese[0].word;
                    if(searchResult[i].japanese[0].word){
                        var addCase = '<span class="badge badge-pill"><i data-id='.concat(i,
                        ' class="text-dark font-large fa fa-plus clickable" onclick="addGroup(',i,')"></i></span>');
                         
                        if(checkExist(words,searchResult[i].japanese[0].word)){
                            defaultLabel += '<li class="insert list-group-item d-flex justify-content-between align-items-center font-kanit">'.concat(
                            searchResult[i].japanese[0].word,' (',searchResult[i].japanese[0].reading,')</li>');
                        }else{
                            defaultLabel += '<li class="insert list-group-item d-flex justify-content-between align-items-center font-kanit">'.concat(
                            searchResult[i].japanese[0].word,' (',searchResult[i].japanese[0].reading,')',addCase,'</li>');
                        }

                        
                    }
                    //console.log(searchResult[i].senses[0].english_definitions[0]);
                }
                $('#result-words-search').css('background','');
                $('#result-words-search').html(defaultLabel);
                //console.log(response.data[0].japanese[0].word);
            }
            request.send();
        }

        function onSelectGroup(obj){

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
        
            if(groupSelect+"" === "0"){    
                $('#group-manage-addword').removeClass('show');
                $('#group-manage-addword').addClass('hide');
            }else{
                $('#search-group').val('');
            }

            saveGroup('result-save',user_id,group_id,'group-name');
            
        }

        function onSearchGroupList(id,user_id,keyword){
            getGroupList(id,user_id,keyword);
            
        }

        // check new search word with exist word 
        function checkExist(data,kanji){
            for(var i=0;i<data.length;i++){
                if(data[i].word === kanji){
                    console.log(kanji);
                    return true
                }
            }
            return false;
        }

        //Add word to group
        function addGroup(id){
            
            if(!checkExist(words,searchResult[id].japanese[0].word)){
                //console.log(words);
                AddToGroup(id,'number-word');
            }else{
                $('#add_status').removeClass('hide');
                $('#add_status').addClass('show');
                setTimeout(function(){
                    $('#add_status').addClass('hide');
                    $('#add_status').removeClass('show');
                },1000);
            }

        }

        function updatelist(){
            var defaultLabel = '<li class="insert list-group-item d-flex justify-content-between align-items-center bg-dark text-white">'+
                        '<span class="font-weight-bold">Search Result</span></li>';

            for(var i=0;i<searchResult.length;i++){
                
                if(searchResult[i].japanese[0].word){
                    var addCase = '<span class="badge badge-pill"><i data-id='.concat(i,
                    ' class="text-dark font-large fa fa-plus clickable" onclick="addGroup(',i,')"></i></span>');
                        
                    if(checkExist(words,searchResult[i].japanese[0].word)){
                        defaultLabel += '<li class="insert list-group-item d-flex justify-content-between align-items-center font-kanit">'.concat(
                        searchResult[i].japanese[0].word,' (',searchResult[i].japanese[0].reading,')</li>');
                    }else{
                        defaultLabel += '<li class="insert list-group-item d-flex justify-content-between align-items-center font-kanit">'.concat(
                        searchResult[i].japanese[0].word,' (',searchResult[i].japanese[0].reading,')',addCase,'</li>');
                    }
           
                }
                
            }
            $('#result-words-search').html(defaultLabel);
        }

        function showLoading(){
            var template = '<li class="insert list-group-item d-flex justify-content-between align-items-center bg-dark text-white">'.concat(
                                '<span class="font-weight-bold">Search Result</span>',
                            '</li>',
                            '<div class="text-center mt-4">',
                                '<img src="./img/puff.svg" width="50"/>',
                            '</div>');
            $('#result-words-search').css('background-color','#a0a0a0');
            $('#result-words-search').html(template);
        }

        getGroupList('list-group',user_id,'');
        
        
    </script>
    
    <?php require_once('header.php') ?>

    <div class="container" style="margin-top:5em;">
        <div class="row">
            <div class="col-sm-5" style="margin-right:3em;">
                <div class="mt-3">
                    <h2>
                        List Group
                        <label title="Add Group">
                            <i class="fa fa-plus clickable font-large text-primary" onclick="onAddGroup(this)"></i>
                        </label>
                        <label title="Delete All Group">
                            <i class="fa fa-trash clickable font-large text-primary" user-id="<?php echo $_SESSION['user-id']; ?>" onclick="deleteAllGroup(this)"></i>
                        </label>
                    </h2>
                </div>
                <div class="row ml-1 mb-2">
                    <input class="form-control col-sm-11" id="search-group" type="text" placeholder="Search Group" 
                    onfocus="onSearchGroupList('list-group',<?php echo $_SESSION['user-id']; ?>,this.value)"/>

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
                        <h3>
                            <div class="Label">
                                <span id="group-name">
                                    Group Name
                                </span>
                                <label title="Number of word">
                                    <span id="number-word" class="badge badge-dark badge-pill" style="font-size:0.4em;">14</span>
                                </label>
                                <label title="Edit Group Name">
                                    <i class="fa fa-pencil clickable font-large text-primary" onclick="editWord('Label')"></i>
                                </label>
                                <label title="Save Group">
                                    <i class="fa fa-save clickable font-large text-primary" id="save-btn" onclick="saveGroupOnPage(this)"></i>
                                </label>    
                                <label title="Delete All Word">
                                    <i class="fa fa-trash clickable font-large text-primary" onclick="deleteAllInGroup()"></i>
                                </label>
                            </div>
                            <div class="hide edit-Label">
                                <input name="Group_Name" type="text" value="Group Name" size="13"/>
                                <i class="fa fa-check clickable" onclick="save('Label','group-name');"></i>
                            </div>

                        </h3>
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