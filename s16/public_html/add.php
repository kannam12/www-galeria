<section class="add-pic">
	<div>
		<div class="add-heading">
			<h2>Dodaj zdjęcie:</h2>
        </div>
        <!-- form na dodawanie obrazków -->
        <table>
            <form action="/add" method="POST"class="add-form" enctype="multipart/form-data">
                <tr>
                    <p>Drag & drop: </p>
                    <input class="add-dropzone" type="file" name="files[]" id="file" >                  
                </tr>                
                <tr>
                    <td><label>Tytuł: </label></td>
                    <td><input class="add-input" type="text" name="title" ></td>
                </tr>
                <tr>
                    <td><label>Opis: </label></td>
                    <td><input class="add-input"type="textarea" name="content" ></td>
                </tr>
                <tr>
                    <td><label>Kategoria: </label></td> 
                    <td>
                        <select class="add-input" name="category">
                            <?php
                                $stmt = $dbh->prepare("SELECT category FROM categories");
                                $stmt->execute();

                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    
                                    print ' <option value="'. htmlspecialchars($row['category'], ENT_QUOTES | ENT_HTML401, 'UTF-8') .  '">' . htmlspecialchars($row['category'], ENT_QUOTES | ENT_HTML401, 'UTF-8') . '</option>
                
                                    ';
                                };
                            ?>
                        </select> 
                    </td>                              
                </tr>
                <tr>
                    <td><input type="submit" name="upload" value="Dodaj"></td>
                </tr>		
            </form>	
        </table>

    <?php	//proces dodawania obrazka do bazy i na serwer
		if (!defined('IN_INDEX')) { 
            exit("Nie można uruchomić tego pliku bezpośrednio."); 
        };

        if (isset($_POST['upload'])){        

            if (isset($_POST['title']) && isset($_POST['content']) && ($_POST['title'] != '') && ($_POST['content'] != '') ) {

                $title = $_POST['title'];
                $content = $_POST['content']; 
                $category = $_POST['category'];           
                $ip = $_SERVER['REMOTE_ADDR'];            
                
                $dir = "uploads/".basename($_FILES['files']['name'][0]);
                //dozwolone formaty plików
                $imageFileType = strtolower(pathinfo($dir,PATHINFO_EXTENSION));
                $extensions_arr = array("jpg","jpeg","png","gif");

                $uploadOk = 1;

                $check = getimagesize($_FILES["files"]["tmp_name"][0]);
                    if($check !== false) {
                    echo "File is an image - " . $check["mime"] . ".";
                    $uploadOk = 1;
                    } else {
                    echo "File is not an image.";
                    $uploadOk = 0;
                    }
                //sprawdzam czy obrazek przypadkiem już nie istnieje na serwerze
                if (file_exists($dir)) {
                    echo "Sorry, file already exists.";
                    $uploadOk = 0;
                } 
                //dozwolone formaty plików
                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"  && $imageFileType != "gif" ) {
                    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    $uploadOk = 0;
                }

                //jeśli wszystkie powyższe warunki spełnione - uploaduj
                if ($uploadOk == 0) {
                    echo "Sorry, your file was not uploaded.";
                    } else {
                        if (move_uploaded_file($_FILES["files"]["tmp_name"][0], $dir)) {
                            print '<p class="komunikat-yes">The file '. basename( $_FILES["files"]["name"][0]). ' has been uploaded.</p>';

                            $stmt = $dbh->prepare("INSERT INTO pics ( dir, title, content, category, ip, added) VALUES ( :dir, :title, :content, :category, :ip, NOW())");
                            $stmt->execute([':dir' => $dir, ':title' => $title, ':content' => $content, ':category' => $category, ':ip' => $ip]);
                        
                            print '<br> <p class="komunikat-yes">Dane zostały dodane do bazy.</p>';
                        } else {
                            echo "Sorry, there was an error uploading your file.";
                        }
                }                		
            } else {
                print '<p class="komunikat-no">Podane dane są nieprawidłowe.</p>';
            }
        }	
		
    ?>
    </div>
</section>  
        
