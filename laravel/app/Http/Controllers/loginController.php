<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use LdapRecord\Container;
use App\Ldap\User;
use App\User as dbUser;

class loginController extends Controller
{
    public function __construct() {
        $this->ldapUser = new User();
        $this->dbUser = new dbUser();
        $this->connectionLdap = Container::getConnection('default');
    }

    public function loginPage(Request $request) {
        if($request->session()->get('auth-user')){
            return redirect($request->session()->get('auth-user')['default-menu-user']);
        }
        return view('login');
    }

    public function login(Request $request) {
        $totalAttempt = 0;
        $methodeLogin = '';
        // Input validation 
        $login = $request->validate([
            "username" => "required|string",
            "password" => "required|string"
        ]);

        // Escape string
        $login["username"] = addslashes($login["username"]);
        $login["password"] = addslashes($login["password"]);

        // ============================================= ATTEMPT CHECK ===========================================================

        // Check attempt
        $dataAttempt = $this->dbUser->checkAttempt($login["username"]);

        if($dataAttempt) {
            if($dataAttempt->attempt == 3) {
                // Set message error 
                $msg = $login["username"]." login failed because account blocked";

                // Add Log
                $this->dbUser->addLog($login["username"],2,$msg,session()->getId());

                // Username is not valid!
                return response()->json(["message" => $msg, "code" => 404]);
            }

            // Update total attempt
            $totalAttempt = $dataAttempt->attempt;
        }    

        // ============================================== WHITELIST CHECK ======================================================

        // Check whitelist user in table users
        $state = $this->dbUser->userCheck($login["username"]);

        if($state == 0) {
            if($totalAttempt == 0) {
                // Set attempt
                $this->dbUser->addAttempt($login["username"], $totalAttempt+1);
            } else {
                // Update total attempt
                $this->dbUser->updateAttempt($login["username"], $totalAttempt+1);
            }

            // Set message error 
            $msg = $login["username"]." can't login because you are not in whitelist.";

            // Add Log
            $this->dbUser->addLog($login["username"],2,$msg,session()->getId());

            // Password is not valid!
            return response()->json(["message" => $msg , "code" => 404]);

            // =================================== OLD METHODE ===========================
            // Insert to user table
            // $this->dbUser->insertUser($user->extensionattribute7[0],$login["username"]);
        }  

        // =================================================== CHOOSING METHODE LOGIN =========================================== 

        // Get profile user
        $userProfile = $this->dbUser->getProfile($login["username"]);

        if(!$userProfile) {
            if($totalAttempt == 0) {
                // Set attempt
                $this->dbUser->addAttempt($login["username"], $totalAttempt+1);
            } else {
                // Update total attempt
                $this->dbUser->updateAttempt($login["username"], $totalAttempt+1);
            }

            // Set message error 
            $msg = "data user ". $login["username"]." not complete. please complete it.";

            // Add Log
            $this->dbUser->addLog($login["username"],2,$msg,session()->getId());

            // Password is not valid!
            return response()->json(["message" => $msg , "code" => 404]);
        }

        // Choose login methode
        if(!empty($userProfile->password) && $userProfile->password == "ldap"){
            // Username checking on ldap
            $user = $this->ldapUser->getUserDN($login["username"]);

            if($user == "Not Found!") {
                if($totalAttempt == 0) {
                    // Set attempt
                    $this->dbUser->addAttempt($login["username"], $totalAttempt+1);
                } else {
                    // Update total attempt
                    $this->dbUser->updateAttempt($login["username"], $totalAttempt+1);
                }

                // Set message error 
                $msg = "Invalid username for credential";

                // Add Log
                $this->dbUser->addLog($login["username"],2,$msg,session()->getId());

                // Username is not valid!
                return response()->json(["message" => $msg, "code" => 404]);
            }

            if (!$this->connectionLdap->auth()->attempt($user->getDn(), $login["password"])) {
                if($totalAttempt == 0) {
                    // Set attempt
                    $this->dbUser->addAttempt($login["username"], $totalAttempt+1);
                } else {
                    // Update total attempt
                    $this->dbUser->updateAttempt($login["username"], $totalAttempt+1);
                }

                // Set message error 
                $msg = "Invalid password for credential";

                // Add Log
                $this->dbUser->addLog($login["username"],2,$msg,session()->getId());
                
                // Password is not valid!
                return response()->json(["message" => $msg , "code" => 404]);
            }

            $methodeLogin = 'ldap';
        } else {
            $state = $this->dbUser->loginWithDB($login["username"],$login["password"]);

            // If auth failed
            if($state == 0){
                if($totalAttempt == 0) {
                    // Set attempt
                    $this->dbUser->addAttempt($login["username"], $totalAttempt+1);
                } else {
                    // Update total attempt
                    $this->dbUser->updateAttempt($login["username"], $totalAttempt+1);
                }

                // Set message error 
                $msg = "Invalid username or password for credential";

                // Add Log
                $this->dbUser->addLog($login["username"],2,$msg,session()->getId());
                
                // Password is not valid!
                return response()->json(["message" => $msg , "code" => 404]);
            }
            $methodeLogin = 'db';
        }

        // ======================================================== CREATE TOKEN ===============================================

        // Get id user
        $userid = $this->dbUser->getID($login["username"]);

        // Delete access token if exist by user id
        $this->dbUser->deleteToken($userid->id);

        // Get instance user
        $dataDBUser = $this->dbUser::find($userid->id);

        // Create access token user
        $accessToken = $dataDBUser->createToken('authToken')->accessToken;

        // ======================================================== CREATE SESSION ===============================================

        if($methodeLogin == 'ldap') {
            $arraySession = [
                'token-user' => $accessToken,
                "username-user" => $login["username"], 
                "unik-lokasi" => $userProfile->unik_lokasi,
                "title-user" => $user->title[0],
                "nama-lengkap-user" => $user->extensionattribute7[0], 
                "nik-user" => $user->extensionattribute1[0],
                "no-telp" => $user->telephonenumber[0],
                "role" => $userProfile->role,
                "default-menu-user" => $userProfile->default_menu
            ];
        } else {
            $arraySession = [
                'token-user' => $accessToken,
                "username-user" => $login["username"], 
                "unik-lokasi" => $userProfile->unik_lokasi,
                "title-user" => $userProfile->jabatan,
                "nama-lengkap-user" => $userProfile->nama_lengkap, 
                "nik-user" => $userProfile->nik,
                "no-telp-user" => $userProfile->no_telp,
                "role-user" => $userProfile->role,
                "default-menu-user" => $userProfile->default_menu
            ];
        }
        
        // Set session username and token
        session(['auth-user' => $arraySession]);

        // Remove attempt
        $this->dbUser->removeAttempt($login["username"]);

        // Add Log
        $this->dbUser->addLog($login["username"],1,$login["username"]." login Dacita successfully",session()->getId());

        return response()->json([
            "code" => 200,
            "menu" => $userProfile->default_menu,
            "user" => $login["username"],
            "token" => $accessToken,
        ]);   
    }
    
    public function getMenuTreee($arrayForPages, $parent = '-')
    {
        $menuTree = [];
        // Mapping menu 
        foreach ($arrayForPages[$parent] as $page) {
            $newMenu = new \stdClass();
            $newMenu->menu_id = $page->menu_id;
            $newMenu->menu_name = $page->menu_name;
            $newMenu->link = $page->link;
            // check if there are children for this item
            if (isset($arrayForPages[$page->menu_id])) {
                $newMenu->submenu = $this->getMenuTreee($arrayForPages, $page->menu_id); // and here we use this nested function recursively
            }
            $menuTree[] = $newMenu;
        }
        return $menuTree;
    }

    public function generateChildHTML($menu) {
        $allMenu = '';
        foreach($menu as $menu) {
            $newMenu = '';
            if (isset($menu->submenu)) {
                $newMenu = '<li><a href="javascript:void(0)" class="has-arrow">'.$menu->menu_name.'</a>';
                $newMenu .='<ul aria-expanded="false" class="collapse">';
                $newMenu .= $this->generateChildHTML($menu->submenu);
                $newMenu .='</ul>';
                $newMenu .= '</li>'; 
            } else {
                $newMenu = '<li><a href="/dgs'.$menu->link.'">'.$menu->menu_name.'</a></li>';
            }       
            $allMenu .= $newMenu;
        }

        return $allMenu;
    }

    public function generateMenuHTML($arrayMenu) {
        $allMenu = '';
        foreach($arrayMenu as $menu) {
            $newMenu = '';
            $newMenu = '<li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><span class="hide-menu">'.$menu->menu_name.'</span></a>';
            if (isset($menu->submenu)) {
                $newMenu .='<ul aria-expanded="false" class="collapse">';
                $newMenu .= $this->generateChildHTML($menu->submenu);
                $newMenu .='</ul>';
            }
            $newMenu .= '</li>';
            $allMenu .= $newMenu;
        }

        return $allMenu;
    }

    public function pleaseWait() {
        $ss = session()->get('auth-user');
        $data_menu = $this->dbUser->getMenuUser($ss['username-user']);
        $arrayForPages = [];

        // Mapping by parent
        foreach ($data_menu as $page)
            $arrayForPages [$page->parent][] = $page;

        // return $arrayForPages;
        $dynamicMenu = $this->getMenuTreee($arrayForPages);
        $htmlMenu = $this->generateMenuHTML($dynamicMenu);
        
        // return $htmlMenu;
        // Save menu in session 
        session(['menu-user'=>$htmlMenu]);
        return redirect($ss['default-menu-user']);
    }

    public function logout(Request $request) {
        // Delete session
        $request->session()->flush();

        return response()->json(["code"=>200, "message" => "Terimkasih sudah berkunjung:)"]);
    }
}
