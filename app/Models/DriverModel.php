<?php
namespace App\Models;

use CodeIgniter\Model;

class DriverModel extends Model
{
    protected $table      = 'f1_drivers'; // přidání prefixu 'f1_'
    protected $primaryKey = 'id';

    protected $allowedFields = ['first_name', 'last_name', 'nationality', 'dob', 'points', 'win', 'wdc', 'image'];
    protected $useTimestamps = false;
}