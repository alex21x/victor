<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$filename = time()."_order.pdf";
		 
		$html = $this->load->view('welcome_message');
		 
		// unpaid_voucher is unpaid_voucher.php file in view directory and $data variable has infor mation that you want to render on view.
		 
		$this->load->library('M_pdf');
		 
		$this->m_pdf->pdf->WriteHTML($html);
		 
		//download it D save F.
		 
		$this->m_pdf->pdf->Output($filename, "F");		

	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */