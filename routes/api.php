    <?php
use Illuminate\Support\Facades\Route;

use App\Events\PekerjaUpdated;
use App\Models\Pekerja;
use Illuminate\Http\Request;

Route::post('/pekerja/update', function(Request $request) {
    $data = $request->validate([
        'device_id' => 'required|string',
        'status_helm' => 'required|string',
        'kondisi_pekerja' => 'required|string',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
    ]);

    $pekerja = Pekerja::updateOrCreate(
        ['device_id' => $data['device_id']],
        $data
    );

    broadcast(new PekerjaUpdated($pekerja))->toOthers();

    return response()->json(['success' => true, 'pekerja' => $pekerja]);
});
