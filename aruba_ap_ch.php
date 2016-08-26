<?php
if (!isset($_GET["post"])) {
	echo "<h1>Aruba AP static channels via CSV file</h1>";
	echo "<form action='?post=1' method='post' enctype='multipart/form-data'>";
	echo "<input type='file' name='csv' />";
	echo "<br />(column field names should be on the first row)";
	echo "<br />Field AP name: <input type='text' name='ap_name' value='AP name' />";
	echo "<br />Field 5GHz channel: <input type='text' name='ch5' value='5GHz channel' />";
        echo "<br />Field 5GHz tx power: <input type='text' name='tx5' value='5GHz tx power' />";
        echo "<br />Field 5GHz gain: <input type='text' name='gain5' value='5GHz gain' />";
        echo "<br />Field 2.4GHz channel: <input type='text' name='ch2' value='2.4GHz channel' />";
        echo "<br />Field 2.4GHz tx power: <input type='text' name='tx2' value='2.4GHz tx power' />";
        echo "<br />Field 2.4GHz gain: <input type='text' name='gain2' value='2.4GHz gain' />";

	echo "<br />ARM profile for static channel assignment: <input type='text' name='arm_prof' value='arm-{$_GET["prefix"]}static' />";
        echo "<br />Radio profile for 802.11g airmonitor: <input type='text' name='11g_airmon' value='dot11g-airmon' />";
        echo "<br />Radio profile for 802.11a airmonitor: <input type='text' name='11a_airmon' value='dot11a-airmon' />";
	echo "<br />Comma separator: <input type='text' name='separator' value=';' />";

	echo "<br /><br /><input type='submit' value='OK' />";
	echo "</form>";
}
else {
	if ($_POST["arm_prof"] == "") {
		echo "Please enter an ARM profile value.";
		exit;
	}
 	$in = file_get_contents($_FILES['csv']['tmp_name']);
	$arm_prof = $_POST["arm_prof"];
	$in = explode("\n",$in);

	$tmp = explode($_POST["separator"], $in[0]);
	$col_names = array("ap_name","ch5","tx5","gain5","ch2","tx2","gain2");
	$col_pos = array();
	$i = 0;
	foreach ($tmp as $col) {
		foreach ($col_names as $cname) {
			if ($_POST[$cname] == trim($col)) {
				$col_pos[$cname] = $i;
			}
		}
		$i++;
	}

	$i = 0;

	foreach ($in as $line) {
		if ($i == 0) {
			$i++;
			continue;
		}

		$tmp = explode($_POST["separator"],$line);

		$name = $tmp[$col_pos["ap_name"]];
		$ch5 = $tmp[$col_pos["ch5"]];
		$tx5 = $tmp[$col_pos["tx5"]];
		$gain5 = $tmp[$col_pos["gain5"]];
		$ch2 = $tmp[$col_pos["ch2"]];
		$tx2 = $tmp[$col_pos["tx2"]];
		$gain2 = $tmp[$col_pos["gain2"]];

		if (intval($ch5) >= 36) {
			$tx5 = intval($tx5) + intval($gain5);

			echo nl2br('rf dot11a-radio-profile "ch' . $ch5 . '-' . $tx5 . 'dbm"
   channel ' . $ch5 . '
   tx-power ' . $tx5 . '
   dot11h
   csa
   beacon-period 300
   arm-profile "' . $arm_prof . '"
!
ap-name "' . $name . '"
   dot11a-radio-profile "ch' . $ch5 . '-' . $tx5 . 'dbm"
!

');
		}
		if (trim($ch5) == "airmon") {
			echo nl2br('ap-name "' . $name . '"
   dot11a-radio-profile "' . $_POST["11a_airmon"] . '"
!

');
		}

		if (intval($ch2) >= 1) {
			$tx2 = intval($tx2) + intval($gain2);

			echo nl2br('rf dot11g-radio-profile "ch' . $ch2 . '-' . $tx2 . 'dbm"
   very-high-throughput-rates-enable
   channel ' . $ch2 . '
   tx-power ' . $tx2 . '
   dot11h
   csa
   beacon-period 300
   no dot11b-protection
   arm-profile "' . $arm_prof . '"
!
ap-name "' . $name . '"
   dot11g-radio-profile "ch' . $ch2 . '-' . $tx2 . 'dbm"
!

');
		}

                if (trim($ch2) == "airmon") {
                        echo nl2br('ap-name "' . $name . '"
   dot11g-radio-profile "' . $_POST["11g_airmon"] . '"
!

');
                }


		$i++;
	}
}
?>
