<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class PartidoRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
             'torneo'           => 'required|numeric|min:1',
             'fecha'            => 'required|date',
             'jornada'          => 'required|numeric|min:1|max:100',
             'lugar'            => 'required|max:200',
             'equipo_local'     => 'required|numeric|min:1',
             'equipo_visitante' => 'required|numeric|min:1',
             'gol_local'        => 'required|numeric|min:0|max:100',
             'gol_visitante'    => 'required|numeric|min:0|max:100',
        ];
    }
}
