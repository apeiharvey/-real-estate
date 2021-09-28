<div style="display:none" class="modal fade" id="negoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCustomScrollable" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Riwayat Negosiasi </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div data-scroll="true" data-height="300">
                    <div id="scrollContent" class="scroll-body pt-2">

                    </div>
                </div>
            </div>

            <div id="modal-footer-nego" class="modal-footer">
                <div class="action-nego-container d-none row my-3">
                    <div class="col-12 mb-3">
                        <input id="nego_price_inp" type="text" class="form-control" placeholder="Masukkan Harga Nego Jika Menawar" />
                        <div class="font-weight-base font-size-sm mt-2"><b>HARGA NEGO</b> tidak perlu dimasukkan jika <b>TERIMA</b> atau <b>TOLAK</b> tawaran</div>
                        <div class="hide invalid-feedback">Anda harus memasukan harga nego</div>
                    </div>
                    <div class="col-12">
                        <textarea id="nego_note_inp" style="resize: none" class="form-control" placeholder="Note" rows="3"></textarea>
                    </div>

                </div>
                <button type="button" class="btn btn-primary btn-nego-action font-weight-bold d-none" data-action="menawar">Tawar</button>
                <button type="button" class="btn btn-success btn-nego-action font-weight-bold d-none" data-action="menerima">Terima</button>
                <button type="button" class="btn btn-danger btn-nego-action font-weight-bold d-none" data-action="menolak">Tolak</button>
                <button type="button" class="btn btn-secondary btn-cancel btn-nego-action font-weight-bold" data-dismiss="modal" data-action="cancel">Close</button>
            </div>
        </div>
    </div>
</div>
