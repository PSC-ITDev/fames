<?php

namespace App\Models; 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Department;
use App\Models\AssetClassification as Classification;
use App\Models\AssetCategory as Category;
use App\Models\AssetLocation as Location;
use App\Models\CostCenter as CostCenter;
use App\Models\GlAccount as GlAccount;       
use Illuminate\Database\Eloquent\Casts\Attribute;
class FixedAsset extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'fixed_assets';
    protected $fillable = [
        'asset_no',
        'department_id',
        'capitalization_date',
        'qty',
        'bun',
        'asset_description',
        'other_identifier',
        'salvage_value',
        'useful_life_years',
        'cumulative_acquisition_value',
        'accumulated_depreciation', 
        'transfer_acquisition_value',
        'start_book_val',
        'end_book_value',
        'trans_acq_val', 
        'cost_center_id',
        'gl_account_id',
        'category_id',
        'location_id',
        'classification_id',
        'notes',
        'ordinary_depreciation_start_date' 
    ];
    protected function serialNumber(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => 
                "{$attributes['asset_number']}-{$attributes['department']}",
        );
    }
    protected function netBookValue(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => 
                ($attributes['cumulative_acquisition_value'] ?? 0) - 
                abs($attributes['accumulated_depreciation'] ?? 0),
        );
    } 
    protected $appends = ['serial_number', 'net_book_value'];
 
    protected $casts = [
        'capitalization_date' => 'date',
        'ordinary_depreciation_start_date' => 'date',
        'salvage_value'       => 'decimal:2',
        'cum_acq_value'       => 'decimal:2',
        'accum_dep'           => 'decimal:2', 
        'start_book_val'      => 'decimal:2',
        'trans_acq_val'       => 'decimal:2',
        'acquired_value'      => 'decimal:2',
        'end_book_value'      => 'decimal:2',
        'qty'                 => 'integer',
        'useful_life_years'   => 'integer',
    ];
  
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function classification(): BelongsTo
    {
        return $this->belongsTo(Classification::class);
    }

    public function costCenter(): BelongsTo
    {
        return $this->belongsTo(CostCenter::class, 'cost_center_id');
    }

    public function glAccount(): BelongsTo
    {
        return $this->belongsTo(GlAccount::class, 'gl_account_id');
    }
    

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function evaluationDetails()
    {
        return $this->hasOne(EvaluationDetail::class, 'asset_id', 'id');
    }  
}
