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
    public function getJob($filter = null, $sortField = 'id', $sortOrder = 'ASC')
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
}
