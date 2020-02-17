<?php
use  System\BaseMvc\Route;

/*
 * Route::get("/","class@method") - supported
 * Route::post("/","class@method"); - supported
 * Route::put("/","class@method"); - supported
 * Route::delete("/","class@method"); - supported
 *
 * Parameters
 *
 * Route::get("/users/{id}","class@method");  -supported
 *
 * Route::get("/post/{url}.html","class@method");  -not supported
 */

Route::get("/","HelloController@hello");
