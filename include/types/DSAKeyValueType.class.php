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

class DSAKeyValueType extends AbstractType {

    // You need to set only one from the following two vars

    /**
     * @var Plain Binary
     */
    public $P;

    /**
     * @var base64Binary
     */
    public $P_encoded;


    // You need to set only one from the following two vars

    /**
     * @var Plain Binary
     */
    public $Q;

    /**
     * @var base64Binary
     */
    public $Q_encoded;


    // You need to set only one from the following two vars

    /**
     * @var Plain Binary
     */
    public $G;

    /**
     * @var base64Binary
     */
    public $G_encoded;


    // You need to set only one from the following two vars

    /**
     * @var Plain Binary
     */
    public $Y;

    /**
     * @var base64Binary
     */
    public $Y_encoded;


    // You need to set only one from the following two vars

    /**
     * @var Plain Binary
     */
    public $J;

    /**
     * @var base64Binary
     */
    public $J_encoded;


    // You need to set only one from the following two vars

    /**
     * @var Plain Binary
     */
    public $Seed;

    /**
     * @var base64Binary
     */
    public $Seed_encoded;


    // You need to set only one from the following two vars

    /**
     * @var Plain Binary
     */
    public $PgenCounter;

    /**
     * @var base64Binary
     */
    public $PgenCounter_encoded;


}

?>
