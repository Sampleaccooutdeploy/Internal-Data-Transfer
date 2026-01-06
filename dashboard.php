<?php
require_once 'auth.php';
requireLogin();

// Convert bytes to readable format
function formatBytes($bytes, $precision = 2)
{
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}

// Get files from JSON
$files = [];
if (file_exists(METADATA_FILE)) {
    $json = file_get_contents(METADATA_FILE);
    $files = json_decode($json, true) ?? [];
}

// Check for session messages
$msg = $_SESSION['upload_message'] ?? '';
$msgType = $_SESSION['upload_message_type'] ?? '';
unset($_SESSION['upload_message']);
unset($_SESSION['upload_message_type']);

// Map simple types to toast styles
$toastClass = ($msgType == 'success') ? 'text-bg-success' : 'text-bg-danger';
$toastTitle = ($msgType == 'success') ? 'Success' : 'Error';
$toastIcon = ($msgType == 'success') ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill';

require_once 'includes/header.php';
?>

<!-- content wrapper -->
<div class="row g-4">
    <!-- SECTION A: UPLOAD -->
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="card-title fw-bold text-primary mb-0">
                    <i class="bi bi-cloud-arrow-up-fill me-2"></i>Upload Files
                </h5>
            </div>
            <div class="card-body">
                <!-- FORM updated for Multiple Files -->
                <form id="uploadForm" action="upload.php" method="POST" enctype="multipart/form-data"
                    class="row align-items-center g-3">
                    <div class="col-md-9">
                        <label class="form-label text-muted small fw-bold">SELECT FILES</label>
                        <!-- Added 'multiple' and changed name to 'files[]' -->
                        <input type="file" name="files[]" multiple class="form-control" required>
                        <div class="form-text small">
                            Supported: PDF, DOC, JPG, PNG, ZIP. <strong>No File Size Limit. Multiple selection
                                allowed.</strong>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex justify-content-center">

                        <!-- ANIMATED BUTTON STRUCTURE -->
                        <div class="anim-wrapper">
                            <input type="checkbox" id="trigger">
                            <label for="trigger" class="anim-btn" id="animBtnLabel">
                                <div class="anim-stars"></div>
                                <div class="anim-background"></div>
                                <span class="anim-order">Upload All</span>
                                <span class="anim-done">Uploading...</span>
                                <div class="anim-car-container">
                                    <div class="anim-car-part1"></div>
                                    <div class="anim-car-part2"></div>
                                    <div class="anim-wheels"></div>
                                    <div class="anim-details"></div>
                                </div>
                                <div class="anim-package-container">
                                    <div class="anim-package"></div>
                                    <div class="anim-package-details"></div>
                                    <span class="anim-package-text">FILES</span>
                                </div>
                            </label>
                        </div>
                        <!-- End Animated Button -->

                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- SECTION B: FILE LIST -->
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title fw-bold text-secondary mb-0">
                    <i class="bi bi-folder-fill me-2"></i>Uploaded Files
                </h5>
                <span class="badge bg-primary rounded-pill"><?php echo count($files); ?> Files</span>
            </div>
            <div class="card-body p-0">
                <?php if (empty($files)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox display-4 text-light-emphasis d-block mb-3"></i>
                        <p class="mb-0">No files uploaded yet.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">File Name</th>
                                    <th>Type</th>
                                    <th>Size</th>
                                    <th>Uploaded On</th>
                                    <th class="text-end pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($files as $file):
                                    $type = strtolower($file['type']);
                                    $canPreview = in_array($type, ['pdf', 'jpg', 'jpeg', 'png', 'gif']);
                                    ?>
                                    <tr>
                                        <td class="ps-4 fw-medium text-dark">
                                            <i class="bi bi-file-earmark-text me-2 text-primary"></i>
                                            <!-- Truncate long names but show full on hover -->
                                            <span title="<?php echo htmlspecialchars($file['original_name']); ?>">
                                                <?php echo htmlspecialchars(mb_strimwidth($file['original_name'], 0, 40, "...")); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-secondary border">
                                                <?php echo strtoupper($file['type']); ?>
                                            </span>
                                        </td>
                                        <td class="text-secondary small"><?php echo formatBytes($file['size']); ?></td>
                                        <td class="text-secondary small"><?php echo htmlspecialchars($file['uploaded_on']); ?>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="btn-group" role="group">
                                                <?php if ($canPreview): ?>
                                                    <button type="button" class="btn btn-sm btn-outline-info"
                                                        onclick="openPreview('<?php echo $file['id']; ?>', '<?php echo $file['original_name']; ?>', '<?php echo $type; ?>')"
                                                        title="Preview">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <button class="btn btn-sm btn-outline-secondary" disabled
                                                        title="Preview not available">
                                                        <i class="bi bi-eye-slash"></i>
                                                    </button>
                                                <?php endif; ?>

                                                <a href="download.php?id=<?php echo $file['id']; ?>"
                                                    class="btn btn-sm btn-success fw-bold" title="Download">
                                                    <i class="bi bi-download"></i> Download
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    onclick="confirmDelete('<?php echo $file['id']; ?>', '<?php echo htmlspecialchars(addslashes($file['original_name'])); ?>')"
                                                    title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- TOAST CONTAINER -->
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="statusToast" class="toast <?php echo $toastClass; ?> border-0" role="alert" aria-live="assertive"
        aria-atomic="true">
        <div class="toast-header">
            <i
                class="bi <?php echo $toastIcon; ?> me-2 text-<?php echo ($msgType == 'success') ? 'success' : 'danger'; ?>"></i>
            <strong class="me-auto text-dark"><?php echo $toastTitle; ?></strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body bg-white text-dark">
            <?php echo htmlspecialchars($msg); ?>
        </div>
    </div>
</div>

<!-- PREVIEW MODAL -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="height: 90vh;">
            <div class="modal-header">
                <h5 class="modal-title fs-5">
                    <i class="bi bi-eye me-2"></i>Preview: <span id="previewTitle" class="fw-bold"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 bg-light d-flex justify-content-center align-items-center overflow-hidden">
                <iframe id="previewFrame" src="" style="width: 100%; height: 100%; border: none;"
                    allowfullscreen></iframe>
                <img id="previewImage" src=""
                    style="max-width: 100%; max-height: 100%; display: none; object-fit: contain;">
            </div>
        </div>
    </div>
</div>

<!-- DELETE CONFIRMATION MODAL -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill me-2"></i>Confirm Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the file <strong id="deleteFileName"></strong>?</p>
                <p class="text-muted small mb-0">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary w-50" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger w-50">
                    <i class="bi bi-trash-fill me-2"></i>Delete
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    // Animated Button Logic
    const trigger = document.getElementById('trigger');
    const uploadForm = document.getElementById('uploadForm');

    if (trigger && uploadForm) {
        trigger.addEventListener('change', function () {
            if (this.checked) {
                const fileInput = uploadForm.querySelector('input[type="file"]');
                if (fileInput.files.length === 0) { // Check length for multiple support
                    alert('Please select at least one file!');
                    this.checked = false;
                    return;
                }
                setTimeout(() => {
                    uploadForm.submit();
                }, 2500);
            }
        });
    }

    // Toast Logic
    document.addEventListener('DOMContentLoaded', function () {
        <?php if ($msg): ?>
            const toastEl = document.getElementById('statusToast');
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        <?php endif; ?>
    });

    // Preview Modal Logic
    function openPreview(fileId, fileName, fileType) {
        const modal = new bootstrap.Modal(document.getElementById('previewModal'));
        const frame = document.getElementById('previewFrame');
        const img = document.getElementById('previewImage');
        const title = document.getElementById('previewTitle');
        const url = 'view_file.php?id=' + fileId;

        title.innerText = fileName;

        // Reset view
        frame.style.display = 'none';
        img.style.display = 'none';
        frame.src = '';
        img.src = '';

        if (['jpg', 'jpeg', 'png', 'gif'].includes(fileType)) {
            img.src = url;
            img.style.display = 'block';
        } else {
            frame.src = url;
            frame.style.display = 'block';
        }

        modal.show();
    }

    // Delete Confirmation Logic
    function confirmDelete(fileId, fileName) {
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        const nameSpan = document.getElementById('deleteFileName');
        const deleteBtn = document.getElementById('confirmDeleteBtn');

        nameSpan.innerText = fileName;
        deleteBtn.href = 'delete.php?id=' + fileId;

        modal.show();
    }
</script>

<?php require_once 'includes/footer.php'; ?>