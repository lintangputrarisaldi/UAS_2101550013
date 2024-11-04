# UTS_2101550013

### Nama : Lintang Putra Risaldi
### Nim : 21.01.55.0013

## Alat yang Dibutuhkan
1. XAMPP (atau server web lain dengan PHP dan MySQL)
2. Text editor (misalnya Visual Studio Code, Notepad++, dll)
3. Postman

### 2. Membuat Database
```sql
CREATE TABLE vehicles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    brand text(15) NOT NULL,
    model text(15) NOT NULL,
    year int(10) NOT NULL,
    price INT NOT NULL
);

INSERT INTO vehicles (id,brand,model,year,price) VALUES
(‘1’,’Kawasaki’,’Ninja SS’,’2015’,’28000000’),
(‘2,Yamaha,’Rx-King’,’2004’,’20000000’);
```
### 3. 	Membuat File Di File C
Membuat folder di dalam C-Xampp-Htdock- vehicles.php
Program yang ada pada vehicles.php
```php
<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, modelization, X-Requested-With");

$method = $_SERVER['REQUEST_METHOD'];
$request = [];

if (isset($_SERVER['PATH_INFO'])) {
    $request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
}

function getConnection() {
    $host = 'localhost';
    $db   = 'vehicles';
    $user = 'root';
    $pass = ''; // Ganti dengan password MySQL Anda jika ada
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    try {
        return new PDO($dsn, $user, $pass, $options);
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
}

function response($status, $data = NULL) {
    header("HTTP/1.1 " . $status);
    if ($data) {
        echo json_encode($data);
    }
    exit();
}

$db = getConnection();

switch ($method) {
    case 'GET':
        if (!empty($request) && isset($request[0])) {
            $id = $request[0];
            $stmt = $db->prepare("SELECT * FROM vehicles WHERE id = ?");
            $stmt->execute([$id]);
            $vehicles = $stmt->fetch();
            if ($vehicles) {
                response(200, $vehicles);
            } else {
                response(404, ["message" => "vehicles not found"]);
            }
        } else {
            $stmt = $db->query("SELECT * FROM vehicles");
            $vehicles = $stmt->fetchAll();
            response(200, $vehicles);
        }
        break;
    
    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        if (!isset($data->brand) || !isset($data->model) || !isset($data->year)) {
            response(400, ["message" => "Missing required fields"]);
        }
        $sql = "INSERT INTO vehicles (brand, model, price, year) VALUES (?, ?, ?)";
        $stmt = $db->prepare($sql);
        if ($stmt->execute([$data->brand, $data->model, $data->price, $data->year])) {
            response(201, ["message" => "vehicles created", "id" => $db->lastInsertId()]);
        } else {
            response(500, ["message" => "Failed to create vehicles"]);
        }
        break;
    
    case 'PUT':
        if (empty($request) || !isset($request[0])) {
            response(400, ["message" => "vehicles ID is required"]);
        }
        $id = $request[0];
        $data = json_decode(file_get_contents("php://input"));
        if (!isset($data->brand) || !isset($data->model) || !isset($data->year)) {
            response(400, ["message" => "Missing required fields"]);
        }
        $sql = "UPDATE vehicles SET brand = ?, model = ?, price = ?, year = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        if ($stmt->execute([$data->brand, $data->model, $data->price, $data->year, $id])) {
            response(200, ["message" => "vehicles updated"]);
        } else {
            response(500, ["message" => "Failed to update vehicles"]);
        }
        break;
    
    case 'DELETE':
        if (empty($request) || !isset($request[0])) {
            response(400, ["message" => "vehicles ID is required"]);
        }
        $id = $request[0];
        $sql = "DELETE FROM vehicles WHERE id = ?";
        $stmt = $db->prepare($sql);
        if ($stmt->execute([$id])) {
            response(200, ["message" => "vehicles deleted"]);
        } else {
            response(500, ["message" => "Failed to delete vehicles"]);
        }
        break;
    
    default:
        response(405, ["message" => "Method not allowed"]);
        break;
}
?>
```
### 4.	Hasil untuk menampilkan seluruh data
GET -> `http://localhost/vehicles/vehicles.php`
### 5. Hasil untuk menampilkan mengambil 1 data
GET -> `http://localhost/vehicles/vehicles.php/1`
### 6. Hasil Untuk POST
POST -> `http://localhost/vehicles/vehicles.php`
### 7. Hasil untuk PUT
PUT -> `http://localhost/vehicles/vehicles.php`
### 8. Hasil untuk delete
DELETE -> `http://localhost/vehicles/vehicles.php`
