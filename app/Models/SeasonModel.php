<?php
namespace App\Models;

use CodeIgniter\Model;

class SeasonModel extends Model
{
    protected $table      = 'f1_seasons';
    protected $primaryKey = 'year';

    protected $allowedFields = ['year', 'description'];
    protected $useTimestamps = false;
}