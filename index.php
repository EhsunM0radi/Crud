<?php
            $user = "root";
            $pass = "";
            $host = "localhost";
            $dbname = "School";
            try {
                $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                //Add stu
                // Prepare the SQL statement for insertion
                if($_POST['addstu']==='t'){
                    $sql = "INSERT INTO Student (stuname, stuage, stugender) VALUES (:stuname, :stuage, :stugender)";
                    $stmt = $conn->prepare($sql);
                    $stuname = $_POST['stuname'];
                    $stuage = $_POST['stuage'];
                    $stugender = $_POST['stugender'];

                    
                    // Bind the parameter values
                    $stmt->bindParam(':stuname', $stuname);
                    $stmt->bindParam(':stuage', $stuage);
                    $stmt->bindParam(':stugender', $stugender);
                    
                    // Execute the statement
                    $stmt->execute();
                    
                    echo "Student created successfully.";
                    }
                    
                    //edit stu
                    if (isset($_POST['editstu'])) {
                        // Handle form submission
                        $stu_id = $_POST['editstu'];
                        $stuname = $_POST['editstuname'];
                        $stuage = $_POST['editstuage'];
                        $stugender = $_POST['editstugender'];
                        var_dump($stu_id);
                        // Prepare the SQL statement for update
                        $sql = "UPDATE Student SET stuname = :stuname, stuage = :stuage, stugender = :stugender WHERE stu_id = :stu_id";
                        $stmt = $conn->prepare($sql);

                        // Bind the parameter values
            
                        $params = [':stuname'=> $stuname, ':stuage'=>$stuage,':stugender'=>$stugender,':stu_id'=>$stu_id];

                        // Execute the statement
                        $stmt->execute($params);

                        echo "Student updated successfully.";
                    }
                    
                    //delete stu
                    // Prepare and execute the delete statement
                    if (isset($_POST['deletestu'])){
                        $stmt = $conn->prepare("DELETE FROM Student WHERE stu_id = :stu_id");
                        $stmt->bindParam(':stu_id', $_POST['deletestu']);
                        $stmt->execute();
                    // Check if any rows were affected
                    if ($stmt->rowCount() > 0) {
                        echo "Student deleted successfully.";
                    } else {
                        echo "Student not found.";
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
                <div class="student">
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
                    <p><a href="index.php">Student</a></p>
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
    
                
                //read stu
                // Fetch students data
                $sql = "SELECT * FROM Student";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($students) > 0) {
                    echo "<table>";
                    echo "<tr><th>name</th><th>id</th><th>age</th><th>gender</th><th>edit</th></tr>";

                foreach ($students as $student) {
                    echo "<tr>";
                    echo "<td class='profile_name'><img src='https://i.postimg.cc/BvPJ7FHN/img1.jpg' alt='img'>".$student['stuname']."</td>";
                    echo "<td>".$student['stu_id']."</td>";
                    echo "<td>".$student['stuage']."</td>";
                    echo "<td>".$student['stugender']."</td>";
                    echo "<td><!-- Button trigger modal -->
                    <button type='submit' class='btn btn-primary' data-toggle='modal' data-target='#modaledit".$student['stu_id']."'>
                    Edit
                    </button> | 
                        <!-- Button trigger modal -->
                    <button type='submit' class='btn btn-primary' data-toggle='modal' data-target='#modaldelete".$student['stu_id']."'>
                    Delete
                    </button>  
                    <!-- Modal -->
                      <div class='modal fade' id='modaledit".$student['stu_id']."' tabindex='-1' role='dialog' aria-labelledby='ModalEdit".$student['stu_id']."' aria-hstu_idden='true'>
                        <div class='modal-dialog' role='document'>
                          <div class='modal-content'>
                            <div class='modal-header'>
                              <h5 class='modal-title' id='ModalEdit".$student['stu_id']."'>Edit</h5>
                              <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                              </button>
                              
                            </div>
                            <div class='modal-body'>
                            <form method='post' action='".$_SERVER['PHP_SELF']."'>
                            <input type='hidden' name='editstu' value='".$student['stu_id']."'>
                            <div class='form-group'>
                              <label for='editstuname".$student['stu_id']."'>name</label>
                              <input type='text' class='form-control' name='editstuname' id='editstuname".$student['stu_id']."' placeholder='stuname' value='".$student['stuname']."'>
                            </div>
                            <div class='form-group'>
                              <label for='editstuage".$student['stu_id']."'>stuage</label>
                              <input type='number' class='form-control' name='editstuage' id='editstuage".$student['stu_id']."' placeholder='stuage' value='".$student['stuage']."'>
                            </div>
                            <fieldset class='form-group'>
                                <div class='row'>
                                <legend class='col-form-label col-sm-2 pt-0'>stugender</legend>
                                <div class='col-sm-10'>
                                    <div class='form-check'>
                                    <input class='form-check-input' type='radio' name='editstugender' id='editstugendermale".$student['stu_id']."' value='Male' ".($student['stugender']==='Male'?'checked':'').">
                                    <label class='form-check-label' for='editstugendermale".$student['stu_id']."'>
                                        Male
                                    </label>
                                    </div>
                                    <div class='form-check'>
                                    <input class='form-check-input' type='radio' name='editstugender' id='editstugenderfemale".$student['stu_id']."' value='Female' ".($student['stugender']==='Female'?'checked':'').">
                                    <label class='form-check-label' for='editstugenderfemale".$student['stu_id']."'>
                                        Female
                                    </label>
                                    </div>
                                    <div class='form-check disabled'>
                                    <input class='form-check-input' type='radio' name='editstugender' id='editstugenderbinary".$student['stu_id']."' value='Binary' ".($student['stugender']==='Binary'?'checked':'').">
                                    <label class='form-check-label' for='editstugenderbinary".$student['stu_id']."'>
                                        Binary
                                    </label>
                                    </div>
                                </div>
                                </div>
                            </fieldset>
                            <div class='form-group'>
                                <label for='editimage'>Profile image</label>
                                <input type='file' class='form-control-file' id='editimage".$student['stu_id']."' name='editimage'>
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
                      <div class='modal fade' id='modaldelete".$student['stu_id']."' tabindex='-1' role='dialog' aria-labelledby='ModalDelete".$student['stu_id']."' aria-hstu_idden='true'>
                        <div class='modal-dialog' role='document'>
                          <div class='modal-content'>
                            <div class='modal-header'>
                              <h5 class='modal-title' id='ModalDelete".$student['stu_id']."'>Modal title</h5>
                              <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                              </button>
                            </div>
                            <div class='modal-body'>
                            <h6>Are you sure you want to delete this student?</h6>
                            <form action='".$_SERVER['PHP_SELF']."' method='POST'>
                                <input type='hidden' name='deletestu' value='".$student['stu_id']."' >
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
                    echo "No students found.";
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
  Add Student
</button>

<!-- Modal -->
<div class="modal fade" id="modaladd" tabindex="-1" role="dialog" aria-labelledby="Modaladd" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="Modaladd">Add Student </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
      <form method='post' action='<?php echo $_SERVER['PHP_SELF'];?>'>
    <input type='hidden' name='addstu' value='t'>
    <div class='form-group'>
      <label for='stuname'>name</label>
      <input type='text' class='form-control' name='stuname' id='stuname' placeholder='stuname'>
    </div>
    <div class='form-group'>
      <label for='stuage'>stuage</label>
      <input type='number' class='form-control' name='stuage' id='stuage' placeholder='stuage'>
    </div>
    <fieldset class='form-group'>
        <div class='row'>
        <legend class='col-form-label col-sm-2 pt-0'>stugender</legend>
        <div class='col-sm-10'>
            <div class='form-check'>
            <input class='form-check-input' type='radio' name='stugender' id='stugendermale' value='Male'>
            <label class='form-check-label' for='stugendermale'>
                Male
            </label>
            </div>
            <div class='form-check'>
            <input class='form-check-input' type='radio' name='stugender' id='stugenderfemale"' value='Female'>
            <label class='form-check-label' for='stugenderfemale'>
                Female
            </label>
            </div>
            <div class='form-check disabled'>
            <input class='form-check-input' type='radio' name='stugender' id='stugenderbinary' value='Binary'>
            <label class='form-check-label' for='stugenderbinary'>
                Binary
            </label>
            </div>
        </div>
        </div>
    </fieldset>
    <div class='form-group'>
        <label for='image'>Profile image</label>
        <input type='file' class='form-control-file' id='stuimage' name='stuimage'>
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
                    <img src="https://i.postimg.cc/g2M32zcz/image.png " alt="studentImg ">
                </div>
                <div class="name_and_class ">
                    <p>Hermione Granger</p>
                    <span>BCA Student</span>
                </div>
                <div class="contact_info ">
                    <i class='bx bx-message-rounded-dots'></i>
                    <i class='bx bx-phone-call'></i>
                    <i class='bx bx-envelope'></i>
                </div>
                <div class="about ">
                    <h4>About</h4>
                    <p>BCA student studied at ABC School of Commerce and Computer studies. I really enjoy solving problems as well as making things pretty and easy to use. I can't stop learning new things; the more, the better.</p>
                </div>
                <div class="other_info ">
                    <div class="stuage ">
                        <h4>stuage</h4>
                        <p>18</p>
                    </div>
                    <div class="stugender ">
                        <h4>stugender</h4>
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
                <div class="student_from_same_class ">
                    <div class="student_same_class_heading ">
                        <h4>Student from the same class</h4>
                    </div>
                    <div class="student_same_class_img ">
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