<?php

class TaskGateway
{
    private PDO $conn;
    
    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getAllForUser(int $user_id): array
    {
        $sql = "SELECT *
                FROM task
                WHERE user_id=:usr_id
                ORDER BY name";
                
        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":usr_id", $user_id, PDO::PARAM_INT);

        $stmt->execute();

        $data =[];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

          $row['is_completed'] = (bool) $row['is_completed'];

          $data[] = $row;

        }
        
        return $data;
    }

    public function getForUser(string $id, int $user_id)
    {
        $sql = "SELECT *
        FROM task
        WHERE id = :id
        AND user_id =:user_id";
        
        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);

        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data !== false) {

          $data['is_completed'] = (bool) $data['is_completed'];

        }

        return $data;
    }

    public function createForUser(array $data, int $user_id): string
    {
      $sql = "INSERT INTO task (name, priority, is_completed, user_id)
              VALUE (:name, :priority, :is_completed, :user_id)";
      
      $stmt = $this->conn->prepare($sql);

      $stmt->bindValue(":name", $data["name"], PDO::PARAM_STR);

      if (empty($data['priority'])) {
        $stmt->bindValue(":priority", null, PDO::PARAM_NULL);
      } else {
        $stmt->bindValue(":priority", $data["priority"], PDO::PARAM_INT);
      }

      $stmt->bindValue(":is_completed", $data["is_completed"] ?? false, PDO::PARAM_BOOL);

      $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);

      $stmt->execute();

      return $this->conn->lastInsertId();
    }

    public function updateForUser(string $id, array $data, int $user_id): int
    {
      $fields = [];
        
      if ( ! empty($data["name"])) {
          
          $fields["name"] = [
              $data["name"],
              PDO::PARAM_STR
          ];
      }
      
      if (array_key_exists("priority", $data)) {
            
        $fields["priority"] = [
            $data["priority"],
            $data["priority"] === null ? PDO::PARAM_NULL : PDO::PARAM_INT
        ];
      } 

      if (array_key_exists("is_completed", $data)) {
        $fields["is_completed"] = [
          $data["is_completed"],
          PDO::PARAM_BOOL
        ];
      }

      if (empty($fields)) {
            
        return 0;
        
      } else {
      
          $sets = array_map(function($value) {
              
              return "$value = :$value";
              
          }, array_keys($fields));
          
          $sql = "UPDATE task"
              . " SET " . implode(", ", $sets)
              . " WHERE id = :id"
              . " AND user_id = :user_id";
          
          $stmt = $this->conn->prepare($sql);
          
          $stmt->bindValue(":id", $id, PDO::PARAM_INT);
          $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
          
          foreach ($fields as $name => $values) {
              
              $stmt->bindValue(":$name", $values[0], $values[1]);
              
          }
          
          $stmt->execute();
          
          return $stmt->rowCount();
      }

    }

    public function deleteForUser(string $id, int $user_id): int
    {
      $sql = "DELETE FROM task 
              WHERE id = :id
              AND user_id = :user_id";

      $stmt = $this->conn->prepare($sql);

      $stmt->bindValue(":id", $id, PDO::PARAM_INT);
      $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);

      $stmt->execute();

      return $stmt->rowcount();

    }

    public function getAll(): array
    {
        $sql = "SELECT *
                FROM task
                ORDER BY name";
                
        $stmt = $this->conn->query($sql);

        $data =[];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

          $row['is_completed'] = (bool) $row['is_completed'];

          $data[] = $row;

        }
        
        return $data;
    }

    public function get(string $id)
    {
        $sql = "SELECT *
        FROM task
        WHERE id = :id";
        
        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data !== false) {

          $data['is_completed'] = (bool) $data['is_completed'];

        }

        return $data;
    }

    public function create(array $data): string
    {
      $sql = "INSERT INTO task (name, priority, is_completed)
              VALUE (:name, :priority, :is_completed)";
      
      $stmt = $this->conn->prepare($sql);

      $stmt->bindValue(":name", $data["name"], PDO::PARAM_STR);

      if (empty($data['priority'])) {
        $stmt->bindValue(":priority", null, PDO::PARAM_NULL);
      } else {
        $stmt->bindValue(":priority", $data["priority"], PDO::PARAM_INT);
      }

      $stmt->bindValue(":is_completed", $data["is_completed"] ?? false, PDO::PARAM_BOOL);

      $stmt->execute();

      return $this->conn->lastInsertId();
    }

    public function update(string $id, array $data): int
    {
      $fields = [];
        
      if ( ! empty($data["name"])) {
          
          $fields["name"] = [
              $data["name"],
              PDO::PARAM_STR
          ];
      }
      
      if (array_key_exists("priority", $data)) {
            
        $fields["priority"] = [
            $data["priority"],
            $data["priority"] === null ? PDO::PARAM_NULL : PDO::PARAM_INT
        ];
      } 

      if (array_key_exists("is_completed", $data)) {
        $fields["is_completed"] = [
          $data["is_completed"],
          PDO::PARAM_BOOL
        ];
      }

      if (empty($fields)) {
            
        return 0;
        
      } else {
      
          $sets = array_map(function($value) {
              
              return "$value = :$value";
              
          }, array_keys($fields));
          
          $sql = "UPDATE task"
              . " SET " . implode(", ", $sets)
              . " WHERE id = :id";
          
          $stmt = $this->conn->prepare($sql);
          
          $stmt->bindValue(":id", $id, PDO::PARAM_INT);
          
          foreach ($fields as $name => $values) {
              
              $stmt->bindValue(":$name", $values[0], $values[1]);
              
          }
          
          $stmt->execute();
          
          return $stmt->rowCount();
      }

    }

    public function delete(string $id): int
    {
      $sql = "DELETE FROM task 
              WHERE id = :id";

      $stmt = $this->conn->prepare($sql);

      $stmt->bindValue(":id", $id, PDO::PARAM_INT);

      $stmt->execute();

      return $stmt->rowcount();

    }

}