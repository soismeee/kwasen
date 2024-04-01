<div class="modal fade" id="modalStatus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="judul">Ubah status pengguna</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="form_status">
                    @csrf
                    <div class="modal-body">
                        <p class="keterangan">Ketrangan informasi</p>
                        <input type="hidden" name="id" id="id">
                        <input type="hidden" name="status" id="status">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                        <button class="btn tombol" type="submit" data-dismiss="modal">Info</button>
                    </div>
                </form>
            </div>
        </div>
    </div>