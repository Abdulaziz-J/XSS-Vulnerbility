<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "xss3";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['clear']))
{
	$sql = "TRUNCATE TABLE comments";
	if ($conn->query($sql) === TRUE) 
	{
		echo "Table Cleared";
	} else {
		echo "Error: Unable to Clear Table". $conn->error;
	}
}

if (isset($_POST['comment']))
{
	$sql = "INSERT INTO comments (comment)
	VALUES ('".addslashes($_POST['comment'])."')";
}

?>
<!DOCTYPE html>
<html>
<title> XSS Avoid Advanced Filters #5 </title>
<style>
	#bord td
	{
		border: 1px solid black;
		border-collapse: collapse;
	}
</style>
<body>
<h1 align="center"> Try The New Comment Website! </h1>
<table align="center">
<tr><td>
<p>
<?php
if (isset($_GET['name']))
{
	echo "<p align='center'>Hey there ".$_GET['name']."! Welcome!</p>";
}
if (isset($_POST['comment']))
{
	if ($conn->query($sql) === TRUE) 
	{
		echo "New record created successfully";
	} else {
		echo "Error: Unable to add comment";
	}
}
?>
</p>
<form action="index.php" method="post">
	<textarea rows="6" cols="50" name="comment" placeholder="Leave a comment" maxlength="400"></textarea>
	<table align="center"><tr><td>
	<input type="submit" value="Comment" />
	</td></tr></table>
</form>
<form action="index.php" method="get">
	<table align="center"><tr><td>
	Name:<input type="text" name="name" />
	<input type="submit" value="Submit" />
	</td></tr></table>
</form>
</td></tr>
</table>
<br />
<br />
<table align="center" id="bord">
<?php
$sql = "SELECT id, comment FROM comments";
$result = $conn->query($sql);

$noChars = array("\'", "\"", "\\");  //The chars we want to replace
$repChars = array('&apos;', "&quot;", "&bsol;"); //The chats we are replacing with. (HTML entities)

$blacklist = array("<script>", "<Script>", "alert");

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
		$row["comment"] = str_replace($noChars, $repChars, $row["comment"]);
        echo "<tr><td style='width:35%;padding:10px'>Comment #".$row["id"]."<br /><hr />".str_replace($blacklist, "", $row["comment"])."<br /></td></tr>";
    }
} else {
    echo "<tr><td style='width:35%'>No Comments!</td></tr>";
}
$conn->close();
?>
</table>
<h3 align="center"> This website was made to show XSS vulnerability! by Aziz_940040! </h3>
<table align="center">
<tr><td>
<form action="index.php" method="post">
	Debug: <input type="submit" name="clear" value="Clear Table" />
</form>
<a href="index2.php">next =></a>
</td></tr>
</table>
</body>
</html> 