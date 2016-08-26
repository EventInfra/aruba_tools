<?php
if (!isset($_POST["mac"])) {
	echo "<h1>Aruba Wired MAC to BSSID's</h1>";
        echo "<form method='post'>Enter wired MAC: <input type='text' name='mac' />";
        echo "<br />BSSID's per radio: <input type='text' name='num_bssid' value='16' maxlength='2' size='2' />";
        echo "<br /><input type='submit' /></form>";
}
else {
        if (!preg_match("/([a-fA-F0-9]{2}[:|\-]?){6}/",$_POST["mac"])) {
                echo "Please enter a MAC address";
                echo "<p><a href=''>Back</a></p>";
                exit;
        }

        $num_bssid = intval($_POST['num_bssid']);

        if ($num_bssid <= 0 || $num_bssid > 32) {
                echo "Please enter number of BSSID's within the range of 1 to 32";
                echo "<p><a href=''>Back</a></p>";
                exit;
        }


        $mac = str_replace(":", "", $_POST["mac"]);

        $f1 = decbin(hexdec(substr($mac, 6, 6)))  . "0000";
        $f2 = decbin(bindec(substr($f1, 0, 8)) ^ 8);
        $out = bindec(substr($f2, 4, 4) . substr($f1, 8, strlen($f1)));

        $r0 = dechex($out);
        $r1 = dechex($out + $num_bssid);

        echo "Radio 0 Base MAC (2.4GHz): {$mac[0]}{$mac[1]}:{$mac[2]}{$mac[3]}:{$mac[4]}{$mac[5]}:{$r0[0]}{$r0[1]}:{$r0[2]}{$r0[3]}:{$r0[4]}{$r0[5]}<br />";
        echo "Radio 1 Base MAC (5GHz): {$mac[0]}{$mac[1]}:{$mac[2]}{$mac[3]}:{$mac[4]}{$mac[5]}:{$r1[0]}{$r1[1]}:{$r1[2]}{$r1[3]}:{$r1[4]}{$r1[5]}<br />";

        echo "<p><a href=''>Back</a></p>";
}
?>
