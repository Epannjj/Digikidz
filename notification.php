<style>
    /* Styles for notification */
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 5px;
        color: white;
        font-weight: 500;
        max-width: 300px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        z-index: 1000;
        animation: slideIn 0.3s ease-out forwards, fadeOut 0.5s ease-out 3s forwards;
        display: flex;
        align-items: center;
    }

    .notification-success {
        background-color: #4CAF50;
    }

    .notification-error {
        background-color: #f44336;
    }

    .notification-icon {
        margin-right: 10px;
        font-size: 18px;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
        }

        to {
            opacity: 0;
            visibility: hidden;
        }
    }
</style>

<?php
// Function to show notification
function showNotification($message, $type = 'success')
{
    $icon = ($type == 'success') ? '✓' : '✗';
    echo "<div class='notification notification-{$type}'>";
    echo "<span class='notification-icon'>{$icon}</span>";
    echo $message;
    echo "</div>";
}

// Session messages system
if (!isset($_SESSION['notifications'])) {
    $_SESSION['notifications'] = [];
}

// Function to set notification for next page load
function setNotification($message, $type = 'success')
{
    $_SESSION['notifications'][] = [
        'message' => $message,
        'type' => $type
    ];
}

// Display any stored notifications
if (!empty($_SESSION['notifications'])) {
    foreach ($_SESSION['notifications'] as $notification) {
        showNotification($notification['message'], $notification['type']);
    }
    // Clear notifications after displaying
    $_SESSION['notifications'] = [];
}
?>

<!-- Add this script at the bottom of your page -->
<script>
    // Auto-remove notifications after 3.5 seconds
    document.addEventListener('DOMContentLoaded', function () {
        const notifications = document.querySelectorAll('.notification');
        notifications.forEach(notification => {
            setTimeout(() => {
                notification.remove();
            }, 3500);
        });
    });
</script>