<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartStoreRequest extends FormRequest
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
            'name' => 'required',
            'fotos' => 'required',
            'profile_id' => 'required_without_all',
//            'client_ids.required' => 'required_without_all',
        ];
    }
    //Mensagem personalizada
    public function messages()
    {
        return [
            'name.required' => 'Campo nome é obrigatório',
            'fotos.required' => 'Selecione pelo menos uma foto',
            'profile_id.required' => 'Campo obrigatório',
//            'client_ids.required' => 'Selecione pelo menos uma produtora',
        ];
    }
}
