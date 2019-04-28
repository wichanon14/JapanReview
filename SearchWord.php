<div style="margin-top:5em">
        <script>
            function search_result(){
                var keyword = $("input[name=keyword]").val()
                var settings = {
                "async": true,
                "crossDomain": true,
                "url": "/JapanReview/Service/Action.php",
                "method": "POST",
                "headers": {
                    "Content-Type": "application/json"
                },
                "processData": false,
                "data": '{ "action":"SearchWord","keyword":"'+keyword+'"}'
                }

                $.ajax(settings).done(function (response) {
                    //console.log(JSON.parse(response));
                    var search_result_format = searchResultFormat(response);
                    $('#result_search').html(search_result_format);
                });
            }

        </script>
    <h1>Search Japanese Word</h1>
    <form method="post">
        <input type="text" name="keyword" />
        <input type="button" onclick="search_result();" value="search"/>
    </form>
    <h2 class="mt-3">Search Result</h2>
    <div>
        <div id="result2">

        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>KANJI</th>
                    <th>HIRAGANA/KATAKANA</th>
                    <th>ROMANJI</th>
                    <th>MEANING</th>
                    <th>EDIT</th>
                    <th>DELETE</th>
                </tr>
            </thead>
            <tbody id="result_search">

            </tbody>
        </table>
    </div>
</div>