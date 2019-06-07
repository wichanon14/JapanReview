function editWord(id){

    $('.'+id).addClass('hide');
    $('.'+id).removeClass('show');
    $('.edit-'+id).addClass('show');
    $('.edit-'+id).removeClass('hide');

}

function save(id,subid){

    $('.'+id).removeClass('hide');
    $('.'+id).addClass('show');
    $('.edit-'+id).removeClass('show');
    $('.edit-'+id).addClass('hide');

    if(subid){
      $('.'+id+' > #'+subid).text($('.edit-'+id+' > input').val());
    }else{
      $('.'+id+' > span').text($('.edit-'+id+' > input').val());
    }
    
}

function deleteItem(array_data,data_id,result_id){
    var datas = array_data.filter(function(item){
        return item.data_id+'' !== data_id+'';
    });

    words = datas;
    $('#number-word').text(words.length);
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
            "data": "{\"action\":\"deleteGroup\",\"user_id\":\""+user_id+"\",\"group_id\":\""+$(obj).attr('data-id')+"\"}"
          }
          
          $.ajax(settings).done(function (response) {

                window.location.reload();
          });
    }
}

function deleteAllGroup(obj){
  if(confirm("Are you sure for delete all group")){
      var settings = {
          "async": true,
          "crossDomain": true,
          "url": "/JapanReview/Service/Action.php",
          "method": "POST",
          "headers": {
            "Content-Type": "application/json",
          },
          "processData": false,
          "data": "{\"action\":\"deleteAllGroup\",\"user_id\":\""+$(obj).attr('user-id')+"\"}"
        }
        
        $.ajax(settings).done(function (response) {

              window.location.reload();
        });
  }
}

function deleteAllInGroup(){
  if(confirm("Are you sure for delete all word that was contain in the group")){
      var settings = {
          "async": true,
          "crossDomain": true,
          "url": "/JapanReview/Service/Action.php",
          "method": "POST",
          "headers": {
            "Content-Type": "application/json",
          },
          "processData": false,
          "data": "{\"action\":\"deleteAllWordInGroup\",\"user_id\":\""+user_id+"\",\"group_id\":\""+groupSelect+"\"}"
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
        "data": "{\"action\":\"getAllWord\",\"user_id\":\""+user_id+"\"}"
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

//get group List sigle select
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

function getGroup(id,user_id,group_id,number_result){
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
            $('#'+number_result).text(words.length);

      });
}

// Add Group and Edit Group
function saveGroup(result_label_id,user_id,group_id,group_name_id){

    var groupObj = {
        "groupName":$('.Label > #'+group_name_id).text(),
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

            getGroupList('list-group',user_id,'');

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
        "data": "{\"action\":\"SearchWord\",\"keyword\":\""+keyword+"\",\"user_id\":\""+user_id+"\"}"
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
        "data": "{\"action\":\"AddingWord\",\"kanji\":\""+searchResult[id].japanese[0].word+"\",\"hiragana\":\""+searchResult[id].japanese[0].reading+"\",\"romanji\":\""+toRomanji(searchResult[id].japanese[0].reading)+"\",\"meaning\":\""+replaceQuote(searchResult[id].senses[0].english_definitions[0])+"\",\"user_id\":\""+user_id+"\"}"
      }
      
      $.ajax(settings).done(function (response) {
        console.log(response);
        $('li')[id+1].innerHTML = '<span>'.concat(searchResult[id].japanese[0].word,' (',searchResult[id].japanese[0].reading,')</span>');
      });
}

function AddWordByForm(word,hiragana,romanji,meaning){
  word = $('#add_kanji').val();
  hiragana = $('#add_hira').val();
  romanji = $('#add_roman').val();
  meaning = $('#add_meaning').val();
  var settings = {
      "async": true,
      "crossDomain": true,
      "url": "/JapanReview/Service/Action.php",
      "method": "POST",
      "headers": {
        "Content-Type": "application/json"
      },
      "processData": false,
      "data": "{\"action\":\"AddingWord\",\"kanji\":\""+word+"\",\"hiragana\":\""+hiragana+"\",\"romanji\":\""+romanji+"\",\"meaning\":\""+meaning+"\",\"user_id\":\""+user_id+"\"}"
    }
    
    $.ajax(settings).done(function (response) {
      console.log(response);
      if(response.ID){
        var data = {
          data_id: response.ID,
          word: word
        }
        words.splice(0, 0, data);
        updatelist();
        $('#words').html(addGroupFormat(words));
        $('#number-word').text(words.length);
      }
      
    });
}

function AddToGroup(id,number_result_id){
    var settings = {
        "async": true,
        "crossDomain": true,
        "url": "/JapanReview/Service/Action.php",
        "method": "POST",
        "headers": {
          "Content-Type": "application/json"
        },
        "processData": false,
        "data": "{\"action\":\"AddingWord\",\"kanji\":\""+searchResult[id].japanese[0].word+"\",\"hiragana\":\""+searchResult[id].japanese[0].reading+"\",\"romanji\":\""+toRomanji(searchResult[id].japanese[0].reading)+"\",\"meaning\":\""+replaceQuote(searchResult[id].senses[0].english_definitions[0])+"\",\"user_id\":\""+user_id+"\"}"
      }
      
      $.ajax(settings).done(function (response) {
        console.log(response);
        var data = {
            data_id: response.ID,
            word: searchResult[id].japanese[0].word
        }
        words.splice(0, 0, data);
        updatelist();
        $('#words').html(addGroupFormat(words));
        $('#'+number_result_id).text(words.length);
      });
}

function replaceQuote(oldString){
  
  for(var i=0;i<oldString.length;i++){

    if(oldString[i]==="'" || oldString[i]==='\"'){
      var font = oldString.substr(0,i-0);
      var back = oldString.substr(i+1);
      oldString = font+"_"+back;
      i++;
    }
  }
  
  console.log(oldString);

  return oldString;
}

function isEnglist(word){
  var english = /^[A-Za-zāīūēō]/;

  for(var i=0;i<word.length;i++){
    if(!english.test(word[i])){
      console.log(word,'>>',false);
      return false;
    }
  }
  console.log(word,'>>',true);
  return true;

}

function toRomanji(japaneseWord){
    var word = japaneseWord;
      
    while(!isEnglist(word)){
      word = word.replace(/ん/g, "n");
      
      word = word.replace(/きゃ/g, "kya");
      word = word.replace(/きゅ/g, "kyu");
      word = word.replace(/きょ/g, "kyo");
      word = word.replace(/にゃ/g, "nya");
      word = word.replace(/にゅ/g, "nyu");
      word = word.replace(/にょ/g, "nyo");
      word = word.replace(/しゃ/g, "sha");
      word = word.replace(/しゅ/g, "shu");
      word = word.replace(/しょ/g, "sho");
      word = word.replace(/ちゃ/g, "cha");
      word = word.replace(/ちゅ/g, "chu");
      word = word.replace(/ちょ/g, "cho");
      word = word.replace(/ひゃ/g, "hya");
      word = word.replace(/ひゅ/g, "hyu");
      word = word.replace(/ひょ/g, "hyo");
      word = word.replace(/みゃ/g, "mya");
      word = word.replace(/みゅ/g, "myu");
      word = word.replace(/みょ/g, "myo");
      word = word.replace(/りゃ/g, "rya");
      word = word.replace(/りゅ/g, "ryu");
      word = word.replace(/りょ/g, "ryo");
      word = word.replace(/ぎゃ/g, "gya");
      word = word.replace(/ぎゅ/g, "gyu");
      word = word.replace(/ぎょ/g, "gyo");
      word = word.replace(/びゃ/g, "bya");
      word = word.replace(/びゅ/g, "byu");
      word = word.replace(/びょ/g, "byo");
      word = word.replace(/ぴゃ/g, "pya");
      word = word.replace(/ぴゅ/g, "pyu");
      word = word.replace(/ぴょ/g, "pyo");
      word = word.replace(/じゃ/g, "ja");
      word = word.replace(/じゅ/g, "ju");
      word = word.replace(/じょ/g, "jo");

      word = word.replace(/し/g, "shi");
      word = word.replace(/ち/g, "chi");
      word = word.replace(/つ/g, "tsu");

      word = word.replace(/ば/g, "ba");
      word = word.replace(/だ/g, "da");
      word = word.replace(/が/g, "ga");
      word = word.replace(/は/g, "ha");
      word = word.replace(/か/g, "ka");
      word = word.replace(/ま/g, "ma");
      word = word.replace(/な/g, "na");
      word = word.replace(/ぱ/g, "pa");
      word = word.replace(/ら/g, "ra");
      word = word.replace(/さ/g, "sa");
      word = word.replace(/た/g, "ta");
      word = word.replace(/わ/g, "wa");
      word = word.replace(/や/g, "ya");
      word = word.replace(/ざ/g, "za");
      word = word.replace(/あ/g, "a");
      word = word.replace(/べ/g, "be");
      word = word.replace(/で/g, "de");
      word = word.replace(/げ/g, "ge");
      word = word.replace(/へ/g, "he");
      word = word.replace(/け/g, "ke");
      word = word.replace(/め/g, "me");
      word = word.replace(/ね/g, "ne");
      word = word.replace(/ぺ/g, "pe");
      word = word.replace(/れ/g, "re");
      word = word.replace(/せ/g, "se");
      word = word.replace(/て/g, "te");
      word = word.replace(/ゑ/g, "we");
      word = word.replace(/ぜ/g, "ze");
      word = word.replace(/え/g, "e");
      word = word.replace(/び/g, "bi");
      word = word.replace(/ぎ/g, "gi");
      word = word.replace(/ひ/g, "hi");
      word = word.replace(/き/g, "ki");
      word = word.replace(/み/g, "mi");
      word = word.replace(/に/g, "ni");
      word = word.replace(/ぴ/g, "pi");
      word = word.replace(/り/g, "ri");
      word = word.replace(/ゐ/g, "wi");
      word = word.replace(/じ/g, "ji");
      word = word.replace(/い/g, "i");
      word = word.replace(/ぼ/g, "bo");
      word = word.replace(/ど/g, "do");
      word = word.replace(/ご/g, "go");
      word = word.replace(/ほ/g, "ho");
      word = word.replace(/こ/g, "ko");
      word = word.replace(/も/g, "mo");
      word = word.replace(/の/g, "no");
      word = word.replace(/ぽ/g, "po");
      word = word.replace(/ろ/g, "ro");
      word = word.replace(/そ/g, "so");
      word = word.replace(/と/g, "to");
      word = word.replace(/を/g, "wo");
      word = word.replace(/よ/g, "yo");
      word = word.replace(/ぞ/g, "zo");
      word = word.replace(/お/g, "o");
      word = word.replace(/ぶ/g, "bu");
      word = word.replace(/ぐ/g, "gu");
      word = word.replace(/ふ/g, "fu");
      word = word.replace(/く/g, "ku");
      word = word.replace(/む/g, "mu");
      word = word.replace(/ぬ/g, "nu");
      word = word.replace(/ぷ/g, "pu");
      word = word.replace(/る/g, "ru");
      word = word.replace(/す/g, "su");
      word = word.replace(/ゆ/g, "yu");
      word = word.replace(/ず/g, "zu");
      word = word.replace(/う/g, "u");
      word = word.replace(/ゔ/g, "v");
      word = word.replace(/ぢ/g, "ji");
      word = word.replace(/づ/g, "zu");

      // ka
      word = word.replace(/ン/g, "n");
      word = word.replace(/キャ/g, "kya");
      word = word.replace(/キュ/g, "kyu");
      word = word.replace(/キョ/g, "kyo");
      word = word.replace(/ニャ/g, "nya");
      word = word.replace(/ニュ/g, "nyu");
      word = word.replace(/ニョ/g, "nyo");
      word = word.replace(/シャ/g, "sha");
      word = word.replace(/シュ/g, "shu");
      word = word.replace(/ショ/g, "sho");
      word = word.replace(/チャ/g, "cha");
      word = word.replace(/チュ/g, "chu");
      word = word.replace(/チョ/g, "cho");
      word = word.replace(/ヒャ/g, "hya");
      word = word.replace(/ヒュ/g, "hyu");
      word = word.replace(/ヒョ/g, "hyo");
      word = word.replace(/ミャ/g, "mya");
      word = word.replace(/ミュ/g, "myu");
      word = word.replace(/ミョ/g, "myo");
      word = word.replace(/リャ/g, "rya");
      word = word.replace(/リュ/g, "ryu");
      word = word.replace(/リョ/g, "ryo");
      word = word.replace(/ギャ/g, "gya");
      word = word.replace(/ギュ/g, "gyu");
      word = word.replace(/ギョ/g, "gyo");
      word = word.replace(/ビャ/g, "bya");
      word = word.replace(/ビュ/g, "byu");
      word = word.replace(/ビョ/g, "byo");
      word = word.replace(/ピャ/g, "pya");
      word = word.replace(/ピュ/g, "pyu");
      word = word.replace(/ピョ/g, "pyo");
      word = word.replace(/ジャ/g, "ja");
      word = word.replace(/ジュ/g, "ju");
      word = word.replace(/ジョ/g, "jo");

      // ajout
      word = word.replace(/ツァ/g, "tsa");
      word = word.replace(/ツィ/g, "tsi");
      word = word.replace(/ツェ/g, "tse");
      word = word.replace(/ツォ/g, "tso");
      word = word.replace(/チェ/g, "che");
      word = word.replace(/シェ/g, "she");
      word = word.replace(/ジェ/g, "je");
      word = word.replace(/ティ/g, "ti");
      word = word.replace(/ディ/g, "di");

      word = word.replace(/シ/g, "shi");
      word = word.replace(/チ/g, "chi");
      word = word.replace(/ツ/g, "tsu");
      word = word.replace(/ヅ/g, "dzu");
      word = word.replace(/ヂ/g, "dji");

      word = word.replace(/バ/g, "ba");
      word = word.replace(/ダ/g, "da");
      word = word.replace(/ガ/g, "ga");
      word = word.replace(/ハ/g, "ha");
      word = word.replace(/カ/g, "ka");
      word = word.replace(/マ/g, "ma");
      word = word.replace(/ナ/g, "na");
      word = word.replace(/パ/g, "pa");
      word = word.replace(/ラ/g, "ra");
      word = word.replace(/サ/g, "sa");
      word = word.replace(/タ/g, "ta");
      word = word.replace(/ワ/g, "wa");
      word = word.replace(/ヤ/g, "ya");
      word = word.replace(/ザ/g, "za");
      word = word.replace(/ア/g, "a");
      word = word.replace(/ベ/g, "be");
      word = word.replace(/デ/g, "de");
      word = word.replace(/ゲ/g, "ge");
      word = word.replace(/ヘ/g, "he");
      word = word.replace(/ケ/g, "ke");
      word = word.replace(/メ/g, "me");
      word = word.replace(/ネ/g, "ne");
      word = word.replace(/ペ/g, "pe");
      word = word.replace(/レ/g, "re");
      word = word.replace(/セ/g, "se");
      word = word.replace(/テ/g, "te");
      word = word.replace(/ヱ/g, "we");
      word = word.replace(/ゼ/g, "ze");
      word = word.replace(/エ/g, "e");
      word = word.replace(/ビ/g, "bi");
      word = word.replace(/ギ/g, "gi");
      word = word.replace(/ヒ/g, "hi");
      word = word.replace(/キ/g, "ki");
      word = word.replace(/ミ/g, "mi");
      word = word.replace(/ニ/g, "ni");
      word = word.replace(/ピ/g, "pi");
      word = word.replace(/リ/g, "ri");
      word = word.replace(/ヰ/g, "wi");
      word = word.replace(/ジ/g, "ji");
      word = word.replace(/イ/g, "i");
      word = word.replace(/ボ/g, "bo");
      word = word.replace(/ド/g, "do");
      word = word.replace(/ゴ/g, "go");
      word = word.replace(/ホ/g, "ho");
      word = word.replace(/コ/g, "ko");
      word = word.replace(/モ/g, "mo");
      word = word.replace(/ノ/g, "no");
      word = word.replace(/ポ/g, "po");
      word = word.replace(/ロ/g, "ro");
      word = word.replace(/ソ/g, "so");
      word = word.replace(/ト/g, "to");
      word = word.replace(/ヲ/g, "wo");
      word = word.replace(/ヨ/g, "yo");
      word = word.replace(/ゾ/g, "zo");
      word = word.replace(/オ/g, "o");
      word = word.replace(/ブ/g, "bu");
      word = word.replace(/グ/g, "gu");
      word = word.replace(/フ/g, "fu");
      word = word.replace(/ク/g, "ku");
      word = word.replace(/ム/g, "mu");
      word = word.replace(/ヌ/g, "nu");
      word = word.replace(/プ/g, "pu");
      word = word.replace(/ル/g, "ru");
      word = word.replace(/ス/g, "su");
      word = word.replace(/ユ/g, "yu");
      word = word.replace(/ズ/g, "zu");
      word = word.replace(/ウ/g, "u");
      word = word.replace(/oo/g, "ō");
      word = word.replace(/ou/g, "ō");
      word = word.replace(/uu/g, "ū");
      word = word.replace(/ッk/g, "kk");
      word = word.replace(/ッs/g, "ss");
      word = word.replace(/ッt/g, "tt");
      word = word.replace(/ッn/g, "nn");
      word = word.replace(/ッm/g, "mm");
      word = word.replace(/ッr/g, "rr");
      word = word.replace(/ッg/g, "gg");
      word = word.replace(/ッd/g, "dd");
      word = word.replace(/ッb/g, "bb");
      word = word.replace(/ッp/g, "pp");
      word = word.replace(/ッf/g, "ff");
      word = word.replace(/ッj/g, "jj");
      word = word.replace(/ッ/g, "\!");

      word = word.replace(/っk/g, "kk");
      word = word.replace(/っs/g, "ss");
      word = word.replace(/っt/g, "tt");
      word = word.replace(/っn/g, "nn");
      word = word.replace(/っm/g, "mm");
      word = word.replace(/っr/g, "rr");
      word = word.replace(/っg/g, "gg");
      word = word.replace(/っd/g, "dd");
      word = word.replace(/っb/g, "bb");
      word = word.replace(/っp/g, "pp");
      word = word.replace(/っf/g, "ff");
      word = word.replace(/っj/g, "jj");

      word = word.replace(/oー/g, "ō");
      word = word.replace(/uー/g, "ū");
      word = word.replace(/aー/g, "ā");
      word = word.replace(/iー/g, "ī");
      word = word.replace(/eー/g, "ē");

    }

    while((word.indexOf('aa')!=-1)||(word.indexOf('ii')!=-1)||(word.indexOf('uu')!=-1)||(word.indexOf('oo')!=-1)||
    (word.indexOf('ou')!=-1)){

      word = word.replace('aa','ā');
      word = word.replace('ii','ī');
      word = word.replace('uu','ū');
      word = word.replace('oo','ō');
      word = word.replace('ou','ō');

    }

    return word;
}

function getMultiGroup(user_id,groupList){
  var settings = {
    "async": true,
    "crossDomain": true,
    "url": "/JapanReview/Service/Action.php",
    "method": "POST",
    "headers": {
      "Content-Type": "application/json"
    },
    "processData": false,
    "data": "{\"action\":\"getMultiGroup\",\"user_id\":\""+user_id+"\",\"group_id\":["+groupList+"]}"
  }
  
  $.ajax(settings).done(function (response) {
    wordList = response;
    console.log(wordList[0]);
    wordList = createRandomWord(wordList);
  });
}

function signup(){
  var settings = {
    "async": true,
    "crossDomain": true,
    "url": "/JapanReview/Service/Action.php",
    "method": "POST",
    "headers": {
      "Content-Type": "application/json"
    },
    "processData": false,
    "data": "{\"action\":\"membership-signup\",\"username\":\""+$('input[name=username]').val()+"\",\"password\":\""+$('input[name=password]').val()+"\",\"confirm_password\":\""+$('input[name=confirm_password]').val()+"\",\"email\":\""+$('input[name=email]').val()+"\"}"
  }
  
  $.ajax(settings).done(function (response) {
    //console.log(response);
    window.location.href="./page-home.php";
  });
}

function check_email_exist_func(obj){
  var settings = {
    "async": true,
    "crossDomain": true,
    "url": "/JapanReview/Service/Action.php",
    "method": "POST",
    "headers": {
      "Content-Type": "application/json"
    },
    "processData": false,
    "data": "{\"action\":\"check_email_exist\",\"email\":\""+$(obj).val()+"\"}"
  }
  
  $.ajax(settings).done(function (response) {
    //console.log(response);
    check_email_exist = response['msg'];
    if(response['msg']){
      $($(obj).parent().parent()).children('span').removeClass('hide');
    }else{
      $($(obj).parent().parent()).children('span').addClass('hide');
    }

  });
}

function check_username_exist_func(obj){
  var settings = {
    "async": true,
    "crossDomain": true,
    "url": "/JapanReview/Service/Action.php",
    "method": "POST",
    "headers": {
      "Content-Type": "application/json",
    },
    "processData": false,
    "data": "{\"action\":\"check_username_exist\",\"username\":\""+$('input[name=username]').val()+"\"}"
  }
  
  $.ajax(settings).done(function (response) {
    //console.log(response);
    check_username_exist = response['msg'];

    $(obj).parent().parent().children('span').removeClass('hide');
    if(!check_username_exist){
        $(obj).parent().parent().children('span').removeClass('text-danger');
        $(obj).parent().parent().children('span').addClass('text-dark');
        $(obj).parent().parent().children('span').text("Username length mustn't over 12 character");
    }else{
        $(obj).parent().parent().children('span').removeClass('text-dark');
        $(obj).parent().parent().children('span').addClass('text-danger');
        $(obj).parent().parent().children('span').text("Your username had already exist!");
    }

  });
}

function validateEmail(email) {
  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}

function signin(){
  var settings = {
    "async": true,
    "crossDomain": true,
    "url": "/JapanReview/Service/Action.php",
    "method": "POST",
    "headers": {
      "Content-Type": "application/json"
    },
    "processData": false,
    "data": "{\"action\":\"sign_in\",\"username\":\""+$('input[name=username_sign_in]').val()+"\",\"password\":\""+$('input[name=password_sign_in]').val()+"\"}"
  }
  
  $.ajax(settings).done(function (response) {
    //console.log(response);
    window.location.href="./page-home.php";
  });
}

function addscore(all_word,correct_word){
  var settings = {
    "async": true,
    "crossDomain": true,
    "url": "/JapanReview/Service/Action.php",
    "method": "POST",
    "headers": {
      "Content-Type": "application/json",
    },
    "processData": false,
    "data": "{    \"action\": \"get_point\",    \"all_word\":"+all_word+",    \"correct_word\":"+correct_word+"}"
  }
  
  $.ajax(settings).done(function (response) {
    console.log(response);
  });
}