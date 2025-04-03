<?php
namespace App\Models;

use CodeIgniter\Model;

class ResultModel extends Model
{
    protected $table      = 'f1_results'; // přidání prefixu 'f1_'
    protected $primaryKey = 'id';

    protected $allowedFields = ['race_id', 'driver_id', 'team_id', 'position', 'points'];
    protected $useTimestamps = false;
}