<section class="categories">
	<div class="container">
		<div class="add-heading">
			<h2>Dodaj nową kategorię:</h2>
        </div>
        <!-- tworzę formularz do dodawania nowej kategorii -->
        <table>
            <form action="/categories" method="POST"class="add-form">
                <tr>
                    <td><label>Nazwa nowej kategorii: </label></td>
                    <td><input class="add-input" type="text" name="category" ></td>
                </tr>
                <tr>
                    <td><input type="submit" value="Dodaj" name="dodaj"></td>
                </tr>		
            </form>	
        </table>           
    <?php 	
		
        if (!defined('IN_INDEX')) { 
            exit("Nie można uruchomić tego pliku bezpośrednio."); 
        };

        //dodawanie kategorii - słowo (lub kilka) dłuże niż 1 znak
        
        if ( isset($_POST['dodaj'])){
            if (isset($_POST['category'] ) && mb_strlen($_POST['category']) >= 2 ) {
                $i = 0;
                $category = $_POST['category'];
                unset($_POST['category']);
                //sprawdzam czy taka kategoria przypadkiem już nie istnieje w bazie 
                $stmt = $dbh->prepare("SELECT category, added FROM categories");
                $stmt->execute();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    if ( $row['category'] == $category){
                        $i = 1;
                    }
                };
                //jeśli daana kategoria nie istnieje jeszcze w bazie - dodaję
                if ($i==0) {                
                                
                    $stmt = $dbh->prepare("INSERT INTO categories ( category, added ) VALUES ( :category, NOW() )");
                    $stmt->execute([':category' => $category]);  

                    print '<p class="komunikat-yes">Dane zostały pomyślnie dodane do bazy.</p>';
                } else {
                    print '<p class="komunikat-no">Niestety, podana kategoria już istnieje.</p>';
                }            			
            } else {
                print '<p class="komunikat-no">Podane dane są nieprawidłowe.</p>';
            }	
        }		
    ?>
    </div>
    <!-- sekcja usuwania -->
    <div class="container">
		<div class="add-heading">
			<h2>Usuń istniejącą kategorię:</h2>
        </div>

        <div>
    <?php //ponownie - pobieram zmienną z url i usuwam obrazem o danym id
         if (isset($_GET['delete'])) {
            $delete = $_GET['delete'];
            $delete = str_replace("-", " ", $delete); 
            unset($_GET['delete']);            
            
            try{
            
                $stmt = $dbh->prepare("DELETE FROM categories WHERE category = :category ");
                $stmt->execute([':category' => $delete]);
            } catch (PDOException $e) {
                print "Nie mozna usunąć z bazy danych :(" . $e->getMessage();
                exit();
            }			
            header('Location: https://s16.labwww.pl/categories'); //'odświeżam' stronę
            }

        // wyświetlanie dostępnych kategorii
        $stmt = $dbh->prepare("SELECT category FROM categories");
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {   
            print '
            <table>
                <tr>
                    <td><p>'. htmlspecialchars($row['category'], ENT_QUOTES | ENT_HTML401, 'UTF-8') . '</p></td>';
                    $tmpcategory = str_replace(" ", "-", $row['category']); 
                    print ' <td><a href="categories/delete/'. $tmpcategory .'"><button>Usuń</button></a></td>
                </tr>
            </table>
            ';
        };
    ?>
    
        </div>     
    </div>
</section>  