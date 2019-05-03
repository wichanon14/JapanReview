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

function deleteGroup(obj){
    var label = $($($(obj).parent()).parent()).text();
    if(confirm("Are you sure for delete \""+label+"\" group")){
        var settings = {
            "async": true,
            "crossDomain": true,
            "url": "/JapanReview/Service/Action.php",
            "method": "POST",
            "headers": {
              "Content-Type": "application/json",
            },
            "processData": false,
            "data": "{\"action\":\"deleteGroup\",\"user_id\":\"1\",\"group_id\":\""+$(obj).attr('data-id')+"\"}"
          }
          
          $.ajax(settings).done(function (response) {

                window.location.reload();
          });
    }
}

function getAllWord(id,data){
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

        if(id){
            $('#'+id).append(dataListFormat('word-datalist',response));
        }

        if(data){
            var stm = data+' = '+JSON.stringify(response)+';';
            //console.log(stm);
            eval(stm);
        }

        
            
      });
}

function getGroupList(id,user_id,keyword){
    var settings = {
        "async": true,
        "crossDomain": true,
        "url": "/JapanReview/Service/Action.php",
        "method": "POST",
        "headers": {
          "Content-Type": "application/json",
        },
        "processData": false,
        "data": "{\"action\":\"getGroupList\",\"user_id\":\""+user_id+"\",\"keyword\":\""+keyword+"\"}"
      }
      
      $.ajax(settings).done(function (response) {
            
            $('#'+id).html(groupListFormat(response));
            for(var i=0;i<response.length;i++){
                var item = $('#list-group > a')[i];
                
                if($(item).attr('data-id')===groupSelect){
                    
                    $(item).addClass('active');
                    
                }
            }
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

// Add Group and Edit Group
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
            $('#'+result_label_id).addClass('text-success');
            $('#'+result_label_id).removeClass('hide');
            $('#'+result_label_id).addClass('show');
            $('#'+result_label_id).text(response.msg);

            if(group_id+""==="0"){
                window.location.reload();
            }
            setTimeout(function(){
                $('#'+result_label_id).removeClass('text-success');
                $('#'+result_label_id).removeClass('text-danger');
                $('#'+result_label_id).removeClass('show');
                $('#'+result_label_id).addClass('hide');
            },3000);
      });
}

function searchWord(keyword){
    var settings = {
        "async": true,
        "crossDomain": true,
        "url": "/JapanReview/Service/Action.php",
        "method": "POST",
        "headers": {
          "Content-Type": "application/json",
        },
        "processData": false,
        "data": "{\"action\":\"SearchWord\",\"keyword\":\""+keyword+"\"}"
      }
      
      $.ajax(settings).done(function (response) {
          console.log(response);
            $('#word-datalist').remove();
            $('#search').append(dataListFormat('word-datalist',response));
      });
}

function AddWord(id){
    var settings = {
        "async": true,
        "crossDomain": true,
        "url": "/JapanReview/Service/Action.php",
        "method": "POST",
        "headers": {
          "Content-Type": "application/json"
        },
        "processData": false,
        "data": "{\"action\":\"AddingWord\",\"kanji\":\""+searchResult[id].japanese[0].word+"\",\"hiragana\":\""+searchResult[id].japanese[0].reading+"\",\"romanji\":\""+toRomanji(searchResult[id].japanese[0].reading)+"\",\"meaning\":\""+$('input[name=searchForm]').val()+"\"}"
      }
      
      $.ajax(settings).done(function (response) {
        console.log(response);
        $('li')[id+1].innerHTML = '<span>'.concat(searchResult[id].japanese[0].word,' (',searchResult[id].japanese[0].reading,')</span>');
      });
}

function AddToGroup(id){
    var settings = {
        "async": true,
        "crossDomain": true,
        "url": "/JapanReview/Service/Action.php",
        "method": "POST",
        "headers": {
          "Content-Type": "application/json"
        },
        "processData": false,
        "data": "{\"action\":\"AddingWord\",\"kanji\":\""+searchResult[id].japanese[0].word+"\",\"hiragana\":\""+searchResult[id].japanese[0].reading+"\",\"romanji\":\""+toRomanji(searchResult[id].japanese[0].reading)+"\",\"meaning\":\""+$('input[name=searchForm]').val()+"\"}"
      }
      
      $.ajax(settings).done(function (response) {
        console.log(response);
        var data = {
            data_id: response.ID,
            word: searchResult[id].japanese[0].word
        }
        words.splice(0, 0, data);
        $('#words').html(addGroupFormat(words));
        $('li')[id+1].innerHTML = '<span>'.concat(searchResult[id].japanese[0].word,' (',searchResult[id].japanese[0].reading,')</span>');
      });
}

function toRomanji(japaneseWord){
    var word = japaneseWord;

    word = word.replace('きゃ','kya');
    word = word.replace('きゅ','kyu');
    word = word.replace('きょ','kyo');
    word = word.replace('キャ','kya');
    word = word.replace('キュ','kyu');
    word = word.replace('キョ','kyo');
    word = word.replace('ぎゃ','gya');
    word = word.replace('ぎゅ','gyu');
    word = word.replace('ぎょ','gyo');
    word = word.replace('ギャ','gya');
    word = word.replace('ギュ','gyu');
    word = word.replace('ギョ','gyo');
    word = word.replace('しゃ','sha');
    word = word.replace('しゅ','shu');
    word = word.replace('しょ','sho');
    word = word.replace('シャ','sha');
    word = word.replace('シュ','shu');
    word = word.replace('ショ','sho');
    word = word.replace('ちゃ','cha');
    word = word.replace('ちゅ','chu');
    word = word.replace('ちょ','cho');
    word = word.replace('チャ','cha');
    word = word.replace('チュ','chu');
    word = word.replace('チョ','cho');
    word = word.replace('にゃ','nya');
    word = word.replace('にゅ','nyu');
    word = word.replace('にょ','nyo');
    word = word.replace('ニャ','nya');
    word = word.replace('ニュ','nyu');
    word = word.replace('ニョ','nyo');
    word = word.replace('ひゃ','hya');
    word = word.replace('ひゅ','hyu');
    word = word.replace('ひょ','hyo');
    word = word.replace('ヒャ','hya');
    word = word.replace('ヒュ','hyu');
    word = word.replace('ヒョ','hyo');
    word = word.replace('びゃ','bya');
    word = word.replace('びゅ','byu');
    word = word.replace('びょ','byo');
    word = word.replace('ビャ','bya');
    word = word.replace('ビュ','byu');
    word = word.replace('ビョ','byo');
    word = word.replace('ぴゃ','pya');
    word = word.replace('ぴゅ','pyu');
    word = word.replace('ぴょ','pyo');
    word = word.replace('ピャ','pya');
    word = word.replace('ピュ','pyu');
    word = word.replace('ピョ','pyo');
    word = word.replace('りゃ','rya');
    word = word.replace('りゅ','ryu');
    word = word.replace('りょ','ryo');
    word = word.replace('リャ','rya');
    word = word.replace('リュ','ryu');
    word = word.replace('リョ','ryo');

    word = word.replace('あ','a');
    word = word.replace('い','i');
    word = word.replace('う','u');
    word = word.replace('え','e');
    word = word.replace('お','o');
    word = word.replace('ア','a');
    word = word.replace('イ','i');
    word = word.replace('ウ','u');
    word = word.replace('エ','e');
    word = word.replace('オ','o');

    word = word.replace('か','ka');
    word = word.replace('き','ki');
    word = word.replace('く','ku');
    word = word.replace('け','ke');
    word = word.replace('こ','ko');
    word = word.replace('カ','ka');
    word = word.replace('キ','ki');
    word = word.replace('ク','ku');
    word = word.replace('ケ','ke');
    word = word.replace('コ','ko');

    word = word.replace('が','ga');
    word = word.replace('ぎ','gi');
    word = word.replace('ぐ','gu');
    word = word.replace('げ','ge');
    word = word.replace('ご','go');
    word = word.replace('ガ','ga');
    word = word.replace('ギ','gi');
    word = word.replace('グ','gu');
    word = word.replace('ゲ','ge');
    word = word.replace('ゴ','go');


    word = word.replace('さ','sa');
    word = word.replace('し','shi');
    word = word.replace('す','su');
    word = word.replace('せ','se');
    word = word.replace('そ','so');
    word = word.replace('サ','sa');
    word = word.replace('シ','shi');
    word = word.replace('ス','su');
    word = word.replace('セ','se');
    word = word.replace('ソ','so');

    word = word.replace('ざ','za');
    word = word.replace('じ','ji');
    word = word.replace('ず','zu');
    word = word.replace('ぜ','ze');
    word = word.replace('ぞ','zo');
    word = word.replace('ザ','za');
    word = word.replace('ジ','ji');
    word = word.replace('ズ','zu');
    word = word.replace('ゼ','ze');
    word = word.replace('ゾ','zo');

    word = word.replace('た','ta');
    word = word.replace('ち','chi');
    word = word.replace('つ','tsu');
    word = word.replace('て','te');
    word = word.replace('と','to');
    word = word.replace('タ','ta');
    word = word.replace('チ','chi');
    word = word.replace('ツ','tsu');
    word = word.replace('テ','te');
    word = word.replace('ト','to');

    word = word.replace('だ','da');
    word = word.replace('ぢ','ji');
    word = word.replace('づ','zu');
    word = word.replace('で','de');
    word = word.replace('ど','do');
    word = word.replace('ダ','da');
    word = word.replace('ヂ','ji');
    word = word.replace('ヅ','zu');
    word = word.replace('デ','de');
    word = word.replace('ド','do');

    word = word.replace('な','na');
    word = word.replace('に','ni');
    word = word.replace('ぬ','nu');
    word = word.replace('ね','ne');
    word = word.replace('の','no');
    word = word.replace('ナ','na');
    word = word.replace('ニ','ni');
    word = word.replace('ヌ','nu');
    word = word.replace('ネ','ne');
    word = word.replace('ノ','no');

    word = word.replace('は','ha');
    word = word.replace('ひ','hi');
    word = word.replace('ふ','fu');
    word = word.replace('へ','he');
    word = word.replace('ほ','ho');
    word = word.replace('ハ','ha');
    word = word.replace('ヒ','hi');
    word = word.replace('フ','fu');
    word = word.replace('ヘ','he');
    word = word.replace('ホ','ho');

    word = word.replace('ば','ba');
    word = word.replace('び','bi');
    word = word.replace('ぶ','bu');
    word = word.replace('べ','be');
    word = word.replace('ぼ','bo');
    word = word.replace('バ','ba');
    word = word.replace('ビ','bi');
    word = word.replace('ブ','bu');
    word = word.replace('ベ','be');
    word = word.replace('ボ','bo');

    word = word.replace('ぱ','pa');
    word = word.replace('ぴ','pi');
    word = word.replace('ぷ','pu');
    word = word.replace('ぺ','pe');
    word = word.replace('ぽ','po');
    word = word.replace('パ','pa');
    word = word.replace('ピ','pi');
    word = word.replace('プ','pu');
    word = word.replace('ペ','pe');
    word = word.replace('ポ','po');

    word = word.replace('ま','ma');
    word = word.replace('み','mi');
    word = word.replace('む','mu');
    word = word.replace('め','me');
    word = word.replace('も','mo');
    word = word.replace('マ','ma');
    word = word.replace('ミ','mi');
    word = word.replace('ム','mu');
    word = word.replace('メ','me');
    word = word.replace('モ','mo');

    word = word.replace('や','ya');
    word = word.replace('ゆ','yu');
    word = word.replace('よ','yo');
    word = word.replace('ヤ','ya');
    word = word.replace('ユ','yu');
    word = word.replace('ヨ','yo');

    word = word.replace('ら','ra');
    word = word.replace('り','ri');
    word = word.replace('る','ru');
    word = word.replace('れ','re');
    word = word.replace('ろ','ro');
    word = word.replace('ラ','ra');
    word = word.replace('リ','ri');
    word = word.replace('ル','ru');
    word = word.replace('レ','re');
    word = word.replace('ロ','ro');

    word = word.replace('わ','wa');
    word = word.replace('を','wo');
    word = word.replace('ワ','wa');
    word = word.replace('ヲ','wo');

    word = word.replace('ん','n');
    word = word.replace('ン','n');

    word = word.replace('aa','ā');
    word = word.replace('ii','ī');
    word = word.replace('uu','ū');
    word = word.replace('oo','ō');
    word = word.replace('ou','ō');

    return word;
}