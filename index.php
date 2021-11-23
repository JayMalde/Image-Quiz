<html>
<?php require 'dbconfig.php';
session_start(); ?>
<head>
<title>Quizzard</title>
<style>
body {
    /* background: url("bg.jpg");
	background-size:100%;
	background-repeat: no-repeat;
	position: relative;
	background-attachment: fixed; */
}
/* button */
.button {
  display: inline-block;
  border-radius: 4px;
  background-color: #f4511e;
  border: none;
  color: #FFFFFF;
  text-align: center;
  font-size: 28px;
  padding: 20px;
  width: 500px;
  transition: all 0.5s;
  cursor: pointer;
  margin: 5px;
}

img{
    width: 400px;
}

.button span {
  cursor: pointer;
  display: inline-block;
  position: relative;
  transition: 0.5s;
}
 
.button span:after {
  content: '\00bb';
  position: absolute;
  opacity: 0;
  top: 0;
  right: -20px;
  transition: 0.5s;
}
 
.button:hover span {
  padding-right: 25px;
}
 
.button:hover span:after {
  opacity: 1;
  right: 0;
}
.title{
	background-color: #ccc11e;
	font-size: 28px;
  padding: 20px;
	
}
.button3 {
    border: none;
    color: white;
    padding: 10px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    -webkit-transition-duration: 0.4s; /* Safari */
    transition-duration: 0.4s;
    cursor: pointer;
}
.button3 {
    background-color: white; 
    color: black; 
    border: 2px solid #f4e542;
}
 
.button3:hover {
    background-color: #f4e542;
    color: Black;
}
</style>  
</head>
<body><center>
<div class="title">Quiz</div>
<div id="clockdiv"></div>
<?php 															
	if (isset($_POST['click']) || isset($_GET['start']) && isset($_GET['uname'])) {
        @$_SESSION['clicks'] += 1 ;
        $c = $_SESSION['clicks'];
        if(isset($_POST['userans'])) { $userselected = $_POST['userans'];
        
        $fetchqry2 = "UPDATE `quiz1` SET `userans`='$userselected' WHERE `id`=$c-1"; 
        $result2 = mysqli_query($con,$fetchqry2);
        }

            
    } else {
            $_SESSION['clicks'] = 0;
    }
        
        //echo($_SESSION['clicks']);
        ?>
<div class="bump">
  <br>
  <form>
    <?php if($_SESSION['clicks']==0){ ?> 
      <input type="text" placeholder="firstname lastname" name="uname" required/><br>
      <button class="button" name="start" float="left"><span>START QUIZ</span></button>
    <?php } ?>
  </form>
</div>
<form action="" method="post">  				
<table><?php if(isset($c)) {   $fetchqry = "SELECT * FROM `quiz1` where id='$c'"; 
				$result=mysqli_query($con,$fetchqry);
				$num=mysqli_num_rows($result);
				$row = mysqli_fetch_array($result,MYSQLI_ASSOC); }
		  ?>
<tr><td><h3><br><?php echo @$row['que'];?></h3></td></tr> <?php if($_SESSION['clicks'] > 0 && $_SESSION['clicks'] < 3){ ?>
  <tr><td><img src="<?php echo @$row['image'];?>"/></td></tr><br>
  <tr><td><input type="text" name="userans"/></td></tr><br>
  <tr><td><button class="button3" name="click" id="click" >Next</button></td></tr> <?php }  
		?> 
  <form>
 <?php if($_SESSION['clicks']>2){ 
	$qry3 = "SELECT `ans`, `userans` FROM `quiz1`;";
	$result3 = mysqli_query($con,$qry3);
	$storeArray = Array();
	while ($row3 = mysqli_fetch_array($result3, MYSQLI_ASSOC)) {
     if($row3['ans']==$row3['userans']){
		 @$_SESSION['score'] += 1 ;
	 }
}
 
 ?> 
 
 
 <h2>Result</h2><br>
 <?php
    $uname=$_GET["uname"];
    $no = @$_SESSION['score'];
    $score = $no*10;
    $sql = "INSERT INTO score (username, correct_answers, total_score) VALUES ('$uname', $no, $score)";

    if ($con  ->query($sql) === TRUE) {
        echo "New record created successfully<br>";
    }
    else {
      echo "Error: " . $sql . "<br>" . $con->error;
    }
 ?>
 <br>
 <span><?php echo $uname; ?></span>
 <span>No. of Correct Answer:&nbsp;<?php echo $no;
 session_unset(); ?></span><br>
 <span>Your Score:&nbsp<?php echo $score; ?></span>
<?php } ?>
</center>

<script>
// 1 minutes from now
// var time_in_minutes = 1;
var time_in_seconds = 10;
var current_time = Date.parse(new Date());
// var deadline = new Date(current_time + time_in_minutes*60*1000);
var deadline = new Date(current_time + time_in_seconds*1000);


function time_remaining(endtime){
	var t = Date.parse(endtime) - Date.parse(new Date());
	var seconds = Math.floor( (t/1000) % 60 );
	var minutes = Math.floor( (t/1000/60) % 60 );
	var hours = Math.floor( (t/(1000*60*60)) % 24 );
	var days = Math.floor( t/(1000*60*60*24) );
	return {'total':t, 'days':days, 'hours':hours, 'minutes':minutes, 'seconds':seconds};
}
function run_clock(id,endtime){
	var clock = document.getElementById(id);
	function update_clock(){
		var t = time_remaining(endtime);
		clock.innerHTML = 'Time-> '+t.minutes+' : '+t.seconds;
		
    if(t.total<=0)
    { 
      clearInterval(timeinterval);
      document.getElementById("click").click();
    }
	
  }
	update_clock(); // run function once at first to avoid delay
	var timeinterval = setInterval(update_clock,1000);
}
run_clock('clockdiv',deadline);
</script>
</body>
</html>