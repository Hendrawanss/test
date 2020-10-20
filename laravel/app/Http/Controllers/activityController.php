<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ldap\User;
use App\DataCenter;
use App\User as UserDB;

class activityController extends Controller
{
    public function __construct() {
        $this->userLdap = new User();
        $this->dt = new DataCenter();
        $this->userDB = new UserDB();
    }

    public function respon($status, $code, $data) {
        $data = [
            "status" => $status,
            "code" => $code,
            "data" => $data
        ];
        
        return response()->json($data);
    }

    public function index(Request $request) {
        $attribute = $this->userDB->getAllAttributeUser(session()->get('username-user'));
        $aplikasi = $this->userDB->getAppinOneUser(session()->get('username-user'));
        $result = array();
        $index = 0;

        foreach($aplikasi as $data) {
            $index2 = 0;
            $buffer = array();
            $result[$index]["aplikasi"] = $data->aplikasi;
            $result[$index]["icon"] = $data->icon;
            foreach($attribute as $row) {
                $result[$index]["default_menu"] = $row->default_menu;
                $buffer[$index2]['menu'] = [
                    'nama' => $row->menu,
                    'submenu' => $row->submenu
                ];
                $index2++;
            }
            $result[$index]["list_menu"] = $buffer;
            $index++;
        }
        // return $result;
        return view('app', compact('result'));
    }

    public function loginPage() {
        $sensor = $this->dt->getSensorSummary();
        unset($sensor[3]);
        array_values($sensor);
        return view('dacita.dashboard', compact('sensor'));
    }

    public function login(Request $request) {
        return $this->userLdap->authUser($request->get('username'),$request->get('password'));
    }

    public function detail($ttc, $lantai) {
        return view('dacita.detail');
    }

    // API

    public function getChartHistory() {
        $data = $this->dt->getChartHistory();
        if(count($data) > 0) {
            $label = array_keys((array)$data[0]);
            // delete element date
            unset($label[0]);
            $label = array_values($label);

            return $this->respon("success","200",["value" => $data, "label" => $label]);
        } else {
            return $this->respon("failed","404",["message" => "Data Chart tidak ditemukan. Mohon untuk melengkapi data terlebih dahulu"]);
        }
    }

    public function getAlarmActive() {
        $data = $this->dt->getSensorDashboard();
        if(count($data) > 0) {
            return $this->respon("success","200",$data);
        } else {
            return $this->respon("failed","404",["message" => "Data Alarm Active tidak ditemukan. Mohon untuk melengkapi data terlebih dahulu"]);
        }
    }

    public function getSensorSummaryPerDatacenter(Request $request) {
        $datacenter_id = addslashes($request->get('datacenter'));

        $datacenter_name = "";
        switch ($datacenter_id) {
            case 'datacenter_tbs':
                $datacenter_name = "TTC TB Simatupang";
                break;
            case 'datacenter_bsd':
                $datacenter_name = "TTC BSD";
                break;
            case 'datacenter_buaran':
                $datacenter_name = "";
                break;
            case 'datacenter_sukoharjo':
                $datacenter_name = "TTC Sukoharjo";
                break;
            case 'datacenter_gayungan':
                $datacenter_name = "";
                break;
            case 'datacenter_arifinahmad':
                $datacenter_name = "TTC Arifin Ahmad";
                break;
            case 'datacenter_sudiang':
                $datacenter_name = "SUDIANG";
                break;
            default:
                echo "Invalid input!";
                exit;
                break;
        }
        $data = $this->dt->getSensorSummaryPerDatacenter($datacenter_name);
        if(count($data) > 0) {
            return $this->respon("success","200",$data);
        } else {
            return $this->respon("failed","404",["message" => "Data Summary Per Datacenter tidak ditemukan. Mohon untuk melengkapi data terlebih dahulu"]);
        }
    }

    public function getSensorDashboardPercategory(Request $request) {
        $category = addslashes($request->post('category'));
        $data = $this->dt->getSensorDashboardPercategory($category);
        if(count($data) > 0) {
            return $this->respon("success","200",$data);
        } else {
            return $this->respon("failed","404",["message" => "Data Sensor tidak ditemukan. Mohon untuk melengkapi data terlebih dahulu"]);
        }
    }

    public function getAssetHeader(Request $request){
        $arr = []; $arr2 = [];
        $datacenter_name = addslashes($request->get('datacenter'));
        $data = $this->dt->getAssetHeader($datacenter_name);

        foreach($data as $row){
            array_push($arr,$row->table_header);
            array_push($arr2,$row->table_reference);
        }
        $data = [
            "header" =>  $arr,
            "data" => $arr2
        ];
        if(count($data) > 0) {
            return $this->respon("success","200",$data);
        } else {
            return $this->respon("failed","404",["message" => "Data Header tidak ditemukan. Mohon untuk melengkapi data terlebih dahulu"]);
        }
    }

    public function getSensor(Request $request){
        $datacenter_name = str_replace('datacenter_','',addslashes($request->get('datacenter')));
        $lantai = addslashes($request->get('lantai'));
        $data = $this->dt->getSensor($datacenter_name,$lantai);
        if(count($data) > 0) {
            return $this->respon("success","200",$data);
        } else {
            return $this->respon("failed","404",["message" => "Data Semua Sensor tidak ditemukan. Mohon untuk melengkapi data terlebih dahulu"]);
        }
    }

    public function getAssetPerlevel(Request $request) {
        $datacenter_name = addslashes($request->get('datacenter'));
        $lantai = addslashes($request->get('lantai'));
        $data = $this->dt->getAssetPerlevel($datacenter_name,$lantai);
        if(count($data) > 0) {
            return $this->respon("success","200",$data);
        } else {
            return $this->respon("failed","404",["message" => "Data Asset Level tidak ditemukan. Mohon untuk melengkapi data terlebih dahulu"]);
        }
    }

    public function getAssetRak(Request $request) {
        $datacenter_name = addslashes($request->get('datacenter'));
        $lantai = addslashes($request->get('lantai'));
        $rakid = addslashes($request->get('rakid'));
        $data = $this->dt->getAssetRak($datacenter_name,$lantai,$rakid);
        if(count($data) > 0) {
            return $this->respon("success","200",$data);
        } else {
            return $this->respon("failed","404",["message" => "Data Asset Rak tidak ditemukan. Mohon untuk melengkapi data terlebih dahulu"]);
        }
    }

    public function getSensorRak(Request $request) {
        $datacenter_name = addslashes($request->get('datacenter'));
        $level = addslashes($request->get('level'));
        $rakid = addslashes($request->get('rakid'));
        $data = $this->dt->getSensorRak($datacenter_name,$level,$rakid);
        if(count($data) > 0) {
            return $this->respon("success","200",$data);
        } else {
            return $this->respon("failed","404",["message" => "Data Sensor Rak tidak ditemukan. Mohon untuk melengkapi data terlebih dahulu"]);
        }
    }

    public function menuUtama() {
        return view('pages.menuUtama');
    }

    public function userManagePage() {
        return view('userManagement.dashboard');
    }
}
