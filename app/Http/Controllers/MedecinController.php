<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use Illuminate\Http\Request;
use Inertia\Inertia;
use function Termwind\render;

class MedecinController extends Controller
{
    public function index(Request $request)
    {

        return Inertia::render("Medecin/dashboard");
    }
    public function patient()
    {
        return Inertia::render("Medecin/patients/index");
    }
    public function consultation()
    {
        return Inertia::render("Medecin/consultation/index");
    }
    public function parametre()
    {
        return Inertia::render("Medecin/parametre");
    }
}

?>

