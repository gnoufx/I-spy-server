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
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ); // le fetch() retourne des objets std par défaut
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Les erreurs sont traitées comme exception
	$dbh->exec("SET NAMES UTF8"); // Les échanges se font en UTF8
} catch (PDOException $e) {
    echo 'Connexion échouée : ' . $e->getMessage();die;
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
$colList = implode(', ', $columns);
$colListParam = ':' . implode(', :', $columns);
$valList = '"' . implode('", "', $values) . '"';

for ($i=0; $i<count($columns); $i++) {

    $set.=($i>0?',':'').'`'.$columns[$i].'`=';
    $set.=($values[$i]===null?'NULL':':'.$columns[$i]);
    $param[$columns[$i]] = $values[$i];

}
 
// create SQL based on HTTP method
switch ($method) {
	case 'GET':
	    $sql = "select * from `$table`".($key?" WHERE ".$table."_id=$key":'');
	    break;
	case 'PUT':
	    $sql = "update `$table` set $set where id=$key";
	    break;
	case 'POST':
	    $sql = "insert into $table($colList) values($colListParam)";
	    break;
	case 'DELETE':
	    $sql = "delete `$table` where id=$key";
	    break;
}
 
// excecute SQL statement
$stmt = $dbh->prepare($sql);

try{
	$stmt->execute($param);
}
catch(PDOException $e){
	if($e->errorInfo[1] == 1062){
		if($table == 'phone'){
			$stmt = $dbh->prepare("select * from phone where number = :number and password = :pass");
			$stmt->execute(array('number' => $param['number'], 'pass' => $param['password']));
			if(($res = $stmt->fetch())){
				echo json_encode(array('success' => true, 'data' => array('id' => $res->phone_id)));
			}
			else{
				echo json_encode(array('success' => false, 'message' => 'wrongPass'));
			}
		}
	}
	else{
		echo json_encode(array('success' => false, 'message' => 'Error'));
	}
	die;
}

if ($method == 'GET') {

	echo json_encode(array('success' => true, 'data' => $stmt->fetchAll()));

} elseif ($method == 'POST') {

    echo json_encode(array('success' => true, 'data' => array('id' => $dbh->lastInsertId())));
    
    // http created ressource status
    http_response_code(201);
}