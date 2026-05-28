<?php

namespace App\Filament\Pages\Auth;

use App\Models\LogAktivitas;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Filament\Notifications\Notification;
use DiogoGPinto\AuthUIEnhancer\Pages\Auth\Concerns\HasCustomLayout;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use AbanoubNassem\FilamentGRecaptchaField\Forms\Components\GRecaptcha;

class Login extends BaseLogin
{
    use HasCustomLayout;

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('username')
                ->label('Username')
                ->required()
                ->autocomplete()
                ->autofocus()
                ->validationMessages([
                    'required' => 'Username tidak boleh kosong.',
                ]),
            TextInput::make('password')
                ->label('Password')
                ->required()
                ->password()
                ->autocomplete()
                ->validationMessages([
                    'required' => 'Password tidak boleh kosong.',
                ]),
            GRecaptcha::make('captcha')
                ->hiddenLabel()
                ->required()
                ->validationMessages([
                    'required' => 'Selesaikan proses verifikasi reCAPTCHA terlebih dahulu.',
                ]),
        ]);
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'username' => $data['username'],
            'password' => $data['password'],
        ];
    }

    public function authenticate(): LoginResponse
    {
        $response = parent::authenticate();

        // Dipanggil hanya jika login berhasil
        $request = request();
        LogAktivitas::create([
            'user_id'    => auth()->id(),
            'aktivitas'  => 'Berhasil Login',
            'ip_address' => $request->ip(),
            'location'   => $request->ipLocation(),
            'user_agent' => $request->userAgent(),
            'keterangan' => 'Berhasil login sebagai username: ' . auth()->user()->name,
        ]);

        return $response;
    }

    protected function throwFailureValidationException(): never
    {
        Notification::make()
            ->title('Username / Password anda salah.')
            ->danger()
            ->send();

        $data = $this->form->getState();
        $request = request();
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Gagal Login',
            'ip_address' => $request->realIp(),
            'location' => $request->ipLocation(),
            'user_agent' => $request->userAgent(),
            'keterangan' => 'mencoba login sebagai username: ' . $data['username'],
        ]);

        throw ValidationException::withMessages([
            'data.username' => '',
        ]);
    }
}