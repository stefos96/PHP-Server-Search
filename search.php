<?php
// echo 'test';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
<title>Search</title>
</head>
<link rel="icon" 
      type="image/png" 
      href="https://img.icons8.com/cotton/2x/search--v2.png" />


<?php
$key = 'class="actions action-add-to"';

$dir = getcwd() . '/app/';


style();
script();

$index = 1;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$key = $_POST["search_query"];
	$dir = getcwd() . $_POST["search_directory"];
	$default_dir = $_POST["search_directory"];
}

$files = ListIn($dir);

echo '<div class="messages-wrapper" style="display: none;"><p>Copied to clipboard</p></div>';

echo '<button style="display: none;" onclick="showHiddenItems(this)" class="show-hidden-items">0</button>';
echo '<span style="display: none;" class="show-hidden-items-label">Hidden Items</span>';

echo '<div class="input-wrapper">';

echo '<form class="form" action="./search_old.php" method="post">';
echo '<h3>Search</h3>';
echo '<input class="search-query" name="search_query" spellcheck="false" type="text" value=\'' . $key . '\'>';
echo '<h3>in</h3>';
if (!isset($default_dir)) {
	$default_dir = '/app/';
}
echo '<input name="search_directory" type="text" spellcheck="false" value="' . $default_dir . '">';
echo '<input type="submit" name="submit" value="Search">';
echo '</form>';




$filetypes = ['all'];

$loop_str_output = '<div class="container">';

foreach ($files as $file) {
	$content = file_get_contents($dir . $file);
	if (strpos($content, $key) !== false) {
		$whole_string = $dir . $file;


		$filename = array_pop(explode("/", $whole_string));

		$before_public_html = explode("/public_html", $whole_string)[0];

		$after_public_html = explode("public_html", $whole_string)[1];
		$correct_length = strlen($after_public_html) - strlen($filename);
		$after_public_html = '/public_html' . substr($after_public_html, 0, $correct_length);


		$filetype_temp = array_pop(explode(".", $filename));
		if (!in_array($filetype_temp, $filetypes)) {
			array_push($filetypes, $filetype_temp);
		}

		$str_output = '<div>';
		$str_output .= '<div class="index">' . $index++ . '</div>';



		$str_output .= wrapButton($before_public_html);
		$str_output .= wrapButton($after_public_html);
		$str_output .= wrapButton($filename);

		$str_output .= '</div>';

		$loop_str_output .= '<div class="row '.$filetype_temp.'">';
		$loop_str_output .= $str_output;
		$loop_str_output .= '</div>';
	}
}
$loop_str_output .= '</div>';


echo '<div class="filetype-wrapper">';
echo getFileTypesHTML($filetypes);
echo '</div>';

// input wrapper div close
echo '</div>';


echo $loop_str_output;



function ListIn($dir, $prefix = '')
{
	$dir = rtrim($dir, '\\/');
	$result = array();

	foreach (scandir($dir) as $f) {
		if ($f !== '.' and $f !== '..') {
			if (is_dir("$dir/$f")) {
				$result = array_merge($result, ListIn("$dir/$f", "$prefix$f/"));
			} else {
				$result[] = $prefix . $f;
			}
		}
	}

	return $result;
}

function style()
{
	echo '
		<style>

		body {
			background: #1d142d;
			overflow-x: hidden;
		}

		.row {
			font-family: Roboto;
			font-size: 15px;
			background: linear-gradient(45deg, #503a3a, #ea3a3a00);
			color: white;
			padding: 5px;
			border-radius: 3px;
			display: flex;
			justify-content: space-between;
			margin-bottom: 2px;
		}

		button {
			padding: 0px 10px;
			font-family: Roboto;
			border-radius: 20px;
			border: none;
			background: #ffffffb8;
			margin-right: 5px;
			transition: .2s all ease-in-out;
		}

		:focus {
			outline: none;
			background: #845968;
			color: white;
		}

		.selected {
			background: #845968;
			color: white;
		}
		
		.index {
			width: auto;
			padding: 0px 6px;
			height: 20px;
			display: inline-flex;
			margin: 0px 5px;
			background: #bb3563;
			border-radius: 50px;
			text-align: center;
			justify-content: center;
			cursor: pointer;
		}

		.input-wrapper {
			display: flex;
			justify-content: center;
			padding: 10px;
			align-items: center;
			flex-direction: column;
		}

		.form {
			display: flex;
			margin: 10px 0px;
		}

		h3 {
			margin: 0px;
			color: white;
			font-family: Roboto;
			font-size: 15px;
			font-weight: normal;
		}

		input[type=text] {
			border-radius: 18px;
			padding: 0px 10px;
			border: none;
			background: #cfcdd2;
			color: black;
			margin: 0px 10px;
			font-family: Roboto;
		}

		input[type=text]:focus {
			box-shadow: 0px 0px 7px 2px #bb3563;
		}

		input[type=submit] {
			border-radius: 18px;
			padding: 0px 10px;
			border: none;
			background: #cfcdd2;
			color: black;
			cursor: pointer;
		}

		.search-query {
			min-width: 40vw;
		}

		button.show-hidden-items {
			position: absolute;
			right: 30px;
			bottom: 30px;
			height: 50px;
			width: 50px;
			border-radius: 100%;
			padding: 0px;
			margin: 0px;
			background: #bb3563;
			color: white;
			font-size: 18px;
			z-index: 2;
			cursor: pointer;
		}

		span.show-hidden-items-label {
			position: absolute;
			right: 47px;
			bottom: 35px;
			width: 135px;
			border-radius: 20px;
			background: white;
			font-family: Roboto;
			padding: 10px 0px 10px 10px;
			z-index: 1;
			color: #353535;
			cursor: default;
			font-size: 15px;
		}

		.messages-wrapper {
			position: absolute;
			width: 100%;
			display: flex;
			top: 15px;
		}

		.messages-wrapper > p {
			width: auto;
			margin: auto;
			color: white;
			padding: 5px 15px;
			background: #bb3563d6;
			border-radius: 20px;
			box-shadow: 0px 0px 2px 2px #bb3563c7;
			font-family: Roboto;
			font-size: 15px;
		}

		.animate-down {
			transition: 1s transform ease-in-out;
			-webkit-transform: translate(0px,100px);
		}

		body::-webkit-scrollbar-track {
			-webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
			background-color: #F5F5F5;
			border-radius: 10px;
		}
		
		body::-webkit-scrollbar {
			width: 10px;
			background-color: #F5F5F5;
		}
		
		body::-webkit-scrollbar-thumb {
			border-radius: 10px;
			background-color: #bb3563;
		}
		
		body::-webkit-scrollbar-thumb:active {
			background-color: #ff0058;
		}		
		
		</style>';
}

function wrapButton($string)
{
	$id = uniqid();
	return '<button id="' . $id . '" onclick="copyToClipboard(\'' . $id . '\')">' . $string . '</button>';
}

function script()
{
	echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>';
	echo '
		<script>
		async function copyToClipboard(selector) {
			var text = document.getElementById(selector).textContent;
			
			navigator.clipboard.writeText(text).then(function() {
				if (jQuery(".messages-wrapper").css("display") === "none") {
					jQuery(".messages-wrapper").fadeIn().delay(2000).fadeOut();
					jQuery(".messages-wrapper").addClass("animate-down");
					setTimeout(function() {
						jQuery(".messages-wrapper").removeClass("animate-down");
					}, 3000);
					

				}
				
  				console.log(\'Async: Copying to clipboard was successful!\');
			}, function(err) {
  				console.error(\'Async: Could not copy text: \', err);
			});
		  
		}

		function fileTypeFilter(filetype) {
			filetypeText = filetype.textContent;

			if (filetypeText !== "all") {
				var query = ".row:not(." + filetypeText + ")";
				jQuery(query).hide();
				jQuery(".filetype-wrapper > button").removeClass("selected");
				jQuery("." + filetypeText).fadeIn();
				jQuery(filetype).addClass("selected");
			} else {
				jQuery(".row").fadeIn();
				jQuery(".filetype-wrapper > button").removeClass("selected");
			}
		}

		var hiddenItems = [];

		jQuery(document).ready(function() {
			jQuery(".index").click(function() {
				var tempItem = jQuery(this).parent().parent();
				hiddenItems.push(tempItem);
				tempItem.fadeOut();
				jQuery(".show-hidden-items").show().html(hiddenItems.length);
				jQuery(".show-hidden-items-label").show();
			});
		});

		function showHiddenItems(button) {
			hiddenItems.forEach(element => element.fadeIn());
			hiddenItems = [];
			button.textContent = "0";
			button.style.display = "none";
			jQuery(".show-hidden-items-label").hide();
		}
		</script>
		';
}

function getFileTypesHTML($filetypes)
{
	$filetypes_output = '';

	foreach ($filetypes as &$filetype) {
		$filetypes_output .= '<button onclick="fileTypeFilter(this)">' . $filetype . '</button>';
	}

	return $filetypes_output;
}
