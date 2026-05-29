<?php

namespace App\Filament\Pages\Auth;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Filament\Notifications\Notification;
use Filament\Auth\Pages\Login as BaseLogin;

use App\Models\LogAktivitas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;

use Filament\Actions\Action;

use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;

use DiogoGPinto\AuthUIEnhancer\Pages\Auth\Concerns\HasCustomLayout;
use Lunaweb\RecaptchaV3\Facades\RecaptchaV3;

class Login extends BaseLogin
{
    use HasCustomLayout;

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('email')
                ->label('Email')
                ->required()
                ->email()
                ->autocomplete()
                ->autofocus()
                ->validationMessages([
                    'required' => 'Email tidak boleh kosong.',
                    'email' => 'Email tidak valid.'
                ]),
            TextInput::make('password')
                ->label('Password')
                ->required()
                ->password()
                ->autocomplete()
                ->validationMessages([
                    'required' => 'Password tidak boleh kosong.',
                ]),
            ViewField::make('recaptcha')
                ->view('components.recaptchav3')
                ->dehydrated(false),
        ]);
    }

    public ?string $captchaToken = '';
    public function setCaptchaToken(string $token): void
    {
        $this->captchaToken = $token;
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'email' => $data['email'],
            'password' => $data['password'],
        ];
    }

    public function authenticate(): LoginResponse
    {
        $token = $this->captchaToken;

        if (! $token) {
            Notification::make()
                ->title('Verifikasi reCAPTCHA gagal. Silakan muat ulang halaman.')
                ->danger()
                ->send();

            throw ValidationException::withMessages([
                'data.email' => 'Verifikasi reCAPTCHA gagal. Token tidak ditemukan.',
            ]);
        }

        $score = RecaptchaV3::verify($token, 'login');

        if ($score === false || $score < (float) config('recaptchav3.threshold', 0.5)) {
            Notification::make()
                ->title('Verifikasi reCAPTCHA gagal. Silakan coba lagi.')
                ->danger()
                ->send();

            throw ValidationException::withMessages([
                'data.email' => 'Verifikasi reCAPTCHA gagal.',
            ]);
        }

        $response = parent::authenticate();

        // Dipanggil hanya jika login berhasil
        $request = request();
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Berhasil Login',
            'ip_address' => $request->ip(),
            'location' => $request->ipLocation(),
            'user_agent' => $request->userAgent(),
            'keterangan' => 'Berhasil login sebagai '.auth()->user()->name,
        ]);

        return $response;
    }

    protected function throwFailureValidationException(): never
    {
        Notification::make()
            ->title('Email / Password anda salah.')
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
            'keterangan' => 'mencoba login sebagai email: ' . $data['email'],
        ]);

        throw ValidationException::withMessages([
            'data.email' => '',
        ]);
    }
}