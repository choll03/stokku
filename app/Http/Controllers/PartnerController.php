<?php

namespace App\Http\Controllers;

use App\Model\Barang;
use App\Model\Invoice;
use App\Model\Invoice_detail;
use App\PartnerInvitation;
use App\ProductColaboration;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Model\Warung;

class PartnerController extends Controller
{
    public function search() {
        return view('partner.search');
    }

    public function getSearchPartner() {
        $user = auth()->user();
        $warung = $user->warung;

        // cari id toko yang mengirim permintaan kolaborasi lalu jgn tampilkan di halaman ini
        $invitationIds = [];

        $partners = PartnerInvitation::where('to', $warung->id)->orWhere('from', $warung->id)->get();
        foreach ($partners as $partner) {
            $invitationIds[$partner->from] = $partner->from;
            if ($partner->success) {
                $invitationIds[$partner->to] = $partner->to;
            }
        }

        $result = Warung::selectRaw('warungs.*, users.name, (SELECT COUNT(id) FROM partner_invitations WHERE partner_invitations.to = warungs.id) as invitation_count')
            ->join('users', 'users.id', '=', 'warungs.user_id')
            ->where('user_id', '!=', $user->id)
            ->whereNotIn('warungs.id', $invitationIds);
        return Datatables::of($result)
            ->addColumn('actions', function ($data) {
                $buttons = '
                <div style="display:flex;justify-content: center;">
                <a href="'. route('partner.profile', $data->id) .'" class="btn btn-sm btn-secondary">Profile</a>';

                if ($data->invitation_count > 0) {
                    $buttons .= '<form action="'. route('partner.remove-invitation') .'" method="POST" style="margin-left: 5px">
                        <input type="hidden" name="_token" value="'. csrf_token() .'">
                        <input type="hidden" name="invite_to" value="'. $data->id .'">
                        <button class="btn btn-sm btn-danger">Batalkan Permintaan</button>
                    </form>';
                } else {
                    $buttons .= '<form action="'. route('partner.send-invitation') .'" method="POST" style="margin-left: 5px">
                        <input type="hidden" name="_token" value="'. csrf_token() .'">
                        <input type="hidden" name="invite_to" value="'. $data->id .'">
                        <button class="btn btn-sm btn-primary">Kirim Permintaan Kolaborasi</button>
                    </form>';
                }

                $buttons .= '</div>';

                return $buttons;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function getPartnerConfirmation() {
        $user = auth()->user();
        $warung = $user->warung;
        $invitationId = PartnerInvitation::where('to', $warung->id)->where('success', 0)->pluck('from')->toArray();
        $result = Warung::selectRaw('warungs.*, users.name')
            ->join('users', 'users.id', '=', 'warungs.user_id')
            ->where('user_id', '!=', $user->id)
            ->whereIn('warungs.id', $invitationId);
        return Datatables::of($result)
            ->addColumn('actions', function ($data) {
                $buttons = '
                <div style="display:flex;justify-content: center;">
                <a href="'. route('partner.profile', $data->id) .'" class="btn btn-sm btn-secondary">Profile</a>';

                $buttons .= '<form action="'. route('partner.confirm-invitation') .'" method="POST" style="margin-left: 5px">
                    <input type="hidden" name="_token" value="'. csrf_token() .'">
                    <input type="hidden" name="from" value="'. $data->id .'">
                    <button class="btn btn-sm btn-success" onclick="return confirm('. var_export("Anda yakin ingin konfirmasi toko ini?", true) .')">Konfirmasi</button>
                </form>';

                $buttons .= '</div>';

                return $buttons;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function getMyPartner() {
        $user = auth()->user();
        $warung = $user->warung;
        $arr = [];
        $invitationId = PartnerInvitation::where(function ($q) use ($warung) {
            $q->where('from', $warung->id);
            $q->orWhere('to', $warung->id);
        })->where('success', 1)->get();

        foreach ($invitationId as $data) {
            if ($data->to == $warung->id) {
                $arr[] = $data->from;
            } else {
                $arr[] = $data->to;
            }
        };

        $result = Warung::selectRaw('warungs.*, users.name')
            ->join('users', 'users.id', '=', 'warungs.user_id')
            ->where('user_id', '!=', $user->id)
            ->whereIn('warungs.id', $arr);
        return Datatables::of($result)
            ->addColumn('actions', function ($data) {
                $buttons = '
                <div style="display:flex;justify-content: center;">
                <a href="'. route('partner.profile', $data->id) .'" class="btn btn-sm btn-secondary">Profile</a>
                <a href="'. route('partner.transaction', $data->id) .'" class="btn btn-sm btn-primary">Transaksi</a>
                ';

                $buttons .= '<a href="'. route('partner.get-product-colaboration', $data->id) .'" class="btn btn-sm btn-success">Konfirmasi Produk</a>';

                $buttons .= '</div>';

                return $buttons;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function getBarangPartner(Request $request) {

        $user = auth()->user();
        $warung = $user->warung;
        $isPartner = PartnerInvitation::where(function ($q) use ($warung, $request){
            $q->where('from', $warung->id);
            $q->where('to', $request->id);
        })->orWhere(function ($qq) use ($warung, $request) {
            $qq->where('to', $warung->id);
            $qq->where('from', $request->id);
        })->where('success', 1)->exists();

        $productJoin = ProductColaboration::where('warung_id', $warung->id)
            ->whereRaw('barang_id IN (SELECT barangs.id FROM barangs WHERE barangs.warung_id = ?)', [$request->id])
            ->get();

        return Datatables::of(Barang::selectRaw('barangs.*, IFNULL((SELECT SUM(jumlah) FROM pembelian_details WHERE barang_id = barangs.id), 0) - IFNULL((SELECT SUM(qty) FROM invoice_details WHERE barangs.id = invoice_details.barang_id ),0) as stok')
            ->where('warung_id', $request->id)
            ->where('active', 1))
            ->addColumn('actions', function ($data) use ($isPartner, $warung, $productJoin){
                $buttons = "";

                if ($isPartner) {
                    $findProduct = $productJoin->where('barang_id' , $data->id)->first();
                    if ($findProduct) {
                        if ($findProduct->isConfirm) {
                            $buttons .= '<p>Berhak Jual</p>';
                        } else {
                            $buttons .= '<form action="'. route('partner.product-colaboration', $data->warung_id) .'" method="POST" style="margin-left: 5px">
                                <input type="hidden" name="_token" value="'. csrf_token() .'">
                                <input type="hidden" name="warung_id" value="'. $warung->id .'">
                                <input type="hidden" name="barang_id" value="'. $data->id .'">
                                <button class="btn btn-sm btn-danger">Batalkan Permintaan</button>
                            </form>';

                        }
                    } else {
                        $buttons .= '<form action="'. route('partner.product-colaboration', $data->warung_id) .'" method="POST" style="margin-left: 5px">
                            <input type="hidden" name="_token" value="'. csrf_token() .'">
                            <input type="hidden" name="warung_id" value="'. $warung->id .'">
                            <input type="hidden" name="barang_id" value="'. $data->id .'">
                            <button class="btn btn-sm btn-primary">Izin Menjual</button>
                        </form>';
                    }
                } else {
                    $buttons .= '<div></div>';
                }

                return $buttons;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function getBarangPartnerConfirmation(Request $request) {
        $user = auth()->user();
        $barangs = $user->barang()->selectRaw('barangs.*, product_colaborations.id as product_colaborations_id, product_colaborations.isConfirm')->join('product_colaborations', 'barangs.id', '=', 'product_colaborations.barang_id')
            ->where('product_colaborations.warung_id', $request->id)
            ->orderBy('isConfirm');

        return Datatables::of($barangs)
            ->addColumn('actions', function ($data) {
                $buttons = '
                <div style="display:flex;justify-content: center;">';

                if ($data->isConfirm) {
                    $buttons .= "<p>Sudah dikonfirmasi</p>";
                } else {
                    $buttons .= '<form action="'. route('partner.product-joined', $data->product_colaborations_id) .'" method="POST" style="margin-left: 5px">
                        <input type="hidden" name="_token" value="'. csrf_token() .'">
                        <button class="btn btn-sm btn-success" onclick="return confirm('. var_export("Anda yakin ingin konfirmasi barang ini?", true) .')">Konfirmasi</button>
                    </form>';

                }

                $buttons .= '</div>';

                return $buttons;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function myPartner() {
        return view('partner.mypartner');
    }

    public function listConfirmation() {
        return view('partner.confirmation');
    }

    public function sendInvitation(Request $request) {
        $user = auth()->user();

        $warung = $user->warung;
        PartnerInvitation::create([
            'from' => $warung->id,
            'to' => $request->invite_to
        ]);

        session()->flash('status', 'Permintaan berhasil di buat');

        return redirect(route('partner.search'));
    }

    public function removeInvitation(Request $request) {
        $user = auth()->user();

        $warung = $user->warung;

        PartnerInvitation::where('to', $request->invite_to)->where('from', $warung->id)->delete();

        session()->flash('status', 'Permintaan berhasil di hapus');

        return redirect(route('partner.search'));
    }

    public function confirmInvitation(Request $request) {
        $user = auth()->user();

        $warung = $user->warung;

        $partner = PartnerInvitation::where('to', $warung->id)->where('from', $request->from)->first();
        $partner->success = 1;
        $partner->save();

        session()->flash('status', 'Permintaan berhasil di konfirmasi');

        return redirect(route('partner.list-confirmation'));
    }

    public function productColaboration(Request $request, $id) {
        ProductColaboration::updateOrCreate(['warung_id' => $request->warung_id, 'barang_id' => $request->barang_id],$request->all());
        return redirect(route('partner.profile', $id));
    }

    public function productJoined($id) {
        ProductColaboration::find($id)->update(['isConfirm' => 1]);
        return redirect()->back();
    }

    public function profile($id) {
        $profile = Warung::selectRaw('warungs.*, users.name')->join('users', 'users.id', '=', 'warungs.user_id')->where('warungs.id', $id)->first();
//
//        $productJoin = ProductColaboration::where('warung_id', 3)
//            ->whereRaw('barang_id IN (SELECT barangs.id FROM barangs WHERE barangs.warung_id = ?)', [1])
//            ->get();
//
//        dd($productJoin->first()->barang_id);
        return view('partner.profile', ['profile' => $profile]);
    }

    public function getProductColaboration($id) {
        $profile = Warung::find($id);
        return view('partner.product-confirmation', ['id' => $id, 'profile' => $profile]);
    }

    public function getTransaction($id) {
        $user = auth()->user();
        $warung = $user->warung;

        $partner = Warung::find($id);

        $productJoin = Barang::join('product_colaborations', 'product_colaborations.barang_id', '=', 'barangs.id')
            ->where('barangs.warung_id', $warung->id)
            ->where('product_colaborations.warung_id', $id)
            ->pluck('barangs.id')
            ->toArray();

        $invoice = Invoice::whereHas('detail' ,function($q) use ($productJoin){
            $q->whereIn('barang_id', $productJoin);
        })
            ->with(['detail' => function($qq) use ($warung) {
                $qq->where('warung_id', $warung->id);
            }])
            ->latest()
            ->get();

        return view('partner.transaction', ['invoices' => $invoice, 'partner' => $partner, 'id' => $warung->id]);
    }

    public function payment(Request $request) {
        $user = auth()->user();

        $warung = $user->warung;
        Invoice_detail::where(['warung_id' => $warung->id, 'invoice_id' => $request->invoice_id])
            ->update([
                'status' => 2
            ]);

        session()->flash('status', 'Transaksi Berhasil dibayar');

        return redirect()->back();
    }

    public function printPartnerTransaction(Request $request, $id) {

        $warung = Warung::find($id);
        return view('partner.print', [
            'warung'    => $warung,
            'invoice'   => $request,
            'partner'   => $request->partner,
            'date'      => Carbon::now()->format("d M Y H:i:s")
        ]);
    }
}
