<?php
if (!isset($_GET["post"])) {
	echo "<h1>Aruba AP provisioning via CSV file</h1>";
        echo "<form action='?post=1' method='post' enctype='multipart/form-data'>";
        echo "<input type='file' name='csv' />";
        echo "<br />(column field names should be on the first row)";
        echo "<br />Field AP name: <input type='text' name='ap_name' value='CINR' />";
        echo "<br />Field MAC adres: <input type='text' name='mac_addr' value='Macadres' />";
        echo "<br />Field AP group: <input type='text' name='ap_group' value='AP group' />";

        echo "<br />Comma separator: <input type='text' name='separator' value=';' />";

        echo "<br /><br /><input type='submit' value='OK' />";
        echo "</form>";
}
else {

        $in = file_get_contents($_FILES['csv']['tmp_name']);
	$in = explode("\n",$in);

        $tmp = explode($_POST["separator"], $in[0]);
        $col_names = array("ap_name","ap_group","mac_addr");
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
		$mac = str_replace(":", "", str_replace("-", "", str_replace(".", "", strtolower($tmp[$col_pos["mac_addr"]]))));
		if ($mac == "") continue;

		$mac = "{$mac[0]}{$mac[1]}:{$mac[2]}{$mac[3]}:{$mac[4]}{$mac[5]}:{$mac[6]}{$mac[7]}:{$mac[8]}{$mac[9]}:{$mac[10]}{$mac[11]}";

		$name = trim($tmp[$col_pos["ap_name"]]);
		$group = trim($tmp[$col_pos["ap_group"]]);
		if ($group == "") continue;

	        echo nl2br('clear provisioning-ap-list
provision-ap
read-bootinfo ap-name ' . $mac . '
copy-provisioning-params ap-name ' . $mac . '
ap-name ' . $name . '
ap-group ' . $group . '
reprovision ap-name ' . $mac . '

');

		$i++;
	}

}
