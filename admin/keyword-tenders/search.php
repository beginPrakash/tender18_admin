<?php
$conn = new mysqli("localhost","root","","your_db");

$term = $_GET['term'];

$result = $conn->query("
    SELECT id, name 
    FROM keywords 
    WHERE name LIKE '%".$conn->real_escape_string($term)."%' 
    group by name order by name ASC
");

$data = [];

while($row = $result->fetch_assoc()){
    $data[] = [
        'id' => $row['id'],
        'name' => $row['name']
    ];
}

echo json_encode($data);
