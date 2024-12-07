<?php

namespace App\Models;

use CodeIgniter\Model;

class KriteriaModel extends Model
{
    protected $table      = 'kriteria';
    protected $primaryKey = 'id_kriteria';
    protected $useTimestamps = false;
    protected $allowedFields = ['nama_kriteria', 'tipe_kriteria', 'bobot_kriteria'];

}
