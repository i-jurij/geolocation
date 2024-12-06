//import "../css/style.css"; /* extract the styles to a external css bundle */
import * as styles from "../css/style.css"; /* import the styles as a string */
//import {styles} from '../css/style.css' assert { type: "css" }; /* import the styles as a CSSStyleSheet */

import { html } from "./geo/html.js";
html();

import { geoLocation } from './geo/geoLocation.js';
geoLocation();

import { fromDB } from './geo/fromDB.js';
fromDB();

// reread data from db with regions or city data of executors or customers from city of localstorage

/* <!-- js for esc on modal (in Home part of site that based on PicnicCSS) --> */
document.onkeydown = function (event) {
    if (event.key == "Escape") {
        var mods = document.querySelectorAll('.modal > [type=checkbox]');
        [].forEach.call(mods, function (mod) { mod.checked = false; });
    }
}

document.addEventListener('DOMContentLoaded', function () {
    if (navigator.cookieEnabled === false) {
        alert("Cookies отключены!");
    }
}, false)
