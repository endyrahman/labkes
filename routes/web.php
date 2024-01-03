<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pendaftaran\Pendaftaran;
use App\Http\Controllers\Registrasi\Registrasi;
use App\Http\Controllers\Pembayaran\Pembayaran;
use App\Http\Controllers\Dashboard\Dashboard;
use App\Http\Controllers\Pengguna\Pengguna;
use App\Http\Controllers\HomeCare\HomeCare;
use App\Http\Controllers\Pemulihan\Pemulihan;
use App\Http\Controllers\Pasien\Pasien;
use App\Http\Controllers\Master\MstJenisPemeriksaan;
use App\Http\Controllers\Master\MstParameterPemeriksaan;
use App\Http\Controllers\Master\MstPaketPemeriksaan;
use App\Http\Controllers\Master\MstPamflet;
use App\Http\Controllers\Landingpage\Landingpage;
use App\Http\Controllers\Landingpage\LandingpageMenu;
use App\Http\Controllers\Landingpage\LandingpageHalamandepan;
use App\Http\Controllers\Landingpage\LandingpageKegiatan;
use App\Http\Controllers\Landingpage\LandingpageLayanankami;
use App\Http\Controllers\Landingpage\LandingpagePromosi;
use App\Http\Controllers\Landingpage\LandingpageSlide;
use App\Http\Controllers\Pelaporan\Pelaporan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [Landingpage::class, 'index'])->name('landingpage');
Route::get('/labklinik', [Landingpage::class, 'indexLabKlinik']);
Route::get('/labklinik/paginationpemeriksaan', [Landingpage::class, 'paginationPemeriksaan']);
Route::get('/labkimia', [Landingpage::class, 'indexLabKimia']);
Route::get('/labkimia/paginationpemeriksaan', [Landingpage::class, 'paginationPemeriksaan']);
Route::get('/labmikrobiologi', [Landingpage::class, 'indexLabMikrobiologi']);
Route::get('/labmikrobiologi/paginationpemeriksaan', [Landingpage::class, 'paginationPemeriksaan']);
Route::get('/kontak', [Landingpage::class, 'kontak']);
Route::get('/kegiatan', [Landingpage::class, 'indexKegiatan']);
Route::get('/kegiatan/paginationkegiatan', [Landingpage::class, 'paginationKegiatan']);
Route::get('/lphomecare', [Landingpage::class, 'homecare']);
Route::get('/paginationlppromosi', [Landingpage::class, 'paginationLpPromosi']);
Route::get('/paginationhdkegiatan', [Landingpage::class, 'paginationHdKegiatan']);

Route::get('/spr/landingpage/menu', [LandingpageMenu::class, 'index']);
Route::get('/spr/landingpage/promosi', [LandingpageHalamandepan::class, 'promosiEdit']);
Route::resource('/spr/landingpage/kegiatan', LandingpageKegiatan::class);
Route::resource('/spr/landingpage/layanankami', LandingpageLayanankami::class);
Route::resource('/spr/landingpage/promosi', LandingpagePromosi::class);
Route::get('/spr/landingpage/slide/paginationslide', [LandingpageSlide::class, 'paginationSlide']);
Route::resource('/spr/landingpage/slide', LandingpageSlide::class);
Route::post('/pasienbaru', [Pasien::class, 'storePasienBaru']);
Route::resource('/pasien', Pasien::class);

Route::get('/registrasi/klinik/paginationpaketpemeriksaan', [Registrasi::class, 'paginationPaketPemeriksaan']);
Route::get('/registrasi/klinik/paginationparameterpemeriksaan', [Registrasi::class, 'paginationParameterPemeriksaan']);
Route::get('/registrasi/kimia/paginationpaketpemeriksaan', [Registrasi::class, 'paginationPaketPemeriksaanKimia']);
Route::get('/registrasi/kimia/paginationparameterpemeriksaan', [Registrasi::class, 'paginationParameterPemeriksaanKimia']);
Route::get('/registrasi/mikrobiologi/paginationpaketpemeriksaan', [Registrasi::class, 'paginationPaketPemeriksaanMikrobiologi']);
Route::get('/registrasi/mikrobiologi/paginationparameterpemeriksaan', [Registrasi::class, 'paginationParameterPemeriksaanMikrobiologi']);

Route::get('/registrasi/klinik/paginationgridpemeriksaan', [Registrasi::class, 'paginationGridPemeriksaan']);
Route::get('/registrasi/kimia/paginationgridpemeriksaan', [Registrasi::class, 'paginationGridPemeriksaan']);
Route::get('/registrasi/mikrobiologi/paginationgridpemeriksaan', [Registrasi::class, 'paginationGridPemeriksaan']);
Route::post('/registrasi/hitungBiayaPaketPemeriksaan', [Registrasi::class, 'hitungBiayaPaketPemeriksaan']);
Route::post('/registrasi/hitungBiayaParameterPemeriksaan', [Registrasi::class, 'hitungBiayaParameterPemeriksaan']);
Route::post('/registrasi/hitungBiayaPaketPemeriksaanKimia', [Registrasi::class, 'hitungBiayaPaketPemeriksaanKimia']);
Route::post('/registrasi/hitungBiayaParameterPemeriksaanKimia', [Registrasi::class, 'hitungBiayaParameterPemeriksaanKimia']);
Route::post('/registrasi/getDetailTotalBiaya', [Registrasi::class, 'getDetailTotalBiaya']);
Route::post('/registrasi/getDetailTotalBiayaKimiaMikro', [Registrasi::class, 'getDetailTotalBiayaKimiaMikro']);
Route::post('/registrasi/getDetailPemeriksaan', [Registrasi::class, 'getDetailPemeriksaan']);
Route::post('/registrasi/getDataPembayaran', [Registrasi::class, 'getDataPembayaran']);
Route::get('/registrasi/downloadHasilPemeriksaan/{filename}', [Registrasi::class, 'downloadHasilPemeriksaan'])->name('download.hasil');;

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/pendaftaran', [Pendaftaran::class, 'index']);
Route::post('/pendaftaran/storependaftaran', [Pendaftaran::class, 'storePendaftaran']);
Route::post('/pendaftaran/ceknohp', [Pendaftaran::class, 'cekNoHp']);
Route::post('/pendaftaran/ceknik', [Pendaftaran::class, 'cekNik']);
Route::post('/pendaftaran/cekemail', [Pendaftaran::class, 'cekEmail']);

Route::get('/registrasi/klinik', [Registrasi::class, 'gridKlinik']);
Route::get('/registrasi/klinik/create', [Registrasi::class, 'createKlinik']);
Route::get('/registrasi/klinik/edit/{pemeriksaan_id}', [Registrasi::class, 'editKlinik']);
Route::post('/registrasi/klinik/updateklinik', [Registrasi::class, 'updateKlinik']);

Route::get('/registrasi/kimia', [Registrasi::class, 'gridKimia']);
Route::get('/registrasi/kimia/create', [Registrasi::class, 'createKimia']);
Route::get('/registrasi/kimia/edit/{pemeriksaan_id}', [Registrasi::class, 'editKimia']);
Route::post('/registrasi/getPemeriksaanKimia', [Registrasi::class, 'getPemeriksaanKimia']);
Route::post('/registrasi/kimia/updatekimia', [Registrasi::class, 'updateKimia']);

Route::get('/registrasi/mikrobiologi', [Registrasi::class, 'gridMikrobiologi']);
Route::get('/registrasi/mikrobiologi/create', [Registrasi::class, 'createMikrobiologi']);
Route::post('/registrasi/mikrobiologi/storemikrobiologi', [Registrasi::class, 'storeMikrobiologi']);
Route::post('/registrasi/getPemeriksaanMikrobiologi', [Registrasi::class, 'getPemeriksaanMikrobiologi']);
Route::get('/registrasi/mikrobiologi/edit/{pemeriksaan_id}', [Registrasi::class, 'editMikrobiologi']);
Route::post('/registrasi/mikrobiologi/updatemikrobiologi', [Registrasi::class, 'updateMikrobiologi']);
Route::post('/registrasi/hitungPemeriksaanKimiaMikro', [Registrasi::class, 'hitungPemeriksaanKimiaMikro']);
Route::post('/registrasi/hitungJumlahSampelKimiaMikro', [Registrasi::class, 'hitungJumlahSampelKimiaMikro']);

Route::post('/registrasi/getDataJenisPemeriksaan', [Registrasi::class, 'getDataJenisPemeriksaan']);
Route::post('/registrasi/kimia/storekimia', [Registrasi::class, 'storeKimia']);
Route::post('/registrasi/klinik/storeklinik', [Registrasi::class, 'storeklinik']);
Route::post('/registrasi/getJadwalPemeriksaan', [Registrasi::class, 'getJadwalPemeriksaan']);
Route::get('/registrasi/verifikasi', [Registrasi::class, 'gridRegistrasiVerifikasi']);
Route::get('/registrasi/verifikasi/paginationgridverifikasipemeriksaan', [Registrasi::class, 'paginationGridVerifikasiPemeriksaan']);
Route::post('/registrasi/updateStatusLab', [Registrasi::class, 'updateStatusLab']);
Route::post('/registrasi/verifikasi/updateStatusLab', [Registrasi::class, 'updateStatusLab']);
Route::post('/registrasi/delete', [Registrasi::class, 'hapusRegistrasi']);
Route::post('/registrasi/verifikasi/setuju', [Registrasi::class, 'setujuRegistrasi']);
Route::post('/registrasi/verifikasi/batal', [Registrasi::class, 'batalRegistrasi']);
Route::post('/registrasi/getDataSampel', [Registrasi::class, 'getDataSampel']);
Route::post('/registrasi/countCetakDownloadHasil', [Registrasi::class, 'countCetakDownloadHasil']);
Route::post('/registrasi/getDetailPemeriksaanKlinik', [Registrasi::class, 'getDetailPemeriksaanKlinik']);
Route::post('/registrasi/getPaketPemeriksaanKlinik', [Registrasi::class, 'getPaketPemeriksaanKlinik']);
Route::post('/registrasi/hitungBiayaPemeriksaanKlinik', [Registrasi::class, 'hitungBiayaPemeriksaanKlinik']);
Route::post('/registrasi/getPemeriksaanKlinik', [Registrasi::class, 'getPemeriksaanKlinik']);

Route::get('/pencetakan/cetakpenerimaansampel', [Registrasi::class, 'cetakpenerimaansampel']);
Route::get('/pencetakan/cetakbuktiregistrasi/{pemeriksaan_id}', [Registrasi::class, 'cetakbuktiregistrasi']);

Route::get('/pembayaran/laboratorium', [Pembayaran::class, 'indexLaboratorium']);
Route::get('/pembayaran/laboratorium/create/{pemeriksaan_id}', [Pembayaran::class, 'createPembayaran']);
Route::post('/pembayaran/laboratorium/storepembayaran', [Pembayaran::class, 'storePembayaran']);
Route::get('/pembayaran/verifikasi/laboratorium', [Pembayaran::class, 'gridPembayaranVerifikasi']);
Route::post('/pembayaran/verifikasi/laboratorium/updateStatusBayar', [Pembayaran::class, 'updateStatusBayar']);
Route::get('/pembayaran/homecare', [Pembayaran::class, 'indexHomecare']);
Route::get('/pembayaran/homecare/create/{homecare_id}', [Pembayaran::class, 'createPembayaranHomecare']);
Route::post('/pembayaran/homecare/storepembayaran', [Pembayaran::class, 'storePembayaranHomecare']);
Route::get('/pembayaran/verifikasi/homecare', [Pembayaran::class, 'gridPembayaranVerifikasiHomecare']);
Route::post('/pembayaran/verifikasi/homecare/updateStatusBayar', [Pembayaran::class, 'updateStatusBayar']);

Route::get('/dashboard', [Dashboard::class, 'index']);
Route::get('/spr/dashboard', [Dashboard::class, 'adminIndex']);

Route::get('/pengguna', [Pengguna::class, 'index']);
Route::get('/pengguna/edit/{user_id}', [Pengguna::class, 'edit']);
Route::post('/pengguna/updatepengguna', [Pengguna::class, 'updatepengguna']);

Route::get('/registrasi/homecare', [HomeCare::class, 'indexPengguna']);
Route::get('/registrasi/homecare/create', [HomeCare::class, 'createHomecare']);
Route::post('/homecare/hitungJarakLokasi', [HomeCare::class, 'hitungJarakLokasi']);
Route::post('/homecare/pengguna/storehomecare', [HomeCare::class, 'storeHomeCare']);
Route::post('/homecare/delete', [HomeCare::class, 'hapusRegistrasi']);
Route::get('/homecare/edit/{homecare_id}', [HomeCare::class, 'editHomecare']);
Route::post('/homecare/pengguna/updateHomeCare', [HomeCare::class, 'updateHomeCare']);
Route::get('/homecare/verifikasi', [HomeCare::class, 'indexAdmin']);
Route::post('/homecare/getJadwalPemeriksaanHomecare', [HomeCare::class, 'getJadwalPemeriksaanHomecare']);
Route::post('/homecare/verifikasi/setuju', [HomeCare::class, 'setujuHomecare']);
Route::post('/homecare/verifikasi/updateStatusLab', [HomeCare::class, 'updateStatusLab']);
Route::post('/homecare/verifikasi/updateStatusSelesaiLab', [HomeCare::class, 'updateStatusSelesaiLab']);
Route::post('/homecare/verifikasi/batal', [HomeCare::class, 'updateStatusBatalHomecare']);


Route::post('master/jenispemeriksaan/getDataParameterPemeriksaan', [MstJenisPemeriksaan::class, 'getDataParameterPemeriksaan']);
Route::post('master/jenispemeriksaan/hapusDataParameterPemeriksaan', [MstJenisPemeriksaan::class, 'hapusDataParameterPemeriksaan']);
Route::post('/master/getParameterJenisPemeriksaan', [MstJenisPemeriksaan::class, 'getParameterJenisPemeriksaan']);
Route::resource('/master/jenispemeriksaan', MstJenisPemeriksaan::class);
Route::post('/master/getdetailmstpaketpemeriksaan', [MstPaketPemeriksaan::class, 'getDetailMstPaketPemeriksaan']);


Route::get('/master/parameterpemeriksaan/1', [MstParameterPemeriksaan::class, 'indexMstParameterKlinik']);
Route::get('/master/parameterpemeriksaan/2', [MstParameterPemeriksaan::class, 'indexMstParameterKimia']);
Route::get('/master/parameterpemeriksaan/3', [MstParameterPemeriksaan::class, 'indexMstParameterMikrobiologi']);


Route::get('/master/parameterpemeriksaan/edit/1/{parameter_id}', [MstParameterPemeriksaan::class, 'editMstParameterKlinik']);
Route::post('/master/parameterpemeriksaan/update/1', [MstParameterPemeriksaan::class, 'updateMstParameterKlinik']);

Route::get('/master/parameterpemeriksaan/edit/2/{parameter_id}', [MstParameterPemeriksaan::class, 'editMstParameterKimia']);
Route::post('/master/parameterpemeriksaan/update/2', [MstParameterPemeriksaan::class, 'updateMstParameterKimia']);

Route::get('/master/parameterpemeriksaan/edit/3/{parameter_id}', [MstParameterPemeriksaan::class, 'editMstParameterMikrobiologi']);
Route::post('/master/parameterpemeriksaan/update/3', [MstParameterPemeriksaan::class, 'updateMstParameterMikrobiologi']);


Route::get('/master/parameterpemeriksaan/create/1', [MstParameterPemeriksaan::class, 'createMstParameterKlinik']);
Route::post('/master/parameterpemeriksaan/storeKlinik', [MstParameterPemeriksaan::class, 'storeKlinik']);
Route::get('/master/parameterpemeriksaan/create/2', [MstParameterPemeriksaan::class, 'createMstParameterKimia']);
Route::post('/master/parameterpemeriksaan/storeKimia', [MstParameterPemeriksaan::class, 'storeKimia']);
Route::get('/master/parameterpemeriksaan/create/3', [MstParameterPemeriksaan::class, 'createMstParameterMikrobiologi']);
Route::post('/master/parameterpemeriksaan/storeMikrobiologi', [MstParameterPemeriksaan::class, 'storeMikrobiologi']);

Route::resource('/master/pamflet', MstPamflet::class);

Route::get('/pemulihan', [Pemulihan::class, 'index']);

Route::get('/registrasi/biomolekuler', [Registrasi::class, 'gridBiomolekuler']);

Route::get('/hasil', [Registrasi::class, 'gridHasilLab']);


Route::post('/registrasi/getDataKemasan', [Registrasi::class, 'getDataKemasan']);

// admin route
Route::get('/spr/master/paketpemeriksaan/paginationmstpaketpemeriksaan', [MstPaketPemeriksaan::class, 'paginationGridPaketPemeriksaan']);
Route::resource('/spr/master/paketpemeriksaan', MstPaketPemeriksaan::class);
Route::get('/spr/pelaporan', [Pelaporan::class, 'index']);
Route::post('/spr/pelaporan/cetakpelaporanregistrasibayar', [Pelaporan::class, 'cetakLaporanRegistrasiPembayaran']);