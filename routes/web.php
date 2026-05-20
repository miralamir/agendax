<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get("/", function () {
    return view("welcome");
});

Route::get("/dashboard", function () {
    return view("dashboard");
})->middleware(["auth", "verified"])->name("dashboard");

Route::middleware("auth")->group(function () {
    Route::get("/profile", [ProfileController::class, "edit"])->name("profile.edit");
    Route::patch("/profile", [ProfileController::class, "update"])->name("profile.update");
    Route::delete("/profile", [ProfileController::class, "destroy"])->name("profile.destroy");
});

require __DIR__."/auth.php";


Route::get("/run-migrations-a7b3c9d8", function () {
    try {
        \Illuminate\Support\Facades\Artisan::call("migrate:fresh", ["--seed" => true]);
        return "<h1>DONE: Migrations and seeding completed successfully!</h1>";
    } catch (\Exception $e) {
        return "<h1>ERROR:</h1><pre>" . $e->getMessage() . "</pre>";
    }
});

