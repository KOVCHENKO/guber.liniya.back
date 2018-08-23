<?php

namespace App\src\Exports\Claim;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClaimExportByProblemTypes implements FromCollection, WithHeadings
{
    use Exportable;

    protected $problemTypes;

    /**
     * ClaimExportByProblemTypes constructor.
     * @param $problemTypes
     */
    public function __construct(array $problemTypes)
    {
        $this->problemTypes = $problemTypes;
    }


    /**
     * @return void
     */
    public function collection()
    {
        // TODO: Implement collection() method.
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // TODO: Implement headings() method.
    }
}