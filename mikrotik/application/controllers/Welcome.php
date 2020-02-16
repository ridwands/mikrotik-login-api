<?php
defined('BASEPATH') or exit('No direct script access allowed');

//Load Speadshet library
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Welcome extends CI_Controller
{

    public function index()
    {   
       $this->load->view('v_index');
    }

     function action_login(){
         //Get Input Email & Password
         $email = $this->input->post('email');
         $password = $this->input->post('password');
 
         //Select Data Where Input Email & Password
         $this->db->from('login');
         $this->db->where('email', $email);
         $this->db->where('pass', $password);
         $user = $this->db->get()->result();
 
         //Checking Data
         if (empty($user)) {
             $this->session->set_flashdata('msg',
                 '<div class="alert alert-warning">
                     <h4>Username Atau Password Salah</h4>
                 </div>');
             redirect('welcome');
         } else {
             $data_session = array(
                 'email' => $user->email,
                 'logged' => true,
             );
             $this->session->set_userdata($data_session); //Create Session If Data User is available
             redirect('welcome/user');
         }
    }
    private function auth()
    {
        //Checking Session
        if ($this->session->logged != 'true') {
            return redirect('welcome');
        } else {
            return $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        }
    }

    public function logout()
    {
        //Logout Session
        $this->session->sess_destroy();
        redirect('welcome');
    }

    public function login()
    {
        $this->load->view('welcome_message');
    }

    public function user()
    {
        //Get Data From Table User
        $data['user'] = $this->db->get('user')->result();

        $this->load->view('v_user', $data);
    }

    public function edit($id)
    {
        //Get Data From User Where ID on Input
        $this->db->where('id', $id);
        $this->db->from('user');
        $data['user'] = $this->db->get()->result();

        $this->load->view('v_user_edit', $data);
    }

    public function delete($id)
    {
        $this->db->delete('user', array('id' => $id)); //Query Delete
        redirect('welcome/user');
    }

    public function update()
    {
        //Get String Data From From
        $id = $this->input->post('id');
        $email = $this->input->post('email');
        $pass = $this->input->post('pass');

        //Data on Database
        $data = array(
            'email' => $email,
            'pass' => $pass,
        );

        //Query Updae
        $this->db->where('id', $id);
        $this->db->update('user', $data);

        redirect('welcome/user');
    }

    function print() {
        $user = $this->db->query("select * from user")->result();
        //If Table User NULL
        if (empty($user)) {
            $this->session->set_flashdata('msg',
                '<div class="alert alert-warning">
                    <h4>Data Belum Ada</h4>
                </div>');
            redirect('welcome/user');
        } else {
            // Create new Spreadsheet object
            $spreadsheet = new Spreadsheet();
            //Title Merge Cell
            $spreadsheet->setActiveSheetIndex(0)
                ->mergeCells('A1:H1');
            $spreadsheet->getActiveSheet()
                ->getCell('A1')
                ->setValue('Print Data User ' . date('d-m-Y'))
                ->getStyle('A1:H1')->getAlignment()->setHorizontal('center');
            //Font Bold
            $spreadsheet->getActiveSheet()->getStyle('A2:H2')->getFont()->setBold(true);
            $spreadsheet->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);
            // Add some data
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('D2', 'Email')
                ->setCellValue('E2', 'Password')
            ;

            // Looping For Data User
            $i = 3;
            foreach ($user as $user) {

                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('D' . $i, $user->email)
                    ->setCellValue('E' . $i, $user->pass);
                $i++;
            }

            // Title On Spreadsheat
            $spreadsheet->getActiveSheet()->setTitle('User ' . date('d-m-Y H'));
            $spreadsheet->setActiveSheetIndex(0);

            //Name Output Spreadshet
            header('Content-Disposition: attachment;filename="User.xlsx"');
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
            exit;
        }
    }

    public function insert()
    {
        require './routeros-api/api.php'; //Load Mikrotik API

        $email = $this->input->post('email'); //Take Email Data
        $linklogin = $this->input->post('link');

        $API = new RouterosAPI(); //Create New Object From API
        $API->debug = false; //Debugging API

        if ($API->connect('192.168.1.1', 'admin', '123')) {

            $API->comm("/ip/hotspot/user/add", array(
                "name" => $email,
                "password" => $email,
            ));

            $API->write('/ip/hotspot/user/print');
            $API->read();
            $API->disconnect();

            //If Connect To Mikrotik, It will be upload email to mysql
            $data = array(
                'email' => $email,
                'pass' => $email,
            );

            $this->db->insert('user', $data);

            redirect($linklogin.'?username=admin&password=admin');  //Redirect to Google If Login is Success
        }
    }
}
