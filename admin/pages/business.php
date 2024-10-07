<?php
require '../../includes/config.php';
require '../../models/Business_model.php';

$businessModel = new BusinessModel($connection);
$businessFields = $businessModel->getBusinessFields();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_field_btn'])) {

        $title_field = $_POST['title_field'];
        $selected_icon = $_POST['selected_icon'];
        if (empty($title_field) || empty($selected_icon)) {
            echo '<script>alert("Title and icon is required")</script>';
        } else {
            $response = $businessModel->addBusinessField($title_field, $selected_icon);
            $businessFields = $businessModel->getBusinessFields();
        }
    }

    if (isset($_POST['field-id'])) {
        $businessModel->removeBusinessField($_POST['field-id']);
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="../../global.css">
    <link rel="stylesheet" href="../css/business.css">
    <title>Business</title>
</head>

<body>
    <?php include('../../includes/admin_nav.php'); ?>

    <div class="main">
        <div class="main-content">
            <h2>Business Information</h2>
            <div class="content">
                <div class="custom-control">
                    <div>
                        <div class="action-btn">
                            <button id="bus-new-btn">
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
                        <div class="select">
                            <select>
                                <option value="1">Americano</option>
                                <option value="2">Latte</option>
                                <option value="3">Green Tea</option>
                            </select>
                        </div>
                    </div>

                </div>

            </div>
            <div class="table-div">
                <table id="example" class="display stripe nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Field</th>
                            <th>Location</th>
                            <th>Contact Number</th>
                            <th>Social Media</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>fsfdsfd</td>
                            <td>fsfdsfd</td>
                            <td>fsfdsfd</td>
                            <td>fsfdsfd</td>
                            <td>fsfdsfd</td>
                        </tr>

                        <tr>
                            <td>fsfdsfd</td>
                            <td>fsfdsfd</td>
                            <td>fsfdsfd</td>
                            <td>fsfdsfd</td>
                            <td>fsfdsfd</td>
                        </tr>

                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Name</th>
                            <th>Field</th>
                            <th>Location</th>
                            <th>Contact Number</th>
                            <th>Social Media</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="field-section">
            <div class="field-head">
                <h2>Field</h2>
                <button id="field-new-btn">
                    <i class="fas fa-plus"></i>
                    <span>New</span>
                </button>
            </div>

            <div class="field-body">
                <table id="theme-table">
                    <thead>
                        <tr>
                            <th>Icon</th>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        if ($businessFields) {
                            foreach ($businessFields as $field) {
                                $id = htmlspecialchars($field['id']);
                                $icon = htmlspecialchars($field['icon']);
                                $title = htmlspecialchars($field['title']);
                                echo "
                                    <tr>
                                    <td>
                                        <img src='$icon' alt='icon'>
                                    </td>
                                    <td>$title</td>
                                    <td><button class='remove-field-btn' id='$id'>
                                            <i class='fas fa-trash'></i>
                                        </button></td></tr>
                                    ";
                            }
                        } else {
                            echo '<tr><td colspan="3">No business fields found.</td></tr>';
                        }
                        ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php include('../../includes/modals/field_modal.php') ?>

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>
    <script>
        new DataTable('#example', {
            scrollX: true,
        });
    </script>

    <script src="../js/business.js"></script>

    <?php include('../../includes/footer.php'); ?>
</body>

</html>

<?php
$connection = null;
?>