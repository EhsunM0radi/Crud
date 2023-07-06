<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modaledit">
  edit
</button>

<!-- Modal -->
<div class="modal fade" id="modaledit" tabindex="-1" role="dialog" aria-labelledby="Modaledit" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="Modaledit">edit teacher </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
      <form method='post' action='<?php echo $_SERVER['PHP_SELF'];?>'>
    <input type='hidden' name='editteacher' value='t'>
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
                Other
            </label>
            </div>
            <h3>Lessons:</h3>
        <?php
        // Retrieve all lessons from the database
        $lessons = getAllLessons();

        // Display checkboxes for each lesson
        foreach ($lessons as $lesson) {
            echo '<input type="checkbox" name="teacherlessons[]" value="' . $lesson['lesson_id'] . '">' . $lesson['lessonname'] . '<br>';
        }
        ?>