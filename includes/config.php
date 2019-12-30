<?php
/* it will turn output buffering on. while output buffering is active no output is sent from the script (other than headers), instead the output is stored in an internal buffer -> to avoid bugs in production */
ob_start();

/* start new or resume session */
session_start();

/* sets the default timezone used by all date/time functions in a script */
date_default_timezone_set("UTC");
