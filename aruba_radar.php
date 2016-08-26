<?php
if (!isset($_POST["input"])) {
	echo "<form action='aruba_radar.php' method='post'><textarea name='input'></textarea><input type='submit' value='OK' /></form>";
}
else {
	$total = 0;
	$aps = array();
	$channels = array();
	$type_id = array();
	$hours = array();
	$tmp = explode("\n",$_POST["input"]);

	foreach ($tmp as $line) {
		if (preg_match("/([0-9]+)(\:)([0-9]+)(\:)([0-9]+)(.+)(\|AP )((.+))(\@)(.+)(Radar detected on interface wifi0\, channel )([0-9]+)(\, typeid )([0-9]+)/i", $line, $out)) {
			$total++;
			$aps[$out[8]]++;
			$channels[$out[13]]++;
			$type_id[$out[15]]++;
			$hours[$out[1]]++;
		}
	}

	echo "<h1>Total</h1>" . $total . "<br />";

	arsort($aps);
	arsort($channels);
	arsort($type_id);
	arsort($hours);

	echo "<h1>AP's</h1><table><col width='200' />";
	foreach ($aps as $field => $count) {
		echo "<tr><td>{$field}</td><td>{$count}</td></tr>";
	}
	echo "</table>";

        echo "<h1>Channels</h1><table><col width='200' />";
        foreach ($channels as $field => $count) {
                echo "<tr><td>{$field}</td><td>{$count}</td></tr>";
        }
        echo "</table>";

        echo "<h1>Typeid's</h1><table><col width='200' />";
        foreach ($type_id as $field => $count) {
                echo "<tr><td>{$field}</td><td>{$count}</td></tr>";
        }
        echo "</table>";

        echo "<h1>Hours</h1><table><col width='200' />";
        foreach ($hours as $hour => $count) {
                echo "<tr><td>{$hour}</td><td>{$count}</td></tr>";
        }
        echo "</table>";


}
