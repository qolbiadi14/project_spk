<?php

namespace App\Models;

use CodeIgniter\Model;

class NilaiAlternatifModel extends Model
{
    protected $table      = 'nilai_alternatif';
    protected $primaryKey = 'id_nilai';
    protected $useTimestamps = false;
    protected $allowedFields = ['id_alternatif', 'id_kriteria', 'value', 'hasil'];
}
