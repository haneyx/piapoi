<?php
namespace App\Http\Controllers;

use App\Models\Cabeza;
use App\Models\Eess;
use App\Models\Oodd;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Log;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DetalleExport;
use App\Models\CabezaExcelData;

class CabezaController extends Controller
{
    public function ooddselect(Request $request)
    {
        Session::forget('idesx');
        $eess = DB::table('eess')
                            ->select('id','codigo', 'eess')
                            ->where('oodd_id',session('idredx'))
                            ->get();

                    return view('ooddselect', [
                        'organo' => session('redx'),
                        'eess' => $eess
                    ]);       
    }
    
    public function ooddmain(Request $request)
    {
        if (!session('idesx')) {
            Session::put('idesx', $request->input('ide_eess'));

            $eess = DB::table('eess')
                        ->select('codigo', 'eess')
                        ->where('id', $request->input('ide_eess'))
                        ->limit(1)
                        ->first();

             Session::put('codesx',$eess->codigo);
             Session::put('esx', $eess->eess);           
        }

        //obtener las actividades
        $results = DB::table('actioodd')
                ->join('cabeza', 'actioodd.id', '=', 'cabeza.actioodd_id')
                ->join('eess', 'cabeza.eess_id', '=', 'eess.id')
                ->where('eess_id', session('idesx')) 
                ->orderBy('actioodd.id')
                ->select('cabeza.id as cabeza_id','actioodd.id as actioodd_id','actioodd.actividad','actioodd.prioridad','cabeza.cerrado')
                ->get();

        return view('ooddmain', ['results' => $results]);        
    }

    public function ooddhoja(Request $request)
    {
        if ($request->input('actividadd')!=NULL) {
            Session::put('nameactiodx',$request->input('actividadd')); 
        } 
        if ($request->input('prioridadd')!=NULL) {
            Session::put('prioactiodx',$request->input('prioridadd')); 
        } 
        if ($request->input('cerradd')!=NULL) {
            Session::put('cerradox',$request->input('cerradd')); 
        }

        if ($request->input('cabezaidex')) {
            Session::put('headerx', $request->input('cabezaidex'));
        }

        $detalles = DB::table('detalle as d')
                ->leftJoin('financia as f', 'd.financia_id', '=', 'f.id')
                ->leftJoin('pofi as p', 'd.pofi_id', '=', 'p.id')
                ->select(
                    'd.cabeza_id',
                    'f.id as financia_id',
                    'f.codigo as financia_codigo',
                    'f.fondo',
                    'p.id as pofi_id',
                    'p.codigo as pofi_codigo',
                    'p.pofi',
                    'p.color',
                    'd.tipo',
                    'd.estimacion',
                    'd.enero',
                    'd.febrero',
                    'd.marzo',
                    'd.abril',
                    'd.mayo',
                    'd.junio',
                    'd.julio',
                    'd.agosto',
                    'd.septiembre',
                    'd.octubre',
                    'd.noviembre',
                    'd.diciembre',
                    'd.total2026',
                    'd.proy2027',
                    'd.proy2028',
                    'd.proy2029'
                )
                ->where('d.cabeza_id',session('headerx'))
                ->orderBy('d.pofi_id')
                ->get();

        return view('ooddhoja', compact('detalles'))
            ->with('cabezax',session('headerx'))
            ->with('cerradox', session('cerradox'));
        
    }

    public function cerrarooddhoja(Request $request)
    {
        $cabezaId = $request->input('cabezaidex');

        $cabeza = Cabeza::find($cabezaId);
        if ($cabeza) {
            $cabeza->cerrado = 1;
            $cabeza->save();
        }

        return redirect()->route('ooddmain')->with('success', 'Oodd cerrado exitosamente');
        
    }


    public function grabaooddhoja(Request $request)
    {
        if(session('headerx')){
            $totalFilasGrabar = $request->input('totalFilasGrabar'); 
            $DataGrabar = json_decode($request->input('DataGrabar'), true);
            $pofitemp = null; $fondotemp = null;

            DB::beginTransaction();
            try {
                DB::table('detalle')->where('cabeza_id',session('headerx'))->delete();
                foreach ($DataGrabar as $fila) {

                    if($fila['x']!=null){$fondotemp = DB::table('financia')->where('codigo', $fila['x'])->limit(1)->value('id');}
                    if($fila['y']!=null){$pofitemp = DB::table('pofi')->where('codigo', $fila['y'])->limit(1)->value('id');}
                    DB::table('detalle')->insert([
                        'cabeza_id' => session('headerx'),
                        'financia_id' => $fondotemp,  // Asignar el valor adecuado
                        'pofi_id' => $pofitemp,  // Asignar el valor adecuado
                        'tipo' => $fila['z'], 
                        'estimacion' => $fila['a'],
                        'enero' => $fila['b'],
                        'febrero' => $fila['c'],
                        'marzo' => $fila['d'],
                        'abril' => $fila['e'],
                        'mayo' => $fila['f'],
                        'junio' => $fila['g'],
                        'julio' => $fila['h'],
                        'agosto' => $fila['i'],
                        'septiembre' => $fila['j'],
                        'octubre' => $fila['k'],
                        'noviembre' => $fila['l'],
                        'diciembre' => $fila['m'],
                        'total2026' => $fila['n'],
                        'proy2027' => $fila['o'],
                        'proy2028' => $fila['p'],
                        'proy2029' => $fila['q'],
                    ]);
                    $pofitemp = null; $fondotemp = null;
                }

                DB::commit();
                Log::info('Datos guardados correctamente', ['Data' => $DataGrabar, 'Headerx' => session('headerx')]);


                return redirect()->route('ooddhoja');  
            } catch (\Exception $r) {
                DB::rollback();
                \Log::error("Error al insertar los datos: " . $r->getMessage());
                
                return back()->withErrors(['error' => 'Hubo un problema al guardar los datos.']);
            }
        }else{ return redirect()->route('login');}
    }

public function exportaooddhoja(Request $request)
{

      Log::info('Datos de la sesión:', [
        'redx' => session('redx'),
        'codesx' => session('codesx'),
        'esx' => session('esx'),
        'nameactiodx' => session('nameactiodx'),
        'prioactiodx' => session('prioactiodx'),
        'headerx' => session('headerx'),
        'cerradox' => session('cerradox')
    ]);


    // Obtener los detalles de la base de datos
    $detalles = DB::table('detalle as d')
        ->leftJoin('financia as f', 'd.financia_id', '=', 'f.id')
        ->leftJoin('pofi as p', 'd.pofi_id', '=', 'p.id')
        ->select(
            'd.cabeza_id',
            'f.id as financia_id',
            'f.codigo as financia_codigo',
            'f.fondo',
            'p.id as pofi_id',
            'p.codigo as pofi_codigo',
            'p.pofi',
            'p.color',
            'd.tipo',
            'd.estimacion',
            'd.enero',
            'd.febrero',
            'd.marzo',
            'd.abril',
            'd.mayo',
            'd.junio',
            'd.julio',
            'd.agosto',
            'd.septiembre',
            'd.octubre',
            'd.noviembre',
            'd.diciembre',
            'd.total2026',
            'd.proy2027',
            'd.proy2028',
            'd.proy2029'
        )
        ->where('d.cabeza_id', session('headerx'))
        ->orderBy('d.pofi_id')
        ->get();

    // Verificar si hay datos para exportar
    if ($detalles->isEmpty()) {
        \Log::warning('La colección de detalles está vacía');
    } else {
        \Log::info($detalles);
    }
    return Excel::download(new DetalleExport($detalles), 'oodd_hoja2.xlsx');
}



    public function consolida_red(Request $request)
    {
        $eess = session('eess');
        if ($eess) {
            // Pasamos los datos a la vista
            return view('ooddselect', compact('eess'));
        } else {
            // Si no hay datos, puedes redirigir o manejar el error de alguna forma
            return redirect()->route('login')->withErrors(['error' => 'Datos no encontrados.']);
        }
    }

}










