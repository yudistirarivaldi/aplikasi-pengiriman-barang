<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/fpdf/fpdf.php';

class Pdf extends FPDF
{
    public function __construct()
    {
        parent::__construct();
    }
}
