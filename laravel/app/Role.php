<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Role extends Model
{
    protected $table = 'role';
    protected $rTableMenu = 'menu';
    protected $rTableUser = 'm_user';

    public function getAlldata() {
        return DB::table($this->table)->get();
    }

    public function getAlldataUserRole() {
        return DB::table($this->table)
                    ->select(DB::raw($this->table.'.id, username, nama_lengkap, default_menu, role_name as role, '.$this->table.'.created_at, '.$this->table.'.updated_at'))
                    ->join($this->rTableUser, $this->rTableUser.'.role_id', '=', $this->table.'.id')
                    ->get();
    }

}
