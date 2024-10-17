<?php

require '../includes/config.php';
require '../models/Business_model.php';

$businessModel = new BusinessModel($connection);
$businessFields = $businessModel->getBusinessFields();



if (isset($_POST['action']) && $_POST['action'] == "datatableDisplay") {
    $search_v = $_POST['search']['value'];
    $selected_field = $_POST['selected_field'] == 'all' ? null : $_POST['selected_field'];
    $output = $businessModel->getBusinessesDataTable($selected_field);

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

    //get specific business data
    if (isset($_POST['action']) && $_POST['action'] == "get_specific_data") {
        $id = $_POST['id'];

        // Fetch business data
        $response = $businessModel->getBusinesses(['id' => $id]);

        // Decode the JSON response from getBusinesses()
        $responseArray = json_decode($response, true);

        // Check if the business data was retrieved successfully
        if ($responseArray['success']) {
            // Fetch social media data
            $socialMedia = $businessModel->getSocialMedia($id);

            // Decode the JSON response from getSocialMedia()
            $socialMediaArray = $socialMedia;

            // Merge social media data into the business data
            if ($socialMediaArray['success']) {
                $responseArray['data']['social_media'] = $socialMediaArray['data'];
            } else {
                // If no social media links are found, you can add an empty array or leave it as is
                $responseArray['data']['social_media'] = [];
            }

            // Update the message to indicate that both business and social media data were retrieved
            $responseArray['message'] = "Business and social media data retrieved";
        }

        // Encode the merged response back to JSON
        $mergedResponse = json_encode($responseArray);

        // Output the final response
        echo $mergedResponse;
        return $mergedResponse;
    }

    //updating business
    if (isset($_POST['action']) && $_POST['action'] == "updateBusiness") {
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
