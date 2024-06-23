<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;

class EditSettings extends Page implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.edit-settings';
    protected static ?string $navigationLabel = "Settings";
    protected static ?int $navigationSort = 6;

    public function mount()
    {
        $this->form->fill(Setting::get_settings());
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Basic Information')
                ->columns(2)
                ->schema([
                    TextInput::make('site_name')
                        ->required()
                        ->autofocus()
                        ->label('Site Name')
                        ->maxLength(255),

                    Textarea::make('address')
                        ->required()
                        ->rows(5)
                        ->maxLength(255)
                        ->label('Address'),

                    TextInput::make('phone1')
                        ->label('Phone 1')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('phone2')
                        ->label('Phone 2')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('phone3')
                        ->label('Phone 3')
                        ->maxLength(255),

                    TextInput::make('phone4')
                        ->label('Phone 4')
                        ->maxLength(255),

                    TextInput::make('admin_email')
                        ->label('Admin Email')
                        ->maxLength(255),

                    TextInput::make('support_email')
                        ->label('Support Email')
                        ->maxLength(255),
                ]),

            Section::make('Social Media')
                ->columns(2)
                ->schema([
                    TextInput::make('facebook')
                        ->label('Facebook Link')
                        ->maxLength(255),

                    TextInput::make('twitter')
                        ->label('Twitter Link')
                        ->maxLength(255),

                    TextInput::make('linkedin')
                        ->label('LinkedIn Link')
                        ->maxLength(255),

                    TextInput::make('pinterest')
                        ->label('Pinterest Link')
                        ->maxLength(255),

                    TextInput::make('gplus')
                        ->label('GPlus Link')
                        ->maxLength(255),

                    TextInput::make('youtube')
                        ->label('Youtube Link')
                        ->maxLength(255),

                    TextInput::make('gmap_api')
                        ->columnSpan(2)
                        ->label('Google Map API Key')
                        ->maxLength(255),

                    TextInput::make('latitude')
                        ->label('Latitude')
                        ->maxLength(255),

                    TextInput::make('longitude')
                        ->label('Latitude')
                        ->maxLength(255),
                ]),
        ])->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        try {
            $settings = $this->form->getState();
            foreach ($settings as $name => $value) {
                Setting::where('name', $name)->update(['value' => $value]);
            }
        } catch (Halt $exception) {
            return;
        }

        Notification::make()
            ->success()
            ->title(__('filament-panels::resources/pages/edit-record.notifications.saved.title'))
            ->send();
    }
}
