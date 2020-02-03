<?php
require_once "inc/functions.php";
$info  = '';
$task  = $_GET['task'] ?? 'report';
$error = $_GET['error'] ?? '0';

if('delete'==$task){
	$id = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_STRING );
	if($id>0){
	    deleteStudent($id);
		header( 'location: /crud/index.php?task=report' );
    }
}
if ( 'seed' == $task ) {
    seed();
    $info = "Seeding is complete";
}
$fname = '';
$lname = '';
$roll  = '';
if ( isset( $_POST['submit'] ) ) {
    $fname = filter_input( INPUT_POST, 'fname', FILTER_SANITIZE_STRING );
    $lname = filter_input( INPUT_POST, 'lname', FILTER_SANITIZE_STRING );
    $roll  = filter_input( INPUT_POST, 'roll', FILTER_SANITIZE_STRING );
    $id = filter_input( INPUT_POST, 'id', FILTER_SANITIZE_STRING );
    if($id){
        //update the existing student
        if ( $fname != '' && $lname != '' && $roll != '' ) {
            $result = updateStudent($id, $fname, $lname, $roll);
            if ( $result ) {
                header( 'location: /crud/index.php?task=report' );
            } else {
                $error = 1;
            }
        }
    }else{
        //add a new student
        if ( $fname != '' && $lname != '' && $roll != '' ) {
            $result = addStudent( $fname, $lname, $roll );
            if ( $result ) {
                header( 'location: /crud/index.php?task=report' );
            } else {
                $error = 1;
            }
        }
    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Form Example</title>
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <style>
        body {
            margin-top: 30px;
        }

    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2>CRUD Project</h2>
                <p>A simple crud project using PHP</p>
                <?php include_once( 'inc/templates/nav.php' ); ?>
                <?php
            if ( $info != '' ) {
                echo "<p>{$info}</p>";
            }
            ?>
            </div>
        </div>
        <?php if ( '1' == $error ): ?>
        <div class="row">
            <div class="col-lg-12 text-center">
                <blockquote>
                    Duplicate Roll Number
                </blockquote>
            </div>
        </div>
        <?php endif; ?>
        <?php if ( 'report' == $task ): ?>
        <div class="row">
            <div class="col-lg-12 text-center">
                <?php generateReport(); ?>
            </div>
        </div>
        <?php endif; ?>
        <?php if ( 'add' == $task ): ?>
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <form action="/crud/index.php?task=add" method="POST">
                    <div class="form-group">
                        <label for="fname">First Name</label>
                        <input type="text" class="form-control" name="fname" id="fname" value="<?php echo $fname;?>">
                    </div>
                    <div class="form-group">
                        <label for="lname">Last Name</label>
                        <input type="text" class="form-control" name="lname" id="lname" value="<?php echo $lname; ?>">
                    </div>
                    <div class="form-group">
                        <label for="roll">Roll</label>
                        <input type="number" class="form-control" name="roll" id="roll" value="<?php echo $roll; ?>">
                    </div>
                    <button type="submit" class="btn btn-primary" name="submit">Save</button>
                </form>

            </div>
        </div>
        <?php endif; ?>
        <?php
    if ( 'edit' == $task ):
        $id = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_STRING );
        $student = getStudent( $id );
        if ( $student ):
            ?>
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <form method="POST">
                    <input type="hidden" value="<?php echo $id ?>" name="id">
                    <div class="form-group">
                        <label for="fname">First Name</label>
                        <input type="text" class="form-control" name="fname" id="fname" value="<?php echo $student['fname']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="lname">Last Name</label>
                        <input type="text" class="form-control" name="lname" id="lname" value="<?php echo $student['lname']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="roll">Roll</label>
                        <input type="number" class="form-control" name="roll" id="roll" value="<?php echo $student['roll']; ?>">
                    </div>
                    <button type="submit" class="btn btn-primary" name="submit">Update</button>
                </form>
            </div>
        </div>
        <?php
        endif;
    endif;
    ?>
    </div>
    <script type="text/javascript" src="assets/js/script.js"></script>
</body>

</html>
