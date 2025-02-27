<?php
require_once '../includes/config.php';
require_once '../includes/Auth.php';
require_once '../includes/Container.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ' . BASE_URL . 'login.php');
    exit();
}

$container = new Container($conn);
$containers = $container->getAllContainers();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Containers - <?php echo SITE_TITLE; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>assets/css/admin.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php include 'sidebar.php'; ?>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Manage Containers</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addContainerModal">
                            <i class="bi bi-plus"></i> Add Container
                        </button>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form id="filterForm" class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Size</label>
                                <select class="form-select" name="size">
                                    <option value="">All Sizes</option>
                                    <option value="small">Small (5x5)</option>
                                    <option value="medium">Medium (10x10)</option>
                                    <option value="large">Large (10x20)</option>
                                    <option value="xlarge">Extra Large (20x20)</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="">All Status</option>
                                    <option value="available">Available</option>
                                    <option value="occupied">Occupied</option>
                                    <option value="maintenance">Maintenance</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Max Price</label>
                                <input type="number" class="form-control" name="max_price" min="0" step="0.01">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary d-block">Apply Filters</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Containers Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Size</th>
                                        <th>Location</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($containers as $container): ?>
                                    <tr>
                                        <td>#<?php echo $container['id']; ?></td>
                                        <td><?php echo htmlspecialchars($container['size']); ?></td>
                                        <td><?php echo htmlspecialchars($container['location']); ?></td>
                                        <td>£<?php echo number_format($container['price'], 2); ?></td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                echo $container['status'] === 'available' ? 'success' : 
                                                    ($container['status'] === 'occupied' ? 'warning' : 'secondary');
                                            ?>">
                                                <?php echo ucfirst($container['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-primary"
                                                        onclick="editContainer(<?php echo htmlspecialchars(json_encode($container)); ?>)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger"
                                                        onclick="deleteContainer(<?php echo $container['id']; ?>)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <!-- Add Container Modal -->
    <div class="modal fade" id="addContainerModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Container</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addContainerForm">
                        <div class="mb-3">
                            <label class="form-label">Size</label>
                            <select class="form-select" name="size" required>
                                <option value="small">Small (5x5)</option>
                                <option value="medium">Medium (10x10)</option>
                                <option value="large">Large (10x20)</option>
                                <option value="xlarge">Extra Large (20x20)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Location</label>
                            <input type="text" class="form-control" name="location" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Price (per month)</label>
                            <input type="number" class="form-control" name="price" min="0" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="available">Available</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveContainer()">Save Container</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Container Modal -->
    <div class="modal fade" id="editContainerModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Container</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editContainerForm">
                        <input type="hidden" name="id">
                        <div class="mb-3">
                            <label class="form-label">Size</label>
                            <select class="form-select" name="size" required>
                                <option value="small">Small (5x5)</option>
                                <option value="medium">Medium (10x10)</option>
                                <option value="large">Large (10x20)</option>
                                <option value="xlarge">Extra Large (20x20)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Location</label>
                            <input type="text" class="form-control" name="location" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Price (per month)</label>
                            <input type="number" class="form-control" name="price" min="0" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="available">Available</option>
                                <option value="occupied">Occupied</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="updateContainer()">Update Container</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Filter form submission
        document.getElementById('filterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const params = new URLSearchParams();
            
            for (const [key, value] of formData.entries()) {
                if (value) params.append(key, value);
            }
            
            window.location.href = 'containers.php?' + params.toString();
        });

        // Add container
        function saveContainer() {
            const form = document.getElementById('addContainerForm');
            const formData = new FormData(form);
            
            fetch('api/container.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while saving the container');
            });
        }

        // Edit container
        function editContainer(container) {
            const form = document.getElementById('editContainerForm');
            form.elements['id'].value = container.id;
            form.elements['size'].value = container.size;
            form.elements['location'].value = container.location;
            form.elements['price'].value = container.price;
            form.elements['status'].value = container.status;
            form.elements['description'].value = container.description;
            
            new bootstrap.Modal(document.getElementById('editContainerModal')).show();
        }

        // Update container
        function updateContainer() {
            const form = document.getElementById('editContainerForm');
            const formData = new FormData(form);
            const containerId = formData.get('id');
            
            fetch(`api/container.php?id=${containerId}`, {
                method: 'PUT',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the container');
            });
        }

        // Delete container
        function deleteContainer(id) {
            if (confirm('Are you sure you want to delete this container?')) {
                fetch(`api/container.php?id=${id}`, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the container');
                });
            }
        }
    </script>
</body>
</html>
