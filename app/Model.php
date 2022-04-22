<?php
class Model {
    //Доступы в базу 
    protected $host = 'localhost';
    protected $user = 'root';
    protected $db = 'newsblog';
    protected $prefix = '';
    protected $pass = '123';
    protected $conn;
    protected $columns;
    protected $table_name;
 
    public function __construct() {

        // Подключение PDO
        try {
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db . ';charset=utf8', $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->setColumns();
        } catch( PDOException $e ) {
            trigger_error('ERROR: ' . $e->getMessage()); 
            exit;
        }
    }
    
    // Вытаскиваем все записи
    public function findAll($tableName = null) {

        $this->setTable($tableName);

        $stmt = $this->conn->query('SELECT ' . $this->columns . ' FROM ' . $this->getTableName($tableName));
        
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Изменение данных в базе
    public function update($id, $options = [], $tableName = null) {
        if( empty($options) ) {
            return false;
        }

        $updates = '';
        foreach($options as $field=>$option) {
            $updates .= $field . '=' . $this->conn->quote($option) . ', ';
        }
        $updates = rtrim($updates, ', ');

        $this->conn->query('UPDATE ' . $this->getTableName($tableName) . ' SET ' . $updates . ' WHERE id =' . $this->conn->quote($id));
    }

    // Запись данных в базу
    public function insert($options = [], $tableName = null) {
        if( empty($options) ) {
            return false;
        }

        $fields = implode(', ', array_keys($options));

        $values = '';
        foreach($options as $option) {
            $values .= $this->conn->quote($option) . ', ';
        }
        $values = rtrim($values, ', ');

        $this->conn->query('INSERT INTO ' . $this->getTableName($tableName) . ' (' . $fields . ') VALUES (' . $values . ')');
    }

    // Удаления записа из базы
    public function delete($id, $tableName = null) {
        $stmt = $this->conn->query('DELETE FROM ' . $this->getTableName($tableName) . ' WHERE id=' . $this->conn->quote($id));
        return $stmt->rowCount();
    }

    // Поиск по полям в Базе
    public function findWhere($options = [], $tableName = null) {
        if( empty($options) ) {
            return false;
        }

        $query = '';
        foreach($options as $option) {
            $query .= ' AND ' . $option[0] . $option[1] . $this->conn->quote($option[2]);
        }
        $query = ltrim($query,' AND ');

        $this->setTable($tableName);

        $stmt = $this->conn->query('SELECT ' . $this->columns . ' FROM ' . $this->getTableName($tableName) . ' WHERE ' . $query);

        $data = $stmt->fetchAll();

        if( empty($data) ) {
            return false;
        } elseif( count($data) == 1 ) {
            return $data[0];
        } else {
            return $data;
        }
    }

    // Сформируем имя таблице 
    private function getTableName($tableName) {
        $tableName = isset($tableName) ? $tableName : $this->table_name;
        if( !$this->prefix ) {
            return $tableName;
        }
        return $tableName ? ($this->prefix ? $this->prefix . '_' . $tableName : $tableName) : false;
    }

    // Определяем всех имя полей
    private function setColumns($tableName = null) {

        $table = $this->getTableName($tableName);

        if($table) {

            $stmt = $this->conn->query('DESCRIBE ' . $table);

			$this->columns = '';

            foreach($stmt as $field) {
                $this->columns .= $field['Field'] . ', '; 
            }

            $this->columns = rtrim($this->columns, ', ');
        }
    }

    // Определяем Таблицу с которым работаем
    private function setTable($tableName) {
        if( isset($tableName) && (empty($this->table_name) || $this->table_name != $tableName) ) {
            $this->table_name = $tableName;
            $this->setColumns($tableName);
        }
    }

}