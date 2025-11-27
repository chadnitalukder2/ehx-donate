<?php

namespace EHXDonate\Models;

use EHXDonate\Core\Database;
use Exception;

/**
 * Base Model class - Lightweight ORM using $wpdb
 */
abstract class Model
{
    /**
     * The table name
     */
    protected $table;

    /**
     * The primary key
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable
     */
    protected $fillable = [];

    /**
     * The attributes that should be hidden for arrays
     */
    protected $hidden = [];

    /**
     * The attributes that are required
     */
    protected $required = [];

    /**
     * Validation rules
     */
    protected $rules = [];

    /**
     * The model's attributes
     */
    protected $attributes = [];

    /**
     * Whether the model exists in the database
     */
    protected $exists = false;

    /**
     * Global $wpdb instance
     */
    protected $wpdb;

    /**
     * Validation errors
     */
    protected $errors = [];

    protected $query = [
        'orderBy' => null,
    ];

    /**
     * Constructor
     */
    public function __construct(array $attributes = [])
    {
        global $wpdb;
        $this->wpdb = $wpdb;

        if (empty($this->table)) {
            $this->table = $this->getTableName();
        }

        $this->fill($attributes);
    }

    /**
     * Get the table name for the model
     */
    protected function getTableName(): string
    {
        $class = get_class($this);
        $class = str_replace('EHXDonate\\Models\\', '', $class);
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $class)) . 's';
    }

    /**
     * Fill the model with an array of attributes
     */
    public function fill(array $attributes): self
    {
        foreach ($attributes as $key => $value) {
            if (in_array($key, $this->fillable) || empty($this->fillable)) {
                $this->attributes[$key] = $value;
            }
        }
        return $this;
    }

    /**
     * Get an attribute from the model
     */
    public function getAttribute(string $key)
    {
        return $this->attributes[$key] ?? null;
    }

    /**
     * Set an attribute on the model
     */
    public function setAttribute(string $key, $value): self
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    /**
     * Get all attributes
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Get the model's attributes as an array
     */
    public function toArray(): array
    {
        $attributes = $this->attributes;

        // Remove hidden attributes
        foreach ($this->hidden as $hidden) {
            unset($attributes[$hidden]);
        }

        return $attributes;
    }

    /**
     * Validate the model's attributes
     */
    protected function validate(): bool
    {
        $this->errors = [];

        // Check required fields
        foreach ($this->required as $field) {
            if (!isset($this->attributes[$field]) || $this->attributes[$field] === '' || $this->attributes[$field] === null) {
                $this->errors[$field] = "The {$field} field is required.";
            }
        }

        // Check custom validation rules
        foreach ($this->rules as $field => $rules) {
            if (!isset($this->attributes[$field])) {
                continue;
            }

            $value = $this->attributes[$field];
            $rulesArray = is_array($rules) ? $rules : explode('|', $rules);

            foreach ($rulesArray as $rule) {
                if (!$this->validateRule($field, $value, $rule)) {
                    break; // Stop on first validation error for this field
                }
            }
        }

        return empty($this->errors);
    }

    /**
     * Validate a single rule
     */
    protected function validateRule(string $field, $value, string $rule): bool
    {
        if (strpos($rule, ':') !== false) {
            list($ruleName, $parameter) = explode(':', $rule, 2);
        } else {
            $ruleName = $rule;
            $parameter = null;
        }

        switch ($ruleName) {
            case 'email':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[$field] = "The {$field} must be a valid email address.";
                    return false;
                }
                break;

            case 'numeric':
                if (!is_numeric($value)) {
                    $this->errors[$field] = "The {$field} must be numeric.";
                    return false;
                }
                break;

            case 'min':
                if (strlen($value) < $parameter) {
                    $this->errors[$field] = "The {$field} must be at least {$parameter} characters.";
                    return false;
                }
                break;

            case 'max':
                if (strlen($value) > $parameter) {
                    $this->errors[$field] = "The {$field} must not exceed {$parameter} characters.";
                    return false;
                }
                break;

            case 'url':
                if (!filter_var($value, FILTER_VALIDATE_URL)) {
                    $this->errors[$field] = "The {$field} must be a valid URL.";
                    return false;
                }
                break;

            case 'unique':
                $table = $this->wpdb->prefix . $this->table;
                $existing = $this->wpdb->get_var(
                    $this->wpdb->prepare(
                        "SELECT COUNT(*) FROM {$table} WHERE {$field} = %s",
                        $value
                    )
                );

                if ($existing > 0) {
                    // If updating, check if it's the same record
                    if ($this->exists) {
                        $currentId = $this->getAttribute($this->primaryKey);
                        $existingId = $this->wpdb->get_var(
                            $this->wpdb->prepare(
                                "SELECT {$this->primaryKey} FROM {$table} WHERE {$field} = %s",
                                $value
                            )
                        );

                        if ($existingId != $currentId) {
                            $this->errors[$field] = "The {$field} has already been taken.";
                            return false;
                        }
                    } else {
                        $this->errors[$field] = "The {$field} has already been taken.";
                        return false;
                    }
                }
                break;
        }

        return true;
    }

    /**
     * Get validation errors
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Get first error message
     */
    public function getFirstError(): ?string
    {
        return !empty($this->errors) ? reset($this->errors) : null;
    }

    /**
     * Check if model has errors
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Save the model to the database
     */
    public function save(): bool
    {
        // Validate before saving
        if (!$this->validate()) {
            return false;
        }

        $data = $this->attributes;

        if ($this->exists) {
            // Update existing record
            $where = [$this->primaryKey => $this->getAttribute($this->primaryKey)];
            unset($data[$this->primaryKey]);

            $result = $this->wpdb->update(
                $this->wpdb->prefix . $this->table,
                $data,
                $where
            );

            // Check for database errors
            if ($this->wpdb->last_error) {
                $this->errors['database'] = $this->wpdb->last_error;
                return false;
            }
        } else {
            // Insert new record
            $result = $this->wpdb->insert(
                $this->wpdb->prefix . $this->table,
                $data
            );

            // Check for database errors
            if ($this->wpdb->last_error) {
                $this->errors['database'] = $this->wpdb->last_error;
                return false;
            }

            if ($result) {
                $this->setAttribute($this->primaryKey, $this->wpdb->insert_id);
                $this->exists = true;
            }
        }

        return $result !== false;
    }

    /**
     * Delete the model from the database
     */
    public function delete(): bool
    {
        if (!$this->exists) {
            $this->errors['delete'] = 'Cannot delete a model that does not exist in the database.';
            return false;
        }

        $result = $this->wpdb->delete(
            $this->wpdb->prefix . $this->table,
            [$this->primaryKey => $this->getAttribute($this->primaryKey)]
        );

        if ($this->wpdb->last_error) {
            $this->errors['database'] = $this->wpdb->last_error;
            return false;
        }

        if ($result) {
            $this->exists = false;
        }

        return $result !== false;
    }

    /**
     * Find a model by its primary key
     */
    public static function find($id): ?self
    {
        $instance = new static();
        $table = $instance->wpdb->prefix . $instance->table;

        $result = $instance->wpdb->get_row(
            $instance->wpdb->prepare("SELECT * FROM {$table} WHERE {$instance->primaryKey} = %s", $id)
        );

        if ($result) {
            $instance->fill((array) $result);
            $instance->exists = true;
            return $instance;
        }

        return null;
    }

    /**
     * Get all records
     */
    public static function all(): array
    {
        $instance = new static();
        $table = $instance->wpdb->prefix . $instance->table;

        $results = $instance->wpdb->get_results("SELECT * FROM {$table}");

        $models = [];
        foreach ($results as $result) {
            $model = new static();
            $model->fill((array) $result);
            $model->exists = true;
            $models[] = $model;
        }

        return $models;
    }


    /**
     * Create a new model instance
     */
    public static function create(array $attributes): self
    {
        $model = new static($attributes);

        if (!$model->save()) {
            throw new Exception('Failed to create model: ' . $model->getFirstError());
        }

        return $model;
    }

    public function orderBy($column, $direction = 'ASC'): self
    {
        $this->query['orderBy'] = [$column, strtoupper($direction)];
        return $this;
    }

    /**
     * Get the first record matching the query
     */
    public function first(): ?self
    {
        $table = $this->wpdb->prefix . $this->table;
        $sql = "SELECT * FROM {$table}";
        $params = [];

        // Apply WHERE conditions
        if (!empty($this->query['where'])) {
            $whereClauses = [];
            foreach ($this->query['where'] as [$column, $operator, $value]) {
                $whereClauses[] = "{$column} {$operator} %s";
                $params[] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $whereClauses);
        }

        // Apply ORDER BY if defined
        if (!empty($this->query['orderBy'])) {
            [$column, $direction] = $this->query['orderBy'];
            $sql .= " ORDER BY {$column} {$direction}";
        }

        // Limit to 1 record
        $sql .= " LIMIT 1";

        // Prepare and execute query
        if (!empty($params)) {
            $prepared = $this->wpdb->prepare($sql, $params);
            $result = $this->wpdb->get_row($prepared);
        } else {
            $result = $this->wpdb->get_row($sql);
        }

        if ($result) {
            $model = new static();
            $model->fill((array) $result);
            $model->exists = true;
            return $model;
        }

        return null;
    }

    /**
     * Add OR WHERE clause
     */
    public function orWhere(string $column, $operator, $value = null): self
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        $this->query['where'][] = [
            'column' => $column,
            'operator' => $operator,
            'value' => $value,
            'boolean' => 'OR'
        ];
        return $this;
    }

    /**
     * Update the where method to include boolean
     */
    public function where(string $column, $operator, $value = null): self
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        $this->query['where'][] = [
            'column' => $column,
            'operator' => $operator,
            'value' => $value,
            'boolean' => 'AND'
        ];
        return $this;
    }

    /**
     * Reset query builder
     */
    protected function resetQuery(): void
    {
        $this->query = [
            'where' => [],
            'whereIn' => [],
            'orderBy' => null,
            'limit' => null,
            'offset' => null,
        ];
    }

    /**
     * Update get() method to support limit and offset
     */
    public function get(): array
    {
        $table = $this->wpdb->prefix . $this->table;
        $sql = "SELECT * FROM {$table}";
        $params = [];

        // Apply WHERE conditions
        if (!empty($this->query['where'])) {
            $sql .= " WHERE ";
            $firstWhere = true;

            foreach ($this->query['where'] as $condition) {
                if (!$firstWhere) {
                    $boolean = $condition['boolean'] ?? 'AND';
                    $sql .= " {$boolean} ";
                } else {
                    $firstWhere = false;
                }

                $sql .= "{$condition['column']} {$condition['operator']} %s";
                $params[] = $condition['value'];
            }
        }

        // Apply ORDER BY if defined
        if (!empty($this->query['orderBy'])) {
            [$column, $direction] = $this->query['orderBy'];
            $sql .= " ORDER BY {$column} {$direction}";
        }

        // Apply LIMIT and OFFSET if defined
        if (isset($this->query['limit'])) {
            $sql .= " LIMIT {$this->query['limit']}";

            if (isset($this->query['offset'])) {
                $sql .= " OFFSET {$this->query['offset']}";
            }
        }

        // Prepare and execute
        if (!empty($params)) {
            $prepared = $this->wpdb->prepare($sql, $params);
            $results = $this->wpdb->get_results($prepared);
        } else {
            $results = $this->wpdb->get_results($sql);
        }

        $models = [];
        foreach ($results as $result) {
            $model = new static();
            $model->fill((array) $result);
            $model->exists = true;
            $models[] = $model;
        }

        return $models;
    }

    /**
     * Corrected paginate method
     */
    public function paginate($perPage = 10, $page = 1, $search = '', $status = null): array
    {
        if (!empty($search)) {
            $this->where('title', 'LIKE', '%' . $this->wpdb->esc_like($search) . '%');
                
        }

        if ($status !== null) {
            $this->where('status', '=', $status);
        }

        $whereConditions = $this->query['where'] ?? [];

        // Calculate offset
        $offset = ($page - 1) * $perPage;
        $this->query['limit'] = $perPage;
        $this->query['offset'] = $offset;

        // Get the paginated results
        $results = $this->get();

        // Build count query
        $table = $this->wpdb->prefix . $this->table;
        $countSql = "SELECT COUNT(*) FROM {$table}";
        $params = [];

        // Apply WHERE conditions to count
        if (!empty($whereConditions)) {
            $countSql .= " WHERE ";
            $firstWhere = true;

            foreach ($whereConditions as $condition) {
                if (!$firstWhere) {
                    $boolean = $condition['boolean'] ?? 'AND';
                    $countSql .= " {$boolean} ";
                } else {
                    $firstWhere = false;
                }

                $countSql .= "{$condition['column']} {$condition['operator']} %s";
                $params[] = $condition['value'];
            }
        }

        // Execute count query
        if (!empty($params)) {
            $countSql = $this->wpdb->prepare($countSql, $params);
        }

        $total = (int) $this->wpdb->get_var($countSql);

        // Reset query for next use
        $this->resetQuery();

        
        return [
            'data' => $results,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage),
            'from' => $offset + 1,
            'to' => min($offset + $perPage, $total),
        ];
    }

    public static function getCampaignByPostId(int $post_id): ?self
    {
        $instance = new static();
        $table = $instance->wpdb->prefix . $instance->table;

        $result = $instance->wpdb->get_row(
            $instance->wpdb->prepare("SELECT * FROM {$table} WHERE post_id = %d", $post_id)
        );

        if ($result) {
            $instance->fill((array)$result);
            $instance->exists = true;
            return $instance;
        }

        return null;
    }

    /**
     * Magic method to get attributes
     */
    public function __get(string $key)
    {
        return $this->getAttribute($key);
    }

    /**
     * Magic method to set attributes
     */
    public function __set(string $key, $value)
    {
        $this->setAttribute($key, $value);
    }

    /**
     * Magic method to check if attribute exists
     */
    public function __isset(string $key): bool
    {
        return isset($this->attributes[$key]);
    }
}
