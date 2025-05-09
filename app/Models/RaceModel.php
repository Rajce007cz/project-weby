<?php
namespace App\Models;

use CodeIgniter\Model;

class RaceModel extends Model
{
    protected $table      = 'f1_races';
    protected $primaryKey = 'id';

    protected $allowedFields = ['name', 'country', 'date', 'season_year'];
    protected $useTimestamps = false;
}