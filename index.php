<?php

// get the HTTP method, path and body of the request
$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
$input = json_decode(file_get_contents('php://input'),true);
 
// connect to the mysql database
$dsn = 'mysql:dbname=i_spy;host=localhost';
$user = 'i_spy';
$password = 'asn_hmin205_2017';

try {
    $dbh = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    echo 'Connexion échouée : ' . $e->getMessage();
}
 
// retrieve the table and key from the path
$table = preg_replace('/[^a-z0-9_]+/i','',array_shift($request));
$key = array_shift($request)+0;
 
// escape the columns and values from the input object
$columns = preg_replace('/[^a-z0-9_]+/i','',array_keys($input));
$values = array_values($input);
 
// build the SET part of the SQL command
$set = '';
$param = array();
for ($i=0;$i<count($columns);$i++) {
    $set.=($i>0?',':'').'`'.$columns[$i].'`=';
    $set.=($values[$i]===null?'NULL':':'.$columns[$i]);
    $param[':'.$columns[$i]] = $values[$i];
}
 
// create SQL based on HTTP method
switch ($method) {
case 'GET':
    $sql = "select * from `$table`".($key?" WHERE ".$table."_id=$key":''); break;
case 'PUT':
    $sql = "update `$table` set $set where id=$key"; break;
case 'POST':
    $sql = "insert into `$table` set $set"; break;
case 'DELETE':
    $sql = "delete `$table` where id=$key"; break;
}
 
// excecute SQL statement
$stmt = $dbh->prepare($sql);

var_dump($sql);die;

// die if SQL statement failed
if ($stmt->execute($param)) {
    http_response_code(500);
} else {
 
    // print results, insert id or affected row count
    if ($method == 'GET') {
    	echo json_encode($stmt->fetchAll());
    } elseif ($method == 'POST') {
        echo PDO::lastInsertId();
        
        // http created ressource status
        http_response_code(201);
    }
}
