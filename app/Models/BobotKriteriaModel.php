<?php

namespace App\Models;

use CodeIgniter\Model;

class BobotKriteriaModel extends Model
{
    protected $table      = 'bobot_kriteria';
    protected $primaryKey = 'id_bobot_kriteria';
    protected $useTimestamps = false;
    protected $allowedFields = ['id_kriteria_kiri', 'id_kriteria_kanan', 'value', 'is_reverse'];
}
