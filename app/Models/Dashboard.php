<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Department;

class Dashboard extends Model
{
    public int $total_assets;
    public int $new_assets;
    public int $disposed_assets;

    // Declare a list of departments as a property
    public array $departments = [];

    // Or as a collection
    // public $departments;

    /**
     * Get departments with asset counts
     */
    public function getDepartmentsWithAssetCounts()
    {
        return Department::withCount('fixedAssets')->get();
    }

    /**
     * Get departments as array with count
     */
    public function getDepartmentsArray()
    {
        return Department::withCount('fixedAssets')
            ->orderBy('fixed_assets_count', 'desc') // Sorts 10, 9, 8...
            ->get()
            ->map(function ($department) {
                return [
                    'id' => $department->id,
                    'code' => $department->code,
                    'name' => $department->name,
                    'count' => $department->fixed_assets_count
                ];
            })
            ->toArray();
    }

    public function getDepartmentsByAssetCount()
    {
        return Department::withCount('fixedAssets')
            ->orderBy('fixed_assets_count', 'desc')
            ->get();
    }

    /**
     * Get departments with only those that have assets
     */
    public function getDepartmentsWithAssets()
    {
        return Department::has('fixedAssets')
            ->withCount('fixedAssets')
            ->get();
    }
}
