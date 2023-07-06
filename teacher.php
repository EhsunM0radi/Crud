<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
    $user = "root";
    $pass = "";
    $host = "localhost";
    $dbname = "School";
    try {
        $connection = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        die("Error: " . $e->getMessage());
    }

    // Create a new 
    function create($name, $specialty, $gender,$lessons)
    {
        global $connection;

        $sql = "INSERT INTO Teacher (`name`, specialty, gender) VALUES (:name, :specialty, :gender);";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':specialty', $specialty);
        $stmt->bindParam(':gender', $gender);
        $stmt->execute();
        $teacherid = $connection->lastInsertId();
        foreach($lessons as $lesson){
            associateTeacherLesson((int)$teacherid, (int)$lesson);
        }
}
    // Create a new Lesson
    function createLesson($name, $type)
    {
        global $connection;

        $sql = "INSERT INTO Lesson (`name`, `type`) VALUES (:name, :type)";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':type', $type);
        $stmt->execute();
    }
    // Associate a  with a Lesson
    function associateTeacherLesson($teacherid, $lessonid)
    {
        global $connection;
        $teachersforlesson = getteachersforlesson($lessonid);
        foreach($teachersforlesson as $teacherforlesson){
            if($teacherforlesson['teacher_id']===$teacherid){
                return;
            }
        }
        $sql = "INSERT INTO teacher_lesson (teacher_id, lesson_id) VALUES (:teacher_id, :lesson_id)";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':teacher_id', $teacherid);
        $stmt->bindParam(':lesson_id', $lessonid);
        $stmt->execute();
}
    // Retrieve all Teachers
    function getAllTeachers()
    {
        global $connection;

        $sql = "SELECT * FROM Teacher";
        $stmt = $connection->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Retrieve all Lessons
    function getAllLessons()
    {
        global $connection;

        $sql = "SELECT * FROM Lesson";
        $stmt = $connection->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Retrieve all Teacher Lesson
    function getAllTeacherLesson($teacherId){
        global $connection;
        $sql = "SELECT * FROM teacher_lesson WHERE teacher_id = :id";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':id',$teacherId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Retrieve all teachers for a lesson
    function getteachersforlesson($lessonid){
        global $connection;
        $sql = "SELECT * FROM teacher_lesson WHERE lesson_id = :id";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':id',$lessonid);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Delete a Teacher
    function deleteTeacher($teacherId)
    {
        global $connection;

        $sql = "DELETE FROM Teacher WHERE id = :teacherId";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':teacherId', $teacherId);
        $stmt->execute();
    }

    // Update a Teacher's information
    function updateTeacher($teacherId, $name, $specialty, $gender, $lessons)
    {
        global $connection;

        $sql = "UPDATE Teacher SET name = :name, specialty = :specialty, gender = :gender WHERE id = :teacherId";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':specialty', $specialty);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':teacherId', $teacherId);
        $stmt->execute();

        $teacherlessons = getAllTeacherLesson($teacherId);

        foreach($teacherlessons as $teacherlesson){

            foreach($lessons as $lesson){
                if (!((int)$lesson === $teacherlesson['lesson_id'])){
                    associateTeacherLesson((int)$teacherId, (int)$lesson);
                }
                //go to next teacherlesson
        }
            
        }
        
    }

    if($_POST['add']==="t"){
        $name = $_POST['name'];
        $specialty = $_POST['specialty'];
        $gender = $_POST['gender'];
        $lessons = $_POST['lessons'];
        create($name,$specialty,$gender,$lessons);
    }

    if(isset($_POST['delete'])){
        deleteTeacher($_POST['delete']);
    }

    if(isset($_POST['edit'])){
        $id = $_POST['edit'];
        $name = $_POST['name'];
        $specialty = $_POST['specialty'];
        $gender = $_POST['gender'];
        $lessons = $_POST['lessons'];
        updateTeacher($id,$name,$specialty,$gender,$lessons);
    }
            $conn = null;
                
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                <div class="">
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
                    <p><a href="index.php"></a></p>
                    <p><a href=".php"></a></p>
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
                $connection = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
                $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                
                //read 
                // Fetch teachers data
                $teachers = getAllTeachers();


                if (count($teachers) > 0) {
                    echo "<table>";
                    echo "<tr><th>name</th><th>id</th><th>specialty</th><th>gender</th><th>lessons</th><th>edit</th></tr>";

                foreach ($teachers as $teacher) {
                    $teacher_lessons = getAllTeacherLesson($teacher['id']);
                    // die(var_dump($teacher_lessons));
                    echo "<tr>";
                    echo "<td class='profile_name'><img src='https://i.postimg.cc/BvPJ7FHN/img1.jpg' alt='img'>".$teacher['name']."</td>";
                    echo "<td>".$teacher['id']."</td>";
                    echo "<td>".$teacher['specialty']."</td>";
                    echo "<td>".$teacher['gender']."</td>";
                    echo '<td>';
                    foreach($teacher_lessons as $teacher_lesson){
                        $sql2 = "SELECT * FROM Lesson WHERE id = :id";
                        $stmt2 = $connection->prepare($sql2);
                        $stmt2->bindParam(':id',$teacher_lesson['lesson_id']);
                        $stmt2->execute();
                        $getlessoninfo = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                        // die(var_dump($getlessoninfo));
                        echo $getlessoninfo[0]['name']."<br>";
                    }
                      
                    echo '<del><td>';
                    echo "<td><!-- Button trigger modal -->
                    <button type='submit' class='btn btn-primary' data-toggle='modal' data-target='#modaledit".$teacher['id']."'>
                    Edit
                    </button> | 
                        <!-- Button trigger modal -->
                    <button type='submit' class='btn btn-primary' data-toggle='modal' data-target='#modaldelete".$teacher['id']."'>
                    Delete
                    </button>  
                    <!-- Modal -->
                      <div class='modal fade' id='modaledit".$teacher['id']."' tabindex='-1' role='dialog' aria-labelledby='ModalEdit".$teacher['id']."' aria-hidden='true'>
                        <div class='modal-dialog' role='document'>
                          <div class='modal-content'>
                            <div class='modal-header'>
                              <h5 class='modal-title' id='ModalEdit".$teacher['id']."'>Edit</h5>
                              <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                              </button>
                              
                            </div>
                            <div class='modal-body'>
                            <form method='post' action='".$_SERVER['PHP_SELF']."'>
                            <input type='hidden' name='edit' value='".$teacher['id']."'>
                            <div class='form-group'>
                              <label for='editname".$teacher['id']."'>name</label>
                              <input type='text' class='form-control' name='name' id='editname".$teacher['id']."' placeholder='name' value='".$teacher['name']."'>
                            </div>
                            <div class='form-group'>
                              <label for='editspecialty".$teacher['id']."'>specialty</label>
                              <input type='text' class='form-control' name='specialty' id='editspecialty".$teacher['id']."' placeholder='specialty' value='".$teacher['specialty']."'>
                            </div>
                            <fieldset class='form-group'>
                                <div class='row'>
                                <legend class='col-form-label col-sm-2 pt-0'>gender</legend>
                                <div class='col-sm-10'>
                                    <div class='form-check'>
                                    <input class='form-check-input' type='radio' name='gender' id='editgendermale".$teacher['id']."' value='Male' ".($teacher['gender']==='Male'?'checked':'').">
                                    <label class='form-check-label' for='editgendermale".$teacher['id']."'>
                                        Male
                                    </label>
                                    </div>
                                    <div class='form-check'>
                                    <input class='form-check-input' type='radio' name='gender' id='editgenderfemale".$teacher['id']."' value='Female' ".($teacher['gender']==='Female'?'checked':'').">
                                    <label class='form-check-label' for='editgenderfemale".$teacher['id']."'>
                                        Female
                                    </label>
                                    </div>
                                    <div class='form-check disabled'>
                                    <input class='form-check-input' type='radio' name='gender' id='editgenderbinary".$teacher['id']."' value='Binary' ".($teacher['gender']==='Binary'?'checked':'').">
                                    <label class='form-check-label' for='editgenderbinary".$teacher['id']."'>
                                        Other
                                    </label>
                                    </div>
                                    <h3>Lessons:</h3>";
                                    
                                    // Retrieve all lessons from the database
                    $lessons = getAllLessons();
                    // Display checkboxes for each lesson
                    foreach ($lessons as $lesson) {
                        $teachersforlesson = getteachersforlesson($lesson['id']);
                        echo '<input type="checkbox" name="lessons[]" value="' . $lesson['id'] . '" ';
                        foreach ($teachersforlesson as $teacherforlesson){
                        // if($teacherforlesson['teacher_id']===$teacher['id']){
                        echo (($teacherforlesson['teacher_id']===$teacher['id'])?"checked":"");
                        // }
                    }
                        echo '>' . $lesson['name'] . '<br>';
                    }    
                                    
                                echo "</div>
                                </div>
                            </fieldset>
                            <div class='form-group'>
                                <label for='editimage'>Profile image</label>
                                <input type='file' class='form-control-file' id='editimage".$teacher['id']."' name='editimage'>
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
                      <div class='modal fade' id='modaldelete".$teacher['id']."' tabindex='-1' role='dialog' aria-labelledby='ModalDelete".$teacher['id']."' aria-hidden='true'>
                        <div class='modal-dialog' role='document'>
                          <div class='modal-content'>
                            <div class='modal-header'>
                              <h5 class='modal-title' id='ModalDelete".$teacher['id']."'>Modal title</h5>
                              <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                              </button>
                            </div>
                            <div class='modal-body'>
                            <h6>Are you sure you want to delete this ?</h6>
                            <form action='".$_SERVER['PHP_SELF']."' method='POST'>
                                <input type='hidden' name='delete' value='".$teacher['id']."' >
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
                    echo "No s found.";
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
  Add 
</button>

<!-- Modal -->
<div class="modal fade" id="modaladd" tabindex="-1" role="dialog" aria-labelledby="Modaladd" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="Modaladd">Add  </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
      <form method='post' action='<?php echo $_SERVER['PHP_SELF'];?>'>
        <input type='hidden' name='add' value='t'>
        <div class='form-group'>
        <label for='name'>name</label>
        <input type='text' class='form-control' name='name' id='name' placeholder='name'>
        </div>
        <div class='form-group'>
        <label for='specialty'>specialty</label>
        <input type='text' class='form-control' name='specialty' id='specialty' placeholder='specialty'>
        </div>
        <fieldset class='form-group'>
            <div class='row'>
            <legend class='col-form-label col-sm-2 pt-0'>gender</legend>
            <div class='col-sm-10'>
                <div class='form-check'>
                <input class='form-check-input' type='radio' name='gender' id='gendermale' value='Male'>
                <label class='form-check-label' for='gendermale'>
                    Male
                </label>
                </div>
                <div class='form-check'>
                <input class='form-check-input' type='radio' name='gender' id='genderfemale"' value='Female'>
                <label class='form-check-label' for='genderfemale'>
                    Female
                </label>
                </div>
                <div class='form-check disabled'>
                <input class='form-check-input' type='radio' name='gender' id='genderbinary' value='Binary'>
                <label class='form-check-label' for='genderbinary'>
                    Other
                </label>
                </div>
                <h3>Lessons:</h3>
            <?php
            // Retrieve all lessons from the database
            $lessons = getAllLessons();

            // Display checkboxes for each lesson
            foreach ($lessons as $lesson) {
                echo '<input type="checkbox" name="lessons[]" value="' . $lesson['id'] . '">' . $lesson['name'] . '<br>';
            }
            ?>
            </div>
            </div>
        </fieldset>
        <div class='form-group'>
            <label for='image'>Profile image</label>
            <input type='file' class='form-control-file' id='image' name='image'>
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
                    <img src="https://i.postimg.cc/g2M32zcz/image.png " alt="Img ">
                </div>
                <div class="name_and_class ">
                    <p>Hermione Granger</p>
                    <span>BCA </span>
                </div>
                <div class="contact_info ">
                    <i class='bx bx-message-rounded-dots'></i>
                    <i class='bx bx-phone-call'></i>
                    <i class='bx bx-envelope'></i>
                </div>
                <div class="about ">
                    <h4>About</h4>
                    <p>BCA  studied at ABC School of Commerce and Computer studies. I really enjoy solving problems as well as making things pretty and easy to use. I can't stop learning new things; the more, the better.</p>
                </div>
                <div class="other_info ">
                    <div class="specialty ">
                        <h4>specialty</h4>
                        <p>18</p>
                    </div>
                    <div class="gender ">
                        <h4>gender</h4>
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
                <div class="_from_same_class ">
                    <div class="_same_class_heading ">
                        <h4> from the same class</h4>
                    </div>
                    <div class="_same_class_img ">
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="script.js"></script>
</body>
</html>