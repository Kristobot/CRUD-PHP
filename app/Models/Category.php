<?php

namespace App\Models;

use PDO;
use PDOException;
use Database\Database;
use App\Utils\ValidateHttpMethod;

class Category
{

  public static function update(): string
  {
    ValidateHttpMethod::validateHttpMethod("PATCH");

    $id = explode('/', $_SERVER['REQUEST_URI'])[4];

    if (!$id || !filter_var($id, FILTER_VALIDATE_INT)) {
      return json_encode(["error" => "ID de categoría no válido o faltante."]);
    }

    $requestData = json_decode(file_get_contents('php://input'), true);

    if (!isset($requestData['name']) || !preg_match('/^[a-zA-Z]+$/', $requestData['name'])) {
      return json_encode(["error" => "El campo 'name' debe contener solo letras."]);
    }

    try {
      $query = "UPDATE categories SET name = :name WHERE id = :id";
      $statement = Database::getConnection()->prepare($query);
      $statement->bindParam(':id', $id, PDO::PARAM_INT);
      $statement->bindParam(':name', $requestData['name'], PDO::PARAM_STR);
      $statement->execute();

      return json_encode(['success' => 'Categoría actualizada.']);
    } catch (PDOException $e) {
      return json_encode(["error" => "Error al actualizar la categoría {$e->getMessage()}"]);
    }
  }

  public static function create(): string {
    ValidateHttpMethod::validateHttpMethod("POST");

    $requestData = json_decode(file_get_contents("php://input"), true);
    if (!isset($requestData["name"]) || !preg_match('/^[a-zA-Z]+$/', $requestData["name"])) {
      return json_encode(["error"=> "El campo debe contener solo letras"]);
    }

    try {
      $query = "INSERT INTO categories(name) VALUES (:name)";
      $statement = Database::getConnection()->prepare($query);
      $statement->bindParam(":name", $requestData["name"], PDO::PARAM_STR);
      $statement->execute();

      return json_encode(["success"=> "Categoria Creada"]);
    } catch (PDOException $e) {
      return json_encode(["error"=> "Error al crear la cateogira {$e->getMessage()}"]);
    }
  }

  public static function delete(): string {

    ValidateHttpMethod::validateHttpMethod("DELETE");

    $id = explode('/', $_SERVER['REQUEST_URI'])[4];

    if (!$id || !filter_var($id, FILTER_VALIDATE_INT)) {
      return json_encode(["error" => "ID de categoría no válido o faltante."]);
    }

    try {
      $query = "DELETE FROM categories WHERE id = :id";
      $statement = Database::getConnection()->prepare($query);
      $statement->bindParam(":id", $id, PDO::PARAM_INT);
      $statement->execute();

      return json_encode(["success"=> "Categoria Eliminada"]);
    } catch (PDOException $e) {
      return json_encode(["error"=> "No se puedo eliminar la fila {$e->getMessage()}"]);
    }
  }

  public static function show(): string {
    ValidateHttpMethod::validateHttpMethod("GET");

    $id = explode('/', $_SERVER['REQUEST_URI'])[4];

    if (!$id || !filter_var($id, FILTER_VALIDATE_INT)) {
      return json_encode(["error" => "ID de categoría no válido o faltante."]);
    }

    try {
      $query = "SELECT * FROM categories WHERE id = :id";
      $statement = Database::getConnection()->prepare($query);
      $statement->bindParam(":id", $id, PDO::PARAM_INT);
      $statement->execute();

      return json_encode($statement->fetchAll(PDO::FETCH_ASSOC));

    } catch (PDOException $e) {
      return json_encode(["error"=> "No se pudo obtener las Categorias {$e->getMessage()}"]);
    }
  }

  public static function getAll(): string {
    ValidateHttpMethod::validateHttpMethod("GET");

    try {
      $query = "SELECT * FROM categories";
      $statement = Database::getConnection()->prepare($query);
      $statement->execute();

      return json_encode($statement->fetchAll(PDO::FETCH_ASSOC));

    } catch (PDOException $e) {
      return json_encode(["error"=> "No se pudo obtener las Categorias {$e->getMessage()}"]);
    }
  }

}
