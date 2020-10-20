<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Role;
use App\User;
use App\Lokasi;
use App\Ldap\User as Ldap;
use Illuminate\Http\Request;

class settingController extends Controller
{
    public function __construct() {
        $this->roleModel = new Role();
        $this->menuModel = new Menu();
        $this->userModel = new User();
        $this->ldapModel = new Ldap();
        $this->lokasi = new Lokasi();
    }

    public function respon($status, $code, $data) {
        $data = [
            "status" => $status,
            "code" => $code,
            "data" => $data
        ];
        
        return response()->json($data);
    }

    public function userManageDashboard() {
        return view('userManagement.dashboard');
    }

    public function user() {
        return view('userManagement.user');
    }

    public function role() {
        return view('userManagement.role');
    }
    // =================================== MENU =======================================

    public function getAlldataMenu() {
        $data = $this->menuModel->getAlldata();
        if($data) {
            return $this->respon("success","200",$data);
        } else {
            return $this->respon("failed","404","Data in Role table not found!");
        }
    }
    

    // =================================== ROLE =======================================

    public function getAlldataRole() {
        $data = $this->roleModel->getAlldata();
        if($data) {
            return $this->respon("success","200",$data);
        } else {
            return $this->respon("failed","404","Data in Role table not found!");
        }
    }

    public function getAlldataUserRole() {
        $data = $this->roleModel->getAlldataUserRole();
        if($data) {
            return $this->respon("success","200",$data);
        } else {
            return $this->respon("failed","404","Data in Role table not found!");
        }
    }

    // =================================== USER =======================================

    public function getAllUser() {
        $data = $this->userModel->getAllUser();
        $arrayInstance = array();

        for($i=0; $i<count($data); $i++) {
            $arrayInstance[$i]['id'] = $data[$i]->id;
            $arrayInstance[$i]['username'] = $data[$i]->username;
            $arrayInstance[$i]['password'] = $data[$i]->password;
            $arrayInstance[$i]['nama_lengkap'] = $data[$i]->nama_lengkap;
            $arrayInstance[$i]['unik_lokasi'] = $data[$i]->unik_lokasi;
            $arrayInstance[$i]['no_telp'] = $data[$i]->no_telp;
            $arrayInstance[$i]['nik'] = $data[$i]->nik;
            $arrayInstance[$i]['jabatan'] = $data[$i]->jabatan;
            $arrayInstance[$i]['tl'] = $data[$i]->tl;
            $arrayInstance[$i]['role'] = $data[$i]->role;
            $arrayInstance[$i]['created_at'] = $data[$i]->created_at;
            $arrayInstance[$i]['updated_at'] = $data[$i]->updated_at;
            $arrayInstance[$i]['action'] = '<a href="'.route('user.delete',$data[$i]->id).'" type="button" id="btn_del_user" class="btn btn-danger color-opct-8">Delete</a> 
            <button type="submit" id="btn_edit_user" class="btn btn-success color-opct-8">Edit</button>';
        }
        if($arrayInstance) {
            return $this->respon("success","200",$arrayInstance);
        } else {
            return $this->respon("failed","404","Data in Role table not found!");
        }
    }

    public function getDataFromLDAP(Request $request) {
        $dn = $this->ldapModel->getUserDN($request->username);
        if($dn != "Not Found!") {
            return $this->respon("success","200",[
                "nama_lengkap" => $dn->extensionattribute7[0],
                "no_telp" => $dn->telephonenumber[0],
                "tanggal_lahir" => date('Y-m-d',strtotime($dn->extensionattribute11[0])),
                "jabatan" => $dn->title[0],
                "nik" => $dn->extensionattribute1[0]
            ]);
        } else {
            return $this->respon("failed","404",$request->username." Not Found!");
        }
    }

    public function insertUser(Request $request) {
        if($request->using_ldap == 'on') {
            $request->password = "";
        } else {
            $request->password = sha1($request->password);
        }
        $data = array(
            'username' => $request->username,
            'nama_lengkap' => $request->nama_lengkap,
            'password' => $request->password,
            'unik_lokasi' => $request->unik_lokasi,
            'no_telp' => $request->no_telp,
            'nik' => $request->nik,
            'jabatan' => $request->jabatan,
            'tl' => $request->tgl_lahir
        );

        $id = $this->userModel->insert($data);
        if (gettype($id) == "integer") {
            return $this->respon("success","200","Insert data success.");
        } else {
            return $this->respon("failed","404","Noo! Something wrong.");
        }
    }

    public function updateUser(Request $request) {
        if($request->password != "") {
            $data = array(
                'username' => $request->username,
                'nama_lengkap' => $request->nama_lengkap,
                'password' => $request->password,
                'unik_lokasi' => $request->unik_lokasi,
                'no_telp' => $request->no_telp,
                'nik' => $request->nik,
                'jabatan' => $request->jabatan,
                'tl' => $request->tgl_lahir
            );
        } else {
            $data = array(
                'username' => $request->username,
                'nama_lengkap' => $request->nama_lengkap,
                'unik_lokasi' => $request->unik_lokasi,
                'no_telp' => $request->no_telp,
                'nik' => $request->nik,
                'jabatan' => $request->jabatan,
                'tl' => $request->tgl_lahir
            );
        }
        $affected = $this->userModel->updateData($data,$request->id);
        if ($affected != 0) {
            return $this->respon("success","200","Update data success.");
        } else {
            return $this->respon("failed","404","Noo! Something wrong.");
        }
    }

    public function deleteUser($id) {
        return $this->userModel->deleteUser($id);
    }

    public function getListLokasiTTC() {
        $lokasi = $this->lokasi->getLokasiTTC();
        if($lokasi) {
            return $this->respon("success","200",$lokasi);
        } else {
            return $this->respon("failed","404","Get Location Error!");
        }
    }
}
