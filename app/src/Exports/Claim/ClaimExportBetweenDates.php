<?php

namespace App\src\Exports\Claim;


use App\src\Models\Claim;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClaimExportBetweenDates implements FromCollection, WithHeadings
{

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            '#',
            'Краткое описание',
            'Описание',
            'Имя заявителя',
            'Отчетство заявителя',
            'Фамилия заявителя',
            'Телефон',
            'Эл.почта',
            'ИД адреса',
            'Создана',
            'Обновлена',
        ];
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return Claim::all();
    }
}