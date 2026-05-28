<?php

namespace App\Filament\Pages;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

use Filament\Pages\Page;
use BackedEnum;

// Form Components
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;

use Filament\Actions\Action;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ColorPicker;

use Filament\Notifications\Notification;

class Profile extends Page
{
    protected string $view = 'filament.pages.profile';
    protected static ?string $title = 'Profil Pengguna';
    protected static ?string $slug = 'profile';

    // Sembunyikan dari sidebar
    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];
    public function mount(): void
    {
        $user = User::first();
        $this->form->fill([
            'foto' => $user?->foto,
            'name' => $user?->name,
            'username' => $user?->username,
            'email' => $user?->email,
            'phone' => $user?->phone,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->columns(1)
            ->components([
                Grid::make()
                    ->columns(2)
                    ->components([
                        Section::make()
                            ->schema([
                                FileUpload::make('foto')
                                    ->hiddenLabel()
                                    ->disk('public')
                                    ->directory('profile-photos')
                                    ->image()
                                    ->avatar()
                                    ->imageEditor()
                                    ->alignCenter()
                                    ->maxSize(2048)
                                    ->validationMessages([
                                        'image' => 'File yang diunggah harus berupa gambar.',
                                        'max' => 'Ukuran file tidak boleh lebih dari 2MB.',
                                    ])
                                    ->deleteUploadedFileUsing(function (string $file) {
                                        Storage::disk('public')->delete($file);
                                    }),
                            ]),
                        Section::make('Informasi Pribadi')
                            ->description('Perbarui informasi profil Anda di sini.')
                            ->icon('heroicon-o-user')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama Lengkap')
                                    ->required()
                                    ->validationMessages([
                                        'required' => 'Nama lengkap wajib diisi.',
                                    ]),
                                Grid::make()
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('email')
                                            ->label('Email')
                                            ->email()
                                            ->required()
                                            ->validationMessages([
                                                'required' => 'Email wajib diisi.',
                                                'email' => 'Format email tidak valid.',
                                            ]),
                                        TextInput::make('phone')
                                            ->label('Nomor Telepon')
                                            ->tel()
                                            ->required()
                                            ->validationMessages([
                                                'required' => 'Nomor telepon wajib diisi.',
                                                'tel' => 'Format nomor telepon tidak valid.',
                                            ]),
                                    ]),
                            ]),
                        Section::make('Keamanan')
                            ->description('Anda dapat memperbarui password Anda di sini. Biarkan kosong jika tidak ingin mengubah password.')
                            ->icon('heroicon-o-lock-closed')
                            ->columnSpanFull()
                            ->schema([
                                TextInput::make('current_password')
                                    ->label('Password Saat Ini')
                                    ->password()
                                    ->revealable()
                                    ->required(fn ($get) => filled($get('password')))
                                    ->currentPassword()
                                    ->dehydrated(false)
                                    ->validationMessages([
                                        'required' => 'Password saat ini wajib diisi.',
                                        'current_password' => 'Password saat ini tidak cocok.',
                                    ]),
                                Grid::make()
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('password')
                                            ->label('Password')
                                            ->password()
                                            ->revealable()
                                            ->required(fn ($livewire) => $livewire instanceof CreateUser)
                                            ->dehydrated(fn ($state) => filled($state))
                                            ->minLength(8)
                                            ->validationMessages([
                                                'required' => 'Password wajib diisi.',
                                                'minLength' => 'Password minimal :min karakter.',
                                            ]),
                                        TextInput::make('password_confirmation')
                                            ->label('Konfirmasi Password')
                                            ->password()
                                            ->revealable()
                                            ->required(fn ($get) => filled($get('password')))
                                            ->dehydrated(false)
                                            ->same('password')
                                            ->validationMessages([
                                                'required' => 'Konfirmasi password wajib diisi.',
                                                'same' => 'Konfirmasi password tidak cocok dengan password.',
                                            ]),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan')
                ->submit('save'),
        ];
    }

    public function save()
    {
        $data = $this->form->getState();
        $user = auth()->user();

        // Hash password baru
        if (!empty($data['password'])) {
             $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']); // Jangan update password jika tidak diisi
        }

        $user->update($data);
        $user->createLogAktivitas('Memperbarui profil pengguna', 'Berhasil memperbarui informasi profilnya.');

        Notification::make()
            ->success()
            ->title('Profil berhasil diperbarui.')
            ->send();

        // Redirect kembali ke halaman profil setelah menyimpan
        $this->redirect(request()->header('Referer') ?? url()->current());
    }
}
