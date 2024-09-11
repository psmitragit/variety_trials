<?php

namespace App\Models;

use CodeIgniter\Model;

class Treatment extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'treatments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['variety_id', 'crop_id', 'name', 'trial_type_id', 'user_id', 'herbicide', 'insecticide', 'refuge', 'seed_treatment', 'year', 'state', 'relative_maturity', 'frogeye', 'sds', 'scn', 'is_approved', 'approved_by', 'user_entered_variety', 'user_entered_brand', 'group'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
