<?php
            $user = "root";
            $pass = "";
            $host = "localhost";
            $dbname = "School";
            try {
                $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                //Add teacher
                // Prepare the SQL statement for insertion
                if($_POST['addteacher']==='t'){
                    $sql = "INSERT INTO Teacher (teachername, teacherspecialty, teachergender) VALUES (:teachername, :teacherspecialty, :teachergender)";
                    $stmt = $conn->prepare($sql);
                    $teachername = $_POST['teachername'];
                    $teacherspecialty = $_POST['teacherspecialty'];
                    $teachergender = $_POST['teachergender'];

                    
                    // Bind the parameter values
                    $stmt->bindParam(':teachername', $teachername);
                    $stmt->bindParam(':teacherspecialty', $teacherspecialty);
                    $stmt->bindParam(':teachergender', $teachergender);
                    
                    // Execute the statement
                    $stmt->execute();
                    
                    echo "teacher created successfully.";
                    }
                    
                    //edit teacher
                    if (isset($_POST['editteacher'])) {
                        // Handle form submission
                        $teacher_id = $_POST['editteacher'];
                        $teachername = $_POST['editteachername'];
                        $teacherspecialty = $_POST['editteacherspecialty'];
                        $teachergender = $_POST['editteachergender'];
                        var_dump($teacher_id);
                        // Prepare the SQL statement for update
                        $sql = "UPDATE Teacher SET teachername = :teachername, teacherspecialty = :teacherspecialty, teachergender = :teachergender WHERE teacher_id = :teacher_id";
                        $stmt = $conn->prepare($sql);

                        // Bind the parameter values
            
                        $params = [':teachername'=> $teachername, ':teacherspecialty'=>$teacherspecialty,':teachergender'=>$teachergender,':teacher_id'=>$teacher_id];

                        // Execute the statement
                        $stmt->execute($params);

                        echo "teacher updated successfully.";
                    }
                    
                    //delete teacher
                    // Prepare and execute the delete statement
                    if (isset($_POST['deleteteacher'])){
                        $stmt = $conn->prepare("DELETE FROM Teacher WHERE teacher_id = :teacher_id");
                        $stmt->bindParam(':teacher_id', $_POST['deleteteacher']);
                        $stmt->execute();
                    // Check if any rows were affected
                    if ($stmt->rowCount() > 0) {
                        echo "teacher deleted successfully.";
                    } else {
                        echo "teacher not found.";
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
                <div class="teacher">
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
                    <p><a href="index.php">teacher</a></p>
                    <p><a href="teacher.php">Teacher</a></p>
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
    
                
                //read teacher
                // Fetch teachers data
                $sql = "SELECT * FROM Teacher";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($teachers) > 0) {
                    echo "<table>";
                    echo "<tr><th>name</th><th>id</th><th>specialty</th><th>gender</th><th>edit</th></tr>";

                foreach ($teachers as $teacher) {
                    echo "<tr>";
                    echo "<td class='profile_name'><img src='https://i.postimg.cc/BvPJ7FHN/img1.jpg' alt='img'>".$teacher['teachername']."</td>";
                    echo "<td>".$teacher['teacher_id']."</td>";
                    echo "<td>".$teacher['teacherspecialty']."</td>";
                    echo "<td>".$teacher['teachergender']."</td>";
                    echo "<td><!-- Button trigger modal -->
                    <button type='submit' class='btn btn-primary' data-toggle='modal' data-target='#modaledit".$teacher['teacher_id']."'>
                    Edit
                    </button> | 
                        <!-- Button trigger modal -->
                    <button type='submit' class='btn btn-primary' data-toggle='modal' data-target='#modaldelete".$teacher['teacher_id']."'>
                    Delete
                    </button>  
                    <!-- Modal -->
                      <div class='modal fade' id='modaledit".$teacher['teacher_id']."' tabindex='-1' role='dialog' aria-labelledby='ModalEdit".$teacher['teacher_id']."' aria-hteacher_idden='true'>
                        <div class='modal-dialog' role='document'>
                          <div class='modal-content'>
                            <div class='modal-header'>
                              <h5 class='modal-title' id='ModalEdit".$teacher['teacher_id']."'>Edit</h5>
                              <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                              </button>
                              
                            </div>
                            <div class='modal-body'>
                            <form method='post' action='".$_SERVER['PHP_SELF']."'>
                            <input type='hidden' name='editteacher' value='".$teacher['teacher_id']."'>
                            <div class='form-group'>
                              <label for='editteachername".$teacher['teacher_id']."'>name</label>
                              <input type='text' class='form-control' name='editteachername' id='editteachername".$teacher['teacher_id']."' placeholder='teachername' value='".$teacher['teachername']."'>
                            </div>
                            <div class='form-group'>
                              <label for='editteacherspecialty".$teacher['teacher_id']."'>teacherspecialty</label>
                              <input type='text' class='form-control' name='editteacherspecialty' id='editteacherspecialty".$teacher['teacher_id']."' placeholder='teacherspecialty' value='".$teacher['teacherspecialty']."'>
                            </div>
                            <fieldset class='form-group'>
                                <div class='row'>
                                <legend class='col-form-label col-sm-2 pt-0'>teachergender</legend>
                                <div class='col-sm-10'>
                                    <div class='form-check'>
                                    <input class='form-check-input' type='radio' name='editteachergender' id='editteachergendermale".$teacher['teacher_id']."' value='Male' ".($teacher['teachergender']==='Male'?'checked':'').">
                                    <label class='form-check-label' for='editteachergendermale".$teacher['teacher_id']."'>
                                        Male
                                    </label>
                                    </div>
                                    <div class='form-check'>
                                    <input class='form-check-input' type='radio' name='editteachergender' id='editteachergenderfemale".$teacher['teacher_id']."' value='Female' ".($teacher['teachergender']==='Female'?'checked':'').">
                                    <label class='form-check-label' for='editteachergenderfemale".$teacher['teacher_id']."'>
                                        Female
                                    </label>
                                    </div>
                                    <div class='form-check disabled'>
                                    <input class='form-check-input' type='radio' name='editteachergender' id='editteachergenderbinary".$teacher['teacher_id']."' value='Binary' ".($teacher['teachergender']==='Binary'?'checked':'').">
                                    <label class='form-check-label' for='editteachergenderbinary".$teacher['teacher_id']."'>
                                        Binary
                                    </label>
                                    </div>
                                </div>
                                </div>
                            </fieldset>
                            <div class='form-group'>
                                <label for='editimage'>Profile image</label>
                                <input type='file' class='form-control-file' id='editimage".$teacher['teacher_id']."' name='editimage'>
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
                      <div class='modal fade' id='modaldelete".$teacher['teacher_id']."' tabindex='-1' role='dialog' aria-labelledby='ModalDelete".$teacher['teacher_id']."' aria-hteacher_idden='true'>
                        <div class='modal-dialog' role='document'>
                          <div class='modal-content'>
                            <div class='modal-header'>
                              <h5 class='modal-title' id='ModalDelete".$teacher['teacher_id']."'>Modal title</h5>
                              <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                              </button>
                            </div>
                            <div class='modal-body'>
                            <h6>Are you sure you want to delete this teacher?</h6>
                            <form action='".$_SERVER['PHP_SELF']."' method='POST'>
                                <input type='hidden' name='deleteteacher' value='".$teacher['teacher_id']."' >
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
                    echo "No teachers found.";
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
  Add teacher
</button>

<!-- Modal -->
<div class="modal fade" id="modaladd" tabindex="-1" role="dialog" aria-labelledby="Modaladd" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="Modaladd">Add teacher </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
      <form method='post' action='<?php echo $_SERVER['PHP_SELF'];?>'>
    <input type='hidden' name='addteacher' value='t'>
    <div class='form-group'>
      <label for='teachername'>name</label>
      <input type='text' class='form-control' name='teachername' id='teachername' placeholder='teachername'>
    </div>
    <div class='form-group'>
      <label for='teacherspecialty'>teacherspecialty</label>
      <input type='text' class='form-control' name='teacherspecialty' id='teacherspecialty' placeholder='teacherspecialty'>
    </div>
    <fieldset class='form-group'>
        <div class='row'>
        <legend class='col-form-label col-sm-2 pt-0'>teachergender</legend>
        <div class='col-sm-10'>
            <div class='form-check'>
            <input class='form-check-input' type='radio' name='teachergender' id='teachergendermale' value='Male'>
            <label class='form-check-label' for='teachergendermale'>
                Male
            </label>
            </div>
            <div class='form-check'>
            <input class='form-check-input' type='radio' name='teachergender' id='teachergenderfemale"' value='Female'>
            <label class='form-check-label' for='teachergenderfemale'>
                Female
            </label>
            </div>
            <div class='form-check disabled'>
            <input class='form-check-input' type='radio' name='teachergender' id='teachergenderbinary' value='Binary'>
            <label class='form-check-label' for='teachergenderbinary'>
                Binary
            </label>
            </div>
        </div>
        </div>
    </fieldset>
    <div class='form-group'>
        <label for='image'>Profile image</label>
        <input type='file' class='form-control-file' id='teacherimage' name='teacherimage'>
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
                    <img src="https://i.postimg.cc/g2M32zcz/image.png " alt="teacherImg ">
                </div>
                <div class="name_and_class ">
                    <p>Hermione Granger</p>
                    <span>BCA teacher</span>
                </div>
                <div class="contact_info ">
                    <i class='bx bx-message-rounded-dots'></i>
                    <i class='bx bx-phone-call'></i>
                    <i class='bx bx-envelope'></i>
                </div>
                <div class="about ">
                    <h4>About</h4>
                    <p>BCA teacher studied at ABC School of Commerce and Computer studies. I really enjoy solving problems as well as making things pretty and easy to use. I can't stop learning new things; the more, the better.</p>
                </div>
                <div class="other_info ">
                    <div class="teacherspecialty ">
                        <h4>teacherspecialty</h4>
                        <p>18</p>
                    </div>
                    <div class="teachergender ">
                        <h4>teachergender</h4>
                        <p>Female</p>
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
                <div class="teacher_from_same_class ">
                    <div class="teacher_same_class_heading ">
                        <h4>teacher from the same class</h4>
                    </div>
                    <div class="teacher_same_class_img ">
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