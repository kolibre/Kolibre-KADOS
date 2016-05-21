<?php

/*
 * Copyright (C) 2013 Kolibre
 *
 * This file is part of Kolibre-KADOS.
 * Kolibre-KADOS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 2.1 of the License, or
 * at your option) any later version.
 *
 * Kolibre-KADOS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with Kolibre-KADOS. If not, see <http://www.gnu.org/licenses/>.
 */

abstract class AbstractType {

    protected $error;
    private $name;

    public function getError()
    {
        return $this->error;
    }

    private function className($name)
    {
        if (is_null($this->name))
            $this->name = $name;
        return $name;
    }

    public function isString($var, $name, $values = null)
    {
        if (is_string($var) === false)
        {
            $this->error = $this->className(get_class($this)) . ".$name is not a string";
            return false;
        }
        if (!is_null($values) && !in_array($var, $values))
        {
            $this->error = $this->className(get_class($this)) . ".$name is not a supported value";
            return false;
        }

        return true;
    }

    public function isNoneEmptyString($var, $name)
    {
        if ($this->isString($var, $name) === false) return false;

        if (strlen($var) == 0)
        {
            $this->error = $this->className(get_class($this)) . ".$name is an empty string";
            return false;
        }

        return true;
    }

    public function isDateTimeString($var, $name)
    {
        if ($this->isNoneEmptyString($var, $name) === false) return false;

        if (preg_match('/\d{4}\-\d{2}\-\d{2}T\d{2}:\d{2}:\d{2}(\+\d{2}:\d{2}|Z)/', $var) != 1) {
            $this->error = $this->className(get_class($this)) . ".$name is not a valid date time string";
            return false;
        }
    }

    public function isInteger($var, $name, $max = null, $min = null)
    {
        if (is_int($var) === false)
        {
            $this->error = $this->className(get_class($this)) . ".$name is not an integer";
            return false;
        }
        if (!is_null($max) && $var > $max)
        {
            $this->error = $this->className(get_class($this)) . ".$name is not a supported value";
            return false;
        }
        if (!is_null($min) && $var < $min)
        {
            $this->error = $this->className(get_class($this)) . ".$name is not a supported value";
            return false;
        }

        return true;
    }

    public function isPositiveInteger($var, $name)
    {
        if ($this->isInteger($var, $name) === false) return false;

        if ($var < 0)
        {
            $this->error = $this->className(get_class($this)) . ".$name is a negative integer";
            return false;
        }

        return true;
    }

    public function isBoolean($var, $name)
    {
        if (is_bool($var) === false)
        {
            $this->error = $this->className(get_class($this)) . ".$name is not a boolean";
            return false;
        }

        return true;
    }

    public function isArray($var, $name)
    {
        if (is_array($var) === false)
        {
            $this->error = $this->className(get_class($this)) . ".$name is not an array";
            return false;
        }

        return true;
    }

    public function isNoneEmptyArray($var, $name, $max = null)
    {
        if ($this->isArray($var, $name) === false) return false;

        if (sizeof($var) == 0)
        {
            $this->error = $this->className(get_class($this)) . ".$name is an empty array";
            return false;
        }
        if (!is_null($max) && sizeof($var) > $max)
        {
            $this->error = $this->className(get_class($this)) . ".$name contains too many elements";
            return false;
        }

        return true;
    }

    public function isInstanceOf($var, $name, $class = null)
    {
        if (is_null($class)) $class = $name;
        if (is_a($var, $class) === false)
        {
            $this->error = $this->className(get_class($this)). ".$name is not an instance of class '$class'";
            return false;
        }

        return true;
    }

    public function isArrayOfString($var, $name, $values = null)
    {
        if ($this->isArray($var, $name) === false) return false;

        foreach ($var as $index => $element)
        {
            if ($this->isString($element, $name.'['.$index.']') === false)
                return false;
            if (!is_null($values) && !in_array($element, $values))
            {
                $this->error = $this->className(get_class($this)) . ".$name" . '[' . $index . ']' . " is not a supported value";
                return false;
            }
        }

        return true;
    }
    public function isArrayOfNoneEmptyString($var, $name)
    {
        if ($this->isArray($var, $name) === false) return false;

        foreach ($var as $index => $element)
        {
            if ($this->isNoneEmptyString($element, $name.'['.$index.']') === false)
                return false;
        }
    }

    public function isArrayOfInteger($var, $name)
    {
        if ($this->isArray($var, $name) === false) return false;

        foreach ($var as $index => $element)
        {
            if ($this->isInteger($element, $name.'['.$index.']') === false)
                return false;
        }

        return true;
    }

    public function isArrayOfBoolean($var, $name)
    {
        if ($this->isArray($var, $name) === false) return false;

        foreach ($var as $index => $element)
        {
            if ($this->isBoolean($element, $name.'['.$index.']') === false)
                return false;
        }

        return true;
    }

    public function isArrayOfInstanceOf($var, $name, $class = null)
    {
        if (is_null($class)) $class = $name;
        if ($this->isArray($var, $name) === false) return false;

        foreach ($var as $index => $element)
        {
            if ($this->isInstanceOf($element, $name.'['.$index.']', $class) === false)
                return false;
        }

        return true;
    }
}

?>
