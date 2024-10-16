<?php

require '../includes/config.php';
require '../models/Business_model.php';

$businessModel = new BusinessModel($connection);
$businessFields = $businessModel->getBusinessFields();



if (isset($_POST['action']) && $_POST['action'] == "datatableDisplay") {
    $search_v = $_POST['search']['value'];
    $selected_field = $_POST['selected_field'] == 'all' ? null : $_POST['selected_field'];
    $output = $businessModel->getBusinesses($selected_field);

    echo $output;
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //adding business field
    if (isset($_POST['title_field'])) {

        $title_field = $_POST['title_field'];
        $selected_icon = $_POST['selected_icon'];

        $response = $businessModel->addBusinessField($title_field, $selected_icon);
        $businessFields = $businessModel->getBusinessFields();

        echo $response;
        return $response;
    }

    //removing business field
    if (isset($_POST['field-id'])) {
        $response = $businessModel->removeBusinessField($_POST['field-id']);

        echo $response;
        return $response;
    }

    //adding business
    if (isset($_POST['business_name']) || empty($_POST['business_id'])) {
        $uploadDir = '../uploads/logo/'; // Ensure this directory exists

        // Get the original file extension
        $fileExtension = pathinfo($_FILES['logo-img']['name'], PATHINFO_EXTENSION);

        // Generate a unique file name using a combination of a timestamp and the original file extension
        $uniqueFilename = uniqid('logo_', true) . '.' . $fileExtension;
        $targetFile = $uploadDir . $uniqueFilename;

        if (move_uploaded_file($_FILES['logo-img']['tmp_name'], $targetFile)) {
            $name = $_POST['business_name'];
            $field = $_POST['field'];
            $bus_contact_num = $_POST['bus_contact_num'];
            $description = $_POST['description'];
            $location = $_POST['location'];

            $response = $businessModel->addBusinessData($targetFile, $name, $field, $bus_contact_num, $description, $location);

            echo $response;
            return $response;
        }
    }
}
