<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pekerja;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class PekerjaController extends Controller
{
    public function index()
    {
        $latestPekerjas = DB::table('pekerjas')
            ->select('pekerjas.*')
            ->join(DB::raw('(SELECT device_id, MAX(id) as max_id FROM pekerjas GROUP BY device_id) as latest'), function($join) {
                $join->on('pekerjas.device_id', '=', 'latest.device_id');
                $join->on('pekerjas.id', '=', 'latest.max_id');
            })
            ->get();

        return view('pekerjas.index', compact('latestPekerjas'));
    }

 public function latest()
{
$latestPekerjas = DB::table('pekerjas')
    ->select('pekerjas.*')
    ->join(DB::raw('(SELECT device_id, MAX(id) as max_id FROM pekerjas GROUP BY device_id) as latest'), function($join) {
        $join->on('pekerjas.device_id', '=', 'latest.device_id')
             ->on('pekerjas.id', '=', 'latest.max_id');
    })
    ->get()
    ->map(function($p) {
        $p->telegram_sent = (bool) $p->telegram_sent; // cast ke boolean
        return $p;
    });

return response()->json($latestPekerjas);

}



    public function create()
    {
        return view('pekerjas.create');
    }

   public function store(Request $request)
{
    // Validasi input tanpa telegram_sent
    $validated = $request->validate([
        'device_id' => 'required|string',
        'status_helm' => 'required|string',
        'kondisi_pekerja' => 'required|string',
        'status_terbaring' => 'required|string', // pastikan ikut validasi
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
    ]);

    // Tambahkan telegram_sent = false secara default
    $validated['telegram_sent'] = false;

    $data = Pekerja::create($validated);

    // Otomatis kirim Telegram jika Benturan_Keras atau Terbaring
    if (
        ($data->kondisi_pekerja === 'Benturan_Keras' || $data->status_terbaring === 'Terbaring') 
        && !$data->telegram_sent
    ) {
        $this->kirimTelegram($data->id);
    }

    return redirect()->route('pekerjas.index')->with('success', '✅ Data berhasil ditambahkan.');
}

public function updateFromDevice(Request $request)
{
    $validated = $request->validate([
        'device_id' => 'required|string',
        'status_helm' => 'required|string',
        'kondisi_pekerja' => 'required|string',
        'status_terbaring' => 'required|string',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
    ]);

    // updateOrCreate harus set telegram_sent = false untuk data baru
    $pekerja = Pekerja::updateOrCreate(
        ['device_id' => $validated['device_id']],
        array_merge($validated, ['telegram_sent' => false])
    );

    // Otomatis kirim Telegram jika Benturan_Keras atau Terbaring
    if (
        ($pekerja->kondisi_pekerja === 'Benturan_Keras' || $pekerja->status_terbaring === 'Terbaring') 
        && !$pekerja->telegram_sent
    ) {
        $this->kirimTelegram($pekerja->id);
    }

    return response()->json(['message' => 'Data diperbarui.']);
}

    public function destroy(Pekerja $pekerja)
    {
        $pekerja->delete();
        return redirect()->route('pekerjas.index')->with('success', 'Data berhasil dihapus.');
    }

    public function riwayat(Request $request)
    {
        $device_id = $request->input('device_id');
        $query = Pekerja::query();

        if ($device_id) {
            $query->where('device_id', $device_id);
        }

        $allPekerjas = $query->orderBy('created_at', 'desc')->get();

        return view('pekerjas.riwayat', compact('allPekerjas', 'device_id'));
    }
public function kirimTelegram($id, $manual = false)
{
    $pekerja = Pekerja::findOrFail($id);

    // Cek telegram_sent hanya jika bukan manual
    if (!$manual && $pekerja->telegram_sent) {
        return response()->json([
            'success' => false,
            'message' => 'Telegram sudah dikirim otomatis sebelumnya.'
        ]);
    }

    $pesan = "🚨 *Notifikasi Helm Proyek* 🚨\n"
        . "Device ID: `{$pekerja->device_id}`\n"
        . "Status Helm: *{$pekerja->status_helm}*\n"
        . "Kondisi Pekerja: *{$pekerja->kondisi_pekerja}*\n"
        . "Keadaan Pekerja: *{$pekerja->status_terbaring}*\n"
        . "Lokasi: https://www.google.com/maps?q={$pekerja->latitude},{$pekerja->longitude}";

    $token = '7811830215:AAH-f0C-hmpyCWEJyHZfoiPwkKpGJ3D3488';
    $chat_ids = ['5846509469', '5292152381'];

    $errors = [];

    foreach ($chat_ids as $chat_id) {
        try {
            $res = Http::asForm()->post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chat_id,
                'text' => $pesan,
                'parse_mode' => 'Markdown'
            ]);

            $body = $res->json();
            if (!isset($body['ok']) || !$body['ok']) {
                $errors[] = "❌ Gagal kirim ke $chat_id: " . ($body['description'] ?? 'Unknown error');
            }
        } catch (\Exception $e) {
            $errors[] = "❌ Gagal kirim ke $chat_id: " . $e->getMessage();
        }
    }

    // Tandai telegram_sent = true hanya untuk otomatis
    if (empty($errors) && !$manual) {
        $pekerja->telegram_sent = true;
        $pekerja->save();
    }

    return response()->json([
        'success' => empty($errors),
        'message' => empty($errors) ? '✅ Telegram terkirim' : implode(', ', $errors)
    ]);
}
public function kirimTelegramManual($id)
{
    // Panggil fungsi kirimTelegram dengan $manual = true
    return $this->kirimTelegram($id, true);
}

}
