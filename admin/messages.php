<?php
require_once '../includes/config.php';
require_once '../includes/Auth.php';
require_once '../includes/Contact.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ' . BASE_URL . 'login.php');
    exit();
}

$contact = new Contact($conn);
$messages = $contact->getAllMessages();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Messages - <?php echo SITE_TITLE; ?></title>
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
                    <h1 class="h2">Contact Messages</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="exportMessages()">
                                <i class="bi bi-download"></i> Export
                            </button>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown">
                                <i class="bi bi-filter"></i> Filter
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="?status=all">All Messages</a></li>
                                <li><a class="dropdown-item" href="?status=unread">Unread</a></li>
                                <li><a class="dropdown-item" href="?status=read">Read</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Messages Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($messages as $message): ?>
                                    <tr class="<?php echo $message['status'] === 'unread' ? 'table-active' : ''; ?>">
                                        <td>#<?php echo $message['id']; ?></td>
                                        <td><?php echo htmlspecialchars($message['name']); ?></td>
                                        <td><?php echo htmlspecialchars($message['email']); ?></td>
                                        <td><?php echo htmlspecialchars($message['subject']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                echo $message['status'] === 'unread' ? 'primary' : 'secondary';
                                            ?>">
                                                <?php echo ucfirst($message['status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($message['created_at'])); ?></td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-primary"
                                                        onclick="viewMessage(<?php echo $message['id']; ?>)">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <?php if ($message['status'] === 'unread'): ?>
                                                <button type="button" class="btn btn-outline-success"
                                                        onclick="markAsRead(<?php echo $message['id']; ?>)">
                                                    <i class="bi bi-check"></i>
                                                </button>
                                                <?php endif; ?>
                                                <button type="button" class="btn btn-outline-danger"
                                                        onclick="deleteMessage(<?php echo $message['id']; ?>)">
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

    <!-- Message Details Modal -->
    <div class="modal fade" id="messageDetailsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Message Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="messageDetails">
                        <!-- Message details will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="replyToMessage()">
                        <i class="bi bi-reply"></i> Reply
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // View message details
        function viewMessage(id) {
            fetch(`api/contact.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const message = data.message;
                        document.getElementById('messageDetails').innerHTML = `
                            <div class="mb-4">
                                <h6>From</h6>
                                <p>
                                    ${message.name}<br>
                                    <a href="mailto:${message.email}">${message.email}</a>
                                </p>
                            </div>
                            <div class="mb-4">
                                <h6>Subject</h6>
                                <p>${message.subject}</p>
                            </div>
                            <div class="mb-4">
                                <h6>Message</h6>
                                <p>${message.message}</p>
                            </div>
                            <div>
                                <small class="text-muted">
                                    Received: ${new Date(message.created_at).toLocaleString()}
                                </small>
                            </div>
                        `;
                        new bootstrap.Modal(document.getElementById('messageDetailsModal')).show();

                        // If message is unread, mark it as read
                        if (message.status === 'unread') {
                            markAsRead(id, false);
                        }
                    } else {
                        alert('Error loading message: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while loading the message');
                });
        }

        // Mark message as read
        function markAsRead(id, reload = true) {
            fetch(`api/contact.php?id=${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    status: 'read'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && reload) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the message');
            });
        }

        // Delete message
        function deleteMessage(id) {
            if (confirm('Are you sure you want to delete this message?')) {
                fetch(`api/contact.php?id=${id}`, {
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
                    alert('An error occurred while deleting the message');
                });
            }
        }

        // Reply to message
        function replyToMessage() {
            const email = document.querySelector('#messageDetails a').getAttribute('href').replace('mailto:', '');
            window.location.href = `mailto:${email}`;
        }

        // Export messages
        function exportMessages() {
            const status = new URLSearchParams(window.location.search).get('status') || 'all';
            window.location.href = `api/export-messages.php?status=${status}`;
        }
    </script>
</body>
</html>
