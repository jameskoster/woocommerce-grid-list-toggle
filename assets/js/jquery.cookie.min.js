/*jshint eqnull:true *//*!
 * jQuery Cookie Plugin v1.2
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2011, Klaus Hartl
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.opensource.org/licenses/GPL-2.0
 */(function(e,t,n){function i(e){return e}function s(e){return decodeURIComponent(e.replace(r," "))}var r=/\+/g;e.cookie=function(r,o,u){if(o!==n&&!/Object/.test(Object.prototype.toString.call(o))){u=e.extend({},e.cookie.defaults,u);o===null&&(u.expires=-1);if(typeof u.expires=="number"){var a=u.expires,f=u.expires=new Date;f.setDate(f.getDate()+a)}o=String(o);return t.cookie=[encodeURIComponent(r),"=",u.raw?o:encodeURIComponent(o),u.expires?"; expires="+u.expires.toUTCString():"",u.path?"; path="+u.path:"",u.domain?"; domain="+u.domain:"",u.secure?"; secure":""].join("")}u=o||e.cookie.defaults||{};var l=u.raw?i:s,c=t.cookie.split("; ");for(var h=0,p;p=c[h]&&c[h].split("=");h++)if(l(p.shift())===r)return l(p.join("="));return null};e.cookie.defaults={};e.removeCookie=function(t,n){if(e.cookie(t,n)!==null){e.cookie(t,null,n);return!0}return!1}})(jQuery,document);