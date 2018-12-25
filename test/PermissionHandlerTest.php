<?php
/**
 * MIT License
 * Copyright (c) 2018 Dogan Ucar
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

use DataProvider\DataProvider;
use DataProvider\MassiveDataProvider;
use doganoo\PHPAlgorithms\Datastructure\Graph\Tree\BinarySearchTree;
use doganoo\SimpleRBAC\Common\IUser;
use doganoo\SimpleRBAC\Handler\PermissionHandler;
use Util\PermissionUtil;
use Util\RoleUtil;

/**
 * Class PermissionHandlerTest
 */
class PermissionHandlerTest extends \PHPUnit\Framework\TestCase {
    /**
     * @throws \doganoo\PHPAlgorithms\Common\Exception\InvalidSearchComparisionException
     */
    public function testDataProvider() {
        $dataProvider = new DataProvider();
        $user = $dataProvider->getUser();
        $permission = $dataProvider->getPermission(1);
        $defaultPermissions = $dataProvider->getDefaultPermissions();
        $this->assertTrue($user instanceof IUser);
        $this->assertTrue($user->getId() === 1);
        $this->assertTrue($user->getRoles() instanceof BinarySearchTree);
        $this->assertTrue($user->getRoles()->height() === 3);
        $this->assertTrue($user->getRoles()->search(RoleUtil::toRole(5)) !== null);
        $this->assertTrue($user->getRoles()->search(RoleUtil::toRole(15)) === null);
        $this->assertTrue($permission->getId() === 1);
        $this->assertTrue($permission->getRoles() instanceof BinarySearchTree);
        $this->assertTrue($defaultPermissions->height() === 4);
        $this->assertTrue($defaultPermissions->search(PermissionUtil::toPermission(101)) !== null);
        $this->assertTrue($defaultPermissions->search(PermissionUtil::toPermission(10)) === null);
    }

    /**
     * @throws \doganoo\PHPAlgorithms\Common\Exception\InvalidSearchComparisionException
     * @throws \doganoo\PHPAlgorithms\Common\Exception\InvalidBitLengthException
     */
    public function testUserRolesAndPermissions() {
        $permissionHandler = new PermissionHandler(new DataProvider());
        $this->assertTrue($permissionHandler->hasPermission(PermissionUtil::toPermission(101)) === true);
        $permission = PermissionUtil::toPermission(1);
        $permission = PermissionUtil::addRoles($permission);
        $this->assertTrue($permissionHandler->hasPermission($permission) === true);
        $this->assertTrue($permissionHandler->hasPermission(PermissionUtil::toPermission(25)) === false);
    }

    /**
     * @throws \doganoo\PHPAlgorithms\Common\Exception\InvalidSearchComparisionException
     * @throws \doganoo\PHPAlgorithms\Common\Exception\InvalidBitLengthException
     */
    public function testMassiveData() {
        $permissionHandler = new PermissionHandler(new MassiveDataProvider());
        $this->assertTrue($permissionHandler->hasPermission(PermissionUtil::toPermission(1)) === false);
    }

    /**
     * @throws \doganoo\PHPAlgorithms\Common\Exception\InvalidSearchComparisionException
     * @throws \doganoo\PHPAlgorithms\Common\Exception\InvalidBitLengthException
     */
    public function testHasRole() {
        $permissionHandler = new PermissionHandler(new DataProvider());
        $role = RoleUtil::toRole(1);
        $hasRole = $permissionHandler->hasRole($role);
        $this->assertTrue($hasRole === false);
        $role = RoleUtil::toRole(5);
        $hasRole = $permissionHandler->hasRole($role);
        $this->assertTrue($hasRole === true);
    }

    public function testOwner() {
        $permission = PermissionUtil::toPermission(20, 1);
        $permissionHandler = new PermissionHandler(new DataProvider());
        $this->assertTrue(true === $permissionHandler->hasPermission($permission));


        $permission = PermissionUtil::toPermission(20, 5);
        $permissionHandler = new PermissionHandler(new DataProvider());
        $this->assertTrue(false === $permissionHandler->hasPermission($permission));
    }

}



