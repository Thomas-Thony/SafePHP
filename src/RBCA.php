<?php

namespace SafePHP;

use SafePHP\Exceptions;

/**
 *  Role-Based Access Control system
 */
class RBAC {
    private array $roleName = [];
    private array $actionsAdmin = [];
    private array $actionsMod = [];
    private array $actionMember = [];

    private array $permissions = [];

    private array $userPerms = [];

    public function __construct(int $indexPermission) {
        $this->actionsAdmin = ["Manage", "View Logs", "Edit", "View"];
        $this->actionsMod = ["View Logs", "Edit", "View"];
        $this->actionMember = ["View"];
        $this->roleName = ["Administrator", "Moderator", "Member"];

        $this->permissions = [
            $this->roleName[0] => [$this->actionsAdmin],
            $this->roleName[1] => [$this->actionsMod],
            $this->roleName[2] => [$this->actionMember],
        ];

        if(in_array($indexPermission, $this->permissions)){
            $this->userPerms = $this->permissions[$indexPermission];
        } else {
            Exceptions::setErreurCustom("None role found for this index");
        }
    }

    public function getPermsUser() {
        return $this->userPerms;
    }
}