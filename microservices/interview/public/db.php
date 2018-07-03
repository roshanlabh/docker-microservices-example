<?php
####################
# Connection mysql_connect deprecated, but you may still use in some projects
$link = mysql_connect('db', 'rlabh', 'rlabh@123');
if (!$link) {
    die('Not connected: ' . mysql_error());
}
mysql_select_db('slimapp', $link);
$result = mysql_query('SELECT * FROM interview');
if (!$result) {
    die('Invalid query: ' . mysql_error());
} else
    echo "<br />result: ".$result;

// $q = mysql_query("INSERT INTO `interview` (`cid`, `status`, `rating`, `notes`) VALUES ('1', '1', '3', 'Just test 1.'), ('2', '1', '4', 'Just test 2.'), ('3', '1', '5', 'Just test 3.')") or die(mysql_error());
echo "<br />Query result: ".$q;
echo 'Connected with mysql_connect<br />';
mysql_close($link);

####################
# Connection mysqli as http://php.net/manual/pt_BR/mysqli.construct.php
$mysqli = new mysqli('db', 'rlabh', 'rlabh@123', 'slimapp');
/*
 * This is the “official” OO way to do it,
 * BUT $connect_error was broken until PHP 5.2.9 and 5.3.0.
 */
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

/*
 * Use this instead of $connect_error if you need to ensure
 * compatibility with PHP versions prior to 5.2.9 and 5.3.0.
 */
if (mysqli_connect_error()) {
    die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
}
echo 'Success with mysqli connection at … ' . $mysqli->host_info;
$mysqli->close();

try {
    $dbh = new PDO('mysql:host=db;dbname=slimapp', 'rlabh', 'rlabh@123');
    foreach($dbh->query('SELECT * FROM interview') as $row) {
        echo "<pre>"; print_r($row); echo "</pre>";
    }
    $dbh = null;
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

# Show PHP informations
# look that we extensions are installed
phpinfo();
?>