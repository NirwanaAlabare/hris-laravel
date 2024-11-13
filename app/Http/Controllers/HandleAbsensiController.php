<?php

namespace App\Http\Controllers;

use App\Models\DataKehadiranInOutEdited;
use App\Models\LogDataGagalAbsen;
use App\Models\MasterDataAbsenKehadiran;
use Illuminate\Http\Request;

class HandleAbsensiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public static function parse_data($data, $p1, $p2)
    {
        $data = " " . $data;
        $hasil = "";
        $awal = strpos($data, $p1);
        if ($awal != "") {
            $akhir = strpos(strstr($data, $p1), $p2);
            if ($akhir != "") {
                $hasil = substr($data, $awal + strlen($p1), $akhir - strlen($p1));
            }
        }
        return $hasil;
    }
    public function index()
    {
        $status_absen_cannot_changes = ['LN', 'DL', 'R', 'IKS'];
        $status_absen_autonull = ['LP', 'S'];
        $IP = "192.168.0.239";
        $Key = "0";
        if ($IP == "") $IP = "192.168.0.239";
        if ($Key == "") $Key = "0";


        $Connect = fsockopen($IP, "80", $errno, $errstr, 1);
        if ($Connect) {
            $soap_request = "<GetAttLog>
                                <ArgComKey xsi:type=\"xsd:integer\">" . $Key . "</ArgComKey>
                                <Arg><PIN xsi:type=\"xsd:integer\">All</PIN></Arg>
                            </GetAttLog>";
            $newLine = "\r\n";
            fputs($Connect, "POST /iWsService HTTP/1.0" . $newLine);
            fputs($Connect, "Content-Type: text/xml" . $newLine);

            fputs($Connect, "Content-Length: " . strlen($soap_request) . $newLine . $newLine);
            fputs($Connect, $soap_request . $newLine);
            $buffer = "";
            while ($Response = fgets($Connect, 1024)) {
                $buffer = $buffer . $Response;
            }
        } else echo "Koneksi Gagal";

        $buffer = $this->parse_data($buffer, "<GetAttLogResponse>", "</GetAttLogResponse>");
        $buffer = explode("\r\n", $buffer);

        for ($a = 0; $a < count($buffer); $a++) {
            $data = $this->parse_data($buffer[$a], "<Row>", "</Row>");
            $export[$a]['enroll_id'] = $this->parse_data($data, "<PIN>", "</PIN>");
            $export[$a]['waktu'] = $this->parse_data($data, "<DateTime>", "</DateTime>");
            $export[$a]['status'] = $this->parse_data($data, "<Status>", "</Status>");
        }

        $result = array();
        foreach ($export as $key => $value) {
            $result[$value['enroll_id']][] = $value;
        }

        $finalResult =  [];

        // Mendapatkan tanggal hari ini dan kemarin
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('yesterday'));

        foreach ($result as $enroll_id => $entries) {
            $groupedByDate = [];

            // Mengelompokkan data berdasarkan tanggal saja (tanpa waktu)
            foreach ($entries as $entry) {
                // Ambil hanya bagian tanggal dari waktu
                $date = date('Y-m-d', strtotime($entry['waktu']));

                // Jika tanggal tersebut adalah hari ini atau kemarin, kelompokkan
                if ($date === $today || $date === $yesterday) {
                    $groupedByDate[$date][] = $entry;
                }
            }

            // Sekarang untuk setiap tanggal, kita ambil waktu yang paling pagi (status = 0) dan paling siang (status = 1)
            foreach ($groupedByDate as $date => $entries) {
                $morning = null;  // Waktu pagi (status 0)
                $afternoon = null; // Waktu siang (status 1)

                // Pisahkan entri status 0 (pagi) dan status 1 (siang)
                foreach ($entries as $entry) {
                    if ($entry['status'] == '0') {
                        // Waktu paling pagi (terawal)
                        if ($morning === null || strtotime($entry['waktu']) < strtotime($morning['waktu'])) {
                            $morning = $entry;
                        }
                    } elseif ($entry['status'] == '1') {
                        // Waktu paling siang (terbaru)
                        if ($afternoon === null || strtotime($entry['waktu']) > strtotime($afternoon['waktu'])) {
                            $afternoon = $entry;
                        }
                    }
                }

                $finalResult[$enroll_id][$date] = [$morning, $afternoon];
            }
        }

        $updateData = [];
        $datesToUpdate = [];


        foreach ($finalResult as $idx => $val) {
            foreach ($val as $val_idx => $val_value) {
                foreach ($val_value as $key_value => $time_value) {
                    if ($time_value) {

                        // Tentukan kolom yang akan diupdate berdasarkan status
                        $columnToUpdate = ($time_value['status'] === "0")
                            ? 'absen_masuk_kerja'
                            : 'absen_pulang_kerja';

                        // Ambil waktu yang dipotong hanya jam dan menit
                        $waktu = substr($time_value['waktu'], 11, 5);
                        // Menambahkan data ke dalam array untuk update batch
                        $updateData[] = [
                            'tanggal_berjalan' => $val_idx,
                            'enroll_id' => $time_value['enroll_id'],
                            'column_to_update' => $columnToUpdate,
                            'waktu' => $waktu,
                        ];

                        $datesToUpdate[$val_idx][$time_value['enroll_id']] = true;
                    }
                }
            }
        }
        if (!empty($updateData)) {
            foreach ($updateData as $data) {
                MasterDataAbsenKehadiran::where('tanggal_berjalan', $data['tanggal_berjalan'])
                    ->where('enroll_id', $data['enroll_id'])
                    ->update([$data['column_to_update'] => $data['waktu']]);
            }
        }
        if (!empty($datesToUpdate)) {
            foreach ($datesToUpdate as $tanggal => $enrollIds) {
                // Ambil nilai kedua kolom absen untuk setiap enroll_id pada tanggal tertentu
                $absenData = MasterDataAbsenKehadiran::where('tanggal_berjalan', $tanggal)
                    ->whereIn('enroll_id', array_keys($enrollIds))
                    ->get(['enroll_id', 'absen_masuk_kerja', 'absen_pulang_kerja', 'tanggal_absen', 'status_absen']);

                // Proses untuk setiap enroll_id
                foreach ($absenData as $data) {
                    $data_kehadiran_edited = DataKehadiranInOutEdited::where('tanggal_absen', $data->tanggal_absen)->where('enroll_id', '=', $data->enroll_id)->count();
                    $data_gagal_absen = LogDataGagalAbsen::where('tanggal_absen', $data->tanggal_absen)->where('enroll_id', $data->enroll_id)->count();
                    if ($data_kehadiran_edited < 1 && $data_gagal_absen < 1) {
                        $statusAbsen = $data->status_absen;
                        if (in_array($statusAbsen, $status_absen_autonull) && $data->absen_masuk_kerja && $data->absen_pulang_kerja) {
                            $statusAbsen = null;
                        }
                        if (!in_array($statusAbsen, $status_absen_cannot_changes) && !in_array($statusAbsen, $status_absen_autonull) && empty($data->absen_masuk_kerja) && empty($data->absen_pulang_kerja)) {
                            $statusAbsen = null;
                        }
                        if (!in_array($statusAbsen, $status_absen_cannot_changes) && !in_array($statusAbsen, $status_absen_autonull) && empty($data->absen_masuk_kerja) || empty($data->absen_pulang_kerja)) {
                            $statusAbsen = 'TL';
                        }
                        // Update kolom status_absen
                        // MasterDataAbsenKehadiran::where('tanggal_berjalan', $tanggal)
                        //     ->where('enroll_id', $data->enroll_id)
                        //     ->update(['status_absen' => $statusAbsen]);
                    }
                }
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
