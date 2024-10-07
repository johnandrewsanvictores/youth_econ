<?php

require '../includes/config.php';
require '../models/Business_model.php';

$businessModel = new BusinessModel($connection);
$businessFields = $businessModel->getBusinessFields();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['title_field'])) {

        $title_field = $_POST['title_field'];
        $selected_icon = $_POST['selected_icon'];

        $response = $businessModel->addBusinessField($title_field, $selected_icon);
        $businessFields = $businessModel->getBusinessFields();

        echo $response;
        return $response;
    }

    if (isset($_POST['field-id'])) {
        $businessModel->removeBusinessField($_POST['field-id']);
    }
}
