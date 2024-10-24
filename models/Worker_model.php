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
}
