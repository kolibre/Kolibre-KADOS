What is Kolibre?
---------------------------------
Kolibre is a Finnish non-profit association whose purpose is to promote
information systems that aid people with reading disabilities. The software
which Kolibre develops is published under open source and made available to all
stakeholders at github.com/kolibre.

Kolibre is committed to broad cooperation between organizations, businesses and
individuals around the innovative development of custom information systems for
people with different needs. More information about Kolibres activities, association
embership and contact information can be found at http://www.kolibre.org/


What is Kolibre-KADOS?
---------------------------------

[![Build Status](https://travis-ci.org/kolibre/Kolibre-KADOS.svg?branch=master)](https://travis-ci.org/kolibre/Kolibre-KADOS)

Kolibre-KADOS is a PHP module for deploying a Daisy Online service onto an existing
user management and content delivery backend. It is fully compatible with the
DAISY Online Delivery Protocol and provides and adapter API for backend communication.


Documentation
---------------------------------
Source code is documented using doxygen. Generate documentation by executing

    $ doxygen doxygen.cfg
    
How to deploy a demo service is described here https://github.com/kolibre/Kolibre-KADOS/wiki


Platforms
---------------------------------
Kolibre-KADOS has been tested with Linux Debian Wheezy.


Dependencies
---------------------------------
Major dependencies for Kolibre-KADOS:

* php5
* log4php
* php5-sqlite (for use with demo adapter only)
* phpunit


Licensing
---------------------------------
Copyright (C) 2013 Kolibre

This file is part of Kolibre-KADOS.

Kolibre-KADOS is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 2.1 of the License, or
(at your option) any later version.

Kolibre-KADOS is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with Kolibre-KADOS. If not, see <http://www.gnu.org/licenses/>.
