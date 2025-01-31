<?php

namespace Konnco\FilamentSafelyDelete\Tables\Actions;

use Closure;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;
use Konnco\FilamentSafelyDelete\Concerns\HasFieldConfirmation;

class DeleteAction extends \Filament\Tables\Actions\DeleteAction
{
    use HasFieldConfirmation;

    protected function setUp(): void
    {
        parent::setUp();

        $this->form([
            TextInput::make('name')
                ->label(fn () => trans('filament-safely-delete::actions.input_label', ['name' => $this->getDeleteRecordConfirmationTypingText()]))
                ->rules([
                    function () {
                        return function (string $attribute, $value, Closure $fail) {
                            if ($value !== $this->getDeleteRecordConfirmationTypingText()) {
                                $fail(trans('filament-safely-delete::actions.validation', ['name' => $this->getDeleteRecordConfirmationTypingText()]));
                            }
                        };
                    },
                ])
                ->required(),
        ]);

        $this->action(function (array $data, Model $record): void {
            if ($data['name'] === $this->getDeleteRecordConfirmationTypingText()) {
                $this->process(static fn (Model $record) => $record->delete());
                $this->success();
            }
        });
    }
}
