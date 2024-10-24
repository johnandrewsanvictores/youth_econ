<?php

require '../includes/config.php';
// require '../models/Business_model.php';
require '../models/Worker_model.php';

$workerModel = new WorkerModel($connection);
// $businessFields = $workerModel->getBusinessFields();


if (isset($_POST['action']) && $_POST['action'] == "datatableDisplay") {
    $search_v = $_POST['search']['value'];
    $selected_job = $_POST['selected_job'] == 'all' ? null : $_POST['selected_job'];
    $output = $workerModel->getWorkersDataTable($selected_job);

    echo $output;
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //get jobs

    if (isset($_POST['action']) && $_POST['action'] == 'getJobs') {
        $workerModel = new WorkerModel($connection);
        $jobs = $workerModel->getJob();
        $response = array(["success" => true, "data" => $jobs]);
        echo json_encode($response);
        return json_encode($response);
    }

    //adding job field
    if (isset($_POST['action']) && $_POST['action'] == 'addJob') {

        $title_field = $_POST['title_job'];
        $field_id = $_POST['field'];

        $response = $workerModel->addJob($title_field, $field_id);
        $jobs = $workerModel->getJob();

        echo $response;
        return $response;
    }

    //removing job field
    if (isset($_POST['job-id'])) {
        $response = $workerModel->removeJob($_POST['job-id']);

        echo $response;
        return $response;
    }

    // //get specific business data
    // if (isset($_POST['action']) && $_POST['action'] == "get_specific_data") {
    //     $id = $_POST['id'];

    //     // Fetch business data
    //     $response = $businessModel->getBusinesses(['id' => $id]);

    //     // Decode the JSON response from getBusinesses()
    //     $responseArray = json_decode($response, true);

    //     // Check if the business data was retrieved successfully
    //     if ($responseArray['success']) {
    //         // Fetch social media data
    //         $socialMedia = $businessModel->getSocialMedia($id);

    //         // Decode the JSON response from getSocialMedia()
    //         $socialMediaArray = $socialMedia;

    //         // Merge social media data into the business data
    //         if ($socialMediaArray['success']) {
    //             $responseArray['data']['social_media'] = $socialMediaArray['data'];
    //         } else {
    //             // If no social media links are found, you can add an empty array or leave it as is
    //             $responseArray['data']['social_media'] = [];
    //         }

    //         // Update the message to indicate that both business and social media data were retrieved
    //         $responseArray['message'] = "Business and social media data retrieved";
    //     }

    //     // Encode the merged response back to JSON
    //     $mergedResponse = json_encode($responseArray);

    //     // Output the final response
    //     echo $mergedResponse;
    //     return $mergedResponse;
    // }

    // //updating business
    // if (isset($_POST['action']) && $_POST['action'] == "updateBusiness") {
    //     $id = $_POST['business_id'];
    //     $old_logo = $_POST['old_img_src'];

    //     $name = $_POST['business_name'];
    //     $field_id = $_POST['field'];
    //     $contact_number = $_POST['bus_contact_num'];
    //     $description = $_POST['description'];
    //     $location = $_POST['location'];

    //     $fb = $_POST['facebook'];
    //     $ig = $_POST['instagram'];
    //     $tt = $_POST['tiktok'];

    //     if ($_FILES['logo-img']['name']) {
    //         $uploadDir = '../uploads/logo/'; // Ensure this directory exists

    //         // Get the original file extension
    //         $fileExtension = pathinfo($_FILES['logo-img']['name'], PATHINFO_EXTENSION);

    //         // Generate a unique file name using a combination of a timestamp and the original file extension
    //         $uniqueFilename = uniqid('logo_', true) . '.' . $fileExtension;
    //         $targetFile = $uploadDir . $uniqueFilename;

    //         if (move_uploaded_file($_FILES['logo-img']['tmp_name'], $targetFile)) {
    //             $logo = $targetFile;

    //             if (file_exists($old_logo)) {
    //                 unlink($old_logo); // Delete the image file
    //             }
    //         }
    //     } else {
    //         $logo = $old_logo;
    //     }


    //     $response = $businessModel->updateBusinessData($id, $logo, $name, $field_id, $contact_number, $description, $location, $fb, $ig, $tt);
    //     echo $response;
    //     return $response;
    // }

    // //removing business/es
    if (isset($_POST['action']) && $_POST['action'] === 'remove' && isset($_POST['ids'])) {
        $response = $workerModel->removeWorkerData($_POST['ids']);

        echo $response;
        return $response;
    }

    //adding worker
    if (isset($_POST['action']) && $_POST['action'] == "addWorker") {
        $uploadDir = '../uploads/worker_img/'; // Ensure this directory exists

        // Get the original file extension
        $fileExtension = pathinfo($_FILES['profile-pic']['name'], PATHINFO_EXTENSION);

        // Generate a unique file name using a combination of a timestamp and the original file extension
        $uniqueFilename = uniqid('worker', true) . '.' . $fileExtension;
        $targetFile = $uploadDir . $uniqueFilename;

        if (move_uploaded_file($_FILES['profile-pic']['tmp_name'], $targetFile)) {
            $name = $_POST['worker_name'];
            $age = $_POST['age'];
            $job_ids = $_POST['jobs'];
            $worker_contact_num = $_POST['worker_contact_num'];
            $educ_level = $_POST['educ_level'];
            $intro = $_POST['intro'];
            $fb = $_POST['facebook'];
            $email = $_POST['email'];

            $response = $workerModel->addWorkerData($targetFile, $name, $age, $job_ids, $worker_contact_num, $intro, $educ_level, $email, $fb);

            echo $response;
            return $response;
        }
    }
}
