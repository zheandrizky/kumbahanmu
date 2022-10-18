<?php
class Model_users extends CI_Model
{
	public $table = 'users';
	public function cekAkun($username, $password)
	{
		// Get data user yang mempunyai username == $username dan active == 1
		$this->db->select('*');
		$this->db->where('username', $username);
		$this->db->where('set_active', '1');
		//jalankan query
		$query = $this->db->get($this->table)->row();
		// Jika query gagal atau tidak menemukan username yang sesuai maka return false
		if (!$query) return false;

		// Ambil data password dari tabel
		$hash = $query->password;

		if (!password_verify($password, $hash)) return false;

		return $query;
	}
	public function logout($id)
	{
		$this->db
			->where('id_users', $id);
		// ->update($this->table, $date);
	}
	public function insert($data)
	{
		$query = $this->db->insert($this->table, $data);
		return $query;
	}
	public function get()
	{
		$query = $this->db->get($this->table);
		return $query;
	}
	public function getUsers()
	{
		$this->db->where('role_id', "admin");
		$query = $this->db->get($this->table);
		return $query;
	}
	public function getGuru()
	{
		$this->db->where('role_id', "guru");
		$query = $this->db->get($this->table);
		return $query;
	}
	public function admin()
	{
		// $this->db->from('users');
		$query = $this->db->get($this->table);
		return $query->result();
	}
	public function add($data)
	{
		$query = $this->db->insert($this->table, $data);
		return $query;
	}
	public function update($id, $data)
	{
		$query = $this->db
			->where('id_users', $id)
			->update($this->table, $data);

		return $query;
	}
	public function get_where($where)
	{
		$query = $this->db
			->where($where)
			->get($this->table);

		return $query;
	}
	public function delete($id)
	{
		$query = $this->db
			->where('id_users', $id)
			->delete($this->table);

		return $query;
	}
}
