<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Entrega el SPA de demo-lecturas, que se compila dentro de la imagen y se
 * publica en public/.
 *
 * Sirve el mismo index.html para toda ruta de navegación porque quien las
 * resuelve es React Router en el navegador, no el servidor.
 */
class SpaController extends Controller
{
    public function __invoke(Request $request): BinaryFileResponse|Response
    {
        // Un endpoint inexistente del API es un 404 del API, no una ruta del
        // SPA: devolver HTML aquí escondería errores de integración.
        if ($request->is('api/*') || $request->is('up')) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $index = public_path('index.html');

        // En desarrollo el SPA lo sirve `npm run dev` aparte, así que este
        // fichero solo existe en la imagen de despliegue.
        if (! is_file($index)) {
            return response(
                'El SPA no está compilado en esta instalación. Para desarrollo, levántelo con "npm run dev" en demo-lecturas.',
                Response::HTTP_NOT_FOUND,
            );
        }

        return response()->file($index);
    }
}
