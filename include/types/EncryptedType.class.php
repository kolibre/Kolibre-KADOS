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

require_once('EncryptionMethodType.class.php');
require_once('KeyInfoType.class.php');
require_once('CipherDataType.class.php');
require_once('EncryptionPropertiesType.class.php');

class EncryptedType extends AbstractType {

    /**
     * @var (object)EncryptionMethodType
     */
    //public $EncryptionMethod;

    /**
     * @var (object)KeyInfoType
     */
    //public $KeyInfo;

    /**
     * @var (object)CipherDataType
     */
    public $CipherData;

    /**
     * @var (object)EncryptionPropertiesType
     */
    //public $EncryptionProperties;

    /**
     * @var ID
     */
    //public $Id;

    /**
     * @var anyURI
     */
    //public $Type;

    /**
     * @var string
     */
    //public $MimeType;

    /**
     * @var anyURI
     */
    //public $Encoding;

}

?>
