<?php defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // $this->cekLogin();
        $this->load->model('model_users');
    }
    public function cekAkun()
    {
        // Memanggil model users
        // $this->load->model('model_users');

        // Mengambil data dari form login dengan method POST
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        // Jalankan function cekAkun pada model_users
        $query = $this->model_users->cekAkun($username, $password);

        //as
        //print_r($query);die;
        // Jika query gagal maka return false
        if (!$query) {

            // Mengatur pesan error validasi data
            $this->form_validation->set_message('cekAkun', 'Username atau Password yang Anda masukkan salah!');
            $this->form_validation->set_message('cekAkunAktif', 'Username atau Password yang Anda masukkan salah!');
            return FALSE;

            // Jika berhasil maka set user session dan return true


            // data user dalam bentuk array
        } else {
            $userData = array(
                'id_users' => $query->id_users,
                'username' => $query->username,
                'email' => $query->email,
                'fullname' => $query->fullname,
                'role_id' => $query->role_id,
                'logged_in' => TRUE
            );

            // set session untuk user
            $this->session->set_userdata($userData);

            return TRUE;
        }
    }

    public function login()
    {
        // Jika user telah login, redirect ke base_url
        // if ($this->session->userdata('logged_in')) redirect(base_url());
        if ($this->session->userdata('logged_in')) redirect('Dashboard');
        // if ($this->session->userdata('level') != 'administrator') redirect('auth/blocked');

        // Jika form di submit jalankan blok kode ini
        if ($this->input->post('login')) {
            // echo '<script type="text/javascript">alert("ok");</script>';
            // Mengatur validasi data username,
            // required = tidak boleh kosong
            $this->form_validation->set_rules('username', 'Username', 'required');

            // Mengatur validasi data password,
            // required = tidak boleh kosong
            // callback_cekAkun = menjalankan function cekAkun()
            $this->form_validation->set_rules('password', 'Password', 'required|callback_cekAkun');

            // Mengatur pesan error validasi data
            $this->form_validation->set_message('required', '%s tidak boleh kosong!');

            // Jalankan validasi jika semuanya benar maka redirect ke controller dashboard
            if ($this->form_validation->run() === TRUE) {

                $message = array('status' => true, 'message' => 'SELAMAT DATANG DI APLIKASI SISFO LBB');
                $this->session->set_flashdata('message', $message);

                redirect('dashboard', 'refresh');
            }
        }

        $data['pageTitle'] = 'Absensi-Login';
        // Jalankan view auth/login.php
        $this->load->view('auth/login', $data);
    }

    function register()

    {
        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('passwordAgain', 'Repeat Password', 'trim|required|matches[password]');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('auth/register');
        } else {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $passwordAgain = $this->input->post('passwordAgain');


            $object = array(
                'username' => $username,
                'password' => md5($password),
                'view' => $passwordAgain

            );

            $query = $this->auth_model->register($object);
            if ($query) {
                echo
                "<script>
        alert('Register berhasil ');
        </script>";
                redirect('admin/list_soal', 'refresh');
            } else {
                echo
                "<script>
        alert('Register gagal ');
        </script>";
                redirect('admin/list_soal', 'refresh');
            }
        }
    }

    //Hak akses / akses diblokir
    public function blocked()
    {
        $data['pageTitle'] = '403 Forbidden';
        $data['pageContent'] = $this->load->view('auth/blocked.php', $data, TRUE);
        $this->load->view('template/layout', $data);
    }

    public function logout()
    {
        ini_set('date.timezone', 'Asia/Jakarta');
        $data = array(
            'last_login' => date('Y-m-d H:i:s')
        );
        $id = $this->session->userdata('id_user');
        $this->model_users->update($id, $data);
        // Hapus semua data pada session
        $this->session->sess_destroy();

        // redirect ke halaman login
        redirect('auth/login');
    }
}