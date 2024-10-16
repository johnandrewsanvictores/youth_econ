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

    // business field
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
            $this->response['message'] = "Error adding Field: " . $e->getMessage();
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

            $this->response['success'] = true;
            $this->response['message'] = "Business field removed successfully.";
        } catch (PDOException $e) {
            $this->response['success'] = false;
            $this->response['message'] = "Error removing field"; //. $e->getMessage();

            if ($e->getCode() == 23000) { // Integrity constraint violation
                $this->response['message'] = "Cannot delete the field because it is still referenced in another table.";
            }
        }

        return json_encode($this->response);
    }


    // business data

    public function addSocialMedia($business_id, $fb, $ig, $tiktok)
    {
        // Set the PDO error mode to exception
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare the statement
        $stmt = $this->pdo->prepare("INSERT INTO social_media_links (business_id, social_media_id, link) VALUES (:business_id, :social_media_id, :link)");

        // Check and insert Facebook link if not empty
        if (!empty($fb)) {
            $social_media_id = 1; // Assuming 1 is the ID for Facebook
            $stmt->bindParam(':business_id', $business_id);
            $stmt->bindParam(':social_media_id', $social_media_id);
            $stmt->bindParam(':link', $fb);
            $stmt->execute();
        }

        // Check and insert Instagram link if not empty
        if (!empty($ig)) {
            $social_media_id = 2; // Assuming 2 is the ID for Instagram
            $stmt->bindParam(':business_id', $business_id);
            $stmt->bindParam(':social_media_id', $social_media_id);
            $stmt->bindParam(':link', $ig);
            $stmt->execute();
        }

        // Check and insert TikTok link if not empty
        if (!empty($tiktok)) {
            $social_media_id = 3; // Assuming 3 is the ID for TikTok
            $stmt->bindParam(':business_id', $business_id);
            $stmt->bindParam(':social_media_id', $social_media_id);
            $stmt->bindParam(':link', $tiktok);
            $stmt->execute();
        }
    }


    // public function getBusinesses($filters = [], $orderBy = 'id', $orderDir = 'ASC', $limit = 10, $offset = 0)
    // {
    //     try {
    //         // Set the PDO error mode to exception
    //         $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //         // Base SQL query
    //         $sql = "SELECT 
    //                     business.name,
    //                     business_field.title,
    //                     business.contact_number,
    //                     business.location

    //                 FROM 
    //                     business
    //                 INNER JOIN 
    //                     business_field ON business_field.id = business.field_id

    //         ";

    //         $stmt = $this->pdo->prepare($sql);

    //         // Initialize an array to hold the WHERE clause and parameters
    //         $whereClauses = [];
    //         $params = [];

    //         // Add filtering based on passed filters (dynamic WHERE clause)
    //         foreach ($filters as $column => $value) {
    //             $whereClauses[] = "$column = :$column";
    //             $params[":$column"] = $value;
    //         }

    //         // Add WHERE clause if there are any filters
    //         if (!empty($whereClauses)) {
    //             $sql .= " WHERE " . implode(" AND ", $whereClauses);
    //         }

    //         // Add ORDER BY clause
    //         $sql .= " ORDER BY $orderBy $orderDir";

    //         // Add LIMIT and OFFSET for pagination
    //         $sql .= " LIMIT :limit OFFSET :offset";

    //         // Prepare the SQL statement
    //         $stmt = $this->pdo->prepare($sql);

    //         // Bind parameters for filters
    //         foreach ($params as $param => $value) {
    //             $stmt->bindValue($param, $value);
    //         }

    //         // Bind the limit and offset for pagination
    //         $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
    //         $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);

    //         // Execute the statement
    //         $stmt->execute();

    //         // Fetch all the results
    //         $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //         // Return the result set
    //         return $results;
    //     } catch (PDOException $e) {
    //         // Handle any errors
    //         $this->response['success'] = false;
    //         $this->response['message'] = "Error retrieving businesses: " . $e->getMessage();
    //         return json_encode($this->response);
    //     }
    // }

    public function getBusinesses($selected_field)
    {
        try {
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT 
                    business.id,
                    business.name,
                    business_field.title AS field,
                    business.contact_number,
                    business.location
                FROM 
                    business
                INNER JOIN 
                    business_field ON business_field.id = business.field_id";

            $whereClauses = [];
            $params = [];

            $columns = [
                0 => 'business.id',
                1 => 'business.name',
                2 => 'business_field.title',
                3 => 'business.location',
                4 => 'business.contact_number',
            ];

            if (isset($_POST['search']['value']) && $_POST['search']['value'] != '') {
                $search_value = $_POST['search']['value'];
                $whereClauses[] = "(business.name LIKE :search OR business_field.title LIKE :search)";
                $params[':search'] = "%$search_value%";
            }

            if (isset($selected_field)) {
                $whereClauses[] = "business_field.id = :field_id ";
                $params[':field_id'] = $selected_field;
            }

            if (!empty($whereClauses)) {
                $sql .= " WHERE " . implode(" AND ", $whereClauses);
            }

            $countSql = "SELECT COUNT(*) FROM (" . $sql . ") AS total";
            $countStmt = $this->pdo->prepare($countSql);

            foreach ($params as $param => $value) {
                $countStmt->bindValue($param, $value);
            }
            $countStmt->execute();
            $totalRecords = $countStmt->fetchColumn();

            if (isset($_POST['order'])) {
                $column_name = $_POST['order'][0]['column'];
                $order = $_POST['order'][0]['dir'];
                $sql .= " ORDER BY " . $columns[$column_name] . " " . $order;
            } else {
                $sql .= " ORDER BY business.name ASC";
            }

            if ($_POST['length'] != -1) {
                $limit = $_POST['length'];
                $offset = $_POST['start'];
                $sql .= " LIMIT :limit OFFSET :offset";
            }

            $stmt = $this->pdo->prepare($sql);

            foreach ($params as $param => $value) {
                $stmt->bindValue($param, $value);
            }

            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $output = [
                'draw' => intval($_POST['draw']),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $results,
            ];

            return json_encode($output);
        } catch (PDOException $e) {
            $this->response['success'] = false;
            $this->response['message'] = "Error retrieving businesses: " . $e->getMessage();
            return json_encode($this->response);
        }
    }





    public function addBusinessData($logo, $name, $field_id, $contact_number, $description, $location)
    {
        try {

            // check if the name of business already exists
            // $existingBusiness = $this->getBusinesses(['name' => $name]);
            // if (!empty($existingBusiness)) {
            //     $this->response['success'] = false;
            //     $this->response['message'] = "The business already exists!";
            //     return json_encode($this->response);
            //     exit();
            // }

            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Check if the name already exists in the business table
            $nameCheckSql = "SELECT COUNT(*) FROM business WHERE name = :name";
            $nameCheckStmt = $this->pdo->prepare($nameCheckSql);
            $nameCheckStmt->bindValue(':name', $name);
            $nameCheckStmt->execute();

            // Get the count of matching names
            $exists = $nameCheckStmt->fetchColumn();

            if ($exists > 0) {
                $this->response['success'] = false;
                $this->response['message'] = "The business already exists!";
                return json_encode($this->response);
                exit();
            }

            // Prepare an SQL statement for execution
            $stmt = $this->pdo->prepare("INSERT INTO business (logo, name, field_id, contact_number, description, location) VALUES (:value1, :value2, :value3, :value4, :value5, :value6)");

            // Bind parameters to the prepared statement
            $stmt->bindParam(':value1', $logo);
            $stmt->bindParam(':value2', $name);
            $stmt->bindParam(':value3', $field_id);
            $stmt->bindParam(':value4', $contact_number);
            $stmt->bindParam(':value5', $description);
            $stmt->bindParam(':value6', $location);


            // Execute the prepared statement
            $stmt->execute();
            $lastId = $this->pdo->lastInsertId();

            $this->addSocialMedia($lastId, $_POST['facebook'], $_POST['instagram'], $_POST['tiktok']);

            $this->response['success'] = true;
            $this->response['message'] = "Business data added successfully.";
        } catch (PDOException $e) {
            $this->response['success'] = false;
            $this->response['message'] = "Error adding Time: " . $e->getMessage();
        }

        return json_encode($this->response);
    }
}
