<?php

namespace SafePHP;

use InvalidArgumentException;

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

    /**
     * Create user permissions to add in your user's session
     * @param int $indexPermission
     */
    public function __construct(int $indexPermission) {
        $this->actionsAdmin = ["Manage", "View Logs", "Edit", "View"];
        $this->actionsMod = ["View Logs", "Edit", "View"];
        $this->actionMember = ["View"];
        $this->roleName = ["Administrator", "Moderator", "Member"];

        $this->permissions = [
            $this->roleName[0] => $this->actionsAdmin,
            $this->roleName[1] => $this->actionsMod,
            $this->roleName[2] => $this->actionMember,
        ];

        if (array_key_exists($indexPermission, $this->roleName)) {
            $roleName = $this->roleName[$indexPermission];
            $this->userPerms = $this->permissions[$roleName];
        } else {
            throw new InvalidArgumentException(
                "Invalid role index: $indexPermission. Valid indexes are 0 (Administrator), 1 (Moderator), or 2 (Member)."
            );
        }
    }

    /**
     * Get user permissions
     * @return array
     */
    public function getPermsUser() {
        return $this->userPerms;
    }

    public function getAllPerms(){
        return $this->permissions;
    }
}