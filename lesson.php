<?php
            $user = "root";
            $pass = "";
            $host = "localhost";
            $dbname = "School";
            try {
                $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                //Add lesson
                // Prepare the SQL statement for insertion
                if($_POST['addlesson']==='t'){
                    $sql = "INSERT INTO Lesson (lessonname, lessonprerequisite, lessontype) VALUES (:lessonname, :lessonprerequisite, :lessontype)";
                    $stmt = $conn->prepare($sql);
                    $lessonname = $_POST['lessonname'];
                    $lessonprerequisite = $_POST['lessonprerequisite'];
                    $lessontype = $_POST['lessontype'];

                    
                    // Bind the parameter values
                    $stmt->bindParam(':lessonname', $lessonname);
                    $stmt->bindParam(':lessonprerequisite', $lessonprerequisite);
                    $stmt->bindParam(':lessontype', $lessontype);
                    
                    // Execute the statement
                    $stmt->execute();
                    
                    echo "lesson created successfully.";
                    }
                    
                    //edit lesson
                    if (isset($_POST['editlesson'])) {
                        // Handle form submission
                        $lesson_id = $_POST['editlesson'];
                        $lessonname = $_POST['editlessonname'];
                        $lessonprerequisite = $_POST['editlessonprerequisite'];
                        $lessontype = $_POST['editlessontype'];
                        var_dump($lesson_id);
                        // Prepare the SQL statement for update
                        $sql = "UPDATE Lesson SET lessonname = :lessonname, lessonprerequisite = :lessonprerequisite, lessontype = :lessontype WHERE lesson_id = :lesson_id";
                        $stmt = $conn->prepare($sql);

                        // Bind the parameter values
            
                        $params = [':lessonname'=> $lessonname, ':lessonprerequisite'=>$lessonprerequisite,':lessontype'=>$lessontype,':lesson_id'=>$lesson_id];

                        // Execute the statement
                        $stmt->execute($params);

                        echo "lesson updated successfully.";
                    }
                    
                    //delete lesson
                    // Prepare and execute the delete statement
                    if (isset($_POST['deletelesson'])){
                        $stmt = $conn->prepare("DELETE FROM Lesson WHERE lesson_id = :lesson_id");
                        $stmt->bindParam(':lesson_id', $_POST['deletelesson']);
                        $stmt->execute();
                    // Check if any rows were affected
                    if ($stmt->rowCount() > 0) {
                        echo "lesson deleted successfully.";
                    } else {
                        echo "lesson not found.";
                    }
                }
            } catch(PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
            $conn = null;
                
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="main.css">
    <title>School</title>
</head>
<body>
<div class="container ">
<?php
        include 'sidebar.php';
        ?>
        <div class="main_content">
            <div class="left_right_sidebar_opener">
                <div class="hamburger">
                    <i class='bx bx-menu'></i>
                </div>
                <div class="lesson">
                    <div class="profile_img">
                        <img src="https://i.postimg.cc/Sxb6gssQ/img-1.jpg" alt="profile img">
                    </div>
                    <div class="profile_name">
                        <p>Kery Roy</p>
                    </div>
                </div>
            </div>
            <div class="main_navbar">
                <div class="search_box">
                    <i class='bx bx-search-alt-2'></i> <input type="text " placeholder="Search">
                </div>
                <div class="dark_mode_icon" onclick="darkMode()">
                    <i class='bx bx-moon'></i>
                    <i class='bx bx-sun'></i>
                </div>
            </div>
            <div class="menu_item_name_and_filter ">
                <div class="menu_item_name">
                    <h2>Database</h2>
                </div>
                <div class="filter_and_sort">
                    <div class="sort sort_and_filter">
                        <p>Sort</p>
                        <i class='bx bx-sort-down'></i>
                    </div>
                    <div class="filter sort_and_filter">
                        <p>Filter</p>
                        <i class='bx bx-filter'></i>
                    </div>
                </div>
            </div>
            <div class="tabs">
                <!-- <div class="tab_name">
                    <p><a href="index.php">lesson</a></p>
                    <p><a href="lesson.php">lesson</a></p>
                    <p><a href="lesson.php">Lesson</a></p>
                </div> -->
                <div class="three_dots">
                    <i class='bx bx-dots-vertical-rounded'></i>
                </div>
            </div>
            <div class="table">
            <?php
            $user = "root";
            $pass = "";
            $host = "localhost";
            $dbname = "School";
            try {
                $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                
                //read lesson
                // Fetch lessons data
                $sql = "SELECT * FROM Lesson";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($lessons) > 0) {
                    echo "<table>";
                    echo "<tr><th>name</th><th>id</th><th>prerequisite</th><th>type</th><th>edit</th></tr>";

                foreach ($lessons as $lesson) {
                    echo "<tr>";
                    echo "<td class='profile_name'><img src='https://i.postimg.cc/BvPJ7FHN/img1.jpg' alt='img'>".$lesson['lessonname']."</td>";
                    echo "<td>".$lesson['lesson_id']."</td>";
                    echo "<td>".$lesson['lessonprerequisite']."</td>";
                    echo "<td>".$lesson['lessontype']."</td>";
                    echo "<td><!-- Button trigger modal -->
                    <button type='submit' class='btn btn-primary' data-toggle='modal' data-target='#modaledit".$lesson['lesson_id']."'>
                    Edit
                    </button> | 
                        <!-- Button trigger modal -->
                    <button type='submit' class='btn btn-primary' data-toggle='modal' data-target='#modaldelete".$lesson['lesson_id']."'>
                    Delete
                    </button>  
                    <!-- Modal -->
                      <div class='modal fade' id='modaledit".$lesson['lesson_id']."' tabindex='-1' role='dialog' aria-labelledby='ModalEdit".$lesson['lesson_id']."' aria-hlesson_idden='true'>
                        <div class='modal-dialog' role='document'>
                          <div class='modal-content'>
                            <div class='modal-header'>
                              <h5 class='modal-title' id='ModalEdit".$lesson['lesson_id']."'>Edit</h5>
                              <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                              </button>
                              
                            </div>
                            <div class='modal-body'>
                            <form method='post' action='".$_SERVER['PHP_SELF']."'>
                            <input type='hidden' name='editlesson' value='".$lesson['lesson_id']."'>
                            <div class='form-group'>
                              <label for='editlessonname".$lesson['lesson_id']."'>name</label>
                              <input type='text' class='form-control' name='editlessonname' id='editlessonname".$lesson['lesson_id']."' placeholder='lessonname' value='".$lesson['lessonname']."'>
                            </div>
                            <div class='form-group'>
                              <label for='editlessonprerequisite".$lesson['lesson_id']."'>lessonprerequisite</label>
                              <input type='text' class='form-control' name='editlessonprerequisite' id='editlessonprerequisite".$lesson['lesson_id']."' placeholder='lessonprerequisite' value='".$lesson['lessonprerequisite']."'>
                            </div>
                            <fieldset class='form-group'>
                                <div class='row'>
                                <legend class='col-form-label col-sm-2 pt-0'>lessontype</legend>
                                <div class='col-sm-10'>
                                    <div class='form-check'>
                                    <input class='form-check-input' type='radio' name='editlessontype' id='editlessontype2unit".$lesson['lesson_id']."' value='2unit' ".($lesson['lessontype']==='2unit'?'checked':'').">
                                    <label class='form-check-label' for='editlessontype2unit".$lesson['lesson_id']."'>
                                        2 unit
                                    </label>
                                    </div>
                                    <div class='form-check'>
                                    <input class='form-check-input' type='radio' name='editlessontype' id='editlessontype3unit".$lesson['lesson_id']."' value='3unit' ".($lesson['lessontype']==='3unit'?'checked':'').">
                                    <label class='form-check-label' for='editlessontype3unit".$lesson['lesson_id']."'>
                                        3 unit
                                    </label>
                                    </div>
                                    <div class='form-check disabled'>
                                    <input class='form-check-input' type='radio' name='editlessontype' id='editlessontype4unit".$lesson['lesson_id']."' value='4unit' ".($lesson['lessontype']==='4unit'?'checked':'').">
                                    <label class='form-check-label' for='editlessontype4unit".$lesson['lesson_id']."'>
                                        4 unit
                                    </label>
                                    </div>
                                </div>
                                </div>
                            </fieldset>
                            <div class='form-group'>
                                <label for='editimage'>Profile image</label>
                                <input type='file' class='form-control-file' id='editimage".$lesson['lesson_id']."' name='editimage'>
                            </div>
                            <div class='form-group row'>
                                <div class='col-sm-10'>
                                <button type='submit' class='btn btn-primary'>Submit</button>
                                </div>
                            </div>
                          </form>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- Modal -->
                      <div class='modal fade' id='modaldelete".$lesson['lesson_id']."' tabindex='-1' role='dialog' aria-labelledby='ModalDelete".$lesson['lesson_id']."' aria-hlesson_idden='true'>
                        <div class='modal-dialog' role='document'>
                          <div class='modal-content'>
                            <div class='modal-header'>
                              <h5 class='modal-title' id='ModalDelete".$lesson['lesson_id']."'>Modal title</h5>
                              <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                              </button>
                            </div>
                            <div class='modal-body'>
                            <h6>Are you sure you want to delete this lesson?</h6>
                            <form action='".$_SERVER['PHP_SELF']."' method='POST'>
                                <input type='hidden' name='deletelesson' value='".$lesson['lesson_id']."' >
                                <input type='submit' class='btn btn-primary'>
                            </form>
                            </div>
                          </div>
                        </div>
                      </div>
                      </td>";
                    echo "</tr>";
                }
            } else {
                    echo "No lessons found.";
                }
                
                echo "</table>";
            } catch(PDOException $e) {
                echo "Error: " . $e->getmessage();
            }
            $conn = null;
            ?>
            </div>
            <!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modaladd">
  Add lesson
</button>

<!-- Modal -->
<div class="modal fade" id="modaladd" tabindex="-1" role="dialog" aria-labelledby="Modaladd" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="Modaladd">Add lesson </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
      <form method='post' action='<?php echo $_SERVER['PHP_SELF'];?>'>
    <input type='hidden' name='addlesson' value='t'>
    <div class='form-group'>
      <label for='lessonname'>name</label>
      <input type='text' class='form-control' name='lessonname' id='lessonname' placeholder='lessonname'>
    </div>
    <div class='form-group'>
      <label for='lessonprerequisite'>lessonprerequisite</label>
      <input type='text' class='form-control' name='lessonprerequisite' id='lessonprerequisite' placeholder='lessonprerequisite'>
    </div>
    <fieldset class='form-group'>
        <div class='row'>
        <legend class='col-form-label col-sm-2 pt-0'>lessontype</legend>
        <div class='col-sm-10'>
            <div class='form-check'>
            <input class='form-check-input' type='radio' name='lessontype' id='lessontype2unit' value='2unit'>
            <label class='form-check-label' for='lessontype2unit'>
                2 unit
            </label>
            </div>
            <div class='form-check'>
            <input class='form-check-input' type='radio' name='lessontype' id='lessontype3unit"' value='3unit'>
            <label class='form-check-label' for='lessontype3unit'>
                3 unit
            </label>
            </div>
            <div class='form-check disabled'>
            <input class='form-check-input' type='radio' name='lessontype' id='lessontype4unit' value='4unit'>
            <label class='form-check-label' for='lessontype4unit'>
                4 unit
            </label>
            </div>
        </div>
        </div>
    </fieldset>
    <div class='form-group'>
        <label for='image'>Profile image</label>
        <input type='file' class='form-control-file' id='lessonimage' name='lessonimage'>
    </div>
    <div class='form-group row'>
        <div class='col-sm-10'>
        <button type='submit' class='btn btn-primary'>Submit</button>
        </div>
    </div>
  </form>
      </div>
    </div>
  </div>
</div>
        </div>
        <div class="right_sidebar ">
            <div class="notification_and_name ">
                <div class="close_btn ">
                    <i class='bx bx-x-circle'></i>
                </div>
                <div class="bell ">
                    <i class='bx bx-bell'></i>
                    <span></span>
                </div>
                <!-- <img src="https://i.postimg.cc/Sxb6gssQ/img-1.jpg " alt="profile ">
                <p>Kery Roy</p>
                <i class='bx bx-chevron-down'></i> -->
            </div>
            <!-- <div class="profile ">
                <div class="img ">
                    <img src="https://i.postimg.cc/g2M32zcz/image.png " alt="lessonImg ">
                </div>
                <div class="name_and_class ">
                    <p>Hermione Granger</p>
                    <span>BCA lesson</span>
                </div>
                <div class="contact_info ">
                    <i class='bx bx-message-rounded-dots'></i>
                    <i class='bx bx-phone-call'></i>
                    <i class='bx bx-envelope'></i>
                </div>
                <div class="about ">
                    <h4>About</h4>
                    <p>BCA lesson studied at ABC School of Commerce and Computer studies. I really enjoy solving problems as well as making things pretty and easy to use. I can't stop learning new things; the more, the better.</p>
                </div>
                <div class="other_info ">
                    <div class="lessonprerequisite ">
                        <h4>lessonprerequisite</h4>
                        <p>18</p>
                    </div>
                    <div class="lessontype ">
                        <h4>lessontype</h4>
                        <p>3unit</p>
                    </div>
                    <div class="dob ">
                        <h4>DOB</h4>
                        <p>12/11/2006</p>
                    </div>
                    <div class="address ">
                        <h4>Address</h4>
                        <p>USA</p>
                    </div>
                </div>
                <div class="lesson_from_same_class ">
                    <div class="lesson_same_class_heading ">
                        <h4>lesson from the same class</h4>
                    </div>
                    <div class="lesson_same_class_img ">
                        <img src="https://i.postimg.cc/qBbpBPZB/img-2.jpg " alt="img ">
                        <img src="https://i.postimg.cc/BvPJ7FHN/img1.jpg " alt="img ">
                        <img src="https://i.postimg.cc/SRkqKt5t/img2.jpg " alt="img ">
                        <img src="https://i.postimg.cc/xCR77pg2/dahiana-waszaj-XQWfro4LrVs-unsplash.jpg " alt="img ">
                        <img src="https://i.postimg.cc/9MXPK7RT/news2.jpg " alt="img ">
                        <span>+12 More</span>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
    <script src="script.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>