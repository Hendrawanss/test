<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Lokasi extends Model
{
    protected $table = "m_lokasi";

    public function getLokasiTTC() {
        return DB::table($this->table)->select('unik_lokasi','nama_lokasi')->groupBy('unik_lokasi', 'nama_lokasi')->get();
    }
}
