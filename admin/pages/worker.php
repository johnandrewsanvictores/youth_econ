<?php
require '../../includes/config.php';
require '../../models/Business_model.php';
require '../../models/Worker_model.php';

$workerModel = new WorkerModel($connection);
$jobs = $workerModel->getJob();

$businessModel = new BusinessModel($connection);
$businessFields = $businessModel->getBusinessFields();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="../../global.css">
    <link rel="stylesheet" href="../css/worker.css">
    <title>Business</title>
</head>

<body>
    <?php include('../../includes/admin_nav.php'); ?>

    <div class="main">
        <div class="main-content">
            <h2>Worker Information</h2>
            <div class="content">
                <div class="custom-control">
                    <div>
                        <div class="action-btn">
                            <button id="worker-new-btn">
                                <i class="fas fa-plus"></i>
                                <span>New</span>
                            </button>
                            <button id="edit-btn">
                                <i class="fas fa-pencil-alt"></i>
                                <span>Edit</span>
                            </button>
                            <button id="remove-btn">
                                <i class="fas fa-trash"></i>
                                <span>Remove</span>
                            </button>

                            <button id="view-btn">
                                <i class="far fa-eye"></i>
                                <span>View</span>
                            </button>
                        </div>
                        <div class='selection-control'>

                            <button id="selectAll-btn">
                                <span>Select All</span>
                            </button>
                            <button id="deselect-btn">
                                <span>Deselect</span>
                            </button>
                        </div>
                    </div>

                    <div class="custom-filter-control">
                        <label for="field">Field</label>
                        <div class="field-select">
                            <select name="field">
                                <option value="all" selected>All</option>
                                <?php
                                $businessModel = new BusinessModel($connection);
                                $businessFields = $businessModel->getBusinessFields();

                                if ($businessFields) {
                                    foreach ($businessFields as $field) {
                                        $id = htmlspecialchars($field['id']);
                                        $title = htmlspecialchars($field['title']);

                                        echo "<option value='$id'>$title</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                </div>

            </div>
            <div class="table-div">
                <table id="example" class="display stripe" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Job</th>
                            <th>Contact Number</th>
                            <th>Email</th>
                            <th>FB Account</th>
                        </tr>
                    </thead>
                    <tbody>


                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Job</th>
                            <th>Contact Number</th>
                            <th>Email</th>
                            <th>FB Account</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="job-section">
            <div class="job-head">
                <h2>Job</h2>
                <button id="job-new-btn">
                    <i class="fas fa-plus"></i>
                    <span>New</span>
                </button>
            </div>

            <div class="job-body">
                <table id="theme-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Field</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        if ($jobs) {
                            foreach ($jobs as $job) {
                                $id = htmlspecialchars($job['id']);
                                $title = htmlspecialchars($job['job_title']);
                                $bs_field = htmlspecialchars($job['field_title']);
                                echo "
                                    <tr>
                                    <td>$title</td>
                                    <td>$bs_field</td>
                                    <td><button class='remove-job-btn' id='$id'>
                                            <i class='fas fa-trash'></i>
                                        </button></td></tr>
                                    ";
                            }
                        } else {
                            echo '<tr><td colspan="3">No jobs found.</td></tr>';
                        }
                        ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php include('../../includes/modals/job_modal.php') ?>
    <?php include('../../includes/modals/worker_form_modal.php') ?>

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>

    <script src="../js/job.js"></script>
    <script src="../js/worker.js"></script>

    <?php include('../../includes/footer.php'); ?>
</body>

</html>

<?php
$connection = null;
?>