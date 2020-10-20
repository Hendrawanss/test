<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'm_user';
    protected $rTableRole = 'role';
    protected $rTableRoleConfig = 'role_config';
    protected $rTableMenu = 'menu';
    protected $rTableApp = 'applications';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];

    public function getAllAttributeUser($username) {
        // kurang banyak attribut yang diambil
        return DB::table($this->table)
                    ->select(DB::raw('username, '.$this->rTableRole.'.name as role,'.$this->rTableRole.'.default_menu, '.$this->rTableMenu.'.name as menu, '.$this->rTableMenu.'.submenu,'.$this->rTableApp.'.name as aplikasi'))
                    ->join($this->rTableRole,$this->table.'.role_id','=',$this->rTableRole.'.id')
                    ->join($this->rTableRoleConfig,$this->rTableRole.'.id','=',$this->rTableRoleConfig.'.role_id')
                    ->join($this->rTableMenu,$this->rTableRoleConfig.'.menu_id','=',$this->rTableMenu.'.id')
                    ->join($this->rTableApp,$this->rTableMenu.'.app_id','=',$this->rTableApp.'.id')
                    ->where('username','=', $username)
                    ->get();
    }

    public function getMenuUser($username){
        return DB::table($this->table)
                    ->select($this->rTableMenu.'.menu_id','role_name','menu_name','level', 'parent', 'link')
                    ->join($this->rTableRole,$this->table.'.role_id','=',$this->rTableRole.'.id')
                    ->join($this->rTableRoleConfig,$this->rTableRole.'.id','=',$this->rTableRoleConfig.'.role_id')
                    ->join($this->rTableMenu,$this->rTableRoleConfig.'.menu_id','=',$this->rTableMenu.'.menu_id')
                    ->where('username','=', $username)
                    ->orderBy('menu_name')
                    ->get();
    }

    public function getAllUser() {
        return DB::table($this->table)
                    ->select(DB::raw($this->table.".id, CASE WHEN password = '' THEN 'Ldap' ELSE 'Non Ldap' END as password, username , nama_lengkap, nik, jabatan, to_char(tl,'DD Month YYYY') as tl, no_telp, unik_lokasi, role_name as role, ".$this->table.".created_at, ".$this->table.".updated_at"))
                    ->leftjoin($this->rTableRole, $this->table.'.role_id', '=', $this->rTableRole.'.id')
                    ->get();
    }

    public function insert($arrayData) {
        return  DB::table($this->table)->insertGetId($arrayData);
    }

    public function updateData($arrayData,$id) {
        return DB::table($this->table)->where('id',$id)->update($arrayData);
    }

    public function deleteUser($id) {
        return  DB::table($this->table)->where('id','=',$id)->delete();
    }

    public function userCheck($username) {
        // Checking user in database
        $jumlah = DB::table($this->table)->where('username',$username)->count();
        return $jumlah;
    }

    public function insertUser($name,$username) {
        // Insert user in database
        return DB::table($this->table)->insert([
            "name" => $name,
            "username" => $username
        ]);
    }

    public function getID($username) {
        // Get id user
        $id = DB::table($this->table)->select('id')->where('username',$username)->first();
        return $id;
    }

    public function getProfile($username) {
        // Get id user
        $id = DB::table($this->table)->select(DB::raw($this->table.'.*, role.role_name as role, link as default_menu'))
                    ->join($this->rTableRole,$this->table.'.role_id','=','role.id')
                    ->join($this->rTableMenu,$this->rTableRole.'.default_menu', '=', $this->rTableMenu.'.menu_id')
                    ->where('username',$username)->first();
        return $id;
    }

    public function loginWithDB($username,$password){
        // Auth user with db
        $jumlah = DB::table($this->table)
                        ->where('username',$username)
                        ->where('password',sha1($password))
                        ->count();
        return $jumlah;
    }
    // ======================= LOCATION AREA (Nitip) =================================
    public function getLoc($unik_lokasi) {
        // get location user
        return DB::table('m_lokasi')->where('unik_lokasi',$unik_lokasi)->first();
    }

    // ======================= TOKEN AREA (Nitip) =================================
    public function deleteToken($userId) {
        // Delete token from db
        DB::table('oauth_access_tokens')->where('user_id',$userId)->delete();
    }

    // ======================= LOG AREA (Nitip) =================================
    public function checkAttempt($username) {
        // Get attempt log user
        $attemptUser = DB::table('login_attempt')->select('*')->where('username',$username)->first();
        return $attemptUser;
    }

    public function addAttempt($username,$attempt) {
        // Insert new user attempt
        DB::table('login_attempt')->insert([
            'username'=> $username,
            'attempt' => $attempt
        ]);
    }

    public function updateAttempt($username,$attempt) {
        // Update user attempt
        DB::table('login_attempt')->where('username',$username)->update(['attempt' => $attempt]);
    }

    public function removeAttempt($username) {
        // Delete attempt log by username
        DB::table('login_attempt')->where('username',$username)->delete();
    }

    public function addLog($username,$status,$detail,$session_id) {
        // Insert new user attempt
        DB::table('login_history')->insert([
            'username'=> $username,
            'status' => $status,
            'detail' => $detail,
            'session_id' => $session_id,
            'datetimeid' => now()
        ]);
    }
}
