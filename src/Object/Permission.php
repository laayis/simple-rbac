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

namespace doganoo\SimpleRBAC\Object;

use doganoo\PHPAlgorithms\Common\Interfaces\Comparable;
use doganoo\PHPAlgorithms\Datastructure\Graph\Tree\BinarySearchTree;

/**
 * Class Permission
 *
 * @package doganoo\SimpleRBAC
 * @deprecated do not use this class as it will be removed in the future. Use IPermission instead
 */
class Permission implements Comparable {
    /** @var int $id */
    private $id;
    /** @var string $name */
    private $name;
    /** @var null|BinarySearchTree */
    private $roles = null;

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name) {
        $this->name = $name;
    }

    /**
     * @return BinarySearchTree|null
     */
    public function getRoles(): ?BinarySearchTree {
        return $this->roles;
    }

    /**
     * @param BinarySearchTree|null $roles
     */
    public function setRoles(?BinarySearchTree $roles): void {
        $this->roles = $roles;
    }

    /**
     * @param $object
     * @return int
     */
    public function compareTo($object): int {
        if (!$object instanceof Permission) {
            return -1;
        }
        if ($this->getId() < $object->getId()) {
            return -1;
        }
        if ($this->getId() == $object->getId()) {
            return 0;
        }
        if ($this->getId() > $object->getId()) {
            return 1;
        }
        return -1;
    }

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id) {
        $this->id = $id;
    }
}