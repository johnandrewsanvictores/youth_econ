<?php
// model/business_model.php

class BusinessModel
{
    private $pdo;
    private $response;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->response = array();
    }

    public function getBusinessFields($filter = null, $sortField = 'id', $sortOrder = 'ASC')
    {
        try {
            // Base query
            $query = "SELECT * FROM business_field";

            // Add filtering
            if (!empty($filter)) {
                $query .= " WHERE title LIKE :filter";
            }

            // Add sorting
            $query .= " ORDER BY $sortField $sortOrder";

            // Prepare statement
            $stmt = $this->pdo->prepare($query);

            // Bind filter if used
            if (!empty($filter)) {
                $filterValue = '%' . $filter . '%';
                $stmt->bindParam(':filter', $filterValue, PDO::PARAM_STR);
            }

            // Execute and fetch data
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle error appropriately
            return [];
        }
    }

    public function addBusinessField($title, $icon)
    {
        try {
            if (empty($icon) || empty($title)) {
                $this->response['success'] = false;
                $this->response['message'] = "Title and Icon is required!";
                return json_encode($this->response);
                exit();
            }

            // Use getBusinessFields to check if the title already exists
            $existingFields = $this->getBusinessFields($title);
            if (!empty($existingFields)) {
                $this->response['success'] = false;
                $this->response['message'] = "The title already exists!";
                return json_encode($this->response);
            }


            // Set the PDO error mode to exception
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Prepare an SQL statement for execution
            $stmt = $this->pdo->prepare("INSERT INTO business_field (icon, title) VALUES (:value1, :value2)");

            // Bind parameters to the prepared statement
            $stmt->bindParam(':value1', $icon);
            $stmt->bindParam(':value2', $title);

            // Execute the prepared statement
            $stmt->execute();
            $lastId = $this->pdo->lastInsertId();

            $this->response['success'] = true;
            $this->response['message'] = "Business field added successfully.";
            $this->response['last_added_data'] = array('title' => $title, 'icon' => $icon, 'id' => $lastId);
        } catch (PDOException $e) {
            $this->response['success'] = false;
            $this->response['message'] = "Error adding Time: " . $e->getMessage();
        }

        return json_encode($this->response);
    }

    public function removeBusinessField($id)
    {
        try {

            // Prepare the DELETE statement
            $stmt = $this->pdo->prepare("DELETE FROM business_field WHERE id = :id");

            // Bind the ID parameter
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            // Execute the statement
            $stmt->execute();

            echo "<script>alert('Record deleted successfully')</script>";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
