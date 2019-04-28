<div>
                <form method="post" action="./Service/Action.php">
                    <table>
                        <thead>
                            <tr>
                                <h1>Adding Japanese Word</h1>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>     
                                    Kanji :
                                </td>
                                <td>
                                    <input name="kanji" type="text" placeholder="漢字" required/>
                                </td>
                            </tr>
                            <tr>
                                <td>Hiragana / Katakana :</td>
                                <td>
                                    <input name="hiragana" type="text" placeholder="ひらがな" required/>
                                </td>
                            </tr>
                            <tr>
                                <td>Romanji :</td>
                                <td>
                                    <input name="romanji" type="text" placeholder="Romanji" required/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Meaning :
                                </td>
                                <td>
                                    <input name="meaning" type="textarea" placeholder="meaning" required/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input name="action" type="text" value="AddingWord" style="display:none;" />
                                    <input type="submit" value="submit"/>
                                </td>
                            </tr>
                        </tbody>
                        <?php if(isset($_SESSION['add_result'])){ 
                                if($_SESSION['add_result']=="complete"){ ?>
                                    <p class="text-success">
                                        <?php echo $_SESSION['message']; ?>
                                    </p>                            
                            <?php    
                                }else{ 
                            ?>
                                <p class="text-danger">
                                    <?php echo $_SESSION['message']; ?>
                                </p>
                            <?php
                                }
                             } ?>
                    </table>
                    
                </form>
            </div>