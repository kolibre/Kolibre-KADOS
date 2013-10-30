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

require_once('AbstractType.class.php');

require_once('KeyValueType.class.php');
require_once('RetrievalMethodType.class.php');
require_once('X509DataType.class.php');
require_once('PGPDataType.class.php');
require_once('SPKIDataType.class.php');

class KeyInfoType extends AbstractType {

    // You may set only one from the following set
    // ---------------Start Choice----------------

    /**
     * @var string
     */
    public $KeyName;

    /**
     * @var (object)KeyValueType
     */
    public $KeyValue;

    /**
     * @var (object)RetrievalMethodType
     */
    public $RetrievalMethod;

    /**
     * @var (object)X509DataType
     */
    public $X509Data;

    /**
     * @var (object)PGPDataType
     */
    public $PGPData;

    /**
     * @var (object)SPKIDataType
     */
    public $SPKIData;

    /**
     * @var string
     */
    public $MgmtData;

    /**
     * @var anonymous4
     */
    public $anonymous4;

    /**
     * @var ID
     */
    public $Id;
    // ----------------End Choice---------------


}

?>
