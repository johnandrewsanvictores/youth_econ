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

    //get specific business data
    if (isset($_POST['action']) && $_POST['action'] == "get_specific_data") {
        $id = (int)$_POST['id'];

        // Fetch business data
        $response = $workerModel->getWorkers(['worker_id' => $id]);
        // $response = $workerModel->getWorkers($filters = ['name' => 'j', 'education_level' => 'undergraduate'], $orderBy = 'worker.name', $orderDir = 'DESC', $limit = -1, $offset = 0);

        echo $response;
        return $response;
    }

    // //removing worker/s
    if (isset($_POST['action']) && $_POST['action'] === 'remove' && isset($_POST['ids'])) {
        $response = $workerModel->removeWorkerData($_POST['ids']);

        echo $response;
        return $response;
    }

    //adding worker
    if (isset($_POST['action']) && $_POST['action'] == "addWorker") {
        $uploadDir = 'uploads/worker_img/'; // Ensure this directory exists

        // Get the original file extension
        $fileExtension = pathinfo($_FILES['profile-pic']['name'], PATHINFO_EXTENSION);

        // Generate a unique file name using a combination of a timestamp and the original file extension
        $uniqueFilename = uniqid('worker', true) . '.' . $fileExtension;
        $targetFile = $uploadDir . $uniqueFilename;

        if (move_uploaded_file($_FILES['profile-pic']['tmp_name'], '../' . $targetFile)) {
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

    //updating worker

    if (isset($_POST['action']) && $_POST['action'] == "updateWorker") {
        $worker_id = $_POST['worker_id'];
        $old_profile_pic = $_POST['old_img_src'];

        $name = $_POST['worker_name'];
        $age = $_POST['age'];
        $job_ids = $_POST['jobs'];
        $worker_contact_num = $_POST['worker_contact_num'];
        $educ_level = $_POST['educ_level'];
        $intro = $_POST['intro'];
        $fb = $_POST['facebook'];
        $email = $_POST['email'];

        if ($_FILES['profile-pic']['name']) {
            $uploadDir = 'uploads/worker_img/'; // Ensure this directory exists

            // Get the original file extension
            $fileExtension = pathinfo($_FILES['profile-pic']['name'], PATHINFO_EXTENSION);

            // Generate a unique file name using a combination of a timestamp and the original file extension
            $uniqueFilename = uniqid('worker', true) . '.' . $fileExtension;
            $targetFile = $uploadDir . $uniqueFilename;

            if (move_uploaded_file($_FILES['profile-pic']['tmp_name'], '../' . $targetFile)) {
                $profile_pic = $targetFile;

                if (file_exists('../' . $old_profile_pic)) {
                    unlink('../' . $old_profile_pic); // Delete the image file
                }
            }
        } else {
            $profile_pic = $old_profile_pic;
        }


        $response = $workerModel->updateWorkerData($worker_id, $profile_pic, $name, $age, $job_ids, $worker_contact_num, $intro, $educ_level, $email, $fb);
        echo $response;
        return $response;
    }
}
