<?php
// Create a new Teacher
    function createTeacher($name, $specialty, $gender,$lessons)
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
        return header("Location: ".$_SERVER["PHP_SELF"]);
    }
// Create a new Student
    function createStudent($name, $age, $gender,$lessons)
    {
        global $connection;

        $sql = "INSERT INTO Student (`name`, age, gender) VALUES (:name, :age, :gender);";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':gender', $gender);
        $stmt->execute();
        $studentid = $connection->lastInsertId();
        foreach($lessons as $lesson){
            associateStudentLesson((int)$studentid, (int)$lesson);
        }
        return header("Location: ".$_SERVER["PHP_SELF"]);
    }

    // Create a new Lesson
    function createLesson($name, $prerequisites, $type, $teachers)
    {
        global $connection;

        $sql = "INSERT INTO Lesson (`name`, `type`) VALUES (:name, :type)";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':type', $type);
        $stmt->execute();
        $lessonid = $connection->lastInsertId();
        foreach($teachers as $teacher){
            associateLessonTeacher((int)$lessonid, (int)$teacher);
        }
        foreach($prerequisites as $prerequisite){
            associateLessonLesson((int)$lessonid,(int)$prerequisite);
        }
        return header("Location: ".$_SERVER["PHP_SELF"]);

    }
    // Associate a Teacher with a Lesson
    function associateTeacherLesson($teacherid, $lessonid)
    {
        global $connection;
        $teachersforlesson = getTeachersForLesson($lessonid);
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
        return header("Location: ".$_SERVER["PHP_SELF"]);
}
    // Associate a Student with a Lesson
    function associateStudentLesson($studentid, $lessonid)
    {
        global $connection;
        $studentsforlesson = getStudentsForLesson($lessonid);
        foreach($studentsforlesson as $studentforlesson){
            if($studentforlesson['student_id']===$studentid){
                return;
            }
        }
        $sql = "INSERT INTO student_lesson (student_id, lesson_id) VALUES (:student_id, :lesson_id)";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':student_id', $studentid);
        $stmt->bindParam(':lesson_id', $lessonid);
        $stmt->execute();
    }

    // Associate a Lesson with a Teacher
    function associateLessonTeacher($lessonId,$teacherId){
        global $connection;
        $lessonsforteacher = getLessonsForTeacher($teacherId);
        foreach($lessonsforteacher as $lessonforteacher){
            if($lessonforteacher['lesson_id']===$lessonId){
                return;
            }
        }
        $sql = "INSERT INTO teacher_lesson (teacher_id, lesson_id) VALUES (:teacher_id, :lesson_id)";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':lesson_id', $lessonId);
        $stmt->bindParam(':teacher_id', $teacherId);
        $stmt->execute();
    }

    function associateLessonLesson($lessonId,$prerequisite){
        global $connection;
        $lessonsforlesson = getLessonsForLesson($lessonId);
        foreach($lessonsforlesson as $lessonforlesson){
            if($lessonforlesson['prerequisite']===$lessonId){
                return;
            }
        }
        $sql = "INSERT INTO lesson_lesson (lesson_id, prerequisite) VALUES (:lesson_id, :prerequisite)";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':lesson_id', $lessonId);
        $stmt->bindParam(':prerequisite', $prerequisite);
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

    // Retrieve all students
    function getAllStudents()
    {
        global $connection;

        $sql = "SELECT * FROM Student";
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

    // Retrieve all student Lesson
    function getAllStudentLesson($studentId){
        global $connection;
        $sql = "SELECT * FROM student_lesson WHERE student_id = :id";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':id',$studentId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Retrieve all Teacher Lesson
    function getAllLessonTeacher($lessonId){
        global $connection;
        $sql = "SELECT * FROM teacher_lesson WHERE lesson_id = :id";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':id',$lessonId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Retrieve all teachers for a lesson
    function getTeachersForLesson($lessonid){
        global $connection;
        $sql = "SELECT * FROM teacher_lesson WHERE lesson_id = :id";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':id',$lessonid);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Retrieve all lessons for a teacher
    function getLessonsForTeacher($teacherid){
        global $connection;
        $sql = "SELECT * FROM teacher_lesson WHERE teacher_id = :id";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':id',$teacherid);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Retrieve all students for a lesson
    function getStudentsForLesson($lessonid){
        global $connection;
        $sql = "SELECT * FROM student_lesson WHERE lesson_id = :id";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':id',$lessonid);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Retrieve all lessons for a lesson
    function getLessonsForLesson($lessonId){
        global $connection;
        $sql = "SELECT * FROM lesson_lesson WHERE lesson_id = :id";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':id',$lessonId);
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
        return header("Location: ".$_SERVER["PHP_SELF"]);
    }

    // Delete a student
    function deleteStudent($studentId)
    {
        global $connection;

        $sql = "DELETE FROM Student WHERE id = :studentId";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':studentId', $studentId);
        $stmt->execute();
        return header("Location: ".$_SERVER["PHP_SELF"]);
        }

    //deleteLesson
    function deleteLesson($lessonId){
        global $connection;

        $sql = "DELETE FROM Lesson WHERE id = :lessonId";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':lessonId', $lessonId);
        $stmt->execute();
        return header("Location: ".$_SERVER["PHP_SELF"]);
        }
    
    // Delete teacher_lessons where teahcer_id is equal to sth
    function deleteTeacherLesson($teacherId){
        global $connection;

        $sql = "DELETE FROM teacher_lesson WHERE teacher_id = :teacherId";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':teacherId', $teacherId);
        $stmt->execute();
    }

    // Delete teacher_lessons where teahcer_id is equal to sth
    function deleteLessonTeacher($lessonId){
        global $connection;

        $sql = "DELETE FROM teacher_lesson WHERE lesson_id = :lessonId";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':lessonId', $lessonId);
        $stmt->execute();
    }

    // Delete teacher_lessons where teahcer_id is equal to sth
    function deleteLessonLesson($lessonId){
        global $connection;

        $sql = "DELETE FROM lesson_lesson WHERE lesson_id = :lessonId";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':lessonId', $lessonId);
        $stmt->execute();
    }

    // Delete student_lessons where student_id is equal to sth
    function deleteStudentLesson($studentId){
        global $connection;

        $sql = "DELETE FROM student_lesson WHERE student_id = :studentId";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':studentId', $studentId);
        $stmt->execute();
    }

    // Delete student_lessons where lesson_id is equal to sth
    function deleteLessonStudent($lessonId){
        global $connection;

        $sql = "DELETE FROM student_lesson WHERE lesson_id = :lessonId";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':lessonId', $lessonId);
        $stmt->execute();
    }


    // Update a Teacher's information
    function updateTeacher($teacherId, $name, $specialty, $gender, $lessons)
    {
        global $connection;

        $sql = "UPDATE Teacher SET `name` = :name, specialty = :specialty, gender = :gender WHERE id = :teacherId";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':specialty', $specialty);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':teacherId', $teacherId);
        $stmt->execute();

        deleteTeacherLesson($teacherId);        

        foreach($lessons as $lesson){
            associateTeacherLesson((int)$teacherId, (int)$lesson);
            //go to next teacherlesson
        }
        
        return header("Location: ".$_SERVER["PHP_SELF"]);
        }

    // Update a student's information
    function updateStudent($studentId, $name, $age, $gender, $lessons)
    {
        global $connection;

        $sql = "UPDATE Student SET `name` = :name, age = :age, gender = :gender WHERE id = :studentId";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':studentId', $studentId);
        $stmt->execute();

        deleteStudentLesson($studentId);        

        foreach($lessons as $lesson){
            associateStudentLesson((int)$studentId, (int)$lesson);
            //go to next studentlesson
        }
        return header("Location: ".$_SERVER["PHP_SELF"]);
        }

    // Update a lesson's information
    function updateLesson($lessonId,$name, $prerequisites, $type, $teachers){
        global $connection;

        $sql = "UPDATE Lesson SET `name` = :name, `type` = :type WHERE id = :lessonId";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':lessonId', $lessonId);
        $stmt->execute();

        deleteLessonLesson($lessonId);
        deleteLessonTeacher($lessonId);

        foreach($teachers as $teacher){
            associateLessonTeacher((int)$lessonId, (int)$teacher);
            //go to next studentlesson
        }

        foreach($prerequisites as $prerequisite){
            associateLessonLesson((int)$lessonId,(int)$prerequisite);
        }
        return header("Location: ".$_SERVER["PHP_SELF"]);
        }

    //deleteLesson
    //updatelesson
    //getAlllessonteacher
    //getlessonsForteacher

?>