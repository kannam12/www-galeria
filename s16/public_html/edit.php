<section class="edit">
    <div class="container">
        <div class="add-heading">
            <h2>Edytuj obrazek:</h2>
        </div>

<?php    //pobieram wartość zmienenj edit z adresu url - pobieram dane z bazy dla właściwego indeksu
if (isset($_GET['edit'])) {
    $edit = $_GET['edit'];
    unset($_GET['edit']);

    print '
            <table>  
                <form method="POST"class="add-form">
    ';          

    $stmt = $dbh->prepare("SELECT id, dir, title, content, ip, added FROM pics WHERE id='$edit'"); //tu
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        print '
                    <tr>
                        <img src="' . htmlspecialchars($row['dir'], ENT_QUOTES | ENT_HTML401, 'UTF-8') .  '" class="edit-img" >
                    </tr>                        
                    <tr>
                        <td><label>Tytuł: </label></td>
                        <td><input class="add-input" type="text" name="title" value="' . htmlspecialchars($row['title'], ENT_QUOTES | ENT_HTML401, 'UTF-8') . '"></td>
                    </tr>
                    <tr>
                        <td><label>Opis: </label></td>
                        <td><input class="add-input"type="textarea" name="content" value="' . htmlspecialchars($row['content'], ENT_QUOTES | ENT_HTML401, 'UTF-8') . '"></td>
                    </tr>
        ';           
    };
            
    print '            
                    
                    <tr>
                        <td><input type="submit" name="refresh" value="Aktualizuj"></td>
                    </tr>		
                </form>	
            </table>
        </div>
    </section>
    ';
    //po zatwierdzeniu - aktualizuję wpis w bazie
    if (isset($_POST['refresh'])){

        $title = $_POST['title'];
        $content = $_POST['content'];
        
        $stmt = $dbh->prepare("UPDATE pics SET title = '$title', content= '$content' WHERE id = '$edit'");
        $stmt->execute();
        header('Location: https://s16.labwww.pl/edit?edit=' . $edit );  
    }      
} else {
    print '<p class="komunikat-no">Brak argumentu - błąd!</p>';
}

