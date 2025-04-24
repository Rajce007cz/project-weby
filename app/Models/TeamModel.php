<?php
namespace App\Models;

use CodeIgniter\Model;

class TeamModel extends Model
{
    protected $table      = 'f1_teams';
    protected $primaryKey = 'id';

    protected $allowedFields = ['name', 'nationality', 'points', 'wins', 'podiums', 'wcc'];
    protected $useTimestamps = false;
}