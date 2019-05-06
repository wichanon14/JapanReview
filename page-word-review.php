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
            var user_id = '<?php echo $_SESSION['user-id']; ?>';

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
            }

            function onSelectAll(){
                
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

            function review(){
                $('#input-review').addClass('hide');
                $('#on-review').removeClass('hide');
                getMultiGroup(user_id,groupList);
            }

            getGroupList('groupList',user_id,'');

        </script>
        <div class="container" style="margin-top:10em;">
            <div class="row" id="input-review">
                <div class="col-sm-3"></div>
                <div class="col-sm-6">
                    <div class="font-weight-bold text-center">
                        <h2>SELECT YOUR GROUP FOR REVIEW</h2>
                    </div>
                    <div id="groupList" class="border list-group scrollbar-custom" style="height:300px;" >
                        <a href="#" class="list-group-item list-group-item-action">
                            Cras justo odio
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">Dapibus ac facilisis in</a>
                        <a href="#" class="list-group-item list-group-item-action">Morbi leo risus</a>
                    </div>
                    <div class="text-center">
                        <button class="btn btn-dark col-sm-6" onclick="review()">Review Word</button>
                    </div>
                </div>
                
            </div>
            <div class="row hide" id="on-review">
                <div class="col-sm-3"></div>
                <div class="col-sm-6">
                    <div id="question">
                        <h1>東</h1>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>