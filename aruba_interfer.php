<?php
if (!isset($_POST["input"])) {
	echo "<form action='aruba_interfer.php' method='post'><textarea name='input'></textarea><input type='submit' value='OK' /></form>";
}
else {
	$total = 0;
	$aps = array();
	$channels = array();
	$type_id = array();
	$tmp = explode("\n",$_POST["input"]);

	foreach ($tmp as $line) {
		if (preg_match("/(\|AP )((.+))(\@)(.+)(ARM Channel Interference Trigger new )/i", $line, $out)) {
			$total++;
			$aps[$out[2]]++;
		}
	}

	echo "<h1>Total</h1>" . $total . "<br />";

	arsort($aps);
	arsort($channels);
	arsort($type_id);

	echo "<h1>AP's</h1><table><col width='200' />";
	foreach ($aps as $field => $count) {
		echo "<tr><td>{$field}</td><td>{$count}</td></tr>";
	}
	echo "</table>";

}
