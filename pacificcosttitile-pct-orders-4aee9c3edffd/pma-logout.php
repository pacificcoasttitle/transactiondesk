<?php

session_start();
session_destroy();
header("location:pma-login.php");
