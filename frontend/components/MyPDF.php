<?php
include(dirname(__FILE__)."/../../vendor/tcpdf/tcpdf.php");

class MyPDF extends TCPDF {

            public $lang;

            public function Header() {
                $bMargin = $this->getBreakMargin();
                $autoPageBreak = $this->AutoPageBreak;
                $this->SetAutoPageBreak(false, 0);
		$template_lang=$this->lang;
		if($template_lang=="by")
			$template_lang="ru";
		if($template_lang!="ru")
			$template_lang="en";
                $this->SetAutoPageBreak($autoPageBreak, $bMargin);
                $this->setPageMark();
            }

            public function setTitleFont() {
                $this->SetFont('freesansb', '', 32, '', true);
                $this->SetTextColor(3, 80, 134);
            }

            public function setHeaderFont($font) {
                $this->SetFont('freesansb', '', 14, '', true);
                $this->SetTextColor(83, 142, 213);
            }

            public function setSubHeaderFont() {
                $this->SetFont('freesansb', '', 16, '', true);
                $this->SetTextColor(51, 95, 146);
            }

            public function setDefaultFont() {
                $this->SetFont('freesans', '', 10, '', true);
                $this->SetTextColor(0, 0, 0);
            }

        }

?>