# Index
```php
        <?php
        session_start();
        
        // Cek apakah user sudah login
        if(!isset($_SESSION['user_id'])) {
            header("Location: login.php");
            exit();
        }
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <brand>Daftar Motor</brand>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        
        <body>
        <div class="container mt-3 text-center">
            <div class="card">
            <div class="card-body">
        <div class="card-title"><h2>Welcome, <?php echo $_SESSION['name']; ?>!</h2></div>
        <div class="card-text"><p>username : <?php echo $_SESSION['username']; ?></p></div>
        <div ><a href="logout.php"><button type="button" class="col-sm-4 btn btn-outline-secondary" data-bs-dismiss="modal">Logout</button></a></div>
        </div>
        </div>
        </div>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <brand>Daftar Motor</brand>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                .btn-group-action {
                    white-space: nowrap;
                }
            </style>
        </head>
        <body class="container py-4">
            <h1>Daftar Motor</h1>
            
            <div class="row mb-3">
                <div class="col">
                    <input type="text" id="searchInput" class="form-control" placeholder="Cari berdasarkan ID">
                </div>
                <div class="col-auto">
                    <button onclick="searchvehicle()" class="btn btn-primary">Cari</button>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#vehicleModal">
                        Tambah Motor
                    </button>
                </div>
            </div>
        
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Brand</th>
                        <th>Model</th>
                        <th>year</th>
                        <th>price</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="vehicleList">
                </tbody>
            </table>
        
            <!-- Modal for Add/Edit vehicle -->
            <div class="modal fade" id="vehicleModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-brand" id="modalbrand">Baru</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="vehicleForm">
                                <input type="hidden" id="vehicleId">
                                <div class="mb-3">
                                    <label for="brand" class="form-label">Brand</label>
                                    <input type="text" class="form-control" id="brand" required>
                                </div>
                                <div class="mb-3">
                                    <label for="model" class="form-label">Model</label>
                                    <input type="text" class="form-control" id="model" required>
                                </div>
                                <div class="mb-3">
                                    <label for="year" class="form-label">Year</label>
                                    <input type="number" class="form-control" id="year" required>
                                </div>
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price</label>
                                    <input type="number" class="form-control" id="price" required>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="button" class="btn btn-primary" onclick="savevehicle()">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
            <script>
                const API_URL = 'http://localhost/vehicles/vehicles.php';
                let vehicleModal;
        
                document.addEventListener('DOMContentLoaded', function() {
                    vehicleModal = new bootstrap.Modal(document.getElementById('vehicleModal'));
                    loadvehicles();
                });
        
                function loadvehicles() {
                    fetch(API_URL)
                        .then(response => response.json())
                        .then(vehicles => {
                            const vehicleList = document.getElementById('vehicleList');
                            vehicleList.innerHTML = '';
                            vehicles.forEach(vehicles => {
                                vehicleList.innerHTML += `
                                    <tr>
                                        <td>${vehicles.id}</td>
                                        <td>${vehicles.brand}</td>
                                        <td>${vehicles.model}</td>
                                        <td>${vehicles.year}</td>
                                         <td>${vehicles.price}</td>
                                        <td class="btn-group-action">
                                            <button class="btn btn-sm btn-warning me-1" onclick="editvehicle(${vehicles.id})">Edit</button>
                                            <button class="btn btn-sm btn-danger" onclick="deletevehicle(${vehicles.id})">Hapus</button>
                                        </td>
                                    </tr>
                                `;
                            });
                        })
                        .catch(error => alert('Error loading vehicles: ' + error));
                }
        
                function searchvehicle() {
                    const id = document.getElementById('searchInput').value;
                    if (!id) {
                        loadvehicles();
                        return;
                    }
                    
                    fetch(`${API_URL}/${id}`)
                        .then(response => response.json())
                        .then(vehicles => {
                            const vehicleList = document.getElementById('vehicleList');
                            if (vehicles.message) {
                                alert('vehicle not found');
                                return;
                            }
                            vehicleList.innerHTML = `
                                <tr>
                                    <td>${vehicles.id}</td>
                                    <td>${vehicles.brand}</td>
                                    <td>${vehicles.model}</td>
                                    <td>${vehicles.year}</td>
                                    <td>${vehicles.price}</td>
                                    <td class="btn-group-action">
                                        <button class="btn btn-sm btn-warning me-1" onclick="editvehicle(${vehicles.id})">Edit</button>
                                        <button class="btn btn-sm btn-danger" onclick="deletevehicle(${vehicles.id})">Hapus</button>
                                    </td>
                                </tr>
                            `;
                        })
                        .catch(error => alert('Error searching vehicles: ' + error));
                }
        
                function editvehicle(id) {
                    fetch(`${API_URL}/${id}`)
                        .then(response => response.json())
                        .then(vehicles => {
                            document.getElementById('vehicleId').value = vehicles.id;
                            document.getElementById('brand').value = vehicles.brand;
                            document.getElementById('model').value = vehicles.model;
                            document.getElementById('year').value = vehicles.year;
                            document.getElementById('price').value = vehicles.price;
                            document.getElementById('modalbrand').textContent = 'Edit Motor';
                            vehicleModal.show();
                        })
                        .catch(error => alert('Error loading vehicles details: ' + error));
                }
        
                function deletevehicle(id) {
                    if (confirm('Are you sure you want to delete this vehicles?')) {
                        fetch(`${API_URL}/${id}`, {
                            method: 'DELETE'
                        })
                        .then(response => response.json())
                        .then(data => {
                            alert('vehicle deleted successfully');
                            loadvehicles();
                        })
                        .catch(error => alert('Error deleting vehicles: ' + error));
                    }
                }
        
                function savevehicle() {
                    const vehicleId = document.getElementById('vehicleId').value;
                    const vehicleData = {
                        brand: document.getElementById('brand').value,
                        model: document.getElementById('model').value,
                        year: document.getElementById('year').value,
                        price: document.getElementById('price').value
                    };
        
                    const method = vehicleId ? 'PUT' : 'POST';
                    const url = vehicleId ? `${API_URL}/${vehicleId}` : API_URL;
        
                    fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(vehicleData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(vehicleId ? 'vehicle updated successfully' : 'vehicle added successfully');
                        vehicleModal.hide();
                        loadvehicles();
                        resetForm();
                    })
                    .catch(error => alert('Error saving vehicles: ' + error));
                }
        
                function resetForm() {
                    document.getElementById('vehicleId').value = '';
                    document.getElementById('vehicleForm').reset();
                    document.getElementById('modalbrand').textContent = 'Tambah Motor';
                }
        
                // Reset form when modal is closed
                document.getElementById('vehicleModal').addEventListener('hidden.bs.modal', resetForm);
            </script>
                        </body>
            <!-- Modal Tambah Motor -->
            <div class="modal fade" id="adddbModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-brand">Tambah Motor Baru</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="adddbForm">
                                <!-- <div class="mb-3">
                                    <label class="form-label">Id</label>
                                    <input type="text" name="brand" class="form-control" required>
                                </div> -->
                                <div class="mb-3">
                                    <label class="form-label">Brand</label>
                                    <input type="text" name="brand" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">model</label>
                                    <input type="text" name="model" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">year</label>
                                    <input type="year" name="year" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">price</label>
                                    <input type="text" name="price" class="form-control" required>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="button" class="btn btn-primary" onclick="adddb()">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            <script>
            function adddb() {
                const form = document.getElementById('adddbForm');
                const formData = new FormData(form);
                const data = {
                    brand: formData.get('brand'),
                    model: formData.get('model'),
                    year: parseInt(formData.get('year')),
                    price: formData.get('price')    
                };
        
                fetch('http://localhost/Vehicles/vehicles.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    if(data.message === "motor created") {
                        alert('Motor berhasil ditambahkan!');
                        window.location.reload();
                    } else {
                        alert('Gagal menambahkan Motor: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Error: ' + error.message);
                });
            }
            </script>
        </body>
        </html>
```
