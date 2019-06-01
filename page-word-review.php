<?php 
    session_start();

    if(!$_SESSION['user-id']){
        header('Location: ./page-home.php');
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Review Word</title>
        <?php require_once('header-resource.php'); ?>
    </head>
    <body>
        <?php require_once('header.php'); ?>
        <script>
            var groupList = [];
            var groupSelect = '';
            var wordList = [];
            var user_id = '<?php echo $_SESSION['user-id']; ?>';
            var score = 0;
            var mode="";
            var label="";
            var modeMix="";

            function onSelectGroup(obj){
                var group_id = $(obj).attr('data-id');
                if($(obj).hasClass('active')){
                    $(obj).removeClass('active');

                    for(var i=0;i<groupList.length;i++){
                        if(groupList[i]===group_id){
                            groupList.splice(i,1);
                            break;
                        }
                    }

                }else{
                    $(obj).addClass('active');
                    if(!checkExistList(group_id)){
                        groupList.push(group_id);
                    }
                }
                checkSelectAllStatus();
            }

            function checkExistList(id){
                var obj=groupList.find(function(item){
                    return item === id;
                });

                if(obj){
                    return true;
                }else{
                    return false;
                }
            }

            function setMode(){
                if(groupList.length>0){
                    $('#input-review').addClass('hide');
                    $('#mode').removeClass('hide');
                    getMultiGroup(user_id,groupList);   
                    $('#wordStatus').attr('word_index',-1);
                }else{
                    alert('Please Select Group');
                }
                
            }

            function selectAll(obj){
                score=0;    
                groupList = [];
                groupSelect = '';
                
                if($(obj).attr('status')){
                    if($(obj).attr('status')==='true'){
                        $(obj).attr('status','false');
                        $(obj).html('Select All');
                        $('#groupList > a').removeClass('active');
                        groupList=[];

                    }else{
                        $(obj).attr('status','true');
                        $(obj).html('Unselect All');
                        $('#groupList > a').addClass('active');
                        pushAll();
                        
                    }
                }else{
                    $(obj).attr('status','true');
                    $(obj).html('Unselect All');
                    $('#groupList > a').addClass('active');
                    pushAll();

                }
            }

            function pushAll(){

                for(var i=0;i<$('#groupList > a').length;i++){
                    var group_id = $($('#groupList > a')[i]).attr('data-id');
                    if(!checkExistList(group_id)){
                        groupList.push(group_id);
                    }
                }

            }

            function checkSelectAllStatus(){
             
                if(!$('#groupList > a').hasClass('active')){
                    $('#selectAllBtn').attr('status','false');
                    $('#selectAllBtn').html('Select All');
                }else{
                    var element = $('#groupList > a');
                    var sumActive = 0;
                    for(var i=0;i < element.length;i++){
                        if($($(element)[i]).hasClass('active')){
                            sumActive += 1;
                        }
                    }
                    if(sumActive === element.length){
                        $('#selectAllBtn').attr('status','true');
                        $('#selectAllBtn').html('Unselect All');
                    }
                }
            }

            function createRandomWord(data){
                
                var result_randomGroup=[];

                while(data.length>0){
                    var index = parseInt(Math.random()*100)%data.length;
                    result_randomGroup.push(data[index]);
                    data.splice(index,1);
                }
                return result_randomGroup;
            }

            function RandomMode(){
                
                var r = parseInt(Math.random()*100)%4;
                var result_random_mode;

                switch(r){
                    case 0: result_random_mode = "kan_hira"; break;
                    case 1: result_random_mode = "kan_mean"; break;
                    case 2: result_random_mode = "hira_kan"; break;
                    case 3: result_random_mode = "mean_kan"; break;
                    default : break;
                }
                
                return result_random_mode;
            }

            function showWord(index){
                switch(mode){
                    case "kan_hira" : $('#question').html(wordList[index].KANJI);
                                    fontAdapt(wordList[index].KANJI);
                                    label = "HIRAGANA";
                                    $('#answerResult').html(label);
                                    break;
                    case "kan_mean" : $('#question').html(wordList[index].KANJI); 
                                    fontAdapt(wordList[index].KANJI);
                                    label = "MEANING";
                                    $('#answerResult').html(label);
                                    break;
                    case "hira_kan" : $('#question').html(wordList[index].HIRAGANA); 
                                    fontAdapt(wordList[index].HIRAGANA);
                                    label = "KANJI";
                                    $('#answerResult').html(label);
                                    break;                
                    case "mean_kan" : $('#question').html(wordList[index].MEANING); 
                                    fontAdapt(wordList[index].MEANING);                    
                                    label = "KANJI";
                                    $('#answerResult').html(label);
                                    break;        
                    case "mix_type" : 
                                    modeMix = RandomMode();
                                    if(modeMix === "kan_hira"){
                                        $('#question').html(wordList[index].KANJI); 
                                        fontAdapt(wordList[index].KANJI);        
                                        label = "HIRAGANA";
                                    }
                                    if(modeMix === "kan_mean"){
                                        $('#question').html(wordList[index].KANJI); 
                                        fontAdapt(wordList[index].KANJI);    
                                        label = "MEANING";
                                    }
                                    if(modeMix === "hira_kan"){
                                        $('#question').html(wordList[index].HIRAGANA); 
                                        fontAdapt(wordList[index].HIRAGANA);    
                                        label = "KANJI";
                                    }
                                    if(modeMix === "mean_kan"){
                                        $('#question').html(wordList[index].MEANING);  
                                        fontAdapt(wordList[index].MEANING);    
                                        label = "KANJI";
                                    }
                                    $('#answerResult').html(label);
                                    break;                            
                    default : break;
                }
                
                $('#order').html(index+1+"/"+wordList.length);
                $('#wordStatus').attr('word_index',index);

                if(index === wordList.length-1){
                    $('.btn-next').addClass('hide');
                }else{
                    $('.btn-next').removeClass('hide');
                }
                
                if(index <= 0 ){
                    $('.btn-prev').addClass('hide');
                }else{
                    $('.btn-prev').removeClass('hide');
                }
            }

            function goBack(){         
                var word_index = parseInt($('#wordStatus').attr('word_index'));
                
                if(word_index <= 0){    
                    showWord(0);
                }else{
                    showWord(word_index-1);
                }
            }

            function goNext(){
                var word_index = parseInt($('#wordStatus').attr('word_index'));

                if(word_index >= wordList.length-1){
                    showWord(word_index);
                }else{
                    showWord(word_index+1);
                }
             
            }

            function selectMode(obj){
                var modeID = $(obj).attr('mode');
                mode = modeID;
                $('#mode').addClass('hide');
                $('#practice_block').removeClass('hide');
                $('#practice_block').addClass('insert');
                $('#question').html('Go!!');
                $('#answer').attr('placeholder','');
                setTimeout(function(){
                    showWord(0);
                },1000);

            }

            function checkResult(){
                var word_index = parseInt($('#wordStatus').attr('word_index'));
                
                if(word_index >= wordList.length){
                    BackToMain();
                }else{

                    if(mode==="kan_hira"){
                        if($('#answer').val() === wordList[word_index].HIRAGANA){
                            $('#answerResult').html(
                                '<div class="text-success">TRUE</div>'
                            );
                            score += 1;

                            
                            setTimeout(function(){
                                if(word_index >= wordList.length-1){
                                    $('#question').html(score+'/'+wordList.length);
                                    $('#answer').val('');
                                    $('#answer').attr('placeholder','Press Enter For Finish Practice');
                                    $('#answerResult').html('');
                                    $('#wordStatus').attr('word_index',word_index+1);
                                    $('#order').html('');
                                    addscore(wordList.length,score);
                                }else{
                                    goNext();
                                    $('#answer').val('');
                                    $('#answerResult').html(
                                        label
                                    );
                                }
                                
                            },500);

                        }else{
                            $('#answerResult').html(
                                '<div class="text-danger">'+wordList[word_index].HIRAGANA+'</div>'
                            );
                            setTimeout(function(){
                                if(word_index >= wordList.length-1){
                                    $('#question').html(score+'/'+wordList.length);
                                    $('#answer').val('');
                                    $('#answer').attr('placeholder','Press Enter For Finish Practice');
                                    $('#answerResult').html('');
                                    $('#wordStatus').attr('word_index',word_index+1);
                                    $('#order').html('');
                                    addscore(wordList.length,score);
                                }else{
                                    goNext();
                                    $('#answer').val('');
                                    $('#answerResult').html(
                                        label
                                    );
                                }
                            },1000);
                        }
                    }

                    if(mode==="kan_mean"){
                        if($('#answer').val().toLowerCase() === wordList[word_index].MEANING.toLowerCase()){
                            $('#answerResult').html(
                                '<div class="text-success">TRUE</div>'
                            );
                            score += 1;

                            
                            setTimeout(function(){
                                if(word_index >= wordList.length-1){
                                    $('#question').html(score+'/'+wordList.length);
                                    $('#answer').val('');
                                    $('#answer').attr('placeholder','Press Enter For Finish Practice');
                                    $('#answerResult').html('');
                                    $('#wordStatus').attr('word_index',word_index+1);
                                    $('#order').html('');
                                    addscore(wordList.length,score);
                                }else{
                                    goNext();
                                    $('#answer').val('');
                                    $('#answerResult').html(
                                        label
                                    );
                                }
                                
                            },500);

                        }else{
                            $('#answerResult').html(
                                '<div class="text-danger">'+wordList[word_index].MEANING+'</div>'
                            );
                            setTimeout(function(){
                                if(word_index >= wordList.length-1){
                                    $('#question').html(score+'/'+wordList.length);
                                    $('#answer').val('');
                                    $('#answer').attr('placeholder','Press Enter For Finish Practice');
                                    $('#answerResult').html('');
                                    $('#wordStatus').attr('word_index',word_index+1);
                                    $('#order').html('');
                                    addscore(wordList.length,score);
                                }else{
                                    goNext();
                                    $('#answer').val('');
                                    $('#answerResult').html(
                                        label
                                    );
                                }
                            },1000);
                        }
                    }
                
                    if(mode==="hira_kan"||mode==="mean_kan"){
                        if($('#answer').val() === wordList[word_index].KANJI){
                            $('#answerResult').html(
                                '<div class="text-success">TRUE</div>'
                            );
                            score += 1;

                            
                            setTimeout(function(){
                                if(word_index >= wordList.length-1){
                                    $('#question').html(score+'/'+wordList.length);
                                    $('#answer').val('');
                                    $('#answer').attr('placeholder','Press Enter For Finish Practice');
                                    $('#answerResult').html('');
                                    $('#wordStatus').attr('word_index',word_index+1);
                                    $('#order').html('');
                                    addscore(wordList.length,score);
                                }else{
                                    goNext();
                                    $('#answer').val('');
                                    $('#answerResult').html(
                                        label
                                    );
                                }
                                
                            },500);

                        }else{
                            $('#answerResult').html(
                                '<div class="text-danger">'+wordList[word_index].KANJI+'</div>'
                            );
                            setTimeout(function(){
                                if(word_index >= wordList.length-1){
                                    $('#question').html(score+'/'+wordList.length);
                                    $('#answer').val('');
                                    $('#answer').attr('placeholder','Press Enter For Finish Practice');
                                    $('#answerResult').html('');
                                    $('#wordStatus').attr('word_index',word_index+1);
                                    $('#order').html('');
                                    addscore(wordList.length,score);
                                }else{
                                    goNext();
                                    $('#answer').val('');
                                    $('#answerResult').html(
                                        label
                                    );
                                }
                            },1000);
                        }
                    }
                
                    if(mode==="mix_type"){
                        if(modeMix === "kan_hira"){
                            if($('#answer').val() === wordList[word_index].HIRAGANA){
                                $('#answerResult').html(
                                    '<div class="text-success">TRUE</div>'
                                );
                                score += 1;

                                
                                setTimeout(function(){
                                    if(word_index >= wordList.length-1){
                                        $('#question').html(score+'/'+wordList.length);
                                        $('#answer').val('');
                                        $('#answer').attr('placeholder','Press Enter For Finish Practice');
                                        $('#answerResult').html('');
                                        $('#wordStatus').attr('word_index',word_index+1);
                                        $('#order').html('');
                                        addscore(wordList.length,score);
                                    }else{
                                        goNext();
                                        $('#answer').val('');
                                        $('#answerResult').html(
                                            label
                                        );
                                    }
                                    
                                },500);

                            }else{
                                $('#answerResult').html(
                                    '<div class="text-danger">'+wordList[word_index].HIRAGANA+'</div>'
                                );
                                setTimeout(function(){
                                    if(word_index >= wordList.length-1){
                                        $('#question').html(score+'/'+wordList.length);
                                        $('#answer').val('');
                                        $('#answer').attr('placeholder','Press Enter For Finish Practice');
                                        $('#answerResult').html('');
                                        $('#wordStatus').attr('word_index',word_index+1);
                                        $('#order').html('');
                                        addscore(wordList.length,score);
                                    }else{
                                        goNext();
                                        $('#answer').val('');
                                        $('#answerResult').html(
                                            label
                                        );
                                    }
                                },1000);
                            }
                        }
                        if(modeMix === "kan_mean"){
                            if($('#answer').val().toLowerCase() === wordList[word_index].MEANING.toLowerCase()){
                                $('#answerResult').html(
                                    '<div class="text-success">TRUE</div>'
                                );
                                score += 1;

                                
                                setTimeout(function(){
                                    if(word_index >= wordList.length-1){
                                        $('#question').html(score+'/'+wordList.length);
                                        $('#answer').val('');
                                        $('#answer').attr('placeholder','Press Enter For Finish Practice');
                                        $('#answerResult').html('');
                                        $('#wordStatus').attr('word_index',word_index+1);
                                        $('#order').html('');
                                        addscore(wordList.length,score);
                                    }else{
                                        goNext();
                                        $('#answer').val('');
                                        $('#answerResult').html(
                                            label
                                        );
                                    }
                                    
                                },500);

                            }else{
                                $('#answerResult').html(
                                    '<div class="text-danger">'+wordList[word_index].MEANING+'</div>'
                                );
                                setTimeout(function(){
                                    if(word_index >= wordList.length-1){
                                        $('#question').html(score+'/'+wordList.length);
                                        $('#answer').val('');
                                        $('#answer').attr('placeholder','Press Enter For Finish Practice');
                                        $('#answerResult').html('');
                                        $('#wordStatus').attr('word_index',word_index+1);
                                        $('#order').html('');
                                        addscore(wordList.length,score);
                                    }else{
                                        goNext();
                                        $('#answer').val('');
                                        $('#answerResult').html(
                                            label
                                        );
                                    }
                                },1000);
                            }
                        }
                        if(modeMix === "hira_kan"||modeMix === "mean_kan"){
                            if($('#answer').val() === wordList[word_index].KANJI){
                                $('#answerResult').html(
                                    '<div class="text-success">TRUE</div>'
                                );
                                score += 1;

                                
                                setTimeout(function(){
                                    if(word_index >= wordList.length-1){
                                        $('#question').html(score+'/'+wordList.length);
                                        $('#answer').val('');
                                        $('#answer').attr('placeholder','Press Enter For Finish Practice');
                                        $('#answerResult').html('');
                                        $('#wordStatus').attr('word_index',word_index+1);
                                        $('#order').html('');
                                        addscore(wordList.length,score);
                                    }else{
                                        goNext();
                                        $('#answer').val('');
                                        $('#answerResult').html(
                                            label
                                        );
                                    }
                                    
                                },500);

                            }else{
                                $('#answerResult').html(
                                    '<div class="text-danger">'+wordList[word_index].KANJI+'</div>'
                                );
                                setTimeout(function(){
                                    if(word_index >= wordList.length-1){
                                        $('#question').html(score+'/'+wordList.length);
                                        $('#answer').val('');
                                        $('#answer').attr('placeholder','Press Enter For Finish Practice');
                                        $('#answerResult').html('');
                                        $('#wordStatus').attr('word_index',word_index+1);
                                        $('#order').html('');
                                        addscore(wordList.length,score);
                                    }else{
                                        goNext();
                                        $('#answer').val('');
                                        $('#answerResult').html(
                                            label
                                        );
                                    }
                                },1000);
                            }
                        }
                    }
                
                }

                

            }

            function fontAdapt(data){
                
                var fontSize=0;
                if(screen.width>1024){
                    fontSize = (-15.25)*data.length+176;
                }else{
                    fontSize = (-8.8)*data.length+98;                    
                }
                
                $('.font-review').css('font-size',fontSize+'px');
            }


            function BackToMain(){
                $('#input-review').removeClass('hide');
                selectAll($('#selectAllBtn'));
                selectAll($('#selectAllBtn'));
                $('#practice_block').addClass('hide');
                $('#mode').addClass('hide');
            }

            getGroupList('groupList',user_id,'');

            $(document).ready(function(){
                $('#answer').keypress(function(e){
                    
                    if(e.key === 'Enter'){
                        checkResult();
                    }
                    
                });

                
            });
            
        </script>
        <div class="container" style="margin-top:10em;">
            <div class="row" id="input-review">
                <div class="col-sm-3"></div>
                <div class="col-sm-6">
                    <div class="font-weight-bold text-center">
                        <h2>
                            <span>SELECT YOUR GROUP FOR REVIEW</span>
                        </h2>
                    </div>
                    <div class="text-right mb-3">
                        <button id="selectAllBtn" class="btn btn-dark" onclick="selectAll(this)">Select All</button>
                    </div>
                    <div id="groupList" class="border list-group scrollbar-custom" style="height:300px;" >
                        <a href="#" class="list-group-item list-group-item-action">
                            Cras justo odio
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">Dapibus ac facilisis in</a>
                        <a href="#" class="list-group-item list-group-item-action">Morbi leo risus</a>
                    </div>
                    <div class="text-center">
                        <button class="btn btn-dark col-sm-6" onclick="setMode()">Review Word</button>
                    </div>
                </div>
                
            </div>
            <div class="hide" id="mode">
                <div class="row">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-6 text-center">
                        <div class="mb-4">
                            <h2>
                                <span>SELECT YOUR MODE</span>
                            </h2>
                        </div>
                        <!--div>
                            <button class="btn btn-dark col-sm-10 mb-2" mode="pic_kanji" onclick="selectMode(this)"
                            disabled>PICTURE -> KANJI</button>
                        </div-->
                        <div>
                            <button class="btn btn-dark col-sm-10 mb-2" mode="kan_hira" onclick="selectMode(this)"
                            >KANJI -> HIRAGANA / KATAKANA</button>
                        </div>
                        <!--div>
                            <button class="btn btn-dark col-sm-10 mb-2" mode="kan_roma" onclick="selectMode(this)"
                            disabled>KANJI -> ROMANJI</button>
                        </div-->
                        <div>
                            <button class="btn btn-dark col-sm-10 mb-2" mode="kan_mean" onclick="selectMode(this)"
                            >KANJI -> MEANING</button>
                        </div>
                        <div>
                            <button class="btn btn-dark col-sm-10 mb-2" mode="hira_kan" onclick="selectMode(this)"
                            >HIRAGANA / KATAKANA -> KANJI</button>
                        </div>
                        <!--div>
                            <button class="btn btn-dark col-sm-10 mb-2" mode="kan_hira" onclick="selectMode(this)"
                            disabled>ROMANJI -> KANJI</button>
                        </div-->
                        <div>
                            <button class="btn btn-dark col-sm-10 mb-2" mode="mean_kan" onclick="selectMode(this)"
                            >MEANING -> KANJI</button>
                        </div>
                        <div>
                            <button class="btn btn-dark col-sm-10 mb-2" mode="mix_type" onclick="selectMode(this)"
                            >MIX TYPE</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hide" id="practice_block">    
                <div class="row" >
                    <div class="col-sm-3"></div>
                    <div class="col-sm-6 text-center">
                        <div id="question" class="font-review">
                            Go!!
                        </div>
                        <div id="order">
                            Ready ~
                        </div>
                    </div>
                </div>
                <!--div class="row mb-4">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-6">
                        <button class="btn btn-dark btn-prev pull-left hide" onclick="goBack()">Back</button>
                        <button class="btn btn-dark btn-next pull-right hide" onclick="goNext()">Next</button>
                        
                    </div>
                </div-->
                <div class="row mb-4">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-6">
                        <div id="answerResult" class="text-center">
                            HIRAGANA
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-6">
                        <input id="answer" type="text" class="form-control text-center"/>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-6 text-center">
                        <input type="button" class="btn btn-dark" value="SUBMIT" onclick="checkResult()"/>
                    </div>
                </div>
                <div id="wordStatus" class="row hide"></div>
            </div>
        </div>
    </body>
</html>