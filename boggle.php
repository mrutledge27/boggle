<?php

session_start();
// session_destroy();
// session_start();

$board = [];
$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

if (!isset($_SESSION['board'])) {
	for ($i=0;$i<16;$i++)
	{
		 $letter = $characters[rand(0, (strlen($characters)-1))];
		 $board[$i] = new Block($letter);
	}
	$_SESSION['errors']= [];
	$_SESSION['words']= [];
	$_SESSION['board'] = $board;
}


for ($i=0;$i<count($_SESSION['board']);$i++) {
	// Setting $this->right values for all nodes except right side of board
	if (isset($_SESSION['board'][$i+1]) && (($i+1)%4 != 0)){
		$_SESSION['board'][$i]->right = $_SESSION['board'][$i+1];
	}
	// Setting $this->left values for all nodes except left side of board
	if (isset($_SESSION['board'][$i-1]) && (($i-1)%4 != 3)){
		$_SESSION['board'][$i]->left = $_SESSION['board'][$i-1];
	}
	// Setting $this->up values for all nodes except top side of board
	if (isset($_SESSION['board'][$i-4])){
		$_SESSION['board'][$i]->up = $_SESSION['board'][$i-4];
	}
	// Setting $this->down values for all nodes except bottom side of board
	if (isset($_SESSION['board'][$i+4])){
		$_SESSION['board'][$i]->down = $_SESSION['board'][$i+4];
	}
}

// if (isset($_POST[''])

// var_dump($_SESSION);


/** NODES **/

class Block
{
	public $letter;
	public $up;
	public $down;
	public $left;
	public $right;

	public function __construct($letter)
	{
		$this->letter = $letter;
	}

	public function checkAttributes($arr, $num)
	{
		// echo $num."<br>";
		if (isset($arr[$num+1])) 
		{	
			// echo "inside if<br>";
			if (isset($this->up) && $arr[$num+1] == $this->up->letter)
			{
				// echo "inside up<br>";
				$this->up->checkAttributes($arr, $num+1);
			}
			 elseif (isset($this->down) && $arr[$num+1] == $this->down->letter)
			{
				// echo "inside down<br>";
				$this->down->checkAttributes($arr, $num+1);
			}
			 elseif (isset($this->left) && $arr[$num+1] == $this->left->letter)
			{
				// echo "inside left<br>";
				$this->left->checkAttributes($arr, $num+1);
			}
			 elseif (isset($this->right) && $arr[$num+1] == $this->right->letter)
			{
				// echo "inside right<br>";
				$this->right->checkAttributes($arr, $num+1);
			}
		}
		 elseif (!in_array(implode("",$arr), $_SESSION['words'])) 
		{
			$_SESSION['words'][] = implode("",$arr);
		}
	}
}

if (isset($_POST['action']) && !empty($_POST['word']) && searchDictionary($_POST['word'])) {
	$word = strtoupper($_POST['word']);	
	$letters = str_split($word);
	foreach ($_SESSION['board'] AS $letter) 
	{
		if ($letters[0] == $letter->letter) 
		{
			$letter->checkAttributes($letters, 0);
		}
	}
	if (!in_array($word, $_SESSION['words']) && !in_array($word, $_SESSION['errors']))
	{
		$_SESSION['errors'][] = $word;
	}
}









function searchDictionary($word) 
{
	$file = fopen('web2.txt', "r");
	while(!feof($file))
	{
		$line = strtoupper(trim(fgets($file)));
		if($line == strtoupper($word))
		{
			return true;
			// $_SESSION['words'][] = $line;
			// break;
		}
	}
	if (!in_array($word, $_SESSION['errors'])) {
		$_SESSION['errors'][] = strtoupper($word);
	}
}

// function traverse($word) 
// {
// 	for ($i=0;$i<count($letters);$i++)
// 		if ($letters[$i] == )
// 		$letters[$i]
// }





	// var_dump($_SESSION);

// var_dump($board);

// $obj = new Html_Helper ($board);

// $obj->print_table($board);

?>

<html>
<head>
	<title>Boggle</title>
	<style type="text/css">
	div.letter {
		display: inline-block;
		border:1px solid silver;
		text-align: center;
		width:40px;
		height:35px;
		padding-top:5px;
		font-size:30px;
	}
	.correct {
		color:green;
		font-weight:bold;
	}
	.wrong {
		color:red;
		font-weight: bold;
	}
	</style>
</head>
<body>
	<?php for($i=0;$i<count($_SESSION['board']);$i++){
		echo "<div class='letter'>" . $_SESSION['board'][$i]->letter . "</div>";
		if (($i+1)%4==0) {
			echo "<div class='row'></div>";			
		}
	} ?>
	<form action="#" method="post">
		<input type="text" name="word" placeholder="Type a word!">
		<input type="submit" value="submit">
		<input type="hidden" name="action" value="word">
	</form>
	<?php
	if (isset($_SESSION['words'])) {
		echo "CORRECT:<ul class='correct'>";
		foreach ($_SESSION['words'] as $word) 
		{
			echo "</li>".$word."</li><br>";
		}
		echo "</ul>";
	}
	if (isset($_SESSION['errors'])) {
		echo "WRONG:<ul class='wrong'>";
		foreach ($_SESSION['errors'] as $word) 
		{
			echo "</li>".$word."</li><br>";
		}
		echo "</ul>";
	}

	?>
</body>
</html>












