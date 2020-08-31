<?php

 function fetch_data()  
 {  

        if (isset($_POST['unpaid'])) {
        $level = $_POST['level'];
        $fees = $_POST['fees'];
        $academic_year = $_POST['academic_year'];
        $term = $_POST['term'];

      }

      $output = '';  

      $conn = mysqli_connect("localhost", "root", "", "lilongwe_private");
      $sql = "SELECT amount, academic_year, term, students.firstname as firstname, students.middlename as middlename, students.lastname as lastname, students.student_no as student_no FROM students LEFT JOIN payments ON (payments.students_student_no=students.student_no) WHERE students.sub_classes_id='$level' AND academic_year IS NULL GROUP BY student_no ";


      $result = mysqli_query($conn, $sql);
      while($row = mysqli_fetch_array($result))  {

      	      	      $output .= '<tr>
                          <td>'.$row["student_no"].'</td>
                          <td>'.$row["firstname"].'</td>  
                          <td>'.$row["lastname"].'</td>
                          <td>'.$academic_year.'</td>
                          <td>'.$term.'</td>
                          <td>K'.number_format($fees).'</td>
                     </tr>
                          ';
      
 }


    
      return $output;
 }  


      require_once('tcpdf/tcpdf.php');

      // Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->Cell(0, 15, 'Reports', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}


      $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
      $obj_pdf->SetCreator(PDF_CREATOR);  
      $obj_pdf->SetTitle("Cases");
      // set default header data
$obj_pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
      //$obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);  
      $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));  
      $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
      $obj_pdf->SetDefaultMonospacedFont('helvetica');  
      $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
      $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '0', PDF_MARGIN_RIGHT);  
      $obj_pdf->setPrintHeader(false);  
      $obj_pdf->setPrintFooter(false);
      $obj_pdf->SetAutoPageBreak(TRUE, 10);  
      $obj_pdf->SetFont('helvetica', '', 7);
      $obj_pdf->AddPage();  
      $content = '';  
      $content .= '
      <br/>
      <h4 align="center"><b>Students without Payments Report</b></h4><br /> 
      <table border="1" cellspacing="0" cellpadding="1" style="width:100%">  
           <tr>  
              <th><b>Student No</b></th>  
              <th><b>First Name</b></th>
              <th><b>Last Name</b></th>
              <th><b>Academic Year</b></th>
              <th><b>Term</b></th>
              <th><b>Balance</b></th>
           </tr>
      ';
      $content .= fetch_data();  
      $content .= '</table>';  
      $obj_pdf->writeHTML($content);
      ob_end_clean();
      $obj_pdf->Output('No-Payment-report.pdf', 'I');
 
 ?>  