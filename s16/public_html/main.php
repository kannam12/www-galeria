<section class="gallery-block cards-gallery">
	<div class="container">
		<div class="heading">
            <h2>Some Nice Pics</h2>

            <form action="/main" class="main-choice" method="POST" >
                <select  name="chosen-cat">
                    <option value="wszystkie">Wszytskie obrazy</option>
                    <?php //ładowanie z bazy listy dostępnych kategorii i wyświetlanie w rozwijanej liście
                        $stmt = $dbh->prepare("SELECT category FROM categories");
                        $stmt->execute();

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            
                            print ' <option value="'. htmlspecialchars($row['category'], ENT_QUOTES | ENT_HTML401, 'UTF-8') .  '">' . htmlspecialchars($row['category'], ENT_QUOTES | ENT_HTML401, 'UTF-8') . '</option>

                            ';
                        };
                    ?>
                </select>
                <input type="submit" name="change" value="Wybierz">
            </form>
        </div>

        <div class="row">
            <?php   //po zatiwerdzeniu wybranej kategorii wczytuję i wyświetlam odpowiednie obrazki
                if (isset($_POST['change']) ) {                 

                    $chosen = $_POST['chosen-cat'];

                    if ( $chosen != 'wszystkie' ){                    
                        $stmt = $dbh->prepare("SELECT id, dir, title, content, ip, added FROM pics WHERE category='$chosen'");
                        $stmt->execute();

                    } else {
                        $stmt = $dbh->prepare("SELECT id, dir, title, content, ip, added FROM pics");
                        $stmt->execute();
                    }
                } else {
                    $stmt = $dbh->prepare("SELECT id, dir, title, content, ip, added FROM pics");
                    $stmt->execute();
                }
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                    print '
                            <div class="col-md-6 col-lg-4">
                                <div class="card-crop">
                                    <div class="img-crop">
                                        <a href="https://s16.labwww.pl/edit?edit='. $row['id'] . ' " class="img-a">
                                            <img src="' . htmlspecialchars($row['dir'], ENT_QUOTES | ENT_HTML401, 'UTF-8') .  '" class="img-crop">
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <h6>' . htmlspecialchars($row['title'], ENT_QUOTES | ENT_HTML401, 'UTF-8') . '</h6>
                                        <p class="text-muted card-text">' . htmlspecialchars($row['content'], ENT_QUOTES | ENT_HTML401, 'UTF-8') . '</p>
                                    </div>
                                </div>
                            </div>
                    ';
                };
            ?>
        </div>            
    </div>
</section>