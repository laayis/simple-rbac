<?php
/**
 * MIT License
 *
 * Copyright (c) 2018 Dogan Ucar
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace doganoo\SimpleRBAC\Handler;

use doganoo\PHPAlgorithms\Algorithm\Traversal\PreOrder;
use doganoo\PHPAlgorithms\Datastructure\Graph\Tree\BinarySearchTree;
use doganoo\SimpleRBAC\Common\IDataProvider;

/**
 * Class PermissionHandler
 *
 * @package doganoo\SimpleRBAC\Handler
 */
class PermissionHandler {
    /** @var IDataProvider $dataProvider */
    private $dataProvider = null;

    /**
     * PermissionHandler constructor.
     *
     * @param IDataProvider $dataProvider
     */
    public function __construct(IDataProvider $dataProvider) {
        $this->dataProvider = $dataProvider;
    }

    /**
     * returns a boolean that determines whether the user has the permission or not
     *
     * TODO break the traversal when $found is true
     *
     * @param int $id
     * @return bool
     */
    public function hasPermission(int $id): bool {
        if ($this->isDefaultPermission($id)) {
            return true;
        }
        $user = $this->dataProvider->getUser();
        if (null === $user) {
            return false;
        }
        if (null === $user->getRoles()) {
            return false;
        }
        $permission = $this->dataProvider->getPermission($id);
        if (null === $permission) {
            return false;
        }
        $roles = $user->getRoles();
        $inOrder = new PreOrder($roles);
        $found = false;
        $inOrder->setCallable(
            function ($userRoleId) use ($permission, &$found) {
                $permissionRoles = $permission->getRoles();
                $node = $permissionRoles->search($userRoleId);
                if (null !== $node) {
                    $found = true;
                }
            });
        $inOrder->traverse();
        return $found;
    }

    /**
     * returns a boolean whether the permission is a default permission or not
     * (for public menu entries, e.g.)
     *
     * @param int $id
     * @return bool
     */
    private function isDefaultPermission(int $id) {
        /** @var null|BinarySearchTree $defaultPermissionsMap */
        $defaultPermissionsTree = $this->dataProvider->getDefaultPermissions();
        $node = $defaultPermissionsTree->search($id);
        if (null === $node) {
            return false;
        }
        return true;
    }

}