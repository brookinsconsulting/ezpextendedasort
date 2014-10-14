eZp Extended Asort
===================

This extension implements a basic eZ Publish template operator which provides the following capabilities:

- Template operator: 'extended_asort()'

Version
-------

- The current version of eZp Extended Asort is 0.0.2
- Last Major update: October 13, 2014

Copyright
---------

- eZp Extended Asort is copyright 1999 - 2014 Brookins Consulting and 2013 - 2014 Think Creative
- See: doc/COPYRIGHT.md for more information on the terms of the copyright and license

License
-------

eZp Extended Asort is licensed under the GNU General Public License.

The complete license agreement is included in the doc/LICENSE file.

eZp Extended Asort is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License or at your
option a later version.

eZp Extended Asort is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

The GNU GPL gives you the right to use, modify and redistribute
eZp Extended Asort under certain conditions. The GNU GPL license
is distributed with the software, see the file doc/LICENSE.

It is also available at http://www.gnu.org/licenses/gpl.txt

You should have received a copy of the GNU General Public License
along with eZp Extended Asort in doc/LICENSE.  If not, see http://www.gnu.org/licenses/.

Using eZp Extended Asort under the terms of the GNU GPL is free (as in freedom).

For more information or questions please contact
license@brookinsconsulting.com

Requirements
------------

The following requirements exists for using eZp Extended Asort extension:

eZ Publish version
- Make sure you use eZ Publish version 5.x (required) or higher.

PHP version
- Make sure you have PHP 5.x or higher.

Features
--------

- Naturally sorts an array using by the column $strSortBy

- Sorts input array by name of column parameter and column position paramter by sortBy parameter value 'ASC' or 'DESC'

- Returns the array sorted as required

Parameters
----------

- paramInput $aryData Array containing data to sort

- param1 $strIndex Name of column to use as an index

- param2 $strSortBy Column to sort the array by

- param3 $strSortType String containing either asc or desc [default to asc]

Usage
-----

- 1. {def $variable = $arrayOfArrays|extended_asort( array('name'), 2, 'ASC' )}

- 2. {def $variable = $arrayOfArrays|extended_asort( array('name'), 0 )}

- 3. {def $variable = $arrayOfArrays|extended_asort( array('priority'), 1, 'ASC' )}

Troubleshooting
---------------

Read the FAQ
- Some problems are more common than others. The most common ones are listed in the the doc/FAQ.

