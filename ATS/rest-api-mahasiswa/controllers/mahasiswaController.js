const Mahasiswa = require('../models/Mahasiswa');

// GET semua mahasiswa
exports.getAllMahasiswa = async (req, res) => {
  try {
    const mahasiswas = await Mahasiswa.find();
    res.status(200).json(mahasiswas);
  } catch (err) {
    res.status(500).json({ message: err.message });
  }
};

// GET detail mahasiswa
exports.getMahasiswaById = async (req, res) => {
  try {
    const mahasiswa = await Mahasiswa.findById(req.params.id);
    if (!mahasiswa) return res.status(404).json({ message: 'Mahasiswa tidak ditemukan' });
    res.status(200).json(mahasiswa);
  } catch (err) {
    res.status(500).json({ message: err.message });
  }
};

// POST tambah mahasiswa
exports.createMahasiswa = async (req, res) => {
  const { nama, npm, prodi, angkatan } = req.body;

  // Validasi input
  if (!nama || !npm || !prodi || !angkatan) {
    return res.status(400).json({ message: 'Semua field wajib diisi' });
  }

  const mahasiswaBaru = new Mahasiswa({ nama, npm, prodi, angkatan });

  try {
    const savedMahasiswa = await mahasiswaBaru.save();
    res.status(201).json(savedMahasiswa);
  } catch (err) {
    res.status(400).json({ message: err.message });
  }
};

// PUT update mahasiswa
exports.updateMahasiswa = async (req, res) => {
  try {
    const updatedMahasiswa = await Mahasiswa.findByIdAndUpdate(
      req.params.id,
      req.body,
      { new: true, runValidators: true }
    );
    if (!updatedMahasiswa) return res.status(404).json({ message: 'Mahasiswa tidak ditemukan' });
    res.status(200).json(updatedMahasiswa);
  } catch (err) {
    res.status(400).json({ message: err.message });
  }
};

// DELETE mahasiswa
exports.deleteMahasiswa = async (req, res) => {
  try {
    const deletedMahasiswa = await Mahasiswa.findByIdAndDelete(req.params.id);
    if (!deletedMahasiswa) return res.status(404).json({ message: 'Mahasiswa tidak ditemukan' });
    res.status(200).json({ message: 'Mahasiswa berhasil dihapus' });
  } catch (err) {
    res.status(500).json({ message: err.message });
  }
};