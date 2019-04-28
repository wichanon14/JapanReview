function searchResultFormat(data){

    var resultElement = "";
    
    for(var i=0;i<data.length;i++){
        resultElement += "<tr class=\"result-"+data[i].ID+"\">"+
            "<td>"+data[i].KANJI+"</td>"+
            "<td>"+data[i].HIRAGANA+"</td>"+
            "<td>"+data[i].ROMANJI+"</td>"+
            "<td>"+data[i].MEANING+"</td>"+
            "<td onclick=\"editWord('result-"+data[i].ID+"')\"><i class=\"fa fa-pencil clickable\"></i></td>"+
            "<td onclick=\"deleteWord("+data[i].ID+")\"><i class=\"text-danger fa fa-eraser clickable\"></i></td>"+
        "</tr>"+
        "<tr class=\"edit-result-"+data[i].ID+" hide\">"+
            "<td><input name='kanji' type='text' value='"+data[i].KANJI+"'/></td>"+
            "<td><input name='hiragana'type='text' value='"+data[i].HIRAGANA+"'</td>"+
            "<td><input name='romanji'type='text' value='"+data[i].ROMANJI+"'</td>"+
            "<td><input name='meaning' type='text' value='"+data[i].MEANING+"'</td>"+
            "<td onclick=\"saveEditWord("+data[i].ID+")\"><i class=\"fa fa-save clickable\"></i></td>"+
        "</tr>";
    }
    
    return resultElement;

}

function addGroupFormat(data){
    var resultElement = "";

    for(var i=0;i<data.length;i++){
        var template1 = '<li class="insert list-group-item d-flex justify-content-between align-items-center">'.concat(
            data[i].word
            ,'<span class="badge badge-pill"><i data-id="',data[i].data_id,'" class="text-danger font-large fa fa-trash clickable" onclick="deleteItem(words,',
            data[i].data_id,',\'words\')"></i></span></li>'
        );
        resultElement += template1;
    }

    return resultElement;
}

function dataListFormat(id,data){
    var resultElement="";
    var dataList = '<datalist id="'+id+'">';
    resultElement += dataList;
    for(var i=0;i<data.length;i++){
        var template1 = '<option data-id="'.concat(
            data[i].ID,'" value="',data[i].KANJI,' (',data[i].HIRAGANA,',',data[i].ROMANJI,')"><span></span></option>'
        );
        resultElement += template1;
    }
    resultElement += '</datalist>';
    
    return resultElement;
}   

function groupListFormat(data){
    var resultElement="";
    for(var i=0;i<data.length;i++){
        var template1 = '<a  class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" onclick="onSelectGroup(this)" data-id="'.concat(
            data[i].ID,'" ><span>',data[i].group_name,'</span><span class="badge badge-pill"><i data-id="',data[i].ID,'" class="text-danger font-large fa fa-trash clickable" onclick="deleteGroup(this)"></i></span></a>'
        );
        resultElement += template1;
    }
    
    return resultElement;
    
}