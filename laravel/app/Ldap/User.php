<?php

namespace App\Ldap;

use LdapRecord\Models\Model;
use LdapRecord\Models\ActiveDirectory\User as Users;

class User extends Model
{
    /**
     * The object classes of the LDAP model.
     *
     * @var array
     */
    public static $objectClasses = [
        'top',
        'person',
        'organizationalperson',
        'user',
    ];

    public function getUserDN($username){
        try {
            $dn = Users::findByOrFail('samaccountname', $username);
        } catch (\LdapRecord\Models\ModelNotFoundException $ex) {
            $dn = "Not Found!";
        }
        return $dn;
    }
}
