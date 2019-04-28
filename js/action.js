function editWord(id){

    $('.'+id).addClass('hide');
    $('.'+id).removeClass('show');
    $('.edit-'+id).addClass('show');
    $('.edit-'+id).removeClass('hide');

}

function save(id){

    $('.'+id).removeClass('hide');
    $('.'+id).addClass('show');
    $('.edit-'+id).removeClass('show');
    $('.edit-'+id).addClass('hide');

    $('.'+id+' > span').text($('.edit-'+id+' > input').val());
}

function deleteItem(array_data,data_id,result_id){
    var datas = array_data.filter(function(item){
        return item.data_id !== data_id+'';
    });

    words = datas;
    $('#'+result_id).html(addGroupFormat(words));
}

function saveEditWord(id){
    
    var kanji = $('.edit-result-'+id+' > td > input[name=kanji]').val();
    var hiragana = $('.edit-result-'+id+' > td > input[name=hiragana]').val();
    var romanji = $('.edit-result-'+id+' > td > input[name=romanji]').val();
    var meaning = $('.edit-result-'+id+' > td > input[name=meaning]').val();

    var settings = {
        "async": true,
        "crossDomain": true,
        "url": "/JapanReview/Service/Action.php",
        "method": "POST",
        "headers": {
            "Content-Type": "application/json",
        },
        "processData": false,
        "data": "{ \"action\":\"UpdateWord\",\"ID\":\""+id+"\",\"kanji\":\""+kanji+"\",\"hiragana\":\""+hiragana+"\",\"romanji\":\""+romanji+"\",\"meaning\":\""+meaning+"\"}"
        }
        
        $.ajax(settings).done(function (response) {
            search_result();
            $('#result2').html(response);
        });

}

function deleteWord(id){
    
    if(confirm("Are you sure for delete \""+$('.edit-result-'+id+' > td > input[name=kanji]').val()+"\"")){
        var settings = {
            "async": true,
            "crossDomain": true,
            "url": "/JapanReview/Service/Action.php",
            "method": "POST",
            "headers": {
                "Content-Type": "application/json",
            },
            "processData": false,
            "data": "{ \"action\":\"DeleteWord\",\"ID\":\""+id+"\",\"kanji\":\""+$('.edit-result-'+id+' > td > input[name=kanji]').val()+"\"}"
            }
            
            $.ajax(settings).done(function (response) {
                search_result();
                $('#result2').html(response);
            });
    
    }
    
}

function getAllWord(){
    var settings = {
        "async": true,
        "crossDomain": true,
        "url": "/JapanReview/Service/Action.php",
        "method": "POST",
        "headers": {
          "Content-Type": "application/json",
        },
        "processData": false,
        "data": "{\"action\":\"getAllWord\"}"
      }
      
      $.ajax(settings).done(function (response) {
            $('#search').append(dataListFormat('word-datalist',response));
      });
}

function getGroupList(id,user_id){
    var settings = {
        "async": true,
        "crossDomain": true,
        "url": "/JapanReview/Service/Action.php",
        "method": "POST",
        "headers": {
          "Content-Type": "application/json",
        },
        "processData": false,
        "data": "{\"action\":\"getGroupList\",\"user_id\":\""+user_id+"\"}"
      }
      
      $.ajax(settings).done(function (response) {
            
            $('#'+id).html(groupListFormat(response));

      });
}


function getGroup(id,user_id,group_id){
    var settings = {
        "async": true,
        "crossDomain": true,
        "url": "/JapanReview/Service/Action.php",
        "method": "POST",
        "headers": {
          "Content-Type": "application/json",
        },
        "processData": false,
        "data": "{\"action\":\"getGroup\",\"user_id\":\""+user_id+"\",\"group_id\":\""+group_id+"\"}"
      }
      
      $.ajax(settings).done(function (response) {
            
            $('#'+id).html(addGroupFormat(response));
            words = response;

      });
}

function saveGroup(result_label_id,user_id,group_id){

    var groupObj = {
        "groupName":$('.Label > span').text(),
        "data":words
    } 

    var settings = {
        "async": true,
        "crossDomain": true,
        "url": "/JapanReview/Service/Action.php",
        "method": "POST",
        "headers": {
          "Content-Type": "application/json",
        },
        "processData": false,
        "data": "{\"action\":\"addGroup\",\"user_id\":\""+user_id+"\",\"group_id\":\""+group_id+"\",\"group\":"+JSON.stringify(groupObj)+"}"
      }
      
      $.ajax(settings).done(function (response) {
            
            //$('#'+id).html(groupListFormat(response));
            console.log(response);
            if(response.msg.indexOf("Success")!==-1){
                $('#'+result_label_id).addClass('text-success');
            }else{
                $('#'+result_label_id).addClass('text-danger');
            }
            $('#'+result_label_id).removeClass('hide');
            $('#'+result_label_id).addClass('show');
            $('#'+result_label_id).text(response.msg);

            setTimeout(function(){
                $('#'+result_label_id).removeClass('text-success');
                $('#'+result_label_id).removeClass('text-danger');
                $('#'+result_label_id).removeClass('show');
                $('#'+result_label_id).addClass('hide');
            },3000);
      });
}