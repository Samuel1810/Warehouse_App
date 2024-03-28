<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Crie uma nova instância do controller.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Exibe o formulário de registro.
     */
    public function index()
    {
        return view('auth.register');
    }

    /**
     * Manipula o registro de um novo usuário após a validação.
     */
    public function store(Request $request)
    {
        try {
            // Validação dos dados do formulário
            $this->validator($request->all())->validate();
    
            // Criação de um novo usuário
            $data = $request->all();


            if (!isset($data['user_type'])) {
                $data['user_type'] = 0;
            }
    
            // Registra o usuário
            $this->create($data);

            // Adicione um log para verificar se o usuário foi criado com sucesso
            \Log::info('Usuário criado com sucesso');
    
            // Redireciona após o registro bem-sucedido
            return redirect(route('login.index'));
        } catch (\Exception $e) {
            // Adicione um log para verificar se há algum erro durante o registro
            \Log::error('Erro no registro: ' . $e->getMessage());
            
            return redirect()->route('register.index')->withErrors(['error'=> 'Email ou palavra-passe inválidos']);
        }
    }
    

    /**
     * Função de validação dos dados do formulário.
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ],[
            'email.required'=> 'O campo email é obrigatório!',
            'email.email'=> 'Esse campo tem que ter um email válido!',
            'password.required'=> 'O campo palavra-passe é obrigatório!',
            'password.min'=> 'Esse campo deve ter no mínimo 6 caractéres!'
        ]);
    }

    /**
     * Cria um novo usuário após a validação.
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'user_type' => $data['user_type'],
        ]);
    }
}
