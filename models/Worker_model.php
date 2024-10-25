<?php
class WorkerModel
{
    private $pdo;
    private $response;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->response = array();
    }

    // business field
    public function getJob($filter = null, $sortField = 'j.title', $sortOrder = 'ASC')
    {
        try {
            // Base query
            $query = "SELECT j.id, j.title AS job_title, b.title AS field_title 
            FROM job j 
            JOIN business_field b ON j.field_id = b.id";

            // Add filtering
            if (!empty($filter)) {
                $query .= " WHERE j.title = :filter";
            }

            // Add sorting
            $query .= " ORDER BY $sortField $sortOrder";

            // Prepare statement
            $stmt = $this->pdo->prepare($query);

            // Bind filter if used
            if (!empty($filter)) {
                $filterValue = '%' . $filter . '%';
                $stmt->bindParam(':filter', $filter, PDO::PARAM_STR);
            }

            // Execute and fetch data
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle error appropriately
            return [];
        }
    }

    public function addJob($title, $field_id)
    {
        try {
            if (empty($field_id) || empty($title)) {
                $this->response['success'] = false;
                $this->response['message'] = "Title and Business Field are required!";
                return json_encode($this->response);
                exit();
            }

            $existingFields = $this->getJob($title);
            if (!empty($existingFields)) {
                $this->response['success'] = false;
                $this->response['message'] = "The title already exists!";
                return json_encode($this->response);
            }

            // Set the PDO error mode to exception
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Prepare an SQL statement for execution
            $stmt = $this->pdo->prepare("INSERT INTO job (title, field_id) VALUES (:value1, :value2)");

            // Bind parameters to the prepared statement
            $stmt->bindParam(':value1', $title);
            $stmt->bindParam(':value2', $field_id);

            // Execute the prepared statement
            $stmt->execute();
            $lastId = $this->pdo->lastInsertId();

            // Retrieve the title from the business_field table using a JOIN
            $stmt = $this->pdo->prepare("
            SELECT j.title AS job_title, b.title AS field_title 
            FROM job j 
            JOIN business_field b ON j.field_id = b.id 
            WHERE j.id = :lastId
        ");
            $stmt->bindParam(':lastId', $lastId);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $this->response['success'] = true;
                $this->response['message'] = "Job added successfully.";
                $this->response['last_added_data'] = array(
                    'job_title' => $result['job_title'],
                    'field_title' => $result['field_title'],
                    'id' => $lastId
                );
            }
        } catch (PDOException $e) {
            $this->response['success'] = false;
            $this->response['message'] = "Error adding Job: " . $e->getMessage();
        }

        return json_encode($this->response);
    }


    public function removeJob($id)
    {
        try {

            // Prepare the DELETE statement
            $stmt = $this->pdo->prepare("DELETE FROM job WHERE id = :id");

            // Bind the ID parameter
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            // Execute the statement
            $stmt->execute();

            $this->response['success'] = true;
            $this->response['message'] = "Job removed successfully.";
        } catch (PDOException $e) {
            $this->response['success'] = false;
            $this->response['message'] = "Error removing job"; //. $e->getMessage();

            if ($e->getCode() == 23000) { // Integrity constraint violation
                $this->response['message'] = "Cannot delete the job because it is still referenced in another table.";
            }
        }

        return json_encode($this->response);
    }




    public function addWorkerData(
        $profile_pic,
        $name,
        $age,
        $job_ids,
        $contact_number,
        $brief_intro,
        $education_level,
        $email,
        $fb
    ) {
        try {
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Check if the worker name already exists
            $nameCheckSql = "SELECT COUNT(*) FROM worker WHERE name = :name";
            $nameCheckStmt = $this->pdo->prepare($nameCheckSql);
            $nameCheckStmt->bindValue(':name', $name);
            $nameCheckStmt->execute();
            $exists = $nameCheckStmt->fetchColumn();

            if ($exists > 0) {
                return json_encode(['success' => false, 'message' => "The worker already exists!"]);
            }

            // Begin a transaction
            $this->pdo->beginTransaction();

            // Insert worker data into the worker table (without job_id)
            $workerSql = "INSERT INTO worker (profile_pic, name, contact_number, brief_intro, education_level, email, fb_account, age)
                          VALUES (:profile_pic, :name, :contact_number, :brief_intro, :education_level, :email, :fb_account, :age)";
            $workerStmt = $this->pdo->prepare($workerSql);
            $workerStmt->bindParam(':profile_pic', $profile_pic);
            $workerStmt->bindParam(':name', $name);
            $workerStmt->bindParam(':contact_number', $contact_number);
            $workerStmt->bindParam(':brief_intro', $brief_intro);
            $workerStmt->bindParam(':education_level', $education_level);
            $workerStmt->bindParam(':email', $email);
            $workerStmt->bindParam(':fb_account', $fb);
            $workerStmt->bindParam(':age', $age);
            $workerStmt->execute();

            // Get the last inserted worker ID
            $worker_id = $this->pdo->lastInsertId();

            // Insert the job_ids into the worker_job table
            $workerJobSql = "INSERT INTO worker_job (worker_id, job_id) VALUES (:worker_id, :job_id)";
            $workerJobStmt = $this->pdo->prepare($workerJobSql);
            $workerJobStmt->bindParam(':worker_id', $worker_id);

            foreach ($job_ids as $job_id) {
                $workerJobStmt->bindParam(':job_id', $job_id);
                $workerJobStmt->execute();
            }

            // Commit the transaction
            $this->pdo->commit();

            return json_encode(['success' => true, 'message' => "Worker data added successfully."]);
        } catch (PDOException $e) {
            // Rollback the transaction if something goes wrong
            $this->pdo->rollBack();
            return json_encode(['success' => false, 'message' => "Error adding worker: " . $e->getMessage()]);
        }
    }



    public function getWorkers($filters = [], $orderBy = 'worker.name', $orderDir = 'ASC', $limit = -1, $offset = 0)
    {
        try {
            // Set PDO error mode to exception
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Base SQL query with necessary joins
            $sql = "SELECT 
                worker.id AS worker_id,
                worker.profile_pic,
                worker.age,
                worker.fb_account,
                worker.brief_intro,
                worker.name,
                GROUP_CONCAT(DISTINCT job.id SEPARATOR ',') AS job_ids, 
                GROUP_CONCAT(DISTINCT job.title SEPARATOR ', ') AS job_titles,
                business_field.id AS field_id,
                business_field.title AS field_title,
                worker.contact_number,
                worker.email,
                worker.education_level
            FROM 
                worker
            LEFT JOIN 
                worker_job ON worker.id = worker_job.worker_id
            LEFT JOIN 
                job ON worker_job.job_id = job.id
            LEFT JOIN 
                business_field ON job.field_id = business_field.id"; // Join to get the field title

            // Initialize an array to hold WHERE clauses and parameters
            $whereClauses = [];
            $params = [];

            // Add filtering based on passed filters (dynamic WHERE clause)
            foreach ($filters as $column => $value) {
                // Check if the value is intended for a LIKE comparison
                if (is_string($value) && in_array($column, ['name', 'field_title'])) {
                    $whereClauses[] = "$column LIKE :$column";
                    $params[":$column"] = '%' . $value . '%'; // Wrap value with wildcards for LIKE
                } elseif ($column === 'job_id') {
                    // Special handling for job_id
                    $params[":job_id"] = $value;
                } else {
                    // Add to WHERE clause for exact matches
                    $whereClauses[] = "$column = :$column";
                    $params[":$column"] = $value; // Correctly bind the value
                }
            }

            // Add WHERE clause if there are any filters
            if (!empty($whereClauses)) {
                $sql .= " WHERE " . implode(" AND ", $whereClauses);
            }

            // Group by worker.id to avoid duplicate rows for workers with multiple jobs
            $sql .= " GROUP BY worker.id";

            // Add HAVING clause to filter workers by job_id, ensuring all jobs are returned
            if (isset($params[":job_id"])) {
                $sql .= " HAVING FIND_IN_SET(:job_id, job_ids) > 0"; // Check if the job_id is in the concatenated job_ids
            }

            // Add ORDER BY clause
            $sql .= " ORDER BY $orderBy $orderDir";

            // Add LIMIT and OFFSET for pagination
            if ($limit !== null && $limit != -1) {
                $sql .= " LIMIT :limit OFFSET :offset";
            }

            // Prepare the SQL statement
            $stmt = $this->pdo->prepare($sql);

            // Bind parameters for filters
            foreach ($params as $param => $value) {
                $stmt->bindValue($param, $value);
            }

            // Bind limit and offset if they are set
            if ($limit !== null && $limit != -1) {
                $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
                $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
            }

            // Execute the statement
            $stmt->execute();

            // Fetch all the results
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Return the result set
            $this->response['success'] = true;
            $this->response['message'] = "Retrieved";
            $this->response['data'] = $results;

            return json_encode($this->response);
        } catch (PDOException $e) {
            // Handle any errors
            $this->response['success'] = false;
            $this->response['message'] = "Error retrieving workers: " . $e->getMessage();
            return json_encode($this->response);
        }
    }





    public function getWorkersDataTable($selected_job)
    {
        try {
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Base SQL query
            $sql = "SELECT 
                    worker.id,
                    worker.name,
                    worker.age,
                    GROUP_CONCAT(job.title SEPARATOR ', ') AS job_titles,
                    worker.contact_number,
                    worker.email,
                    worker.education_level
                FROM 
                    worker
                LEFT JOIN 
                    worker_job ON worker.id = worker_job.worker_id
                LEFT JOIN 
                    job ON worker_job.job_id = job.id";

            $whereClauses = [];
            $params = [];

            // Search filter
            if (isset($_POST['search']['value']) && $_POST['search']['value'] != '') {
                $search_value = $_POST['search']['value'];
                $whereClauses[] = "(worker.name LIKE :search OR job.title LIKE :search)";
                $params[':search'] = "%$search_value%";
            }

            // Filter by search or job
            if (!empty($whereClauses)) {
                $sql .= " WHERE " . implode(" AND ", $whereClauses);
            }

            // Group by worker.id to aggregate job titles
            $sql .= " GROUP BY worker.id";

            // Filter by selected job using HAVING to ensure all jobs for the worker are still displayed
            if (isset($selected_job)) {
                $sql .= " HAVING SUM(CASE WHEN worker_job.job_id = :selected_job THEN 1 ELSE 0 END) > 0";
                $params[':selected_job'] = $selected_job;
            }

            // Count total records
            $countSql = "SELECT COUNT(*) FROM (" . $sql . ") AS total";
            $countStmt = $this->pdo->prepare($countSql);
            foreach ($params as $param => $value) {
                $countStmt->bindValue($param, $value);
            }
            $countStmt->execute();
            $totalRecords = $countStmt->fetchColumn();

            // Sorting
            if (isset($_POST['order'])) {
                $columns = [
                    0 => 'worker.id',
                    1 => 'worker.name',
                    2 => 'worker.age',
                    3 => 'job_titles',
                    4 => 'worker.contact_number',
                    5 => 'worker.email',
                    6 => 'worker.education_level',
                ];
                $column_name = $_POST['order'][0]['column'];
                $order = $_POST['order'][0]['dir'];
                $sql .= " ORDER BY " . $columns[$column_name] . " " . $order;
            } else {
                $sql .= " ORDER BY worker.name ASC";
            }

            // Pagination
            if ($_POST['length'] != -1) {
                $limit = $_POST['length'];
                $offset = $_POST['start'];
                $sql .= " LIMIT :limit OFFSET :offset";
            }

            // Prepare the statement
            $stmt = $this->pdo->prepare($sql);

            // Bind all parameters
            foreach ($params as $param => $value) {
                $stmt->bindValue($param, $value);
            }

            // Bind pagination parameters
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

            // Execute the query
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Return the data
            $output = [
                'draw' => intval($_POST['draw']),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $results,
                'sql' => $sql // Optional for debugging purposes
            ];

            return json_encode($output);
        } catch (PDOException $e) {
            $this->response['success'] = false;
            $this->response['message'] = "Error retrieving workers: " . $e->getMessage();
            return json_encode($this->response);
        }
    }


    public function removeWorkerData($ids)
    {
        // Check if any IDs were provided

        $ids = json_decode($ids);
        if (empty($ids)) {
            $this->response['success'] = false;
            $this->response['message'] = "No IDs provided.";
            return json_encode($this->response);
        }

        // Prepare array and placeholders for query
        $placeholders = rtrim(str_repeat('?,', count($ids)), ','); // Create placeholders

        try {
            // Begin transaction for safety in case of failure
            $this->pdo->beginTransaction();

            // Step 1: Select profile pictures to remove
            $selectProfilePicsSql = "SELECT profile_pic FROM worker WHERE id IN ($placeholders)";
            $selectStmt = $this->pdo->prepare($selectProfilePicsSql);
            $selectStmt->execute($ids); // Execute with the IDs array

            // Fetch and delete profile pictures
            while ($row = $selectStmt->fetch(PDO::FETCH_ASSOC)) {
                if (!empty($row['profile_pic']) && file_exists($row['profile_pic'])) {
                    unlink($row['profile_pic']); // Delete profile picture file if exists
                }
            }

            // Step 2: Remove entries from worker_job table (to maintain referential integrity)
            $deleteWorkerJobsSql = "DELETE FROM worker_job WHERE worker_id IN ($placeholders)";
            $deleteWorkerJobsStmt = $this->pdo->prepare($deleteWorkerJobsSql);
            $deleteWorkerJobsStmt->execute($ids); // Execute with the IDs array

            // Step 3: Remove workers from the worker table
            $deleteWorkersSql = "DELETE FROM worker WHERE id IN ($placeholders)";
            $deleteWorkersStmt = $this->pdo->prepare($deleteWorkersSql);
            $deleteWorkersStmt->execute($ids); // Execute with the IDs array

            // Step 4: Check if the workers were successfully removed
            if ($deleteWorkersStmt->rowCount() > 0) {
                // Commit transaction since all deletions succeeded
                $this->pdo->commit();

                $this->response['success'] = true;
                $this->response['message'] = "Worker(s) removed successfully.";
            } else {
                // Rollback transaction if no rows were deleted
                $this->pdo->rollBack();
                $this->response['success'] = false;
                $this->response['message'] = "No workers were removed.";
            }
        } catch (PDOException $e) {
            // Rollback transaction in case of any error
            $this->pdo->rollBack();
            $this->response['success'] = false;
            $this->response['message'] = "Error removing worker: " . $e->getMessage();
        }

        return json_encode($this->response);
    }


    public function updateWorkerData(
        $worker_id,
        $profile_pic,
        $name,
        $age,
        $job_ids,
        $contact_number,
        $brief_intro,
        $education_level,
        $email,
        $fb
    ) {
        try {
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Check if the worker exists
            $workerCheckSql = "SELECT COUNT(*) FROM worker WHERE id = :worker_id";
            $workerCheckStmt = $this->pdo->prepare($workerCheckSql);
            $workerCheckStmt->bindValue(':worker_id', $worker_id);
            $workerCheckStmt->execute();
            $exists = $workerCheckStmt->fetchColumn();

            if ($exists == 0) {
                return json_encode(['success' => false, 'message' => "The worker does not exist!"]);
            }

            // Begin a transaction
            $this->pdo->beginTransaction();

            // Update worker data in the worker table
            $workerSql = "UPDATE worker 
                          SET profile_pic = :profile_pic,
                              name = :name,
                              contact_number = :contact_number,
                              brief_intro = :brief_intro,
                              education_level = :education_level,
                              email = :email,
                              fb_account = :fb_account,
                              age = :age
                          WHERE id = :worker_id";
            $workerStmt = $this->pdo->prepare($workerSql);
            $workerStmt->bindParam(':profile_pic', $profile_pic);
            $workerStmt->bindParam(':name', $name);
            $workerStmt->bindParam(':contact_number', $contact_number);
            $workerStmt->bindParam(':brief_intro', $brief_intro);
            $workerStmt->bindParam(':education_level', $education_level);
            $workerStmt->bindParam(':email', $email);
            $workerStmt->bindParam(':fb_account', $fb);
            $workerStmt->bindParam(':age', $age);
            $workerStmt->bindParam(':worker_id', $worker_id);
            $workerStmt->execute();

            // Update job associations in the worker_job table
            // First, remove all current job associations
            $deleteJobsSql = "DELETE FROM worker_job WHERE worker_id = :worker_id";
            $deleteJobsStmt = $this->pdo->prepare($deleteJobsSql);
            $deleteJobsStmt->bindParam(':worker_id', $worker_id);
            $deleteJobsStmt->execute();

            // Insert the new job_ids into the worker_job table
            $workerJobSql = "INSERT INTO worker_job (worker_id, job_id) VALUES (:worker_id, :job_id)";
            $workerJobStmt = $this->pdo->prepare($workerJobSql);
            $workerJobStmt->bindParam(':worker_id', $worker_id);

            foreach ($job_ids as $job_id) {
                $workerJobStmt->bindParam(':job_id', $job_id);
                $workerJobStmt->execute();
            }

            // Commit the transaction
            $this->pdo->commit();

            return json_encode(['success' => true, 'message' => "Worker data updated successfully."]);
        } catch (PDOException $e) {
            // Rollback the transaction if something goes wrong
            $this->pdo->rollBack();
            return json_encode(['success' => false, 'message' => "Error updating worker: " . $e->getMessage()]);
        }
    }
}
