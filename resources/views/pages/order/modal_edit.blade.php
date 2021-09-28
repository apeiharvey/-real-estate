@if($selected->update_order != null)
<div class="modal fade" id="modal_edit" role="dialog" aria-labelledby="exampleModalSizeSm" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body text-center">
                <h5 class="text-left font-weight-boldest">Note : {{$selected->update_order->status_note}}</h5>
                <div class="row justify-content-center py-4 px-4 py-md-6 px-md-0">
                    <div class="col-md-10">
                        <div class="table-responsive">
                            <table class="table font-size-sm">
                                <thead>
                                <tr>
                                    <th class="pl-0 font-weight-bold text-muted text-uppercase" width="250px">Produk</th>
                                    <th class="text-right font-weight-bold text-muted text-uppercase">Qty</th>
                                    <th class="text-right font-weight-bold text-muted text-uppercase">Harga Satuan</th>
                                    <th class="text-right pr-0 font-weight-bold text-muted text-uppercase">Subtotal</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($selected->update_order->update_detail as $item)
                                    <tr class="font-weight-boldest">
                                        <td class="border-0 pl-0 pt-7 d-flex">
                                            <div class="d-flex flex-column flex-root">
                                                <div class="d-flex align-items-center">
                                                    <!--begin::Symbol-->
                                                    <div class="symbol symbol-40 flex-shrink-0 mr-4 bg-light">
                                                        <div class="symbol-label" style="background-image: url('{{asset($url['disk']->url('').$item->product->image)}}')"></div>
                                                    </div>
                                                    <!--end::Symbol-->
                                                    {{$item->product->name}}
                                                </div>
                                                @if($item->user_cart != null)
                                                    <div class="mt-4">
                                                        <button data-index="{{Crypt::encryptString($item->user_cart->id)}}" class="btn btn-sm btn-light-primary btn-nego">Lihat Nego</button>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-right pt-10"><span class="text-dark-50"><strike>{{$item->quantity_before}}</strike></span><br>{{$item->quantity}}</td>
                                        <td class="text-right pt-10">Rp. {{number_format(($item->price),0,",",".")}}</td>
                                        <td class="text-primary pr-0 pt-10 text-right">
                                            <span class="text-dark-50"><strike>RP. {{number_format($item->quantity_before*$item->price,0,",",".")}}</strike></span><br>
                                            RP. {{number_format($item->quantity*$item->price,0,",",".")}}
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="2" class="text-right pt-7 align-middle">Subtotal <br>(termasuk pajak)</td>
                                    <td colspan="2" class="font-weight-boldest text-primary pr-0 pt-7 text-right align-middle">
                                        <span class="text-dark-50"><strike>Rp. {{number_format($selected->total_price,0,",",".")}}</strike></span><br>
                                        Rp. {{number_format($selected->update_order->total_price,0,",",".")}}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-right pt-7 align-middle">Pajak</td>
                                    <td colspan="2" class="font-weight-boldest text-primary pr-0 pt-7 text-right align-middle">
                                        <span class="text-dark-50"><strike>Rp. {{number_format($selected->total_tax_vendor,0,",",".")}}</strike></span><br>
                                        Rp. {{number_format($selected->update_order->total_tax_vendor,0,",",".")}}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-right pt-7 align-middle">Ongkir</td>
                                    <td colspan="2" class="font-weight-boldest text-primary pr-0 pt-7 text-right align-middle">
                                        <span class="text-dark-50"><strike>Rp. {{number_format($selected->delivery_fee,0,",",".")}}</strike></span><br>
                                        Rp. {{number_format($selected->update_order->delivery_fee,0,",",".")}}
                                    </td>
                                </tr>
                                <tr class="font-weight-boldest">
                                    <td colspan="2" class="text-right pt-7 align-middle">Total</td>
                                    <td colspan="2" class="text-primary pr-0 pt-7 text-right align-middle font-size-h5">
                                        <span class="text-dark-50"><strike>Rp. {{number_format($selected->total_price+$selected->delivery_fee,0,",",".")}}</strike></span><br>
                                        Rp. {{number_format($selected->update_order->total_price+$selected->update_order->delivery_fee,0,",",".")}}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div id="modal-footer-nego" class="modal-footer">
                <button id="btn_update_approved" type="button" class="btn btn-success btn-cancel btn-update-action font-weight-bold">Terima</button>
                <button id="btn_update_rejected" type="button" class="btn btn-danger btn-cancel btn-update-action font-weight-bold">Tolak</button>
                <button type="button" class="btn btn-secondary btn-cancel font-weight-bold" data-dismiss="modal" >Close</button>
            </div>
        </div>
    </div>
</div>
@endif
