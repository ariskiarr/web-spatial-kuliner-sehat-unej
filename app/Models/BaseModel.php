<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Base Model Class - Implementasi Enkapsulasi dan Inheritance
 * Semua model akan inherit dari class ini
 */
abstract class BaseModel extends Model
{
    use HasFactory;

    // Enkapsulasi: protected properties
    protected $guarded = ['id'];
    public $timestamps = false;

    /**
     * Get model name - Polymorphism
     * Setiap child class harus implement method ini
     */
    abstract public function getModelName(): string;

    /**
     * Get display name with formatting - Enkapsulasi
     */
    public function getFormattedName(): string
    {
        return ucwords(strtolower($this->getModelName()));
    }

    /**
     * Validate data before save - Template Method Pattern
     */
    protected function validateBeforeSave(): bool
    {
        return true;
    }

    /**
     * Override save method - Overriding
     */
    public function save(array $options = [])
    {
        if (!$this->validateBeforeSave()) {
            throw new \Exception("Validation failed for " . $this->getModelName());
        }

        return parent::save($options);
    }

    /**
     * Get all with custom conditions - Overloading concept
     */
    public static function getAllData($conditions = [])
    {
        $query = static::query();

        foreach ($conditions as $key => $value) {
            $query->where($key, $value);
        }

        return $query->get();
    }

    /**
     * Soft search - Polymorphism
     */
    public function search(string $keyword): \Illuminate\Database\Eloquent\Collection
    {
        return static::where(function($query) use ($keyword) {
            foreach ($this->getFillable() as $field) {
                $query->orWhere($field, 'ILIKE', "%{$keyword}%");
            }
        })->get();
    }
}
