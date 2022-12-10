<?php

namespace App\Manager;

use Dompdf\Dompdf;

class PdfManager
{
    /** @var Dompdf */
    private $dompdf;

    public function __construct()
    {
        $this->dompdf = new Dompdf();

    }

    public function generate($html)
    {
        $this->dompdf->loadHtml($html);
        $this->dompdf->render();

        return $this->dompdf->output();
    }


}