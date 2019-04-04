<?php

/*
Plugin Name: http to https forced url
Description: change url http to https to get no more duplicate
Version: 1.0
Licence: GPLv2
Copytight {2017} {FacemWeb} (email: {a.pitula@facemweb.com})
This program is free software; you can redistribute it and/or modify it under the term of the GNU
General Public licence, version 2, as published by the Free Software Foundation

This program is distributed in the hope that it will be usefull, but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public Licence for more details.

You should have received a copy of the GNU General Public Licence along with this program; if not, write to the
Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/

function forcer_https () {
  if ( !is_ssl() ) {
    wp_redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301 );
    exit();
  }
}
add_action ( 'init', 'forcer_https', 1 );
